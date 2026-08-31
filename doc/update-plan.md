# Update Plan - Next 4 Weekly Releases

Written: 11/07/2026, updated 31/08/2026. Cadence: one release every Sunday (slipping in
practice - 1.8.0 shipped Monday 24/08, not Sunday 26/07). Current version: 1.9.0 (31/08/2026).

Strategy for the month: close the top gaps found in [competitors.md](competitors.md) - quick wins first, then the auto-insert headline feature, then lightweight/quality parity with SimpleTOC, then start capturing users from abandoned competitors.

## v1.6.0 - Sunday 12/07/2026 (small scope, one day left) - DONE, implemented 11/07

- New: Smooth-scroll offset setting - pixel offset so anchors are not hidden under sticky/fixed headers (top LuckyWP feature, frequent complaint across all TOC plugins). Implement via `scroll-margin-top` CSS on heading anchors, no JS needed.
- Fix: Deferred known bug - paginated posts (`<!--nextpage-->`) with plain permalinks build broken page URLs.
- Improvement: RTL pass - verify indent, numbering, and copy-link button render correctly in RTL locales; add `[dir="rtl"]` CSS where needed.

## v1.7.0 - Sunday 19/07/2026 (headline: auto-insert) - DONE, implemented 19/07

The biggest gap vs every larger competitor. Full week reserved.

- New: Auto-insert TOC without placing the block - plugin settings screen (Settings > TOC Block) with:
  - Enable per post type (posts, pages, public CPTs).
  - Position: before first heading / after first paragraph / top of content.
  - Auto-insert uses the same renderer and respects min_headings, so it stays silent on short posts.
- Manual block placement in a post overrides auto-insert for that post.
- Keep the "no frontend JS" promise: auto-insert output is identical to block output.

## v1.8.0 - 24/08/2026 (native collapse, dev workflow)

- New: Zero-JS collapsible mode using native `<details>`/`<summary>` - removes the last inline script for collapsible users; keep the current JS toggle as a style choice.
- Improvement: Added local Docker development workflow and realistic seeded sample content.

## v1.9.0 - 31/08/2026 (foundation: defaults, accessibility, e2e) - DONE, implemented 31/08

Global defaults and the accessibility audit were originally slated for 1.8.0, then deferred
again when 1.9.0 grew a second, unrelated theme (the LuckyWP migration push). Split for real
this time: this release is foundation/quality work only, no external dependency.

- New: Complete global defaults - the "New block defaults" settings section now covers the
  full block attribute set: list style and numbering, thresholds and keyword filtering,
  back-to-top and copy-link, smooth scroll offset, and title/count/search-snippet options.
- Improvement: Accessibility audit completed and documented in
  [accessibility-audit.md](accessibility-audit.md). No structural or keyboard gaps found;
  the readme claim is scoped to default styling rather than an unqualified WCAG 2.2 AA badge,
  since author-chosen colors and the de-emphasized count/number text aren't contrast-checked.
- Testing: Added a checked-in Playwright e2e suite (`tests/e2e/`) covering rendering/anchor
  sync, anchor-click scrolling, native and JS collapse modes, keyboard flow, and a live
  settings-page-to-render check for the new global defaults - runs against the `make dev`
  stack via `npm run test:e2e`. Requires Node 20+ (see readme.md Requirements).

## v1.10.0 - capture migration pools

- New: One-click settings importer from LuckyWP Table of Contents (100k installs, abandoned since 04/2025) - map heading levels, list style, title, smooth scroll to our attributes/defaults. Stretch: Table of Contents Plus import.
- Improvement: readme.txt refresh targeting "LuckyWP alternative" / "Table of Contents Plus alternative" search terms; add FAQ entries about switching.
- Housekeeping: bump "Tested up to", screenshots for the new settings screen.

## Backlog (not this month)

- Pro tier scaffolding and launch (see roadmap memory): scrollspy/active-heading highlight, sticky/floating TOC, progress bar, reading time, localStorage collapse state, regex/CSS-class exclude, Roman/alpha numbering, per-device visibility.
- Elementor/Classic editor support via shortcode wrapper - only if user requests appear; conflicts with block-first positioning.
- Additional locales once strings stabilize (SimpleTOC ships 18).

## Risks / notes

- Auto-insert (1.7.0) is the largest change since launch - it moves rendering out of the block into a content filter. Reuse the existing render pipeline; verify with the /verify skill against paginated, nested-block, and CPT content.
- Settings screen in 1.7.0 is deliberately built one week before global defaults (1.8.0) needs it - keep the options schema extensible.
- If 1.7.0 slips, ship the settings screen + global defaults first and move auto-insert to 1.8.0; do not rush the content filter.
