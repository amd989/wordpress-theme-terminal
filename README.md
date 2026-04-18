# Terminal — WordPress theme

A minimal retro-terminal blog theme for WordPress. This is a port of [panr/hugo-theme-terminal](https://github.com/panr/hugo-theme-terminal) — the original Hugo theme's CSS, typography, and markup have been preserved; Hugo templating has been rewritten as classic PHP templates.

![Terminal](https://github.com/panr/hugo-theme-terminal/blob/master/images/terminal-theme.png?raw=true)

## Install

```
cd wp-content/themes
git clone https://github.com/amd989/wordpress-theme-terminal.git terminal
```

Then activate under **Appearance → Themes**.

Requires WordPress 6.0+ and PHP 8.0+.

## Configure

All options live under **Appearance → Customize → Terminal Theme**:

- **Colors** — accent, background, foreground. Change any of these and the customizer preview updates live via CSS custom properties.
- **Layout & Navigation** — container mode (default / full / centered), logo text, site subtitle, keywords, menu "More"-dropdown threshold and label.
- **Post Display** — reading time, last-updated stamp, auto-cover-image, unit labels, date format.
- **Table of Contents** — site-wide enable + heading text. Individual posts override via the meta box.
- **Labels** — all user-facing button / heading strings.
- **Social & SEO** — Twitter card handles.
- **Analytics** — Google Analytics Measurement ID.

Per-post overrides live in the **Terminal Options** meta box on the post editor (side column): cover image URL, cover credit, noindex, hide comments, force reading time on this post, TOC on/off, date format override, show-full-content on listings.

Assign a menu to the **Main Navigation** location under **Appearance → Menus**.

## Shortcodes

### Collapsible code block

```
[code language="go" title="example.go" open="true"]
package main

func main() {
    println("hello")
}
[/code]
```

Argument names are preserved from the Hugo theme's `code` shortcode. Prism.js highlights the block client-side; the language identifier is the Prism short name (e.g. `go`, `bash`, `python`, `javascript`).

### Positioned image

```
[image src="https://example.com/img.jpg" alt="Alt text" position="center" width="600"]
```

Valid positions: `left` (default), `center`, `right`.

### Terms listing

```
[terminal_terms taxonomy="post_tag"]
```

Alphabetical list of taxonomy terms with post counts. Replaces Hugo's `terms.html` behavior.

## Syntax highlighting

Prism.js is loaded from jsDelivr with the autoloader plugin, so any language grammar loads on demand the first time it's used. The CSS color mapping in `assets/css/syntax.css` uses the same duotone variables (`--first-tone`, `--second-tone`, `--comment`) derived from your accent color.

To self-host Prism, drop a custom build from [prismjs.com/download](https://prismjs.com/download) into `assets/js/prism.js` and override the `prism-core` / `prism-autoloader` enqueues from a child theme's `functions.php`.

## Custom CSS overrides

Override theme CSS variables or add custom rules by editing `assets/css/terminal.css` (pre-bundled, but empty) or by adding a child theme.

### terminal-css theme generator

panr's [terminal-css generator](https://panr.github.io/terminal-css/) produces drop-in color palettes for this theme. Use the **theme** output (the 4-line `:root { --background; --foreground; --accent; }` block) — it's CSS-variable-only and layers cleanly on top of the theme.

Two ways to apply a generated palette:

- Paste the three hex values directly into **Appearance → Customize → Terminal Theme → Colors**.
- Or paste the full `:root {…}` block into `assets/css/terminal.css` to version-control the palette alongside the theme.

Do **not** use the generator's "standalone" output — that's a full stylesheet meant for pages with no other CSS loaded, and will conflict with the theme's own rules.

## Multi-language

The theme is translation-ready — UI strings are wrapped in `__()` / `_e()` and shipped with a `.pot` template in `languages/`. For content translation, install Polylang — the language switcher in the header activates automatically when Polylang's functions are present, and no-ops otherwise.

## Development

There is no build step. CSS is served directly from `assets/css/` in the order Hugo used (fonts → main → header → menu → footer → post → code → buttons → pagination → terms → syntax → gist → terminal). JS is in `assets/js/`. Make changes in place and refresh.

CI runs `php -l` against every `.php` file across supported PHP versions.

### Local test harness

```
docker compose up -d
```

Then open http://localhost:8080, run the WordPress install wizard, and activate **Terminal** under Appearance → Themes. The repo is bind-mounted into `wp-content/themes/terminal`, so edits to `.php` / `.css` / `.js` files are reflected on refresh.

WP-CLI is wired under the `cli` profile:

```
docker compose run --rm wpcli wp theme list
docker compose run --rm wpcli wp theme activate terminal
```

Stop + wipe:

```
docker compose down -v   # -v also removes the db + wp volumes
```

## Credits

- WordPress theme © 2026 [amd989](https://github.com/amd989) — original PHP templates, customizer integration, meta boxes, shortcodes, and WordPress plumbing.
- Design inspiration: Hugo theme [terminal](https://github.com/panr/hugo-theme-terminal) © 2024 [panr](https://github.com/panr) — palette, Fira Code typography, duotone code colors, container layout.
- [Prism.js](https://prismjs.com) © Lea Verou — syntax highlighting.
- [Fira Code](https://github.com/tonsky/FiraCode) © Nikita Prokopov — SIL Open Font License.

## License

MIT — see [LICENSE](LICENSE).
