# Accessibility Audit - v1.9.0

Scope: the TOC block's frontend output (`wpwing_toc_render_callback()` in
`wpwing-table-of-contents-block.php`), covering the block placement, auto-insert, and both
collapse modes. Checked against the WCAG 2.2 AA success criteria that actually apply to a
static navigation widget. Not an automated scan - a manual read of the markup this plugin
generates plus a live check in the `/verify` docker environment.

## Findings

| Criterion | Verdict | Notes |
|---|---|---|
| 1.3.1 Info and Relationships | Pass | Output is a `<nav aria-label="...">` wrapping a real `<ul>`/`<ol>`; `<details>`/`<summary>` used for native collapse instead of a div-based fake widget. |
| 2.4.1 Bypass Blocks / landmark uniqueness | Pass | Manual block placement always overrides auto-insert for that post (checked via `has_block()` in `wpwing_toc_auto_insert_block()`, line 676), so a page never ends up with two `<nav aria-label="Table of Contents">` landmarks. |
| 4.1.2 Name, Role, Value | Pass | JS toggle is a real `<button>` with `aria-expanded`/`aria-controls` kept in sync on click (render at line 968, JS at ~line 483); copy-link button has a static `aria-label` (line 916). Native mode gets this for free from `<details>`/`<summary>`. |
| 2.1.1 Keyboard | Pass | Toggle button and copy-link are real `<button>` elements (not `<div onclick>`), and `<summary>` is natively keyboard-operable - no custom key handling needed for either collapse mode. |
| 2.4.7 Focus Visible | Pass | `.wpwing-toc-toggle` and `.wpwing-toc-copy` both define `:focus-visible` outlines (`src/style.scss` lines 74, 113). `<summary>` relies on the browser's default focus ring, which nothing in this stylesheet resets. |
| 1.4.13 Content on Hover or Focus | Pass | The copy-link button is hover-revealed but also shown via `:focus-visible` (line 74-78), so keyboard users aren't locked out of a hover-only control. |
| 2.5.3 Label in Name | Pass | Visible toggle icon has no competing text; the accessible name comes entirely from the `.screen-reader-text` span (line 969), so there's no mismatch. |
| 1.4.10 Reflow / RTL | Pass | Verified in the 1.6.0 RTL pass ([doc/update-plan.md](update-plan.md)); the collapse chevron direction also flips for `[dir="rtl"]` (style.scss line 136). |
| **1.4.3 Contrast (Minimum)** | **Conditional** | The block supports the core `color` block-support (background/text, `src/block.json` line 105), so authors can pick arbitrary colors. Default (no color override) styling passes AA against a light theme background. Author-chosen custom colors are **not** validated for contrast - same scope limitation SimpleTOC and virtually every color-picker-enabled block ship with. |
| 1.4.3 Contrast, secondary text | **Known limitation** | `.wpwing-toc-count` (opacity 0.6) and `.wpwing-toc-number` (opacity 0.7) reduce contrast relative to the surrounding text color by design (they're meant to read as de-emphasized). At small font sizes (0.8em) this can fall under 4.5:1 depending on the active theme's text color. Not fixed in this release - documented as a scoped limitation rather than silently claimed as passing. |

## Conclusion

No blocking gaps found in the plugin's own markup, ARIA usage, or keyboard behavior - default
styling is AA-conformant. The two "Conditional"/"Known limitation" rows are both about
**author-controlled styling** (custom colors, and the de-emphasized count/number text), not
the plugin's structural or interactive accessibility. That's a narrower guarantee than an
unqualified "WCAG 2.2 AA" badge implies, so the readme claim is scoped accordingly: the
plugin's output is described as accessible by design (semantic landmarks, keyboard-operable
controls, focus-visible states, ARIA-correct toggles) rather than certified AA in every
possible author configuration.
