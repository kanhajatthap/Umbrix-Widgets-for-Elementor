<?php
/**
 * FINAL FIX: Use direct SQL to avoid WordPress meta layer corruption.
 */
require_once dirname(__DIR__, 3) . '/wp-load.php';

if ( ! did_action( 'elementor/loaded' ) ) {
    die( 'Elementor not loaded.' );
}

$page = get_page_by_path( 'widget-demo' );
if ( ! $page ) {
    die( 'Page not found.' );
}

function _bdea_rand_id() {
    return substr( md5( uniqid( mt_rand(), true ) ), 0, 7 );
}

function _bdea_section( $widgets, $settings = [], $col_settings = [] ) {
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
            'id'         => _bdea_rand_id(),
            'elType'     => 'column',
            'settings'   => array_merge( [ '_column_size' => 100 ], $col_settings ),
            'elements'   => $elements,
            'isInner'    => false,
        ];
    }
    $size = (int) ( 100 / count( $cols ) );
    foreach ( $cols as &$col ) {
        $col['settings']['_column_size'] = $size;
    }
    unset( $col );
    return [
        'id'         => _bdea_rand_id(),
        'elType'     => 'section',
        'settings'   => array_merge( [
            'layout'          => 'boxed',
            'content_width'   => [ 'unit' => 'px', 'size' => 1140 ],
            'gap'             => 'extended',
        ], $settings ),
        'elements'   => $cols,
        'isInner'    => false,
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

function _bdea_heading( $text, $tag = 'h2', $align = 'center' ) {
    return _bdea_widget( 'heading', [
        'title'        => $text,
        'header_size'  => $tag,
        'align'        => $align,
    ] );
}

// ========================================
// BUILD ALL SECTIONS
// ========================================
$elements = [];

// ── HERO ──
$elements[] = _bdea_section(
    [
        [
            _bdea_heading( 'ElementStack Elementor Addons', 'h1', 'center' ),
        ],
        [
            _bdea_heading( 'Complete widget showcase — all 73 custom widgets', 'h3', 'center' ),
        ],
        [
            _bdea_widget( 'bdea_button', [
                'button_text' => 'View All Widgets',
                'button_link' => [ 'url' => '#widgets' ],
            ] ),
        ],
    ],
    [
        'background_background'     => 'gradient',
        'background_color'          => '#4361ee',
        'background_color_b'        => '#7209b7',
        'background_gradient_angle' => [ 'size' => 135, 'unit' => 'deg' ],
        'padding'                   => [ 'unit' => 'px', 'top' => '80', 'right' => '20', 'bottom' => '80', 'left' => '20', 'isLinked' => false ],
    ]
);

// ── BUTTONS ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Buttons' ) ] ] );
$elements[] = _bdea_section( [
    [ _bdea_widget( 'bdea_button', [ 'button_text' => 'Primary', 'button_bg' => '#4361ee', 'button_color' => '#ffffff' ] ) ],
    [ _bdea_widget( 'bdea_button', [ 'button_text' => 'Success', 'button_bg' => '#10b981', 'button_color' => '#ffffff' ] ) ],
    [ _bdea_widget( 'bdea_button', [ 'button_text' => 'Danger',  'button_bg' => '#ef4444', 'button_color' => '#ffffff' ] ) ],
] );

// ── ICON BOX ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Icon Box' ) ] ] );
$elements[] = _bdea_section( [
    [ _bdea_widget( 'bdea_icon_box', [ 'selected_icon' => [ 'value' => 'fas fa-rocket', 'library' => 'fa-solid' ], 'title' => 'Fast Performance', 'description' => 'Optimized for speed.', 'align' => 'center' ] ) ],
    [ _bdea_widget( 'bdea_icon_box', [ 'selected_icon' => [ 'value' => 'fas fa-shield-alt', 'library' => 'fa-solid' ], 'title' => 'Secure', 'description' => 'Built with security in mind.', 'align' => 'center' ] ) ],
    [ _bdea_widget( 'bdea_icon_box', [ 'selected_icon' => [ 'value' => 'fas fa-paint-brush', 'library' => 'fa-solid' ], 'title' => 'Customizable', 'description' => 'Full styling controls.', 'align' => 'center' ] ) ],
] );

// ── COUNTER ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Counter' ) ] ] );
$elements[] = _bdea_section( [
    [ _bdea_widget( 'bdea_counter', [ 'counter_number' => 2500, 'counter_suffix' => '+', 'counter_title' => 'Happy Customers', 'number_color' => '#4361ee' ] ) ],
    [ _bdea_widget( 'bdea_counter', [ 'counter_number' => 150, 'counter_suffix' => '+', 'counter_title' => 'Projects Done', 'number_color' => '#7209b7' ] ) ],
    [ _bdea_widget( 'bdea_counter', [ 'counter_number' => 99, 'counter_suffix' => '%', 'counter_title' => 'Satisfaction', 'number_color' => '#10b981' ] ) ],
] );

// ── STAR RATING ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Star Rating' ) ] ] );
$elements[] = _bdea_section( [
    [ _bdea_widget( 'bdea_star_rating', [ 'rating_value' => [ 'size' => 4.5 ], 'rating_scale' => '5', 'show_number' => 'yes', 'star_color' => '#f7b500' ] ) ],
] );

// ── PROGRESS BAR ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Progress Bar' ) ] ] );
$elements[] = _bdea_section( [
    [ _bdea_widget( 'bdea_progress_bar', [ 'progress_bar_title' => 'HTML/CSS', 'progress_bar_percentage' => [ 'size' => 90 ], 'inner_content_color' => '#4361ee' ] ) ],
    [ _bdea_widget( 'bdea_progress_bar', [ 'progress_bar_title' => 'JavaScript', 'progress_bar_percentage' => [ 'size' => 75 ], 'inner_content_color' => '#7209b7' ] ) ],
    [ _bdea_widget( 'bdea_progress_bar', [ 'progress_bar_title' => 'PHP', 'progress_bar_percentage' => [ 'size' => 60 ], 'inner_content_color' => '#10b981' ] ) ],
] );

// ── TABS ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Tabs' ) ] ] );
$elements[] = _bdea_section( [
    [ _bdea_widget( 'bdea_tabs', [
        'tabs' => [
            [ 'tab_title' => 'Overview', 'tab_content' => 'This is a beautiful tab widget.' ],
            [ 'tab_title' => 'Features', 'tab_content' => 'Responsive layout with custom icons.' ],
            [ 'tab_title' => 'Reviews', 'tab_content' => 'Rated 5 stars by happy users!' ],
        ],
    ] ) ],
] );

// ── ACCORDION ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Accordion' ) ] ] );
$elements[] = _bdea_section( [
    [ _bdea_widget( 'bdea_accordion', [
        'accordion' => [
            [ 'accordion_title' => 'What is ElementStack?', 'accordion_content' => 'A powerful Elementor addon with 73+ widgets.' ],
            [ 'accordion_title' => 'Do I need Elementor Pro?', 'accordion_content' => 'No! It works with the free version.' ],
            [ 'accordion_title' => 'Can I use it on client sites?', 'accordion_content' => 'Yes, unlimited personal and client websites.' ],
        ],
    ] ) ],
] );

// ── TESTIMONIAL ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Testimonial' ) ] ] );
$elements[] = _bdea_section( [
    [ _bdea_widget( 'bdea_testimonial', [
        'testimonial_content' => 'This plugin completely transformed how we build pages.',
        'testimonial_name' => 'Sarah Johnson',
        'testimonial_job' => 'CEO, TechCorp',
        'testimonial_image' => [ 'url' => 'https://i.pravatar.cc/100?img=1' ],
    ] ) ],
    [ _bdea_widget( 'bdea_testimonial', [
        'testimonial_content' => 'Absolutely the best Elementor addon. Highly recommended!',
        'testimonial_name' => 'Mike Rodriguez',
        'testimonial_job' => 'Senior Developer',
        'testimonial_image' => [ 'url' => 'https://i.pravatar.cc/100?img=2' ],
    ] ) ],
] );

// ── BLOCKQUOTE ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Blockquote' ) ] ] );
$elements[] = _bdea_section( [
    [ _bdea_widget( 'bdea_blockquote', [
        'blockquote_content' => 'Design is not just what it looks like. Design is how it works.',
        'blockquote_author' => 'Steve Jobs',
    ] ) ],
] );

// ── DATA TABLE ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Data Table' ) ] ] );
$elements[] = _bdea_section( [
    [ _bdea_widget( 'bdea_data_table', [
        'table_data' => [
            'header' => [ 'Widget', 'Category', 'Status' ],
            'rows' => [
                [ 'Accordion', 'Content', 'Active' ],
                [ 'Counter', 'Dynamic', 'Active' ],
                [ 'Form', 'Interactive', 'Active' ],
            ],
        ],
    ] ) ],
] );

// ── FEATURE COMPARISON ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Feature Comparison' ) ] ] );
$elements[] = _bdea_section( [
    [ _bdea_widget( 'bdea_feature_comparison_table', [
        'features' => [
            [ 'feature_name' => '73+ Widgets', 'free' => 'yes', 'pro' => 'yes' ],
            [ 'feature_name' => 'Header/Footer Builder', 'free' => 'yes', 'pro' => 'yes' ],
            [ 'feature_name' => 'Loop Templates', 'free' => '', 'pro' => 'yes' ],
        ],
    ] ) ],
] );

// ── PRICE TABLE ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Price Table' ) ] ] );
$elements[] = _bdea_section( [
    [ _bdea_widget( 'bdea_price_table', [
        'plan_name' => 'Starter',
        'price' => '$9',
        'period' => '/mo',
        'features_list' => [
            ['text' => '5 Projects', 'icon' => 'fas fa-check', 'included' => 'yes'],
            ['text' => '10GB Storage', 'icon' => 'fas fa-check', 'included' => 'yes'],
        ],
        'button_text' => 'Choose Plan',
    ] ) ],
    [ _bdea_widget( 'bdea_price_table', [
        'plan_name' => 'Professional',
        'price' => '$29',
        'period' => '/mo',
        'features_list' => [
            ['text' => 'Unlimited Projects', 'icon' => 'fas fa-check', 'included' => 'yes'],
            ['text' => '100GB Storage', 'icon' => 'fas fa-check', 'included' => 'yes'],
            ['text' => 'Priority Support', 'icon' => 'fas fa-check', 'included' => 'yes'],
        ],
        'button_text' => 'Choose Plan',
        'highlighted' => 'yes',
    ] ) ],
    [ _bdea_widget( 'bdea_price_table', [
        'plan_name' => 'Enterprise',
        'price' => '$99',
        'period' => '/mo',
        'features_list' => [
            ['text' => 'Everything in Pro', 'icon' => 'fas fa-check', 'included' => 'yes'],
            ['text' => 'Unlimited Storage', 'icon' => 'fas fa-check', 'included' => 'yes'],
        ],
        'button_text' => 'Contact Sales',
    ] ) ],
] );

// ── PRICE LIST ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Price List' ) ] ] );
$elements[] = _bdea_section( [
    [ _bdea_widget( 'bdea_price_list', [
        'price_list' => [
            [ 'title' => 'Web Design', 'description' => 'Custom responsive website', 'price' => '$1,200' ],
            [ 'title' => 'SEO', 'description' => 'Full on-page SEO', 'price' => '$500' ],
            [ 'title' => 'Maintenance', 'description' => 'Updates and support', 'price' => '$99/mo' ],
        ],
    ] ) ],
] );

// ── SOCIAL ICONS ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Social Icons' ) ] ] );
$elements[] = _bdea_section( [
    [ _bdea_widget( 'bdea_social_icons', [
        'social_icon_list' => [
            [ 'social_icon' => 'fab fa-facebook-f', 'link' => [ 'url' => '#' ], 'social_color' => '#1877f2' ],
            [ 'social_icon' => 'fab fa-twitter', 'link' => [ 'url' => '#' ], 'social_color' => '#1da1f2' ],
            [ 'social_icon' => 'fab fa-instagram', 'link' => [ 'url' => '#' ], 'social_color' => '#e4405f' ],
            [ 'social_icon' => 'fab fa-linkedin-in', 'link' => [ 'url' => '#' ], 'social_color' => '#0077b5' ],
            [ 'social_icon' => 'fab fa-youtube', 'link' => [ 'url' => '#' ], 'social_color' => '#ff0000' ],
        ],
    ] ) ],
] );

// ── ICON LIST ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Icon List' ) ] ] );
$elements[] = _bdea_section( [
    [ _bdea_widget( 'bdea_icon_list', [
        'icon_list' => [
            [ 'text' => 'Easy to install', 'icon' => 'fas fa-check-circle' ],
            [ 'text' => '73+ widgets', 'icon' => 'fas fa-check-circle' ],
            [ 'text' => 'Header/Footer builder', 'icon' => 'fas fa-check-circle' ],
            [ 'text' => 'Works with any theme', 'icon' => 'fas fa-check-circle' ],
        ],
    ] ) ],
] );

// ── FORM ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Form' ) ] ] );
$elements[] = _bdea_section( [
    [ _bdea_widget( 'bdea_form', [
        'form_fields' => [
            [ 'type' => 'text', 'field_label' => 'Name', 'placeholder' => 'John Doe', 'required' => 'yes' ],
            [ 'type' => 'email', 'field_label' => 'Email', 'placeholder' => 'john@example.com', 'required' => 'yes' ],
            [ 'type' => 'textarea', 'field_label' => 'Message', 'placeholder' => 'Your message...' ],
        ],
        'submit_button_text' => 'Send Message',
    ] ) ],
] );

// ── LOGIN ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Login' ) ] ] );
$elements[] = _bdea_section( [
    [ _bdea_widget( 'bdea_login', [
        'login_title' => 'Welcome Back',
        'button_label' => 'Log In',
    ] ) ],
] );

// ── SHARE IT ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Share It' ) ] ] );
$elements[] = _bdea_section( [
    [ _bdea_widget( 'bdea_share_it', [
        'share_networks' => [
            [ 'social_network' => 'facebook', 'link' => [ 'url' => '#' ] ],
            [ 'social_network' => 'twitter', 'link' => [ 'url' => '#' ] ],
            [ 'social_network' => 'linkedin', 'link' => [ 'url' => '#' ] ],
            [ 'social_network' => 'pinterest', 'link' => [ 'url' => '#' ] ],
            [ 'social_network' => 'whatsapp', 'link' => [ 'url' => '#' ] ],
        ],
    ] ) ],
] );

// ── COUNTDOWN ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Countdown' ) ] ] );
$elements[] = _bdea_section( [
    [ _bdea_widget( 'bdea_countdown', [
        'due_date' => gmdate( 'Y-m-d\TH:i:s', strtotime( '+12 days' ) ),
        'show_labels' => 'yes',
    ] ) ],
] );

// ── CODE HIGHLIGHT ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Code Highlight' ) ] ] );
$elements[] = _bdea_section( [
    [ _bdea_widget( 'bdea_code_highlight', [
        'code_language' => 'javascript',
        'code_content' => 'const greeting = "Hello";\nconsole.log(greeting);',
        'show_copy_btn' => 'yes',
    ] ) ],
] );

// ── BREADCRUMBS ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Breadcrumbs' ) ] ] );
$elements[] = _bdea_section( [ [ _bdea_widget( 'bdea_breadcrumbs', [] ) ] ] );

// ── DIVIDER ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Divider' ) ] ] );
$elements[] = _bdea_section( [
    [ _bdea_widget( 'bdea_divider', [ 'style' => 'solid', 'weight' => [ 'size' => 1, 'unit' => 'px' ], 'color' => '#4361ee', 'width' => [ 'size' => 50, 'unit' => '%' ], 'align' => 'center' ] ) ],
] );

// ── SPACER ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Spacer' ) ] ] );
$elements[] = _bdea_section( [
    [ _bdea_widget( 'bdea_spacer', [ 'space' => [ 'size' => 50, 'unit' => 'px' ] ] ) ],
] );

// ── SEARCH ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Search' ) ] ] );
$elements[] = _bdea_section( [ [ _bdea_widget( 'bdea_search', [] ) ] ] );

// ── SITE TITLE ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Site Title' ) ] ] );
$elements[] = _bdea_section( [ [ _bdea_widget( 'bdea_site_title', [] ) ] ] );

// ── SITE LOGO ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Site Logo' ) ] ] );
$elements[] = _bdea_section( [ [ _bdea_widget( 'bdea_site_logo', [ 'logo_width' => [ 'size' => 200, 'unit' => 'px' ], 'logo_align' => 'center' ] ) ] ] );

// ── VIDEO ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Video' ) ] ] );
$elements[] = _bdea_section( [
    [ _bdea_widget( 'bdea_video', [ 'video_type' => 'youtube', 'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ' ] ) ],
] );

// ── FLIP BOX ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Flip Box' ) ] ] );
$elements[] = _bdea_section( [
    [ _bdea_widget( 'bdea_flip_box', [
        'front_title' => 'Hover Me!',
        'front_description' => 'Click to see the back',
        'back_title' => 'Surprise!',
        'back_description' => 'This is the back content.',
        'front_icon' => [ 'value' => 'fas fa-sync-alt', 'library' => 'fa-solid' ],
    ] ) ],
] );

// ── CALL TO ACTION ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Call to Action' ) ] ] );
$elements[] = _bdea_section( [
    [ _bdea_widget( 'bdea_call_to_action', [
        'cta_title' => 'Ready to Get Started?',
        'cta_description' => 'Join 10,000+ happy customers.',
        'cta_button_text' => 'Get Started Free',
        'cta_button_link' => [ 'url' => '#' ],
    ] ) ],
] );

// ── IMAGE BOX ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Image Box' ) ] ] );
$elements[] = _bdea_section( [
    [ _bdea_widget( 'bdea_image_box', [
        'image' => [ 'url' => 'https://picsum.photos/seed/ibox1/400/200' ],
        'title' => 'Creative Solutions',
        'description' => 'Innovative designs that stand out.',
    ] ) ],
    [ _bdea_widget( 'bdea_image_box', [
        'image' => [ 'url' => 'https://picsum.photos/seed/ibox2/400/200' ],
        'title' => 'Expert Team',
        'description' => 'Professional experience in every project.',
    ] ) ],
] );

// ── ICON ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Icon' ) ] ] );
$elements[] = _bdea_section( [
    [ _bdea_widget( 'bdea_icon', [ 'selected_icon' => [ 'value' => 'fas fa-star', 'library' => 'fa-solid' ] ] ) ],
    [ _bdea_widget( 'bdea_icon', [ 'selected_icon' => [ 'value' => 'fas fa-heart', 'library' => 'fa-solid' ] ] ) ],
    [ _bdea_widget( 'bdea_icon', [ 'selected_icon' => [ 'value' => 'fas fa-bolt', 'library' => 'fa-solid' ] ] ) ],
] );

// ── SHARE BUTTONS (using social_icons as fallback) ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Share Buttons' ) ] ] );
$elements[] = _bdea_section( [
    [ _bdea_widget( 'bdea_social_icons', [
        'social_icon_list' => [
            [ 'social_icon' => 'fab fa-facebook-f', 'link' => [ 'url' => '#' ], 'social_color' => '#1877f2' ],
            [ 'social_icon' => 'fab fa-twitter', 'link' => [ 'url' => '#' ], 'social_color' => '#1da1f2' ],
            [ 'social_icon' => 'fab fa-linkedin-in', 'link' => [ 'url' => '#' ], 'social_color' => '#0077b5' ],
        ],
    ] ) ],
] );

// ── TOC ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Table of Content' ) ] ] );
$elements[] = _bdea_section( [
    [ _bdea_widget( 'bdea_table_of_content', [
        'toc_title' => 'Table of Contents',
        'toc_heading' => [ 'h2', 'h3' ],
    ] ) ],
] );

// ── WP MENU ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'WordPress Menu' ) ] ] );
$elements[] = _bdea_section( [ [ _bdea_widget( 'bdea_wp_menu', [] ) ] ] );

// ── SITEMAP ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Sitemap' ) ] ] );
$elements[] = _bdea_section( [ [ _bdea_widget( 'bdea_sitemap', [] ) ] ] );

// ── TAXONOMY FILTER ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Taxonomy Filter' ) ] ] );
$elements[] = _bdea_section( [
    [ _bdea_widget( 'bdea_taxonomy_filter', [
        'filter_taxonomy' => 'category',
        'filter_all_label' => 'All',
    ] ) ],
] );

// ── REVIEWS ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Reviews' ) ] ] );
$elements[] = _bdea_section( [
    [ _bdea_widget( 'bdea_reviews', [
        'reviews_items' => [
            [ 'review_name' => 'Mike R.', 'review_job' => 'Developer', 'review_text' => 'Love this addon!', 'review_rating' => 5, 'review_image' => [ 'url' => 'https://i.pravatar.cc/60?img=3' ] ],
            [ 'review_name' => 'Anna K.', 'review_job' => 'Designer', 'review_text' => 'Great variety of widgets.', 'review_rating' => 4, 'review_image' => [ 'url' => 'https://i.pravatar.cc/60?img=4' ] ],
        ],
    ] ) ],
] );

// ── AUTHOR BOX ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Author Box' ) ] ] );
$elements[] = _bdea_section( [ [ _bdea_widget( 'bdea_author_box', [] ) ] ] );

// ── GOOGLE MAPS ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Google Maps' ) ] ] );
$elements[] = _bdea_section( [
    [ _bdea_widget( 'bdea_google_maps', [ 'location' => 'New York, USA', 'zoom' => [ 'size' => 12 ] ] ) ],
] );

// ── FACEBOOK BUTTON ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Facebook Button' ) ] ] );
$elements[] = _bdea_section( [
    [ _bdea_widget( 'bdea_facebook_button', [ 'fb_button_layout' => 'standard', 'fb_button_action' => 'like' ] ) ],
] );

// ── FACEBOOK PAGE ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Facebook Page' ) ] ] );
$elements[] = _bdea_section( [
    [ _bdea_widget( 'bdea_facebook_page', [ 'url' => 'https://www.facebook.com/facebook', 'tabs' => [ 'timeline' ] ] ) ],
] );

// ── LOTTIE ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Lottie' ) ] ] );
$elements[] = _bdea_section( [
    [ _bdea_widget( 'bdea_lottie', [ 'lottie_url' => 'https://assets2.lottiefiles.com/packages/lf20_usmfx6bp.json' ] ) ],
] );

// ── AUDIO ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Audio Player' ) ] ] );
$elements[] = _bdea_section( [
    [ _bdea_widget( 'bdea_video', [ 'video_type' => 'youtube', 'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ' ] ) ],
] );

// ── ANIMATED HEADLINE ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Animated Headline' ) ] ] );
$elements[] = _bdea_section( [
    [ _bdea_widget( 'bdea_animated_headline', [
        'animated_headline' => 'We build',
        'headline_style' => 'rotating',
        'rotating_text' => [ 'websites', 'apps', 'stores', 'brands' ],
        'animated_text_color' => '#4361ee',
    ] ) ],
] );

// ── OFF CANVAS ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Off-Canvas' ) ] ] );
$elements[] = _bdea_section( [
    [ _bdea_widget( 'bdea_off_canvas', [ 'offcanvas_title' => 'Menu', 'trigger_text' => 'Open Menu' ] ) ],
] );

// ── GALLERY ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Basic Gallery' ) ] ] );
$elements[] = _bdea_section( [
    [ _bdea_widget( 'bdea_basic_gallery', [
        'gallery' => [
            [ 'url' => 'https://picsum.photos/seed/g1/400/300' ],
            [ 'url' => 'https://picsum.photos/seed/g2/400/300' ],
            [ 'url' => 'https://picsum.photos/seed/g3/400/300' ],
            [ 'url' => 'https://picsum.photos/seed/g4/400/300' ],
            [ 'url' => 'https://picsum.photos/seed/g5/400/300' ],
            [ 'url' => 'https://picsum.photos/seed/g6/400/300' ],
        ],
        'columns' => 3,
    ] ) ],
] );

// ── IMAGE CAROUSEL ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Image Carousel' ) ] ] );
$elements[] = _bdea_section( [
    [ _bdea_widget( 'bdea_image_carousel', [
        'carousel' => [
            [ 'url' => 'https://picsum.photos/seed/c1/600/400' ],
            [ 'url' => 'https://picsum.photos/seed/c2/600/400' ],
            [ 'url' => 'https://picsum.photos/seed/c3/600/400' ],
        ],
        'slides_to_show' => 2,
    ] ) ],
] );

// ── HOTSPOT ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Hotspot' ) ] ] );
$elements[] = _bdea_section( [
    [ _bdea_widget( 'bdea_hotspot', [
        'hotspot_image' => [ 'url' => 'https://picsum.photos/seed/hotspot/600/400' ],
        'hotspots' => [
            [ 'x' => 30, 'y' => 40, 'tooltip' => 'Feature A' ],
            [ 'x' => 70, 'y' => 60, 'tooltip' => 'Feature B' ],
        ],
    ] ) ],
] );

// ── PAGE TITLE ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Page Title' ) ] ] );
$elements[] = _bdea_section( [ [ _bdea_widget( 'bdea_page_title', [] ) ] ] );

// ── POST TITLE ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Post Title' ) ] ] );
$elements[] = _bdea_section( [ [ _bdea_widget( 'bdea_post_title', [] ) ] ] );

// ── POST EXCERPT ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Post Excerpt' ) ] ] );
$elements[] = _bdea_section( [ [ _bdea_widget( 'bdea_post_excerpt', [ 'excerpt_length' => 20 ] ) ] ] );

// ── POST INFO ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Post Info' ) ] ] );
$elements[] = _bdea_section( [
    [ _bdea_widget( 'bdea_post_info', [
        'post_info_items' => [
            [ 'selected_icon' => [ 'value' => 'fas fa-calendar', 'library' => 'fa-solid' ], 'type' => 'post_date' ],
            [ 'selected_icon' => [ 'value' => 'fas fa-user', 'library' => 'fa-solid' ], 'type' => 'author' ],
        ],
    ] ) ],
] );

// ── POST NAVIGATION ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Post Navigation' ) ] ] );
$elements[] = _bdea_section( [ [ _bdea_widget( 'bdea_post_navigation', [] ) ] ] );

// ── FEATURED IMAGE ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Featured Image' ) ] ] );
$elements[] = _bdea_section( [ [ _bdea_widget( 'bdea_featured_image', [] ) ] ] );

// ── ARCHIVE TITLE ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Archive Title' ) ] ] );
$elements[] = _bdea_section( [ [ _bdea_widget( 'bdea_archive_title', [] ) ] ] );

// ── POST COMMENTS ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Post Comments' ) ] ] );
$elements[] = _bdea_section( [ [ _bdea_widget( 'bdea_post_comments', [] ) ] ] );

// ── ARCHIVE POSTS ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Archive Posts' ) ] ] );
$elements[] = _bdea_section( [ [ _bdea_widget( 'bdea_archive_posts', [] ) ] ] );

// ── POST CONTENT ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Post Content' ) ] ] );
$elements[] = _bdea_section( [ [ _bdea_widget( 'bdea_post_content', [] ) ] ] );

// ── LOOP GRID ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Loop Grid' ) ] ] );
$elements[] = _bdea_section( [ [ _bdea_widget( 'bdea_loop_grid', [] ) ] ] );

// ── LOOP CAROUSEL ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Loop Carousel' ) ] ] );
$elements[] = _bdea_section( [ [ _bdea_widget( 'bdea_loop_carousel', [] ) ] ] );

// ── PORTFOLIO ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Portfolio' ) ] ] );
$elements[] = _bdea_section( [ [ _bdea_widget( 'bdea_portfolio', [] ) ] ] );

// ── SLIDES ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Slides' ) ] ] );
$elements[] = _bdea_section( [
    [ _bdea_widget( 'bdea_slides', [
        'slides' => [
            [ 'slide_title' => 'Welcome to ElementStack', 'slide_description' => '73+ custom widgets', 'button_text' => 'Explore', 'button_link' => [ 'url' => '#' ] ],
        ],
    ] ) ],
] );

// ── PROGRESS TRACKER ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Progress Tracker' ) ] ] );
$elements[] = _bdea_section( [
    [ _bdea_widget( 'bdea_progress_tracker', [ 'tracker_type' => 'steps', 'tracker_title' => 'Order Progress' ] ) ],
] );

// ── LINK IN BIO ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Link in Bio' ) ] ] );
$elements[] = _bdea_section( [
    [ _bdea_widget( 'bdea_link_in_bio', [
        'lib_profile_image' => [ 'url' => 'https://i.pravatar.cc/100?img=5' ],
        'lib_name' => '@brandname',
        'lib_bio' => 'Digital Creator',
        'lib_links' => [
            [ 'text' => 'Website', 'link' => [ 'url' => '#' ] ],
            [ 'text' => 'Blog', 'link' => [ 'url' => '#' ] ],
            [ 'text' => 'YouTube', 'link' => [ 'url' => '#' ] ],
        ],
    ] ) ],
] );

// ── TEMPLATE ──
$elements[] = _bdea_section( [ [ _bdea_heading( 'Template' ) ] ] );
$elements[] = _bdea_section( [
    [ _bdea_widget( 'bdea_template', [ 'template_id' => '' ] ) ],
] );

// ========================================
// SAVE TO DATABASE — DIRECT SQL to avoid WP meta layer corruption
// ========================================
$data_json = json_encode( $elements, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );

if ( json_last_error() !== JSON_ERROR_NONE ) {
    die( 'JSON ENCODE ERROR: ' . json_last_error_msg() );
}

// Verify
$verify = json_decode( $data_json, true );
if ( $verify === null ) {
    die( 'JSON VERIFY FAILED' );
}

echo "JSON encoded: " . strlen( $data_json ) . " bytes, " . count( $elements ) . " sections" . PHP_EOL;

// Use direct SQL to bypass WP meta layer
global $wpdb;

// Delete existing meta
$wpdb->delete( $wpdb->postmeta, [
    'post_id' => $page->ID,
    'meta_key' => '_elementor_data',
], [ '%d', '%s' ] );

// Insert fresh — use %s with no escaping (wpdb->prepare handles it)
$wpdb->insert( $wpdb->postmeta, [
    'post_id' => $page->ID,
    'meta_key' => '_elementor_data',
    'meta_value' => $data_json,
], [ '%d', '%s', '%s' ] );

// Also set edit mode and version
$wpdb->query( $wpdb->prepare(
    "DELETE FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key IN ('_elementor_edit_mode', '_elementor_version', '_wp_page_template')",
    $page->ID
) );
$wpdb->insert( $wpdb->postmeta, [ 'post_id' => $page->ID, 'meta_key' => '_elementor_edit_mode', 'meta_value' => 'builder' ], [ '%d', '%s', '%s' ] );
$wpdb->insert( $wpdb->postmeta, [ 'post_id' => $page->ID, 'meta_key' => '_elementor_version', 'meta_value' => ELEMENTOR_VERSION ], [ '%d', '%s', '%s' ] );
$wpdb->insert( $wpdb->postmeta, [ 'post_id' => $page->ID, 'meta_key' => '_wp_page_template', 'meta_value' => '' ], [ '%d', '%s', '%s' ] );

// Verify read-back
$saved = $wpdb->get_var( $wpdb->prepare(
    "SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key = '_elementor_data'",
    $page->ID
) );

$saved_verify = json_decode( $saved, true );
echo "Read-back: " . strlen( $saved ) . " bytes" . PHP_EOL;
echo "Decode: " . ( $saved_verify !== null ? 'OK (' . count( $saved_verify ) . ' sections)' : 'FAILED' ) . PHP_EOL;

// Clear Elementor cache
if ( class_exists( '\Elementor\Plugin' ) ) {
    \Elementor\Plugin::$instance->files_manager->clear_cache();
    \Elementor\Plugin::$instance->documents->clear_cache();
}

echo PHP_EOL . "Done! Open: " . get_permalink( $page->ID ) . PHP_EOL;
