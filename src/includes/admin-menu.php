<?php

// Registers the shared "WPWing" parent menu only if no other WPWing plugin already has.
function wpwing_toc_add_settings_page() {
	if ( '' === menu_page_url( 'wpwing', false ) ) {
		// We own the parent, so give it our own page directly - no redirect round-trip needed.
		add_menu_page(
			__( 'WPWing', 'wpwing-table-of-contents-block' ),
			__( 'WPWing', 'wpwing-table-of-contents-block' ),
			'manage_options',
			'wpwing',
			'wpwing_toc_render_settings_page',
			'dashicons-heart',
			58
		);
		wpwing_toc_owns_parent_menu( true );
	}

	add_submenu_page(
		'wpwing',
		__( 'Table of Contents Block', 'wpwing-table-of-contents-block' ),
		__( 'TOC Block', 'wpwing-table-of-contents-block' ),
		'manage_options',
		'wpwing-toc',
		'wpwing_toc_render_settings_page'
	);
}
add_action( 'admin_menu', 'wpwing_toc_add_settings_page' );

// Tracks whether we won the parent-registration race, so the redirect below only fires for a sibling's blank page.
function wpwing_toc_owns_parent_menu( $set = null ) {
	static $owns = false;
	if ( null !== $set ) {
		$owns = $set;
	}
	return $owns;
}

// Fallback for a sibling WPWing plugin's blank parent page - send visitors straight to ours.
function wpwing_toc_redirect_parent_menu() {
	if ( ! wpwing_toc_owns_parent_menu() && isset( $_GET['page'] ) && 'wpwing' === $_GET['page'] ) {
		wp_safe_redirect( admin_url( 'admin.php?page=wpwing-toc' ) );
		exit;
	}
}
add_action( 'admin_init', 'wpwing_toc_redirect_parent_menu', 15 );

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
		<?php if ( isset( $_GET['wpwing_toc_import'] ) ) : ?>
			<?php if ( 'success' === $_GET['wpwing_toc_import'] ) : ?>
				<div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Settings imported from LuckyWP Table of Contents.', 'wpwing-table-of-contents-block' ); ?></p></div>
			<?php elseif ( 'notfound' === $_GET['wpwing_toc_import'] ) : ?>
				<div class="notice notice-error is-dismissible"><p><?php esc_html_e( 'No LuckyWP Table of Contents settings were found to import.', 'wpwing-table-of-contents-block' ); ?></p></div>
			<?php endif; ?>
		<?php endif; ?>
		<?php if ( false !== wpwing_toc_get_luckywp_settings() ) : ?>
			<h2><?php esc_html_e( 'Import settings', 'wpwing-table-of-contents-block' ); ?></h2>
			<p><?php esc_html_e( 'LuckyWP Table of Contents settings were found on this site. Import its list style, title, minimum-headings threshold, and smooth-scroll settings into the defaults below.', 'wpwing-table-of-contents-block' ); ?></p>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<?php wp_nonce_field( 'wpwing_toc_import_luckywp' ); ?>
				<input type="hidden" name="action" value="wpwing_toc_import_luckywp" />
				<?php submit_button( __( 'Import from LuckyWP Table of Contents', 'wpwing-table-of-contents-block' ), 'secondary', 'wpwing-toc-import-submit' ); ?>
			</form>
		<?php endif; ?>
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
		<h2><?php esc_html_e( 'New block defaults', 'wpwing-table-of-contents-block' ); ?></h2>
		<table class="form-table" role="presentation">
			<tr>
				<th scope="row"><label for="wpwing-toc-default-title"><?php esc_html_e( 'Custom title', 'wpwing-table-of-contents-block' ); ?></label></th>
				<td><input type="text" id="wpwing-toc-default-title" name="wpwing_toc_settings[block_defaults][title_text]" value="<?php echo esc_attr( $settings['block_defaults']['title_text'] ?? '' ); ?>" /></td>
			</tr>
			<tr>
				<th scope="row"><label for="wpwing-toc-default-min"><?php esc_html_e( 'Minimum and maximum levels', 'wpwing-table-of-contents-block' ); ?></label></th>
				<td>
					<input type="number" id="wpwing-toc-default-min" name="wpwing_toc_settings[block_defaults][min_level]" min="1" max="6" value="<?php echo esc_attr( $settings['block_defaults']['min_level'] ?? 2 ); ?>" />
					<input type="number" name="wpwing_toc_settings[block_defaults][max_level]" min="1" max="6" value="<?php echo esc_attr( $settings['block_defaults']['max_level'] ?? 6 ); ?>" />
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Collapsible mode', 'wpwing-table-of-contents-block' ); ?></th>
				<td>
					<label><input type="checkbox" name="wpwing_toc_settings[block_defaults][collapsible]" value="1" <?php checked( ! empty( $settings['block_defaults']['collapsible'] ) ); ?> /> <?php esc_html_e( 'Make new blocks collapsible', 'wpwing-table-of-contents-block' ); ?></label>
					<select name="wpwing_toc_settings[block_defaults][collapse_mode]">
						<option value="js" <?php selected( $settings['block_defaults']['collapse_mode'] ?? 'js', 'js' ); ?>><?php esc_html_e( 'JavaScript toggle', 'wpwing-table-of-contents-block' ); ?></option>
						<option value="native" <?php selected( $settings['block_defaults']['collapse_mode'] ?? 'js', 'native' ); ?>><?php esc_html_e( 'Native HTML (zero JavaScript)', 'wpwing-table-of-contents-block' ); ?></option>
					</select>
					<br />
					<label><input type="checkbox" name="wpwing_toc_settings[block_defaults][start_collapsed]" value="1" <?php checked( ! empty( $settings['block_defaults']['start_collapsed'] ) ); ?> /> <?php esc_html_e( 'Start collapsed', 'wpwing-table-of-contents-block' ); ?></label>
				</td>
			</tr>
		</table>

		<h3><?php esc_html_e( 'List style', 'wpwing-table-of-contents-block' ); ?></h3>
		<table class="form-table" role="presentation">
			<tr>
				<th scope="row"><label for="wpwing-toc-default-style"><?php esc_html_e( 'Style preset', 'wpwing-table-of-contents-block' ); ?></label></th>
				<td>
					<select id="wpwing-toc-default-style" name="wpwing_toc_settings[block_defaults][style_preset]">
						<option value="default" <?php selected( $settings['block_defaults']['style_preset'] ?? 'default', 'default' ); ?>><?php esc_html_e( 'Default', 'wpwing-table-of-contents-block' ); ?></option>
						<option value="boxed" <?php selected( $settings['block_defaults']['style_preset'] ?? 'default', 'boxed' ); ?>><?php esc_html_e( 'Boxed', 'wpwing-table-of-contents-block' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="wpwing-toc-default-list-style"><?php esc_html_e( 'List markup', 'wpwing-table-of-contents-block' ); ?></label></th>
				<td>
					<?php
					$list_style = ! empty( $settings['block_defaults']['hierarchical_numbers'] ) ? 'hierarchical' : ( ! empty( $settings['block_defaults']['use_ol'] ) ? 'ordered' : 'bullet' );
					?>
					<select id="wpwing-toc-default-list-style" name="wpwing_toc_settings[block_defaults][list_style]">
						<option value="bullet" <?php selected( $list_style, 'bullet' ); ?>><?php esc_html_e( 'Bulleted', 'wpwing-table-of-contents-block' ); ?></option>
						<option value="ordered" <?php selected( $list_style, 'ordered' ); ?>><?php esc_html_e( 'Numbered', 'wpwing-table-of-contents-block' ); ?></option>
						<option value="hierarchical" <?php selected( $list_style, 'hierarchical' ); ?>><?php esc_html_e( 'Hierarchical (1.1, 1.1.1)', 'wpwing-table-of-contents-block' ); ?></option>
					</select>
					<br />
					<label><input type="checkbox" name="wpwing_toc_settings[block_defaults][remove_indent]" value="1" <?php checked( ! empty( $settings['block_defaults']['remove_indent'] ) ); ?> /> <?php esc_html_e( 'Remove nested indentation', 'wpwing-table-of-contents-block' ); ?></label>
				</td>
			</tr>
		</table>

		<h3><?php esc_html_e( 'Thresholds and filtering', 'wpwing-table-of-contents-block' ); ?></h3>
		<table class="form-table" role="presentation">
			<tr>
				<th scope="row"><label for="wpwing-toc-default-min-headings"><?php esc_html_e( 'Minimum headings to show TOC', 'wpwing-table-of-contents-block' ); ?></label></th>
				<td><input type="number" id="wpwing-toc-default-min-headings" name="wpwing_toc_settings[block_defaults][min_headings]" min="1" step="1" value="<?php echo esc_attr( $settings['block_defaults']['min_headings'] ?? 1 ); ?>" /></td>
			</tr>
			<tr>
				<th scope="row"><label for="wpwing-toc-default-exclude"><?php esc_html_e( 'Exclude headings containing', 'wpwing-table-of-contents-block' ); ?></label></th>
				<td>
					<input type="text" id="wpwing-toc-default-exclude" name="wpwing_toc_settings[block_defaults][exclude_keywords]" value="<?php echo esc_attr( $settings['block_defaults']['exclude_keywords'] ?? '' ); ?>" />
					<p class="description"><?php esc_html_e( 'Comma-separated keywords. Headings containing any of them are skipped.', 'wpwing-table-of-contents-block' ); ?></p>
				</td>
			</tr>
		</table>

		<h3><?php esc_html_e( 'Links', 'wpwing-table-of-contents-block' ); ?></h3>
		<table class="form-table" role="presentation">
			<tr>
				<th scope="row"><?php esc_html_e( 'Back to top', 'wpwing-table-of-contents-block' ); ?></th>
				<td>
					<label><input type="checkbox" name="wpwing_toc_settings[block_defaults][show_back_to_top]" value="1" <?php checked( ! empty( $settings['block_defaults']['show_back_to_top'] ) ); ?> /> <?php esc_html_e( 'Show a back-to-top link below the TOC', 'wpwing-table-of-contents-block' ); ?></label><br />
					<label><input type="checkbox" name="wpwing_toc_settings[block_defaults][add_back_to_top]" value="1" <?php checked( ! empty( $settings['block_defaults']['add_back_to_top'] ) ); ?> /> <?php esc_html_e( 'Add a back-to-top link after each heading', 'wpwing-table-of-contents-block' ); ?></label>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Copy link', 'wpwing-table-of-contents-block' ); ?></th>
				<td><label><input type="checkbox" name="wpwing_toc_settings[block_defaults][copy_anchor]" value="1" <?php checked( ! empty( $settings['block_defaults']['copy_anchor'] ) ); ?> /> <?php esc_html_e( 'Show a copy-link button on each entry', 'wpwing-table-of-contents-block' ); ?></label></td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'URL format', 'wpwing-table-of-contents-block' ); ?></th>
				<td><label><input type="checkbox" name="wpwing_toc_settings[block_defaults][use_absolute_urls]" value="1" <?php checked( ! empty( $settings['block_defaults']['use_absolute_urls'] ) ); ?> /> <?php esc_html_e( 'Use absolute URLs in TOC links', 'wpwing-table-of-contents-block' ); ?></label></td>
			</tr>
		</table>

		<h3><?php esc_html_e( 'Scrolling', 'wpwing-table-of-contents-block' ); ?></h3>
		<table class="form-table" role="presentation">
			<tr>
				<th scope="row"><?php esc_html_e( 'Smooth scroll', 'wpwing-table-of-contents-block' ); ?></th>
				<td><label><input type="checkbox" name="wpwing_toc_settings[block_defaults][add_smooth]" value="1" <?php checked( ! empty( $settings['block_defaults']['add_smooth'] ) ); ?> /> <?php esc_html_e( 'Enable smooth scrolling to headings', 'wpwing-table-of-contents-block' ); ?></label></td>
			</tr>
			<tr>
				<th scope="row"><label for="wpwing-toc-default-scroll-offset"><?php esc_html_e( 'Scroll offset', 'wpwing-table-of-contents-block' ); ?></label></th>
				<td>
					<input type="number" id="wpwing-toc-default-scroll-offset" name="wpwing_toc_settings[block_defaults][scroll_offset]" min="0" step="1" value="<?php echo esc_attr( $settings['block_defaults']['scroll_offset'] ?? 0 ); ?>" /> <?php esc_html_e( 'px', 'wpwing-table-of-contents-block' ); ?>
					<p class="description"><?php esc_html_e( 'Keeps headings visible below sticky or fixed headers when jumping from the TOC.', 'wpwing-table-of-contents-block' ); ?></p>
				</td>
			</tr>
		</table>

		<h3><?php esc_html_e( 'Accessibility and SEO', 'wpwing-table-of-contents-block' ); ?></h3>
		<table class="form-table" role="presentation">
			<tr>
				<th scope="row"><?php esc_html_e( 'Title and count', 'wpwing-table-of-contents-block' ); ?></th>
				<td>
					<label><input type="checkbox" name="wpwing_toc_settings[block_defaults][no_title]" value="1" <?php checked( ! empty( $settings['block_defaults']['no_title'] ) ); ?> /> <?php esc_html_e( 'Hide the TOC title', 'wpwing-table-of-contents-block' ); ?></label><br />
					<label><input type="checkbox" name="wpwing_toc_settings[block_defaults][show_heading_count]" value="1" <?php checked( ! empty( $settings['block_defaults']['show_heading_count'] ) ); ?> /> <?php esc_html_e( 'Show a heading-count badge next to the title', 'wpwing-table-of-contents-block' ); ?></label>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Search snippets', 'wpwing-table-of-contents-block' ); ?></th>
				<td><label><input type="checkbox" name="wpwing_toc_settings[block_defaults][seo_nosnippet]" value="1" <?php checked( ! empty( $settings['block_defaults']['seo_nosnippet'] ) ); ?> /> <?php esc_html_e( 'Exclude TOC text from search result snippets', 'wpwing-table-of-contents-block' ); ?></label></td>
			</tr>
		</table>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}
