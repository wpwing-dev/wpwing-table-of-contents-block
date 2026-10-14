<?php

/**
 * Initalise frontend and backend and register block
 *
 * @since 1.0.0
 */
function wpwing_toc_register_block() {
	load_plugin_textdomain( 'wpwing-table-of-contents-block', false, dirname( plugin_basename( WPWING_TOC_FILE ) ) . '/languages' );
	add_filter( 'plugin_row_meta', 'wpwing_toc_plugin_meta', 10, 2 );
	add_filter( 'plugin_action_links_' . plugin_basename( WPWING_TOC_FILE ), 'wpwing_toc_plugin_action_links' );

	register_block_type( WPWING_TOC_DIR . '/assets/public', [
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
		$links = array_merge( $links, ['<a href="' . esc_url( plugins_url( 'docs/index.html', WPWING_TOC_FILE ) ) . '" target="_blank">' . __( 'Documentation', 'wpwing-table-of-contents-block' ) . '</a>'] );
	}

	return $links;
}

// Add a Settings link on the plugins list row.
function wpwing_toc_plugin_action_links( $links ) {
	array_unshift( $links, '<a href="' . esc_url( admin_url( 'admin.php?page=wpwing-toc' ) ) . '">' . __( 'Settings', 'wpwing-table-of-contents-block' ) . '</a>' );

	return $links;
}
