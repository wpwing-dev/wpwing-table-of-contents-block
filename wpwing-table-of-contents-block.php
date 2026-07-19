<?php

/**
 * Plugin Name:        Table of Contents (TOC) Block - Fast & SEO Friendly
 * Plugin URI:         https://wpwing.com/
 * Description:        Automated, ultra-fast Table of Contents block built to boost SEO and readability with zero frontend JavaScript.
 * Version:            1.7.0
 * Requires at least:  5.8
 * Tested up to:       7.0
 * Requires PHP:       7.1
 * Author:             WPWing
 * Author URI:         https://wpwing.com/
 * License:            GPL-3.0-or-later
 * License URI:        https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:        wpwing-table-of-contents-block
 * Domain Path:        /languages
 */

/**
 * Initalise frontend and backend and register block
 *
 * @since 1.0.0
 */
function wpwing_toc_register_block() {
	add_filter( 'plugin_row_meta', 'wpwing_toc_plugin_meta', 10, 2 );
	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'wpwing_toc_plugin_action_links' );

	register_block_type( __DIR__ . '/build', [
		'render_callback' => 'wpwing_toc_render_callback',
	] );
}

add_action( 'init', 'wpwing_toc_register_block' );

function wpwing_toc_get_align_class( $attributes ) {
	return isset( $attributes['align'] ) ? 'align' . $attributes['align'] : '';
}

function wpwing_toc_get_blocks( $post_content ) {
	static $cache = [];
	$key = md5( $post_content );
	if ( ! array_key_exists( $key, $cache ) ) {
		$cache[ $key ] = parse_blocks( $post_content );
	}
	return $cache[ $key ];
}

/**
 * Add meta information in plugin list
 *
 * @since 1.0.0
 */
function wpwing_toc_plugin_meta( $links, $file ) {
	if ( false !== strpos( $file, 'wpwing-table-of-contents-block' ) ) {
		$links = array_merge( $links, ['<a href="https://wordpress.org/support/plugin/wpwing-table-of-contents-block/">' . __( 'Support', 'wpwing-table-of-contents-block' ) . '</a>'] );
		$links = array_merge( $links, ['<a href="https://wordpress.org/support/plugin/wpwing-table-of-contents-block/reviews/#new-post">' . __( 'Write a review', 'wpwing-table-of-contents-block' ) . '&nbsp;⭐️⭐️⭐️⭐️⭐️</a>'] );
		$links = array_merge( $links, ['<a href="' . esc_url( plugins_url( 'docs/index.html', __FILE__ ) ) . '" target="_blank">' . __( 'Documentation', 'wpwing-table-of-contents-block' ) . '</a>'] );
	}

	return $links;
}

// Add a Settings link on the plugins list row.
function wpwing_toc_plugin_action_links( $links ) {
	array_unshift( $links, '<a href="' . esc_url( admin_url( 'options-general.php?page=wpwing-toc' ) ) . '">' . __( 'Settings', 'wpwing-table-of-contents-block' ) . '</a>' );

	return $links;
}

/**
 * Plugin settings. Stored as one option array so future keys (global block
 * defaults in 1.8.0) can be added without a new option.
 *
 * @since 1.7.0
 */
function wpwing_toc_default_settings() {
	return [
		'auto_insert_post_types'   => [],
		'auto_insert_position'     => 'before_first_heading',
		'auto_insert_min_headings' => 2,
	];
}

// Saved settings merged over defaults.
function wpwing_toc_get_settings() {
	$saved = get_option( 'wpwing_toc_settings', [] );

	return wp_parse_args( is_array( $saved ) ? $saved : [], wpwing_toc_default_settings() );
}

// Public post types eligible for auto-insert (attachments excluded).
function wpwing_toc_insertable_post_types() {
	$types = get_post_types( [ 'public' => true ], 'objects' );
	unset( $types['attachment'] );

	return $types;
}

function wpwing_toc_register_settings() {
	register_setting( 'wpwing_toc_settings', 'wpwing_toc_settings', [
		'type'              => 'array',
		'sanitize_callback' => 'wpwing_toc_sanitize_settings',
		'default'           => wpwing_toc_default_settings(),
	] );
}
add_action( 'admin_init', 'wpwing_toc_register_settings' );

function wpwing_toc_sanitize_settings( $input ) {
	$defaults = wpwing_toc_default_settings();
	$input    = is_array( $input ) ? $input : [];

	$requested = isset( $input['auto_insert_post_types'] ) && is_array( $input['auto_insert_post_types'] ) ? $input['auto_insert_post_types'] : [];
	$positions = [ 'top', 'before_first_heading', 'after_first_paragraph' ];
	$position  = isset( $input['auto_insert_position'] ) ? $input['auto_insert_position'] : '';

	return [
		'auto_insert_post_types'   => array_values( array_intersect( $requested, array_keys( wpwing_toc_insertable_post_types() ) ) ),
		'auto_insert_position'     => in_array( $position, $positions, true ) ? $position : $defaults['auto_insert_position'],
		'auto_insert_min_headings' => isset( $input['auto_insert_min_headings'] ) ? max( 1, (int) $input['auto_insert_min_headings'] ) : $defaults['auto_insert_min_headings'],
	];
}

function wpwing_toc_add_settings_page() {
	add_options_page(
		__( 'Table of Contents Block', 'wpwing-table-of-contents-block' ),
		__( 'TOC Block', 'wpwing-table-of-contents-block' ),
		'manage_options',
		'wpwing-toc',
		'wpwing_toc_render_settings_page'
	);
}
add_action( 'admin_menu', 'wpwing_toc_add_settings_page' );

function wpwing_toc_render_settings_page() {
	$settings  = wpwing_toc_get_settings();
	$positions = [
		'before_first_heading'  => __( 'Before the first heading', 'wpwing-table-of-contents-block' ),
		'after_first_paragraph' => __( 'After the first paragraph', 'wpwing-table-of-contents-block' ),
		'top'                   => __( 'Top of the content', 'wpwing-table-of-contents-block' ),
	];
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Table of Contents Block', 'wpwing-table-of-contents-block' ); ?></h1>
		<form method="post" action="options.php">
			<?php settings_fields( 'wpwing_toc_settings' ); ?>
			<h2><?php esc_html_e( 'Auto-insert', 'wpwing-table-of-contents-block' ); ?></h2>
			<p><?php esc_html_e( 'Show a Table of Contents automatically, without adding the block to each post. Posts that already contain a TOC block keep their own block and are skipped.', 'wpwing-table-of-contents-block' ); ?></p>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><?php esc_html_e( 'Enable for post types', 'wpwing-table-of-contents-block' ); ?></th>
					<td>
						<fieldset>
							<?php foreach ( wpwing_toc_insertable_post_types() as $slug => $type ) : ?>
								<label style="display:block;margin-bottom:4px;">
									<input type="checkbox" name="wpwing_toc_settings[auto_insert_post_types][]" value="<?php echo esc_attr( $slug ); ?>" <?php checked( in_array( $slug, $settings['auto_insert_post_types'], true ) ); ?> />
									<?php echo esc_html( $type->labels->name ); ?>
								</label>
							<?php endforeach; ?>
							<p class="description"><?php esc_html_e( 'No post type checked means auto-insert is off.', 'wpwing-table-of-contents-block' ); ?></p>
						</fieldset>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="wpwing-toc-position"><?php esc_html_e( 'Position', 'wpwing-table-of-contents-block' ); ?></label></th>
					<td>
						<select id="wpwing-toc-position" name="wpwing_toc_settings[auto_insert_position]">
							<?php foreach ( $positions as $value => $label ) : ?>
								<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $settings['auto_insert_position'], $value ); ?>><?php echo esc_html( $label ); ?></option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="wpwing-toc-min-headings"><?php esc_html_e( 'Minimum headings', 'wpwing-table-of-contents-block' ); ?></label></th>
					<td>
						<input type="number" id="wpwing-toc-min-headings" name="wpwing_toc_settings[auto_insert_min_headings]" min="1" step="1" value="<?php echo esc_attr( $settings['auto_insert_min_headings'] ); ?>" />
						<p class="description"><?php esc_html_e( 'Skip posts with fewer qualifying headings, so short posts stay clean.', 'wpwing-table-of-contents-block' ); ?></p>
					</td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}

/**
 * Render block output
 *
 * @since 1.0.0
 */
function wpwing_toc_render_callback( $attributes ) {
	$is_backend = defined( 'REST_REQUEST' ) && true === REST_REQUEST && 'edit' === filter_input( INPUT_GET, 'context' );

	$alignClass = wpwing_toc_get_align_class( $attributes );

	// Get all the blocks from post content
	$post = get_post();
	if ( ! $post ) {
		return '';
	}
	$blocks = wpwing_toc_get_blocks( $post->post_content );

	$effective_title = ! empty( $attributes['title_text'] ) ? $attributes['title_text'] : __( 'Table of Contents', 'wpwing-table-of-contents-block' );

	// If no block found
	if ( empty( $blocks ) ) {
		$html = '';
		if ( $is_backend === true ) {
			if ( $attributes['no_title'] === false ) {
				$html = '<h2 class="wpwing-toc-title ' . esc_attr( $alignClass ) . '">' . esc_html( $effective_title ) . '</h2>';
			}
			$html .= '<p class="components-notice is-warning ' . esc_attr( $alignClass ) . '">' . __( 'No content found. Save or update the post first.', 'wpwing-table-of-contents-block' ) . '</p>';
		}

		return $html;
	}

	$headings = wpwing_toc_filter_headings_recursive( $blocks );

	// Enrich headings with pages as a data-attribute
	$headings = wpwing_toc_add_pagenumber( $blocks, $headings );

	$headings_clean = array_map( 'trim', $headings );

	if ( empty( $headings_clean ) ) {
		$html = '';
		if ( $is_backend === true ) {
			if ( $attributes['no_title'] === false ) {
				$html = '<h2 class="wpwing-toc-title ' . esc_attr( $alignClass ) . '">' . esc_html( $effective_title ) . '</h2>';
			}

			$html .= '<p class="components-notice is-warning ' . esc_attr( $alignClass ) . '">' . __( 'Add Heading blocks to your post and the TOC will appear here.', 'wpwing-table-of-contents-block' ) . '</p>';
		}

		return $html;
	}

	$min_headings = isset( $attributes['min_headings'] ) ? (int) $attributes['min_headings'] : 1;
	if ( $min_headings > 1 ) {
		$min_lv           = (int) $attributes['min_level'];
		$max_lv           = (int) $attributes['max_level'];
		$kw_for_threshold = array_filter( array_map( 'trim', explode( ',', isset( $attributes['exclude_keywords'] ) ? $attributes['exclude_keywords'] : '' ) ) );
		$eligible         = 0;
		foreach ( $headings_clean as $headline ) {
			if ( ! wpwing_toc_heading_excluded( $headline, $min_lv, $max_lv, $kw_for_threshold ) ) {
				$eligible++;
			}
		}
		if ( $eligible < $min_headings ) {
			if ( $is_backend ) {
				/* translators: 1: current heading count, 2: required minimum */
				return '<p class="components-notice is-info ' . esc_attr( $alignClass ) . '">' . sprintf( __( 'TOC hidden: %1$d qualifying heading(s) found, minimum required is %2$d.', 'wpwing-table-of-contents-block' ), $eligible, $min_headings ) . '</p>';
			}
			return '';
		}
	}

	if ( $attributes['add_smooth'] === true && ! $is_backend ) {
		add_action( 'wp_footer', 'wpwing_toc_print_smooth_scroll_style' );
	}

	if ( ! empty( $attributes['scroll_offset'] ) && (int) $attributes['scroll_offset'] > 0 && ! $is_backend ) {
		wpwing_toc_scroll_offset( (int) $attributes['scroll_offset'] );
		add_action( 'wp_footer', 'wpwing_toc_print_scroll_offset_style' );
	}

	if ( ( ! empty( $attributes['collapsible'] ) || ! empty( $attributes['show_back_to_top'] ) || ! empty( $attributes['add_back_to_top'] ) || ! empty( $attributes['copy_anchor'] ) ) && ! $is_backend ) {
		add_action( 'wp_footer', 'wpwing_toc_print_frontend_script' );
	}

	return wpwing_toc_generate_toc( $headings_clean, $attributes );
}

/**
 * Print smooth scroll CSS in the footer when the toggle is enabled.
 * Named function so add_action deduplicates it if multiple TOC blocks are on the page.
 *
 * @since 1.0.0
 */
function wpwing_toc_print_smooth_scroll_style() {
	echo '<style>html{scroll-behavior:smooth}</style>';
}

// Holds the largest scroll offset requested by any TOC block on the page.
function wpwing_toc_scroll_offset( $set = null ) {
	static $offset = 0;
	if ( null !== $set ) {
		$offset = max( $offset, (int) $set );
	}
	return $offset;
}

/**
 * Print scroll offset CSS so anchors are not hidden under sticky/fixed headers.
 * Named function so add_action deduplicates it if multiple TOC blocks are on the page.
 *
 * @since 1.6.0
 */
function wpwing_toc_print_scroll_offset_style() {
	$offset = wpwing_toc_scroll_offset();
	if ( $offset > 0 ) {
		echo '<style>h1[id],h2[id],h3[id],h4[id],h5[id],h6[id]{scroll-margin-top:' . (int) $offset . 'px}</style>';
	}
}

/**
 * Print interactive feature JS in the footer (collapsible toggle + back to top).
 * Named function so add_action deduplicates it if multiple TOC blocks are on the page.
 *
 * @since 1.1.0
 */
function wpwing_toc_print_frontend_script() {
	?>
	<script>
	(function () {
		document.querySelectorAll('.wpwing-toc--collapsible').forEach(function (toc) {
			var btn  = toc.querySelector('.wpwing-toc-toggle');
			var list = toc.querySelector('.wpwing-toc-list');
			if ( btn && list ) {
				// Collapse via JS so no-JS readers still see the full list
				if (toc.classList.contains('wpwing-toc--start-collapsed')) {
					btn.setAttribute('aria-expanded', 'false');
					list.hidden = true;
				}
				btn.addEventListener('click', function () {
					var expanded = btn.getAttribute('aria-expanded') === 'true';
					btn.setAttribute('aria-expanded', String( ! expanded));
					list.hidden = expanded;
				});
			}
		});

		var smoothEnabled = !! document.querySelector('.wpwing-toc a.smooth-scroll');
		document.querySelectorAll('.wpwing-toc-back-top').forEach(function (link) {
			link.addEventListener('click', function (e) {
				e.preventDefault();
				window.scrollTo({ top: 0, behavior: smoothEnabled ? 'smooth' : 'auto' });
			});
		});

		document.querySelectorAll('.wpwing-toc-copy').forEach(function (btn) {
			btn.addEventListener('click', function () {
				var url = btn.getAttribute('data-clipboard-text');
				if ( ! url || ! navigator.clipboard ) {
					return;
				}
				navigator.clipboard.writeText(url).then(function () {
					btn.classList.add('is-copied');
					setTimeout(function () { btn.classList.remove('is-copied'); }, 1500);
				});
			});
		});
	})();
	</script>
	<?php
}

/**
 * Return all headings with a recursive walk through all blocks.
 * This includes groups and reusable block with groups within reusable blocks.
 *
 * @since 1.0.0
 */
function wpwing_toc_filter_headings_recursive( array $blocks ) {
	$arr = [];
	foreach ( $blocks as $block ) {
		if ( ! is_array( $block ) || ! isset( $block['blockName'] ) ) {
			continue;
		}
		if ( $block['blockName'] === 'core/heading' && ! empty( $block['innerHTML'] ) ) {
			if ( preg_match( '/(<h[1-6])/i', $block['innerHTML'] ) ) {
				$arr[] = $block['innerHTML'];
			}
		} elseif ( $block['blockName'] === 'core/block' && isset( $block['attrs']['ref'] ) ) {
			$reusable_post = get_post( $block['attrs']['ref'] );
			if ( $reusable_post ) {
				$arr = array_merge( $arr, wpwing_toc_filter_headings_recursive( wpwing_toc_get_blocks( $reusable_post->post_content ) ) );
			}
		} elseif ( ! empty( $block['innerBlocks'] ) ) {
			$arr = array_merge( $arr, wpwing_toc_filter_headings_recursive( $block['innerBlocks'] ) );
		}
	}
	return $arr;
}

/**
 * Headings with pages as a data-attribute
 *
 * @since 1.0.0
 */
function wpwing_toc_add_pagenumber( array $blocks, array $headings, int $pages = 1 ) {
	foreach ( $blocks as $block ) {
		if ( ! is_array( $block ) || ! isset( $block['blockName'] ) ) {
			continue;
		}
		if ( $block['blockName'] === 'core/nextpage' ) {
			$pages++;
		} elseif ( $block['blockName'] === 'core/heading' && ! empty( $block['innerHTML'] ) ) {
			foreach ( $headings as &$innerHeading ) {
				if ( $innerHeading === $block['innerHTML'] ) {
					$innerHeading = preg_replace( '/(<h[1-6])/i', '$1 data-page="' . $pages . '"', $block['innerHTML'] );
				}
			}
			unset( $innerHeading );
		}
		if ( ! empty( $block['innerBlocks'] ) ) {
			$headings = wpwing_toc_add_pagenumber( $block['innerBlocks'], $headings, $pages );
		}
	}
	return $headings;
}

// Recursively search parsed blocks for a named attribute on a specific block type.
function wpwing_toc_get_block_attr( array $blocks, $block_name, $attr_name, $default = null ) {
	foreach ( $blocks as $block ) {
		if ( isset( $block['blockName'] ) && $block['blockName'] === $block_name ) {
			return isset( $block['attrs'][ $attr_name ] ) ? $block['attrs'][ $attr_name ] : $default;
		}
		if ( ! empty( $block['innerBlocks'] ) ) {
			$found = wpwing_toc_get_block_attr( $block['innerBlocks'], $block_name, $attr_name, $default );
			if ( null !== $found ) {
				return $found;
			}
		}
	}
	return $default;
}

// True when a heading should be skipped from the TOC (out of level range, hidden class, or keyword match).
function wpwing_toc_heading_excluded( $headline, $min_level, $max_level, array $exclude_keywords ) {
	$level = (int) $headline[2];
	if ( $level < $min_level || $level > $max_level ) {
		return true;
	}
	if ( strpos( $headline, 'wpwing-toc-hidden' ) !== false ) {
		return true;
	}
	if ( ! empty( $exclude_keywords ) ) {
		$text = strip_tags( $headline );
		foreach ( $exclude_keywords as $kw ) {
			if ( $kw !== '' && stripos( $text, $kw ) !== false ) {
				return true;
			}
		}
	}
	return false;
}

// Return the custom HTML anchor already set on a heading tag (via the block's Advanced panel), or ''.
// Used by BOTH the content-side ID writer and the TOC link builder; they must stay in lockstep.
function wpwing_toc_existing_anchor( $html ) {
	if ( preg_match( '/<h[1-6][^>]*\bid=(["\'])(.*?)\1/i', $html, $m ) ) {
		return $m[2];
	}
	return '';
}

// Return a document-unique anchor, appending -2, -3 ... on collision. $seen tracks counts by base.
function wpwing_toc_unique_anchor( $base, array &$seen ) {
	if ( ! isset( $seen[ $base ] ) ) {
		$seen[ $base ] = 0;
		return $base;
	}
	$seen[ $base ]++;
	return $base . '-' . $seen[ $base ];
}

// True when auto-insert should produce a TOC for this post; a manually placed block wins.
function wpwing_toc_auto_insert_applies( $post = null ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return false;
	}
	$settings = wpwing_toc_get_settings();
	if ( ! in_array( $post->post_type, $settings['auto_insert_post_types'], true ) ) {
		return false;
	}
	if ( has_block( 'wpwing/toc', $post ) ) {
		return false;
	}

	return has_blocks( $post->post_content );
}

// Index in the top-level block list where the auto-inserted TOC goes.
function wpwing_toc_auto_insert_index( array $blocks, $position ) {
	if ( 'top' !== $position ) {
		$target = ( 'after_first_paragraph' === $position ) ? 'core/paragraph' : 'core/heading';
		foreach ( $blocks as $i => $block ) {
			if ( isset( $block['blockName'] ) && $target === $block['blockName'] ) {
				return ( 'core/paragraph' === $target ) ? $i + 1 : $i;
			}
		}
	}

	return 0;
}

/**
 * Insert a TOC block into the content when auto-insert is enabled for the post type.
 * Runs before block rendering (priority 0) so the inserted block goes through the
 * exact same pipeline as a manually placed block: anchor IDs, min_headings, no JS.
 *
 * @since 1.7.0
 */
function wpwing_toc_auto_insert_block( $content ) {
	global $page;

	if ( ! is_singular() || ! is_main_query() || is_admin() || doing_filter( 'get_the_excerpt' ) ) {
		return $content;
	}
	// Paginated posts get the TOC on the first page only; its links cross pages
	if ( (int) $page > 1 ) {
		return $content;
	}
	// Skip if this content already carries a TOC (block markup or rendered list);
	// must not match wpwing-toc-hidden, which users put on excluded headings
	if ( has_block( 'wpwing/toc', $content ) || false !== strpos( $content, 'wpwing-toc-list' ) ) {
		return $content;
	}
	if ( ! wpwing_toc_auto_insert_applies() ) {
		return $content;
	}

	$settings = wpwing_toc_get_settings();
	$blocks   = parse_blocks( $content );
	$toc      = [
		'blockName'    => 'wpwing/toc',
		'attrs'        => [ 'min_headings' => (int) $settings['auto_insert_min_headings'] ],
		'innerBlocks'  => [],
		'innerHTML'    => '',
		'innerContent' => [],
	];
	array_splice( $blocks, wpwing_toc_auto_insert_index( $blocks, $settings['auto_insert_position'] ), 0, [ $toc ] );

	return serialize_blocks( $blocks );
}
add_filter( 'the_content', 'wpwing_toc_auto_insert_block', 0 );

/**
 * Add IDs to the H1-6 content
 *
 * @since 1.0.0
 */
function wpwing_toc_add_ids_to_content( $content ) {
	if ( has_block( 'wpwing/toc', get_the_ID() ) || wpwing_toc_auto_insert_applies() ) {
		$blocks           = parse_blocks( $content );
		$add_back_to_top  = (bool) wpwing_toc_get_block_attr( $blocks, 'wpwing/toc', 'add_back_to_top', false );
		$exclude_keywords = array_filter( array_map( 'trim', explode( ',', (string) wpwing_toc_get_block_attr( $blocks, 'wpwing/toc', 'exclude_keywords', '' ) ) ) );
		$seen             = [];
		$blocks           = wpwing_toc_add_ids_to_blocks( $blocks, $add_back_to_top, $exclude_keywords, $seen );
		$content          = serialize_blocks( $blocks );
	}

	return $content;
}

// Recursively walk all blocks (including nested groups) and add anchor IDs to headings.
function wpwing_toc_add_ids_to_blocks( array $blocks, bool $add_back_to_top = false, array $exclude_keywords = [], array &$seen = [] ) {
	$back_to_top_link = $add_back_to_top
		? '<a href="#top" class="wpwing-toc-back-top">' . esc_html__( 'Back to top', 'wpwing-table-of-contents-block' ) . '</a>'
		: '';

	foreach ( $blocks as &$block ) {
		if ( isset( $block['blockName'] ) && $block['blockName'] === 'core/heading' && ! empty( $block['innerHTML'] ) && isset( $block['innerContent'][0] ) ) {
			$keyword_excluded = false;
			if ( $back_to_top_link && ! empty( $exclude_keywords ) ) {
				$heading_text = strip_tags( $block['innerHTML'] );
				foreach ( $exclude_keywords as $kw ) {
					if ( $kw !== '' && stripos( $heading_text, $kw ) !== false ) {
						$keyword_excluded = true;
						break;
					}
				}
			}
			$link                     = ( $back_to_top_link && strpos( $block['innerHTML'], 'wpwing-toc-hidden' ) === false && ! $keyword_excluded ) ? $back_to_top_link : '';
			// A custom anchor set on the heading wins; only generated anchors advance the dedup counter
			$anchor = wpwing_toc_existing_anchor( $block['innerHTML'] );
			if ( '' === $anchor ) {
				$anchor = wpwing_toc_unique_anchor( wpwing_toc_sanitize_string( strip_tags( $block['innerHTML'] ) ), $seen );
			}
			$block['innerHTML']       = wpwing_toc_apply_anchor( $block['innerHTML'], $anchor ) . $link;
			$block['innerContent'][0] = wpwing_toc_apply_anchor( $block['innerContent'][0], $anchor ) . $link;
		} elseif ( ! empty( $block['innerBlocks'] ) ) {
			$block['innerBlocks'] = wpwing_toc_add_ids_to_blocks( $block['innerBlocks'], $add_back_to_top, $exclude_keywords, $seen );
		}
	}

	return $blocks;
}

add_filter( 'the_content', 'wpwing_toc_add_ids_to_content', 1 );

// Set a known anchor id on every heading tag in the given HTML fragment.
function wpwing_toc_apply_anchor( $html, $anchor ) {
	// Remove non-breaking space entites from input HTML
	$html_wo_nbsp = str_replace( "&nbsp;", " ", $html );

	if (  ! $html_wo_nbsp ) {
		return $html;
	}

	libxml_use_internal_errors( true );
	$dom = new \DOMDocument();
	// The encoding prolog stops libxml treating UTF-8 input as Latin-1 (mojibake)
	$dom->loadHTML( '<?xml encoding="utf-8" ?>' . $html_wo_nbsp, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
	libxml_clear_errors();

	// Use xpath to select the Heading html tags.
	$xpath = new \DOMXPath( $dom );
	$tags  = $xpath->evaluate( "//*[self::h1 or self::h2 or self::h3 or self::h4 or self::h5 or self::h6]" );

	foreach ( $tags as $tag ) {
		$tag->setAttribute( 'id', $anchor );
	}

	// Save the HTML changes
	$content = $dom->saveHTML( $dom->documentElement );

	return $content;
}

// Full URL of a given page of the current paginated post; mirrors core _wp_link_page().
function wpwing_toc_page_url( $page_num ) {
	global $wp_rewrite;
	$permalink = get_permalink();
	if ( $page_num <= 1 ) {
		return $permalink;
	}
	$post = get_post();
	if ( ! $wp_rewrite instanceof WP_Rewrite || ! $wp_rewrite->using_permalinks() || ( $post && in_array( $post->post_status, [ 'draft', 'pending' ], true ) ) ) {
		return add_query_arg( 'page', $page_num, $permalink );
	}
	if ( 'page' === get_option( 'show_on_front' ) && $post && (int) get_option( 'page_on_front' ) === $post->ID ) {
		return trailingslashit( $permalink ) . user_trailingslashit( $wp_rewrite->pagination_base . '/' . $page_num, 'single_paged' );
	}
	return trailingslashit( $permalink ) . user_trailingslashit( $page_num, 'single_paged' );
}

/**
 * Generate final Table of Contents
 *
 * @since 1.0.0
 */
function wpwing_toc_generate_toc( $headings, $attributes ) {
	static $toc_instance = 0;
	$toc_instance++;

	$list = '';

	$hierarchical = ! empty( $attributes['hierarchical_numbers'] );
	$listtype     = ( $attributes['use_ol'] === true && ! $hierarchical ) ? 'ol' : 'ul';
	$absolute_url = $attributes['use_absolute_urls'] === true ? get_permalink() : '';
	$link_class   = $attributes['add_smooth'] === true ? 'smooth-scroll' : '';
	$flat_class   = ! empty( $attributes['remove_indent'] ) ? 'wpwing-toc-list--flat' : '';

	$collapsible      = ! empty( $attributes['collapsible'] );
	$copy_anchor      = ! empty( $attributes['copy_anchor'] );
	$permalink        = $copy_anchor ? get_permalink() : '';
	$style_preset     = isset( $attributes['style_preset'] ) ? $attributes['style_preset'] : 'default';
	$show_back_to_top = ! empty( $attributes['show_back_to_top'] );
	$list_id          = $collapsible ? 'wpwing-toc-list-' . $toc_instance : '';

	$min_level          = (int) $attributes['min_level'];
	$max_level          = (int) $attributes['max_level'];
	$show_heading_count = ! empty( $attributes['show_heading_count'] );
	$exclude_keywords   = array_filter( array_map( 'trim', explode( ',', isset( $attributes['exclude_keywords'] ) ? $attributes['exclude_keywords'] : '' ) ) );
	$seen               = [];

	$eligible_count = 0;
	if ( $show_heading_count ) {
		foreach ( $headings as $headline ) {
			if ( ! wpwing_toc_heading_excluded( $headline, $min_level, $max_level, $exclude_keywords ) ) {
				$eligible_count++;
			}
		}
	}

	// Collect visible items. Anchors are deduped across every heading (in document order)
	// so the hrefs stay in lockstep with the IDs added to the content by the_content filter.
	$items = [];
	foreach ( $headings as $line => $headline ) {
		$title = strip_tags( $headline );
		// Mirror of the content-side logic: custom anchor wins, generated ones advance the counter
		$link  = wpwing_toc_existing_anchor( $headline );
		if ( '' === $link ) {
			$link = wpwing_toc_unique_anchor( wpwing_toc_sanitize_string( $title ), $seen );
		}

		if ( wpwing_toc_heading_excluded( $headline, $min_level, $max_level, $exclude_keywords ) ) {
			continue;
		}

		libxml_use_internal_errors( true );
		$dom = new \DOMDocument();
		$dom->loadHTML( $headline, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
		libxml_clear_errors();
		$xpath    = new \DOMXPath( $dom );
		$nodes    = $xpath->query( '//*/@data-page' );
		$page_num = isset( $nodes[0] ) ? (int) $nodes[0]->nodeValue : 1;
		$base_url = ( $page_num > 1 ) ? wpwing_toc_page_url( $page_num ) : $absolute_url;

		$items[] = [
			'depth'  => (int) $headline[2],
			'title'  => $title,
			'link'   => $link,
			'page'   => $page_num,
			'href'   => esc_url( $base_url . '#' . $link ),
		];
	}

	if ( empty( $items ) ) {
		return '';
	}

	// Render items as a balanced nested list. A stack of heading depths tracks the open
	// <li> ancestors; level jumps (e.g. H2 -> H4) collapse to a single nesting step.
	$depth_stack = [];
	$counters    = [];
	foreach ( $items as $it ) {
		$depth = $it['depth'];

		if ( empty( $depth_stack ) ) {
			$list .= '<li>';
			$depth_stack[] = $depth;
		} elseif ( $depth > end( $depth_stack ) ) {
			$list .= "<{$listtype}><li>";
			$depth_stack[] = $depth;
		} else {
			while ( count( $depth_stack ) > 1 && $depth < end( $depth_stack ) ) {
				$list .= "</li></{$listtype}>";
				array_pop( $depth_stack );
			}
			$list .= '</li><li>';
			array_pop( $depth_stack );
			$depth_stack[] = $depth;
		}

		$number_html = '';
		if ( $hierarchical ) {
			$level              = count( $depth_stack );
			$counters[ $level ] = isset( $counters[ $level ] ) ? $counters[ $level ] + 1 : 1;
			foreach ( array_keys( $counters ) as $lvl ) {
				if ( $lvl > $level ) {
					unset( $counters[ $lvl ] );
				}
			}
			$parts = [];
			for ( $lvl = 1; $lvl <= $level; $lvl++ ) {
				$parts[] = isset( $counters[ $lvl ] ) ? $counters[ $lvl ] : 1;
			}
			$number_html = '<span class="wpwing-toc-number" aria-hidden="true">' . esc_html( implode( '.', $parts ) ) . '</span> ';
		}

		$copy_html = '';
		if ( $copy_anchor ) {
			$copy_url  = ( $it['page'] > 1 ) ? wpwing_toc_page_url( $it['page'] ) : $permalink;
			$copy_html = '<button type="button" class="wpwing-toc-copy" data-clipboard-text="' . esc_url( $copy_url . '#' . $it['link'] ) . '" aria-label="' . esc_attr__( 'Copy link to this section', 'wpwing-table-of-contents-block' ) . '"></button>';
		}

		$list .= '<a' . ( $link_class ? ' class="' . esc_attr( $link_class ) . '"' : '' ) . ' href="' . $it['href'] . '">' . $number_html . esc_html( $it['title'] ) . '</a>' . $copy_html;
	}

	for ( $level = count( $depth_stack ); $level > 0; $level-- ) {
		$list .= ( $level > 1 ) ? "</li></{$listtype}>" : '</li>';
	}

	if ( ! $list ) {
		return '';
	}

	$effective_title = ! empty( $attributes['title_text'] ) ? $attributes['title_text'] : __( 'Table of Contents', 'wpwing-table-of-contents-block' );

	$badge_html = '';
	if ( $show_heading_count && $eligible_count > 0 ) {
		$badge_html = '<span class="wpwing-toc-count">' . sprintf(
			_n( '%d heading', '%d headings', $eligible_count, 'wpwing-table-of-contents-block' ),
			$eligible_count
		) . '</span>';
	}

	// Build nav class list
	$nav_classes = array_filter( [
		'wpwing-toc',
		'boxed' === $style_preset ? 'wpwing-toc--boxed' : '',
		$collapsible ? 'wpwing-toc--collapsible' : '',
		$collapsible && ! empty( $attributes['start_collapsed'] ) ? 'wpwing-toc--start-collapsed' : '',
		$copy_anchor ? 'wpwing-toc--copyable' : '',
	] );

	$extra_attributes = [
		'class'      => implode( ' ', $nav_classes ),
		'aria-label' => $effective_title,
	];
	if ( ! empty( $attributes['seo_nosnippet'] ) ) {
		$extra_attributes['data-nosnippet'] = 'true';
	}
	$wrapper_attributes = get_block_wrapper_attributes( $extra_attributes );
	$html = '<nav ' . $wrapper_attributes . '>';

	if ( $collapsible ) {
		$html .= '<div class="wpwing-toc-header">';
		if ( $attributes['no_title'] === false ) {
			$html .= '<h2 class="wpwing-toc-title">' . esc_html( $effective_title ) . $badge_html . '</h2>';
		}
		$html .= '<button type="button" class="wpwing-toc-toggle" aria-expanded="true" aria-controls="' . esc_attr( $list_id ) . '">';
		$html .= '<span class="screen-reader-text">' . esc_html__( 'Toggle Table of Contents', 'wpwing-table-of-contents-block' ) . '</span>';
		$html .= '<span class="wpwing-toc-toggle-icon" aria-hidden="true"></span>';
		$html .= '</button>';
		$html .= '</div>';
	} elseif ( $attributes['no_title'] === false ) {
		$html .= '<h2 class="wpwing-toc-title">' . esc_html( $effective_title ) . $badge_html . '</h2>';
	}

	$list_id_attr = $list_id ? ' id="' . esc_attr( $list_id ) . '"' : '';
	$numbered_class = $hierarchical ? 'wpwing-toc-list--numbered' : '';
	$list_classes   = array_filter( [ 'wpwing-toc-list', $flat_class, $numbered_class ] );
	$html .= "<{$listtype} class=\"" . esc_attr( implode( ' ', $list_classes ) ) . "\"{$list_id_attr}>{$list}</{$listtype}>";

	if ( $show_back_to_top ) {
		$html .= '<a href="#top" class="wpwing-toc-back-top">' . esc_html__( 'Back to top', 'wpwing-table-of-contents-block' ) . '</a>';
	}

	$html .= '</nav>';

	return $html;
}

/**
 * Remove all problematic characters for toc links
 *
 * @since 1.0.0
 */
function wpwing_toc_sanitize_string( $string ) {
	// Remove punctuation
	$zero_punctuation = preg_replace( "/\p{P}/u", "", $string );
	// Remove non-breaking spaces
	$html_wo_nbsp = str_replace( "&nbsp;", " ", $zero_punctuation );
	// Remove umlauts and accents
	$string_without_accents = remove_accents( $html_wo_nbsp );
	// Sanitizes a title, replacing whitespace and a few other characters with dashes.
	$sanitized_string = sanitize_title_with_dashes( $string_without_accents );
	// Encode for use in an url
	$urlencoded = urlencode( $sanitized_string );

	return $urlencoded;
}

// When add_back_to_top is enabled, print a #top anchor at the start of the body for no-JS fallback.
function wpwing_toc_setup_top_anchor() {
	if ( ! is_singular() ) {
		return;
	}
	$post = get_post();
	if ( ! $post || ! has_block( 'wpwing/toc', $post->ID ) ) {
		return;
	}
	$blocks = wpwing_toc_get_blocks( $post->post_content );
	$needs_top = wpwing_toc_get_block_attr( $blocks, 'wpwing/toc', 'add_back_to_top', false )
		|| wpwing_toc_get_block_attr( $blocks, 'wpwing/toc', 'show_back_to_top', false );
	if ( $needs_top ) {
		add_action( 'wp_body_open', 'wpwing_toc_print_top_anchor' );
	}
}
add_action( 'wp', 'wpwing_toc_setup_top_anchor' );

function wpwing_toc_print_top_anchor() {
	echo '<span id="top" aria-hidden="true"></span>';
}

/**
 * Filter to add plugins to the TOC list for Rank Math plugin
 *
 * @param array TOC plugins.
 */
add_filter( 'rank_math/researches/toc_plugins', function ( $toc_plugins ) {
	$toc_plugins['wpwing-table-of-contents-block/wpwing-table-of-contents-block.php'] = 'WPWingTOC';

	return $toc_plugins;
} );

add_filter( 'wpseo_table_of_contents_blocks', function ( $blocks ) {
	$blocks[] = 'wpwing/toc';

	return $blocks;
} );

