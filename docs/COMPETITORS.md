# Competitor Research - Table of Contents Plugins

Last researched: 2026-09-23 (wordpress.org plugin directory). Previous passes: 2026-09-11, 2026-07-11.
Refresh alongside [MARKETING-PLAN.md](MARKETING-PLAN.md) at the start of every quarter, and
re-check any row before it is used to justify release scope in [UPDATE-PLAN.md](UPDATE-PLAN.md).

Date format throughout: yyyy-mm-dd.

---

## 1. Our baseline

**Table of Contents (TOC) Block - Fast & SEO Friendly** (slug: `wpwing-table-of-contents-block`)

- Active installs: 10+
- Rating: 4.0 (1 review)
- Version: v1.10.0, released 2026-09-09
- Tested up to: 7.1 (current)
- Positioning: Gutenberg-native block, zero frontend JS by default, biweekly releases, performance-obsessed

---

## 2. Competitor overview matrix

| Plugin | Slug | Installs | Rating (reviews) | Version | Last updated | Block-based? | Pricing model | Primary moat |
|---|---|---|---|---|---|---|---|---|
| Easy Table of Contents | `easy-table-of-contents` | 600,000+ | 4.4 (219) | 2.0.87.1 | 2026-09-09 | Auto-insert first; Gutenberg block is Pro | Free + Pro ($49-$99/yr, $499 lifetime) | Page builder legacy install base |
| Table of Contents Plus | `table-of-contents-plus` | 200,000+ | 4.4 (178) | 202608.2 | 2026-08-21 | No (shortcode/auto-insert) | Free (AIOSEO ecosystem vehicle) | Syed Balkhi / AIOSEO cross-promotion |
| LuckyWP Table of Contents | `luckywp-table-of-contents` | 100,000+ | 4.9 (885) | 2.1.14 | 2025-04-16 | Block + shortcode + widget | Free only (abandoned) | Historic reputation & clean URL hashes |
| Rich Table of Contents | `rich-table-of-content` | 20,000+ | 4.1 (17) | 1.4.3 | 2025-09-15 | No (auto-insert/shortcode) | Free + Pro | Visual design presets |
| SimpleTOC | `simpletoc` | 10,000+ | 4.9 (77) | 7.3.0 | 2026-08-21 | Yes, pure block | 100% Free / Donation | Purity, zero-JS, 18 locales, PHPUnit |
| Joli Table Of Contents | `joli-table-of-contents` | 7,000+ | 4.9 (43) | 3.0.3 | 2026-08-14 | Block + auto-insert + shortcode | Free + Pro ($49-$89/yr) | Visual styles, onboarding wizard |
| Heroic Table of Contents | `heroic-table-of-contents` | 5,000+ | 4.7 (9) | 1.2.7 | 2026-01-07 | Yes, pure block | Free (HeroThemes lead-gen) | Simple presets, KB product funnel |
| Table Of Content Block (bPlugins) | `table-of-content-block` | 3,000+ | 5.0 (4) | 1.0.10 | 2026-08-17 | Yes, pure block | Free + Pro | Gutenberg styling controls |
| TOP Table Of Contents (BoomDevs) | `top-table-of-contents` | Closed | - | 1.4.1 | Closed 2026-09-06 | Block + auto-insert | Removed by WP.org | Orphaned install pool |

---

## 3. Market shifts and competitor intelligence

Three major dynamics reshape the competitive space heading into 2027:

1. **The AIOSEO takeover of Table of Contents Plus is an existential corporate threat:**
   TOC+ was acquired by Syed Balkhi and rebuilt in 2026-07. It now features an AIOSEO settings UI, date-based versioning (`202608.2`), live preview, and aggressive cross-promotional hooks into the 3M+ install AIOSEO footprint. While still architecturally legacy (shortcode and content-filter driven rather than block-native), its distribution engine is massive. We cannot compete on raw marketing budget; we compete on technical purity, Gutenberg FSE integration, and zero script payload.

2. **The collapse and decay of legacy incumbents creates prime migration pools:**
   - **LuckyWP (100,000+ installs, abandoned since 2025-04-16):** Untested past WordPress 6.7.7. PHP 8.4/8.5 deprecation warnings and block API evolution will progressively break it. Our 1-click importer shipped in v1.10.0 (2026-09-09) is the direct solution.
   - **Rich TOC (20,000+ installs, stale since 2025-09-15):** Tested only up to 6.8.8. Shows the wordpress.org stale plugin warning. R7 (2026-12-16) targets this pool.
   - **TOP TOC (Closed 2026-09-06):** Removed for guideline violations (unauthorized telemetry, breaking updates, settings data loss). A cautionary tale regarding directory compliance and an immediate pool of orphaned users.

3. **SimpleTOC is the benchmark for credibility; Joli is the template for monetization:**
   - SimpleTOC (10,000+ installs, 4.9 rating) owns the developer/minimalist reputation with 18 translations, PHPUnit suites, and full WCAG 2.2 AA compliance. However, its creator dogmatically refuses common user requests (sticky TOC, active scrollspy, reading time, multi-column layouts).
   - Joli TOC (7,000+ installs) proves what users will pay for ($49-$89/yr): floating TOC, slide-out drawer, reading progress bar, timeline view, multi-column.
   - **Our Product Wedge:** Provide SimpleTOC's technical purity and zero-JS baseline by default, while providing Joli's premium UX capabilities (sticky TOC, scrollspy, reading time) free and CSS-first.

---

## 4. Deep competitor teardowns

### Easy Table of Contents (Market Leader)
- **Installs:** 600,000+ | **Rating:** 4.4 (219 reviews) | **Developer:** Magazine3
- **Strengths:** Dominant SEO ranking in wordpress.org directory, supports legacy page builders (Elementor, Divi, WPBakery), automatic insertion.
- **Weaknesses & Tech Debt:** Heavy frontend payload (loads scripts and CSS unconditionally), bloated options table, intrusive admin dashboard upselling Pro addons, Gutenberg block is locked behind Pro ($49/yr). Changelog is dominated by edge-case page builder patches.
- **How we win:** Pitch directly against their bloat. Zero JS, pure block-native, zero admin clutter, 100% Core Web Vitals friendly.

### Table of Contents Plus (Corporate Incumbent)
- **Installs:** 200,000+ | **Rating:** 4.4 (178 reviews) | **Developer:** Syed Balkhi / AIOSEO
- **Strengths:** Backed by Awesome Motive / AIOSEO marketing machine, modern AIOSEO admin styling, live preview.
- **Weaknesses:** Shortcode-first architecture dating back a decade. Lacks native Gutenberg block editing experience. Heavy sales funnel orientation.
- **How we win:** Native Gutenberg block with instant block canvas manipulation, Full Site Editing (FSE) template support, independent community trust.

### LuckyWP Table of Contents (Abandoned Leader)
- **Installs:** 100,000+ | **Rating:** 4.9 (885 reviews) | **Status:** Abandoned since 2025-04-16
- **Strengths:** Historic user love, smooth scroll offset, customizable colors.
- **Weaknesses:** Inactive for over 17 months. Breaking under PHP 8.4+ strict typing and Gutenberg block schema updates.
- **How we win:** Active maintenance, Tested up to WP 7.1+, and our one-click settings importer in v1.10.0.

### SimpleTOC (Direct Architectural Rival)
- **Installs:** 10,000+ | **Rating:** 4.9 (77 reviews) | **Developer:** Marc Heatley
- **Strengths:** Pure Gutenberg block, zero CSS/JS by default, native HTML5 details/summary tags, WCAG 2.2 AA certified, 18 locales, PHPUnit test coverage.
- **Weaknesses:** Strict refusal of user-requested features. Lacks sticky/floating options, active scrollspy, reading time estimates, style presets, search/filter, and custom layout controls.
- **How we win:** Close the credibility gap first (i18n in R1, PHPUnit in R2, WCAG in R9), then dramatically out-feature it on user experience while keeping zero-JS as the default.

### Joli Table Of Contents (Freemium Reference)
- **Installs:** 7,000+ | **Rating:** 4.9 (43 reviews) | **Developer:** WP Joli
- **Strengths:** Sleek visual styling, color themes, onboarding wizard.
- **Weaknesses:** Aggressive paywalling. Floating TOC, progress bar, timeline, and multi-column require $49-$89/yr. Free version feels crippled.
- **How we win:** Commoditize their paid tier. Deliver sticky TOC (R4), reading time (R5), reading progress (R11), and multi-column (R15) completely free.

---

## 5. Strategic takeaways for product planning (through Q4 2027)

1. **Preserve the zero-JS core promise:** Any feature that introduces frontend JS must be strictly opt-in, isolated, lightweight vanilla JS, and disabled by default. The default TOC markup must remain zero-JS and byte-pure.
2. **Aggressively capture migration pools:** Convert users from LuckyWP, Rich TOC, and orphaned TOP TOC before competitors or core changes absorb them.
3. **Win on Core Web Vitals & INP:** In 2026-2027, Interaction to Next Paint (INP) is a primary Google ranking signal. Heavy TOC plugins hurt INP. Our zero-JS architecture is an unmatched SEO asset.
4. **Leverage AI / GEO (Generative Engine Optimization):** Implement Schema.org SiteNavigationElement and ItemList markup so AI search engines (Google AI Overviews, Perplexity) parse content structures cleanly.
5. **Freemium timing discipline:** Do not attempt a commercial Pro launch until reaching 1,000 active installs and 25 reviews (scheduled decision at R13, 2027-03-24). Until then, shipping Pro-level features free drives rapid organic acquisition.
