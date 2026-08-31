const { test, expect } = require('@playwright/test');
const { getPostUrl } = require('./helpers');

test('native collapse mode (details/summary) starts collapsed and toggles via keyboard', async ({
	page,
	request,
	baseURL,
}) => {
	const url = await getPostUrl(request, baseURL, 'post', 'wpwing-toc-article');
	await page.goto(url);

	const details = page.locator('.wpwing-toc-details');
	await expect(async () => {
		expect(await details.evaluate((el) => el.open)).toBe(false);
	}).toPass();

	const summary = page.locator('.wpwing-toc-details summary');
	await summary.focus();
	await page.keyboard.press('Enter');
	await expect(async () => {
		expect(await details.evaluate((el) => el.open)).toBe(true);
	}).toPass();

	await page.keyboard.press('Enter');
	await expect(async () => {
		expect(await details.evaluate((el) => el.open)).toBe(false);
	}).toPass();
});

test('JS collapse mode toggles via keyboard with correct aria-expanded', async ({ page, request, baseURL }) => {
	const url = await getPostUrl(request, baseURL, 'post', 'wpwing-toc-performance');
	await page.goto(url);

	const toggle = page.locator('.wpwing-toc-toggle');
	const list = page.locator('.wpwing-toc-list');

	await expect(toggle).toHaveAttribute('aria-expanded', 'true');
	await expect(list).toBeVisible();

	await toggle.focus();
	await page.keyboard.press('Enter');
	await expect(toggle).toHaveAttribute('aria-expanded', 'false');
	await expect(list).toBeHidden();

	await page.keyboard.press('Space');
	await expect(toggle).toHaveAttribute('aria-expanded', 'true');
	await expect(list).toBeVisible();
});
