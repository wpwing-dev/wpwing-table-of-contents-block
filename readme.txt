=== Table of Contents (TOC) Block - Fast & SEO Friendly ===

Contributors:      wpwing, voboghure
Donate link:       https://wpwing.com/
Tags:              TOC, Table of Contents, Navigation, SEO, Gutenberg Blocks
Stable tag:        1.7.0
Requires at least: 5.8
Tested up to:      7.0
Requires PHP:      7.1
License:           GPL-3.0-or-later
License URI:       https://www.gnu.org/licenses/gpl-3.0.html

Instantly add an SEO-friendly, automated, and hyper-fast Table of Contents block to your posts and pages. Fully optimized for Gutenberg and Core Web Vitals.

== Description ==

Boost your site’s SEO and maximize reader engagement with the **Table of Contents (TOC) Block - Fast & SEO Friendly**.

Long articles can overwhelm readers, causing them to leave your site early. This plugin automatically scans your content headings and generates a clean, nested list of anchor links. This allows users to jump directly to the section they need, decreasing bounce rates and satisfying search engines like Google.

Unlike clunky legacy plugins, our Table of Contents block is custom-built for the modern WordPress Gutenberg editor. By default it adds **no JavaScript** to your frontend - and if you enable an interactive extra like the collapsible toggle, it prints only a few lines of inline script with no external files and no jQuery. Your website stays lightning-fast and scores perfectly on Core Web Vitals.

### ⚡ Super Lightweight & Performance-First
We know page speed matters for SEO. Our block generates minimal, valid HTML and injects absolutely no extra resources or external scripts. It's built to perform seamlessly without adding bloat to your database or frontend.

### 🎨 Complete Customization Control
Unlike plugins that lock basic layout settings behind a pro version, you get full control over your layout directly inside the native block settings:
* **Style Natively:** Easily add background colors, custom padding, and text colors by wrapping the TOC inside standard Gutenberg groups.
* **Flexible Layouts:** Full native block support for both Wide Width and Full Width settings to match your theme.
* **Depth Control:** Choose exactly which headings to include by restricting the maximum depth (e.g., show only H2 and H3, hide H4+).
* **HTML Flexibility:** Choose between an Ordered (numbered) or Unordered (bulleted) HTML list structure for better semantic SEO.
* **Tidy Formatting:** Toggle list indents on or off with a simple click to keep deep nesting looking clean.
* **Header Freedom:** Disable the built-in H2 headline of the TOC block entirely from the sidebar, allowing you to write your own personalized introduction using standard heading blocks.

### 🚀 Key Features At A Glance
* **100% Automated:** Scans content instantly to generate accurate internal jump links.
* **Auto-Insert:** Optionally show the TOC on posts, pages, or custom post types without adding the block - choose the position under Settings > TOC Block. Placing the block manually in a post always overrides it.
* **Pure Performance:** No frontend JavaScript unless you enable an interactive feature - fully optimized for page speed.
* **Designed for Gutenberg:** Integrates natively into the WordPress block editor experience.
* **Responsive Layouts:** Inherits theme styles fluidly for perfect rendering on mobile devices.

### 🔌 Ultimate Compatibility
We ensure our block plays nicely with your entire WordPress stack.
* Fully supports **Rank Math** and top-tier SEO plugins for schema optimization.
* Works seamlessly with the **GeneratePress** theme and major page frameworks.
* 100% compatible with popular **AMP plugins** for mobile-first rendering.

### 🧑‍💻 Dedicated Startup Support
At WPWing, we are committed to building high-quality, lightweight utility plugins for WordPress and WooCommerce. Our dedicated support team responds rapidly to the support forums to help you resolve any issues instantly.

== Installation ==

1. Log into your WordPress dashboard, navigate to **Plugins > Add New**.
2. Search for `Table of Contents (TOC) Block - Fast & SEO Friendly`.
3. Click **Install Now** and then **Activate**.
4. Alternatively, upload the plugin files directly to the `/wp-content/plugins/` directory.
5. Open any post or page in the Gutenberg editor, type `/toc` or search for "Table of Contents", and add the block anywhere you like!

== Frequently Asked Questions ==

= How do I change the default "Table of Contents" text? =
It's simple! Select the block, look at the sidebar options on the right, and toggle off the default headline. You can then insert a standard WordPress Heading block right above the TOC and type whatever you prefer (e.g., "What's Inside", "Quick Navigation").

= How can I add a background color or border to the block? =
Because this plugin utilizes native Gutenberg styling, simply click the three dots on the block toolbar and select **Group**. Once grouped, you can use the native block editor sidebar to add background colors, text colors, paddings, and borders to the entire group.

= How do I exclude a specific heading from the TOC? =
Select the heading block you want to hide, open the **Advanced** panel in the block sidebar, and add `wpwing-toc-hidden` to the **Additional CSS class(es)** field. That heading will be skipped when the TOC is generated.

= Does this plugin add extra scripts or bloat to my site? =
No. The plugin generates lightweight, valid, and semantic HTML and never loads external JavaScript files. If you enable an interactive option (collapsible toggle, back-to-top, or copy-link buttons), a few lines of inline script are printed in the footer - nothing else. With those options off, the frontend is 100% JavaScript-free.

= My sticky header covers the heading when I click a TOC link. How do I fix it? =
Select the block, open the **Links & Behavior** panel, and set **Scroll offset (px)** to roughly the height of your sticky header. Headings will then land below the header instead of behind it. This is done with pure CSS - no JavaScript is added.

= Can the TOC appear automatically without adding the block to every post? =
Yes. Go to **Settings > TOC Block**, check the post types you want, and pick a position (before the first heading, after the first paragraph, or top of content). The automatic TOC uses the same fast renderer as the block, skips short posts below your minimum-headings threshold, and steps aside on any post where you place the block manually.

= I set a custom HTML anchor on a heading - will the TOC use it? =
Yes. If you give a heading your own anchor via its **Advanced > HTML anchor** field, the TOC links to that anchor instead of generating one, so your existing incoming links keep working.

== Screenshots ==

1. Editor with Table of Contents block
2. Block inserter section
3. Block editor section
4. Block settings section
5. Output block with default settings
6. Output block using Group and background color

== Changelog ==

= 1.7.0 - 19/07/2026 =

* New: Auto-insert - show the TOC automatically on posts, pages, and public custom post types without adding the block. Configure it on the new Settings > TOC Block screen.
* New: Auto-insert position - choose before the first heading, after the first paragraph, or top of the content.
* New: Auto-insert minimum headings - posts with fewer qualifying headings are skipped so short posts stay clean.
* Note: The automatic TOC uses the exact same renderer as the block - identical output, still no frontend JavaScript. Adding a TOC block to a post manually always overrides auto-insert for that post.

= 1.6.0 - 12/07/2026 =

* New: Scroll offset - set a pixel offset in the block sidebar so headings are not hidden behind sticky or fixed headers when jumping from the TOC. Pure CSS, no JavaScript added.
* Fix: TOC links to headings on other pages of paginated posts now build correct URLs on sites using plain or non-standard permalinks.
* Improvement: RTL support - list indentation, the heading count badge, the copy-link button, and the collapsible toggle icon now render correctly in right-to-left languages.

= 1.5.0 - 05/07/2026 =

* New: Collapsed by default - optionally start the collapsible TOC hidden until the reader expands it.
* New: Hide TOC from search snippets - one click adds the data-nosnippet attribute so search engines skip the TOC text when building result snippets.
* New: Custom HTML anchors set on a heading block (Advanced > HTML anchor) are now respected - the TOC links to your anchor instead of overwriting it, so existing incoming links keep working.
* Fix: Headings containing accented or non-Latin characters (e.g. Ü, é, Bangla, emoji) are no longer corrupted on the frontend when the TOC block is present.

= 1.4.0 - 28/06/2026 =

* New: Hierarchical numbering - a new "List style" option that prefixes each entry with 1, 1.1, 1.1.1 style numbers reflecting the heading structure.
* New: Copy link button - optionally show a button beside each TOC item that copies a direct link to that section to the clipboard.
* Fix: TOC list HTML is now well-formed when the shallowest heading level is hidden from the TOC by keyword or the `wpwing-toc-hidden` class, not just when the last heading is hidden.
* Fix: Heading anchor IDs are now de-duplicated, so posts with two or more headings that share the same text get unique links that jump to the correct section.
* Fix: Heading anchor IDs and TOC links now stay in sync for headings that contain HTML entities such as &amp;.

= 1.3.0 - 21/06/2026 =

* New: Heading count badge - optionally display the number of visible headings next to the TOC title.
* New: Exclude headings by keyword - type comma-separated keywords in the block sidebar; any heading whose text contains a match is hidden from the TOC and skipped by per-section back-to-top links.
* Fix: The "minimum headings to show TOC" threshold now correctly counts only the headings that will actually appear in the TOC, so keyword-excluded headings no longer count toward the minimum.
* Fix: The "Back to top" link inside the TOC now scrolls back to the top of the page correctly even when JavaScript is disabled.
* Fix: TOC list HTML is now always well-formed when the last heading in a post is hidden from the TOC (via CSS class or keyword exclusion).
* Improvement: The block sidebar now shows an informational notice when the heading count badge is enabled but the TOC title is hidden, since the badge has nowhere to appear in that configuration.

= 1.2.0 - 14/06/2026 =

* New: Native color picker - set the TOC background and text color directly from the block sidebar without needing to wrap it in a Group block.
* New: Per-section "Back to top" links - optionally insert a return-to-top link after every heading in the post content.
* New: Minimum headings threshold - optionally hide the TOC automatically if the post has fewer qualifying headings than a number you choose.
* New: Exclude headings from the TOC by adding the CSS class `wpwing-toc-hidden` to any heading block via its Advanced settings panel.
* New: Yoast SEO integration - the block is now recognized as a TOC by Yoast SEO in addition to the existing Rank Math support.
* Fix: Headings with multiple CSS classes (e.g. a font size applied alongside `wpwing-toc-hidden`) are now correctly excluded from the TOC.
* Fix: Hidden headings no longer receive a per-section "Back to top" link in the post content.
* Fix: The TOC no longer renders an empty `<nav>` element when all headings fall outside the selected minimum/maximum level range.
* Fix: Back to top links now respect the "Enable smooth scrolling" setting instead of always scrolling smoothly.
* Fix: Heading anchor IDs are now found and applied correctly inside nested block structures such as Groups and Columns, and page numbers are now assigned accurately to headings inside those nested structures too.
* Improvement: Collapsible toggle button now shows a visible focus ring for keyboard users.
* Improvement: "Back to top" link styling is now applied consistently to both the TOC link and per-section links in the post content.

= 1.1.0 - 07/06/2026 =

* New: Collapsible TOC - add a toggle button so readers can show or hide the table of contents with one click.
* New: Boxed style preset - wrap the TOC in a subtle bordered box with a single click from the sidebar.
* New: "Back to top" link - optionally show a link below the TOC that scrolls readers back to the top of the page.
* Improvement: Sidebar settings are now grouped into three clear sections - Content, Display, and Links & Behavior - making them much easier to navigate.
* Improvement: Renamed confusing labels. "Remove heading" is now "Hide TOC Title", "Remove list indent" is now "Flat list (no indent)", and "Smooth scrolling support" is now "Enable smooth scrolling".
* Improvement: Added a warning when the minimum heading level is set deeper than the maximum, so you know immediately if the TOC will be empty.
* Improvement: All sidebar controls now have clear, plain-language descriptions explaining exactly what each option does.
* Improvement: Added safe default list spacing so the TOC looks consistent across all themes without overriding your theme's styling.

= 1.0.9 - 30/05/2026 =

* New: Custom Title — set your own TOC heading text directly from the block sidebar.
* New: Minimum Heading Level — exclude shallow headings (e.g. start from H3, hiding H2s).
* Improvement: TOC output is now wrapped in a semantic <nav> element with an aria-label for better accessibility and screen reader support.

= 1.0.8 - 22/05/2026 =

* Fix: Several PHP fatal errors that could occur in certain edge cases (widgets, templates, reusable blocks).
* Fix: Rank Math SEO integration was not being detected due to a plugin slug typo.
* Fix: Smooth scroll CSS was incorrectly applied site-wide even when the option was disabled.
* Security: Improved output escaping in the rendered TOC HTML.
* Improvement: Modernized block editor sidebar markup.
* Improvement: Various internal code quality and reliability improvements.
* Compatibility: Tested and confirmed compatible with WordPress 7.0.

= 1.0.7 - 28/07/2025 =

* Update: Compatibility with WP 6.8
* Few minor improvements.

= 1.0.6 - 22/04/2024 =

* Update: Compatibility with WP 6.5
* Update: Name and description update
* Fix: Update text domain in js file
* Few minor improvements.

= 1.0.5 - 18/02/2024 =

* Bump version update.
* Few minor improvements.

= 1.0.4 - 02/02/2024 =

* Update: NPM packages.
* Update: Compatibility with the latest WordPress.
* Few minor improvements.

= 1.0.3 - 14/06/2022 =

* Update: Change block name.
* Update: NPM packages.
* Fix: Remove unused code.
* Few minor improvements.

= 1.0.2 - 26/06/2022 =

* Update: Change block category to widgets.
* Fix: Refactor code.
* Few minor improvements.

= 1.0.1 - 18/06/2022 =

* Fix: Refactor & mark deprecated some code.
* Few minor improvements.

= 1.0.0 - 08/06/2022 =

* Initial release
