---
name: verify
description: Verify TOC block changes end-to-end in a throwaway WordPress (docker compose + wp-cli + headless Chrome)
---

# Verify the TOC block in a real WordPress

The Local (by Flywheel) site in this tree is usually NOT running (its per-site MySQL is off), and Node-based runners fail here: this machine's IPv6 is broken and Node's fetch/got try IPv6 first, so `@wp-playground/cli`, `wp-now`, and `@wordpress/env` all time out or crash. Use docker compose with the already-pulled `wordpress:latest` + `mysql:8.0` images instead.

## Recipe

1. `npm run build` in the plugin dir (frontend renders from `build/`).
2. In a scratch dir, write a `docker-compose.yml` with `db` (mysql:8.0), `wp` (wordpress:latest, port 9412, plugin dir bind-mounted into `/var/www/html/wp-content/plugins/wpwing-table-of-contents-block`, named volume for `/var/www/html`), and `cli` (wordpress:cli, same mounts, `user: "33:33"`).
3. `docker compose up -d db wp`, then wait: `until docker compose exec db mysqladmin ping -uwp -pwp --silent; do sleep 3; done`
4. `docker compose run --rm cli wp core install --url=http://localhost:9412 --title=X --admin_user=admin --admin_password=admin --admin_email=admin@example.com --skip-email`
5. `docker compose run --rm cli wp plugin activate wpwing-table-of-contents-block`
6. Seed posts via **stdin** (bind-mounted scratch files are unreadable by uid 33): `docker compose run --rm -T cli wp post create - --post_title=T --post_status=publish --porcelain < post.html`
7. `curl -s http://localhost:9412/?p=ID` and grep the output.
8. JS behavior: headless Chrome exists at `/usr/bin/google-chrome`; playwright lib is in the plugin's `node_modules` - run driver scripts with `NODE_PATH=<plugin>/node_modules node drive.js`.
9. Tear down: `docker compose down -v`.

## What to check on any anchor-related change

Heading IDs are generated on BOTH the content side and the TOC side and must match byte-for-byte (see memory: anchor-id-dual-sync). Seed a post with: a UTF-8 heading (Über/Café/emoji), two duplicate-text headings, a duplicate-text heading with a custom `id` anchor, a `wpwing-toc-hidden` heading, and a heading nested in a Group. Then compare `grep -oE '<h[23][^>]*id="[^"]*"'` against the hrefs inside `wpwing-toc-list`.

## Gotchas

- The copy-link button only shows on hover; click it with `{ force: true }` after `page.hover()`.
- WordPress inlines the plugin's CSS into the page, so grepping for class names matches CSS, not just markup - grep for `querySelectorAll` to detect the plugin's inline JS.
- Default permalinks are plain (`/?p=ID`) in a fresh install.
