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