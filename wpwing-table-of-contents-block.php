<?php

/**
 * Plugin Name:       WPWing Table Of Contents Block
 * Plugin URI:        https://wpwing.com/
 * Description:       Adds a basic "Table of Contents" Gutenberg block.
 * Version:           1.0.0
 * Requires at least: 5.8
 * Requires PHP:      7.0
 * Author:            WPWing
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wpwing-toc
 * Domain Path:       /languages
 *
 * @package           create-block
 */

/**
 * Initalise frontend and backend and register block
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
 */
function wpwing_toc_plugin_meta( $links, $file ) {
  if ( false !== strpos( $file, 'wpwing-table-of-contens-block' ) ) {
    $links = array_merge( $links, ['<a href="https://wordpress.org/support/plugin/wpwing-table-of-contents-block">' . __( 'Support', 'wpwing-toc' ) . '</a>'] );
    $links = array_merge( $links, ['<a href="https://wordpress.org/support/plugin/wpwing-table-of-contents-block/reviews/#new-post">' . __( 'Write a review', 'wpwing-toc' ) . '&nbsp;⭐️⭐️⭐️⭐️⭐️</a>'] );
  }

  return $links;
}

/**
 * Render block output
 */
function wpwing_toc_render_callback( $attributes ) {
  $is_backend = defined( 'REST_REQUEST' ) && true === REST_REQUEST && 'edit' === filter_input( INPUT_GET, 'context' );

  $alignclass = '';
  if ( isset( $attributes['align'] ) ) {
    $align      = $attributes['align'];
    $alignclass = 'align' . $align;
  }

  $className = '';
  if ( isset( $attributes['className'] ) ) {
    $className = strip_tags( htmlspecialchars( $attributes['className'] ) );
  }

  $pre_html  = '';
  $post_html = '';
  if ( $className != '' ) {
    $pre_html  = '<div class="wpwing-toc ' . $className . '">';
    $post_html = '</div>';
  }

  $post   = get_post();
  $blocks = parse_blocks( $post->post_content );

  if ( empty( $blocks ) ) {
    $html = '';
    if ( $is_backend == true ) {
      if ( $attributes['no_title'] == false ) {
        $html = '<h2 class="wpwing-toc-title ' . $alignclass . '">' . __( 'Table of Contents', 'wpwing-toc' ) . '</h2>';
      }

      $html .= '<p class="components-notice is-warning ' . $alignclass . '">' . __( 'No blocks found.', 'wpwing-toc' ) . ' ' . __( 'Save or update post first.', 'wpwing-toc' ) . '</p>';
    }
    return $html;
  }

  $headings = array_reverse( wpwing_toc_filter_headings_recursive( $blocks ) );

  // enrich headings with pages as a data-attribute
  $headings = wpwing_toc_add_pagenumber( $blocks, $headings );

  $headings_clean = array_map( 'trim', $headings );

  if ( empty( $headings_clean ) ) {
    $html = '';
    if ( $is_backend == true ) {

      if ( $attributes['no_title'] == false ) {
        $html = '<h2 class="wpwing-toc-title ' . $alignclass . '">' . __( 'Table of Contents', 'wpwing-toc' ) . '</h2>';
      }

      $html .= '<p class="components-notice is-warning ' . $alignclass . '">' . __( 'No headings found.', 'wpwing-toc' ) . ' ' . __( 'Save or update post first.', 'wpwing-toc' ) . '</p>';
    }
    return $html;
  }

  $toclist = wpwing_toc_generate_toc( $headings_clean, $attributes );

  $output = $pre_html . $toclist . $post_html;

  return $output;
}

/**
 * Return all headings with a recursive walk through all blocks.
 * This includes groups and reusable block with groups within reusable blocks.
 */
function wpwing_toc_filter_headings_recursive( $blocks ) {
  $arr = [];

  foreach ( $blocks as $block => $innerBlock ) {
    if ( is_array( $innerBlock ) ) {
      if ( isset( $innerBlock['attrs']['ref'] ) ) {
        // search in reusable blocks
        $e_arr = parse_blocks( get_post( $innerBlock['attrs']['ref'] )->post_content );
        $arr   = array_merge( wpwing_toc_filter_headings_recursive( $e_arr ), $arr );
      } else {
        // search in groups
        $arr = array_merge( wpwing_toc_filter_headings_recursive( $innerBlock ), $arr );
      }
    } else {
      if ( isset( $blocks['blockName'] ) && $blocks['blockName'] === 'core/heading' && $innerBlock !== 'core/heading' ) {
        // make sure its a headline.
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
 */
function wpwing_toc_add_pagenumber( $blocks, $headings ) {
  $pages = 1;

  foreach ( $blocks as $block => $innerBlock ) {

    // count nextpage blocks
    if ( isset( $blocks[$block]['blockName'] ) && $blocks[$block]['blockName'] === 'core/nextpage' ) {
      $pages++;
    }

    if ( isset( $blocks[$block]['blockName'] ) && $blocks[$block]["blockName"] === 'core/heading' ) {
      // make sure its a headline.
      foreach ( $headings as $heading => &$innerHeading ) {
        if ( $innerHeading == $blocks[$block]["innerHTML"] ) {
          $innerHeading = preg_replace( "/(<h1|<h2|<h3|<h4|<h5|<h6)/i", '$1 data-page="' . $pages . '"', $blocks[$block]["innerHTML"] );
        }
      }
    }
  }

  return $headings;
}

/**
 * Add IDs to the H1-6 content
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
  // remove non-breaking space entites from input HTML
  $html_wo_nbsp = str_replace( "&nbsp;", " ", $html );

  if ( ! $html_wo_nbsp ) {
    return $html;
  }

  libxml_use_internal_errors( TRUE );
  $dom = new \DOMDocument();
  @$dom->loadHTML( $html_wo_nbsp, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );

  // use xpath to select the Heading html tags.
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
  $content = utf8_decode( $dom->saveHTML( $dom->documentElement ) );

  return $content;
}

function wpwing_toc_generate_toc( $headings, $attributes ) {
  $list         = '';
  $html         = '';
  $min_depth    = 6;
  $listtype     = 'ul';
  $absolute_url = '';
  $inital_depth = 6;
  $link_class   = '';
  $styles       = '';

  $alignclass = '';
  if ( isset( $attributes['align'] ) ) {
    $align      = $attributes['align'];
    $alignclass = 'align' . $align;
  }

  if ( $attributes['remove_indent'] == true ) {
    $styles = 'style="padding-left:0;list-style:none;"';
  }

  if ( $attributes['add_smooth'] == true ) {
    $link_class = 'class="smooth-scroll"';
  }

  if ( $attributes['use_ol'] == true ) {
    $listtype = 'ol';
  }

  if ( $attributes['use_absolute_urls'] == true ) {
    $absolute_url = get_permalink();
  }

  foreach ( $headings as $line => $headline ) {
    if ( $min_depth > $headings[$line][2] ) {
      // search for lowest level
      $min_depth    = (int) $headings[$line][2];
      $inital_depth = $min_depth;
    }
  }

  foreach ( $headings as $line => $headline ) {
    $title = strip_tags( $headline );
    $page  = '';
    $dom   = new \DOMDocument();
    @$dom->loadHTML( $headline, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
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

    // skip this heading because a max depth is set.
    if ( $this_depth > $attributes['max_level'] or strpos( $headline, 'class="wpwing-toc-hidden' ) > 0 ) {
      goto closelist;
    }

    // start list
    if ( $this_depth == $min_depth ) {
      $list .= "<li>\n";
    } else {
      // we are not as base level. Start opening levels until base is reached.
      for ( $min_depth; $min_depth < $this_depth; $min_depth++ ) {
        $list .= "\n\t\t<" . $listtype . "><li>\n";
      }
    }

    $list .= "<a " . $link_class . " href=\"" . $absolute_url . esc_html( $page ) . "#" . $link . "\">" . $title . "</a>";

    closelist:
    // close lists
    // check if this is not the last heading
    if ( $line != count( $headings ) - 1 ) {
      // do we need to close the door behind us?
      if ( $min_depth > $next_depth ) {
        // If yes, how many times?
        for ( $min_depth; $min_depth > $next_depth; $min_depth-- ) {
          $list .= "</li></" . $listtype . ">\n";
        }
      }
      if ( $min_depth == $next_depth ) {
        $list .= "</li>";
      }
      // last heading
    } else {
      for ( $inital_depth; $inital_depth < $this_depth; $inital_depth++ ) {
        $list .= "</li></" . $listtype . ">\n";
      }
    }
  }

  if ( $attributes['no_title'] == false ) {
    $html = "<h2 class=\"wpwing-toc-title\">" . __( "Table of Contents", "wpwing-toc" ) . "</h2>";
  }
  $html .= "<" . $listtype . " class=\"wpwing-toc-list\" " . $styles . "  " . $alignclass . ">\n" . $list . "</li></" . $listtype . ">";

  return $html;
}

/**
 * Remove all problematic characters for toc links
 */
function wpwing_toc_sanitize_string( $string ) {
  // remove punctuation
  $zero_punctuation = preg_replace( "/\p{P}/u", "", $string );
  // remove non-breaking spaces
  $html_wo_nbsp = str_replace( "&nbsp;", " ", $zero_punctuation );
  // remove umlauts and accents
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
  $toc_plugins['wpwing-table-of-contens-block/wpwing-table-of-contens-block.php'] = 'WPWingTOC';

  return $toc_plugins;
} );

/**
 * For test and debug, log function to view any data in wp-content/debug.log
 * uses: log_it($variable);
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'log_it' ) ) {
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
