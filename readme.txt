=== Table of Contents (TOC) Block - Fast & SEO Friendly ===

Contributors:		wpwing, voboghure
Donate link:		https://wpwing.com/
Tags:				TOC, Table of Contents, Navigation, SEO, Gutenberg Blocks
Stable tag:			1.0.9
Requires at least:	5.8
Tested up to:		7.0
Requires PHP:		7.1
License:			GPL-3.0-or-later
License URI:		https://www.gnu.org/licenses/gpl-3.0.html

Instantly add an SEO-friendly, automated, and hyper-fast Table of Contents block to your posts and pages. Fully optimized for Gutenberg and Core Web Vitals.

== Description ==

Boost your site’s SEO and maximize reader engagement with the **Table of Contents (TOC) Block - Fast & SEO Friendly**.

Long articles can overwhelm readers, causing them to leave your site early. This plugin automatically scans your content headings and generates a clean, nested list of anchor links. This allows users to jump directly to the section they need, decreasing bounce rates and satisfying search engines like Google.

Unlike clunky legacy plugins, our Table of Contents block is custom-built for the modern WordPress Gutenberg editor. It adds **zero JavaScript** to your frontend, keeping your website lightning-fast and ensuring you score perfectly on Core Web Vitals.

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
* **Pure Performance:** Zero JavaScript added to the frontend—fully optimized for page speed.
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

= Does this plugin add extra scripts or bloat to my site? =
Absolutely not. The plugin generates lightweight, valid, and semantic HTML structure. It does not load any external JavaScript, keeping your page load times completely unaffected.

== Screenshots ==

1. Editor with Table of Contents block
2. Block inserter section
3. Block editor section
4. Block settings section
5. Output block with default settings
6. Output block using Group and background color

== Changelog ==

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
