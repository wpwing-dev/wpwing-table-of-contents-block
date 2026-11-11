const { test, expect } = require( '@playwright/test' );
const { getPostUrl, login } = require( './helpers' );

const SETTINGS_URL_PATH = '/wp-admin/admin.php?page=wpwing-toc';

async function resetStickyDefaults( page, baseURL ) {
	await login( page, baseURL );
	await page.goto( `${ baseURL }${ SETTINGS_URL_PATH }` );
	const checkbox = page.locator(
		'input[name="wpwing_toc_settings[block_defaults][sticky]"]'
	);
	if ( await checkbox.isChecked() ) {
		await checkbox.uncheck();
	}
	await page.fill(
		'input[name="wpwing_toc_settings[block_defaults][scroll_offset]"]',
		'0'
	);
	await page.click( '#submit' );
}

test.describe( 'Sticky / Floating TOC', () => {
	test.beforeEach( async ( { page, baseURL } ) => {
		await resetStickyDefaults( page, baseURL );
	} );

	test.afterEach( async ( { page, baseURL } ) => {
		await resetStickyDefaults( page, baseURL );
	} );

	test( 'output has no sticky class when disabled', async ( {
		page,
		request,
		baseURL,
	} ) => {
		const url = await getPostUrl(
			request,
			baseURL,
			'page',
			'wpwing-toc-demo'
		);
		await page.goto( url );

		const toc = page.locator( '.wpwing-toc' );
		await expect( toc ).not.toHaveClass( /wpwing-toc--sticky/ );

		const styleTag = page
			.locator( 'style' )
			.filter( { hasText: '--wpwing-toc-sticky-top' } );
		await expect( styleTag ).toHaveCount( 0 );
	} );

	test( 'applies sticky positioning and custom top offset on desktop', async ( {
		page,
		request,
		baseURL,
	} ) => {
		await login( page, baseURL );
		await page.goto( `${ baseURL }${ SETTINGS_URL_PATH }` );
		await page.check(
			'input[name="wpwing_toc_settings[block_defaults][sticky]"]'
		);
		await page.fill(
			'input[name="wpwing_toc_settings[block_defaults][scroll_offset]"]',
			'60'
		);
		await page.click( '#submit' );

		const url = await getPostUrl(
			request,
			baseURL,
			'page',
			'wpwing-toc-demo'
		);
		await page.setViewportSize( { width: 1024, height: 768 } );
		await page.goto( url );

		const toc = page.locator( '.wpwing-toc' );
		await expect( toc ).toHaveClass( /wpwing-toc--sticky/ );
		await expect( toc ).toHaveCSS( 'position', 'sticky' );
		await expect( toc ).toHaveCSS( 'overflow-y', 'auto' );

		const hasStickyTopStyle = await page.evaluate( () => {
			const styles = Array.from( document.querySelectorAll( 'style' ) );
			return styles.some( ( s ) =>
				( s.textContent || '' ).includes(
					'--wpwing-toc-sticky-top:60px;'
				)
			);
		} );
		expect( hasStickyTopStyle ).toBe( true );
	} );

	test( 'unpins on mobile viewports under 782px', async ( {
		page,
		request,
		baseURL,
	} ) => {
		await login( page, baseURL );
		await page.goto( `${ baseURL }${ SETTINGS_URL_PATH }` );
		await page.check(
			'input[name="wpwing_toc_settings[block_defaults][sticky]"]'
		);
		await page.click( '#submit' );

		const url = await getPostUrl(
			request,
			baseURL,
			'post',
			'wpwing-toc-field-guide'
		);
		await page.setViewportSize( { width: 500, height: 800 } );
		await page.goto( url );

		const toc = page.locator( '.wpwing-toc' );
		await expect( toc ).toHaveClass( /wpwing-toc--sticky/ );
		await expect( toc ).toHaveCSS( 'position', 'static' );
	} );
} );
