const { test, expect } = require('@playwright/test');
const { getPostUrl, login } = require('./helpers');

const SETTINGS_URL_PATH = '/wp-admin/options-general.php?page=wpwing-toc';

// Resets the shared dev stack's block defaults so a test run doesn't leave state behind
// for the next run or for manual testing in the same environment.
async function resetBlockDefaults(page, baseURL) {
	await login(page, baseURL);
	await page.goto(`${baseURL}${SETTINGS_URL_PATH}`);
	for (const name of ['copy_anchor', 'show_heading_count', 'show_back_to_top']) {
		const checkbox = page.locator(`input[name="wpwing_toc_settings[block_defaults][${name}]"]`);
		if (await checkbox.isChecked()) {
			await checkbox.uncheck();
		}
	}
	await page.fill('input[name="wpwing_toc_settings[block_defaults][scroll_offset]"]', '0');
	await page.selectOption('select[name="wpwing_toc_settings[block_defaults][style_preset]"]', 'default');
	await page.click('#submit');
}

test('settings-page block defaults flow through to a block with no explicit attributes', async ({
	page,
	request,
	baseURL,
}) => {
	try {
		await login(page, baseURL);
		await page.goto(`${baseURL}${SETTINGS_URL_PATH}`);

		await page.check('input[name="wpwing_toc_settings[block_defaults][copy_anchor]"]');
		await page.check('input[name="wpwing_toc_settings[block_defaults][show_heading_count]"]');
		await page.check('input[name="wpwing_toc_settings[block_defaults][show_back_to_top]"]');
		await page.fill('input[name="wpwing_toc_settings[block_defaults][scroll_offset]"]', '80');
		await page.selectOption('select[name="wpwing_toc_settings[block_defaults][style_preset]"]', 'boxed');
		// WP admin strips "settings-updated=true" from the URL via history.replaceState() shortly
		// after load, so the save is verified by its effect on the rendered block below instead.
		await page.click('#submit');

		const demoUrl = await getPostUrl(request, baseURL, 'page', 'wpwing-toc-demo');
		await page.goto(demoUrl);

		await expect(page.locator('.wpwing-toc')).toHaveClass(/wpwing-toc--boxed/);
		await expect(page.locator('.wpwing-toc-count')).toBeVisible();
		await expect(page.locator('.wpwing-toc-back-top')).toBeVisible();

		const firstHeading = page.locator('.entry-content :is(h2, h3, h4, h5, h6)[id]').first();
		await expect(firstHeading).toHaveCSS('scroll-margin-top', '80px');

		// Copy-link is hover-revealed; keyboard/touch users get it via :focus-visible instead (see
		// doc/accessibility-audit.md), so force the click past Playwright's hover-visibility check.
		const firstRow = page.locator('.wpwing-toc-list li').first();
		// Direct-child combinator: nested sub-headings are also <li> descendants of the first
		// row and carry their own copy buttons, which a plain descendant selector would also match.
		const copyButton = firstRow.locator(':scope > .wpwing-toc-copy');
		await firstRow.hover();
		await copyButton.click({ force: true });
		await expect(copyButton).toHaveClass(/is-copied/);
	} finally {
		await resetBlockDefaults(page, baseURL);
	}
});
