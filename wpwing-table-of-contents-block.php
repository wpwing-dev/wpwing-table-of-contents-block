<?php

/**
 * Plugin Name:			Table of Contents (TOC) Block - Fast & SEO Friendly
 * Plugin URI:			https://wpwing.com/
 * Description:			Automated, ultra-fast Table of Contents block built to boost SEO and readability with zero frontend JavaScript.
 * Version:				1.1.0
 * Requires at least:	5.8
 * Tested up to:		7.0
 * Requires PHP:		7.1
 * Author:				WPWing
 * Author URI:			https://wpwing.com/
 * License:				GPL-3.0-or-later
 * License URI:			https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:			wpwing-table-of-contents-block
 * Domain Path:			/languages
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

	$alignClass = '';
	if ( isset( $attributes['align'] ) ) {
		$align      = $attributes['align'];
		$alignClass = 'align' . $align;
	}

	// Get all the blocks from post content
	$post = get_post();
	if ( ! $post ) {
		return '';
	}
	$blocks = parse_blocks( $post->post_content );

	$effective_title = ! empty( $attributes['title_text'] ) ? $attributes['title_text'] : __( 'Table of Contents', 'wpwing-table-of-contents-block' );

	// If no block found
	if ( empty( $blocks ) ) {
		$html = '';
		if ( $is_backend === true ) {
			if ( $attributes['no_title'] === false ) {
				$html = '<h2 class="wpwing-toc-title ' . esc_attr( $alignClass ) . '">' . esc_html( $effective_title ) . '</h2>';
			}
			$html .= '<p class="components-notice is-warning ' . esc_attr( $alignClass ) . '">' . __( 'No blocks found.', 'wpwing-table-of-contents-block' ) . ' ' . __( 'Save or update post first.', 'wpwing-table-of-contents-block' ) . '</p>';
		}

		return $html;
	}

	$headings = array_reverse( wpwing_toc_filter_headings_recursive( $blocks ) );

	// Enrich headings with pages as a data-attribute
	$headings = wpwing_toc_add_pagenumber( $blocks, $headings );

	$headings_clean = array_map( 'trim', $headings );

	if ( empty( $headings_clean ) ) {
		$html = '';
		if ( $is_backend === true ) {
			if ( $attributes['no_title'] === false ) {
				$html = '<h2 class="wpwing-toc-title ' . esc_attr( $alignClass ) . '">' . esc_html( $effective_title ) . '</h2>';
			}

			$html .= '<p class="components-notice is-warning ' . esc_attr( $alignClass ) . '">' . __( 'No headings found.', 'wpwing-table-of-contents-block' ) . ' ' . __( 'Save or update post first.', 'wpwing-table-of-contents-block' ) . '</p>';
		}

		return $html;
	}

	if ( $attributes['add_smooth'] === true ) {
		add_action( 'wp_footer', 'wpwing_toc_print_smooth_scroll_style' );
	}

	if ( ! empty( $attributes['collapsible'] ) ) {
		add_action( 'wp_footer', 'wpwing_toc_print_collapsible_script' );
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
 * Print collapsible toggle JS in the footer when the option is enabled.
 * Named function so add_action deduplicates it if multiple TOC blocks are on the page.
 *
 * @since 1.1.0
 */
function wpwing_toc_print_collapsible_script() {
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
function wpwing_toc_filter_headings_recursive( $blocks ) {
	$arr = [];

	foreach ( $blocks as $block => $innerBlock ) {
		if ( is_array( $innerBlock ) ) {
			if ( isset( $innerBlock['attrs']['ref'] ) ) {
				// Search in reusable blocks
				$reusable_post = get_post( $innerBlock['attrs']['ref'] );
				if ( $reusable_post ) {
					$e_arr = parse_blocks( $reusable_post->post_content );
					$arr   = array_merge( wpwing_toc_filter_headings_recursive( $e_arr ), $arr );
				}
			} else {
				// Search in groups
				$arr = array_merge( wpwing_toc_filter_headings_recursive( $innerBlock ), $arr );
			}
		} else {
			if ( isset( $blocks['blockName'] ) && $blocks['blockName'] === 'core/heading' && $innerBlock !== 'core/heading' ) {
				// Make sure its a headline.
				if ( preg_match( "/(<h1|<h2|<h3|<h4|<h5|<h6)/i", $innerBlock ) ) {
					$arr[] = $innerBlock;
				}
			}
		}
	}

	return $arr;
}

/**
 * Headings with pages as a data-attribute
 *
 * @since 1.0.0
 */
function wpwing_toc_add_pagenumber( $blocks, $headings ) {
	$pages = 1;

	foreach ( $blocks as $block => $innerBlock ) {
		// Count nextpage blocks
		if ( isset( $blocks[$block]['blockName'] ) && $blocks[$block]['blockName'] === 'core/nextpage' ) {
			$pages++;
		}

		if ( isset( $blocks[$block]['blockName'] ) && $blocks[$block]["blockName"] === 'core/heading' ) {
			// Make sure its a headline.
			foreach ( $headings as $heading => &$innerHeading ) {
				if ( $innerHeading === $blocks[$block]["innerHTML"] ) {
					$innerHeading = preg_replace( "/(<h1|<h2|<h3|<h4|<h5|<h6)/i", '$1 data-page="' . $pages . '"', $blocks[$block]["innerHTML"] );
				}
			}
		}
	}

	return $headings;
}

/**
 * Add IDs to the H1-6 content
 *
 * @since 1.0.0
 */
function wpwing_toc_add_ids_to_content( $content ) {
	if ( has_block( 'wpwing/toc', get_the_ID() ) ) {
		$blocks = parse_blocks( $content );

		foreach ( $blocks as &$block ) {
			if ( isset( $block['blockName'] ) && $block['blockName'] === 'core/heading' && isset( $block['innerHTML'] ) && isset( $block['innerContent'] ) && isset( $block['innerContent'][0] ) ) {
				$block['innerHTML']       = wpwing_toc_add_anchor_attribute( $block['innerHTML'] );
				$block['innerContent'][0] = wpwing_toc_add_anchor_attribute( $block['innerContent'][0] );
			}
		}

		$content = serialize_blocks( $blocks );
	}

	return $content;
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

	// Loop through all the found tags
	foreach ( $tags as $tag ) {
		// Set id attribute
		$heading_text = strip_tags( $html );
		$anchor       = wpwing_toc_sanitize_string( $heading_text );
		$tag->setAttribute( "id", $anchor );
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
	$link_class   = $attributes['add_smooth'] === true ? 'class="smooth-scroll"' : '';
	$styles       = $attributes['remove_indent'] === true ? 'style="padding-left:0;list-style:none;"' : '';

	$collapsible      = ! empty( $attributes['collapsible'] );
	$style_preset     = isset( $attributes['style_preset'] ) ? $attributes['style_preset'] : 'default';
	$show_back_to_top = ! empty( $attributes['show_back_to_top'] );
	$list_id          = $collapsible ? 'wpwing-toc-list-' . $toc_instance : '';

	$alignClass = '';
	if ( isset( $attributes['align'] ) ) {
		$alignClass = 'align' . $attributes['align'];
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

		$skip = $this_depth < (int) $attributes['min_level'] || $this_depth > (int) $attributes['max_level'] || strpos( $headline, 'class="wpwing-toc-hidden' ) !== false;

		if ( ! $skip ) {
			if ( $this_depth === $min_depth ) {
				$list .= "<li>\n";
			} else {
				for ( $min_depth; $min_depth < $this_depth; $min_depth++ ) {
					$list .= "\n\t\t<" . $listtype . "><li>\n";
				}
			}

			$list .= "<a" . ( $link_class ? ' ' . $link_class : '' ) . " href=\"" . $absolute_url . esc_html( $page ) . "#" . $link . "\">" . esc_html( $title ) . "</a>";
		}

		if ( $line !== count( $headings ) - 1 ) {
			if ( $min_depth > $next_depth ) {
				for ( $min_depth; $min_depth > $next_depth; $min_depth-- ) {
					$list .= "</li></" . $listtype . ">\n";
				}
			}
			if ( $min_depth === $next_depth ) {
				$list .= "</li>";
			}
		} else {
			for ( $initial_depth; $initial_depth < $this_depth; $initial_depth++ ) {
				$list .= "</li></" . $listtype . ">\n";
			}
		}
	}

	$effective_title = ! empty( $attributes['title_text'] ) ? $attributes['title_text'] : __( 'Table of Contents', 'wpwing-table-of-contents-block' );

	// Build nav class list
	$nav_classes = array_filter( [
		'wpwing-toc',
		$alignClass,
		'boxed' === $style_preset ? 'wpwing-toc--boxed' : '',
		$collapsible ? 'wpwing-toc--collapsible' : '',
	] );

	$html = '<nav class="' . esc_attr( implode( ' ', $nav_classes ) ) . '" aria-label="' . esc_attr( $effective_title ) . '">';

	if ( $collapsible ) {
		$html .= '<div class="wpwing-toc-header">';
		if ( $attributes['no_title'] === false ) {
			$html .= '<h2 class="wpwing-toc-title">' . esc_html( $effective_title ) . '</h2>';
		}
		$html .= '<button class="wpwing-toc-toggle" aria-expanded="true" aria-controls="' . esc_attr( $list_id ) . '">';
		$html .= '<span class="screen-reader-text">' . esc_html__( 'Toggle Table of Contents', 'wpwing-table-of-contents-block' ) . '</span>';
		$html .= '<span class="wpwing-toc-toggle-icon" aria-hidden="true"></span>';
		$html .= '</button>';
		$html .= '</div>';
	} elseif ( $attributes['no_title'] === false ) {
		$html .= '<h2 class="wpwing-toc-title">' . esc_html( $effective_title ) . '</h2>';
	}

	$list_id_attr = $list_id ? ' id="' . esc_attr( $list_id ) . '"' : '';
	$html .= "<{$listtype} class=\"wpwing-toc-list\"{$list_id_attr}" . ( $styles ? ' ' . $styles : '' ) . ">\n{$list}</li></{$listtype}>";

	if ( $show_back_to_top ) {
		$html .= '<a href="#" class="wpwing-toc-back-top">' . esc_html__( 'Back to top', 'wpwing-table-of-contents-block' ) . '</a>';
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

/**
 * Filter to add plugins to the TOC list for Rank Math plugin
 *
 * @param array TOC plugins.
 */
add_filter( 'rank_math/researches/toc_plugins', function ( $toc_plugins ) {
	$toc_plugins['wpwing-table-of-contents-block/wpwing-table-of-contents-block.php'] = 'WPWingTOC';

	return $toc_plugins;
} );

/**
 * For test and debug, log function to view any data in wp-content/debug.log
 * uses: log_it($variable);
 *
 * @since 1.0.0
 */
if (  ! function_exists( 'log_it' ) ) {
	function log_it( $message ) {
		if ( WP_DEBUG === true ) {
			if ( is_array( $message ) || is_object( $message ) ) {
				error_log( "\r\n" . print_r( $message, true ) );
			} else {
				error_log( $message );
			}
		}
	}
}
