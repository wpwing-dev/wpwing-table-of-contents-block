<?php

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
