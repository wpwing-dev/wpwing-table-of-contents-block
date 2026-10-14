<?php

use PHPUnit\Framework\TestCase;

final class LuckyWpMappingTest extends TestCase {

	public function test_numeration_maps_to_list_markup() {
		$decimal = wpwing_toc_map_luckywp_settings( [ 'general' => [ 'numeration' => 'decimal' ] ] );
		$this->assertTrue( $decimal['block_defaults']['use_ol'] );
		$this->assertFalse( $decimal['block_defaults']['hierarchical_numbers'] );

		$nested = wpwing_toc_map_luckywp_settings( [ 'general' => [ 'numeration' => 'romannested' ] ] );
		$this->assertTrue( $nested['block_defaults']['use_ol'] );
		$this->assertTrue( $nested['block_defaults']['hierarchical_numbers'] );

		$none = wpwing_toc_map_luckywp_settings( [ 'general' => [] ] );
		$this->assertFalse( $none['block_defaults']['use_ol'] );
		$this->assertFalse( $none['block_defaults']['hierarchical_numbers'] );
	}

	public function test_general_options_map_to_block_defaults() {
		$mapped = wpwing_toc_map_luckywp_settings( [
			'general' => [
				'title'              => 'Contents',
				'hierarchical'       => true,
				'toggle'             => true,
				'hideItems'          => true,
				'smoothScroll'       => true,
				'smoothScrollOffset' => '40',
				'min'                => '3',
			],
		] );

		$this->assertSame( 'Contents', $mapped['block_defaults']['title_text'] );
		$this->assertFalse( $mapped['block_defaults']['remove_indent'] );
		$this->assertTrue( $mapped['block_defaults']['collapsible'] );
		$this->assertTrue( $mapped['block_defaults']['start_collapsed'] );
		$this->assertTrue( $mapped['block_defaults']['add_smooth'] );
		$this->assertSame( 40, $mapped['block_defaults']['scroll_offset'] );
		$this->assertSame( 3, $mapped['block_defaults']['min_headings'] );
	}

	public function test_negative_offset_and_min_are_clamped() {
		$mapped = wpwing_toc_map_luckywp_settings( [ 'general' => [ 'smoothScrollOffset' => -5, 'min' => 0 ] ] );

		$this->assertSame( 0, $mapped['block_defaults']['scroll_offset'] );
		$this->assertSame( 1, $mapped['block_defaults']['min_headings'] );
	}

	public function test_auto_insert_is_only_mapped_when_enabled() {
		$off = wpwing_toc_map_luckywp_settings( [
			'general'    => [],
			'autoInsert' => [ 'enable' => false, 'postTypes' => [ 'post' ] ],
		] );
		$this->assertArrayNotHasKey( 'auto_insert_post_types', $off );

		$on = wpwing_toc_map_luckywp_settings( [
			'general'    => [ 'min' => 4 ],
			'autoInsert' => [ 'enable' => true, 'postTypes' => [ 'post', 'page' ], 'position' => 'afterfirstblock' ],
		] );
		$this->assertSame( [ 'post', 'page' ], $on['auto_insert_post_types'] );
		$this->assertSame( 'after_first_paragraph', $on['auto_insert_position'] );
		$this->assertSame( 4, $on['auto_insert_min_headings'] );
	}

	public function test_unknown_position_falls_back_to_before_first_heading() {
		$mapped = wpwing_toc_map_luckywp_settings( [
			'general'    => [],
			'autoInsert' => [ 'enable' => true, 'postTypes' => [ 'post' ], 'position' => 'bogus' ],
		] );

		$this->assertSame( 'before_first_heading', $mapped['auto_insert_position'] );
		$this->assertSame( 2, $mapped['auto_insert_min_headings'] );
	}
}
