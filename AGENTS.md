# AGENTS.md — ElementStack Addons for Elementor

WordPress/Elementor addon plugin: 74 custom widgets + Header/Footer theme-builder module. Prefix: `BDEA` (classes) / `bdea_` (functions, hooks). Text domain: `elementstack-elementor-addons`. Requires Elementor (site has 4.2.2).

## Commands (run from plugin root)

| Command | Purpose |
|---|---|
| `cmd /c "tools\wp.cmd plugin list"` | WP-CLI (site: `http://plugin-test.local`, DB port 10005) |
| `cmd /c "tools\wp.cmd eval-file tools\qa-smoke.php"` | Smoke test: boots WP, verifies plugin + Elementor widgets registered |
| `cmd /c "tools\php.cmd tools\qa-smoke.php"` | Same as above (no wp-cli wrapper needed) |
| `cmd /c "tools\php.cmd tools\lint.php"` | PHP syntax lint all files (89 files, ~20s) |
| `cmd /c "tools\wp.cmd plugin check elementstack-elementor-addons --exclude-directories=tools"` | Official WP quality check (slow, use after major work) |
| `cmd /c "tools\php.cmd <script.php>"` | Run any PHP against bundled PHP 8.2.29 (mysqli loaded, port 10005) |
| `cmd /c "tools\wp.cmd eval '<php>'` | Run inline PHP in WP context |

## Test after EVERY change

1. `tools\php.cmd tools\lint.php` — syntax
2. `tools\php.cmd tools\qa-smoke.php` — plugin loads + widgets registered
3. If logic touched: `tools\wp.cmd eval-file <test.php>` with a temporary test file (gitignored: `test-*.php`, `verify-*.php`, `debug-*.php`)
4. Check `C:\Users\india\Local Sites\plugin-test\app\public\wp-content\debug.log` for PHP warnings/fatals
5. Major changes: `tools\wp.cmd plugin check ...` (see above)

## Conventions

- Files: `widgets/*.php` (74 widgets, each `bdea_<name>` slug), `modules/header-footer/` (Admin.php, Document.php, FrontendRender.php, MetaBox.php, Module.php, PostType.php), `Framework/` (Cache, Conditions, Elementor\ThemeBuilderDocument, Renderer\TemplateRenderer, WidgetConditions)
- Class prefix `BDEA\`, PSR-4 autoloader maps `BDEA\` to plugin root (main file: `elementstack-elementor-addons.php`)
- WordPress coding style, snake_case functions, `esc_html_e/esc_attr__` escaping, i18n text domain
- Do NOT add code comments unless asked
- Do not commit unless explicitly asked

## Rules

- Never edit files in `tools/` unless the task involves tooling itself.
- Never modify the site's `wp-config.php` or Local config.
- Keep changes scoped to the file(s) related to the request; no refactors unless asked.
- After finishing, run lint + smoke test and report results.
