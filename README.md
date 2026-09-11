# ElementsKey Addons for Elementor

[![WordPress](https://img.shields.io/badge/WordPress-6.0%2B-blueviolet)](https://wordpress.org/)
[![Elementor](https://img.shields.io/badge/Elementor-4.x-blue)](https://elementor.com/)
[![PHP](https://img.shields.io/badge/PHP-7.4%2B-8892BF)](https://www.php.net/)
[![License](https://img.shields.io/badge/License-GPLv2_or_later-green)](https://www.gnu.org/licenses/gpl-2.0.html)

Supercharge Elementor with **73 lightweight widgets**, a full **Theme Builder** for custom headers, footers and archives, **Elementor Pro-style display conditions**, and **Custom CSS controls** at every level — all in one drop-in plugin.

---

## Features

- **73+ lightweight, fast widgets** — no bloat, each widget loads its own assets.
- **Theme Builder** — create custom headers, footers, and archive templates with advanced display conditions, template ordering, duplicates, export/import and cached rendering.
- **Display Conditions** — Pro-style per-widget show/hide rules across every widget (even Elementor core widgets).
- **Custom CSS module** — add custom CSS at page, section, container, and widget level.
- **Widget manager** — enable/disable individual widgets and modules from one settings screen.
- **Design styles everywhere** — every widget ships with full destination-style controls: colors, typography, spacing, borders, hover states, breakpoints and more.
- **Mobile-first navigation widgets** — Menu and WordPress Menu widgets with a dropdown system, caret indicators, hamburger toggle and per-breakpoint behavior.

## Requirements

- WordPress **6.0+**
- [Elementor](https://elementor.com/) **3.x / 4.x** (free version works; widgets appear in the **ElementsKey Elements** category)
- PHP **7.4+**

## Installation

### From the repository

1. Download the plugin ZIP or clone the repository:

   ```bash
   git clone https://github.com/kanhajatthap/ElementsKey-Elementor-Addons.git
   ```

2. Copy the `elementskey` folder to `/wp-content/plugins/`.
3. Go to **Plugins → Installed Plugins** and activate **ElementsKey Addons for Elementor**.
4. Go to **ElementsKey** in the WP Admin menu to configure which widgets are enabled.
5. Open a page in the Elementor editor — widgets appear under the **ElementsKey Elements** category.

> If Elementor is not installed, an admin notice will prompt you to install and activate it first.

## Included Widgets

All widgets are registered under the **ElementsKey Elements** category in the Elementor editor.

### General & Content

| Widget | Description |
| --- | --- |
| Icon | Single icon with link, alignment, size, color and background controls. |
| Icon Box | Icon with title, description and link in top or left layouts. |
| Icon List | Bullet list with custom icons, links, alignment and styling. |
| Image Box | Image with title, description, link and full image style controls. |
| Basic Gallery | Responsive image gallery grid with captions, links and column controls. |
| Image Carousel | Swiper-powered image carousel with captions, links, arrows and dots. |
| Button | Stylable button/CTA with icon, link options and full hover states. |
| Divider | Decorative divider with style, width, alignment and color controls. |
| Google Maps | Embed Google Maps by address with zoom, height and width controls. |
| Spacer | Responsive vertical spacing helper with px or vh heights. |
| Star Rating | Display ratings with adjustable value, size and colors. |
| Counter | Animated number counter with prefix, suffix, duration and title. |
| Progress Bar | Animated, customizable progress bars with label and percentage display. |
| Progress Tracker | Animated linear progress bar with label and percentage. |
| Countdown | Animated countdown timer to a target date and time. |
| Blockquote | Styled quote block with author name and role. |
| Testimonial | Quote card with author, role, avatar, rating and full style controls. |
| Reviews | Responsive review cards grid with stars, avatars and ratings. |
| Tabs | Tabbed content with titles, WYSIWYG panes and top or left layouts. |
| Accordion | Collapsible content items with WYSIWYG panes and full styling. |
| Price List | List of priced items with badges, descriptions and featured state. |
| Price Table | Pricing table with features, price, period and CTA button. |
| Flip Box | 3D flip card with front/back content, icon and button. |
| Call to Action | Banner CTA with background image, overlay, text and buttons. |
| Animated Headline | Headline with rotating words, animation speed and styling. |
| Hotspot | Image with positioned tooltip hotspots and descriptions. |
| Code Highlight | Code block with language, copy button and styling. |
| Lottie | Render Lottie JSON animations with loop, speed and size controls. |
| Social Icons | Social network icons with links, colors, sizes and hover states. |
| Share It | Share buttons for the current page with network toggles and copy-link support. |
| Shortcode | Render any WordPress shortcode inside Elementor. |
| Video | Embed YouTube, Vimeo or self-hosted videos with responsive player. |
| Video Playlist | Video player with a clickable playlist of YouTube/Vimeo items. |
| SoundCloud | Embed SoundCloud tracks with visual player and autoplay options. |
| Slides | Full-width Swiper slides with background image, content and buttons. |
| Carousel | Swiper-powered responsive carousel with slides, arrows and dots. |
| Media Carousel | Swiper carousel for images with captions, dots and arrows. |
| Testimonial Carousel | Swiper carousel of testimonials with avatars and ratings. |
| Data Table | Label-value rows with a titled card, alternating backgrounds and custom colors. |
| Feature Comparison Table | Two-column feature vs checkmark comparison table with styled header. |
| Table of Content | Auto-generated table of contents from page headings. |
| Search | Search form with styled input and button. |
| Breadcrumbs | Breadcrumb trail with separators for pages and archives. |
| Template | Embed any Elementor template inside another page. |
| Off-Canvas | Slide-in panel with toggle button, overlay, close controls and template support. |
| Link in Bio | Centered avatar, bio and vertical link buttons layout. |

### Posts, Loops & Archives

| Widget | Description |
| --- | --- |
| Posts | Classic posts listing with thumbnail, meta, excerpt and pagination. |
| Loop Grid | Responsive post grid with full query controls (post type, categories, tags, order). |
| Loop Carousel | Swiper-powered carousel that pulls posts dynamically from any post type. |
| Portfolio | Filterable portfolio grid with category filter buttons. |
| Taxonomy Filter | Term filter buttons or dropdown for any taxonomy. |
| Archive Posts | Grid listing of posts with pagination for archive contexts. |
| Archive Title | Display the current archive title. |

### Forms & Login

| Widget | Description |
| --- | --- |
| Form | Custom contact form with configurable fields and submit button. |
| Login | Login form for logged-out users with logout option for logged-in users. |

### Facebook

| Widget | Description |
| --- | --- |
| Facebook Button | Facebook Like/Recommend button with layouts and SDK. |
| Facebook Comments | Facebook comments plugin embedded with SDK. |
| Facebook Embed | Embed a single Facebook post or video. |
| Facebook Page | Embed a Facebook page plugin box. |

### Theme, Navigation & Single Post

| Widget | Description |
| --- | --- |
| Menu | Display a WordPress navigation menu horizontally or vertically with Pro-style controls, dropdowns and a mobile hamburger toggle. |
| WordPress Menu | WordPress menu by menu or theme location with design style presets (default, pills, underline, vertical). |
| Sitemap | List published posts of selected post types grouped by type. |
| Site Logo | Show the site custom logo with fallback to site name. |
| Site Title | Show the site title linked to the homepage. |
| Page Title | Display the current page title. |
| Post Title | Display the current post title with link option. |
| Post Excerpt | Display the current post excerpt. |
| Featured Image | Display the current post featured image with link option. |
| Post Content | Render the full content of the current post. |
| Author Box | Author card with avatar, name, bio and website. |
| Post Comments | WordPress comments list and comment form. |
| Post Navigation | Previous/next post navigation links. |
| Post Info | Post meta row: author, date, categories, tags and comments. |

## Theme Builder

The **Builder** module (Admin → ElementsKey → Builder) lets you design templates that replace or extend parts of your theme:

- Custom **Headers** and **Footers**
- **Archive** templates for any post type or taxonomy
- **Entire site / front page / home / search / 404 / custom URL** conditions
- Advanced conditions for **Singular** (post types), **Archives** (taxonomies) and **User roles** (matching WooCommerce conditions too)
- Template **ordering**, **duplicate**, **export / import** and **bulk actions**
- Cached frontend rendering with automatic cache invalidation on save/trash

Templates are edited directly in the Elementor editor (a `builder` custom post type).

> Automatic migration from the older `elementskey_header_footer` post type is handled by `tools/migrate-header-footer.php`.

## Display Conditions (pro-style)

Every Elementor widget — including core Elementor widgets — gets a **Display Conditions** section in the **Advanced** tab. Set visibility rules (entire site, front page, singular post types, archives, taxonomies, user roles, custom URL, 404, search, etc.) and the widget is hidden on the frontend when the rules don't match.

## Custom CSS Module

Add custom CSS at four levels, each scoped to the target element:

- **Page / Document** — saved as post meta, output on the document
- **Section** — Advanced tab
- **Container** — Advanced tab
- **Widget** — Advanced tab

## Widget Manager

From **Admin → ElementsKey → Settings** you can:

- Toggle each of the 73 widgets on/off (disabled widgets disappear from the editor assets and frontend)
- Toggle the Theme Builder module
- Regenerate/reset the demo pages

## Development

```bash
# Lint a single widget
php -l widgets/menu.php

# PHPCS with WordPress coding standards (vendor included)
vendor/bin/phpcs --standard=WordPress widgets/

# Quick JS sanity check
node --check assets/js/content-widgets.js
```

### Project structure

```
elementskey/
├── assets/                 # CSS & JS (widgets, admin, builder)
├── Framework/              # Renderer, conditions, caches, widget conditions
├── modules/
│   ├── builder/            # Theme Builder (post type, admin, meta boxes, frontend)
│   └── custom-css/         # Custom CSS at page/section/container/widget level
├── tools/                  # Migration & development utilities
├── widgets/                # All 73 widget classes
└── elementskey-elementor-addons.php  # Main plugin file
```

## Changelog

### 1.1.0
- Rebuilt **Menu** widget with Pro-style controls (layout, alignment, items, active item, dropdown, separator, caret, toggle button)
- Redesigned **WordPress Menu** widget with design presets and per-breakpoint mobile behavior
- Fixed Share It icon rendering
- Fix "Build all templates" and template listing in the Builder module

### 1.0.0
- Initial release: 73 widgets, Theme Builder, Display Conditions, Custom CSS

## License

GPLv2 or later — see the [GNU GPL v2](https://www.gnu.org/licenses/gpl-2.0.html).

## Credits

Developed by [Kanha Jatthap](https://github.com/kanhajatthap).