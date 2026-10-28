<?php

/**
 * Plugin settings, stored as one option array so new keys can be added
 * without a new option.
 *
 * @since 1.7.0
 */
function wpwing_toc_default_settings() {
	return [
		'auto_insert_post_types'   => [],
		'auto_insert_position'     => 'before_first_heading',
		'auto_insert_min_headings' => 2,
				'block_defaults'           => [],
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
				'block_defaults'           => wpwing_toc_sanitize_block_defaults( isset( $input['block_defaults'] ) ? $input['block_defaults'] : [] ),
	];
}

function wpwing_toc_sanitize_block_defaults( $input ) {
		$input = is_array( $input ) ? $input : [];

		// The settings page presents list markup as a single mutually-exclusive choice, same as
		// the block editor's own List style control, then derives the two underlying attributes.
		if ( isset( $input['list_style'] ) ) {
			$input['use_ol']               = 'ordered' === $input['list_style'];
			$input['hierarchical_numbers'] = 'hierarchical' === $input['list_style'];
		}

		$allowed = [
			'title_text', 'min_level', 'max_level', 'min_headings', 'collapsible', 'collapse_mode', 'start_collapsed', 'use_ol', 'remove_indent',
			'style_preset', 'hierarchical_numbers', 'exclude_keywords', 'show_back_to_top', 'add_back_to_top', 'copy_anchor', 'add_smooth', 'scroll_offset',
			'no_title', 'use_absolute_urls', 'show_heading_count', 'seo_nosnippet', 'scrollspy',
		];
		$output  = [];
		foreach ( $allowed as $key ) {
			if ( ! array_key_exists( $key, $input ) ) {
				continue;
			}
			$value = $input[ $key ];
			if ( in_array( $key, [ 'collapsible', 'start_collapsed', 'use_ol', 'remove_indent', 'hierarchical_numbers', 'show_back_to_top', 'add_back_to_top', 'copy_anchor', 'add_smooth', 'no_title', 'use_absolute_urls', 'show_heading_count', 'seo_nosnippet', 'scrollspy' ], true ) ) {
				$output[ $key ] = (bool) $value;
			} elseif ( in_array( $key, [ 'min_level', 'max_level', 'min_headings' ], true ) ) {
				$output[ $key ] = max( 1, (int) $value );
			} elseif ( 'scroll_offset' === $key ) {
				$output[ $key ] = max( 0, (int) $value );
			} elseif ( 'collapse_mode' === $key ) {
				$output[ $key ] = in_array( $value, [ 'js', 'native' ], true ) ? $value : 'js';
			} elseif ( 'style_preset' === $key ) {
				$output[ $key ] = in_array( $value, [ 'default', 'boxed' ], true ) ? $value : 'default';
			} else {
				$output[ $key ] = sanitize_text_field( $value );
			}
		}
		return $output;
}

function wpwing_toc_apply_block_defaults( $settings, $metadata ) {
		if ( 'wpwing/toc' !== $metadata['name'] ) {
			return $settings;
		}
		$defaults = wpwing_toc_get_settings()['block_defaults'];
		foreach ( $defaults as $key => $value ) {
			if ( isset( $settings['attributes'][ $key ] ) ) {
				$settings['attributes'][ $key ]['default'] = $value;
			}
		}
		return $settings;
}
add_filter( 'block_type_metadata_settings', 'wpwing_toc_apply_block_defaults', 10, 2 );
