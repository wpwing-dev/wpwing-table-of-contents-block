<?php

/**
 * Plugin Name:        Table of Contents (TOC) Block - Fast & SEO Friendly
 * Plugin URI:         https://wpwing.com/
 * Description:        Automated, ultra-fast Table of Contents block built to boost SEO and readability with zero frontend JavaScript.
 * Version:            1.3.0
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
	}

	return $links;
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
			$level = (int) $headline[2];
			if ( $level < $min_lv || $level > $max_lv || strpos( $headline, 'wpwing-toc-hidden' ) !== false ) {
				continue;
			}
			if ( ! empty( $kw_for_threshold ) ) {
				$hl_text = strip_tags( $headline );
				$matched = false;
				foreach ( $kw_for_threshold as $kw ) {
					if ( $kw !== '' && stripos( $hl_text, $kw ) !== false ) {
						$matched = true;
						break;
					}
				}
				if ( $matched ) {
					continue;
				}
			}
			$eligible++;
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

	if ( ( ! empty( $attributes['collapsible'] ) || ! empty( $attributes['show_back_to_top'] ) || ! empty( $attributes['add_back_to_top'] ) ) && ! $is_backend ) {
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

/**
 * Add IDs to the H1-6 content
 *
 * @since 1.0.0
 */
function wpwing_toc_add_ids_to_content( $content ) {
	if ( has_block( 'wpwing/toc', get_the_ID() ) ) {
		$blocks           = parse_blocks( $content );
		$add_back_to_top  = (bool) wpwing_toc_get_block_attr( $blocks, 'wpwing/toc', 'add_back_to_top', false );
		$exclude_keywords = array_filter( array_map( 'trim', explode( ',', (string) wpwing_toc_get_block_attr( $blocks, 'wpwing/toc', 'exclude_keywords', '' ) ) ) );
		$blocks           = wpwing_toc_add_ids_to_blocks( $blocks, $add_back_to_top, $exclude_keywords );
		$content          = serialize_blocks( $blocks );
	}

	return $content;
}

// Recursively walk all blocks (including nested groups) and add anchor IDs to headings.
function wpwing_toc_add_ids_to_blocks( array $blocks, bool $add_back_to_top = false, array $exclude_keywords = [] ) {
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
			$block['innerHTML']       = wpwing_toc_add_anchor_attribute( $block['innerHTML'] ) . $link;
			$block['innerContent'][0] = wpwing_toc_add_anchor_attribute( $block['innerContent'][0] ) . $link;
		} elseif ( ! empty( $block['innerBlocks'] ) ) {
			$block['innerBlocks'] = wpwing_toc_add_ids_to_blocks( $block['innerBlocks'], $add_back_to_top, $exclude_keywords );
		}
	}

	return $blocks;
}

add_filter( 'the_content', 'wpwing_toc_add_ids_to_content', 1 );

function wpwing_toc_add_anchor_attribute( $html ) {
	// Remove non-breaking space entites from input HTML
	$html_wo_nbsp = str_replace( "&nbsp;", " ", $html );

	if (  ! $html_wo_nbsp ) {
		return $html;
	}

	libxml_use_internal_errors( true );
	$dom = new \DOMDocument();
	$dom->loadHTML( $html_wo_nbsp, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
	libxml_clear_errors();

	// Use xpath to select the Heading html tags.
	$xpath = new \DOMXPath( $dom );
	$tags  = $xpath->evaluate( "//*[self::h1 or self::h2 or self::h3 or self::h4 or self::h5 or self::h6]" );

	foreach ( $tags as $tag ) {
		$anchor = wpwing_toc_sanitize_string( $tag->textContent );
		$tag->setAttribute( 'id', $anchor );
	}

	// Save the HTML changes
	$content = $dom->saveHTML( $dom->documentElement );

	return $content;
}

/**
 * Generate final Table of Contents
 *
 * @since 1.0.0
 */
function wpwing_toc_generate_toc( $headings, $attributes ) {
	static $toc_instance = 0;
	$toc_instance++;

	$list          = '';
	$min_depth     = 6;
	$initial_depth = 6;

	$listtype     = $attributes['use_ol'] === true ? 'ol' : 'ul';
	$absolute_url = $attributes['use_absolute_urls'] === true ? get_permalink() : '';
	$link_class   = $attributes['add_smooth'] === true ? 'smooth-scroll' : '';
	$flat_class   = ! empty( $attributes['remove_indent'] ) ? 'wpwing-toc-list--flat' : '';

	$collapsible      = ! empty( $attributes['collapsible'] );
	$style_preset     = isset( $attributes['style_preset'] ) ? $attributes['style_preset'] : 'default';
	$show_back_to_top = ! empty( $attributes['show_back_to_top'] );
	$list_id          = $collapsible ? 'wpwing-toc-list-' . $toc_instance : '';

	$heading_count      = count( $headings );
	$show_heading_count = ! empty( $attributes['show_heading_count'] );
	$exclude_keywords   = array_filter( array_map( 'trim', explode( ',', isset( $attributes['exclude_keywords'] ) ? $attributes['exclude_keywords'] : '' ) ) );

	$eligible_count = 0;
	if ( $show_heading_count ) {
		foreach ( $headings as $headline ) {
			$level = (int) $headline[2];
			if ( $level < (int) $attributes['min_level'] || $level > (int) $attributes['max_level'] ) {
				continue;
			}
			if ( strpos( $headline, 'wpwing-toc-hidden' ) !== false ) {
				continue;
			}
			if ( ! empty( $exclude_keywords ) ) {
				$title_text_check = strip_tags( $headline );
				$matched          = false;
				foreach ( $exclude_keywords as $kw ) {
					if ( $kw !== '' && stripos( $title_text_check, $kw ) !== false ) {
						$matched = true;
						break;
					}
				}
				if ( $matched ) {
					continue;
				}
			}
			$eligible_count++;
		}
	}

	foreach ( $headings as $line => $headline ) {
		$level = (int) $headings[$line][2];
		if ( $level < (int) $attributes['min_level'] || $level > (int) $attributes['max_level'] ) {
			continue;
		}
		if ( $min_depth > $level ) {
			$min_depth     = $level;
			$initial_depth = $min_depth;
		}
	}

	foreach ( $headings as $line => $headline ) {
		$title = strip_tags( $headline );
		$page  = '';
		libxml_use_internal_errors( true );
		$dom = new \DOMDocument();
		$dom->loadHTML( $headline, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
		libxml_clear_errors();
		$xpath = new \DOMXPath( $dom );
		$nodes = $xpath->query( '//*/@data-page' );

		if ( isset( $nodes[0] ) && $nodes[0]->nodeValue > 1 ) {
			$page         = $nodes[0]->nodeValue . '/';
			$absolute_url = get_permalink();
		}

		$link       = wpwing_toc_sanitize_string( $title );
		$this_depth = (int) $headings[$line][2];
		if ( isset( $headings[$line + 1][2] ) ) {
			$next_depth = (int) $headings[$line + 1][2];
		} else {
			$next_depth = '';
		}

		$keyword_match = false;
		if ( ! empty( $exclude_keywords ) ) {
			foreach ( $exclude_keywords as $kw ) {
				if ( $kw !== '' && stripos( $title, $kw ) !== false ) {
					$keyword_match = true;
					break;
				}
			}
		}
		$skip = $this_depth < (int) $attributes['min_level'] || $this_depth > (int) $attributes['max_level'] || strpos( $headline, 'wpwing-toc-hidden' ) !== false || $keyword_match;

		if ( ! $skip ) {
			if ( $this_depth === $min_depth ) {
				$list .= "<li>\n";
			} else {
				for ( $min_depth; $min_depth < $this_depth; $min_depth++ ) {
					$list .= "\n\t\t<" . $listtype . "><li>\n";
				}
			}

			$list .= "<a" . ( $link_class ? ' class="' . esc_attr( $link_class ) . '"' : '' ) . " href=\"" . esc_url( $absolute_url ) . esc_attr( $page ) . "#" . $link . "\">" . esc_html( $title ) . "</a>";
		}

		if ( $line !== $heading_count - 1 ) {
			if ( $min_depth > $next_depth ) {
				for ( $min_depth; $min_depth > $next_depth; $min_depth-- ) {
					$list .= "</li></" . $listtype . ">\n";
				}
			}
			if ( $min_depth === $next_depth ) {
				$list .= "</li>";
			}
		} elseif ( ! $skip ) {
			for ( $initial_depth; $initial_depth < $this_depth; $initial_depth++ ) {
				$list .= "</li></" . $listtype . ">\n";
			}
		}
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
	] );

	$wrapper_attributes = get_block_wrapper_attributes( [
		'class'      => implode( ' ', $nav_classes ),
		'aria-label' => $effective_title,
	] );
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
	$list_classes = array_filter( [ 'wpwing-toc-list', $flat_class ] );
	$list_close   = ( $list && substr( rtrim( $list ), -5 ) !== '</li>' ) ? '</li>' : '';
	$html .= "<{$listtype} class=\"" . esc_attr( implode( ' ', $list_classes ) ) . "\"{$list_id_attr}>\n{$list}{$list_close}</{$listtype}>";

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

