# Update Plan - Biweekly Releases: 2026-09-09 to 2027-12-15 (Q4 2026 - Q4 2027)

**Target plugin:** Table of Contents (TOC) Block - Fast & SEO Friendly (`wpwing-table-of-contents-block`)
**Current baseline:** v1.10.0 shipped 2026-09-09 (LuckyWP settings importer, WPWing shared admin menu)
**Cadence:** Biweekly, every second Wednesday, anchored on the 2026-09-09 release date. Two holiday freezes (2026-12-30 and 2027-12-29). 33 planned releases across a 15.5-month runway, 2026-09-09 to 2027-12-15.
**Date format:** yyyy-mm-dd throughout.
**Core objective:** Scale from 10+ installs to 3,000+ active installs by capturing abandoned competitor pools (LuckyWP, Rich TOC, TOP TOC), matching SimpleTOC on code quality and accessibility, and delivering modern features (sticky TOC, scrollspy, reading time, multi-column, schema, headless REST API) with zero frontend JavaScript by default.

**Commercial / Pro Gate:** The Pro decision is formally evaluated at R13 (2027-03-24) against the install and review gate in [MARKETING-PLAN.md](MARKETING-PLAN.md) section 6 (1,000+ active installs and 25+ reviews). If passed, Q2 2027 introduces the commercial tier while accelerating the free tier; if deferred, all planned features continue shipping free to maximize acquisition.

Research context and competitor intelligence are documented in [COMPETITORS.md](COMPETITORS.md).

---

## 1. Strategic principles

- **Capture the migration pools before they disappear:** LuckyWP (100,000+ installs, abandoned since 2025-04-16) and Rich Table of Contents (20,000+ installs, stale since 2025-09-15) are unmaintained and searchable today. TOP Table Of Contents was closed by wordpress.org on 2026-09-06. These pools represent over 120,000 sites that will break as WordPress 7.x and PHP 8.4/8.5 rollout. Our 1-click importers are our primary customer acquisition wedge.
- **Ship free what competitors gate behind paywalls:** Sticky/floating TOC, scrollspy active heading highlight, reading time, reading progress bar, and multi-column layouts cost $29.99-$89/yr at Joli and Easy Table of Contents. Providing these free undercuts the commercial leaders and drives organic recommendations.
- **The zero-JS promise is our non-negotiable brand moat:** CSS first (`position: sticky`, `scroll-margin-top`, `<details>`, CSS Grid), micro-vanilla script only when the feature is technically impossible without it (scrollspy, progress bar, search/filter), always strictly opt-in, never external library files, never jQuery. Default block output must remain 100% zero-JS and byte-identical across updates.
- **Credibility before breadth:** SimpleTOC holds a 4.9 rating across 77 reviews with 18 locales, WCAG 2.2 AA certification, and PHPUnit coverage. We start by matching those engineering foundations (i18n in R1, modular architecture and PHPUnit in R2, WCAG in R9) before stacking complex frontend capabilities.
- **Cadence feeds the directory algorithm, but QA is the hard limit:** wordpress.org heavily weights update freshness. A biweekly Wednesday release cadence ensures our listing displays "Updated 2 weeks ago" continuously. However, releases never compromise stability: every release must pass linting, PHPUnit, and Playwright e2e suites before tagging. If a feature slips, the release ships with remaining scope rather than slipping the calendar date.
- **Strict compliance and zero telemetry:** Competitors like TOP TOC were de-listed for aggressive tracking and lost settings. We enforce zero telemetry, dismissible notices only, and schema-migration tests on every release.
- **Future-proof for modern WordPress (FSE, Interactivity API, Headless, GEO):** Support Full Site Editing (FSE) block themes, Query Loops, AI/GEO Schema.org structured data, and Headless REST API endpoints to make this the default TOC for both simple bloggers and advanced agencies.

---

## 2. Release overview (33 releases, 5 quarters)

| # | Date (Wed) | Version | Quarter | Primary scope and headline | Focus area |
| :-- | :--- | :--- | :--- | :--- | :--- |
| R0 | 2026-09-09 | v1.10.0 | Q3 2026 | **Shipped.** LuckyWP settings importer, WPWing shared admin menu | Migration wedge |
| R1 | 2026-09-23 | v1.11.0 | Q4 2026 | i18n foundation: `languages/`, `.pot`, translate.wordpress.org, keyword pass | Credibility & SEO |
| R2 | 2026-10-07 | v1.12.0 | Q4 2026 | Code split into `includes/` + PHPUnit for parser + Playground live preview | Architecture & Testing |
| R3 | 2026-10-21 | v1.13.0 | Q4 2026 | **Scrollspy / active heading highlight** (free, opt-in `IntersectionObserver`) | Pro feature parity |
| R4 | 2026-11-04 | v1.14.0 | Q4 2026 | **Sticky / floating TOC** (CSS-only `position: sticky`, mobile guard) | Pro feature parity |
| R5 | 2026-11-18 | v1.15.0 | Q4 2026 | Reading time + numbering styles (Roman, alpha, padded) + dark presets | User experience |
| R6 | 2026-12-02 | v1.15.1 | Q4 2026 | **Patch window** - compatibility sweep across block and classic themes | Stability buffer |
| R7 | 2026-12-16 | v1.16.0 | Q4 2026 | Rich TOC importer on generalized import layer + migration landing pages | Migration wedge |
| - | 2026-12-30 | - | Q4 2026 | **Holiday freeze - no release** (support coverage only) | Team buffer |
| R8 | 2027-01-13 | v1.17.0 | Q1 2027 | Advanced auto-insert rules: taxonomy targeting + per-post override panel | Content control |
| R9 | 2027-01-27 | v1.18.0 | Q1 2027 | WCAG 2.2 AA certification: contrast guardrails in editor, axe-core in CI | Accessibility |
| R10 | 2027-02-10 | v1.19.0 | Q1 2027 | Platform readiness: WP 7.2, PHP 8.5, FSE query loop testing, `.pot` refresh | Platform currency |
| R11 | 2027-02-24 | v1.20.0 | Q1 2027 | Reading progress bar + per-device visibility (CSS-only breakpoints) | Pro feature parity |
| R12 | 2027-03-10 | v1.21.0 | Q1 2027 | Performance proven: heading-parse caching + published reproducible benchmark | Proof & Marketing |
| R13 | 2027-03-24 | v1.22.0 | Q1 2027 | Q1 close: milestone audit, review sweep, **recorded Pro launch decision gate** | Strategic governance |
| R14 | 2027-04-07 | v1.23.0 | Q2 2027 | **Schema.org & GEO/AI Overviews**: `SiteNavigationElement` & `ItemList` JSON-LD | Search & AI optimization |
| R15 | 2027-04-21 | v1.24.0 | Q2 2027 | Multi-column & CSS Grid layouts (2-col, 3-col, responsive column-gap) | Layout flexibility |
| R16 | 2027-05-05 | v1.25.0 | Q2 2027 | **Slide-out / Off-canvas drawer TOC**: floating toggle with accessible modal trap | Advanced UX |
| R17 | 2027-05-19 | v1.26.0 | Q2 2027 | JSON settings export/import & orphaned TOP Table of Contents rescue tool | Migration & Backup |
| R18 | 2027-06-02 | v1.26.1 | Q2 2027 | **Patch window** - high-traffic cache engine audit (Redis, LiteSpeed, WP Rocket) | Performance buffer |
| R19 | 2027-06-16 | v1.27.0 | Q2 2027 | Visual sidebar design tokens: border radius, shadow, custom CSS variable mapper | Visual customization |
| R20 | 2027-06-30 | v1.28.0 | Q2 2027 | Q2 close: mid-year platform sync, WordPress 7.3 beta testing, Pro review | Platform & Commercial |
| R21 | 2027-07-14 | v1.29.0 | Q3 2027 | **In-TOC instant search & live filter** for large documentation posts (micro-JS) | Long-form content |
| R22 | 2027-07-28 | v1.30.0 | Q3 2027 | **REST API & Headless WP endpoints**: expose heading trees to Next.js/Astro | Headless & Agencies |
| R23 | 2027-08-11 | v1.31.0 | Q3 2027 | Custom fields (ACF / Meta Box) heading parsing & custom template hooks | Developer tools |
| R24 | 2027-08-25 | v1.31.1 | Q3 2027 | **Patch window** - international character encoding audit (CJK, Arabic, Cyrillic) | Internationalization |
| R25 | 2027-09-08 | v1.32.0 | Q3 2027 | Timeline & stepped progression TOC layout (visual connected milestones) | Layout innovation |
| R26 | 2027-09-22 | v1.33.0 | Q3 2027 | Q3 close: 1-year baseline retrospective (growth velocity, tech debt review) | Milestone audit |
| R27 | 2027-10-06 | v1.34.0 | Q4 2027 | **Privacy-first engagement analytics**: local/GA4/Plausible TOC jump event tracking | Content analytics |
| R28 | 2027-10-20 | v1.35.0 | Q4 2027 | WordPress Multisite Network management & sitewide TOC blueprint sync | Enterprise & Networks |
| R29 | 2027-11-03 | v1.36.0 | Q4 2027 | Dynamic heading sync in block editor via WordPress Block Bindings / core APIs | Editor UX |
| R30 | 2027-11-17 | v1.37.0 | Q4 2027 | Annual accessibility re-audit (WCAG 2.2 AAA / EN 301 549) & 2027 benchmark | Quality assurance |
| R31 | 2027-12-01 | v1.37.1 | Q4 2027 | **Patch window** - PHP 8.6 & WordPress 7.4 RC regression suite sweep | Pre-freeze stability |
| R32 | 2027-12-15 | v1.38.0 | Q4 2027 | Q4 close: year-end wrap, translation sync across 25+ locales, 2028 roadmap | Annual close |
| - | 2027-12-29 | - | Q4 2027 | **Holiday freeze - no release** (support coverage only) | Team buffer |

---

## 3. Q4 2026 - Credibility and capture

Focus: Lay solid technical foundations, achieve internationalization parity with SimpleTOC, refactor monolithic PHP into testable modules, and capture the primary migration pools while shipping the first wave of free Pro-equivalent features.

### R1 - 2026-09-23 - v1.11.0: Internationalization foundation
Zero locales exist today, despite header declarations. SimpleTOC ships 18 locales and dominates non-English directory searches.
- Create `src/languages/`, generate `wpwing-table-of-contents-block.pot`, and commit.
- Audit all strings in `src/wpwing-table-of-contents-block.php` and `src/assets/src/edit.js` for the text domain; add translator notes to all `sprintf` calls.
- Submit to translate.wordpress.org and seed initial translations: de_DE, es_ES, fr_FR, pt_BR, nl_NL.
- Execute the readme keyword rewrite from MARKETING-PLAN.md: refine short description and replace low-intent tags with high-intent keywords.
- Scope discipline: Done first so downstream features add to an established translation workflow.

### R2 - 2026-10-07 - v1.12.0: Modular architecture, tests, and Playground live preview
The monolithic 1,211-line procedural file must be structured before feature expansion.
- Split `src/wpwing-table-of-contents-block.php` into `src/includes/`:
  - `block-registration.php`
  - `render.php`
  - `settings.php`
  - `auto-insert.php`
  - `importers/class-importer-luckywp.php`
  - `admin-menu.php`
- Keep all function and hook names identical for backward compatibility.
- Add PHPUnit suite covering core pure logic: anchor slug generation, collision de-duplication, entity handling, keyword exclusions, minimum heading calculations, and LuckyWP settings mapping.
- Commit a WordPress Playground blueprint (`assets/blueprints/blueprint.json`) enabling a "Live Preview" button on wordpress.org.
- Definition of done: Playwright e2e suite passes 100% against the split structure with zero test changes.

### R3 - 2026-10-21 - v1.13.0: Scrollspy / active heading highlight
The number-one requested user feature, commercialized by Joli and Easy TOC. Delivered completely free.
- Opt-in block attribute plus global setting default. Output remains zero-JS when disabled.
- Pure vanilla `IntersectionObserver` observing heading anchors, applying an `is-active` class to the corresponding TOC list link.
- Inline script printed via deduplicated named helper; zero external JavaScript files.
- Respect `prefers-reduced-motion` and expose CSS custom properties (`--wpwing-toc-active-color`) for theme integration.
- e2e verification: handles nested headings, auto-inserted TOCs, and posts with excluded headings.

### R4 - 2026-11-04 - v1.14.0: Sticky / floating TOC
A major Pro differentiator at Easy TOC ($49/yr) and Joli ($49/yr). Implemented with **zero JavaScript**.
- Pure CSS `position: sticky` with configurable top offset (reusing existing `scroll_offset` attribute).
- Max-height calculation with internal overflow scrolling (`overflow-y: auto`) to protect long TOCs from exceeding viewport height.
- Responsive mobile breakpoint disable: sticky navigation automatically unpins on mobile viewports to prevent screen obstruction.
- Works across manual blocks, auto-inserted content, and inside Group/Column wrappers.

### R5 - 2026-11-18 - v1.15.0: Reading time and expanded numbering styles
- Numbering styles extending `hierarchical_numbers`: Roman numerals (I, II, III), alphabet (A, B, C), and zero-padded decimal (01, 02, 03) via pure CSS counters.
- Estimated reading time calculation (configurable words-per-minute, calculated on raw post content) displayed optionally in the TOC header badge.
- Editor guardrails: warning notice if reading time or heading count badges are active with the header title hidden.
- Style presets expansion: introduce clean minimalist, boxed border, modern card, and dark-mode-ready presets with verified color contrast.

### R6 - 2026-12-02 - v1.15.1: Patch and compatibility buffer
Deliberate stabilization window following three major frontend releases (R3, R4, R5).
- Compatibility sweep against WordPress default themes (Twenty Twenty-Four, Twenty Twenty-Five) and major block/classic themes.
- Clear support backlog and update readme FAQ and documentation based on incoming tickets.
- Bump "Tested up to" if a minor core WordPress release has landed.

### R7 - 2026-12-16 - v1.16.0: Rich TOC importer and generalized import architecture
Capture the second abandoned competitor pool (Rich Table of Contents, 20,000+ installs).
- Abstract the LuckyWP importer into a unified source-agnostic migration framework.
- Add Rich Table of Contents mapping layer: import layout settings, auto-insert configuration, heading depth rules, and title options.
- Transparent import report: inform user explicitly of settings successfully transferred versus deprecated features dropped.
- Publish companion migration landing content and FAQ entries in readme.txt.

**2026-12-30 - Holiday freeze. No release.** Support triage and monitoring only.

---

## 4. Q1 2027 - Differentiation and the Pro decision

Focus: Close advanced auto-insert gaps, establish verified accessibility leadership (WCAG 2.2 AA), ensure platform currency with WP 7.2/PHP 8.5, publish objective performance benchmarks, and evaluate the milestone criteria at R13 for the Pro launch.

### R8 - 2027-01-13 - v1.17.0: Advanced auto-insert rules
Matches Easy TOC's auto-insert capabilities while remaining significantly faster.
- Granular taxonomy targeting: include or exclude specific categories, tags, or custom taxonomy terms.
- Editor sidebar panel for per-post override: Force TOC Enable, Force TOC Disable, or Default to Sitewide Rules.
- Conditional exclusion rules: exclude front page, blog index, archive pages, and posts shorter than configurable word counts.
- Versioned options schema with safe upgrade routines to prevent settings corruption.

### R9 - 2027-01-27 - v1.18.0: WCAG 2.2 AA certification
Matches SimpleTOC's accessibility claims with verifiable test evidence.
- Audit all style presets against WCAG 2.2 AA 4.5:1 minimum contrast ratios across light and dark modes.
- Implement an editor-side contrast warning if an author selects custom text/background colors with insufficient contrast.
- Integrate `axe-core` accessibility checks into Playwright e2e CI suite.
- Verify WCAG 2.4.11 (Focus Not Obscured) compliance specifically against sticky TOC states.

### R10 - 2027-02-10 - v1.19.0: Platform readiness (WP 7.2 & PHP 8.5)
- Validate compatibility against WordPress 7.2 and PHP 8.5 runtime; update CI matrix.
- Evaluate raising the `Requires PHP` floor from 7.1 to 7.4 or 8.0 based on official wordpress.org version telemetry.
- Full Site Editing (FSE) validation: verify rendering inside block templates, template parts, and Query Loop blocks.
- Regenerate translation templates (`.pot`) and update local docs.

### R11 - 2027-02-24 - v1.20.0: Reading progress bar and per-device visibility
- Reading progress indicator: subtle scroll-progress bar integrated directly into the top of the sticky TOC.
- Implemented with lightweight, throttled scroll listener; strictly opt-in and off by default.
- Per-device responsive visibility controls: options to hide TOC specifically on mobile, tablet, or desktop using pure CSS media queries.

### R12 - 2027-03-10 - v1.21.0: Performance proven and published benchmarks
Defend our performance positioning with transparent, reproducible data.
- Implement heading-parse caching: store parsed heading structures in post transients/meta keyed to content hashes to eliminate redundant DOM parsing on high-traffic sites.
- Publish a reproducible benchmark suite: document DOM node count, CSS/JS payload, execution time, and Core Web Vitals (LCP, INP, CLS) comparing WPWing against Easy TOC, TOC+, and SimpleTOC.
- Commit the benchmark harness to the public repository.

### R13 - 2027-03-24 - v1.22.0: Q1 close and the formal Pro decision gate
- Audit quarterly growth against MARKETING-PLAN.md targets: active install bracket, review count, average rating, keyword rankings.
- **Formal recorded Pro decision:**
  - *Gate Criteria:* 1,000+ active installs and 25+ reviews (minimum 4.8 rating).
  - *If Met:* Greenlight the Pro tier architecture for Q2 2027 (licensing server, premium add-on structure, advanced agency features).
  - *If Missed:* Record decision in this file, defer commercial launch, and release planned premium features free into the core plugin to accelerate acquisition.
- Refresh COMPETITORS.md quarterly review and publish the finalized Q2 2027 roadmap.

---

## 5. Q2 2027 - Modern UX, Schema/GEO, and commercial expansion

Focus: Capitalize on the R13 decision. Deliver modern Search Engine & Generative Engine Optimization (GEO) schema, multi-column layouts, off-canvas slide-out drawers, and visual design token customization.

### R14 - 2027-04-07 - v1.23.0: Schema.org structured data & GEO/AI search optimization
Search engines and AI Overviews (Google SGE, Perplexity, ChatGPT Search) rely on clean semantic structures to parse content sections.
- Opt-in JSON-LD structured data output: generate valid Schema.org `SiteNavigationElement` and `ItemList` nodes referencing heading anchors.
- Clean semantic integration: outputted inside non-rendering script tags, adding zero visual weight.
- Filter hook `wpwing_toc_schema_data` allowing SEO plugins (Rank Math, Yoast, AIOSEO) or custom themes to extend or modify the schema graph.
- Automated validation via Google Rich Results testing suite in CI.

### R15 - 2027-04-21 - v1.24.0: Multi-column & CSS Grid layouts
Long reference articles, glossaries, and documentation guides frequently have 20+ headings that create excessive vertical scrolling.
- CSS Grid layout attribute: configure 2-column or 3-column TOC layouts for desktop viewports.
- Pure CSS implementation using `display: grid`, `column-gap`, and responsive `minmax()` definitions; zero JavaScript.
- Automatic fallback to single-column on mobile viewports to prevent cramped text.
- Works seamlessly with both hierarchical (nested) and flat heading lists.

### R16 - 2027-05-05 - v1.25.0: Slide-out / Off-canvas drawer TOC
Pro-gated at Joli ($49-$89/yr). High-converting UX pattern for mobile users and clean editorial blogs.
- Floating toggle button positioned in viewport corner (bottom-right, bottom-left, top-right).
- Smooth off-canvas drawer sliding into view upon click.
- Accessible modal behavior: manages ARIA attributes (`aria-expanded`, `aria-controls`), implements keyboard focus trap when open, and dismisses on `Escape` key or backdrop click.
- Lightweight micro-vanilla script; strictly opt-in, zero external dependencies.

### R17 - 2027-05-19 - v1.26.0: Settings backup, export/import & TOP TOC rescue
- Full settings export and import tool: export sitewide TOC configurations, auto-insert rules, and custom presets as portable JSON files.
- Orphaned TOP Table of Contents rescue tool: heuristic import detecting orphaned option keys from the removed BoomDevs plugin, migrating users with zero manual reconfiguration.
- Integrity check: schema validation on uploaded JSON files to prevent corrupted configurations.

### R18 - 2027-06-02 - v1.26.1: Mid-Q2 patch window & high-traffic cache profiling
- Buffer release. Audit compatibility with popular WordPress caching layers: Redis object cache, Memcached, LiteSpeed Cache, WP Rocket, and Cloudflare Super Page Cache.
- Verify heading anchor persistence and transient cache invalidation during automated post updates and bulk edits.
- Triage support backlog and apply community feedback.

### R19 - 2027-06-16 - v1.27.0: Visual sidebar styler & design tokens
Empower non-technical site owners to customize TOC appearance without writing custom CSS.
- Block inspector design token controls: configurable border radius, border width/style, box shadow presets, and container padding.
- Modern CSS variables mapping: values render as inline CSS custom properties (`--wpwing-toc-radius`, `--wpwing-toc-shadow`, `--wpwing-toc-padding`) inheriting theme typography.
- Real-time live preview in Gutenberg editor canvas without server reload delays.

### R20 - 2027-06-30 - v1.28.0: Q2 close, mid-year platform sync & Pro metrics audit
- WordPress 7.3 beta compatibility check; bump "Tested up to" and verify block registration schema.
- Comprehensive review of Q2 performance: install count trajectory, review sentiment, and initial conversion metrics if Pro tier was launched at R13.
- `.pot` translation catalog refresh and docs update.

---

## 6. Q3 2027 - Developer APIs, Headless, and publisher tools

Focus: Expand adoption beyond individual bloggers to agencies, technical documentation hubs, and headless WordPress architectures. Deliver search/filter inside TOCs, REST API endpoints, and ACF custom field integrations.

### R21 - 2027-07-14 - v1.29.0: In-TOC instant search & live filter
Essential capability for 5,000+ word guides, technical whitepapers, and knowledge bases.
- Opt-in live search input field positioned above the TOC list.
- Instant, client-side real-time filtering: hides non-matching headings and keeps matching parent headings visible for context.
- Accessible live region notifications (`aria-live="polite"`) announcing matching result counts to screen readers.
- Pure vanilla micro-script (under 1KB), zero external libraries.

### R22 - 2027-07-28 - v1.30.0: REST API & Headless WordPress endpoints
Enable headless and decoupled frontend frameworks (Next.js, Nuxt, Astro, SvelteKit) to render WPWing TOC natively.
- Register custom REST API fields on standard post endpoints: `wp/v2/posts/{id}` exposes parsed `toc_headings` array with hierarchy, IDs, titles, and reading time.
- Dedicated lightweight endpoint: `GET /wp-json/wpwing-toc/v1/headings/{post_id}` returning clean structured JSON without loading the full post content.
- Comprehensive documentation and code examples for headless frontend developers.

### R23 - 2027-08-11 - v1.31.0: Custom fields (ACF / Meta Box) & custom template hooks
Overcomes the limitation of only parsing core post content.
- Support parsing headings located in Advanced Custom Fields (ACF) WYSIWYG fields and Meta Box wysiwyg fields.
- Official shortcode `[wpwing_toc]` with attribute overrides for custom page builders and classic widget areas.
- PHP template tag `wpwing_toc()` and action hook `do_action('wpwing_toc')` for custom theme developers building bespoke single-post templates.

### R24 - 2027-08-25 - v1.31.1: Mid-Q3 patch window & international character audit
- Buffer release. Comprehensive audit of anchor slug generation across non-Latin alphabets: Cyrillic, Arabic, Hebrew, Greek, Japanese (Kanji/Kana), and Chinese (Simplified/Traditional).
- Ensure safe URL encoding without stripping valid unicode characters or creating duplicate anchor slugs.
- Support ticket resolution and documentation improvements.

### R25 - 2027-09-08 - v1.32.0: Timeline & stepped progression TOC layout
Popularized by Joli Pro ($89/yr), re-engineered for pure performance and semantic accessibility.
- Visual timeline presentation: vertical connective line linking heading nodes with step numbers or circular bullet markers.
- Ideal for tutorials, chronological guides, case studies, and recipe steps.
- Implemented with clean CSS pseudo-elements (`::before`, `::after`) and CSS counters; zero JS overhead.
- Fully accessible semantic ordered list (`<ol>`) under the hood.

### R26 - 2027-09-22 - v1.33.0: Q3 close & 1-year baseline retrospective
Marks exactly one full year since the v1.10.0 baseline (2026-09-09).
- Comprehensive retrospective: evaluate growth against MARKETING-PLAN.md targets (aiming for 2,000+ active installs and 50+ reviews).
- Codebase health assessment: technical debt paydown, deprecated code removal, and test suite execution time optimization.
- Refresh COMPETITORS.md and publish Q4 2027 execution priorities.

---

## 7. Q4 2027 - Enterprise scalability, analytics, and market leadership

Focus: Scale into enterprise and multisite environments, deliver privacy-first content analytics, implement modern WordPress Block Bindings for real-time editor sync, and solidify market leadership.

### R27 - 2027-10-06 - v1.34.0: Privacy-first TOC engagement analytics
Help content creators and SEO managers understand which sections of their articles readers actually care about.
- Opt-in click event tracking on TOC links.
- 100% GDPR and privacy compliant: zero external cookies, zero third-party calls, no personally identifiable information collected.
- Flexible dispatch: trigger custom browser events dispatchable to Google Tag Manager, Google Analytics 4, or Plausible Analytics, plus optional local post-meta click counter for top headings.

### R28 - 2027-10-20 - v1.35.0: WordPress Multisite Network management
Target agency networks, universities, and enterprise publisher networks running WP Multisite.
- Network Admin settings dashboard: define sitewide default TOC behaviors, auto-insert rules, and styling presets across all sub-sites.
- Policy enforcement options: network administrators can lock global settings or allow individual site administrators to override styles.
- Single-click network-wide blueprint propagation.

### R29 - 2027-11-03 - v1.36.0: Dynamic heading sync via Block Bindings in Block Editor
Elevate the Gutenberg authoring experience to gold standard.
- Replace ServerSideRender editor preview with real-time client-side block synchronization using WordPress Block Bindings and core block APIs.
- When an author types, edits, or rearranges Heading blocks in Gutenberg, the TOC block updates instantly in the visual canvas without waiting for REST roundtrips.
- Seamless, lag-free editing experience even on 50-heading long-form articles.

### R30 - 2027-11-17 - v1.37.0: Annual accessibility (WCAG 2.2 AAA / EN 301 549) & performance benchmark
- Annual comprehensive accessibility audit: manual testing with screen readers (NVDA, JAWS, VoiceOver), keyboard-only navigation verification, and full automated axe-core suite.
- Re-run and publish the 2027 Edition Core Web Vitals Benchmark against Easy TOC, TOC+, SimpleTOC, and Joli TOC.
- Document and publicize verified zero-regression performance results.

### R31 - 2027-12-01 - v1.37.1: Pre-holiday stability sweep & PHP 8.6 prep
- Final buffer release of 2027. Full compatibility testing against PHP 8.6 release candidates and WordPress 7.4.
- End-of-year support ticket burn-down and documentation cleanup.

### R32 - 2027-12-15 - v1.38.0: Q4 close, year-end wrap & 2028 strategic roadmap
- Annual retrospective: evaluate total install growth, retention rate, review distribution, and commercial performance.
- Translation synchronization across 25+ locales on translate.wordpress.org.
- Publish the comprehensive 2028 Strategic Roadmap.

**2027-12-29 - Holiday freeze. No release.** Support triage and monitoring only.

---

## 8. Per-release definition of done

Every release without exception must satisfy this 8-point checklist before tagging in SVN/Git:

1. **Linting clean:** `npm run lint:js` and `npm run lint:css` exit with code 0 and zero warnings.
2. **End-to-end tests green:** `npm run test:e2e` passes completely against the local `make dev` environment across Chromium, Firefox, and WebKit.
3. **Unit tests green:** PHPUnit test suite passes 100% of assertions (starting from R2).
4. **Automated verification pass:** `/verify` skill executed against paginated content, nested blocks, custom post types, and RTL layouts.
5. **Settings preservation verified:** Explicit manual/automated test verifying settings persist cleanly without corruption across upgrades from prior version.
6. **Version synchronized in all 4 locations:** Plugin header `Version:`, `readme.txt` Stable tag, `package.json`, and `src/assets/src/block.json`. `make check` verifies all 4 agree.
7. **Meaningful changelog:** `src/readme.txt` changelog contains at least one bug fix or improvement alongside new features. A changelog of only "New:" items indicates the bug backlog is being neglected.
8. **Documentation currency:** `src/docs/index.html` and readme FAQ updated whenever user-facing behavior is added or modified.

---

## 9. Backlog & parked scope

Items deliberately excluded from the 2026-2027 release runway:

- **Third-party telemetry or phone-home tracking:** Permanently prohibited. Violates our privacy-first brand promise and introduces catastrophic directory de-listing risk.
- **Intrusive upsell banners or admin takeover notices:** Prohibited. Degrades user trust and violates wordpress.org administrative quality expectations.
- **Page builder native modules (Divi, Elementor, WPBakery builders):** Parked. Developing bespoke builder modules diverts engineering resources into eternal conflict patches (which plague Easy TOC). We provide clean shortcodes and blocks; page builders can embed the block or shortcode natively.
- **Unconditional frontend JavaScript:** Prohibited. Any interactive feature must be strictly opt-in. Default markup is 100% zero-JS.

---

## 10. Risk management and mitigation matrix

| Risk | Severity | Likelihood | Mitigation Strategy |
| :--- | :--- | :--- | :--- |
| **Monolith code split regression (R2)** | High | Medium | Scheduled early (R2) before feature work. Full Playwright e2e suite must pass against split files with zero changes to test files. |
| **Frontend script bloat eroding zero-JS brand** | Critical | Medium | Zero-JS default is enforced in CI. If an unconfigured block emits a `<script>` tag or external JS asset, the build pipeline fails. |
| **Migration pools vanish before capture** | Medium | Medium | Importers are prioritized: LuckyWP in R0 (shipped), Rich TOC in R7, TOP TOC in R17. Migration content published in tandem. |
| **Biweekly cadence burnout or feature slippage** | Medium | High | Dedicated buffer releases scheduled every quarter (R6, R18, R24, R31). If a feature is not ready, release ships smaller on schedule; it never ships late or unverified. |
| **WordPress directory compliance issues** | Critical | Low | Zero telemetry, dismissible notices only, schema-migration test on every release touching options. |
| **FSE / Block theme rendering quirks** | Medium | Medium | FSE Query Loop and template part tests integrated into standard e2e test suite starting in R2. |
