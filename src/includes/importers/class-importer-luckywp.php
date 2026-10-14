<?php

// Raw LuckyWP option groups (from its `lwptoc_` option prefix), or false when not installed.
function wpwing_toc_get_luckywp_settings() {
	$general = get_option( 'lwptoc_general', false );
	if ( ! is_array( $general ) ) {
		return false;
	}

	$auto_insert = get_option( 'lwptoc_autoInsert', [] );

	return [
		'general'    => $general,
		'autoInsert' => is_array( $auto_insert ) ? $auto_insert : [],
	];
}

// Map LuckyWP's option shape onto ours. Only returns keys we have a confident equivalent for,
// so the caller can wp_parse_args() every one of them over the current settings uniformly -
// anything LuckyWP has no signal for (or that we can't map safely) is simply absent here.
function wpwing_toc_map_luckywp_settings( array $luckywp ) {
	$general     = is_array( $luckywp['general'] ?? null ) ? $luckywp['general'] : [];
	$auto_insert = is_array( $luckywp['autoInsert'] ?? null ) ? $luckywp['autoInsert'] : [];

	$numeration = isset( $general['numeration'] ) ? $general['numeration'] : 'none';
	$use_ol     = in_array( $numeration, [ 'decimal', 'decimalnested', 'roman', 'romannested' ], true );
	$nested     = in_array( $numeration, [ 'decimalnested', 'romannested' ], true );

	// Heading level range (min_level/max_level) is deliberately not mapped - LuckyWP's "depth" is
	// relative to each post's own headings, not a sitewide absolute level.
	$mapped = [
		'block_defaults' => [
			'title_text'           => isset( $general['title'] ) ? $general['title'] : '',
			'remove_indent'        => empty( $general['hierarchical'] ),
			'use_ol'               => $use_ol,
			'hierarchical_numbers' => $nested,
			'collapsible'          => ! empty( $general['toggle'] ),
			'start_collapsed'      => ! empty( $general['hideItems'] ),
			'add_smooth'           => ! empty( $general['smoothScroll'] ),
			'scroll_offset'        => isset( $general['smoothScrollOffset'] ) ? max( 0, (int) $general['smoothScrollOffset'] ) : 0,
		],
	];

	if ( isset( $general['min'] ) ) {
		$mapped['block_defaults']['min_headings'] = max( 1, (int) $general['min'] );
	}

	// Only import auto-insert settings if LuckyWP actually had it enabled - otherwise we'd be
	// overwriting whatever the admin already configured for this plugin with LuckyWP defaults
	// that were never actually in use.
	if ( ! empty( $auto_insert['enable'] ) && is_array( $auto_insert['postTypes'] ?? null ) ) {
		$positions = [
			'beforefirstheading' => 'before_first_heading',
			'afterfirstblock'    => 'after_first_paragraph',
			'top'                => 'top',
		];

		$mapped['auto_insert_post_types']   = $auto_insert['postTypes'];
		$mapped['auto_insert_position']     = $positions[ $auto_insert['position'] ?? '' ] ?? 'before_first_heading';
		$mapped['auto_insert_min_headings'] = isset( $general['min'] ) ? max( 1, (int) $general['min'] ) : 2;
	}

	return $mapped;
}

// Applies the mapped LuckyWP settings, keeping any of our fields it has no equivalent for.
function wpwing_toc_handle_luckywp_import() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You are not allowed to do this.', 'wpwing-table-of-contents-block' ) );
	}
	check_admin_referer( 'wpwing_toc_import_luckywp' );

	$redirect = admin_url( 'admin.php?page=wpwing-toc' );
	$luckywp  = wpwing_toc_get_luckywp_settings();

	if ( false === $luckywp ) {
		wp_safe_redirect( add_query_arg( 'wpwing_toc_import', 'notfound', $redirect ) );
		exit;
	}

	$mapped  = wpwing_toc_map_luckywp_settings( $luckywp );
	$current = wpwing_toc_get_settings();

	// Merge block_defaults first, since wp_parse_args() on the outer array would otherwise
	// replace it wholesale with $mapped's (partial) version instead of filling gaps in it.
	$current['block_defaults'] = wp_parse_args( $mapped['block_defaults'], $current['block_defaults'] );
	unset( $mapped['block_defaults'] );
	$current = wp_parse_args( $mapped, $current );

	update_option( 'wpwing_toc_settings', wpwing_toc_sanitize_settings( $current ) );

	wp_safe_redirect( add_query_arg( 'wpwing_toc_import', 'success', $redirect ) );
	exit;
}
add_action( 'admin_post_wpwing_toc_import_luckywp', 'wpwing_toc_handle_luckywp_import' );
