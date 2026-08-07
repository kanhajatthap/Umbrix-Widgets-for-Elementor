# ElementStack Addons — Quality Control Report (Aug 2026)

Full QC pass across all 74 registered widget files, compared against Elementor (free) and Elementor Pro equivalents. Three parallel audits were run (PHP code quality, Pro feature-gap analysis, asset/CSS/JS audit) plus browser verification on a live test page.

## 1. Inventory

- **74 widget files** in `widgets/` (QA-CHECKLIST.md previously said 29 — outdated)
- All register under the `elementstack-elements` category
- Assets: 4 CSS files, 4 JS files + editor JS; all registered handles resolve to existing files (no 404s)
- Widgets use `get_style_depends()` / `get_script_depends()` correctly; no direct `wp_enqueue_*` from widgets

## 2. Bugs Found & Fixed

| # | Severity | Bug | Fix |
|---|----------|-----|-----|
| 1 | **Critical** | `form.php` submitted to `action="#"`; no server handler existed anywhere; JS faked a success message (submissions silently lost) | Added `bdea_handle_form_submit()` (nonce-verified, sanitized, `wp_mail`) hooked to `admin_post(_nopriv)_bdea_form_submit` in the main plugin file (not the widget loader, which never runs on admin-post requests). Form now posts to `admin-post.php` with hidden action field + nonce; JS does a real `fetch` with `X-Requested-With: XMLHttpRequest` and shows real success/error from the JSON response. Added Success/Error Message controls and `data-*` attributes. |
| 2 | **Critical** | Form widget had no `get_script_depends()` → `content-widgets.js` never loaded on frontend → submit handler never attached | Added `return [ 'bdea-content-script' ]` |
| 3 | **Bug** | `spacer.php` "Show helper text in editor only" rendered the `↔` helper on the frontend too | Only renders when `\Elementor\Plugin::$instance->editor->is_edit_mode()` |
| 4 | **Bug** | Plugin registered a CDN `swiper` handle (v11) that Elementor's own `swiper` handle (local v8, registered later) always overwrote — dead code, silently depending on Elementor's copy | Removed the dead CDN registration; widget deps on `'swiper'` now cleanly resolve to Elementor's handle (verified working) |
| 5 | **Bug** | Facebook SDK printed per widget; 2+ different FB widgets on one page loaded the SDK twice (each class had its own static guard) | Shared `bdea_maybe_print_fb_sdk()` helper in `helpers.php`; defers a single SDK print to `wp_footer` (also survives Elementor's double render pass, which previously consumed the print-once flag into a discarded buffer) |
| 6 | **Bug** | JS used `form.action`, which returns the named `<input name="action">` element (404 on submit) | Use `form.getAttribute('action')` |
| 7 | **Minor** | CTA `$bg_style` flagged unescaped — verified safe (only `esc_url` output inside literals); left as-is | — |

All changed files pass `php -l`; JS passes `node --check`.

## 3. Verified Working (browser, live site)

- **Loop Grid**: renders template 691, 24 elementor elements, correct display conditions (hidden with `include: 404`, shown with `custom_url: /widget-qa-test`, wildcard `/widget-*` matches)
- **Loop Carousel**: template 892, 8 slides, Swiper initialized, arrows + 8 pagination dots
- **Form**: end-to-end submit → nonce verify → email built → `wp_mail` → success message shown, form resets; invalid nonce rejected with proper JSON error
- **Spacer**: helper visible in editor, absent on frontend
- **FB Page + FB Comments together**: exactly one SDK script + one `#fb-root` on the page
- **Swiper**: `window.Swiper` available, carousels initialize with Elementor's bundled swiper
- **Display conditions** (`ConditionManager`): exact + wildcard custom URL matching (relative-path fix previously applied)

## 4. Console errors on test pages (pre-existing, NOT from this plugin)

CORS-blocked Google Fonts (site fonts URL to `batchdata.io` while testing on localhost), RudderStack SDK config 400, Meta pixel unavailable, missing `hello-elementor/js/news-grid.js`, OTTO/Debug scripts. No errors originate from ElementStack assets.

## 5. Biggest Feature Gaps vs Elementor Pro (top 10)

1. **data-table** — weakest: no sorting, search, pagination, or per-column emphasis (Pro Table)
2. **loop-grid / posts / archive-posts / portfolio** — loop-grid/post widgets render content from a selected Loop Template (verified: template mode takes over at `loop-grid.php:534`, built-in card is only a fallback). Content-level features (meta, link-whole-card, typography) intentionally live inside the template — by design, not a gap. The only genuine widget-level gap is **pagination / load-more / infinite scroll**. archive-posts/portfolio are classic widgets (not template-based) and do miss pagination, lightbox, hover overlay, AJAX filter.
3. **slides** — bare shell: no autoplay/arrows/speed/content-position controls (works, but far below Pro Slides)
4. **media-carousel / testimonial-carousel** — no slides-per-view, loop, pause-on-hover, thumbnails
5. **carousel / image-carousel** — no pause-on-hover, transition effects (coverflow/fade), center mode
6. **loop-carousel** — no pause-on-hover, effects, center mode
7. **sitemap** — no depth/exclude/nesting controls
8. **video-playlist** — no autoplay/loop/per-item thumbnails/modal
9. **login** — no lost-password link, redirect-after-login, label customization
10. **taxonomy-filter / portfolio** — page-reload filtering only, no AJAX

Full per-widget table: see section 7.

## 6. Feature gaps by widget — full table

| Widget | Pro counterpart | Key missing features |
|---|---|---|
| progress-bar | Progress Tracker | label prefix/suffix, animation toggle |
| data-table | Table | sorting, search, pagination, zebra rows, column emphasis |
| carousel | Carousel | pause-on-hover, transition effects, center mode |
| feature-comparison-table | Feature Comparison Table | per-row links, highlighted column, mobile scroll hint |
| share-it | Share Buttons | share counts, more networks, per-network URLs |
| loop-grid | Loop Grid | pagination/load-more added Aug 2026 (numbers, prev-next, load more, infinite scroll); content designed in loop template — by design |
| loop-carousel | Loop Carousel | slides-per-view, loop, pause-on-hover (container-level); content in loop template |
| off-canvas | (none) | open/close animation controls, auto-open, scroll-lock |
| posts | Posts | pagination/load-more, responsive columns, meta toggles, title tag |
| portfolio | Portfolio | pagination, lightbox, hover overlay, AJAX filter, read-more |
| video | Video | poster for yt/vimeo, lazy-load, lightbox, start/end time |
| button | Button | hover animation types, icon spacing |
| star-rating | Star Rating | alignment, custom icon, label |
| divider | Divider | center content (icon/text), gap control |
| google-maps | Google Maps | minor only |
| icon | Icon | minor only |
| icon-box | Icon Box | stacked/framed view, hover animation, whole-box link |
| image-box | Image Box | description field, text position, hover effects |
| basic-gallery | Basic Gallery | lightbox, masonry, hover overlay |
| image-carousel | Image Carousel | pause-on-hover, effects, lightbox, center mode |
| icon-list | Icon List | icon/text spacing, icon alignment |
| counter | Counter | animation duration/easing, thousand separator |
| spacer | Spacer | minor only |
| testimonial | Testimonial | avatar, layout option, quote link |
| tabs | Tabs | tab position, icon position, vertical view, title tag |
| accordion | Accordion | multiple-open mode, icon customization, title tag |
| social-icons | Social Icons | extra networks, shape option, ordering |
| soundcloud | SoundCloud | track color, custom height |
| shortcode | Shortcode | minor only |
| login | Form Login | lost-password, redirect-after-login, label customization |
| slides | Slider | autoplay/arrows/speed controls, ken-burns, content position |
| media-carousel | Media Carousel | slides-per-view, loop, pause-on-hover, thumbnails |
| testimonial-carousel | Testimonial Carousel | slides-per-view, loop, pause-on-hover, speed |
| reviews | Reviews | star field per review, link, slider mode |
| table-of-content | Table of Contents | numbering, exclude selector, marker style |
| facebook-* (4) | FB widgets | minor only |
| template | Template | minor only |
| lottie-widget | Lottie | renderer option, trigger, hover speed |
| video-playlist | Video Playlist | autoplay/loop, per-item thumbs, modal |
| progress-tracker | Progress Tracker | multiple skill bars, per-bar styling |
| menu | Menu | mobile hamburger, item icons, indicators |
| taxonomy-filter | Taxonomy Filter | AJAX filtering, multiple taxonomies |
| site-logo/site-title/page-title | Site widgets | minor only |
| wp-menu | WordPress Menu | minor only |
| sitemap | Sitemap | depth, exclude, nesting, title tag |
| post-title/excerpt/featured-image/post-content | Post widgets | minor only |
| author-box | Author Box | minor only |
| post-comments | Post Comments | minor only |
| post-navigation | Post Navigation | same-term filter, prev/next thumbnails |
| post-info | Post Info | per-item icons, avatar, custom taxonomy terms |
| search | Search | skins, icon-only button, size controls |
| breadcrumbs | Breadcrumbs | custom taxonomy trails, prefix, last-item toggle |
| archive-title | Archive Title | minor only |
| archive-posts | Archive Posts | responsive columns, read-more, title tag, hover effects |

## 7. Recommendations

1. **Form widget** is the only functional bug — now fixed and verified end-to-end. Worth re-testing on the live (non-localhost) site, since `wp_mail` delivery depends on server mail config (SMTP plugin is present).
2. **Highest-value Pro-parity work**: data-table (sort/search), pagination for loop-grid/posts/portfolio, slides controls, slides-per-view for the two carousels.
3. When editing `_elementor_data` by hand (or adding widget code), remember the **element cache**: delete `_elementor_element_cache` + `_elementor_css` post meta and clear `wp-content/cache/wp-fastlayer/` or the changes stay invisible for up to 24h.
4. Console noise (fonts CORS, RudderStack, Meta pixel) is site config, not plugin issues.
5. QA-CHECKLIST.md still lists "29 widgets" — count should read 74.

## 8. Post-audit feature work (Aug 2026): Loop Grid pagination

Elementor Pro–style pagination added to **Loop Grid** (`widgets/loop-grid.php`), verified live on http://localhost/batchdata/loop-qa-test/:

- **Pagination Type control**: None / Numbers / Previous-Next / Load on Demand (Load More) / Infinite Scroll, plus Load More/Prev/Next text controls and a full Pagination style section (align, colors, active/hover, radius, gap).
- **Numbers / Prev-Next**: pure server-side links with a per-widget URL param (`?bdea_page_{widget_id}=N`), so multiple loop grids on one page paginate independently. Current page is marked `aria-current`, Prev/Next hidden on first/last page.
- **Load More / Infinite Scroll**: AJAX via `wp_ajax_(nopriv_)bdea_loop_load` → `bdea_handle_loop_load()` (nonce-checked, sanitized settings, offset-aware paging). Widget settings travel as JSON in `data-bdea-settings`; shared `bdea_render_loop_items()` in `widgets/helpers.php` renders items identically in widget and AJAX contexts, including the **loop-template mode** (verified: template 691 renders correctly via AJAX).
- Offset + paged handled correctly (`offset + (page-1) * per_page`, since WP_Query ignores `paged` when `offset` is set).
- Files touched: `widgets/loop-grid.php` (controls + render + `get_script_depends()` added), `widgets/helpers.php` (`no_found_rows` now keyed on `pagination_type`; `bdea_render_loop_items()` added), `custom-elementor-addons.php` (`bdea_handle_loop_load()`; `require_once widgets/helpers.php` inside handler because the widgets loader never runs on admin-ajax), `assets/js/content-widgets.js` (`initLoopPagination`), `assets/css/content-widgets.css` (pagination styles).

**Bugs caught during verification** (all fixed):
1. Loop Grid had no `get_script_depends()` → `content-widgets.js` never loaded on loop-only pages (same class of bug as the form widget).
2. AJAX 500: `bdea_widget_query_args`/`bdea_render_loop_items` live in `widgets/helpers.php`, which is only required by the `elementor/widgets/register` hook — never fires on admin-ajax. Fixed by requiring helpers inside the handler.
3. `pagination_type` missing from AJAX settings → `no_found_rows=true` → `max_num_pages=1` → Load More stopped after page 2.
4. Load More button stayed `disabled` after success (re-enable was missing in the final promise step).

Test evidence: 157 pages / 469 posts; Numbers → page 2 correct posts + `current` highlight; Prev-Next → independent per-widget params; Load More card mode 3→6→9 cards, no duplicates; Load More template mode 12→24 template elements; Infinite Scroll auto-loads on scroll; `has_more:true` on page 2.

## 9. Post-audit feature work (Aug 2026): Create Loop Template flow

Elementor Pro docs say the workflow starts from the loop grid: pick a template — if none exists, *create one*. Our Loop Grid now offers that flow from the widget itself:

- **Editor-only prompt**: when a Loop Grid has no template selected, the editor canvas shows a "Create Template" prompt (frontend visitors are unaffected — the card fallback still renders).
- **AJAX endpoint** `wp_ajax_bdea_create_loop_template` → `bdea_create_loop_template()`: nonce-checked (`bdea_editor`), `edit_posts` capability required; creates an `elementor_library` post titled `Loop Template YYYY-MM-DD`, sets `_elementor_template_type` to `loop-item` (when Elementor Pro is active — our widget then renders like a Pro Loop Grid template) and returns its Elementor edit URL.
- **JS** (`initCreateLoopTemplate`): click → POST → redirect the whole editor tab (`window.top.location.href`, cross-origin-guarded) to the new template's editor, exactly like Pro's "Edit template" hop.
- **Bug caught during verification**: `// line comments` in `assets/js/content-widgets.js` and `share-it.js` silently killed the whole minified bundle — FastLayer's minifier joins all lines into one and keeps comments, so a `//` comment commented out the rest of the file (click handler never attached, no console error naming our file). Replaced with `/* block comments */`. Verify after any JS change by `node --check` on the file + clearing both FastLayer cache dirs.
- Verified live: click in editor created template 24048 (`loop-item`), top tab navigated to its Elementor editor; frontend pagination regression-tested after the JS fix (Load More template mode 3→6→9 items, button re-enables, `data-bdea-page` advances).
- Files touched: `custom-elementor-addons.php` (endpoint), `widgets/loop-grid.php` (editor-only prompt in render), `assets/js/content-widgets.js` (`initCreateLoopTemplate` + boot refactor), `assets/css/content-widgets.css` (prompt styles).
