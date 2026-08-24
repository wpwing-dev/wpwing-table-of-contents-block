#!/bin/sh
set -eu

cd /var/www/html

if ! wp core is-installed --allow-root; then
	wp core install \
		--url=https://toc.local \
		--title="WPWing TOC Development" \
		--admin_user=admin \
		--admin_password=password \
		--admin_email=admin@example.com \
		--skip-email \
		--allow-root
fi

wp plugin activate wpwing-table-of-contents-block --allow-root

create_sample_content() {
	post_type="$1"
	slug="$2"
	title="$3"
	content="$4"

	if test -n "$(wp post list --post_type="$post_type" --name="$slug" --format=ids --allow-root)"; then
		return
	fi

	wp post create \
		--post_type="$post_type" \
		--post_name="$slug" \
		--post_title="$title" \
		--post_status=publish \
		--post_content="$content" \
		--porcelain \
		--allow-root >/dev/null
}

create_sample_content page wpwing-toc-demo "WPWing TOC Demo" '<!-- wp:wpwing/toc /-->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Getting started</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>This page demonstrates the Table of Contents block in the local development environment.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Add the block</h3>
<!-- /wp:heading -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Choose a display style</h3>
<!-- /wp:heading -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Test interactive options</h2>
<!-- /wp:heading -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Native collapsible mode</h3>
<!-- /wp:heading -->'

create_sample_content post wpwing-toc-article "A Sample Article for Testing" '<!-- wp:paragraph -->
<p>This sample post contains enough structure to test anchors, nested headings, and responsive TOC styling.</p>
<!-- /wp:paragraph -->

<!-- wp:wpwing/toc {"collapsible":true,"collapse_mode":"native","start_collapsed":true} /-->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Planning the article</h2>
<!-- /wp:heading -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Collect the requirements</h3>
<!-- /wp:heading -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Outline the sections</h3>
<!-- /wp:heading -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Reviewing the result</h2>
<!-- /wp:heading -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Check keyboard navigation</h3>
<!-- /wp:heading -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Check mobile layout</h3>
<!-- /wp:heading -->'