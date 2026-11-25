const { test, expect } = require( '@playwright/test' );
const { getPostUrl, login } = require( './helpers' );

const SETTINGS_URL_PATH = '/wp-admin/admin.php?page=wpwing-toc';

test.describe( 'Reading time and style presets', () => {
	test.afterEach( async ( { page, baseURL } ) => {
		await login( page, baseURL );
		await page.goto( `${ baseURL }${ SETTINGS_URL_PATH }` );
		const checkbox = page.locator(
			'input[name="wpwing_toc_settings[block_defaults][show_reading_time]"]'
		);
		if ( await checkbox.isChecked() ) {
			await checkbox.uncheck();
		}
		await page.selectOption(
			'select[name="wpwing_toc_settings[block_defaults][style_preset]"]',
			'default'
		);
		await page.click( '#submit' );
	} );

	test( 'displays reading time badge when enabled', async ( {
		page,
		request,
		baseURL,
	} ) => {
		await login( page, baseURL );
		await page.goto( `${ baseURL }${ SETTINGS_URL_PATH }` );
		await page.check(
			'input[name="wpwing_toc_settings[block_defaults][show_reading_time]"]'
		);
		await page.click( '#submit' );

		const demoUrl = await getPostUrl(
			request,
			baseURL,
			'page',
			'wpwing-toc-demo'
		);
		await page.goto( demoUrl );

		const readingTime = page.locator( '.wpwing-toc-reading-time' );
		await expect( readingTime ).toBeVisible();
		await expect( readingTime ).toContainText( 'min read' );
	} );

	test( 'applies style preset classes like card or minimal', async ( {
		page,
		request,
		baseURL,
	} ) => {
		await login( page, baseURL );
		await page.goto( `${ baseURL }${ SETTINGS_URL_PATH }` );
		await page.selectOption(
			'select[name="wpwing_toc_settings[block_defaults][style_preset]"]',
			'card'
		);
		await page.click( '#submit' );

		const demoUrl = await getPostUrl(
			request,
			baseURL,
			'page',
			'wpwing-toc-demo'
		);
		await page.goto( demoUrl );

		const toc = page.locator( 'nav.wpwing-toc' );
		await expect( toc ).toHaveClass( /wpwing-toc--card/ );
	} );
} );
