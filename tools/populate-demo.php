<?php
/**
 * Dev tool (gitignored): rebuilds the "Widget Showcase" page (Elementor data)
 * so every widget renders under its own name header with demo content.
 * Usage: tools\wp.cmd eval-file tools\populate-demo.php
 */

if ( ! defined( 'ABSPATH' ) ) {
    require_once dirname( __DIR__, 4 ) . '/wp-load.php';
}

if ( ! did_action( 'elementor/loaded' ) ) {
    die( 'Elementor not loaded.' );
}

$page = get_page_by_path( 'widgets' );
if ( ! $page ) {
    die( 'Page not found.' );
}

function _bdea_rand_id() {
    return substr( md5( uniqid( mt_rand(), true ) ), 0, 7 );
}

function _bdea_section( $widgets, $settings = [], $column_sizes = [] ) {
    $cols = [];
    if ( ! is_array( $widgets[0] ?? null ) ) {
        $widgets = [ $widgets ];
    }
    foreach ( $widgets as $col_widgets ) {
        $elements = [];
        foreach ( $col_widgets as $w ) {
            $elements[] = $w;
        }
        $cols[] = [
            'id'       => _bdea_rand_id(),
            'elType'   => 'column',
            'settings' => [ '_column_size' => 100 ],
            'elements' => $elements,
            'isInner'  => false,
        ];
    }
    if ( $column_sizes ) {
        foreach ( $cols as $i => &$col ) {
            $col['settings']['_column_size'] = (int) ( $column_sizes[ $i ] ?? 100 );
        }
    } else {
        $size = (int) ( 100 / count( $cols ) );
        foreach ( $cols as &$col ) {
            $col['settings']['_column_size'] = $size;
        }
    }
    unset( $col );
    return [
        'id'       => _bdea_rand_id(),
        'elType'   => 'section',
        'settings' => array_merge( [
            'layout'        => 'boxed',
            'content_width' => [ 'unit' => 'px', 'size' => 1140 ],
            'gap'           => 'extended',
        ], $settings ),
        'elements' => $cols,
        'isInner'  => false,
    ];
}

function _bdea_widget( $type, $settings = [] ) {
    return [
        'id'         => _bdea_rand_id(),
        'elType'     => 'widget',
        'widgetType' => $type,
        'settings'   => $settings,
        'elements'   => [],
        'isInner'    => false,
    ];
}

function _bdea_heading( $text, $tag = 'h2', $align = 'left' ) {
    return _bdea_widget( 'heading', [
        'title'       => $text,
        'header_size' => $tag,
        'align'       => $align,
        'title_color' => '#111827',
    ] );
}

function _bdea_showcase( $name, $slug, $col_widgets, $band ) {
    $bg   = ( $band % 2 ) ? '#f8fafc' : '#ffffff';
    $pad  = [ 'unit' => 'px', 'top' => '60', 'right' => '20', 'bottom' => '60', 'left' => '20', 'isLinked' => false ];
    $header = _bdea_section( [ [ _bdea_heading( $name ) ] ], [
        'background_background' => 'classic',
        'background_color'      => $bg,
        'padding'               => [ 'unit' => 'px', 'top' => '60', 'right' => '20', 'bottom' => '10', 'left' => '20', 'isLinked' => false ],
    ] );
    $body = _bdea_section( $col_widgets, [
        'background_background' => 'classic',
        'background_color'      => $bg,
        'padding'               => [ 'unit' => 'px', 'top' => '20', 'right' => '20', 'bottom' => '60', 'left' => '20', 'isLinked' => false ],
    ] );
    return [ $header, $body ];
}

$elements = [];

// ── HERO ──
$elements[] = _bdea_section(
    [
        [
            _bdea_heading( 'ElementStack Widget Showcase', 'h1', 'center' ),
            _bdea_widget( 'text-editor', [
                'editor' => "<p style='color:#cbd5e1;font-size:17px'>All 73 widgets — live demos with a name header above each widget.</p>",
                'align'  => 'center',
            ] ),
            _bdea_widget( 'bdea_button', [
                'button_text'  => 'Edit in Elementor',
                'button_link'  => [ 'url' => admin_url( 'post.php?post=' . $page->ID . '&action=elementor' ) ],
                'button_bg'    => '#ffffff',
                'button_color' => '#0f172a',
            ] ),
        ],
    ],
    [
        'background_background' => 'gradient',
        'background_color'      => '#0f172a',
        'background_color_b'    => '#4f46e5',
        'padding'               => [ 'unit' => 'px', 'top' => '90', 'right' => '20', 'bottom' => '90', 'left' => '20', 'isLinked' => false ],
    ]
);

$band = 0;

// ── BUTTON ──
array_push( $elements, ..._bdea_showcase( 'Button', 'bdea_button', [
    [ _bdea_widget( 'bdea_button', [ 'button_text' => 'Primary', 'button_bg' => '#4361ee', 'button_color' => '#ffffff' ] ) ],
    [ _bdea_widget( 'bdea_button', [ 'button_text' => 'Success', 'button_bg' => '#10b981', 'button_color' => '#ffffff' ] ) ],
    [ _bdea_widget( 'bdea_button', [ 'button_text' => 'Danger', 'button_bg' => '#ef4444', 'button_color' => '#ffffff' ] ) ],
], $band++ ) );

// ── ICON BOX ──
array_push( $elements, ..._bdea_showcase( 'Icon Box', 'bdea_icon_box', [
    [ _bdea_widget( 'bdea_icon_box', [ 'selected_icon' => [ 'value' => 'fas fa-rocket', 'library' => 'fa-solid' ], 'title' => 'Fast Performance', 'description' => 'Optimized for speed.', 'align' => 'center' ] ) ],
    [ _bdea_widget( 'bdea_icon_box', [ 'selected_icon' => [ 'value' => 'fas fa-shield-alt', 'library' => 'fa-solid' ], 'title' => 'Secure', 'description' => 'Built with security in mind.', 'align' => 'center' ] ) ],
    [ _bdea_widget( 'bdea_icon_box', [ 'selected_icon' => [ 'value' => 'fas fa-paint-brush', 'library' => 'fa-solid' ], 'title' => 'Customizable', 'description' => 'Full styling controls.', 'align' => 'center' ] ) ],
], $band++ ) );

// ── ICON ──
array_push( $elements, ..._bdea_showcase( 'Icon', 'bdea_icon', [
    [ _bdea_widget( 'bdea_icon', [ 'selected_icon' => [ 'value' => 'fas fa-star', 'library' => 'fa-solid' ] ] ) ],
    [ _bdea_widget( 'bdea_icon', [ 'selected_icon' => [ 'value' => 'fas fa-heart', 'library' => 'fa-solid' ] ] ) ],
    [ _bdea_widget( 'bdea_icon', [ 'selected_icon' => [ 'value' => 'fas fa-bolt', 'library' => 'fa-solid' ] ] ) ],
], $band++ ) );

// ── COUNTER ──
array_push( $elements, ..._bdea_showcase( 'Counter', 'bdea_counter', [
    [ _bdea_widget( 'bdea_counter', [ 'counter_number' => 2500, 'counter_suffix' => '+', 'counter_title' => 'Happy Customers', 'number_color' => '#4361ee' ] ) ],
    [ _bdea_widget( 'bdea_counter', [ 'counter_number' => 150, 'counter_suffix' => '+', 'counter_title' => 'Projects Done', 'number_color' => '#7209b7' ] ) ],
    [ _bdea_widget( 'bdea_counter', [ 'counter_number' => 99, 'counter_suffix' => '%', 'counter_title' => 'Satisfaction', 'number_color' => '#10b981' ] ) ],
], $band++ ) );

// ── STAR RATING ──
array_push( $elements, ..._bdea_showcase( 'Star Rating', 'bdea_star_rating', [
    [ _bdea_widget( 'bdea_star_rating', [ 'rating_value' => [ 'size' => 4.5 ], 'rating_scale' => '5', 'show_number' => 'yes', 'star_color' => '#f7b500' ] ) ],
], $band++ ) );

// ── PROGRESS BAR ──
array_push( $elements, ..._bdea_showcase( 'Progress Bar', 'bdea_progress_bar', [
    [ _bdea_widget( 'bdea_progress_bar', [ 'progress_bar_title' => 'HTML/CSS', 'progress_bar_percentage' => [ 'size' => 90 ], 'inner_content_color' => '#4361ee' ] ) ],
    [ _bdea_widget( 'bdea_progress_bar', [ 'progress_bar_title' => 'JavaScript', 'progress_bar_percentage' => [ 'size' => 75 ], 'inner_content_color' => '#7209b7' ] ) ],
    [ _bdea_widget( 'bdea_progress_bar', [ 'progress_bar_title' => 'PHP', 'progress_bar_percentage' => [ 'size' => 60 ], 'inner_content_color' => '#10b981' ] ) ],
], $band++ ) );

// ── PROGRESS TRACKER ──
array_push( $elements, ..._bdea_showcase( 'Progress Tracker', 'bdea_progress_tracker', [
    [ _bdea_widget( 'bdea_progress_tracker', [ 'tracker_type' => 'steps', 'tracker_title' => 'Order Progress' ] ) ],
], $band++ ) );

// ── DIVIDER ──
array_push( $elements, ..._bdea_showcase( 'Divider', 'bdea_divider', [
    [ _bdea_widget( 'bdea_divider', [ 'style' => 'solid', 'weight' => [ 'size' => 1, 'unit' => 'px' ], 'color' => '#4361ee', 'width' => [ 'size' => 50, 'unit' => '%' ], 'align' => 'center' ] ) ],
], $band++ ) );

// ── SPACER ──
array_push( $elements, ..._bdea_showcase( 'Spacer', 'bdea_spacer', [
    [ _bdea_widget( 'bdea_spacer', [ 'space' => [ 'size' => 50, 'unit' => 'px' ] ] ) ],
], $band++ ) );

// ── BLOCKQUOTE ──
array_push( $elements, ..._bdea_showcase( 'Blockquote', 'bdea_blockquote', [
    [ _bdea_widget( 'bdea_blockquote', [
        'blockquote_content' => 'Design is not just what it looks like. Design is how it works.',
        'blockquote_author'  => 'Steve Jobs',
    ] ) ],
], $band++ ) );

// ── TESTIMONIAL ──
array_push( $elements, ..._bdea_showcase( 'Testimonial', 'bdea_testimonial', [
    [ _bdea_widget( 'bdea_testimonial', [
        'testimonial_content' => 'This plugin completely transformed how we build pages.',
        'testimonial_name'    => 'Sarah Johnson',
        'testimonial_job'     => 'CEO, TechCorp',
        'testimonial_image'   => [ 'url' => 'https://i.pravatar.cc/100?img=1' ],
    ] ) ],
    [ _bdea_widget( 'bdea_testimonial', [
        'testimonial_content' => 'Absolutely the best Elementor addon. Highly recommended!',
        'testimonial_name'    => 'Mike Rodriguez',
        'testimonial_job'     => 'Senior Developer',
        'testimonial_image'   => [ 'url' => 'https://i.pravatar.cc/100?img=2' ],
    ] ) ],
], $band++ ) );

// ── TESTIMONIAL CAROUSEL ──
array_push( $elements, ..._bdea_showcase( 'Testimonial Carousel', 'bdea_testimonial_carousel', [
    [ _bdea_widget( 'bdea_testimonial_carousel', [
        'testimonials' => [
            [ 'tc_name' => 'Sarah Johnson', 'tc_role' => 'CEO, TechCorp', 'tc_content' => 'This plugin completely transformed how we build pages.', 'tc_rating' => 5 ],
            [ 'tc_name' => 'Mike Rodriguez', 'tc_role' => 'Senior Developer', 'tc_content' => 'Absolutely the best Elementor addon. Highly recommended!', 'tc_rating' => 4 ],
            [ 'tc_name' => 'Anna Kim', 'tc_role' => 'Designer', 'tc_content' => 'Great variety of widgets and clean code.', 'tc_rating' => 5 ],
        ],
    ] ) ],
], $band++ ) );

// ── REVIEWS ──
array_push( $elements, ..._bdea_showcase( 'Reviews', 'bdea_reviews', [
    [ _bdea_widget( 'bdea_reviews', [
        'reviews_items' => [
            [ 'review_name' => 'Mike R.', 'review_job' => 'Developer', 'review_text' => 'Love this addon!', 'review_rating' => 5, 'review_image' => [ 'url' => 'https://i.pravatar.cc/60?img=3' ] ],
            [ 'review_name' => 'Anna K.', 'review_job' => 'Designer', 'review_text' => 'Great variety of widgets.', 'review_rating' => 4, 'review_image' => [ 'url' => 'https://i.pravatar.cc/60?img=4' ] ],
        ],
    ] ) ],
], $band++ ) );

// ── TABS ──
array_push( $elements, ..._bdea_showcase( 'Tabs', 'bdea_tabs', [
    [ _bdea_widget( 'bdea_tabs', [
        'tabs' => [
            [ 'tab_title' => 'Overview', 'tab_content' => 'This is a beautiful tab widget.' ],
            [ 'tab_title' => 'Features', 'tab_content' => 'Responsive layout with custom icons.' ],
            [ 'tab_title' => 'Reviews', 'tab_content' => 'Rated 5 stars by happy users!' ],
        ],
    ] ) ],
], $band++ ) );

// ── ACCORDION ──
array_push( $elements, ..._bdea_showcase( 'Accordion', 'bdea_accordion', [
    [ _bdea_widget( 'bdea_accordion', [
        'accordion' => [
            [ 'accordion_title' => 'What is ElementStack?', 'accordion_content' => 'A powerful Elementor addon with 73 widgets.' ],
            [ 'accordion_title' => 'Do I need Elementor Pro?', 'accordion_content' => 'No! It works with the free version.' ],
            [ 'accordion_title' => 'Can I use it on client sites?', 'accordion_content' => 'Yes, unlimited personal and client websites.' ],
        ],
    ] ) ],
], $band++ ) );

// ── DATA TABLE ──
array_push( $elements, ..._bdea_showcase( 'Data Table', 'bdea_data_table', [
    [ _bdea_widget( 'bdea_data_table', [
        'table_data' => [
            'header' => [ 'Widget', 'Category', 'Status' ],
            'rows'   => [
                [ 'Accordion', 'Content', 'Active' ],
                [ 'Counter', 'Dynamic', 'Active' ],
                [ 'Form', 'Interactive', 'Active' ],
            ],
        ],
    ] ) ],
], $band++ ) );

// ── FEATURE COMPARISON TABLE ──
array_push( $elements, ..._bdea_showcase( 'Feature Comparison Table', 'bdea_feature_comparison_table', [
    [ _bdea_widget( 'bdea_feature_comparison_table', [
        'features' => [
            [ 'feature_name' => '73 Widgets', 'free' => 'yes', 'pro' => 'yes' ],
            [ 'feature_name' => 'Header/Footer Builder', 'free' => 'yes', 'pro' => 'yes' ],
            [ 'feature_name' => 'Loop Templates', 'free' => '', 'pro' => 'yes' ],
        ],
    ] ) ],
], $band++ ) );

// ── PRICE TABLE ──
array_push( $elements, ..._bdea_showcase( 'Price Table', 'bdea_price_table', [
    [ _bdea_widget( 'bdea_price_table', [
        'plan_name' => 'Starter', 'price' => '$9', 'period' => '/mo',
        'features_list' => [
            [ 'text' => '5 Projects', 'icon' => 'fas fa-check', 'included' => 'yes' ],
            [ 'text' => '10GB Storage', 'icon' => 'fas fa-check', 'included' => 'yes' ],
        ],
        'button_text' => 'Choose Plan',
    ] ) ],
    [ _bdea_widget( 'bdea_price_table', [
        'plan_name' => 'Professional', 'price' => '$29', 'period' => '/mo',
        'features_list' => [
            [ 'text' => 'Unlimited Projects', 'icon' => 'fas fa-check', 'included' => 'yes' ],
            [ 'text' => '100GB Storage', 'icon' => 'fas fa-check', 'included' => 'yes' ],
            [ 'text' => 'Priority Support', 'icon' => 'fas fa-check', 'included' => 'yes' ],
        ],
        'button_text' => 'Choose Plan',
        'highlighted' => 'yes',
    ] ) ],
    [ _bdea_widget( 'bdea_price_table', [
        'plan_name' => 'Enterprise', 'price' => '$99', 'period' => '/mo',
        'features_list' => [
            [ 'text' => 'Everything in Pro', 'icon' => 'fas fa-check', 'included' => 'yes' ],
            [ 'text' => 'Unlimited Storage', 'icon' => 'fas fa-check', 'included' => 'yes' ],
        ],
        'button_text' => 'Contact Sales',
    ] ) ],
], $band++ ) );

// ── PRICE LIST ──
array_push( $elements, ..._bdea_showcase( 'Price List', 'bdea_price_list', [
    [ _bdea_widget( 'bdea_price_list', [
        'price_list' => [
            [ 'title' => 'Web Design', 'description' => 'Custom responsive website', 'price' => '$1,200' ],
            [ 'title' => 'SEO', 'description' => 'Full on-page SEO', 'price' => '$500' ],
            [ 'title' => 'Maintenance', 'description' => 'Updates and support', 'price' => '$99/mo' ],
        ],
    ] ) ],
], $band++ ) );

// ── FORM ──
array_push( $elements, ..._bdea_showcase( 'Form', 'bdea_form', [
    [ _bdea_widget( 'bdea_form', [
        'form_fields' => [
            [ 'type' => 'text', 'field_label' => 'Name', 'placeholder' => 'John Doe', 'required' => 'yes' ],
            [ 'type' => 'email', 'field_label' => 'Email', 'placeholder' => 'john@example.com', 'required' => 'yes' ],
            [ 'type' => 'textarea', 'field_label' => 'Message', 'placeholder' => 'Your message...' ],
        ],
        'submit_button_text' => 'Send Message',
    ] ) ],
], $band++ ) );

// ── LOGIN ──
array_push( $elements, ..._bdea_showcase( 'Login', 'bdea_login', [
    [ _bdea_widget( 'bdea_login', [
        'login_title'  => 'Welcome Back',
        'button_label' => 'Log In',
    ] ) ],
], $band++ ) );

// ── SHARE IT ──
array_push( $elements, ..._bdea_showcase( 'Share It', 'bdea_share_it', [
    [ _bdea_widget( 'bdea_share_it', [
        'share_networks' => [
            [ 'social_network' => 'facebook', 'link' => [ 'url' => '#' ] ],
            [ 'social_network' => 'twitter', 'link' => [ 'url' => '#' ] ],
            [ 'social_network' => 'linkedin', 'link' => [ 'url' => '#' ] ],
            [ 'social_network' => 'pinterest', 'link' => [ 'url' => '#' ] ],
            [ 'social_network' => 'whatsapp', 'link' => [ 'url' => '#' ] ],
        ],
    ] ) ],
], $band++ ) );

// ── SOCIAL ICONS ──
array_push( $elements, ..._bdea_showcase( 'Social Icons', 'bdea_social_icons', [
    [ _bdea_widget( 'bdea_social_icons', [
        'social_icon_list' => [
            [ 'social_icon' => 'fab fa-facebook-f', 'link' => [ 'url' => '#' ], 'social_color' => '#1877f2' ],
            [ 'social_icon' => 'fab fa-twitter', 'link' => [ 'url' => '#' ], 'social_color' => '#1da1f2' ],
            [ 'social_icon' => 'fab fa-instagram', 'link' => [ 'url' => '#' ], 'social_color' => '#e4405f' ],
            [ 'social_icon' => 'fab fa-linkedin-in', 'link' => [ 'url' => '#' ], 'social_color' => '#0077b5' ],
            [ 'social_icon' => 'fab fa-youtube', 'link' => [ 'url' => '#' ], 'social_color' => '#ff0000' ],
        ],
    ] ) ],
], $band++ ) );

// ── ICON LIST ──
array_push( $elements, ..._bdea_showcase( 'Icon List', 'bdea_icon_list', [
    [ _bdea_widget( 'bdea_icon_list', [
        'icon_list' => [
            [ 'text' => 'Easy to install', 'icon' => 'fas fa-check-circle' ],
            [ 'text' => '73 widgets', 'icon' => 'fas fa-check-circle' ],
            [ 'text' => 'Header/Footer builder', 'icon' => 'fas fa-check-circle' ],
            [ 'text' => 'Works with any theme', 'icon' => 'fas fa-check-circle' ],
        ],
    ] ) ],
], $band++ ) );

// ── TABLE OF CONTENT ──
array_push( $elements, ..._bdea_showcase( 'Table of Content', 'bdea_table_of_content', [
    [ _bdea_widget( 'bdea_table_of_content', [
        'toc_title'  => 'Table of Contents',
        'toc_heading' => [ 'h2', 'h3' ],
    ] ) ],
], $band++ ) );

// ── SEARCH ──
array_push( $elements, ..._bdea_showcase( 'Search', 'bdea_search', [
    [ _bdea_widget( 'bdea_search', [] ) ],
], $band++ ) );

// ── BREADCRUMBS ──
array_push( $elements, ..._bdea_showcase( 'Breadcrumbs', 'bdea_breadcrumbs', [
    [ _bdea_widget( 'bdea_breadcrumbs', [] ) ],
], $band++ ) );

// ── AUTHOR BOX ──
array_push( $elements, ..._bdea_showcase( 'Author Box', 'bdea_author_box', [
    [ _bdea_widget( 'bdea_author_box', [] ) ],
], $band++ ) );

// ── GOOGLE MAPS ──
array_push( $elements, ..._bdea_showcase( 'Google Maps', 'bdea_google_maps', [
    [ _bdea_widget( 'bdea_google_maps', [ 'map_address' => 'New York, USA', 'map_zoom' => 12 ] ) ],
], $band++ ) );

// ── SOUNDCLOUD ──
array_push( $elements, ..._bdea_showcase( 'SoundCloud', 'bdea_soundcloud', [
    [ _bdea_widget( 'bdea_soundcloud', [ 'soundcloud_url' => 'https://soundcloud.com/skrillex/scary-monsters-and-nice-sprites' ] ) ],
], $band++ ) );

// ── VIDEO ──
array_push( $elements, ..._bdea_showcase( 'Video', 'bdea_video', [
    [ _bdea_widget( 'bdea_video', [ 'video_type' => 'youtube', 'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ' ] ) ],
], $band++ ) );

// ── VIDEO PLAYLIST ──
array_push( $elements, ..._bdea_showcase( 'Video Playlist', 'bdea_video_playlist', [
    [ _bdea_widget( 'bdea_video_playlist', [
        'playlist' => [
            [ 'vp_title' => 'Intro Video', 'vp_source' => 'youtube', 'vp_url' => [ 'url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ' ] ],
            [ 'vp_title' => 'Getting Started', 'vp_source' => 'youtube', 'vp_url' => [ 'url' => 'https://www.youtube.com/watch?v=8aGhZQkoFbQ' ] ],
            [ 'vp_title' => 'Advanced Tips', 'vp_source' => 'youtube', 'vp_url' => [ 'url' => 'https://www.youtube.com/watch?v=kXYiU_JCYtU' ] ],
        ],
    ] ) ],
], $band++ ) );

// ── CAROUSEL ──
array_push( $elements, ..._bdea_showcase( 'Carousel', 'bdea_swiper_carousel', [
    [ _bdea_widget( 'bdea_swiper_carousel', [ 'layout_type' => 'card', 'slides_per_view' => 3 ] ) ],
], $band++ ) );

// ── IMAGE CAROUSEL ──
array_push( $elements, ..._bdea_showcase( 'Image Carousel', 'bdea_image_carousel', [
    [ _bdea_widget( 'bdea_image_carousel', [
        'carousel_images' => [
            [ 'carousel_image' => [ 'url' => 'https://picsum.photos/seed/c1/600/400' ] ],
            [ 'carousel_image' => [ 'url' => 'https://picsum.photos/seed/c2/600/400' ] ],
            [ 'carousel_image' => [ 'url' => 'https://picsum.photos/seed/c3/600/400' ] ],
        ],
        'slides_per_view' => 2,
    ] ) ],
], $band++ ) );

// ── MEDIA CAROUSEL ──
array_push( $elements, ..._bdea_showcase( 'Media Carousel', 'bdea_media_carousel', [
    [ _bdea_widget( 'bdea_media_carousel', [
        'media_items' => [
            [ 'mc_title' => 'Image One', 'mc_image' => [ 'url' => 'https://picsum.photos/seed/m1/600/400' ] ],
            [ 'mc_title' => 'Image Two', 'mc_image' => [ 'url' => 'https://picsum.photos/seed/m2/600/400' ] ],
            [ 'mc_title' => 'Image Three', 'mc_image' => [ 'url' => 'https://picsum.photos/seed/m3/600/400' ] ],
        ],
    ] ) ],
], $band++ ) );

// ── LOOP CAROUSEL ──
array_push( $elements, ..._bdea_showcase( 'Loop Carousel', 'bdea_loop_carousel', [
    [ _bdea_widget( 'bdea_loop_carousel', [] ) ],
], $band++ ) );

// ── LOOP GRID ──
array_push( $elements, ..._bdea_showcase( 'Loop Grid', 'bdea_loop_grid', [
    [ _bdea_widget( 'bdea_loop_grid', [] ) ],
], $band++ ) );

// ── POSTS ──
array_push( $elements, ..._bdea_showcase( 'Posts', 'bdea_posts', [
    [ _bdea_widget( 'bdea_posts', [ 'posts_per_page' => 3, 'columns' => 3 ] ) ],
], $band++ ) );

// ── PORTFOLIO ──
array_push( $elements, ..._bdea_showcase( 'Portfolio', 'bdea_portfolio', [
    [ _bdea_widget( 'bdea_portfolio', [] ) ],
], $band++ ) );

// ── ARCHIVE POSTS ──
array_push( $elements, ..._bdea_showcase( 'Archive Posts', 'bdea_archive_posts', [
    [ _bdea_widget( 'bdea_archive_posts', [ 'post_type' => 'post', 'posts_per_page' => 3, 'columns' => 3 ] ) ],
], $band++ ) );

// ── BASIC GALLERY ──
array_push( $elements, ..._bdea_showcase( 'Basic Gallery', 'bdea_basic_gallery', [
    [ _bdea_widget( 'bdea_basic_gallery', [
        'galleries' => [
            [ 'gallery_image' => [ 'url' => 'https://picsum.photos/seed/g1/400/300' ], 'gallery_caption' => 'Mountain View' ],
            [ 'gallery_image' => [ 'url' => 'https://picsum.photos/seed/g2/400/300' ], 'gallery_caption' => 'Ocean Sunset' ],
            [ 'gallery_image' => [ 'url' => 'https://picsum.photos/seed/g3/400/300' ], 'gallery_caption' => 'City Lights' ],
            [ 'gallery_image' => [ 'url' => 'https://picsum.photos/seed/g4/400/300' ], 'gallery_caption' => 'Forest Trail' ],
            [ 'gallery_image' => [ 'url' => 'https://picsum.photos/seed/g5/400/300' ], 'gallery_caption' => 'Desert Dunes' ],
            [ 'gallery_image' => [ 'url' => 'https://picsum.photos/seed/g6/400/300' ], 'gallery_caption' => 'Lake View' ],
        ],
        'columns' => 3,
    ] ) ],
], $band++ ) );

// ── HOTSPOT ──
array_push( $elements, ..._bdea_showcase( 'Hotspot', 'bdea_hotspot', [
    [ _bdea_widget( 'bdea_hotspot', [
        'hotspot_image' => [ 'url' => 'https://picsum.photos/seed/hotspot/600/400' ],
        'hotspot_markers' => [
            [ 'hotspot_left' => 30, 'hotspot_top' => 40, 'hotspot_label' => 'Feature A', 'hotspot_description' => 'Cloud sync enabled.' ],
            [ 'hotspot_left' => 70, 'hotspot_top' => 60, 'hotspot_label' => 'Feature B', 'hotspot_description' => 'Real-time analytics.' ],
        ],
    ] ) ],
], $band++ ) );

// ── IMAGE BOX ──
array_push( $elements, ..._bdea_showcase( 'Image Box', 'bdea_image_box', [
    [ _bdea_widget( 'bdea_image_box', [
        'image'       => [ 'url' => 'https://picsum.photos/seed/ibox1/400/200' ],
        'title'       => 'Creative Solutions',
        'description' => 'Innovative designs that stand out.',
    ] ) ],
    [ _bdea_widget( 'bdea_image_box', [
        'image'       => [ 'url' => 'https://picsum.photos/seed/ibox2/400/200' ],
        'title'       => 'Expert Team',
        'description' => 'Professional experience in every project.',
    ] ) ],
], $band++ ) );

// ── CALL TO ACTION ──
array_push( $elements, ..._bdea_showcase( 'Call to Action', 'bdea_call_to_action', [
    [ _bdea_widget( 'bdea_call_to_action', [
        'cta_title'       => 'Ready to Get Started?',
        'cta_description' => 'Join 10,000+ happy customers.',
        'cta_button_text' => 'Get Started Free',
        'cta_button_link' => [ 'url' => '#' ],
    ] ) ],
], $band++ ) );

// ── FLIP BOX ──
array_push( $elements, ..._bdea_showcase( 'Flip Box', 'bdea_flip_box', [
    [ _bdea_widget( 'bdea_flip_box', [
        'front_title'       => 'Hover Me!',
        'front_description' => 'Click to see the back',
        'back_title'        => 'Surprise!',
        'back_description'  => 'This is the back content.',
        'front_icon'        => [ 'value' => 'fas fa-sync-alt', 'library' => 'fa-solid' ],
    ] ) ],
], $band++ ) );

// ── SLIDES ──
array_push( $elements, ..._bdea_showcase( 'Slides', 'bdea_slides', [
    [ _bdea_widget( 'bdea_slides', [
        'slides' => [
            [ 'slide_title' => 'Welcome to ElementStack', 'slide_description' => '73 custom widgets', 'button_text' => 'Explore', 'button_link' => [ 'url' => '#' ] ],
        ],
    ] ) ],
], $band++ ) );

// ── ANIMATED HEADLINE ──
array_push( $elements, ..._bdea_showcase( 'Animated Headline', 'bdea_animated_headline', [
    [ _bdea_widget( 'bdea_animated_headline', [
        'animated_headline'  => 'We build',
        'headline_style'     => 'rotating',
        'rotating_text'      => [ 'websites', 'apps', 'stores', 'brands' ],
        'animated_text_color' => '#4361ee',
    ] ) ],
], $band++ ) );

// ── COUNTDOWN ──
array_push( $elements, ..._bdea_showcase( 'Countdown', 'bdea_countdown', [
    [ _bdea_widget( 'bdea_countdown', [
        'due_date'    => gmdate( 'Y-m-d\TH:i:s', strtotime( '+12 days' ) ),
        'show_labels' => 'yes',
    ] ) ],
], $band++ ) );

// ── CODE HIGHLIGHT ──
array_push( $elements, ..._bdea_showcase( 'Code Highlight', 'bdea_code_highlight', [
    [ _bdea_widget( 'bdea_code_highlight', [
        'code_language' => 'javascript',
        'code_content'  => 'const greeting = "Hello";\nconsole.log(greeting);',
        'show_copy_btn' => 'yes',
    ] ) ],
], $band++ ) );

// ── LOTTIE ──
array_push( $elements, ..._bdea_showcase( 'Lottie', 'bdea_lottie', [
    [ _bdea_widget( 'bdea_lottie', [ 'lottie_url' => [ 'url' => 'https://assets2.lottiefiles.com/packages/lf20_usmfx6bp.json' ] ] ) ],
], $band++ ) );

// ── OFF-CANVAS ──
array_push( $elements, ..._bdea_showcase( 'Off-Canvas', 'bdea_off_canvas', [
    [ _bdea_widget( 'bdea_off_canvas', [ 'offcanvas_title' => 'Menu', 'trigger_text' => 'Open Menu' ] ) ],
], $band++ ) );

// ── LINK IN BIO ──
array_push( $elements, ..._bdea_showcase( 'Link in Bio', 'bdea_link_in_bio', [
    [ _bdea_widget( 'bdea_link_in_bio', [
        'bio_avatar' => [ 'url' => 'https://i.pravatar.cc/100?img=5' ],
        'bio_name'   => '@brandname',
        'bio_handle' => 'Brand Name',
        'bio_text'   => 'Digital Creator & Storyteller',
        'bio_links'  => [
            [ 'bio_link_label' => 'Website', 'bio_link_url' => [ 'url' => '#' ] ],
            [ 'bio_link_label' => 'Blog', 'bio_link_url' => [ 'url' => '#' ] ],
            [ 'bio_link_label' => 'YouTube', 'bio_link_url' => [ 'url' => '#' ] ],
        ],
    ] ) ],
], $band++ ) );

// ── SITE TITLE ──
array_push( $elements, ..._bdea_showcase( 'Site Title', 'bdea_site_title', [
    [ _bdea_widget( 'bdea_site_title', [] ) ],
], $band++ ) );

// ── SITE LOGO ──
array_push( $elements, ..._bdea_showcase( 'Site Logo', 'bdea_site_logo', [
    [ _bdea_widget( 'bdea_site_logo', [ 'logo_width' => [ 'size' => 200, 'unit' => 'px' ], 'logo_align' => 'center' ] ) ],
], $band++ ) );

// ── PAGE TITLE ──
array_push( $elements, ..._bdea_showcase( 'Page Title', 'bdea_page_title', [
    [ _bdea_widget( 'bdea_page_title', [] ) ],
], $band++ ) );

// ── POST TITLE ──
array_push( $elements, ..._bdea_showcase( 'Post Title', 'bdea_post_title', [
    [ _bdea_widget( 'bdea_post_title', [] ) ],
], $band++ ) );

// ── POST EXCERPT ──
array_push( $elements, ..._bdea_showcase( 'Post Excerpt', 'bdea_post_excerpt', [
    [ _bdea_widget( 'bdea_post_excerpt', [ 'excerpt_length' => 20 ] ) ],
], $band++ ) );

// ── POST INFO ──
array_push( $elements, ..._bdea_showcase( 'Post Info', 'bdea_post_info', [
    [ _bdea_widget( 'bdea_post_info', [
        'post_info_items' => [
            [ 'selected_icon' => [ 'value' => 'fas fa-calendar', 'library' => 'fa-solid' ], 'type' => 'post_date' ],
            [ 'selected_icon' => [ 'value' => 'fas fa-user', 'library' => 'fa-solid' ], 'type' => 'author' ],
        ],
    ] ) ],
], $band++ ) );

// ── POST NAVIGATION ──
array_push( $elements, ..._bdea_showcase( 'Post Navigation', 'bdea_post_navigation', [
    [ _bdea_widget( 'bdea_post_navigation', [] ) ],
], $band++ ) );

// ── FEATURED IMAGE ──
array_push( $elements, ..._bdea_showcase( 'Featured Image', 'bdea_featured_image', [
    [ _bdea_widget( 'bdea_featured_image', [] ) ],
], $band++ ) );

// ── ARCHIVE TITLE ──
array_push( $elements, ..._bdea_showcase( 'Archive Title', 'bdea_archive_title', [
    [ _bdea_widget( 'bdea_archive_title', [] ) ],
], $band++ ) );

// ── POST COMMENTS ──
array_push( $elements, ..._bdea_showcase( 'Post Comments', 'bdea_post_comments', [
    [ _bdea_widget( 'bdea_post_comments', [] ) ],
], $band++ ) );

// ── POST CONTENT ──
array_push( $elements, ..._bdea_showcase( 'Post Content', 'bdea_post_content', [
    [ _bdea_widget( 'bdea_post_content', [] ) ],
], $band++ ) );

// ── WP MENU ──
array_push( $elements, ..._bdea_showcase( 'WP Menu', 'bdea_wp_menu', [
    [ _bdea_widget( 'bdea_wp_menu', [] ) ],
], $band++ ) );

// ── MENU ──
array_push( $elements, ..._bdea_showcase( 'Menu', 'bdea_menu', [
    [ _bdea_widget( 'bdea_menu', [] ) ],
], $band++ ) );

// ── SITEMAP ──
array_push( $elements, ..._bdea_showcase( 'Sitemap', 'bdea_sitemap', [
    [ _bdea_widget( 'bdea_sitemap', [] ) ],
], $band++ ) );

// ── TAXONOMY FILTER ──
array_push( $elements, ..._bdea_showcase( 'Taxonomy Filter', 'bdea_taxonomy_filter', [
    [ _bdea_widget( 'bdea_taxonomy_filter', [
        'filter_taxonomy' => 'category',
        'filter_all_label' => 'All',
    ] ) ],
], $band++ ) );

// ── FACEBOOK BUTTON ──
array_push( $elements, ..._bdea_showcase( 'Facebook Button', 'bdea_facebook_button', [
    [ _bdea_widget( 'bdea_facebook_button', [ 'fb_button_layout' => 'standard', 'fb_button_action' => 'like' ] ) ],
], $band++ ) );

// ── FACEBOOK PAGE ──
array_push( $elements, ..._bdea_showcase( 'Facebook Page', 'bdea_facebook_page', [
    [ _bdea_widget( 'bdea_facebook_page', [ 'url' => 'https://www.facebook.com/facebook', 'tabs' => [ 'timeline' ] ] ) ],
], $band++ ) );

// ── FACEBOOK COMMENTS ──
array_push( $elements, ..._bdea_showcase( 'Facebook Comments', 'bdea_facebook_comments', [
    [ _bdea_widget( 'bdea_facebook_comments', [ 'url' => 'https://www.facebook.com/facebook' ] ) ],
], $band++ ) );

// ── FACEBOOK EMBED ──
array_push( $elements, ..._bdea_showcase( 'Facebook Embed', 'bdea_facebook_embed', [
    [ _bdea_widget( 'bdea_facebook_embed', [ 'url' => 'https://www.facebook.com/facebook/videos/10153231379906729/' ] ) ],
], $band++ ) );

// ── SHORTCODE ──
array_push( $elements, ..._bdea_showcase( 'Shortcode', 'bdea_shortcode', [
    [ _bdea_widget( 'bdea_shortcode', [] ) ],
], $band++ ) );

// ── TEMPLATE ──
array_push( $elements, ..._bdea_showcase( 'Template', 'bdea_template', [
    [ _bdea_widget( 'bdea_template', [ 'template_id' => '' ] ) ],
], $band++ ) );

// ========================================
// SAVE (direct SQL, same as original demo script)
// ========================================
$data_json = json_encode( $elements, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );

if ( json_last_error() !== JSON_ERROR_NONE ) {
    die( 'JSON ENCODE ERROR: ' . json_last_error_msg() );
}

$verify = json_decode( $data_json, true );
if ( $verify === null ) {
    die( 'JSON VERIFY FAILED' );
}

echo 'JSON encoded: ' . strlen( $data_json ) . ' bytes, ' . count( $elements ) . ' sections' . PHP_EOL;

global $wpdb;

$wpdb->delete( $wpdb->postmeta, [ 'post_id' => $page->ID, 'meta_key' => '_elementor_data' ], [ '%d', '%s' ] );
$wpdb->insert( $wpdb->postmeta, [ 'post_id' => $page->ID, 'meta_key' => '_elementor_data', 'meta_value' => $data_json ], [ '%d', '%s', '%s' ] );

$wpdb->query( $wpdb->prepare(
    "DELETE FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key IN ('_elementor_edit_mode', '_elementor_version', '_wp_page_template')",
    $page->ID
) );
$wpdb->insert( $wpdb->postmeta, [ 'post_id' => $page->ID, 'meta_key' => '_elementor_edit_mode', 'meta_value' => 'builder' ], [ '%d', '%s', '%s' ] );
$wpdb->insert( $wpdb->postmeta, [ 'post_id' => $page->ID, 'meta_key' => '_elementor_version', 'meta_value' => ELEMENTOR_VERSION ], [ '%d', '%s', '%s' ] );
$wpdb->insert( $wpdb->postmeta, [ 'post_id' => $page->ID, 'meta_key' => '_wp_page_template', 'meta_value' => '' ], [ '%d', '%s', '%s' ] );

wp_update_post( [
    'ID'         => $page->ID,
    'post_title' => 'Widget Showcase',
] );

$saved = $wpdb->get_var( $wpdb->prepare(
    "SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key = '_elementor_data'",
    $page->ID
) );

$saved_verify = json_decode( $saved, true );
echo 'Read-back: ' . strlen( $saved ) . ' bytes' . PHP_EOL;
echo 'Decode: ' . ( $saved_verify !== null ? 'OK (' . count( $saved_verify ) . ' sections)' : 'FAILED' ) . PHP_EOL;

if ( class_exists( '\Elementor\Plugin' ) ) {
    \Elementor\Plugin::$instance->files_manager->clear_cache();
}

echo PHP_EOL . 'Done! Open: ' . get_permalink( $page->ID ) . PHP_EOL;
