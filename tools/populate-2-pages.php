<?php
/**
 * Populates ONLY 2 pages with all 73 widgets (3 variations each).
 *   Page 82 – widgets-content  (Content / layout / interactive widgets)
 *   Page 84 – widgets-media    (Media / embed / social / form / theme widgets)
 *
 * Usage: tools\wp.cmd eval-file tools/populate-2-pages.php
 */

if ( ! defined( 'ABSPATH' ) ) {
    require_once dirname( __DIR__, 4 ) . '/wp-load.php';
}
if ( ! did_action( 'elementor/loaded' ) ) {
    die( 'Elementor not loaded.' );
}

/* ── helpers ────────────────────────────────────────────────────── */

function _rid() { return substr( md5( uniqid( mt_rand(), true ) ), 0, 7 ); }

function _w( $type, $s = [] ) {
    return [ 'id' => _rid(), 'elType' => 'widget', 'widgetType' => $type, 'settings' => $s, 'elements' => [], 'isInner' => false ];
}

function _h( $text, $tag = 'h2' ) {
    return _w( 'heading', [ 'title' => $text, 'header_size' => $tag, 'align' => 'left', 'title_color' => '#111827' ] );
}

function _col( $widgets, $size = 100 ) {
    $els = [];
    foreach ( (array) $widgets as $w ) $els[] = $w;
    return [ 'id' => _rid(), 'elType' => 'column', 'settings' => [ '_column_size' => $size ], 'elements' => $els, 'isInner' => false ];
}

function _sec( $cols, $s = [] ) {
    return [ 'id' => _rid(), 'elType' => 'section', 'settings' => array_merge( [ 'layout' => 'boxed', 'content_width' => [ 'unit' => 'px', 'size' => 1140 ], 'gap' => 'extended' ], $s ), 'elements' => $cols, 'isInner' => false ];
}

function _cols( $cols_of_w, $s = [], $sizes = [] ) {
    $cs = [];
    foreach ( $cols_of_w as $i => $cw ) {
        $cs[] = _col( $cw, $sizes[ $i ] ?? (int) ( 100 / count( $cols_of_w ) ) );
    }
    return _sec( $cs, $s );
}

function _show( $name, $cols, $band = 0 ) {
    $bg = ( $band % 2 ) ? '#f8fafc' : '#ffffff';
    $hdr = _cols( [ [ _h( $name ) ] ], [
        'background_background' => 'classic', 'background_color' => $bg,
        'padding' => [ 'unit' => 'px', 'top' => 50, 'right' => 20, 'bottom' => 10, 'left' => 20, 'isLinked' => false ],
    ] );
    $body = _cols( $cols, [
        'background_background' => 'classic', 'background_color' => $bg,
        'padding' => [ 'unit' => 'px', 'top' => 20, 'right' => 20, 'bottom' => 50, 'left' => 20, 'isLinked' => false ],
    ] );
    return [ $hdr, $body ];
}

function _save( $id, $els, $title ) {
    $json = wp_json_encode( $els, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
    if ( json_last_error() !== JSON_ERROR_NONE ) { echo "JSON ERROR: " . json_last_error_msg() . PHP_EOL; return false; }
    global $wpdb;
    $wpdb->delete( $wpdb->postmeta, [ 'post_id' => $id, 'meta_key' => '_elementor_data' ], [ '%d', '%s' ] );
    $wpdb->insert( $wpdb->postmeta, [ 'post_id' => $id, 'meta_key' => '_elementor_data', 'meta_value' => $json ], [ '%d', '%s', '%s' ] );
    $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->postmeta} WHERE post_id=%d AND meta_key IN ('_elementor_edit_mode','_elementor_version','_wp_page_template')", $id ) );
    $wpdb->query( $wpdb->prepare( "INSERT INTO {$wpdb->postmeta} (post_id, meta_key, meta_value) VALUES (%d, '_elementor_edit_mode', 'builder')", $id ) );
    $wpdb->query( $wpdb->prepare( "INSERT INTO {$wpdb->postmeta} (post_id, meta_key, meta_value) VALUES (%d, '_elementor_version', %s)", $id, ELEMENTOR_VERSION ) );
    $wpdb->query( $wpdb->prepare( "INSERT INTO {$wpdb->postmeta} (post_id, meta_key, meta_value) VALUES (%d, '_wp_page_template', '')", $id ) );
    if ( class_exists( '\Elementor\Plugin' ) ) \Elementor\Plugin::$instance->files_manager->clear_cache();
    echo "Page $id ($title): " . count( $els ) . " sections, " . strlen( $json ) . " bytes" . PHP_EOL;
    return true;
}

/* ── Page 1: widgets-content (ID 82) ── 36 content widgets ────── */

$b = 0;
$e = [];

$e[] = _cols( [ [
    _h( 'Content Widgets', 'h1' ),
    _w( 'text-editor', [ 'editor' => '<p style="color:#6b7280">All content widgets with 3 design variations each.</p>', 'align' => 'center' ] ),
] ], [
    'background_background' => 'gradient', 'background_color' => '#0f172a', 'background_color_b' => '#4f46e5',
    'padding' => [ 'unit' => 'px', 'top' => 80, 'right' => 20, 'bottom' => 80, 'left' => 20, 'isLinked' => false ],
] );

// Button
array_push( $e, ..._show( 'Button', [
    [ _w( 'elementskey_button', [ 'button_text' => 'Get Started', 'button_bg' => '#4361ee', 'button_color' => '#fff' ] ) ],
    [ _w( 'elementskey_button', [ 'button_text' => 'Learn More', 'button_bg' => '#10b981', 'button_color' => '#fff' ] ) ],
    [ _w( 'elementskey_button', [ 'button_text' => 'Contact Us', 'button_bg' => '#ef4444', 'button_color' => '#fff' ] ) ],
], $b++ ) );

// Icon
array_push( $e, ..._show( 'Icon', [
    [ _w( 'elementskey_icon', [ 'selected_icon' => [ 'value' => 'fas fa-star', 'library' => 'fa-solid' ], 'icon_color' => '#f7b500' ] ) ],
    [ _w( 'elementskey_icon', [ 'selected_icon' => [ 'value' => 'fas fa-heart', 'library' => 'fa-solid' ], 'icon_color' => '#ef4444' ] ) ],
    [ _w( 'elementskey_icon', [ 'selected_icon' => [ 'value' => 'fas fa-bolt', 'library' => 'fa-solid' ], 'icon_color' => '#f59e0b' ] ) ],
], $b++ ) );

// Icon Box
array_push( $e, ..._show( 'Icon Box', [
    [ _w( 'elementskey_icon_box', [ 'selected_icon' => [ 'value' => 'fas fa-rocket', 'library' => 'fa-solid' ], 'title' => 'Fast Performance', 'description' => 'Optimized for speed.', 'align' => 'center' ] ) ],
    [ _w( 'elementskey_icon_box', [ 'selected_icon' => [ 'value' => 'fas fa-shield-alt', 'library' => 'fa-solid' ], 'title' => 'Secure', 'description' => 'Built with security in mind.', 'align' => 'center' ] ) ],
    [ _w( 'elementskey_icon_box', [ 'selected_icon' => [ 'value' => 'fas fa-paint-brush', 'library' => 'fa-solid' ], 'title' => 'Customizable', 'description' => 'Full styling controls.', 'align' => 'center' ] ) ],
], $b++ ) );

// Image Box
array_push( $e, ..._show( 'Image Box', [
    [ _w( 'elementskey_image_box', [ 'image' => [ 'url' => 'https://picsum.photos/seed/ibox1/400/200' ], 'title' => 'Creative Solutions', 'description' => 'Innovative designs that stand out.' ] ) ],
    [ _w( 'elementskey_image_box', [ 'image' => [ 'url' => 'https://picsum.photos/seed/ibox2/400/200' ], 'title' => 'Expert Team', 'description' => 'Professional experience.' ] ) ],
    [ _w( 'elementskey_image_box', [ 'image' => [ 'url' => 'https://picsum.photos/seed/ibox3/400/200' ], 'title' => '24/7 Support', 'description' => 'Always here to help.' ] ) ],
], $b++ ) );

// Counter
array_push( $e, ..._show( 'Counter', [
    [ _w( 'elementskey_counter', [ 'counter_number' => 2500, 'counter_suffix' => '+', 'counter_title' => 'Happy Customers', 'number_color' => '#4361ee' ] ) ],
    [ _w( 'elementskey_counter', [ 'counter_number' => 150, 'counter_suffix' => '+', 'counter_title' => 'Projects Done', 'number_color' => '#7209b7' ] ) ],
    [ _w( 'elementskey_counter', [ 'counter_number' => 99, 'counter_suffix' => '%', 'counter_title' => 'Satisfaction', 'number_color' => '#10b981' ] ) ],
], $b++ ) );

// Spacer
array_push( $e, ..._show( 'Spacer', [
    [ _w( 'elementskey_spacer', [ 'space' => [ 'size' => 50, 'unit' => 'px' ] ] ) ],
    [ _w( 'elementskey_spacer', [ 'space' => [ 'size' => 80, 'unit' => 'px' ] ] ) ],
    [ _w( 'elementskey_spacer', [ 'space' => [ 'size' => 30, 'unit' => 'px' ] ] ) ],
], $b++ ) );

// Divider
array_push( $e, ..._show( 'Divider', [
    [ _w( 'elementskey_divider', [ 'divider_style' => 'solid', 'divider_color' => '#4361ee', 'divider_width' => [ 'size' => 50, 'unit' => '%' ] ] ) ],
    [ _w( 'elementskey_divider', [ 'divider_style' => 'dashed', 'divider_color' => '#ef4444', 'divider_width' => [ 'size' => 70, 'unit' => '%' ] ] ) ],
    [ _w( 'elementskey_divider', [ 'divider_style' => 'dotted', 'divider_color' => '#10b981', 'divider_width' => [ 'size' => 40, 'unit' => '%' ] ] ) ],
], $b++ ) );

// Accordion
array_push( $e, ..._show( 'Accordion', [
    [ _w( 'elementskey_accordion', [ 'accordion_items' => [
        [ 'acc_title' => 'What is ElementsKey?', 'acc_content' => 'A powerful Elementor addon with 73 widgets.', 'acc_active' => 'yes' ],
        [ 'acc_title' => 'Do I need Elementor Pro?', 'acc_content' => 'No! It works with the free version.' ],
        [ 'acc_title' => 'Can I use it on client sites?', 'acc_content' => 'Yes, unlimited sites.' ],
    ], 'show_icon' => 'yes' ] ) ],
    [ _w( 'elementskey_accordion', [ 'accordion_items' => [
        [ 'acc_title' => 'How to install?', 'acc_content' => 'Upload and activate.', 'acc_active' => 'yes' ],
        [ 'acc_title' => 'Translation ready?', 'acc_content' => 'Yes, text domain: elementskey.' ],
        [ 'acc_title' => 'Any theme?', 'acc_content' => 'Yes, all themes.' ],
    ] ] ) ],
    [ _w( 'elementskey_accordion', [ 'accordion_items' => [
        [ 'acc_title' => 'Performance', 'acc_content' => 'Lightweight and fast.', 'acc_active' => 'yes' ],
        [ 'acc_title' => 'Updates', 'acc_content' => 'Regular updates.' ],
        [ 'acc_title' => 'Support', 'acc_content' => 'GitHub issues.' ],
    ] ] ) ],
], $b++ ) );

// Tabs
array_push( $e, ..._show( 'Tabs', [
    [ _w( 'elementskey_tabs', [ 'tabs' => [
        [ 'tab_title' => 'Overview', 'tab_content' => 'Lightweight Elementor addon with 73 custom widgets.' ],
        [ 'tab_title' => 'Features', 'tab_content' => 'Loop Grid, Theme Builder, Widget Manager.' ],
        [ 'tab_title' => 'Reviews', 'tab_content' => 'Rated 5 stars!' ],
    ] ] ) ],
    [ _w( 'elementskey_tabs', [ 'tabs' => [
        [ 'tab_title' => 'Design', 'tab_content' => 'Beautiful modern designs.' ],
        [ 'tab_title' => 'Speed', 'tab_content' => 'Zero bloat.' ],
        [ 'tab_title' => 'Support', 'tab_content' => 'GitHub support.' ],
    ], 'tab_position' => 'left' ] ) ],
    [ _w( 'elementskey_tabs', [ 'tabs' => [
        [ 'tab_title' => 'Free', 'tab_content' => 'Works with Elementor free.' ],
        [ 'tab_title' => 'Pro', 'tab_content' => 'No Pro needed.' ],
    ] ] ) ],
], $b++ ) );

// Testimonial
array_push( $e, ..._show( 'Testimonial', [
    [ _w( 'elementskey_testimonial', [ 'testimonial_quote' => 'This plugin transformed how we build pages.', 'testimonial_author' => 'Sarah Johnson', 'testimonial_role' => 'CEO, TechCorp', 'testimonial_rating' => '5' ] ) ],
    [ _w( 'elementskey_testimonial', [ 'testimonial_quote' => 'Best Elementor addon. Highly recommended!', 'testimonial_author' => 'Mike Rodriguez', 'testimonial_role' => 'Developer', 'testimonial_rating' => '4' ] ) ],
    [ _w( 'elementskey_testimonial', [ 'testimonial_quote' => 'Clean code, great performance.', 'testimonial_author' => 'Anna Kim', 'testimonial_role' => 'Designer', 'testimonial_rating' => '5' ] ) ],
], $b++ ) );

// Blockquote
array_push( $e, ..._show( 'Blockquote', [
    [ _w( 'elementskey_blockquote', [ 'blockquote_content' => 'Design is not just what it looks like. Design is how it works.', 'blockquote_author' => 'Steve Jobs' ] ) ],
    [ _w( 'elementskey_blockquote', [ 'blockquote_content' => 'Simplicity is the ultimate sophistication.', 'blockquote_author' => 'Leonardo da Vinci' ] ) ],
    [ _w( 'elementskey_blockquote', [ 'blockquote_content' => 'Code is like humor. When you explain it, it is bad.', 'blockquote_author' => 'Cory House' ] ) ],
], $b++ ) );

// Call to Action
array_push( $e, ..._show( 'Call to Action', [
    [ _w( 'elementskey_call_to_action', [ 'cta_title' => 'Ready to Get Started?', 'cta_description' => 'Join 10,000+ happy customers.', 'cta_button_text' => 'Get Started Free' ] ) ],
    [ _w( 'elementskey_call_to_action', [ 'cta_title' => 'Build Something Amazing', 'cta_description' => '73 widgets at your fingertips.', 'cta_button_text' => 'Explore Widgets' ] ) ],
    [ _w( 'elementskey_call_to_action', [ 'cta_title' => 'Need Help?', 'cta_description' => 'Our support team is ready.', 'cta_button_text' => 'Contact Support' ] ) ],
], $b++ ) );

// Flip Box
array_push( $e, ..._show( 'Flip Box', [
    [ _w( 'elementskey_flip_box', [ 'front_title' => 'Hover Me!', 'front_description' => 'Click to see the back', 'back_title' => 'Surprise!', 'back_description' => 'Back content here.', 'front_icon' => [ 'value' => 'fas fa-sync-alt', 'library' => 'fa-solid' ] ] ) ],
    [ _w( 'elementskey_flip_box', [ 'front_title' => 'Features', 'front_description' => 'See what we offer', 'back_title' => '73 Widgets', 'back_description' => 'All included.', 'front_icon' => [ 'value' => 'fas fa-cog', 'library' => 'fa-solid' ] ] ) ],
    [ _w( 'elementskey_flip_box', [ 'front_title' => 'About Us', 'front_description' => 'Learn more', 'back_title' => 'Our Story', 'back_description' => '12 years experience.', 'front_icon' => [ 'value' => 'fas fa-info-circle', 'library' => 'fa-solid' ] ] ) ],
], $b++ ) );

// Price Table
array_push( $e, ..._show( 'Price Table', [
    [ _w( 'elementskey_price_table', [ 'pt_plan_name' => 'Starter', 'pt_price' => '$9', 'pt_period' => '/mo', 'pt_features' => [ [ 'pt_feature_text' => '5 Projects', 'pt_feature_included' => 'yes' ], [ 'pt_feature_text' => '10GB Storage', 'pt_feature_included' => 'yes' ] ], 'pt_button_text' => 'Choose Plan' ] ) ],
    [ _w( 'elementskey_price_table', [ 'pt_plan_name' => 'Professional', 'pt_price' => '$29', 'pt_period' => '/mo', 'pt_features' => [ [ 'pt_feature_text' => 'Unlimited Projects', 'pt_feature_included' => 'yes' ], [ 'pt_feature_text' => 'Priority Support', 'pt_feature_included' => 'yes' ] ], 'pt_button_text' => 'Choose Plan', 'pt_highlighted' => 'yes' ] ) ],
    [ _w( 'elementskey_price_table', [ 'pt_plan_name' => 'Enterprise', 'pt_price' => '$99', 'pt_period' => '/mo', 'pt_features' => [ [ 'pt_feature_text' => 'Everything in Pro', 'pt_feature_included' => 'yes' ] ], 'pt_button_text' => 'Contact Sales' ] ) ],
], $b++ ) );

// Price List
array_push( $e, ..._show( 'Price List', [
    [ _w( 'elementskey_price_list', [ 'price_list_items' => [ [ 'pl_title' => 'Web Design', 'pl_price' => '$1,200', 'pl_description' => 'Custom website' ], [ 'pl_title' => 'SEO', 'pl_price' => '$500', 'pl_description' => 'Full SEO' ], [ 'pl_title' => 'Maintenance', 'pl_price' => '$99/mo', 'pl_description' => 'Updates' ] ] ] ) ],
    [ _w( 'elementskey_price_list', [ 'price_list_items' => [ [ 'pl_title' => 'Logo Design', 'pl_price' => '$299', 'pl_description' => 'Brand identity' ], [ 'pl_title' => 'Business Card', 'pl_price' => '$49', 'pl_description' => 'Premium cards' ] ] ] ) ],
    [ _w( 'elementskey_price_list', [ 'price_list_items' => [ [ 'pl_title' => 'Monthly', 'pl_price' => '$199/mo', 'pl_description' => 'All inclusive' ], [ 'pl_title' => 'Annual', 'pl_price' => '$1,999/yr', 'pl_description' => 'Save 2 months', 'pl_featured' => 'yes' ] ] ] ) ],
], $b++ ) );

// Data Table
array_push( $e, ..._show( 'Data Table', [
    [ _w( 'elementskey_data_table', [ 'table_title' => 'Widget Comparison', 'table_rows' => [ [ 'row_label' => 'Widgets', 'row_value' => '73' ], [ 'row_label' => 'Categories', 'row_value' => '4' ], [ 'row_label' => 'Performance', 'row_value' => 'Lightweight' ] ] ] ) ],
    [ _w( 'elementskey_data_table', [ 'table_title' => 'Plan Features', 'table_rows' => [ [ 'row_label' => 'Price', 'row_value' => 'Free' ], [ 'row_label' => 'Support', 'row_value' => 'GitHub' ] ] ] ) ],
    [ _w( 'elementskey_data_table', [ 'table_title' => 'System Info', 'table_rows' => [ [ 'row_label' => 'PHP', 'row_value' => '7.4+' ], [ 'row_label' => 'WordPress', 'row_value' => '6.0+' ] ] ] ) ],
], $b++ ) );

// Feature Comparison Table
array_push( $e, ..._show( 'Feature Comparison Table', [
    [ _w( 'elementskey_feature_comparison_table', [ 'table_title' => 'ElementsKey vs Others', 'rows' => [ [ 'feature_text' => '73 Widgets', 'value_type' => 'icon', 'value_icon' => 'fas fa-check' ], [ 'feature_text' => 'Theme Builder', 'value_type' => 'icon', 'value_icon' => 'fas fa-check' ], [ 'feature_text' => 'Loop Grid', 'value_type' => 'icon', 'value_icon' => 'fas fa-check' ] ] ] ) ],
    [ _w( 'elementskey_feature_comparison_table', [ 'table_title' => 'Free vs Pro', 'rows' => [ [ 'feature_text' => 'All 73 Widgets', 'value_type' => 'icon', 'value_icon' => 'fas fa-check' ], [ 'feature_text' => 'Header/Footer', 'value_type' => 'icon', 'value_icon' => 'fas fa-check' ] ] ] ) ],
    [ _w( 'elementskey_feature_comparison_table', [ 'table_title' => 'Compatibility', 'rows' => [ [ 'feature_text' => 'Any Theme', 'value_type' => 'icon', 'value_icon' => 'fas fa-check' ], [ 'feature_text' => 'Elementor Free', 'value_type' => 'icon', 'value_icon' => 'fas fa-check' ] ] ] ) ],
], $b++ ) );

// Icon List
array_push( $e, ..._show( 'Icon List', [
    [ _w( 'elementskey_icon_list', [ 'icon_list' => [ [ 'list_text' => 'Easy to install', 'list_icon' => 'fas fa-check-circle' ], [ 'list_text' => '73 widgets', 'list_icon' => 'fas fa-check-circle' ], [ 'list_text' => 'Any theme', 'list_icon' => 'fas fa-check-circle' ] ] ] ) ],
    [ _w( 'elementskey_icon_list', [ 'icon_list' => [ [ 'list_text' => 'Lightweight', 'list_icon' => 'fas fa-bolt' ], [ 'list_text' => 'Responsive', 'list_icon' => 'fas fa-mobile-alt' ], [ 'list_text' => 'SEO friendly', 'list_icon' => 'fas fa-search' ] ] ] ) ],
    [ _w( 'elementskey_icon_list', [ 'icon_list' => [ [ 'list_text' => '24/7 support', 'list_icon' => 'fas fa-headset' ], [ 'list_text' => 'Regular updates', 'list_icon' => 'fas fa-sync' ] ] ] ) ],
], $b++ ) );

// Progress Bar
array_push( $e, ..._show( 'Progress Bar', [
    [ _w( 'elementskey_progress_bar', [ 'bars' => [ [ 'title' => 'HTML/CSS', 'percentage' => [ 'size' => 90 ] ] ] ] ) ],
    [ _w( 'elementskey_progress_bar', [ 'bars' => [ [ 'title' => 'JavaScript', 'percentage' => [ 'size' => 75 ] ] ] ] ) ],
    [ _w( 'elementskey_progress_bar', [ 'bars' => [ [ 'title' => 'PHP', 'percentage' => [ 'size' => 60 ] ] ] ] ) ],
], $b++ ) );

// Progress Tracker
array_push( $e, ..._show( 'Progress Tracker', [
    [ _w( 'elementskey_progress_tracker', [ 'tracker_title' => 'Order Progress' ] ) ],
    [ _w( 'elementskey_progress_tracker', [ 'tracker_title' => 'Course Progress' ] ) ],
    [ _w( 'elementskey_progress_tracker', [ 'tracker_title' => 'Project Status' ] ) ],
], $b++ ) );

// Star Rating
array_push( $e, ..._show( 'Star Rating', [
    [ _w( 'elementskey_star_rating', [ 'rating_value' => [ 'size' => 5 ], 'rating_scale' => '5', 'show_number' => 'yes' ] ) ],
    [ _w( 'elementskey_star_rating', [ 'rating_value' => [ 'size' => 4.5 ], 'rating_scale' => '5', 'show_number' => 'yes' ] ) ],
    [ _w( 'elementskey_star_rating', [ 'rating_value' => [ 'size' => 3 ], 'rating_scale' => '5', 'show_number' => 'yes' ] ) ],
], $b++ ) );

// Animated Headline
array_push( $e, ..._show( 'Animated Headline', [
    [ _w( 'elementskey_animated_headline', [ 'animated_headline' => 'We build', 'headline_style' => 'rotating', 'rotating_text' => [ 'websites', 'apps', 'stores', 'brands' ] ] ) ],
    [ _w( 'elementskey_animated_headline', [ 'animated_headline' => 'Learn', 'headline_style' => 'rotating', 'rotating_text' => [ 'HTML', 'CSS', 'JavaScript', 'PHP' ] ] ) ],
    [ _w( 'elementskey_animated_headline', [ 'animated_headline' => 'Create', 'headline_style' => 'rotating', 'rotating_text' => [ 'designs', 'experiences', 'solutions' ] ] ) ],
], $b++ ) );

// Countdown
array_push( $e, ..._show( 'Countdown', [
    [ _w( 'elementskey_countdown', [ 'due_date' => gmdate( 'Y-m-d\TH:i:s', strtotime( '+7 days' ) ), 'show_labels' => 'yes' ] ) ],
    [ _w( 'elementskey_countdown', [ 'due_date' => gmdate( 'Y-m-d\TH:i:s', strtotime( '+30 days' ) ), 'show_labels' => 'yes' ] ) ],
    [ _w( 'elementskey_countdown', [ 'due_date' => gmdate( 'Y-m-d\TH:i:s', strtotime( '+90 days' ) ), 'show_labels' => 'yes' ] ) ],
], $b++ ) );

// Code Highlight
array_push( $e, ..._show( 'Code Highlight', [
    [ _w( 'elementskey_code_highlight', [ 'code_language' => 'javascript', 'code_content' => 'const greeting = "Hello World";\nconsole.log(greeting);', 'show_copy_btn' => 'yes' ] ) ],
    [ _w( 'elementskey_code_highlight', [ 'code_language' => 'php', 'code_content' => '<?php\necho "Hello";\n?>', 'show_copy_btn' => 'yes' ] ) ],
    [ _w( 'elementskey_code_highlight', [ 'code_language' => 'css', 'code_content' => '.btn {\n  background: #4361ee;\n  color: white;\n}', 'show_copy_btn' => 'yes' ] ) ],
], $b++ ) );

// Table of Content
array_push( $e, ..._show( 'Table of Content', [
    [ _w( 'elementskey_table_of_content', [ 'toc_title' => 'Table of Contents' ] ) ],
    [ _w( 'elementskey_table_of_content', [ 'toc_title' => 'On This Page' ] ) ],
    [ _w( 'elementskey_table_of_content', [ 'toc_title' => 'Navigation' ] ) ],
], $b++ ) );

// Reviews
array_push( $e, ..._show( 'Reviews', [
    [ _w( 'elementskey_reviews', [ 'reviews' => [ [ 'rv_name' => 'Mike R.', 'rv_role' => 'Developer', 'rv_comment' => 'Love this addon!', 'rv_rating' => 5 ], [ 'rv_name' => 'Anna K.', 'rv_role' => 'Designer', 'rv_comment' => 'Great widgets.', 'rv_rating' => 4 ] ] ] ) ],
    [ _w( 'elementskey_reviews', [ 'reviews' => [ [ 'rv_name' => 'John D.', 'rv_role' => 'Owner', 'rv_comment' => 'Best addon.', 'rv_rating' => 5 ], [ 'rv_name' => 'Lisa M.', 'rv_role' => 'Marketer', 'rv_comment' => 'Easy and fast.', 'rv_rating' => 5 ] ] ] ) ],
    [ _w( 'elementskey_reviews', [ 'reviews' => [ [ 'rv_name' => 'Tom B.', 'rv_role' => 'Freelancer', 'rv_comment' => 'Saves hours.', 'rv_rating' => 5 ] ] ] ) ],
], $b++ ) );

// Testimonial Carousel
array_push( $e, ..._show( 'Testimonial Carousel', [
    [ _w( 'elementskey_testimonial_carousel', [ 'testimonials' => [ [ 'tc_name' => 'Sarah', 'tc_role' => 'CEO', 'tc_content' => 'Transformed our workflow.', 'tc_rating' => 5 ], [ 'tc_name' => 'Mike', 'tc_role' => 'Dev', 'tc_content' => 'Best addon.', 'tc_rating' => 4 ], [ 'tc_name' => 'Anna', 'tc_role' => 'Designer', 'tc_content' => 'Beautiful widgets.', 'tc_rating' => 5 ] ] ] ) ],
    [ _w( 'elementskey_testimonial_carousel', [ 'testimonials' => [ [ 'tc_name' => 'John', 'tc_role' => 'Director', 'tc_content' => 'Highly recommended.', 'tc_rating' => 5 ], [ 'tc_name' => 'Emily', 'tc_role' => 'Manager', 'tc_content' => 'Excellent support.', 'tc_rating' => 5 ] ] ] ) ],
    [ _w( 'elementskey_testimonial_carousel', [ 'testimonials' => [ [ 'tc_name' => 'Chris', 'tc_role' => 'Founder', 'tc_content' => 'Game changer.', 'tc_rating' => 5 ] ] ] ) ],
], $b++ ) );

// Hotspot
array_push( $e, ..._show( 'Hotspot', [
    [ _w( 'elementskey_hotspot', [ 'hotspot_image' => [ 'url' => 'https://picsum.photos/seed/h1/600/400' ], 'hotspot_markers' => [ [ 'hotspot_left' => 30, 'hotspot_top' => 40, 'hotspot_label' => 'A', 'hotspot_description' => 'Cloud sync.' ], [ 'hotspot_left' => 70, 'hotspot_top' => 60, 'hotspot_label' => 'B', 'hotspot_description' => 'Analytics.' ] ] ] ) ],
    [ _w( 'elementskey_hotspot', [ 'hotspot_image' => [ 'url' => 'https://picsum.photos/seed/h2/600/400' ], 'hotspot_markers' => [ [ 'hotspot_left' => 50, 'hotspot_top' => 30, 'hotspot_label' => 'Click', 'hotspot_description' => 'Action.' ] ] ] ) ],
    [ _w( 'elementskey_hotspot', [ 'hotspot_image' => [ 'url' => 'https://picsum.photos/seed/h3/600/400' ], 'hotspot_markers' => [ [ 'hotspot_left' => 20, 'hotspot_top' => 50, 'hotspot_label' => 'Info', 'hotspot_description' => 'Details.' ], [ 'hotspot_left' => 80, 'hotspot_top' => 30, 'hotspot_label' => 'Go', 'hotspot_description' => 'Start.' ] ] ] ) ],
], $b++ ) );

// Basic Gallery
array_push( $e, ..._show( 'Basic Gallery', [
    [ _w( 'elementskey_basic_gallery', [ 'galleries' => [ [ 'gallery_image' => [ 'url' => 'https://picsum.photos/seed/g1/400/300' ], 'gallery_caption' => 'Mountain' ], [ 'gallery_image' => [ 'url' => 'https://picsum.photos/seed/g2/400/300' ], 'gallery_caption' => 'Ocean' ], [ 'gallery_image' => [ 'url' => 'https://picsum.photos/seed/g3/400/300' ], 'gallery_caption' => 'City' ] ], 'columns' => 3 ] ) ],
    [ _w( 'elementskey_basic_gallery', [ 'galleries' => [ [ 'gallery_image' => [ 'url' => 'https://picsum.photos/seed/g4/400/300' ] ], [ 'gallery_image' => [ 'url' => 'https://picsum.photos/seed/g5/400/300' ] ], [ 'gallery_image' => [ 'url' => 'https://picsum.photos/seed/g6/400/300' ] ] ], 'columns' => 3 ] ) ],
    [ _w( 'elementskey_basic_gallery', [ 'galleries' => [ [ 'gallery_image' => [ 'url' => 'https://picsum.photos/seed/g7/400/300' ] ], [ 'gallery_image' => [ 'url' => 'https://picsum.photos/seed/g8/400/300' ] ] ], 'columns' => 2 ] ) ],
], $b++ ) );

// Image Carousel
array_push( $e, ..._show( 'Image Carousel', [
    [ _w( 'elementskey_image_carousel', [ 'carousel_images' => [ [ 'carousel_image' => [ 'url' => 'https://picsum.photos/seed/ic1/600/400' ] ], [ 'carousel_image' => [ 'url' => 'https://picsum.photos/seed/ic2/600/400' ] ], [ 'carousel_image' => [ 'url' => 'https://picsum.photos/seed/ic3/600/400' ] ] ], 'slides_per_view' => 2 ] ) ],
    [ _w( 'elementskey_image_carousel', [ 'carousel_images' => [ [ 'carousel_image' => [ 'url' => 'https://picsum.photos/seed/ic4/600/400' ] ], [ 'carousel_image' => [ 'url' => 'https://picsum.photos/seed/ic5/600/400' ] ], [ 'carousel_image' => [ 'url' => 'https://picsum.photos/seed/ic6/600/400' ] ] ], 'slides_per_view' => 3 ] ) ],
    [ _w( 'elementskey_image_carousel', [ 'carousel_images' => [ [ 'carousel_image' => [ 'url' => 'https://picsum.photos/seed/ic7/600/400' ] ], [ 'carousel_image' => [ 'url' => 'https://picsum.photos/seed/ic8/600/400' ] ] ], 'slides_per_view' => 2 ] ) ],
], $b++ ) );

// Media Carousel
array_push( $e, ..._show( 'Media Carousel', [
    [ _w( 'elementskey_media_carousel', [ 'media_items' => [ [ 'mc_image' => [ 'url' => 'https://picsum.photos/seed/m1/600/400' ], 'mc_title' => 'One' ], [ 'mc_image' => [ 'url' => 'https://picsum.photos/seed/m2/600/400' ], 'mc_title' => 'Two' ], [ 'mc_image' => [ 'url' => 'https://picsum.photos/seed/m3/600/400' ], 'mc_title' => 'Three' ] ] ] ) ],
    [ _w( 'elementskey_media_carousel', [ 'media_items' => [ [ 'mc_image' => [ 'url' => 'https://picsum.photos/seed/m4/600/400' ], 'mc_title' => 'A' ], [ 'mc_image' => [ 'url' => 'https://picsum.photos/seed/m5/600/400' ], 'mc_title' => 'B' ] ] ] ) ],
    [ _w( 'elementskey_media_carousel', [ 'media_items' => [ [ 'mc_image' => [ 'url' => 'https://picsum.photos/seed/m6/600/400' ] ], [ 'mc_image' => [ 'url' => 'https://picsum.photos/seed/m7/600/400' ] ], [ 'mc_image' => [ 'url' => 'https://picsum.photos/seed/m8/600/400' ] ] ] ] ) ],
], $b++ ) );

// Carousel
array_push( $e, ..._show( 'Carousel', [
    [ _w( 'elementskey_swiper_carousel', [] ) ],
    [ _w( 'elementskey_swiper_carousel', [] ) ],
    [ _w( 'elementskey_swiper_carousel', [] ) ],
], $b++ ) );

// Slides
array_push( $e, ..._show( 'Slides', [
    [ _w( 'elementskey_slides', [ 'slides' => [ [ 'slide_title' => 'Welcome', 'slide_subtitle' => '73 widgets', 'slide_btn_text' => 'Explore' ] ] ] ) ],
    [ _w( 'elementskey_slides', [ 'slides' => [ [ 'slide_title' => 'Build Pages', 'slide_subtitle' => 'With Elementor', 'slide_btn_text' => 'Start' ] ] ] ) ],
    [ _w( 'elementskey_slides', [ 'slides' => [ [ 'slide_title' => 'Lightweight', 'slide_subtitle' => 'Zero bloat', 'slide_btn_text' => 'Learn' ] ] ] ) ],
], $b++ ) );

// Off-Canvas
array_push( $e, ..._show( 'Off-Canvas', [
    [ _w( 'elementskey_off_canvas', [ 'offcanvas_title' => 'Menu', 'trigger_text' => 'Open Menu' ] ) ],
    [ _w( 'elementskey_off_canvas', [ 'offcanvas_title' => 'Cart', 'trigger_text' => 'View Cart' ] ) ],
    [ _w( 'elementskey_off_canvas', [ 'offcanvas_title' => 'Settings', 'trigger_text' => 'Open Settings' ] ) ],
], $b++ ) );

// Link in Bio
array_push( $e, ..._show( 'Link in Bio', [
    [ _w( 'elementskey_link_in_bio', [ 'bio_name' => '@brand', 'bio_text' => 'Creator', 'bio_links' => [ [ 'bio_link_label' => 'Website', 'bio_link_url' => [ 'url' => '#' ] ], [ 'bio_link_label' => 'Blog', 'bio_link_url' => [ 'url' => '#' ] ] ] ] ) ],
    [ _w( 'elementskey_link_in_bio', [ 'bio_name' => '@dev', 'bio_text' => 'Developer', 'bio_links' => [ [ 'bio_link_label' => 'GitHub', 'bio_link_url' => [ 'url' => '#' ] ], [ 'bio_link_label' => 'LinkedIn', 'bio_link_url' => [ 'url' => '#' ] ] ] ] ) ],
    [ _w( 'elementskey_link_in_bio', [ 'bio_name' => '@agency', 'bio_text' => 'Agency', 'bio_links' => [ [ 'bio_link_label' => 'Portfolio', 'bio_link_url' => [ 'url' => '#' ] ], [ 'bio_link_label' => 'Contact', 'bio_link_url' => [ 'url' => '#' ] ] ] ] ) ],
], $b++ ) );

// Share It
array_push( $e, ..._show( 'Share It', [
    [ _w( 'elementskey_share_it', [] ) ],
    [ _w( 'elementskey_share_it', [] ) ],
    [ _w( 'elementskey_share_it', [] ) ],
], $b++ ) );

// Google Maps
array_push( $e, ..._show( 'Google Maps', [
    [ _w( 'elementskey_google_maps', [ 'map_address' => 'New York, USA', 'map_zoom' => 12 ] ) ],
    [ _w( 'elementskey_google_maps', [ 'map_address' => 'London, UK', 'map_zoom' => 12 ] ) ],
    [ _w( 'elementskey_google_maps', [ 'map_address' => 'Tokyo, Japan', 'map_zoom' => 12 ] ) ],
], $b++ ) );

_save( 82, $e, 'Widgets — Content' );

/* ── Page 2: widgets-media (ID 84) ── remaining 37 widgets ────── */

$b = 0;
$e = [];

$e[] = _cols( [ [
    _h( 'Media, Social, Forms & Theme Widgets', 'h1' ),
    _w( 'text-editor', [ 'editor' => '<p style="color:#6b7280">Media, social, forms, and theme widgets with 3 variations each.</p>', 'align' => 'center' ] ),
] ], [
    'background_background' => 'gradient', 'background_color' => '#0f172a', 'background_color_b' => '#7c3aed',
    'padding' => [ 'unit' => 'px', 'top' => 80, 'right' => 20, 'bottom' => 80, 'left' => 20, 'isLinked' => false ],
] );

// Video
array_push( $e, ..._show( 'Video', [
    [ _w( 'elementskey_video', [ 'video_type' => 'youtube', 'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ' ] ) ],
    [ _w( 'elementskey_video', [ 'video_type' => 'youtube', 'youtube_url' => 'https://www.youtube.com/watch?v=8aGhZQkoFbQ' ] ) ],
    [ _w( 'elementskey_video', [ 'video_type' => 'youtube', 'youtube_url' => 'https://www.youtube.com/watch?v=kXYiU_JCYtU' ] ) ],
], $b++ ) );

// Video Playlist
array_push( $e, ..._show( 'Video Playlist', [
    [ _w( 'elementskey_video_playlist', [ 'playlist' => [ [ 'vp_title' => 'Intro', 'vp_source' => 'youtube', 'vp_url' => [ 'url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ' ] ], [ 'vp_title' => 'Getting Started', 'vp_source' => 'youtube', 'vp_url' => [ 'url' => 'https://www.youtube.com/watch?v=8aGhZQkoFbQ' ] ], [ 'vp_title' => 'Advanced', 'vp_source' => 'youtube', 'vp_url' => [ 'url' => 'https://www.youtube.com/watch?v=kXYiU_JCYtU' ] ] ] ] ) ],
    [ _w( 'elementskey_video_playlist', [ 'playlist' => [ [ 'vp_title' => 'Tutorial 1', 'vp_source' => 'youtube', 'vp_url' => [ 'url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ' ] ], [ 'vp_title' => 'Tutorial 2', 'vp_source' => 'youtube', 'vp_url' => [ 'url' => 'https://www.youtube.com/watch?v=8aGhZQkoFbQ' ] ] ] ] ) ],
    [ _w( 'elementskey_video_playlist', [ 'playlist' => [ [ 'vp_title' => 'Demo', 'vp_source' => 'youtube', 'vp_url' => [ 'url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ' ] ] ] ] ) ],
], $b++ ) );

// Lottie
array_push( $e, ..._show( 'Lottie', [
    [ _w( 'elementskey_lottie', [ 'lottie_url' => [ 'url' => 'https://assets2.lottiefiles.com/packages/lf20_usmfx6bp.json' ] ] ) ],
    [ _w( 'elementskey_lottie', [ 'lottie_url' => [ 'url' => 'https://assets2.lottiefiles.com/packages/lf20_usmfx6bp.json' ] ] ) ],
    [ _w( 'elementskey_lottie', [ 'lottie_url' => [ 'url' => 'https://assets2.lottiefiles.com/packages/lf20_usmfx6bp.json' ] ] ) ],
], $b++ ) );

// SoundCloud
array_push( $e, ..._show( 'SoundCloud', [
    [ _w( 'elementskey_soundcloud', [ 'soundcloud_url' => 'https://soundcloud.com/skrillex/scary-monsters-and-nice-sprites' ] ) ],
    [ _w( 'elementskey_soundcloud', [ 'soundcloud_url' => 'https://soundcloud.com/skrillex/scary-monsters-and-nice-sprites' ] ) ],
    [ _w( 'elementskey_soundcloud', [ 'soundcloud_url' => 'https://soundcloud.com/skrillex/scary-monsters-and-nice-sprites' ] ) ],
], $b++ ) );

// Facebook Embed
array_push( $e, ..._show( 'Facebook Embed', [
    [ _w( 'elementskey_facebook_embed', [ 'url' => 'https://www.facebook.com/facebook/videos/10153231379906729/' ] ) ],
    [ _w( 'elementskey_facebook_embed', [ 'url' => 'https://www.facebook.com/facebook/videos/10153231379906729/' ] ) ],
    [ _w( 'elementskey_facebook_embed', [ 'url' => 'https://www.facebook.com/facebook/videos/10153231379906729/' ] ) ],
], $b++ ) );

// Facebook Page
array_push( $e, ..._show( 'Facebook Page', [
    [ _w( 'elementskey_facebook_page', [ 'url' => 'https://www.facebook.com/facebook' ] ) ],
    [ _w( 'elementskey_facebook_page', [ 'url' => 'https://www.facebook.com/facebook' ] ) ],
    [ _w( 'elementskey_facebook_page', [ 'url' => 'https://www.facebook.com/facebook' ] ) ],
], $b++ ) );

// Social Icons
array_push( $e, ..._show( 'Social Icons', [
    [ _w( 'elementskey_social_icons', [ 'social_icons' => [ [ 'social_type' => 'facebook', 'social_link' => [ 'url' => '#' ] ], [ 'social_type' => 'twitter', 'social_link' => [ 'url' => '#' ] ], [ 'social_type' => 'instagram', 'social_link' => [ 'url' => '#' ] ], [ 'social_type' => 'linkedin', 'social_link' => [ 'url' => '#' ] ], [ 'social_type' => 'youtube', 'social_link' => [ 'url' => '#' ] ] ] ] ) ],
    [ _w( 'elementskey_social_icons', [ 'social_icons' => [ [ 'social_type' => 'facebook', 'social_link' => [ 'url' => '#' ] ], [ 'social_type' => 'twitter', 'social_link' => [ 'url' => '#' ] ], [ 'social_type' => 'instagram', 'social_link' => [ 'url' => '#' ] ] ] ] ) ],
    [ _w( 'elementskey_social_icons', [ 'social_icons' => [ [ 'social_type' => 'linkedin', 'social_link' => [ 'url' => '#' ] ], [ 'social_type' => 'youtube', 'social_link' => [ 'url' => '#' ] ], [ 'social_type' => 'github', 'social_link' => [ 'url' => '#' ] ] ] ] ) ],
], $b++ ) );

// Form
array_push( $e, ..._show( 'Form', [
    [ _w( 'elementskey_form', [ 'form_fields' => [ [ 'field_type' => 'text', 'field_label' => 'Name', 'field_name' => 'name', 'field_required' => 'yes' ], [ 'field_type' => 'email', 'field_label' => 'Email', 'field_name' => 'email', 'field_required' => 'yes' ], [ 'field_type' => 'textarea', 'field_label' => 'Message', 'field_name' => 'message' ] ], 'submit_button_text' => 'Send Message' ] ) ],
    [ _w( 'elementskey_form', [ 'form_fields' => [ [ 'field_type' => 'text', 'field_label' => 'Full Name', 'field_name' => 'fullname', 'field_required' => 'yes' ], [ 'field_type' => 'email', 'field_label' => 'Email', 'field_name' => 'email', 'field_required' => 'yes' ], [ 'field_type' => 'tel', 'field_label' => 'Phone', 'field_name' => 'phone' ], [ 'field_type' => 'textarea', 'field_label' => 'Details', 'field_name' => 'details' ] ], 'submit_button_text' => 'Submit' ] ) ],
    [ _w( 'elementskey_form', [ 'form_fields' => [ [ 'field_type' => 'email', 'field_label' => 'Email', 'field_name' => 'email', 'field_required' => 'yes' ] ], 'submit_button_text' => 'Subscribe' ] ) ],
], $b++ ) );

// Login
array_push( $e, ..._show( 'Login', [
    [ _w( 'elementskey_login', [ 'login_title' => 'Welcome Back', 'button_label' => 'Log In' ] ) ],
    [ _w( 'elementskey_login', [ 'login_title' => 'Member Login', 'button_label' => 'Sign In' ] ) ],
    [ _w( 'elementskey_login', [ 'login_title' => 'Access Account', 'button_label' => 'Login' ] ) ],
], $b++ ) );

// Shortcode
array_push( $e, ..._show( 'Shortcode', [
    [ _w( 'elementskey_shortcode', [] ) ],
    [ _w( 'elementskey_shortcode', [] ) ],
    [ _w( 'elementskey_shortcode', [] ) ],
], $b++ ) );

// Facebook Button
array_push( $e, ..._show( 'Facebook Button', [
    [ _w( 'elementskey_facebook_button', [ 'fb_button_layout' => 'standard', 'fb_button_action' => 'like' ] ) ],
    [ _w( 'elementskey_facebook_button', [ 'fb_button_layout' => 'standard', 'fb_button_action' => 'recommend' ] ) ],
    [ _w( 'elementskey_facebook_button', [ 'fb_button_layout' => 'button_count', 'fb_button_action' => 'like' ] ) ],
], $b++ ) );

// Facebook Comments
array_push( $e, ..._show( 'Facebook Comments', [
    [ _w( 'elementskey_facebook_comments', [ 'url' => 'https://www.facebook.com/facebook' ] ) ],
    [ _w( 'elementskey_facebook_comments', [ 'url' => 'https://www.facebook.com/facebook' ] ) ],
    [ _w( 'elementskey_facebook_comments', [ 'url' => 'https://www.facebook.com/facebook' ] ) ],
], $b++ ) );

// Search
array_push( $e, ..._show( 'Search', [
    [ _w( 'elementskey_search', [] ) ],
    [ _w( 'elementskey_search', [] ) ],
    [ _w( 'elementskey_search', [] ) ],
], $b++ ) );

// Taxonomy Filter
array_push( $e, ..._show( 'Taxonomy Filter', [
    [ _w( 'elementskey_taxonomy_filter', [ 'filter_taxonomy' => 'category', 'filter_all_label' => 'All' ] ) ],
    [ _w( 'elementskey_taxonomy_filter', [ 'filter_taxonomy' => 'category', 'filter_all_label' => 'All Posts' ] ) ],
    [ _w( 'elementskey_taxonomy_filter', [ 'filter_taxonomy' => 'post_tag', 'filter_all_label' => 'All Tags' ] ) ],
], $b++ ) );

// Template
array_push( $e, ..._show( 'Template', [
    [ _w( 'elementskey_template', [] ) ],
    [ _w( 'elementskey_template', [] ) ],
    [ _w( 'elementskey_template', [] ) ],
], $b++ ) );

// Menu
array_push( $e, ..._show( 'Menu', [
    [ _w( 'elementskey_menu', [] ) ],
    [ _w( 'elementskey_menu', [] ) ],
    [ _w( 'elementskey_menu', [] ) ],
], $b++ ) );

// WP Menu
array_push( $e, ..._show( 'WordPress Menu', [
    [ _w( 'elementskey_wp_menu', [] ) ],
    [ _w( 'elementskey_wp_menu', [] ) ],
    [ _w( 'elementskey_wp_menu', [] ) ],
], $b++ ) );

// Sitemap
array_push( $e, ..._show( 'Sitemap', [
    [ _w( 'elementskey_sitemap', [] ) ],
    [ _w( 'elementskey_sitemap', [] ) ],
    [ _w( 'elementskey_sitemap', [] ) ],
], $b++ ) );

// Site Logo
array_push( $e, ..._show( 'Site Logo', [
    [ _w( 'elementskey_site_logo', [] ) ],
    [ _w( 'elementskey_site_logo', [] ) ],
    [ _w( 'elementskey_site_logo', [] ) ],
], $b++ ) );

// Site Title
array_push( $e, ..._show( 'Site Title', [
    [ _w( 'elementskey_site_title', [] ) ],
    [ _w( 'elementskey_site_title', [] ) ],
    [ _w( 'elementskey_site_title', [] ) ],
], $b++ ) );

// Page Title
array_push( $e, ..._show( 'Page Title', [
    [ _w( 'elementskey_page_title', [] ) ],
    [ _w( 'elementskey_page_title', [] ) ],
    [ _w( 'elementskey_page_title', [] ) ],
], $b++ ) );

// Post Title
array_push( $e, ..._show( 'Post Title', [
    [ _w( 'elementskey_post_title', [] ) ],
    [ _w( 'elementskey_post_title', [] ) ],
    [ _w( 'elementskey_post_title', [] ) ],
], $b++ ) );

// Post Excerpt
array_push( $e, ..._show( 'Post Excerpt', [
    [ _w( 'elementskey_post_excerpt', [ 'excerpt_length' => 20 ] ) ],
    [ _w( 'elementskey_post_excerpt', [ 'excerpt_length' => 30 ] ) ],
    [ _w( 'elementskey_post_excerpt', [ 'excerpt_length' => 10 ] ) ],
], $b++ ) );

// Featured Image
array_push( $e, ..._show( 'Featured Image', [
    [ _w( 'elementskey_featured_image', [] ) ],
    [ _w( 'elementskey_featured_image', [] ) ],
    [ _w( 'elementskey_featured_image', [] ) ],
], $b++ ) );

// Post Content
array_push( $e, ..._show( 'Post Content', [
    [ _w( 'elementskey_post_content', [] ) ],
    [ _w( 'elementskey_post_content', [] ) ],
    [ _w( 'elementskey_post_content', [] ) ],
], $b++ ) );

// Author Box
array_push( $e, ..._show( 'Author Box', [
    [ _w( 'elementskey_author_box', [] ) ],
    [ _w( 'elementskey_author_box', [] ) ],
    [ _w( 'elementskey_author_box', [] ) ],
], $b++ ) );

// Post Comments
array_push( $e, ..._show( 'Post Comments', [
    [ _w( 'elementskey_post_comments', [] ) ],
    [ _w( 'elementskey_post_comments', [] ) ],
    [ _w( 'elementskey_post_comments', [] ) ],
], $b++ ) );

// Post Navigation
array_push( $e, ..._show( 'Post Navigation', [
    [ _w( 'elementskey_post_navigation', [] ) ],
    [ _w( 'elementskey_post_navigation', [] ) ],
    [ _w( 'elementskey_post_navigation', [] ) ],
], $b++ ) );

// Post Info
array_push( $e, ..._show( 'Post Info', [
    [ _w( 'elementskey_post_info', [ 'post_info_items' => [ [ 'selected_icon' => [ 'value' => 'fas fa-calendar', 'library' => 'fa-solid' ], 'type' => 'post_date' ], [ 'selected_icon' => [ 'value' => 'fas fa-user', 'library' => 'fa-solid' ], 'type' => 'author' ] ] ] ) ],
    [ _w( 'elementskey_post_info', [ 'post_info_items' => [ [ 'selected_icon' => [ 'value' => 'fas fa-folder', 'library' => 'fa-solid' ], 'type' => 'post_terms' ], [ 'selected_icon' => [ 'value' => 'fas fa-comments', 'library' => 'fa-solid' ], 'type' => 'comments' ] ] ] ) ],
    [ _w( 'elementskey_post_info', [ 'post_info_items' => [ [ 'selected_icon' => [ 'value' => 'fas fa-clock', 'library' => 'fa-solid' ], 'type' => 'post_date' ] ] ] ) ],
], $b++ ) );

// Breadcrumbs
array_push( $e, ..._show( 'Breadcrumbs', [
    [ _w( 'elementskey_breadcrumbs', [] ) ],
    [ _w( 'elementskey_breadcrumbs', [] ) ],
    [ _w( 'elementskey_breadcrumbs', [] ) ],
], $b++ ) );

// Archive Title
array_push( $e, ..._show( 'Archive Title', [
    [ _w( 'elementskey_archive_title', [] ) ],
    [ _w( 'elementskey_archive_title', [] ) ],
    [ _w( 'elementskey_archive_title', [] ) ],
], $b++ ) );

// Archive Posts
array_push( $e, ..._show( 'Archive Posts', [
    [ _w( 'elementskey_archive_posts', [ 'posts_per_page' => 3, 'columns' => 3 ] ) ],
    [ _w( 'elementskey_archive_posts', [ 'posts_per_page' => 2, 'columns' => 2 ] ) ],
    [ _w( 'elementskey_archive_posts', [ 'posts_per_page' => 4, 'columns' => 4 ] ) ],
], $b++ ) );

// Posts
array_push( $e, ..._show( 'Posts', [
    [ _w( 'elementskey_posts', [ 'posts_per_page' => 3, 'columns' => 3 ] ) ],
    [ _w( 'elementskey_posts', [ 'posts_per_page' => 2, 'columns' => 2 ] ) ],
    [ _w( 'elementskey_posts', [ 'posts_per_page' => 4, 'columns' => 4 ] ) ],
], $b++ ) );

// Portfolio
array_push( $e, ..._show( 'Portfolio', [
    [ _w( 'elementskey_portfolio', [] ) ],
    [ _w( 'elementskey_portfolio', [] ) ],
    [ _w( 'elementskey_portfolio', [] ) ],
], $b++ ) );

// Loop Grid
array_push( $e, ..._show( 'Loop Grid', [
    [ _w( 'elementskey_loop_grid', [] ) ],
    [ _w( 'elementskey_loop_grid', [] ) ],
    [ _w( 'elementskey_loop_grid', [] ) ],
], $b++ ) );

// Loop Carousel
array_push( $e, ..._show( 'Loop Carousel', [
    [ _w( 'elementskey_loop_carousel', [] ) ],
    [ _w( 'elementskey_loop_carousel', [] ) ],
    [ _w( 'elementskey_loop_carousel', [] ) ],
], $b++ ) );

_save( 84, $e, 'Widgets — Media' );

echo PHP_EOL . 'Done! Both pages populated.' . PHP_EOL;
