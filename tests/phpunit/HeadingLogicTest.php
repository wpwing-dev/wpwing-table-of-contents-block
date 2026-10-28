<?php

use PHPUnit\Framework\TestCase;

final class HeadingLogicTest extends TestCase {

	public function test_sanitize_string_builds_a_url_safe_slug() {
		$this->assertSame( 'hello-world', wpwing_toc_sanitize_string( 'Hello, World!' ) );
	}

	public function test_sanitize_string_strips_accents() {
		$this->assertSame( 'uber-cafe', wpwing_toc_sanitize_string( 'über café' ) );
	}

	public function test_unique_anchor_returns_base_first_then_suffixes() {
		$seen = [];
		$this->assertSame( 'intro', wpwing_toc_unique_anchor( 'intro', $seen ) );
		$this->assertSame( 'intro-1', wpwing_toc_unique_anchor( 'intro', $seen ) );
		$this->assertSame( 'intro-2', wpwing_toc_unique_anchor( 'intro', $seen ) );
		$this->assertSame( 'other', wpwing_toc_unique_anchor( 'other', $seen ) );
	}

	public function test_existing_anchor_reads_custom_id() {
		$this->assertSame( 'custom', wpwing_toc_existing_anchor( '<h2 class="x" id="custom">Title</h2>' ) );
		$this->assertSame( 'single', wpwing_toc_existing_anchor( "<h3 id='single'>Title</h3>" ) );
		$this->assertSame( '', wpwing_toc_existing_anchor( '<h2>Title</h2>' ) );
	}

	public function test_apply_anchor_sets_id_and_handles_nbsp() {
		$html = wpwing_toc_apply_anchor( '<h2>Fish&nbsp;&amp; Chips</h2>', 'fish-chips' );
		$this->assertStringContainsString( 'id="fish-chips"', $html );
		$this->assertStringNotContainsString( '&nbsp;', $html );
	}

	public function test_apply_anchor_keeps_utf8_intact() {
		$html = wpwing_toc_apply_anchor( '<h2>Café</h2>', 'cafe' );
		$this->assertStringContainsString( 'Café', $html );
	}

	public function test_heading_excluded_by_level_range() {
		$this->assertTrue( wpwing_toc_heading_excluded( '<h1>Title</h1>', 2, 6, [] ) );
		$this->assertTrue( wpwing_toc_heading_excluded( '<h5>Title</h5>', 2, 4, [] ) );
		$this->assertFalse( wpwing_toc_heading_excluded( '<h3>Title</h3>', 2, 4, [] ) );
	}

	public function test_heading_excluded_by_hidden_class() {
		$this->assertTrue( wpwing_toc_heading_excluded( '<h2 class="wpwing-toc-hidden">Title</h2>', 1, 6, [] ) );
	}

	public function test_heading_excluded_by_keyword_is_case_insensitive_and_ignores_markup() {
		$this->assertTrue( wpwing_toc_heading_excluded( '<h2>Related <em>Posts</em></h2>', 1, 6, [ 'related' ] ) );
		$this->assertFalse( wpwing_toc_heading_excluded( '<h2 class="related">Intro</h2>', 1, 6, [ 'related' ] ) );
		$this->assertFalse( wpwing_toc_heading_excluded( '<h2>Intro</h2>', 1, 6, [ '' ] ) );
	}

	public function test_filter_headings_recurses_into_inner_blocks() {
		$blocks = [
			[ 'blockName' => 'core/heading', 'innerHTML' => '<h2>One</h2>', 'innerBlocks' => [] ],
			[
				'blockName'   => 'core/group',
				'innerHTML'   => '',
				'innerBlocks' => [
					[ 'blockName' => 'core/heading', 'innerHTML' => '<h3>Two</h3>', 'innerBlocks' => [] ],
					[ 'blockName' => 'core/paragraph', 'innerHTML' => '<p>x</p>', 'innerBlocks' => [] ],
				],
			],
		];

		$this->assertSame( [ '<h2>One</h2>', '<h3>Two</h3>' ], wpwing_toc_filter_headings_recursive( $blocks ) );
	}

	public function test_add_pagenumber_tracks_nextpage_blocks() {
		$blocks   = [
			[ 'blockName' => 'core/heading', 'innerHTML' => '<h2>A</h2>', 'innerBlocks' => [] ],
			[ 'blockName' => 'core/nextpage', 'innerHTML' => '', 'innerBlocks' => [] ],
			[ 'blockName' => 'core/heading', 'innerHTML' => '<h2>B</h2>', 'innerBlocks' => [] ],
		];
		$headings = wpwing_toc_filter_headings_recursive( $blocks );

		$this->assertSame(
			[ '<h2 data-page="1">A</h2>', '<h2 data-page="2">B</h2>' ],
			wpwing_toc_add_pagenumber( $blocks, $headings )
		);
	}

	public function test_get_block_attr_finds_nested_attribute_and_default() {
		$blocks = [
			[
				'blockName'   => 'core/group',
				'innerBlocks' => [
					[ 'blockName' => 'wpwing/toc', 'attrs' => [ 'min_level' => 3 ], 'innerBlocks' => [] ],
				],
			],
		];

		$this->assertSame( 3, wpwing_toc_get_block_attr( $blocks, 'wpwing/toc', 'min_level', 2 ) );
		$this->assertSame( 'x', wpwing_toc_get_block_attr( $blocks, 'wpwing/toc', 'missing', 'x' ) );
		$this->assertSame( 'd', wpwing_toc_get_block_attr( $blocks, 'core/other', 'a', 'd' ) );
	}

	public function test_auto_insert_index_positions() {
		$blocks = [
			[ 'blockName' => 'core/image' ],
			[ 'blockName' => 'core/paragraph' ],
			[ 'blockName' => 'core/heading' ],
		];

		$this->assertSame( 0, wpwing_toc_auto_insert_index( $blocks, 'top' ) );
		$this->assertSame( 2, wpwing_toc_auto_insert_index( $blocks, 'before_first_heading' ) );
		$this->assertSame( 2, wpwing_toc_auto_insert_index( $blocks, 'after_first_paragraph' ) );
		$this->assertSame( 0, wpwing_toc_auto_insert_index( [ [ 'blockName' => 'core/image' ] ], 'before_first_heading' ) );
	}

	public function test_sanitize_block_defaults_handles_scrollspy() {
		$sanitized = wpwing_toc_sanitize_block_defaults( [ 'scrollspy' => 1 ] );
		$this->assertTrue( $sanitized['scrollspy'] );

		$sanitized = wpwing_toc_sanitize_block_defaults( [ 'scrollspy' => 0 ] );
		$this->assertFalse( $sanitized['scrollspy'] );
	}

	public function test_generate_toc_adds_scrollspy_nav_class_when_enabled() {
		$headings = [ '<h2>Section One</h2>', '<h2>Section Two</h2>' ];
		$attrs = [
			'min_level' => 2,
			'max_level' => 6,
			'use_ol' => false,
			'use_absolute_urls' => false,
			'add_smooth' => false,
			'title_text' => 'TOC',
			'no_title' => false,
			'scrollspy' => true,
		];
		$html = wpwing_toc_generate_toc( $headings, $attrs );
		$this->assertStringContainsString( 'wpwing-toc--scrollspy', $html );

		$attrs['scrollspy'] = false;
		$html_no_spy = wpwing_toc_generate_toc( $headings, $attrs );
		$this->assertStringNotContainsString( 'wpwing-toc--scrollspy', $html_no_spy );
	}
}

