# Update Plan - Biweekly Releases: Sept 2026 - Mar 2027 (Q4 2026 + Q1 2027)

**Target plugin:** Table of Contents (TOC) Block - Fast & SEO Friendly (`wpwing-table-of-contents-block`)
**Current baseline:** v1.10.0 shipped 09/09/2026 (LuckyWP settings importer, WPWing admin menu)
**Cadence:** biweekly, every second Wednesday, anchored on the 09/09/2026 release date. One
holiday freeze (30/12/2026). 13 planned releases across a 6.5-month runway, 09/09/2026 to 24/03/2027.
**Core objective:** free tier only. Convert the two abandoned-competitor install pools, close
the credibility gap against SimpleTOC (locales, unit tests, accessibility), then ship - free -
the three features every larger competitor sells behind a Pro license.

**No Pro launch is committed in this cycle.** The Pro decision is taken at R13 (24/03/2027)
against the install and review gate in [MARKETING-PLAN.md](MARKETING-PLAN.md) section 6, not
against a calendar date. Research context for every claim below is in
[COMPETITORS.md](COMPETITORS.md).

Cadence history, for honesty about what this plan is worth: the previous plan promised weekly
Sunday releases and delivered 1.8.0 on a Monday, 1.9.0 on a Tuesday, and 1.10.0 on a Wednesday.
Biweekly Wednesday is the cadence we have actually been hitting. This plan writes down the real
one rather than a better-looking one.

---

## 1. Strategic principles

- **The migration pools are the wedge, and they are shrinking.** LuckyWP (100,000+ installs,
  abandoned since 04/2025) and Rich Table of Contents (20,000+, ~1 year stale) are unmaintained
  and searchable today. Table of Contents Plus was *not* abandoned - it was acquired by the
  AIOSEO team and rewritten in 07/2026 - so that pool is gone and the 1.10.0-era assumption
  behind it is dead. Ship the second importer while the remaining pools still exist.
- **Ship free what the leaders sell.** Scrollspy, sticky/floating TOC, reading time, progress
  bar, and per-device visibility sit behind $29.99-$49/yr at Joli and Easy Table of Contents.
  Free is our only structural advantage as the smallest vendor in the category; spending it is
  the point, not a concession.
- **The zero-JS promise is the product, and every feature below is constrained by it.** CSS
  first (`position: sticky`, `scroll-margin-top`, `<details>`), inline script only when the
  feature is impossible without it, always opt-in, never an external file, never jQuery. A
  feature that cannot meet this bar gets cut, not shipped with an asterisk.
- **Credibility before breadth.** SimpleTOC holds 4.9 across 77 reviews with 18 locales and
  PHPUnit coverage. We ship zero locales and no unit tests, out of a single 1,211-line PHP
  file at `src/wpwing-table-of-contents-block.php`. R1 and R2 fix that before the feature work lands on top of it.
- **Cadence is a ranking input, but QA capacity is the limit.** Every release passes the
  `/verify` skill against the `make dev` stack and a green `npm run test:e2e` before tagging.
  If a feature is not ready, the release ships smaller - it does not ship late, and it does not
  ship unverified.
- **Directory risk is not hypothetical.** TOP Table Of Contents (BoomDevs) was closed by
  wordpress.org on 09/06/2026 over guideline violations including trackers and settings lost
  across updates. No telemetry, dismissible notices only, and a settings-preservation check in
  every release that touches the options schema.

---

## 2. Release overview (13 releases, free tier only)

| # | Date (Wed) | Version | Primary scope and headline |
| :-- | :--- | :--- | :--- |
| R0 | 09/09/2026 | v1.10.0 | **Shipped.** LuckyWP settings importer, WPWing shared admin menu |
| R1 | 23/09/2026 | v1.11.0 | i18n foundation: `languages/`, `.pot`, translate.wordpress.org, readme/keyword rewrite |
| R2 | 07/10/2026 | v1.12.0 | Code split into `includes/` + PHPUnit for the parser, and the Playground live preview blueprint |
| R3 | 21/10/2026 | v1.13.0 | **Scrollspy / active heading highlight** (free; Pro-gated everywhere else) |
| R4 | 04/11/2026 | v1.14.0 | **Sticky / floating TOC**, CSS-only (free; Pro-gated everywhere else) |
| R5 | 18/11/2026 | v1.15.0 | Reading time + numbering styles (Roman, alpha, padded) + preset expansion |
| R6 | 02/12/2026 | v1.15.1 | **Patch window** - no feature scope, compatibility sweep, support backlog |
| R7 | 16/12/2026 | v1.16.0 | Rich TOC importer on a generalized import layer |
| - | 30/12/2026 | - | **Holiday freeze - no release** |
| R8 | 13/01/2027 | v1.17.0 | Advanced auto-insert rules: taxonomy targeting + per-post override |
| R9 | 27/01/2027 | v1.18.0 | WCAG 2.2 AA: contrast-safe presets, axe-core in CI, honest public claim |
| R10 | 10/02/2027 | v1.19.0 | Platform readiness: WP 7.2, PHP 8.5, PHP floor decision, FSE/query loop, `.pot` refresh |
| R11 | 24/02/2027 | v1.20.0 | Reading progress bar + per-device visibility (free; Pro-gated everywhere else) |
| R12 | 10/03/2027 | v1.21.0 | Performance: heading-parse caching + a published, reproducible benchmark |
| R13 | 24/03/2027 | v1.22.0 | Q1 close: polish, Q2 2027 roadmap, **recorded Pro launch decision** |

**Slack warning:** R6 is the only pure buffer slot in the entire runway. If two features slip,
the Q1 2027 dates move. Do not absorb slippage by skipping the `/verify` pass or the e2e suite.

---

## 3. Q4 2026 - credibility and capture

### R1 - 23/09/2026 - v1.11.0: Internationalization foundation

Zero locales today, and no `languages/` directory or `.pot` file exists at all, despite the
plugin header already declaring `Text Domain` and `Domain Path: /languages`. SimpleTOC ships 18
locales. Translation completeness feeds directory ranking in every non-English locale, which is
search volume we currently do not appear in at all.

- Create `src/languages/`, generate `wpwing-table-of-contents-block.pot`, and commit it.
- Audit every user-facing string in `src/wpwing-table-of-contents-block.php` and `src/assets/src/edit.js` for
  the correct text domain, and add translator comments to every `sprintf` - the heading-count
  notice around line 569 already has one; the rest need the same treatment.
- Submit the project to translate.wordpress.org and seed de_DE, es_ES, fr_FR, pt_BR, nl_NL.
- Ship the readme keyword rewrite from MARKETING-PLAN.md section 1a in the same release: short
  description, tag review ("Navigation" is broad and low-intent), FAQ pass.
- Deliberately first: strings churn with every feature release after this one, and a `.pot`
  generated before the churn is cheaper to maintain than one generated after it.

### R2 - 07/10/2026 - v1.12.0: Structure, tests, and the live preview

Everything in this plan after R2 lands on top of a single 1,211-line procedural PHP file with
no unit tests. This release is the last cheap moment to fix that.

- Split `src/wpwing-table-of-contents-block.php` into `src/includes/`: block registration and render,
  settings and defaults, auto-insert content filter, the importer, admin screen. Keep the
  function names and hook names identical - this is a file move, not a rewrite, and nothing
  user-visible may change.
- Add PHPUnit covering the pure logic that currently has no test at all: anchor slug generation
  and de-duplication, HTML-entity and non-Latin heading handling, keyword exclusion, the
  qualifying-heading count that feeds `min_headings`, and `wpwing_toc_map_luckywp_settings()`.
  These are exactly the functions that produced the 1.4.0 and 1.5.0 bug fixes.
- Ship the WordPress Playground blueprint to SVN (`assets/blueprints/blueprint.json`) so the
  listing gets a "Live Preview" button - none of the nine tracked competitors has one. The
  `make dev` stack already seeds realistic sample content; the blueprint is a translation of it.
- Definition of done: the full e2e suite passes unchanged against the split codebase. If a
  test needed editing to pass, the split changed behavior and is not done.

### R3 - 21/10/2026 - v1.13.0: Scrollspy / active heading highlight

The most-requested TOC behavior that we do not have, and Pro-gated at Joli and Easy Table of
Contents. Free here.

- Opt-in block attribute plus a global default. Off means the output is byte-identical to today.
- `IntersectionObserver` on the heading anchors, appending an `is-active` class to the matching
  TOC link. A few inline lines printed by the same named-function pattern as
  `wpwing_toc_print_frontend_script()`, so it deduplicates across multiple TOCs on one page.
- Honor `prefers-reduced-motion`. Style the active state with a CSS custom property so themes
  and the existing color supports can override it.
- Correctness cases the e2e suite must cover: works with auto-inserted TOCs, with collapsed
  TOCs, on paginated posts, and with keyword-excluded headings that have no TOC link.

### R4 - 04/11/2026 - v1.14.0: Sticky / floating TOC

Pro-gated at Joli (Solo $29.99) and Easy Table of Contents (Pro $49/yr). Free here, and
achievable with **no JavaScript at all**.

- `position: sticky` with a configurable top offset, reusing the `scroll_offset` attribute
  shipped in 1.6.0 rather than introducing a second offset concept.
- Max-height plus internal scroll so a long TOC cannot exceed the viewport, and a mobile
  breakpoint at which sticky disables - a sticky TOC on a phone is a bug, not a feature.
- Works for both manually placed and auto-inserted TOCs, and inside a Group block wrapper,
  which is the documented styling path in the readme.
- Explicitly not a floating overlay panel or slide-out - those need JS and a focus trap. If
  demand appears, they belong in the Pro discussion at R13, not here.

### R5 - 18/11/2026 - v1.15.0: Reading time and numbering styles

Table of Contents Plus shipped numbering styles and title alignment in its 07/2026 rewrite;
reading time is Pro at Easy Table of Contents. Both are small, both are visible in screenshots,
and bundling them is correct because they touch the same rendering path and the same settings
section.

- Numbering styles extending the existing `hierarchical_numbers` attribute: decimal (current),
  Roman, upper/lower alpha, zero-padded. Implement with CSS counters where possible so the
  markup does not grow.
- Estimated reading time beside the TOC title, next to the existing heading-count badge.
  Configurable words-per-minute; count words from post content, not from the TOC.
- Note the existing 1.3.0 interaction: the editor already warns when the count badge is enabled
  with the title hidden. Reading time needs the same guard, in the same notice.
- Style preset expansion on the existing `style_preset` attribute, including a dark-mode-aware
  preset via `prefers-color-scheme`. Every preset must satisfy the R9 contrast bar - build them
  correct now rather than fixing them in January.

### R6 - 02/12/2026 - v1.15.1: Patch and compatibility window

No feature scope, and no release at all if nothing needs fixing. Reserved deliberately: R3, R4,
and R5 are the three largest frontend behavior changes since auto-insert, and they ship in six
consecutive weeks.

- Compatibility sweep across the default themes and at least two popular block themes, plus a
  classic theme, with sticky and scrollspy both enabled.
- Bump "Tested up to" if a WordPress release has landed.
- Clear the support backlog and convert every recurring question into a readme FAQ entry and a
  `src/docs/index.html` section, per MARKETING-PLAN.md section 5.

### R7 - 16/12/2026 - v1.16.0: Rich TOC importer

The second migration pool, on the mapping layer that 1.10.0 already proved.

- Generalize the LuckyWP import into a small source-agnostic layer: detect saved settings,
  map to our attributes and defaults, render the "Import settings" section on WPWing > TOC
  Block, report what was and was not imported.
- Add Rich Table of Contents (`rich-table-of-content`, 20,000+ installs, ~1 year stale, tested
  only to 6.8.8) as the second source.
- Carry forward the 1.10.0 discipline: map only what maps cleanly, and drop anything ambiguous
  rather than shipping a guess. LuckyWP's relative heading "depth" was dropped for exactly this
  reason and the decision was correct.
- Tell the user plainly what did not import. A silent partial import is the failure mode that
  generates one-star reviews.
- Ship the migration landing content and FAQ entries from MARKETING-PLAN.md section 2 alongside.

**30/12/2026 - holiday freeze. No release.** Support coverage only.

---

## 4. Q1 2027 - differentiation and the Pro decision

Q1 is where this plan either earns the Pro conversation or settles the question. Cadence stays
biweekly - the previous plan's habit of tapering to monthly in Q1 costs freshness at precisely
the moment the migration content starts ranking.

### R8 - 13/01/2027 - v1.17.0: Advanced auto-insert rules

Auto-insert shipped in 1.7.0 as all-or-nothing per post type. Joli gates per-post-type rules
behind Pro; Easy Table of Contents has richer free rules than we do. This closes the last
functional gap in the feature we already own.

- Target by taxonomy: include or exclude specific categories and tags.
- Per-post override in a small editor sidebar panel: force on, force off, or follow the global
  rule. Manual block placement continues to override everything, unchanged from 1.7.0.
- Exclude the front page, archives, and (optionally) short posts, on top of the existing
  `auto_insert_min_headings` threshold.
- Schema care: `wpwing_toc_sanitize_settings()` grows nested rules here. Version the options
  array and write the upgrade routine in this release, because the R10 platform work will need
  it and because losing user settings on upgrade is what got a competitor removed from the
  directory.

### R9 - 27/01/2027 - v1.18.0: WCAG 2.2 AA, claimed honestly

The 1.9.0 audit found no structural or keyboard gaps, and the readme claim was deliberately
scoped to default styling because author-chosen colors and the de-emphasized count and number
text were never contrast-checked. SimpleTOC claims WCAG 2.2 AA outright. This release earns the
same claim instead of narrowing ours further. The 1.9.0 audit document has been retired - its
findings are carried in this section and in the readme's accessible-by-design FAQ entry, which
is where users actually read them.

- Contrast-audit every `style_preset` including the R5 additions, in light and dark, and fix
  the de-emphasized count badge and number prefixes to meet 4.5:1.
- Add an editor-side contrast warning when an author's chosen background and text colors fail -
  a warning, never a block on saving.
- Add axe-core assertions to the Playwright suite covering both collapse modes, scrollspy
  active states, and the sticky TOC, so accessibility becomes a gate rather than a periodic audit.
- Verify the 2.4.11 focus-not-obscured criterion specifically against the R4 sticky TOC - a
  sticky element that covers a focused heading is the exact failure this criterion describes,
  and it is the most likely regression from R4.
- Only then update the readme claim, and state the scope in the same sentence.

### R10 - 10/02/2027 - v1.19.0: Platform readiness

- WordPress 7.2 and PHP 8.5 verification; bump "Tested up to" and add a PHP 8.5 job to CI.
- Decide the `Requires PHP` floor. It is 7.1 today, which is far below what the block API and
  the R2 test tooling actually need, and it costs us modern syntax for a vanishing share of
  installs. Decide with data from the wordpress.org PHP version stats, and if it moves, ship
  the bump with a clear upgrade notice.
- Full site editing: verify rendering inside template parts and Query Loop contexts. SimpleTOC
  reviews cite FSE compatibility explicitly, and our auto-insert runs on `the_content` at
  priority 0, which behaves differently inside a Query Loop. Test it, document the result.
- `@wordpress/scripts` and block API currency check; `.pot` regeneration after two quarters of
  string churn; regenerate `src/docs/index.html`.

### R11 - 24/02/2027 - v1.20.0: Progress bar and per-device visibility

The last two Joli Pro features worth having, both free.

- Reading progress bar, opt-in, rendered as part of the sticky TOC rather than as a separate
  page-wide element. Needs a throttled scroll listener - the one place in this plan where JS is
  unavoidable - so it stays strictly opt-in, inline, and off by default.
- Per-device visibility: hide the TOC below or above configurable breakpoints, CSS-only, no
  JS and no user-agent sniffing.
- Both interact with R4. If the sticky work slipped or shipped compromised, cut the progress
  bar rather than building it on an unstable base.

### R12 - 10/03/2027 - v1.21.0: Performance, proven

Our entire positioning is a performance claim. We have never published a measurement of it.

- Cache the heading parse for long posts. `wpwing_toc_add_ids_to_content()` and the render
  callback both walk the content on every request; a post-meta or transient cache keyed on
  content hash removes the duplicate work. Invalidate on save, and verify against paginated
  posts and reusable blocks.
- Publish a reproducible benchmark: bytes added, requests added, and Core Web Vitals deltas for
  this plugin against Easy Table of Contents, Table of Contents Plus, and SimpleTOC on
  identical content. Commit the harness so anyone can re-run it.
- The benchmark is a release deliverable *and* the anchor asset for MARKETING-PLAN.md section 3.
  Be scrupulously fair - a benchmark that shades the numbers is worth less than no benchmark.

### R13 - 24/03/2027 - v1.22.0: Q1 close and the Pro decision

- Polish, bug backlog, translation sync, screenshot refresh via `make screenshots`.
- Measure against the MARKETING-PLAN.md milestone table: installs bracket, review count, review
  average, and directory position for the three target keywords.
- **Record the Pro decision in this document, either way.** The gate is 1,000+ active installs
  and 25+ reviews. Below it, no Pro launch is scheduled and one currently planned Pro-tier
  feature ships free in Q2 instead. Write down the actual numbers, the decision, and the date.
- Publish the Q2 2027 roadmap and refresh COMPETITORS.md with a full quarterly snapshot.

---

## 5. Per-release definition of done

No release is tagged until all of these pass:

1. `npm run lint:js` and `npm run lint:css` clean.
2. `npm run test:e2e` green against the `make dev` stack (Node 20+ required).
3. PHPUnit green, from R2 onward.
4. `/verify` skill run against paginated content, nested blocks, a custom post type, and an RTL
   locale.
5. Settings preserved across upgrade from the previous version - explicitly checked whenever
   the options schema changes.
6. Version bumped in **all four** places: the plugin header `Version:`, `readme.txt` Stable tag,
   `package.json`, and `src/assets/src/block.json`. `make check` verifies all four agree.
7. Changelog entry in `src/readme.txt` including at least one fix or improvement, not only new
   features. A release that is all "New:" lines means the bug backlog is not being worked.
8. `src/docs/index.html` updated when user-facing behavior changed.

---

## 6. Backlog (explicitly not scheduled)

- Pro tier scaffolding, licensing, and update server - blocked on the R13 decision.
- Multi-column and timeline TOC layouts (Joli Pro) - styling-only differentiation, low value
  against our positioning.
- Slide-out and overlay floating panels - need JS and a focus trap; revisit only with the Pro
  decision.
- Shortcode wrapper for Classic editor, Elementor, and Divi - only if user requests actually
  appear. It conflicts with block-first positioning, and it is how Easy Table of Contents ended
  up spending most of its changelog on page-builder conflict fixes.
- Additional locales beyond the R1 seed set, once strings stabilize after R10.
- TOP Table Of Contents importer - the plugin was closed on 09/06/2026 and its settings schema
  is undocumented; only worth building if the affected users actually show up in support.

---

## 7. Risks and notes

- **R2 is the highest-risk release in the plan** and delivers nothing a user can see. Splitting
  a 1,211-line file that owns block registration, a `the_content` filter at priority 0, the
  settings API, and the importer will break something. It is scheduled early on purpose, before
  three feature releases land on top of it, and it ships behind an unchanged e2e suite.
- **R3, R4, and R11 each add frontend behavior to a plugin whose entire pitch is adding
  nothing.** Every one is off by default, and default output must stay byte-identical. If a
  reviewer can find a case where installing this plugin and touching no settings adds a single
  byte to the page, the positioning is gone and no feature is worth it.
- **The migration pools have a clock on them.** LuckyWP has been abandoned since 04/2025 and its
  users are actively being courted by Easy Table of Contents. R7 in mid-December is later than
  ideal; if R3-R5 slip, pull R7 forward ahead of R5 rather than letting the importer drift into
  Q1.
- **R6 is the only buffer.** Two slips move the Q1 dates. Say so out loud when it happens rather
  than compressing QA.
- **Do not taper to monthly in Q1.** Freshness is a directory ranking input we control for free,
  and Q1 is when the migration content should start ranking. Biweekly holds through R13.
