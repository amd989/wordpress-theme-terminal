=== Terminal ===
Contributors: amd989
Tags: one-column, custom-colors, custom-menu, featured-images, threaded-comments, translation-ready, blog, rtl-language-support
Requires at least: 6.0
Tested up to: 6.6
Requires PHP: 8.0
Stable tag: 1.0.0
License: MIT
License URI: https://opensource.org/licenses/MIT

Minimal retro-terminal blog theme by amd989, inspired by panr's Hugo theme of the same name. Fira Code type, duotone syntax highlighting, customizer-driven accent colors.

== Description ==

Terminal is an original WordPress theme by amd989 whose aesthetic is inspired by the Hugo theme "terminal" by panr. All PHP templates, customizer integration, meta boxes, shortcodes, asset pipeline, and WordPress plumbing are written from scratch for WordPress; the look (palette, Fira Code typography, duotone code colors, container layout) follows the Hugo original. Code highlighting is provided by Prism.js rather than Chroma.

Features:

* Customizer controls for accent / background / foreground colors — all rendered via CSS custom properties so live preview updates instantly.
* Three layout modes: default (left-aligned with a dashed border), full width, centered.
* Configurable "More"-collapsing main navigation with per-break threshold.
* Per-post overrides via a "Terminal Options" meta box: cover image URL, noindex, hide comments, force reading time, TOC on/off, date format override, show-full-content on listings.
* Table of contents auto-generated from H2/H3 headings.
* Reading time and word count display.
* Cover image with credit — uses featured image, or auto-detects the first attachment when enabled, or falls back to a custom URL from the meta box.
* Collapsible code block shortcode: `[code language="go" title="example.go" open="true"]...[/code]`.
* Positioned image shortcode: `[image src="..." position="center" width="600"]`.
* Terms listing shortcode: `[terminal_terms taxonomy="post_tag"]`.
* Polylang-aware language switcher (no-op if Polylang is not installed).
* Translation-ready with .pot template.

== Installation ==

1. Upload the theme folder to /wp-content/themes/.
2. Activate via Appearance → Themes.
3. Configure under Appearance → Customize → Terminal Theme.
4. Assign a menu to the "Main Navigation" location under Appearance → Menus.

== Frequently Asked Questions ==

= Why are Prism scripts loaded from a CDN? =

To avoid bundling a multi-megabyte Prism build that most sites would never fully use. To self-host Prism instead, download a custom bundle from prismjs.com/download and override the `prism-core` / `prism-autoloader` enqueues in a child theme's functions.php.

= How do I preserve an existing Hugo content library? =

Run a markdown-to-WXR conversion (e.g. `wp import` after converting with a tool like hugo-to-wp). The theme itself does not ship a migration script.

== Credits ==

* WordPress theme © 2026 amd989 — https://github.com/amd989
* Design inspiration: Hugo theme "terminal" © 2024 panr — https://github.com/panr/hugo-theme-terminal
* Prism.js © Lea Verou — https://prismjs.com
* Fira Code font © Nikita Prokopov, SIL Open Font License — https://github.com/tonsky/FiraCode

== Changelog ==

= 1.0.0 =
* Initial release by amd989. Design inspired by panr's Hugo theme.
