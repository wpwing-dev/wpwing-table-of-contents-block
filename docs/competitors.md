# Competitor Research - Table of Contents Plugins

Last researched: 11/07/2026 (wordpress.org plugin directory)

## Our baseline

**Table of Contents (TOC) Block - Fast & SEO Friendly** (slug: `wpwing-table-of-contents-block`)

- Active installs: 10+
- Rating: 4.0 (1 review)
- Version: 1.5.0, released 05/07/2026
- Positioning: Gutenberg-native block, zero frontend JS by default, weekly releases

## Competitor overview

| Plugin | Slug | Installs | Rating | Last updated | Block-based? |
|---|---|---|---|---|---|
| Easy Table of Contents | `easy-table-of-contents` | 600,000+ | 4.4 (219) | 10/06/2026 | Auto-insert first; Gutenberg block is Pro |
| Table of Contents Plus | `table-of-contents-plus` | 200,000+ | (159) | Stale (tested 6.7.5) | No (shortcode) |
| LuckyWP Table of Contents | `luckywp-table-of-contents` | 100,000+ | 4.9 (884) | 16/04/2025, effectively abandoned | Block + shortcode + widget |
| Rich Table of Contents | `rich-table-of-content` | 20,000+ | 4.1 | ~1 year ago | No (auto-insert/shortcode) |
| SimpleTOC | `simpletoc` | 10,000+ | 5.0 (76) | June 2026, active | Yes, pure block |
| Joli Table Of Contents | `joli-table-of-contents` | 7,000+ | 4.9 (43) | 04/2026, active | Block + auto-insert + shortcode |
| Heroic Table of Contents | `heroic-table-of-contents` | 5,000+ | 4.7 (9) | 07/01/2026 | Yes, pure block |
| Table Of Content Block (bPlugins) | `table-of-content-block` | 3,000+ | 5.0 (3) | 14/06/2026 | Yes, pure block |
| TOP Table Of Contents (BoomDevs) | `top-table-of-contents` | 3,000+ | - | - | - |

## Detailed notes

### Easy Table of Contents (market leader)

- 600,000+ installs, v2.0.85, actively maintained by Magazine3, roughly monthly releases.
- Free: auto-insert by post type, parses Gutenberg/Divi/Elementor/WPBakery, appearance themes, sticky widget, smooth scroll, migration tool importing settings from Table of Contents Plus.
- Pro: Gutenberg block builder, Elementor widget, fixed/sticky TOC, full AMP, ACF fields, collapsible sub-headings, read time.
- Weakness we exploit: heavy settings page, loads CSS/JS on the frontend, block support is an afterthought (Pro only). Recent changelogs are all conflict fixes with page builders.

### LuckyWP Table of Contents (abandoned giant)

- 100,000+ installs, 4.9 stars, but last release 16/04/2025 and flagged "not tested with the latest 3 major releases". Its users are ripe for migration.
- Loved for: pretty URL hashes, smooth scroll with offset setting, color schemes, RTL support, insertion flexibility.

### Table of Contents Plus (legacy, stale)

- 200,000+ installs but shortcode-era plugin, tested only to 6.7.5. Easy TOC actively poaches its users with a migration tool. Another migration pool.

### SimpleTOC (closest philosophical competitor)

- 10,000+ installs, perfect 5.0 rating, v7.1.1, very active.
- Same pitch as ours: minimal HTML, no JS/CSS by default, Gutenberg-native.
- Differentiators it has: native `<details>`/`<summary>` collapsible (works with zero JS), WCAG 2.2 AA claim, global admin settings that override block settings, 18 locales, PHPUnit coverage.
- This is the plugin to beat on quality; we beat it on feature breadth (numbering, back-to-top, keyword exclude, copy link, badge).

### Joli Table Of Contents (best freemium model to study)

- 7,000+ installs, 4.9 stars, v3.0.2, active.
- Free: auto-insert + block + shortcode, themes with dark mode, color palettes, onboarding wizard, numbering styles, RTL/WPML.
- Pro: floating/slide-out/sticky TOC, progress bar, timeline view, multi-column, per-post-type auto-insert rules, per-device visibility. Their Pro list overlaps heavily with our planned Pro tier - good pricing/packaging reference.

### Heroic Table of Contents

- 5,000+ installs, block-based, 4 style presets, collapsible, list styles. Slow release cadence (quarterly). Backed by HeroThemes as a lead-gen for their KB products.

### Table Of Content Block (bPlugins)

- 3,000+ installs, young but active (v1.0.9, 14/06/2026). Basic feature set, styling-focused. Watching for growth; not a threat yet.

## Takeaways

1. **Auto-insert is the biggest functional gap.** Every plugin above us in installs has it; we are block-manual-placement only. It is also the main reason users pick Easy TOC.
2. **Two large migration pools exist:** LuckyWP (100k, abandoned) and TOC+ (200k, stale). A settings importer + "switching from LuckyWP" docs could capture searches.
3. **SimpleTOC owns the "lightweight" keyword with a 5.0 rating.** Matching its no-JS `<details>` collapsible and global-defaults settings closes the gap while we win on features.
4. **Common free features we still lack:** auto-insert, smooth-scroll offset for sticky headers, RTL verification, global defaults, migration tool.
5. **Pro tier direction confirmed by Joli/Easy TOC Pro:** sticky/floating TOC, scrollspy/progress, reading time, per-device visibility, advanced insert rules. Matches our deferred Pro list.
