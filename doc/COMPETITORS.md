# Competitor Research - Table of Contents Plugins

Last researched: 11/09/2026 (wordpress.org plugin directory). Previous pass: 11/07/2026.
Refresh alongside [MARKETING-PLAN.md](MARKETING-PLAN.md) at the start of every quarter, and
re-check any row before it is used to justify release scope in [UPDATE-PLAN.md](UPDATE-PLAN.md).

## Our baseline

**Table of Contents (TOC) Block - Fast & SEO Friendly** (slug: `wpwing-table-of-contents-block`)

- Active installs: 10+
- Rating: 4.0 (1 review)
- Version: 1.10.0, released 09/09/2026
- Tested up to: 7.1 (current)
- Positioning: Gutenberg-native block, zero frontend JS by default, weekly-to-biweekly releases

## Competitor overview

| Plugin | Slug | Installs | Rating (reviews) | Version | Last updated | Block-based? |
|---|---|---|---|---|---|---|
| Easy Table of Contents | `easy-table-of-contents` | 600,000+ | 4.4 (219) | 2.0.87.1 | 09/09/2026, active | Auto-insert first; Gutenberg block is Pro |
| Table of Contents Plus | `table-of-contents-plus` | 200,000+ | 4.4 (178) | 202608.2 | 21/08/2026, active | No (shortcode/auto-insert), AIOSEO-rewritten |
| LuckyWP Table of Contents | `luckywp-table-of-contents` | 100,000+ | 4.9 (885) | 2.1.14 | 16/04/2025, abandoned | Block + shortcode + widget |
| Rich Table of Contents | `rich-table-of-content` | 20,000+ | 4.1 (17) | 1.4.3 | ~09/2025, stale | No (auto-insert/shortcode) |
| SimpleTOC | `simpletoc` | 10,000+ | 4.9 (77) | 7.3.0 | ~21/08/2026, active | Yes, pure block |
| Joli Table Of Contents | `joli-table-of-contents` | 7,000+ | 4.9 (43) | 3.0.3 | ~14/08/2026, active | Block + auto-insert + shortcode |
| Heroic Table of Contents | `heroic-table-of-contents` | 5,000+ | 4.7 (9) | 1.2.7 | 07/01/2026, slow | Yes, pure block |
| Table Of Content Block (bPlugins) | `table-of-content-block` | 3,000+ | 5.0 (4) | 1.0.10 | ~17/08/2026, active | Yes, pure block |
| TOP Table Of Contents (BoomDevs) | `top-table-of-contents` | closed | - | 1.4.1 | Closed 09/06/2026 | - |

## What changed since 11/07/2026

Three findings invalidate assumptions the previous pass and the old update plan were built on.

1. **Table of Contents Plus is no longer a migration pool.** It was acquired by Syed Balkhi
   and the AIOSEO team and rewritten in 07/2026: date-style versioning (`202608.2`), a
   redesigned settings screen on the AIOSEO framework with Display / Appearance / Behavior /
   Advanced tabs, a live preview panel, new numbering styles, and title alignment. Tested up
   to 7.1, shipping roughly monthly, rating 4.4 across 178 reviews. Treat it as a
   well-funded, actively developed competitor with an SEO-brand distribution advantage, not
   as an abandoned plugin to poach.
2. **TOP Table Of Contents (BoomDevs) was closed by wordpress.org on 09/06/2026** for a
   guideline violation. Its reviews cite trackers and GDPR complaints, a 1.3.22 update that
   crashed sites, and settings lost across updates. Its install base is now unsupported and
   receiving no security fixes - a second migration pool, and a cautionary example for our
   own admin-notice and telemetry choices (see MARKETING-PLAN.md section 1d).
3. **Rich Table of Contents has gone stale** - v1.4.3, roughly a year old, tested only to
   6.8.8, carrying the "not tested with the latest 3 major releases" banner. 20,000 installs
   with no maintainer response. Lower value than LuckyWP but the same shape of opportunity.

LuckyWP is unchanged and still abandoned: 100,000+ installs, 4.9 across 885 reviews, last
release 16/04/2025, tested only to 6.7.7. Our 1.10.0 importer targets exactly this pool and
remains the single best-aimed feature we have shipped.

## Detailed notes

### Easy Table of Contents (market leader)

- 600,000+ installs, v2.0.87.1, Magazine3, releasing roughly monthly.
- Free: auto-insert by post type, parses Gutenberg/Divi/Elementor/WPBakery, appearance
  themes, sticky widget, smooth scroll, migration tool importing Table of Contents Plus settings.
- Pro ($49/yr single site, $79/yr 10 sites, $99/yr 25 sites, $499 lifetime): Gutenberg block
  builder, Elementor widget, fixed/sticky TOC, full AMP, ACF fields, collapsible
  sub-headings, read time.
- Recent changelog direction: TOC link click tracking, splitting the TOC on heading-heavy
  pages, ARIA roles and labels, WooCommerce checkout shortcode crash fixes, per-device
  (tablet) sticky customization.
- Weakness we exploit: heavy settings page, unconditional frontend CSS/JS, block support is
  Pro-only and an afterthought, and a large share of releases are page-builder conflict fixes.

### Table of Contents Plus (rebuilt, AIOSEO-backed)

- 200,000+ installs, 4.4 (178 reviews), v202608.2, under Syed Balkhi / AIOSEO since mid-2026.
- Now shipping live preview, numbering styles, title alignment, collapse control styling.
- Still shortcode/auto-insert rather than block-native - that remains the wedge - but expect
  AIOSEO cross-promotion inside a 3M+ install SEO plugin footprint. This is now the fastest
  growing threat in the category.

### LuckyWP Table of Contents (abandoned giant, our migration target)

- 100,000+ installs, 4.9 (885 reviews), last release 16/04/2025, tested to 6.7.7.
- Loved for: pretty URL hashes, smooth scroll with offset, color schemes, RTL support,
  insertion flexibility.
- Our 1.10.0 importer maps list style, title, minimum-headings threshold, smooth scroll, and
  auto-insert settings. Heading level range is deliberately not imported - LuckyWP's "depth"
  is relative to each post's own headings, not a sitewide absolute level.

### SimpleTOC (closest philosophical competitor)

- 10,000+ installs, 4.9 (77 reviews), v7.3.0, active, tested to 7.1.
- Same pitch as ours: minimal HTML, no JS/CSS by default, Gutenberg-native, FSE-friendly -
  reviews specifically praise full site editing compatibility.
- Differentiators it has: native `<details>`/`<summary>` collapsible (we shipped this in
  1.8.0), WCAG 2.2 AA claim, global admin settings that override block settings (we shipped
  this in 1.9.0), 18 locales, PHPUnit coverage.
- Remaining gaps against it: locales (we ship none) and PHP unit tests (we have Playwright
  e2e only). This is the plugin to beat on quality; we beat it on feature breadth
  (numbering, back-to-top, keyword exclude, copy link, badge, LuckyWP import).

### Joli Table Of Contents (best freemium model to study)

- 7,000+ installs, 4.9 (43 reviews), v3.0.3, active.
- Free: auto-insert + block + shortcode, themes with dark mode, color palettes, onboarding
  wizard, numbering styles, RTL/WPML.
- Pro: Solo $29.99 (1 site), Guru $47 (5 sites), Hero $97 (15 sites), all 1 year of support
  and updates. Features: floating/slide-out/sticky TOC, progress bar, timeline view,
  multi-column, per-post-type auto-insert rules, per-device visibility.
- Their Pro list overlaps almost exactly with our deferred Pro list, and their ladder sits
  well below Easy TOC's $49/$79/$99. Any WPWing Pro pricing should be derived from these two
  ladders, not invented.

### Heroic Table of Contents

- 5,000+ installs, 4.7 (9 reviews), v1.2.7, last release 07/01/2026, tested only to 6.9.0.
- Free-only, no premium tier; block-based, 4 style presets, collapsible, list styles.
- Backed by HeroThemes as lead-gen for their KB products, which explains the slow cadence.
  Drifting toward the stale bucket - worth re-checking next quarter.

### Table Of Content Block (bPlugins)

- 3,000+ installs, 5.0 (4 reviews), v1.0.10, active (~17/08/2026), tested to 6.9.7.
- Our nearest peer by age and shape: young, block-only, styling-focused. Growing faster than
  us from a similar start. Watch its install bracket each quarter.

### TOP Table Of Contents (BoomDevs) - closed

- Removed from the directory 09/06/2026 for a guideline violation; 12 reviews cite trackers,
  GDPR concerns, a site-crashing update, and lost settings.
- Practical value to us: an unsupported install base with no update path, and a clear signal
  that admin-side branding and telemetry carry real directory risk.

## Takeaways

1. **Auto-insert is closed** (1.7.0) and **global defaults are closed** (1.9.0). The
   remaining functional gaps against larger free competitors are sticky/floating TOC,
   scrollspy/active-highlight, and reading time - all of which the leaders gate behind Pro,
   so shipping any of them free is a differentiator rather than parity.
2. **Two live migration pools remain: LuckyWP (100k, abandoned) and Rich TOC (20k, stale),
   plus the orphaned TOP TOC base.** TOC+ is gone as a pool. The 1.10.0 LuckyWP importer is
   the template; a second importer is cheap once the mapping layer is generalized.
3. **SimpleTOC owns the "lightweight" keyword with 4.9 across 77 reviews.** We have matched
   its two headline technical features. The gap is now credibility: locales, unit tests, and
   review count.
4. **Easy TOC and TOC+ are both adding polish, not architecture.** Neither can become
   block-native without breaking its own user base. Block-first stays defensible.
5. **Pricing reference for a future Pro tier:** Joli $29.99/$47/$97, Easy TOC $49/$79/$99 +
   $499 lifetime, Heroic free-only. A WPWing single-site license belongs between Joli Solo
   and Easy TOC Pro - derive the exact ladder when a Pro launch is actually scheduled.
6. **Directory risk is real:** TOP TOC was closed for guideline violations. Keep admin
   notices dismissible, ship no trackers, and never lose user settings across an update.
