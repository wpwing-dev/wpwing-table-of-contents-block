# Growth / Marketing Plan

Goal: grow from **10+ active installs** (4.0 rating, 1 review, snapshot 11/09/2026) to a
self-sustaining install base that supports a Pro launch decision at the end of Q1 2027.

Written alongside [COMPETITORS.md](COMPETITORS.md) and [UPDATE-PLAN.md](UPDATE-PLAN.md).
Review monthly; refresh the competitor snapshot quarterly.

## Targets

wordpress.org shows installs in brackets (10+, 20+, ... 100+, 200+, ...) and growth compounds
slowly at first. Honest targets:

| Milestone | Target date | Leading indicators |
|---|---|---|
| 50+ installs, 5 reviews | Nov 2026 | downloads/day > 10, biweekly cadence held |
| 100+ installs, 10 reviews | Jan 2027 | first-page directory rank for 1 keyword |
| 300+ installs, 20 reviews | Mar 2027 | LuckyWP migration content ranking in Google |
| 1,000+ installs | Sep 2027 | organic installs consistently exceed churn |

Track weekly: active installs bracket, downloads/day (wordpress.org advanced stats), review
count and average, directory search position for "table of contents", "toc block",
"table of contents block", support threads answered, and LuckyWP import events if we ever add
an opt-in counter.

---

## 1. Win the wordpress.org directory (the only channel that matters at 10 installs)

Almost all installs at this scale come from directory search. The ranking algorithm weights
readme keyword relevance, active install growth rate, rating count and quality, support
thread resolution, freshness / tested-up-to, and translations. Every action below targets one
of those inputs.

### 1a. Listing hygiene (this week, near-zero effort)

- [ ] Keep **Tested up to: 7.1** current, and bump it within days of every WordPress release.
      Stale values are the cheapest ranking loss in the directory.
- [ ] Hold the biweekly cadence from UPDATE-PLAN.md. Freshness is a ranking input we fully
      control, and "Last updated: 2 weeks ago" beats every abandoned competitor on the page.
- [ ] Review the 5 tags. Current: TOC, Table of Contents, Navigation, SEO, Gutenberg Blocks.
      "Navigation" is broad and low-intent - test swapping it for "anchor links" or
      "table of contents block" against directory tag search volume.
- [ ] Rewrite the readme **short description** around the highest-volume phrases: "table of
      contents", "TOC block", "Gutenberg", and "no JavaScript" should read naturally in the
      first sentence.

### 1b. Live preview button (this month, high leverage, almost nobody does it)

- [ ] Add a WordPress Playground **blueprint** (`assets/blueprints/blueprint.json` in the SVN
      repo, which is separate from this repo's `src/assets/`) so
      the listing gets a "Live Preview" button: a seeded long post with real headings, the
      TOC block already placed, auto-insert configured.
- None of the nine tracked competitors ships one. It converts undecided visitors, and it
  demonstrates the one thing our screenshots cannot - that the block just works with no setup.
- Cheap for us specifically: the `make dev` stack already seeds realistic sample content
  (1.8.0), so the blueprint is a translation of work we have done, not new work.

### 1c. Screenshots and media (this month)

- [ ] `make screenshots` already produces the 8-shot set and rewrites readme.txt captions.
      Re-order it so the **rendered frontend TOC leads**, not the editor or settings screen.
- [ ] Annotated captions on every screenshot - captions are indexed by directory search.
- [ ] Professional banner (1544x500) and icon. Next to SimpleTOC and Joli our listing must
      not look homemade.
- [ ] 45-60 second demo video (install -> add block -> rendered TOC -> auto-insert toggle)
      embedded in the readme via YouTube.

### 1d. Review engine (continuous - the single biggest ranking lever)

1 review vs. LuckyWP's 885 and SimpleTOC's 77 is the gap that matters most. Mechanisms:

- [ ] **In-plugin review prompt**: one dismissible admin notice, shown once, after the block
      has rendered on the frontend a meaningful number of times - not on activation. Triggers
      at delivered value, never blocks work, dismissible forever.
- [ ] **Post-import prompt**: users who complete a LuckyWP import have just been saved an
      afternoon of reconfiguration. That is the single highest-converting moment we have.
- [ ] **Support-to-review pipeline**: after every resolved support thread, one polite sentence
      asking for a review. Resolved-thread users convert best.
- [ ] **Hard constraints** - TOP Table Of Contents was closed by wordpress.org on 09/06/2026
      over guideline violations including trackers and lost settings. No telemetry, no
      undismissable notices, no admin-wide branding, and never lose settings on update.

### 1e. Translations (Q1 2027)

SimpleTOC ships 18 locales; we ship none, and the plugin has no `languages/` directory or
`.pot` file at all. Translation completeness feeds directory ranking in non-English locales
and unlocks entire search markets we currently do not appear in.

- [ ] Generate and commit `src/languages/*.pot`, then open the project on translate.wordpress.org.
- [ ] Seed 3-5 high-volume locales (de_DE, es_ES, fr_FR, pt_BR, nl_NL) so the project has
      momentum before asking for volunteer translation editors.

---

## 2. Capture the migration pools (our sharpest, most defensible wedge)

Three competitor install bases are unmaintained and searchable:

| Source | Installs | Status | Our hook |
|---|---|---|---|
| LuckyWP Table of Contents | 100,000+ | Abandoned since 04/2025, tested to 6.7.7 | One-click importer shipped in 1.10.0 |
| Rich Table of Contents | 20,000+ | ~1 year stale, tested to 6.8.8 | Importer planned, UPDATE-PLAN R7 |
| TOP Table Of Contents | unknown | Closed by wordpress.org 09/06/2026 | No update path at all - security angle |

Actions:

- [ ] **Landing content per pool.** One page each: "Switching from LuckyWP Table of Contents",
      "Rich Table of Contents alternative", "TOP Table Of Contents was removed - what now".
      Each covers why the source is unsafe, the feature mapping table, and the one-click
      import path. These target long-tail searches with real intent and near-zero competition.
- [ ] **readme.txt FAQ entries** mirroring each landing page - the 1.10.0 LuckyWP FAQ entry is
      the template. Directory search indexes FAQ text.
- [ ] **Answer migration questions in competitors' own support forums** where the rules allow
      it: a genuinely helpful reply on an abandoned plugin's forum is permitted and visible to
      exactly the right audience. Never spam, never lead with the plugin name.
- [ ] **Screenshot the import flow** in the listing set - the proof is the two-click screen.

---

## 3. Content and SEO (compounding, start now, pays from Q1 2027)

Directory search saturates quickly at low install counts; Google is what breaks a plugin out.

- [ ] **Comparison pages** (highest intent): "SimpleTOC vs TOC Block", "Easy Table of Contents
      alternatives", "best table of contents plugin without JavaScript". Be factually accurate
      about competitors - the competitor doc is the source, and being fair is what makes these
      pages linkable.
- [ ] **Technical posts that only we can write** and that reinforce positioning: "Why your
      table of contents plugin is hurting Core Web Vitals", "Building a zero-JavaScript
      collapsible with `<details>`", "Heading anchors, `scroll-margin-top`, and sticky headers".
- [ ] **Docs as SEO surface.** `src/docs/index.html` ships with the plugin and is linked from the
      plugin row. Publish the same content on the site with proper headings and internal links.
- [ ] Target one keyword cluster per month, not ten. Measure with Search Console position
      changes, not traffic - traffic lags position by months at this scale.

---

## 4. Positioning (say one thing, repeat it everywhere)

**"The table of contents block that adds nothing to your page."**

Everything else is proof of that claim:

- Zero frontend JavaScript by default; a few inline lines only if you turn on an interactive
  extra. No external files, no jQuery.
- Native `<details>` collapsible mode - interactivity with literally zero script.
- Gutenberg-native, not a shortcode with a block wrapper bolted on.
- Accessible markup by default: semantic `nav`, real buttons, correct ARIA, visible focus.
- Every layout and behavior setting is free. Nothing basic is held back.

Sharpen it against each competitor: Easy TOC and TOC+ load frontend assets unconditionally and
keep the block behind Pro; Joli and Easy TOC gate sticky/progress behind $29.99-$49/yr;
LuckyWP and Rich TOC are unmaintained. SimpleTOC is the only honest rival on this claim -
against it we compete on feature breadth and on shipping, not on rhetoric.

**Anti-positioning - things we will not do:** no upsell nags in the editor, no settings
takeover, no telemetry, no "Pro" badges scattered through the free UI. That restraint is
itself marketing in a category where the leaders do all four.

---

## 5. Support as a growth channel

At this size every support thread is a review candidate and a public quality signal.

- [ ] Respond to every wordpress.org thread within 24 hours, and mark resolved threads
      resolved - resolution rate is a documented ranking input.
- [ ] Convert every recurring question into a readme FAQ entry and a `src/docs/index.html` section
      in the same release the question is asked.
- [ ] Publish the fix in the very next biweekly release and reply in-thread with the version
      number. "Fixed in 1.13.0, released today" is the single most effective review request
      that exists.

---

## 6. Pro tier - decision, not campaign

No Pro launch is committed in this plan. The Pro feature list (scrollspy, sticky/floating TOC,
progress bar, reading time, per-device visibility, advanced insert rules) is the same list
Joli and Easy TOC already sell, which proves demand but also means we would enter as the
smallest vendor in the category.

Gate the decision on install count, not on calendar date. **Do not schedule a Pro launch below
1,000 active installs and 25 reviews.** Below that, a paid tier costs more in support load,
licensing infrastructure, and free-tier goodwill than it returns. The decision point is the
end of Q1 2027 (UPDATE-PLAN R13), evaluated against the milestone table above.

If the numbers do not clear the bar, the correct move is to ship one of the gated features
free and take the differentiation instead.

---

## 7. Monthly review checklist

- Installs bracket, downloads/day, review count and average.
- Directory search position for the three target keywords.
- Competitor snapshot deltas - version, last-updated, install bracket, rating.
- Which planned release actually shipped, and on which date (cadence is a ranking input).
- One-line answer: what did we do this month that a competitor cannot copy in a week?
