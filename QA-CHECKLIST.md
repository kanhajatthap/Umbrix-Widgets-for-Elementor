# ElementStack Addons — QA Checklist

Covers all plugin areas. Test in editor **and** on frontend (logged-in and incognito).

## Settings (Admin)

- [ ] Widget Manager page lists all 74 widgets with correct names, slugs, descriptions
- [ ] New widgets (Loop Grid, Loop Carousel, Off-Canvas, Posts, Portfolio, Video, Button, Star Rating, Divider, Google Maps, Icon, Icon Box, Image Box, Basic Gallery, Image Carousel, Icon List, Counter, Spacer, Testimonial, Tabs, Accordion, Social Icons, SoundCloud, Shortcode) toggle ON/OFF and change persists after save
- [ ] Disabling a widget removes it from the Elementor panel; re-enabling restores it
- [ ] Header/Footer module toggle works independently of widget toggles
- [ ] Settings save keeps other options intact (no overwrite/blanking)

## Theme Builder

### Header / Footer / Single / 404 / Archive
- [ ] 5 template types listed: Header, Footer, Single, 404, Archive
- [ ] "Assign" sets template as active; active template has badge, inactive has no badge
- [ ] Only one template per type is active
- [ ] Create modal creates Elementor template of correct type
- [ ] New template appears in "Your templates" with pending/inactive state
- [ ] Conditions UI saves conditions; saved conditions reflected on template cards
- [ ] Delete removes template assignment and card

### Conditions
- [ ] Single: applies to the assigned post only, not to other posts
- [ ] 404: shows on non-existent URLs only, not on real pages
- [ ] Archive: `archive:all`, `archive:taxonomy:*` (all categories/tags), `archive:taxonomy:*:term:*` (single term) all match correctly
- [ ] Archive template does NOT leak onto single posts or the homepage
- [ ] Date archives render the archive template
- [ ] Homepage (posts page) does not get archive template unless assigned

### Frontend rendering
- [ ] Header renders once at top of page, Footer once at bottom
- [ ] Single template overrides the post content, not the whole page layout
- [ ] 404 template shows instead of default 404
- [ ] Archive template shows post grid/loop over archive pages
- [ ] Templates missing (trashed) fall back to defaults without fatal errors

## Widgets (all in editor + frontend)

### Loop Grid
- [ ] Appears in "ElementStack Elements" category with icon
- [ ] Query: post type, categories, tags, exclude IDs, count, orderby (date/title/rand), order
- [ ] Responsive columns (desktop/tablet/mobile) and gaps
- [ ] Card toggles: thumbnail, title, excerpt, meta, read more
- [ ] Styling: card background/border/radius/shadow/padding, image height, typography colors
- [ ] Thumbnail missing on a post → card still renders cleanly without broken image
- [ ] No posts match query → "No posts found" message, no PHP errors

### Loop Carousel
- [ ] Slides render from query; arrows and dots appear when enabled
- [ ] Autoplay runs, stops on hover (when enabled); loop wraps around
- [ ] Slides per view responsive (desktop 3 / tablet 2 / mobile 1)
- [ ] Arrows/dots styled via Navigation section; equal height cards
- [ ] Swiper initializes on frontend (no duplicate init after Elementor re-renders)
- [ ] Carousel works in Elementor preview mode too

### Off-Canvas
- [ ] Trigger button opens panel from all 4 positions (left/right/top/bottom)
- [ ] Overlay click closes panel (when enabled), ESC closes (when enabled)
- [ ] Width/height controls apply responsively
- [ ] Panel title, WYSIWYG content, links render
- [ ] Body scroll locked while panel open, restored on close
- [ ] Close button and panel styles (bg, padding, overlay color) apply
- [ ] Hidden in Elementor editor (does not cover the editing UI)

### Posts
- [ ] Grid and List layouts both render
- [ ] Responsive columns for grid; list collapses to column on mobile
- [ ] Thumbnail size selector works (thumbnail → full)
- [ ] Meta/title/excerpt/read more toggles; excerpt length applies
- [ ] Card styles: bg, border, radius, shadow, padding, image height/fit
- [ ] Title/excerpt/meta/read-more typography + colors

### Portfolio
- [ ] Filter bar shows "All" + terms; clicking a filter hides non-matching cards
- [ ] Active filter button is highlighted; clicking "All" restores everything
- [ ] Filter taxonomy selector works (category + custom taxonomies)
- [ ] Cards carry correct term data; items with no terms only show under "All"
- [ ] Responsive columns, gaps, card/thumbnail/typography styles
- [ ] Filter works in Elementor preview mode and after re-render

### Video
- [ ] YouTube, Vimeo and self-hosted sources all render
- [ ] Aspect ratio classes (16:9, 4:3, 3:2, custom) apply correctly
- [ ] Radius/shadow/alignment style controls apply
- [ ] Invalid URL shows friendly error message, no broken iframe
- [ ] Autoplay/mute/loop/controls toggles translate into iframe/video attributes

### Button
- [ ] Text, link, external/nofollow attributes render on the <a>
- [ ] Sizes (sm/md/lg) and full-width work; icon before/after positions
- [ ] Hover anims (grow/fade) apply; colors, bg, radius, padding, alignment controls work

### Star Rating
- [ ] Value renders on 5 and 10 scales; number and label display
- [ ] Filled, half and empty stars all display with correct colors
- [ ] Half-star shows exactly half filled (left half gold, right half gray)
- [ ] Star size and spacing controls apply

### Divider
- [ ] solid/dashed/dotted/double styles render; color, gap controls work
- [ ] Responsive width/alignment where applicable

### Google Maps
- [ ] Embed loads by address; zoom, height, width controls apply
- [ ] Prevent-scroll attribute present; iframe fills embed area on mobile

### Icon
- [ ] Icon library picker works; align, size, rotate, color, hover color, bg controls apply
- [ ] Link wraps icon as <a> with target/nofollow when provided

### Icon Box
- [ ] Top and left positions render correctly; icon color/bg/size/padding/radius apply
- [ ] Title/desc typography + colors; "Learn More" link renders when set

### Image Box
- [ ] Image renders at chosen size; width/height/radius/shadow/spacing controls apply
- [ ] Title/desc typography + colors; link renders when set

### Basic Gallery
- [ ] Columns 1–6 render; responsive (desktop/tablet/mobile) column counts
- [ ] Captions, links, gap, image height/fit/radius controls apply

### Image Carousel
- [ ] Swiper initializes on frontend (same init path as Carousel/Loop Carousel)
- [ ] Slides per view responsive; arrows/dots show when enabled; autoplay + loop work
- [ ] Captions and links on slides; nav styles via widget controls

### Icon List
- [ ] Items render with icons; links wrap items with target/nofollow
- [ ] Align, spacing, icon color/bg/size, text typography + color controls apply

### Counter
- [ ] Number animates on scroll into view and lands on target (prefix/suffix intact)
- [ ] Duration, align, number typography/color, title styles apply

### Spacer
- [ ] Height responds (px/vh) on desktop/tablet/mobile
- [ ] Editor-only striped helper shown in Elementor, invisible on frontend

### Testimonial
- [ ] Quote, author, role, avatar and rating render
- [ ] Card bg/border/radius/shadow/padding; quote mark + typography colors apply

### Tabs
- [ ] Clicking a tab shows its pane and hides others; active tab highlighted
- [ ] Top and left positions; nav align, tab colors/bg/padding/gap, pane border/radius/padding

### Accordion
- [ ] Clicking a header expands its content and collapses others (single-open)
- [ ] Keyboard (Enter/Space) toggles; default-open item works
- [ ] Header/content typography, colors, padding, radius apply

### Social Icons
- [ ] Icons render with links (target/_blank + rel); align, size, colors, hover, radius, padding, gap
- [ ] Icons without a link render as span, not a broken <a>

### SoundCloud
- [ ] Track embed iframe loads; visual player toggle changes height/format
- [ ] Height, align, max-width, radius controls; invalid URL shows error message

### Shortcode
- [ ] Arbitrary shortcode renders (e.g. [gallery]); empty field renders nothing
- [ ] Alignment control applies

## Cross-widget / regression

- [ ] Existing widgets (Progress Bar, Data Table, Carousel, Feature Comparison, Share It) unaffected
- [ ] No PHP notices/warnings in debug.log during any test
- [ ] No JS errors on frontend or in Elementor editor console
- [ ] Widget Manager toggles for old widgets still function
- [ ] Pages with multiple of the same widget render each independently
