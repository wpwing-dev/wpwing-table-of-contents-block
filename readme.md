# Table of Contents (TOC) Block — Fast & SEO Friendly

![Version](https://img.shields.io/badge/version-1.7.0-blue) ![WordPress](https://img.shields.io/badge/WordPress-5.8%2B-21759b) ![PHP](https://img.shields.io/badge/PHP-7.1%2B-777bb4) ![License](https://img.shields.io/badge/license-GPL--3.0--or--later-green)

Automatically generate a clean, nested Table of Contents from your post headings - no frontend JavaScript unless you enable interactive extras. Built natively for the WordPress Gutenberg block editor and fully optimized for Core Web Vitals.

[WordPress.org Plugin Page](https://wordpress.org/plugins/wpwing-table-of-contents-block/) · [Support Forum](https://wordpress.org/support/plugin/wpwing-table-of-contents-block/) · [WPWing.com](https://wpwing.com/)

---

## Features

- **JavaScript-free by default** - interactive extras (collapse, back-to-top, copy link) print a few lines of inline script only when enabled
- **Automated** - scans content headings and generates anchor links instantly
- **Auto-insert** - show the TOC on chosen post types without placing the block; pick the position (before first heading, after first paragraph, or top of content) under Settings > TOC Block
- **Respects custom anchors** - headings with their own HTML anchor keep it; the TOC links to it instead of overwriting
- **Collapsed by default** - optionally start the collapsible TOC hidden
- **Search snippet control** - optional `data-nosnippet` attribute keeps TOC text out of Google result snippets
- **Scroll offset** - keep headings visible below sticky/fixed headers when jumping from the TOC (pure CSS)
- **RTL ready** - indentation, badges, and toggle icon adapt to right-to-left languages
- **Depth control** - choose minimum and maximum heading levels to include (H2-H6)
- **Custom TOC title** - set your own heading text directly from the sidebar
- **Collapsible** - optional show/hide toggle button so readers can collapse the TOC
- **Style presets** - Default or Boxed, selectable from the sidebar
- **Native color picker** - set TOC background and text color directly from the block sidebar
- **"Back to top" link** - optional link below the TOC that scrolls readers back to the top
- **Per-section back to top links** - optionally insert a return-to-top link after every heading in the post content
- **Minimum headings threshold** - automatically hide the TOC if the post has fewer qualifying headings than a number you choose
- **Exclude headings** - add the CSS class `wpwing-toc-hidden` to any heading block to skip it from the TOC
- **List styles** - bulleted, numbered, or hierarchical (1.1, 1.1.1) numbering
- **Copy link button** - optional per-item button that copies a direct link to that section
- **Ordered or unordered** list output for semantic flexibility
- **Toggle indentation** for deeply nested heading structures
- **Optional built-in headline** - disable to write your own heading block
- **Accessible markup** - semantic `<nav>` wrapper with `aria-label` for screen readers
- **Wide & Full Width** alignment support
- Compatible with **Rank Math**, **Yoast SEO**, **GeneratePress**, and **AMP plugins**

## Requirements

| Requirement | Minimum |
|---|---|
| WordPress | 5.8 |
| PHP | 7.1 |
| Node.js | 18+ (dev only) |

## Installation (End Users)

1. In your WordPress dashboard go to **Plugins → Add New**.
2. Search for `Table of Contents TOC Block`.
3. Click **Install Now**, then **Activate**.
4. In any post or page, type `/toc` in the block editor to insert the block.

## Development Setup

```bash
# Clone the repository
git clone https://github.com/wpwing/wpwing-table-of-contents-block.git
cd wpwing-table-of-contents-block

# Install dependencies
npm install

# Start the development watcher
npm start
```

### Available Scripts

| Script | Description |
|---|---|
| `npm start` | Start development build watcher |
| `npm run build` | Production build |
| `npm run lint:js` | Lint JavaScript files |
| `npm run lint:css` | Lint CSS/SCSS files |
| `npm run format` | Auto-format source files |
| `npm run dist` | Build, zip, and output to `dist/` |

The compiled build output goes to the `build/` directory. The `dist/` script produces a ready-to-upload `wpwing-table-of-contents-block.zip`.

## Contributing

Pull requests are welcome! To contribute:

1. Fork this repository
2. Create a feature branch (`git checkout -b feature/my-improvement`)
3. Make your changes and run `npm run lint:js && npm run lint:css`
4. Submit a pull request against `master`

For bug reports or feature requests, please use the [WordPress.org support forum](https://wordpress.org/support/plugin/wpwing-table-of-contents-block/).

## License

[GPL-3.0-or-later](https://www.gnu.org/licenses/gpl-3.0.html)
