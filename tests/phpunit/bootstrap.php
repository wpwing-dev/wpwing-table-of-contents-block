<?php

// Minimal WordPress stand-ins so the plugin's pure logic loads without a WordPress install.
define( 'ABSPATH', __DIR__ . '/' );
define( 'WPWING_TOC_FILE', dirname( __DIR__, 2 ) . '/src/wpwing-table-of-contents-block.php' );
define( 'WPWING_TOC_DIR', dirname( __DIR__, 2 ) . '/src' );

function add_action() {}
function add_filter() {}

function remove_accents( $string ) {
	return strtr( $string, [ 'ä' => 'a', 'ö' => 'o', 'ü' => 'u', 'é' => 'e', 'è' => 'e', 'ñ' => 'n' ] );
}

function sanitize_title_with_dashes( $title ) {
	$title = strtolower( $title );
	$title = preg_replace( '/[^a-z0-9 _-]/', '', $title );

	return trim( preg_replace( '/[\s_-]+/', '-', $title ), '-' );
}

function sanitize_text_field( $str ) {
	return trim( strip_tags( (string) $str ) );
}

function get_permalink() {
	return 'http://example.com/post';
}

function get_block_wrapper_attributes( $extra = [] ) {
	$attrs = [];
	foreach ( $extra as $k => $v ) {
		$attrs[] = $k . '="' . htmlspecialchars( (string) $v, ENT_QUOTES ) . '"';
	}
	return implode( ' ', $attrs );
}

function esc_attr( $t ) { return htmlspecialchars( (string) $t, ENT_QUOTES ); }
function esc_html( $t ) { return htmlspecialchars( (string) $t, ENT_QUOTES ); }
function esc_url( $u ) { return $u; }
function __( $text ) { return $text; }
function _n( $s, $p, $c ) { return $c === 1 ? $s : $p; }

require_once WPWING_TOC_DIR . '/includes/settings.php';
require_once WPWING_TOC_DIR . '/includes/importers/class-importer-luckywp.php';
require_once WPWING_TOC_DIR . '/includes/render.php';
require_once WPWING_TOC_DIR . '/includes/auto-insert.php';
