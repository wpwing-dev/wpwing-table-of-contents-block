const { defineConfig } = require('@playwright/test');

module.exports = defineConfig({
	testDir: 'tests/e2e',
	// All specs share one WordPress instance (see docker/wordpress/setup.sh), and
	// global-defaults.spec.js temporarily mutates site-wide settings - workers: 1 keeps that
	// from racing against other files reading the same pages in parallel.
	workers: 1,
	reporter: 'list',
	use: {
		baseURL: process.env.WPWING_TOC_BASE_URL || 'https://toc.local',
		ignoreHTTPSErrors: true,
		// The copy-link button uses navigator.clipboard.writeText(), which needs this permission
		// granted up front in a headless browser (there's no user gesture to prompt for it).
		permissions: ['clipboard-write'],
	},
});
