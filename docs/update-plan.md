# Update Plan - Next 4 Weekly Releases

Written: 11/07/2026. Cadence: one release every Sunday. Current version: 1.5.0 (05/07/2026).

Strategy for the month: close the top gaps found in [competitors.md](competitors.md) - quick wins first, then the auto-insert headline feature, then lightweight/quality parity with SimpleTOC, then start capturing users from abandoned competitors.

## v1.6.0 - Sunday 12/07/2026 (small scope, one day left)

- New: Smooth-scroll offset setting - pixel offset so anchors are not hidden under sticky/fixed headers (top LuckyWP feature, frequent complaint across all TOC plugins). Implement via `scroll-margin-top` CSS on heading anchors, no JS needed.
- Fix: Deferred known bug - paginated posts (`<!--nextpage-->`) with plain permalinks build broken page URLs.
- Improvement: RTL pass - verify indent, numbering, and copy-link button render correctly in RTL locales; add `[dir="rtl"]` CSS where needed.

## v1.7.0 - Sunday 19/07/2026 (headline: auto-insert)

The biggest gap vs every larger competitor. Full week reserved.

- New: Auto-insert TOC without placing the block - plugin settings screen (Settings > TOC Block) with:
  - Enable per post type (posts, pages, public CPTs).
  - Position: before first heading / after first paragraph / top of content.
  - Auto-insert uses the same renderer and respects min_headings, so it stays silent on short posts.
- Manual block placement in a post overrides auto-insert for that post.
- Keep the "no frontend JS" promise: auto-insert output is identical to block output.

## v1.8.0 - Sunday 26/07/2026 (SimpleTOC parity, quality)

- New: Global defaults - on the settings screen from 1.7.0, set default values for block attributes (levels, list style, title, etc.); new blocks start from these defaults.
- New: Zero-JS collapsible mode using native `<details>`/`<summary>` - removes the last inline script for collapsible users; keep the current JS toggle as a style choice.
- Improvement: Accessibility audit toward a WCAG 2.2 AA claim in the readme (focus states, aria attributes, keyboard flow on copy-link and toggle).

## v1.9.0 - Sunday 02/08/2026 (capture migration pools)

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
