<?php
/**
 * Dev tool: Populates all 4 test pages with every widget shown 3 times.
 * Usage: tools\wp.cmd eval-file tools\populate-pages.php
 *
 * Pages:
 *   82 – Widgets — Content
 *   84 – Widgets — Media
 *   86 – Widgets — Social & Forms
 *   88 – Widgets — Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    require_once dirname( __DIR__, 4 ) . '/wp-load.php';
}

if ( ! did_action( 'elementor/loaded' ) ) {
    die( 'Elementor not loaded.' );
}

function _ep_rand_id() {
    return substr( md5( uniqid( mt_rand(), true ) ), 0, 7 );
}

function _ep_widget( $type, $settings = [] ) {
    return [
        'id'         => _ep_rand_id(),
        'elType'     => 'widget',
        'widgetType' => $type,
        'settings'   => $settings,
        'elements'   => [],
        'isInner'    => false,
    ];
}

function _ep_heading( $text, $tag = 'h2', $align = 'left' ) {
    return _ep_widget( 'heading', [
        'title'       => $text,
        'header_size' => $tag,
        'align'       => $align,
        'title_color' => '#111827',
    ] );
}

function _ep_column( $widgets, $size = 100 ) {
    $elements = [];
    foreach ( (array) $widgets as $w ) {
        $elements[] = $w;
    }
    return [
        'id'       => _ep_rand_id(),
        'elType'   => 'column',
        'settings' => [ '_column_size' => $size ],
        'elements' => $elements,
        'isInner'  => false,
    ];
}

function _ep_section( $columns, $settings = [] ) {
    return [
        'id'       => _ep_rand_id(),
        'elType'   => 'section',
        'settings' => array_merge( [
            'layout'        => 'boxed',
            'content_width' => [ 'unit' => 'px', 'size' => 1140 ],
            'gap'           => 'extended',
        ], $settings ),
        'elements' => $columns,
        'isInner'  => false,
    ];
}

function _ep_cols_section( $cols_of_widgets, $settings = [], $column_sizes = [] ) {
    $columns = [];
    foreach ( $cols_of_widgets as $i => $col_widgets ) {
        $size = $column_sizes[ $i ] ?? (int) ( 100 / count( $cols_of_widgets ) );
        $columns[] = _ep_column( $col_widgets, $size );
    }
    return _ep_section( $columns, $settings );
}

function _ep_showcase( $name, $col_widgets, $band = 0 ) {
    $bg  = ( $band % 2 ) ? '#f8fafc' : '#ffffff';
    $pad = [ 'unit' => 'px', 'top' => '60', 'right' => '20', 'bottom' => '60', 'left' => '20', 'isLinked' => false ];

    $header = _ep_cols_section( [ [ _ep_heading( $name ) ] ], [
        'background_background' => 'classic',
        'background_color'      => $bg,
        'padding'               => [ 'unit' => 'px', 'top' => '60', 'right' => '20', 'bottom' => '10', 'left' => '20', 'isLinked' => false ],
    ] );

    $body = _ep_cols_section( $col_widgets, [
        'background_background' => 'classic',
        'background_color'      => $bg,
        'padding'               => [ 'unit' => 'px', 'top' => '20', 'right' => '20', 'bottom' => '60', 'left' => '20', 'isLinked' => false ],
    ] );

    return [ $header, $body ];
}

function _ep_save_page( $page_id, $elements, $title ) {
    $data_json = json_encode( $elements, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
    if ( json_last_error() !== JSON_ERROR_NONE ) {
        echo "JSON ERROR for page $page_id: " . json_last_error_msg() . PHP_EOL;
        return false;
    }

    global $wpdb;

    $wpdb->delete( $wpdb->postmeta, [ 'post_id' => $page_id, 'meta_key' => '_elementor_data' ], [ '%d', '%s' ] );
    $wpdb->insert( $wpdb->postmeta, [ 'post_id' => $page_id, 'meta_key' => '_elementor_data', 'meta_value' => $data_json ], [ '%d', '%s', '%s' ] );

    $wpdb->query( $wpdb->prepare(
        "DELETE FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key IN ('_elementor_edit_mode','_elementor_version','_wp_page_template')",
        $page_id
    ) );
    $wpdb->query( $wpdb->prepare( "INSERT INTO {$wpdb->postmeta} (post_id, meta_key, meta_value) VALUES (%d, '_elementor_edit_mode', 'builder')", $page_id ) );
    $wpdb->query( $wpdb->prepare( "INSERT INTO {$wpdb->postmeta} (post_id, meta_key, meta_value) VALUES (%d, '_elementor_version', %s)", $page_id, ELEMENTOR_VERSION ) );
    $wpdb->query( $wpdb->prepare( "INSERT INTO {$wpdb->postmeta} (post_id, meta_key, meta_value) VALUES (%d, '_wp_page_template', '')", $page_id ) );

    wp_update_post( [ 'ID' => $page_id, 'post_title' => $title ] );

    if ( class_exists( '\Elementor\Plugin' ) ) {
        \Elementor\Plugin::$instance->files_manager->clear_cache();
    }

    echo "Page $page_id ($title): " . count( $elements ) . " sections, " . strlen( $data_json ) . " bytes" . PHP_EOL;
    return true;
}

// ================================================================
// PAGE 1: Widgets — Content (ID 82)
// ================================================================
$band = 0;
$elements = [];

$elements[] = _ep_cols_section( [ [
    _ep_heading( 'Content Widgets Showcase', 'h1', 'center' ),
    _ep_widget( 'text-editor', [
        'editor' => '<p style="color:#6b7280;font-size:16px">Every content widget with 3 design variations — live demos with real data.</p>',
        'align'  => 'center',
    ] ),
] ], [
    'background_background' => 'gradient',
    'background_color'      => '#0f172a',
    'background_color_b'    => '#4f46e5',
    'padding'               => [ 'unit' => 'px', 'top' => '80', 'right' => '20', 'bottom' => '80', 'left' => '20', 'isLinked' => false ],
] );

// Button
array_push( $elements, ..._ep_showcase( 'Button', [
    [ _ep_widget( 'elementskey_button', [ 'button_text' => 'Get Started', 'button_bg' => '#4361ee', 'button_color' => '#ffffff', 'button_size' => 'md' ] ) ],
    [ _ep_widget( 'elementskey_button', [ 'button_text' => 'Learn More', 'button_bg' => '#10b981', 'button_color' => '#ffffff', 'button_size' => 'lg' ] ) ],
    [ _ep_widget( 'elementskey_button', [ 'button_text' => 'Contact Us', 'button_bg' => '#ef4444', 'button_color' => '#ffffff', 'button_size' => 'sm' ] ) ],
], $band++ ) );

// Icon
array_push( $elements, ..._ep_showcase( 'Icon', [
    [ _ep_widget( 'elementskey_icon', [ 'selected_icon' => [ 'value' => 'fas fa-star', 'library' => 'fa-solid' ], 'icon_color' => '#f7b500' ] ) ],
    [ _ep_widget( 'elementskey_icon', [ 'selected_icon' => [ 'value' => 'fas fa-heart', 'library' => 'fa-solid' ], 'icon_color' => '#ef4444' ] ) ],
    [ _ep_widget( 'elementskey_icon', [ 'selected_icon' => [ 'value' => 'fas fa-bolt', 'library' => 'fa-solid' ], 'icon_color' => '#f59e0b' ] ) ],
], $band++ ) );

// Icon Box
array_push( $elements, ..._ep_showcase( 'Icon Box', [
    [ _ep_widget( 'elementskey_icon_box', [ 'selected_icon' => [ 'value' => 'fas fa-rocket', 'library' => 'fa-solid' ], 'title' => 'Fast Performance', 'description' => 'Optimized for speed and efficiency.', 'align' => 'center' ] ) ],
    [ _ep_widget( 'elementskey_icon_box', [ 'selected_icon' => [ 'value' => 'fas fa-shield-alt', 'library' => 'fa-solid' ], 'title' => 'Secure', 'description' => 'Built with security in mind.', 'align' => 'center' ] ) ],
    [ _ep_widget( 'elementskey_icon_box', [ 'selected_icon' => [ 'value' => 'fas fa-paint-brush', 'library' => 'fa-solid' ], 'title' => 'Customizable', 'description' => 'Full styling controls available.', 'align' => 'center' ] ) ],
], $band++ ) );

// Image Box
array_push( $elements, ..._ep_showcase( 'Image Box', [
    [ _ep_widget( 'elementskey_image_box', [ 'image' => [ 'url' => 'https://picsum.photos/seed/ibox1/400/200' ], 'title' => 'Creative Solutions', 'description' => 'Innovative designs that stand out.' ] ) ],
    [ _ep_widget( 'elementskey_image_box', [ 'image' => [ 'url' => 'https://picsum.photos/seed/ibox2/400/200' ], 'title' => 'Expert Team', 'description' => 'Professional experience in every project.' ] ) ],
    [ _ep_widget( 'elementskey_image_box', [ 'image' => [ 'url' => 'https://picsum.photos/seed/ibox3/400/200' ], 'title' => '24/7 Support', 'description' => 'We are always here to help you.' ] ) ],
], $band++ ) );

// Counter
array_push( $elements, ..._ep_showcase( 'Counter', [
    [ _ep_widget( 'elementskey_counter', [ 'counter_number' => 2500, 'counter_suffix' => '+', 'counter_title' => 'Happy Customers', 'number_color' => '#4361ee' ] ) ],
    [ _ep_widget( 'elementskey_counter', [ 'counter_number' => 150, 'counter_suffix' => '+', 'counter_title' => 'Projects Done', 'number_color' => '#7209b7' ] ) ],
    [ _ep_widget( 'elementskey_counter', [ 'counter_number' => 99, 'counter_suffix' => '%', 'counter_title' => 'Satisfaction', 'number_color' => '#10b981' ] ) ],
], $band++ ) );

// Spacer
array_push( $elements, ..._ep_showcase( 'Spacer', [
    [ _ep_widget( 'elementskey_spacer', [ 'space' => [ 'size' => 50, 'unit' => 'px' ] ] ) ],
    [ _ep_widget( 'elementskey_spacer', [ 'space' => [ 'size' => 80, 'unit' => 'px' ] ] ) ],
    [ _ep_widget( 'elementskey_spacer', [ 'space' => [ 'size' => 30, 'unit' => 'px' ] ] ) ],
], $band++ ) );

// Divider
array_push( $elements, ..._ep_showcase( 'Divider', [
    [ _ep_widget( 'elementskey_divider', [ 'divider_style' => 'solid', 'divider_color' => '#4361ee', 'divider_width' => [ 'size' => 50, 'unit' => '%' ] ] ) ],
    [ _ep_widget( 'elementskey_divider', [ 'divider_style' => 'dashed', 'divider_color' => '#ef4444', 'divider_width' => [ 'size' => 70, 'unit' => '%' ] ] ) ],
    [ _ep_widget( 'elementskey_divider', [ 'divider_style' => 'dotted', 'divider_color' => '#10b981', 'divider_width' => [ 'size' => 40, 'unit' => '%' ] ] ) ],
], $band++ ) );

// Accordion
array_push( $elements, ..._ep_showcase( 'Accordion', [
    [ _ep_widget( 'elementskey_accordion', [
        'accordion_items' => [
            [ 'acc_title' => 'What is ElementsKey?', 'acc_content' => 'A powerful Elementor addon with 73 widgets.', 'acc_active' => 'yes' ],
            [ 'acc_title' => 'Do I need Elementor Pro?', 'acc_content' => 'No! It works with the free version.' ],
            [ 'acc_title' => 'Can I use it on client sites?', 'acc_content' => 'Yes, unlimited personal and client websites.' ],
        ],
        'show_icon' => 'yes',
    ] ) ],
    [ _ep_widget( 'elementskey_accordion', [
        'accordion_items' => [
            [ 'acc_title' => 'How to install?', 'acc_content' => 'Upload and activate like any WordPress plugin.', 'acc_active' => 'yes' ],
            [ 'acc_title' => 'Is it translation ready?', 'acc_content' => 'Yes, fully translatable with text domain elementskey.' ],
            [ 'acc_title' => 'Works with any theme?', 'acc_content' => 'Yes, compatible with all WordPress themes.' ],
        ],
    ] ) ],
    [ _ep_widget( 'elementskey_accordion', [
        'accordion_items' => [
            [ 'acc_title' => 'Performance', 'acc_content' => 'Lightweight and optimized for speed.', 'acc_active' => 'yes' ],
            [ 'acc_title' => 'Updates', 'acc_content' => 'Regular updates with new features and fixes.' ],
            [ 'acc_title' => 'Support', 'acc_content' => 'Dedicated support via GitHub issues.' ],
        ],
    ] ) ],
], $band++ ) );

// Tabs
array_push( $elements, ..._ep_showcase( 'Tabs', [
    [ _ep_widget( 'elementskey_tabs', [
        'tabs' => [
            [ 'tab_title' => 'Overview', 'tab_content' => 'ElementsKey is a lightweight Elementor addon with 73 custom widgets.' ],
            [ 'tab_title' => 'Features', 'tab_content' => 'Loop Grid, Theme Builder, Widget Manager, and 73 responsive widgets.' ],
            [ 'tab_title' => 'Reviews', 'tab_content' => 'Rated 5 stars by happy users worldwide!' ],
        ],
    ] ) ],
    [ _ep_widget( 'elementskey_tabs', [
        'tabs' => [
            [ 'tab_title' => 'Design', 'tab_content' => 'Beautiful, modern designs for every widget.' ],
            [ 'tab_title' => 'Performance', 'tab_content' => 'Zero bloat, lightweight code.' ],
            [ 'tab_title' => 'Support', 'tab_content' => 'Dedicated GitHub support.' ],
        ],
        'tab_position' => 'left',
    ] ) ],
    [ _ep_widget( 'elementskey_tabs', [
        'tabs' => [
            [ 'tab_title' => 'Free', 'tab_content' => 'Works with Elementor free version.' ],
            [ 'tab_title' => 'Pro', 'tab_content' => 'No Pro version needed.' ],
        ],
    ] ) ],
], $band++ ) );

// Testimonial
array_push( $elements, ..._ep_showcase( 'Testimonial', [
    [ _ep_widget( 'elementskey_testimonial', [
        'testimonial_quote'   => 'This plugin completely transformed how we build pages.',
        'testimonial_author'  => 'Sarah Johnson',
        'testimonial_role'    => 'CEO, TechCorp',
        'testimonial_rating'  => '5',
    ] ) ],
    [ _ep_widget( 'elementskey_testimonial', [
        'testimonial_quote'   => 'Absolutely the best Elementor addon. Highly recommended!',
        'testimonial_author'  => 'Mike Rodriguez',
        'testimonial_role'    => 'Senior Developer',
        'testimonial_rating'  => '4',
    ] ) ],
    [ _ep_widget( 'elementskey_testimonial', [
        'testimonial_quote'   => 'Clean code, great performance, beautiful widgets.',
        'testimonial_author'  => 'Anna Kim',
        'testimonial_role'    => 'UI Designer',
        'testimonial_rating'  => '5',
    ] ) ],
], $band++ ) );

// Blockquote
array_push( $elements, ..._ep_showcase( 'Blockquote', [
    [ _ep_widget( 'elementskey_blockquote', [ 'blockquote_content' => 'Design is not just what it looks like. Design is how it works.', 'blockquote_author' => 'Steve Jobs' ] ) ],
    [ _ep_widget( 'elementskey_blockquote', [ 'blockquote_content' => 'Simplicity is the ultimate sophistication.', 'blockquote_author' => 'Leonardo da Vinci' ] ) ],
    [ _ep_widget( 'elementskey_blockquote', [ 'blockquote_content' => 'Code is like humor. When you have to explain it, it is bad.', 'blockquote_author' => 'Cory House' ] ) ],
], $band++ ) );

// Call to Action
array_push( $elements, ..._ep_showcase( 'Call to Action', [
    [ _ep_widget( 'elementskey_call_to_action', [ 'cta_title' => 'Ready to Get Started?', 'cta_description' => 'Join 10,000+ happy customers today.', 'cta_button_text' => 'Get Started Free' ] ) ],
    [ _ep_widget( 'elementskey_call_to_action', [ 'cta_title' => 'Build Something Amazing', 'cta_description' => '73 widgets at your fingertips.', 'cta_button_text' => 'Explore Widgets' ] ) ],
    [ _ep_widget( 'elementskey_call_to_action', [ 'cta_title' => 'Need Help?', 'cta_description' => 'Our support team is ready.', 'cta_button_text' => 'Contact Support' ] ) ],
], $band++ ) );

// Flip Box
array_push( $elements, ..._ep_showcase( 'Flip Box', [
    [ _ep_widget( 'elementskey_flip_box', [ 'front_title' => 'Hover Me!', 'front_description' => 'Click to see the back', 'back_title' => 'Surprise!', 'back_description' => 'This is the back content.', 'front_icon' => [ 'value' => 'fas fa-sync-alt', 'library' => 'fa-solid' ] ] ) ],
    [ _ep_widget( 'elementskey_flip_box', [ 'front_title' => 'Features', 'front_description' => 'See what we offer', 'back_title' => '73 Widgets', 'back_description' => 'All included for free.', 'front_icon' => [ 'value' => 'fas fa-cog', 'library' => 'fa-solid' ] ] ) ],
    [ _ep_widget( 'elementskey_flip_box', [ 'front_title' => 'About Us', 'front_description' => 'Learn more', 'back_title' => 'Our Story', 'back_description' => '12 years of WordPress experience.', 'front_icon' => [ 'value' => 'fas fa-info-circle', 'library' => 'fa-solid' ] ] ) ],
], $band++ ) );

// Price Table
array_push( $elements, ..._ep_showcase( 'Price Table', [
    [ _ep_widget( 'elementskey_price_table', [
        'pt_plan_name' => 'Starter', 'pt_price' => '$9', 'pt_period' => '/mo',
        'pt_features' => [
            [ 'pt_feature_text' => '5 Projects', 'pt_feature_included' => 'yes' ],
            [ 'pt_feature_text' => '10GB Storage', 'pt_feature_included' => 'yes' ],
            [ 'pt_feature_text' => 'Email Support', 'pt_feature_included' => 'yes' ],
        ],
        'pt_button_text' => 'Choose Plan',
    ] ) ],
    [ _ep_widget( 'elementskey_price_table', [
        'pt_plan_name' => 'Professional', 'pt_price' => '$29', 'pt_period' => '/mo',
        'pt_features' => [
            [ 'pt_feature_text' => 'Unlimited Projects', 'pt_feature_included' => 'yes' ],
            [ 'pt_feature_text' => '100GB Storage', 'pt_feature_included' => 'yes' ],
            [ 'pt_feature_text' => 'Priority Support', 'pt_feature_included' => 'yes' ],
        ],
        'pt_button_text' => 'Choose Plan',
        'pt_highlighted' => 'yes',
    ] ) ],
    [ _ep_widget( 'elementskey_price_table', [
        'pt_plan_name' => 'Enterprise', 'pt_price' => '$99', 'pt_period' => '/mo',
        'pt_features' => [
            [ 'pt_feature_text' => 'Everything in Pro', 'pt_feature_included' => 'yes' ],
            [ 'pt_feature_text' => 'Unlimited Storage', 'pt_feature_included' => 'yes' ],
        ],
        'pt_button_text' => 'Contact Sales',
    ] ) ],
], $band++ ) );

// Price List
array_push( $elements, ..._ep_showcase( 'Price List', [
    [ _ep_widget( 'elementskey_price_list', [
        'price_list_items' => [
            [ 'pl_title' => 'Web Design', 'pl_price' => '$1,200', 'pl_description' => 'Custom responsive website' ],
            [ 'pl_title' => 'SEO', 'pl_price' => '$500', 'pl_description' => 'Full on-page SEO' ],
            [ 'pl_title' => 'Maintenance', 'pl_price' => '$99/mo', 'pl_description' => 'Updates and support' ],
        ],
    ] ) ],
    [ _ep_widget( 'elementskey_price_list', [
        'price_list_items' => [
            [ 'pl_title' => 'Logo Design', 'pl_price' => '$299', 'pl_description' => 'Professional brand identity' ],
            [ 'pl_title' => 'Business Card', 'pl_price' => '$49', 'pl_description' => 'Premium quality cards' ],
        ],
    ] ) ],
    [ _ep_widget( 'elementskey_price_list', [
        'price_list_items' => [
            [ 'pl_title' => 'Monthly Plan', 'pl_price' => '$199/mo', 'pl_description' => 'All inclusive' ],
            [ 'pl_title' => 'Annual Plan', 'pl_price' => '$1,999/yr', 'pl_description' => 'Save 2 months', 'pl_featured' => 'yes' ],
        ],
    ] ) ],
], $band++ ) );

// Data Table
array_push( $elements, ..._ep_showcase( 'Data Table', [
    [ _ep_widget( 'elementskey_data_table', [
        'table_title' => 'Widget Comparison',
        'table_rows' => [
            [ 'row_label' => 'Widgets', 'row_value' => '73' ],
            [ 'row_label' => 'Categories', 'row_value' => '4' ],
            [ 'row_label' => 'Performance', 'row_value' => 'Lightweight' ],
        ],
    ] ) ],
    [ _ep_widget( 'elementskey_data_table', [
        'table_title' => 'Plan Features',
        'table_rows' => [
            [ 'row_label' => 'Price', 'row_value' => 'Free' ],
            [ 'row_label' => 'Support', 'row_value' => 'GitHub' ],
            [ 'row_label' => 'Updates', 'row_value' => 'Regular' ],
        ],
    ] ) ],
    [ _ep_widget( 'elementskey_data_table', [
        'table_title' => 'System Info',
        'table_rows' => [
            [ 'row_label' => 'PHP', 'row_value' => '7.4+' ],
            [ 'row_label' => 'WordPress', 'row_value' => '6.0+' ],
            [ 'row_label' => 'Elementor', 'row_value' => 'Free' ],
        ],
    ] ) ],
], $band++ ) );

// Feature Comparison Table
array_push( $elements, ..._ep_showcase( 'Feature Comparison Table', [
    [ _ep_widget( 'elementskey_feature_comparison_table', [
        'table_title' => 'ElementsKey vs Others',
        'rows' => [
            [ 'feature_text' => '73 Widgets', 'value_type' => 'icon', 'value_icon' => 'fas fa-check' ],
            [ 'feature_text' => 'Theme Builder', 'value_type' => 'icon', 'value_icon' => 'fas fa-check' ],
            [ 'feature_text' => 'Loop Grid', 'value_type' => 'icon', 'value_icon' => 'fas fa-check' ],
            [ 'feature_text' => 'Widget Manager', 'value_type' => 'icon', 'value_icon' => 'fas fa-check' ],
        ],
    ] ) ],
    [ _ep_widget( 'elementskey_feature_comparison_table', [
        'table_title' => 'Free vs Pro',
        'rows' => [
            [ 'feature_text' => 'All 73 Widgets', 'value_type' => 'icon', 'value_icon' => 'fas fa-check' ],
            [ 'feature_text' => 'Header/Footer', 'value_type' => 'icon', 'value_icon' => 'fas fa-check' ],
            [ 'feature_text' => 'Loop Templates', 'value_type' => 'icon', 'value_icon' => 'fas fa-check' ],
        ],
    ] ) ],
    [ _ep_widget( 'elementskey_feature_comparison_table', [
        'table_title' => 'Compatibility',
        'rows' => [
            [ 'feature_text' => 'Any Theme', 'value_type' => 'icon', 'value_icon' => 'fas fa-check' ],
            [ 'feature_text' => 'Elementor Free', 'value_type' => 'icon', 'value_icon' => 'fas fa-check' ],
            [ 'feature_text' => 'WP 6.0+', 'value_type' => 'icon', 'value_icon' => 'fas fa-check' ],
        ],
    ] ) ],
], $band++ ) );

// Icon List
array_push( $elements, ..._ep_showcase( 'Icon List', [
    [ _ep_widget( 'elementskey_icon_list', [
        'icon_list' => [
            [ 'list_text' => 'Easy to install', 'list_icon' => 'fas fa-check-circle' ],
            [ 'list_text' => '73 widgets', 'list_icon' => 'fas fa-check-circle' ],
            [ 'list_text' => 'Header/Footer builder', 'list_icon' => 'fas fa-check-circle' ],
            [ 'list_text' => 'Works with any theme', 'list_icon' => 'fas fa-check-circle' ],
        ],
    ] ) ],
    [ _ep_widget( 'elementskey_icon_list', [
        'icon_list' => [
            [ 'list_text' => 'Lightweight code', 'list_icon' => 'fas fa-bolt' ],
            [ 'list_text' => 'Fully responsive', 'list_icon' => 'fas fa-mobile-alt' ],
            [ 'list_text' => 'SEO friendly', 'list_icon' => 'fas fa-search' ],
        ],
    ] ) ],
    [ _ep_widget( 'elementskey_icon_list', [
        'icon_list' => [
            [ 'list_text' => '24/7 support', 'list_icon' => 'fas fa-headset' ],
            [ 'list_text' => 'Regular updates', 'list_icon' => 'fas fa-sync' ],
            [ 'list_text' => 'GPL licensed', 'list_icon' => 'fas fa-file-contract' ],
        ],
    ] ) ],
], $band++ ) );

// Progress Bar
array_push( $elements, ..._ep_showcase( 'Progress Bar', [
    [ _ep_widget( 'elementskey_progress_bar', [
        'bars' => [
            [ 'title' => 'HTML/CSS', 'percentage' => [ 'size' => 90 ] ],
        ],
    ] ) ],
    [ _ep_widget( 'elementskey_progress_bar', [
        'bars' => [
            [ 'title' => 'JavaScript', 'percentage' => [ 'size' => 75 ] ],
        ],
    ] ) ],
    [ _ep_widget( 'elementskey_progress_bar', [
        'bars' => [
            [ 'title' => 'PHP', 'percentage' => [ 'size' => 60 ] ],
        ],
    ] ) ],
], $band++ ) );

// Progress Tracker
array_push( $elements, ..._ep_showcase( 'Progress Tracker', [
    [ _ep_widget( 'elementskey_progress_tracker', [ 'tracker_title' => 'Order Progress' ] ) ],
    [ _ep_widget( 'elementskey_progress_tracker', [ 'tracker_title' => 'Course Progress' ] ) ],
    [ _ep_widget( 'elementskey_progress_tracker', [ 'tracker_title' => 'Project Status' ] ) ],
], $band++ ) );

// Star Rating
array_push( $elements, ..._ep_showcase( 'Star Rating', [
    [ _ep_widget( 'elementskey_star_rating', [ 'rating_value' => [ 'size' => 5 ], 'rating_scale' => '5', 'show_number' => 'yes' ] ) ],
    [ _ep_widget( 'elementskey_star_rating', [ 'rating_value' => [ 'size' => 4.5 ], 'rating_scale' => '5', 'show_number' => 'yes' ] ) ],
    [ _ep_widget( 'elementskey_star_rating', [ 'rating_value' => [ 'size' => 3 ], 'rating_scale' => '5', 'show_number' => 'yes' ] ) ],
], $band++ ) );

// Animated Headline
array_push( $elements, ..._ep_showcase( 'Animated Headline', [
    [ _ep_widget( 'elementskey_animated_headline', [
        'animated_headline' => 'We build',
        'headline_style'    => 'rotating',
        'rotating_text'     => [ 'websites', 'apps', 'stores', 'brands' ],
    ] ) ],
    [ _ep_widget( 'elementskey_animated_headline', [
        'animated_headline' => 'Learn',
        'headline_style'    => 'rotating',
        'rotating_text'     => [ 'HTML', 'CSS', 'JavaScript', 'PHP' ],
    ] ) ],
    [ _ep_widget( 'elementskey_animated_headline', [
        'animated_headline' => 'Create',
        'headline_style'    => 'rotating',
        'rotating_text'     => [ 'designs', 'experiences', 'solutions' ],
    ] ) ],
], $band++ ) );

// Countdown
array_push( $elements, ..._ep_showcase( 'Countdown', [
    [ _ep_widget( 'elementskey_countdown', [ 'due_date' => gmdate( 'Y-m-d\TH:i:s', strtotime( '+7 days' ) ), 'show_labels' => 'yes' ] ) ],
    [ _ep_widget( 'elementskey_countdown', [ 'due_date' => gmdate( 'Y-m-d\TH:i:s', strtotime( '+30 days' ) ), 'show_labels' => 'yes' ] ) ],
    [ _ep_widget( 'elementskey_countdown', [ 'due_date' => gmdate( 'Y-m-d\TH:i:s', strtotime( '+90 days' ) ), 'show_labels' => 'yes' ] ) ],
], $band++ ) );

// Code Highlight
array_push( $elements, ..._ep_showcase( 'Code Highlight', [
    [ _ep_widget( 'elementskey_code_highlight', [ 'code_language' => 'javascript', 'code_content' => 'const greeting = "Hello World";\nconsole.log(greeting);', 'show_copy_btn' => 'yes' ] ) ],
    [ _ep_widget( 'elementskey_code_highlight', [ 'code_language' => 'php', 'code_content' => '<?php\necho "Hello World";\n?>', 'show_copy_btn' => 'yes' ] ) ],
    [ _ep_widget( 'elementskey_code_highlight', [ 'code_language' => 'css', 'code_content' => '.button {\n  background: #4361ee;\n  color: white;\n}', 'show_copy_btn' => 'yes' ] ) ],
], $band++ ) );

// Table of Content
array_push( $elements, ..._ep_showcase( 'Table of Content', [
    [ _ep_widget( 'elementskey_table_of_content', [ 'toc_title' => 'Table of Contents' ] ) ],
    [ _ep_widget( 'elementskey_table_of_content', [ 'toc_title' => 'On This Page' ] ) ],
    [ _ep_widget( 'elementskey_table_of_content', [ 'toc_title' => 'Navigation' ] ) ],
], $band++ ) );

// Reviews
array_push( $elements, ..._ep_showcase( 'Reviews', [
    [ _ep_widget( 'elementskey_reviews', [
        'reviews' => [
            [ 'rv_name' => 'Mike R.', 'rv_role' => 'Developer', 'rv_comment' => 'Love this addon!', 'rv_rating' => 5 ],
            [ 'rv_name' => 'Anna K.', 'rv_role' => 'Designer', 'rv_comment' => 'Great variety of widgets.', 'rv_rating' => 4 ],
        ],
    ] ) ],
    [ _ep_widget( 'elementskey_reviews', [
        'reviews' => [
            [ 'rv_name' => 'John D.', 'rv_role' => 'Business Owner', 'rv_comment' => 'Best Elementor addon.', 'rv_rating' => 5 ],
            [ 'rv_name' => 'Lisa M.', 'rv_role' => 'Marketer', 'rv_comment' => 'Easy to use and fast.', 'rv_rating' => 5 ],
        ],
    ] ) ],
    [ _ep_widget( 'elementskey_reviews', [
        'reviews' => [
            [ 'rv_name' => 'Tom B.', 'rv_role' => 'Freelancer', 'rv_comment' => 'Saves me hours of work.', 'rv_rating' => 5 ],
        ],
    ] ) ],
], $band++ ) );

// Testimonial Carousel
array_push( $elements, ..._ep_showcase( 'Testimonial Carousel', [
    [ _ep_widget( 'elementskey_testimonial_carousel', [
        'testimonials' => [
            [ 'tc_name' => 'Sarah Johnson', 'tc_role' => 'CEO, TechCorp', 'tc_content' => 'This plugin transformed our workflow.', 'tc_rating' => 5 ],
            [ 'tc_name' => 'Mike Rodriguez', 'tc_role' => 'Developer', 'tc_content' => 'Best Elementor addon available.', 'tc_rating' => 4 ],
            [ 'tc_name' => 'Anna Kim', 'tc_role' => 'Designer', 'tc_content' => 'Beautiful widgets, clean code.', 'tc_rating' => 5 ],
        ],
    ] ) ],
    [ _ep_widget( 'elementskey_testimonial_carousel', [
        'testimonials' => [
            [ 'tc_name' => 'John Smith', 'tc_role' => 'Director', 'tc_content' => 'Highly recommended for any project.', 'tc_rating' => 5 ],
            [ 'tc_name' => 'Emily Davis', 'tc_role' => 'Manager', 'tc_content' => 'Excellent support and features.', 'tc_rating' => 5 ],
        ],
    ] ) ],
    [ _ep_widget( 'elementskey_testimonial_carousel', [
        'testimonials' => [
            [ 'tc_name' => 'Chris Lee', 'tc_role' => 'Founder', 'tc_content' => 'Game changer for our agency.', 'tc_rating' => 5 ],
        ],
    ] ) ],
], $band++ ) );

// Hotspot
array_push( $elements, ..._ep_showcase( 'Hotspot', [
    [ _ep_widget( 'elementskey_hotspot', [
        'hotspot_image' => [ 'url' => 'https://picsum.photos/seed/hotspot1/600/400' ],
        'hotspot_markers' => [
            [ 'hotspot_left' => 30, 'hotspot_top' => 40, 'hotspot_label' => 'Feature A', 'hotspot_description' => 'Cloud sync enabled.' ],
            [ 'hotspot_left' => 70, 'hotspot_top' => 60, 'hotspot_label' => 'Feature B', 'hotspot_description' => 'Real-time analytics.' ],
        ],
    ] ) ],
    [ _ep_widget( 'elementskey_hotspot', [
        'hotspot_image' => [ 'url' => 'https://picsum.photos/seed/hotspot2/600/400' ],
        'hotspot_markers' => [
            [ 'hotspot_left' => 50, 'hotspot_top' => 30, 'hotspot_label' => 'Click Here', 'hotspot_description' => 'Main action area.' ],
        ],
    ] ) ],
    [ _ep_widget( 'elementskey_hotspot', [
        'hotspot_image' => [ 'url' => 'https://picsum.photos/seed/hotspot3/600/400' ],
        'hotspot_markers' => [
            [ 'hotspot_left' => 20, 'hotspot_top' => 50, 'hotspot_label' => 'Info', 'hotspot_description' => 'Additional details.' ],
            [ 'hotspot_left' => 80, 'hotspot_top' => 30, 'hotspot_label' => 'Action', 'hotspot_description' => 'Start now.' ],
            [ 'hotspot_left' => 50, 'hotspot_top' => 80, 'hotspot_label' => 'Help', 'hotspot_description' => 'Get support.' ],
        ],
    ] ) ],
], $band++ ) );

// Basic Gallery
array_push( $elements, ..._ep_showcase( 'Basic Gallery', [
    [ _ep_widget( 'elementskey_basic_gallery', [
        'galleries' => [
            [ 'gallery_image' => [ 'url' => 'https://picsum.photos/seed/g1/400/300' ], 'gallery_caption' => 'Mountain View' ],
            [ 'gallery_image' => [ 'url' => 'https://picsum.photos/seed/g2/400/300' ], 'gallery_caption' => 'Ocean Sunset' ],
            [ 'gallery_image' => [ 'url' => 'https://picsum.photos/seed/g3/400/300' ], 'gallery_caption' => 'City Lights' ],
        ],
        'columns' => 3,
    ] ) ],
    [ _ep_widget( 'elementskey_basic_gallery', [
        'galleries' => [
            [ 'gallery_image' => [ 'url' => 'https://picsum.photos/seed/g4/400/300' ], 'gallery_caption' => 'Forest' ],
            [ 'gallery_image' => [ 'url' => 'https://picsum.photos/seed/g5/400/300' ], 'gallery_caption' => 'Desert' ],
            [ 'gallery_image' => [ 'url' => 'https://picsum.photos/seed/g6/400/300' ], 'gallery_caption' => 'Lake' ],
        ],
        'columns' => 3,
    ] ) ],
    [ _ep_widget( 'elementskey_basic_gallery', [
        'galleries' => [
            [ 'gallery_image' => [ 'url' => 'https://picsum.photos/seed/g7/400/300' ] ],
            [ 'gallery_image' => [ 'url' => 'https://picsum.photos/seed/g8/400/300' ] ],
        ],
        'columns' => 2,
    ] ) ],
], $band++ ) );

// Image Carousel
array_push( $elements, ..._ep_showcase( 'Image Carousel', [
    [ _ep_widget( 'elementskey_image_carousel', [
        'carousel_images' => [
            [ 'carousel_image' => [ 'url' => 'https://picsum.photos/seed/ic1/600/400' ], 'carousel_caption' => 'Image 1' ],
            [ 'carousel_image' => [ 'url' => 'https://picsum.photos/seed/ic2/600/400' ], 'carousel_caption' => 'Image 2' ],
            [ 'carousel_image' => [ 'url' => 'https://picsum.photos/seed/ic3/600/400' ], 'carousel_caption' => 'Image 3' ],
        ],
        'slides_per_view' => 2,
    ] ) ],
    [ _ep_widget( 'elementskey_image_carousel', [
        'carousel_images' => [
            [ 'carousel_image' => [ 'url' => 'https://picsum.photos/seed/ic4/600/400' ] ],
            [ 'carousel_image' => [ 'url' => 'https://picsum.photos/seed/ic5/600/400' ] ],
            [ 'carousel_image' => [ 'url' => 'https://picsum.photos/seed/ic6/600/400' ] ],
        ],
        'slides_per_view' => 3,
    ] ) ],
    [ _ep_widget( 'elementskey_image_carousel', [
        'carousel_images' => [
            [ 'carousel_image' => [ 'url' => 'https://picsum.photos/seed/ic7/600/400' ] ],
            [ 'carousel_image' => [ 'url' => 'https://picsum.photos/seed/ic8/600/400' ] ],
        ],
        'slides_per_view' => 2,
    ] ) ],
], $band++ ) );

// Media Carousel
array_push( $elements, ..._ep_showcase( 'Media Carousel', [
    [ _ep_widget( 'elementskey_media_carousel', [
        'media_items' => [
            [ 'mc_image' => [ 'url' => 'https://picsum.photos/seed/m1/600/400' ], 'mc_title' => 'Image One' ],
            [ 'mc_image' => [ 'url' => 'https://picsum.photos/seed/m2/600/400' ], 'mc_title' => 'Image Two' ],
            [ 'mc_image' => [ 'url' => 'https://picsum.photos/seed/m3/600/400' ], 'mc_title' => 'Image Three' ],
        ],
    ] ) ],
    [ _ep_widget( 'elementskey_media_carousel', [
        'media_items' => [
            [ 'mc_image' => [ 'url' => 'https://picsum.photos/seed/m4/600/400' ], 'mc_title' => 'Photo 1' ],
            [ 'mc_image' => [ 'url' => 'https://picsum.photos/seed/m5/600/400' ], 'mc_title' => 'Photo 2' ],
        ],
    ] ) ],
    [ _ep_widget( 'elementskey_media_carousel', [
        'media_items' => [
            [ 'mc_image' => [ 'url' => 'https://picsum.photos/seed/m6/600/400' ] ],
            [ 'mc_image' => [ 'url' => 'https://picsum.photos/seed/m7/600/400' ] ],
            [ 'mc_image' => [ 'url' => 'https://picsum.photos/seed/m8/600/400' ] ],
        ],
    ] ) ],
], $band++ ) );

// Swiper Carousel
array_push( $elements, ..._ep_showcase( 'Carousel', [
    [ _ep_widget( 'elementskey_swiper_carousel', [] ) ],
    [ _ep_widget( 'elementskey_swiper_carousel', [] ) ],
    [ _ep_widget( 'elementskey_swiper_carousel', [] ) ],
], $band++ ) );

// Slides
array_push( $elements, ..._ep_showcase( 'Slides', [
    [ _ep_widget( 'elementskey_slides', [
        'slides' => [
            [ 'slide_title' => 'Welcome to ElementsKey', 'slide_subtitle' => '73 custom widgets', 'slide_btn_text' => 'Explore' ],
        ],
    ] ) ],
    [ _ep_widget( 'elementskey_slides', [
        'slides' => [
            [ 'slide_title' => 'Build Amazing Pages', 'slide_subtitle' => 'With Elementor', 'slide_btn_text' => 'Get Started' ],
        ],
    ] ) ],
    [ _ep_widget( 'elementskey_slides', [
        'slides' => [
            [ 'slide_title' => 'Lightweight & Fast', 'slide_subtitle' => 'Zero bloat', 'slide_btn_text' => 'Learn More' ],
        ],
    ] ) ],
], $band++ ) );

// Off-Canvas
array_push( $elements, ..._ep_showcase( 'Off-Canvas', [
    [ _ep_widget( 'elementskey_off_canvas', [ 'offcanvas_title' => 'Menu', 'trigger_text' => 'Open Menu' ] ) ],
    [ _ep_widget( 'elementskey_off_canvas', [ 'offcanvas_title' => 'Cart', 'trigger_text' => 'View Cart' ] ) ],
    [ _ep_widget( 'elementskey_off_canvas', [ 'offcanvas_title' => 'Settings', 'trigger_text' => 'Open Settings' ] ) ],
], $band++ ) );

// Link in Bio
array_push( $elements, ..._ep_showcase( 'Link in Bio', [
    [ _ep_widget( 'elementskey_link_in_bio', [
        'bio_name'   => '@brandname',
        'bio_text'   => 'Digital Creator & Storyteller',
        'bio_links'  => [
            [ 'bio_link_label' => 'Website', 'bio_link_url' => [ 'url' => '#' ] ],
            [ 'bio_link_label' => 'Blog', 'bio_link_url' => [ 'url' => '#' ] ],
            [ 'bio_link_label' => 'YouTube', 'bio_link_url' => [ 'url' => '#' ] ],
        ],
    ] ) ],
    [ _ep_widget( 'elementskey_link_in_bio', [
        'bio_name'   => '@developer',
        'bio_text'   => 'Full Stack Developer',
        'bio_links'  => [
            [ 'bio_link_label' => 'GitHub', 'bio_link_url' => [ 'url' => '#' ] ],
            [ 'bio_link_label' => 'LinkedIn', 'bio_link_url' => [ 'url' => '#' ] ],
        ],
    ] ) ],
    [ _ep_widget( 'elementskey_link_in_bio', [
        'bio_name'   => '@agency',
        'bio_text'   => 'Web Design Agency',
        'bio_links'  => [
            [ 'bio_link_label' => 'Portfolio', 'bio_link_url' => [ 'url' => '#' ] ],
            [ 'bio_link_label' => 'Contact', 'bio_link_url' => [ 'url' => '#' ] ],
            [ 'bio_link_label' => 'Pricing', 'bio_link_url' => [ 'url' => '#' ] ],
        ],
    ] ) ],
], $band++ ) );

// Share It
array_push( $elements, ..._ep_showcase( 'Share It', [
    [ _ep_widget( 'elementskey_share_it', [] ) ],
    [ _ep_widget( 'elementskey_share_it', [] ) ],
    [ _ep_widget( 'elementskey_share_it', [] ) ],
], $band++ ) );

_ep_save_page( 82, $elements, 'Widgets — Content' );

// ================================================================
// PAGE 2: Widgets — Media (ID 84)
// ================================================================
$band = 0;
$elements = [];

$elements[] = _ep_cols_section( [ [
    _ep_heading( 'Media Widgets Showcase', 'h1', 'center' ),
    _ep_widget( 'text-editor', [
        'editor' => '<p style="color:#6b7280;font-size:16px">Media and embed widgets with 3 design variations each.</p>',
        'align'  => 'center',
    ] ),
] ], [
    'background_background' => 'gradient',
    'background_color'      => '#0f172a',
    'background_color_b'    => '#7c3aed',
    'padding'               => [ 'unit' => 'px', 'top' => '80', 'right' => '20', 'bottom' => '80', 'left' => '20', 'isLinked' => false ],
] );

// Video
array_push( $elements, ..._ep_showcase( 'Video', [
    [ _ep_widget( 'elementskey_video', [ 'video_type' => 'youtube', 'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ' ] ) ],
    [ _ep_widget( 'elementskey_video', [ 'video_type' => 'youtube', 'youtube_url' => 'https://www.youtube.com/watch?v=8aGhZQkoFbQ' ] ) ],
    [ _ep_widget( 'elementskey_video', [ 'video_type' => 'youtube', 'youtube_url' => 'https://www.youtube.com/watch?v=kXYiU_JCYtU' ] ) ],
], $band++ ) );

// Video Playlist
array_push( $elements, ..._ep_showcase( 'Video Playlist', [
    [ _ep_widget( 'elementskey_video_playlist', [
        'playlist' => [
            [ 'vp_title' => 'Intro Video', 'vp_source' => 'youtube', 'vp_url' => [ 'url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ' ] ],
            [ 'vp_title' => 'Getting Started', 'vp_source' => 'youtube', 'vp_url' => [ 'url' => 'https://www.youtube.com/watch?v=8aGhZQkoFbQ' ] ],
            [ 'vp_title' => 'Advanced Tips', 'vp_source' => 'youtube', 'vp_url' => [ 'url' => 'https://www.youtube.com/watch?v=kXYiU_JCYtU' ] ],
        ],
    ] ) ],
    [ _ep_widget( 'elementskey_video_playlist', [
        'playlist' => [
            [ 'vp_title' => 'Tutorial 1', 'vp_source' => 'youtube', 'vp_url' => [ 'url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ' ] ],
            [ 'vp_title' => 'Tutorial 2', 'vp_source' => 'youtube', 'vp_url' => [ 'url' => 'https://www.youtube.com/watch?v=8aGhZQkoFbQ' ] ],
        ],
    ] ) ],
    [ _ep_widget( 'elementskey_video_playlist', [
        'playlist' => [
            [ 'vp_title' => 'Demo', 'vp_source' => 'youtube', 'vp_url' => [ 'url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ' ] ],
        ],
    ] ) ],
], $band++ ) );

// Lottie
array_push( $elements, ..._ep_showcase( 'Lottie', [
    [ _ep_widget( 'elementskey_lottie', [ 'lottie_url' => [ 'url' => 'https://assets2.lottiefiles.com/packages/lf20_usmfx6bp.json' ] ] ) ],
    [ _ep_widget( 'elementskey_lottie', [ 'lottie_url' => [ 'url' => 'https://assets2.lottiefiles.com/packages/lf20_usmfx6bp.json' ] ] ) ],
    [ _ep_widget( 'elementskey_lottie', [ 'lottie_url' => [ 'url' => 'https://assets2.lottiefiles.com/packages/lf20_usmfx6bp.json' ] ] ) ],
], $band++ ) );

// SoundCloud
array_push( $elements, ..._ep_showcase( 'SoundCloud', [
    [ _ep_widget( 'elementskey_soundcloud', [ 'soundcloud_url' => 'https://soundcloud.com/skrillex/scary-monsters-and-nice-sprites' ] ) ],
    [ _ep_widget( 'elementskey_soundcloud', [ 'soundcloud_url' => 'https://soundcloud.com/skrillex/scary-monsters-and-nice-sprites' ] ) ],
    [ _ep_widget( 'elementskey_soundcloud', [ 'soundcloud_url' => 'https://soundcloud.com/skrillex/scary-monsters-and-nice-sprites' ] ) ],
], $band++ ) );

// Google Maps
array_push( $elements, ..._ep_showcase( 'Google Maps', [
    [ _ep_widget( 'elementskey_google_maps', [ 'map_address' => 'New York, USA', 'map_zoom' => 12 ] ) ],
    [ _ep_widget( 'elementskey_google_maps', [ 'map_address' => 'London, UK', 'map_zoom' => 12 ] ) ],
    [ _ep_widget( 'elementskey_google_maps', [ 'map_address' => 'Tokyo, Japan', 'map_zoom' => 12 ] ) ],
], $band++ ) );

// Facebook Embed
array_push( $elements, ..._ep_showcase( 'Facebook Embed', [
    [ _ep_widget( 'elementskey_facebook_embed', [ 'url' => 'https://www.facebook.com/facebook/videos/10153231379906729/' ] ) ],
    [ _ep_widget( 'elementskey_facebook_embed', [ 'url' => 'https://www.facebook.com/facebook/videos/10153231379906729/' ] ) ],
    [ _ep_widget( 'elementskey_facebook_embed', [ 'url' => 'https://www.facebook.com/facebook/videos/10153231379906729/' ] ) ],
], $band++ ) );

// Facebook Page
array_push( $elements, ..._ep_showcase( 'Facebook Page', [
    [ _ep_widget( 'elementskey_facebook_page', [ 'url' => 'https://www.facebook.com/facebook' ] ) ],
    [ _ep_widget( 'elementskey_facebook_page', [ 'url' => 'https://www.facebook.com/facebook' ] ) ],
    [ _ep_widget( 'elementskey_facebook_page', [ 'url' => 'https://www.facebook.com/facebook' ] ) ],
], $band++ ) );

_ep_save_page( 84, $elements, 'Widgets — Media' );

// ================================================================
// PAGE 3: Widgets — Social & Forms (ID 86)
// ================================================================
$band = 0;
$elements = [];

$elements[] = _ep_cols_section( [ [
    _ep_heading( 'Social & Forms Widgets Showcase', 'h1', 'center' ),
    _ep_widget( 'text-editor', [
        'editor' => '<p style="color:#6b7280;font-size:16px">Social, form, and utility widgets with 3 design variations each.</p>',
        'align'  => 'center',
    ] ),
] ], [
    'background_background' => 'gradient',
    'background_color'      => '#0f172a',
    'background_color_b'    => '#059669',
    'padding'               => [ 'unit' => 'px', 'top' => '80', 'right' => '20', 'bottom' => '80', 'left' => '20', 'isLinked' => false ],
] );

// Social Icons
array_push( $elements, ..._ep_showcase( 'Social Icons', [
    [ _ep_widget( 'elementskey_social_icons', [
        'social_icons' => [
            [ 'social_type' => 'facebook', 'social_link' => [ 'url' => '#' ] ],
            [ 'social_type' => 'twitter', 'social_link' => [ 'url' => '#' ] ],
            [ 'social_type' => 'instagram', 'social_link' => [ 'url' => '#' ] ],
            [ 'social_type' => 'linkedin', 'social_link' => [ 'url' => '#' ] ],
            [ 'social_type' => 'youtube', 'social_link' => [ 'url' => '#' ] ],
        ],
    ] ) ],
    [ _ep_widget( 'elementskey_social_icons', [
        'social_icons' => [
            [ 'social_type' => 'facebook', 'social_link' => [ 'url' => '#' ] ],
            [ 'social_type' => 'twitter', 'social_link' => [ 'url' => '#' ] ],
            [ 'social_type' => 'instagram', 'social_link' => [ 'url' => '#' ] ],
        ],
    ] ) ],
    [ _ep_widget( 'elementskey_social_icons', [
        'social_icons' => [
            [ 'social_type' => 'linkedin', 'social_link' => [ 'url' => '#' ] ],
            [ 'social_type' => 'youtube', 'social_link' => [ 'url' => '#' ] ],
            [ 'social_type' => 'github', 'social_link' => [ 'url' => '#' ] ],
        ],
    ] ) ],
], $band++ ) );

// Form
array_push( $elements, ..._ep_showcase( 'Form', [
    [ _ep_widget( 'elementskey_form', [
        'form_fields' => [
            [ 'field_type' => 'text', 'field_label' => 'Name', 'field_name' => 'name', 'field_required' => 'yes' ],
            [ 'field_type' => 'email', 'field_label' => 'Email', 'field_name' => 'email', 'field_required' => 'yes' ],
            [ 'field_type' => 'textarea', 'field_label' => 'Message', 'field_name' => 'message' ],
        ],
        'submit_button_text' => 'Send Message',
    ] ) ],
    [ _ep_widget( 'elementskey_form', [
        'form_fields' => [
            [ 'field_type' => 'text', 'field_label' => 'Full Name', 'field_name' => 'fullname', 'field_required' => 'yes' ],
            [ 'field_type' => 'email', 'field_label' => 'Email Address', 'field_name' => 'email', 'field_required' => 'yes' ],
            [ 'field_type' => 'tel', 'field_label' => 'Phone', 'field_name' => 'phone' ],
            [ 'field_type' => 'textarea', 'field_label' => 'Project Details', 'field_name' => 'details' ],
        ],
        'submit_button_text' => 'Submit Request',
    ] ) ],
    [ _ep_widget( 'elementskey_form', [
        'form_fields' => [
            [ 'field_type' => 'email', 'field_label' => 'Email', 'field_name' => 'email', 'field_required' => 'yes' ],
        ],
        'submit_button_text' => 'Subscribe',
    ] ) ],
], $band++ ) );

// Login
array_push( $elements, ..._ep_showcase( 'Login', [
    [ _ep_widget( 'elementskey_login', [ 'login_title' => 'Welcome Back', 'button_label' => 'Log In' ] ) ],
    [ _ep_widget( 'elementskey_login', [ 'login_title' => 'Member Login', 'button_label' => 'Sign In' ] ) ],
    [ _ep_widget( 'elementskey_login', [ 'login_title' => 'Access Account', 'button_label' => 'Login' ] ) ],
], $band++ ) );

// Shortcode
array_push( $elements, ..._ep_showcase( 'Shortcode', [
    [ _ep_widget( 'elementskey_shortcode', [] ) ],
    [ _ep_widget( 'elementskey_shortcode', [] ) ],
    [ _ep_widget( 'elementskey_shortcode', [] ) ],
], $band++ ) );

// Facebook Button
array_push( $elements, ..._ep_showcase( 'Facebook Button', [
    [ _ep_widget( 'elementskey_facebook_button', [ 'fb_button_layout' => 'standard', 'fb_button_action' => 'like' ] ) ],
    [ _ep_widget( 'elementskey_facebook_button', [ 'fb_button_layout' => 'standard', 'fb_button_action' => 'recommend' ] ) ],
    [ _ep_widget( 'elementskey_facebook_button', [ 'fb_button_layout' => 'button_count', 'fb_button_action' => 'like' ] ) ],
], $band++ ) );

// Facebook Comments
array_push( $elements, ..._ep_showcase( 'Facebook Comments', [
    [ _ep_widget( 'elementskey_facebook_comments', [ 'url' => 'https://www.facebook.com/facebook' ] ) ],
    [ _ep_widget( 'elementskey_facebook_comments', [ 'url' => 'https://www.facebook.com/facebook' ] ) ],
    [ _ep_widget( 'elementskey_facebook_comments', [ 'url' => 'https://www.facebook.com/facebook' ] ) ],
], $band++ ) );

// Search
array_push( $elements, ..._ep_showcase( 'Search', [
    [ _ep_widget( 'elementskey_search', [] ) ],
    [ _ep_widget( 'elementskey_search', [] ) ],
    [ _ep_widget( 'elementskey_search', [] ) ],
], $band++ ) );

// Taxonomy Filter
array_push( $elements, ..._ep_showcase( 'Taxonomy Filter', [
    [ _ep_widget( 'elementskey_taxonomy_filter', [ 'filter_taxonomy' => 'category', 'filter_all_label' => 'All' ] ) ],
    [ _ep_widget( 'elementskey_taxonomy_filter', [ 'filter_taxonomy' => 'category', 'filter_all_label' => 'All Posts' ] ) ],
    [ _ep_widget( 'elementskey_taxonomy_filter', [ 'filter_taxonomy' => 'post_tag', 'filter_all_label' => 'All Tags' ] ) ],
], $band++ ) );

_ep_save_page( 86, $elements, 'Widgets — Social & Forms' );

// ================================================================
// PAGE 4: Widgets — Theme (ID 88)
// ================================================================
$band = 0;
$elements = [];

$elements[] = _ep_cols_section( [ [
    _ep_heading( 'Theme Widgets Showcase', 'h1', 'center' ),
    _ep_widget( 'text-editor', [
        'editor' => '<p style="color:#6b7280;font-size:16px">Theme, navigation, and post widgets with 3 design variations each.</p>',
        'align'  => 'center',
    ] ),
] ], [
    'background_background' => 'gradient',
    'background_color'      => '#0f172a',
    'background_color_b'    => '#dc2626',
    'padding'               => [ 'unit' => 'px', 'top' => '80', 'right' => '20', 'bottom' => '80', 'left' => '20', 'isLinked' => false ],
] );

// Template
array_push( $elements, ..._ep_showcase( 'Template', [
    [ _ep_widget( 'elementskey_template', [] ) ],
    [ _ep_widget( 'elementskey_template', [] ) ],
    [ _ep_widget( 'elementskey_template', [] ) ],
], $band++ ) );

// Menu
array_push( $elements, ..._ep_showcase( 'Menu', [
    [ _ep_widget( 'elementskey_menu', [] ) ],
    [ _ep_widget( 'elementskey_menu', [] ) ],
    [ _ep_widget( 'elementskey_menu', [] ) ],
], $band++ ) );

// WP Menu
array_push( $elements, ..._ep_showcase( 'WordPress Menu', [
    [ _ep_widget( 'elementskey_wp_menu', [] ) ],
    [ _ep_widget( 'elementskey_wp_menu', [] ) ],
    [ _ep_widget( 'elementskey_wp_menu', [] ) ],
], $band++ ) );

// Sitemap
array_push( $elements, ..._ep_showcase( 'Sitemap', [
    [ _ep_widget( 'elementskey_sitemap', [] ) ],
    [ _ep_widget( 'elementskey_sitemap', [] ) ],
    [ _ep_widget( 'elementskey_sitemap', [] ) ],
], $band++ ) );

// Site Logo
array_push( $elements, ..._ep_showcase( 'Site Logo', [
    [ _ep_widget( 'elementskey_site_logo', [] ) ],
    [ _ep_widget( 'elementskey_site_logo', [] ) ],
    [ _ep_widget( 'elementskey_site_logo', [] ) ],
], $band++ ) );

// Site Title
array_push( $elements, ..._ep_showcase( 'Site Title', [
    [ _ep_widget( 'elementskey_site_title', [] ) ],
    [ _ep_widget( 'elementskey_site_title', [] ) ],
    [ _ep_widget( 'elementskey_site_title', [] ) ],
], $band++ ) );

// Page Title
array_push( $elements, ..._ep_showcase( 'Page Title', [
    [ _ep_widget( 'elementskey_page_title', [] ) ],
    [ _ep_widget( 'elementskey_page_title', [] ) ],
    [ _ep_widget( 'elementskey_page_title', [] ) ],
], $band++ ) );

// Post Title
array_push( $elements, ..._ep_showcase( 'Post Title', [
    [ _ep_widget( 'elementskey_post_title', [] ) ],
    [ _ep_widget( 'elementskey_post_title', [] ) ],
    [ _ep_widget( 'elementskey_post_title', [] ) ],
], $band++ ) );

// Post Excerpt
array_push( $elements, ..._ep_showcase( 'Post Excerpt', [
    [ _ep_widget( 'elementskey_post_excerpt', [ 'excerpt_length' => 20 ] ) ],
    [ _ep_widget( 'elementskey_post_excerpt', [ 'excerpt_length' => 30 ] ) ],
    [ _ep_widget( 'elementskey_post_excerpt', [ 'excerpt_length' => 10 ] ) ],
], $band++ ) );

// Featured Image
array_push( $elements, ..._ep_showcase( 'Featured Image', [
    [ _ep_widget( 'elementskey_featured_image', [] ) ],
    [ _ep_widget( 'elementskey_featured_image', [] ) ],
    [ _ep_widget( 'elementskey_featured_image', [] ) ],
], $band++ ) );

// Post Content
array_push( $elements, ..._ep_showcase( 'Post Content', [
    [ _ep_widget( 'elementskey_post_content', [] ) ],
    [ _ep_widget( 'elementskey_post_content', [] ) ],
    [ _ep_widget( 'elementskey_post_content', [] ) ],
], $band++ ) );

// Author Box
array_push( $elements, ..._ep_showcase( 'Author Box', [
    [ _ep_widget( 'elementskey_author_box', [] ) ],
    [ _ep_widget( 'elementskey_author_box', [] ) ],
    [ _ep_widget( 'elementskey_author_box', [] ) ],
], $band++ ) );

// Post Comments
array_push( $elements, ..._ep_showcase( 'Post Comments', [
    [ _ep_widget( 'elementskey_post_comments', [] ) ],
    [ _ep_widget( 'elementskey_post_comments', [] ) ],
    [ _ep_widget( 'elementskey_post_comments', [] ) ],
], $band++ ) );

// Post Navigation
array_push( $elements, ..._ep_showcase( 'Post Navigation', [
    [ _ep_widget( 'elementskey_post_navigation', [] ) ],
    [ _ep_widget( 'elementskey_post_navigation', [] ) ],
    [ _ep_widget( 'elementskey_post_navigation', [] ) ],
], $band++ ) );

// Post Info
array_push( $elements, ..._ep_showcase( 'Post Info', [
    [ _ep_widget( 'elementskey_post_info', [
        'post_info_items' => [
            [ 'selected_icon' => [ 'value' => 'fas fa-calendar', 'library' => 'fa-solid' ], 'type' => 'post_date' ],
            [ 'selected_icon' => [ 'value' => 'fas fa-user', 'library' => 'fa-solid' ], 'type' => 'author' ],
        ],
    ] ) ],
    [ _ep_widget( 'elementskey_post_info', [
        'post_info_items' => [
            [ 'selected_icon' => [ 'value' => 'fas fa-folder', 'library' => 'fa-solid' ], 'type' => 'post_terms' ],
            [ 'selected_icon' => [ 'value' => 'fas fa-comments', 'library' => 'fa-solid' ], 'type' => 'comments' ],
        ],
    ] ) ],
    [ _ep_widget( 'elementskey_post_info', [
        'post_info_items' => [
            [ 'selected_icon' => [ 'value' => 'fas fa-clock', 'library' => 'fa-solid' ], 'type' => 'post_date' ],
        ],
    ] ) ],
], $band++ ) );

// Breadcrumbs
array_push( $elements, ..._ep_showcase( 'Breadcrumbs', [
    [ _ep_widget( 'elementskey_breadcrumbs', [] ) ],
    [ _ep_widget( 'elementskey_breadcrumbs', [] ) ],
    [ _ep_widget( 'elementskey_breadcrumbs', [] ) ],
], $band++ ) );

// Archive Title
array_push( $elements, ..._ep_showcase( 'Archive Title', [
    [ _ep_widget( 'elementskey_archive_title', [] ) ],
    [ _ep_widget( 'elementskey_archive_title', [] ) ],
    [ _ep_widget( 'elementskey_archive_title', [] ) ],
], $band++ ) );

// Archive Posts
array_push( $elements, ..._ep_showcase( 'Archive Posts', [
    [ _ep_widget( 'elementskey_archive_posts', [ 'posts_per_page' => 3, 'columns' => 3 ] ) ],
    [ _ep_widget( 'elementskey_archive_posts', [ 'posts_per_page' => 2, 'columns' => 2 ] ) ],
    [ _ep_widget( 'elementskey_archive_posts', [ 'posts_per_page' => 4, 'columns' => 4 ] ) ],
], $band++ ) );

// Posts
array_push( $elements, ..._ep_showcase( 'Posts', [
    [ _ep_widget( 'elementskey_posts', [ 'posts_per_page' => 3, 'columns' => 3 ] ) ],
    [ _ep_widget( 'elementskey_posts', [ 'posts_per_page' => 2, 'columns' => 2 ] ) ],
    [ _ep_widget( 'elementskey_posts', [ 'posts_per_page' => 4, 'columns' => 4 ] ) ],
], $band++ ) );

// Portfolio
array_push( $elements, ..._ep_showcase( 'Portfolio', [
    [ _ep_widget( 'elementskey_portfolio', [] ) ],
    [ _ep_widget( 'elementskey_portfolio', [] ) ],
    [ _ep_widget( 'elementskey_portfolio', [] ) ],
], $band++ ) );

// Loop Grid
array_push( $elements, ..._ep_showcase( 'Loop Grid', [
    [ _ep_widget( 'elementskey_loop_grid', [] ) ],
    [ _ep_widget( 'elementskey_loop_grid', [] ) ],
    [ _ep_widget( 'elementskey_loop_grid', [] ) ],
], $band++ ) );

// Loop Carousel
array_push( $elements, ..._ep_showcase( 'Loop Carousel', [
    [ _ep_widget( 'elementskey_loop_carousel', [] ) ],
    [ _ep_widget( 'elementskey_loop_carousel', [] ) ],
    [ _ep_widget( 'elementskey_loop_carousel', [] ) ],
], $band++ ) );

_ep_save_page( 88, $elements, 'Widgets — Theme' );

echo PHP_EOL . 'All 4 pages populated successfully!' . PHP_EOL;
