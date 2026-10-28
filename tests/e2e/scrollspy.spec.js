const { test, expect } = require( '@playwright/test' );
const { getPostUrl, login } = require( './helpers' );

const SETTINGS_URL_PATH = '/wp-admin/admin.php?page=wpwing-toc';

async function resetScrollspyDefaults( page, baseURL ) {
	await login( page, baseURL );
	await page.goto( `${ baseURL }${ SETTINGS_URL_PATH }` );
	const checkbox = page.locator(
		'input[name="wpwing_toc_settings[block_defaults][scrollspy]"]'
	);
	if ( await checkbox.isChecked() ) {
		await checkbox.uncheck();
	}
	await page.click( '#submit' );
}

test.describe( 'Scrollspy active heading highlight', () => {
	test.beforeEach( async ( { page, baseURL } ) => {
		await resetScrollspyDefaults( page, baseURL );
	} );

	test.afterEach( async ( { page, baseURL } ) => {
		await resetScrollspyDefaults( page, baseURL );
	} );

	test( 'output has zero scrollspy JS when disabled', async ( {
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
		await expect( toc ).not.toHaveClass( /wpwing-toc--scrollspy/ );

		const content = await page.content();
		expect( content ).not.toContain( 'wpwing-toc--scrollspy' );
		expect( content ).not.toContain( 'rootMargin' );
	} );

	test( 'highlights active link as page scrolls and handles nested headings', async ( {
		page,
		request,
		baseURL,
	} ) => {
		await login( page, baseURL );
		await page.goto( `${ baseURL }${ SETTINGS_URL_PATH }` );
		await page.check(
			'input[name="wpwing_toc_settings[block_defaults][scrollspy]"]'
		);
		await page.click( '#submit' );

		const url = await getPostUrl(
			request,
			baseURL,
			'post',
			'wpwing-toc-field-guide'
		);
		await page.goto( url );

		const toc = page.locator( '.wpwing-toc' );
		await expect( toc ).toHaveClass( /wpwing-toc--scrollspy/ );

		const targetHeading = page.locator( '#map-the-reader-journey' );
		await targetHeading.evaluate( ( el ) =>
			el.scrollIntoView( { block: 'start' } )
		);

		const activeLink = page.locator(
			'.wpwing-toc-list a[href="#map-the-reader-journey"]'
		);
		await expect( activeLink ).toHaveClass( /is-active/ );

		const otherLink = page.locator(
			'.wpwing-toc-list a[href="#start-with-a-clear-structure"]'
		);
		await expect( otherLink ).not.toHaveClass( /is-active/ );
	} );

	test( 'clicking a TOC link updates active heading state', async ( {
		page,
		request,
		baseURL,
	} ) => {
		await login( page, baseURL );
		await page.goto( `${ baseURL }${ SETTINGS_URL_PATH }` );
		await page.check(
			'input[name="wpwing_toc_settings[block_defaults][scrollspy]"]'
		);
		await page.click( '#submit' );

		const url = await getPostUrl(
			request,
			baseURL,
			'post',
			'wpwing-toc-field-guide'
		);
		await page.goto( url );

		const targetLink = page.locator(
			'.wpwing-toc-list a[href="#review-the-finished-page"]'
		);
		await targetLink.click();

		await expect( targetLink ).toHaveClass( /is-active/ );
	} );
} );
