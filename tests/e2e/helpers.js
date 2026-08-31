async function getPostUrl(request, baseURL, type, slug) {
	const endpoint = type === 'page' ? 'pages' : 'posts';
	// Uses the rest_route query-var form, not the /wp-json/ pretty path, since it works
	// regardless of the site's permalink structure - the seeded dev stack defaults to plain.
	const res = await request.get(`${baseURL}/?rest_route=/wp/v2/${endpoint}&slug=${slug}`);
	const [item] = await res.json();
	if (!item) {
		throw new Error(
			`No published ${type} found with slug "${slug}". Run "make dev" to seed sample content first.`
		);
	}
	return item.link;
}

// Idempotent: a second call within the same test (e.g. settings-reset cleanup) is a no-op
// once the session cookie is already set, rather than re-driving a login form that may not
// render the same way when WordPress knows the session is already authenticated.
async function login(page, baseURL) {
	await page.goto(`${baseURL}/wp-admin/`);
	if (page.url().includes('wp-login.php')) {
		await page.fill('#user_login', 'admin');
		await page.fill('#user_pass', 'password');
		await page.click('#wp-submit');
		await page.waitForURL(/wp-admin/);
	}
}

module.exports = { getPostUrl, login };
