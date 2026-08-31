const { test, expect } = require('@playwright/test');
const { getPostUrl } = require('./helpers');

const SEEDED = [
	{ type: 'page', slug: 'wpwing-toc-demo' },
	{ type: 'post', slug: 'wpwing-toc-article' },
	{ type: 'post', slug: 'wpwing-toc-field-guide' },
	{ type: 'post', slug: 'wpwing-toc-performance' },
];

for (const { type, slug } of SEEDED) {
	test(`${slug}: TOC anchors match heading ids`, async ({ page, request, baseURL }) => {
		const url = await getPostUrl(request, baseURL, type, slug);
		await page.goto(url);

		const headingIds = await page
			.locator('.entry-content :is(h2, h3, h4, h5, h6)[id]')
			.evaluateAll((els) => els.map((el) => el.id));
		const tocHrefs = await page
			.locator('.wpwing-toc-list a[href^="#"]')
			.evaluateAll((els) => els.map((el) => el.getAttribute('href').slice(1)));

		expect(headingIds.length).toBeGreaterThan(0);
		expect(tocHrefs).toEqual(headingIds);
	});

	test(`${slug}: clicking a TOC link jumps to the matching heading`, async ({ page, request, baseURL }) => {
		const url = await getPostUrl(request, baseURL, type, slug);
		await page.goto(url);

		// Native collapse mode can start collapsed, which hides the list until expanded.
		const details = page.locator('.wpwing-toc-details');
		if ((await details.count()) > 0 && !(await details.evaluate((el) => el.open))) {
			await page.locator('.wpwing-toc-details summary').click();
		}

		const firstLink = page.locator('.wpwing-toc-list a[href^="#"]').first();
		const targetId = (await firstLink.getAttribute('href')).slice(1);

		await firstLink.click();
		await expect(page).toHaveURL(new RegExp(`#${targetId}$`));
		await expect(page.locator(`#${targetId}`)).toBeInViewport();
	});
}
