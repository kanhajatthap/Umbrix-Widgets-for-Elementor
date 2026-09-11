<?php
if ( ! function_exists( 'register_post_type' ) ) {
    die( "WordPress not loaded\n" );
}

if ( ! post_type_exists( 'builder' ) ) {
    register_post_type(
        'builder',
        [
            'labels'              => [
                'name'               => 'Theme Builder',
                'singular_name'      => 'Template',
                'add_new'            => 'Add New',
                'add_new_item'       => 'Add New Template',
                'edit_item'          => 'Edit Template',
                'view_item'          => 'View Template',
                'search_items'       => 'Search Templates',
                'not_found'          => 'No templates found',
                'not_found_in_trash' => 'No templates found in Trash',
                'all_items'          => 'Theme Builder',
            ],
            'public'              => false,
            'publicly_queryable'  => is_super_admin(),
            'show_ui'             => true,
            'show_in_menu'        => false,
            'show_in_admin_bar'   => false,
            'show_in_nav_menus'   => false,
            'exclude_from_search' => true,
            'capability_type'     => 'post',
            'map_meta_cap'        => true,
            'hierarchical'        => false,
            'supports'            => [ 'title', 'revisions', 'author' ],
            'menu_icon'           => 'dashicons-editor-kitchensink',
            'rewrite'             => false,
            'query_var'          => false,
        ]
    );
    add_post_type_support( 'builder', 'elementor' );
}

function elementskey_make_template( $type, $title, $data ) {
    $existing = get_posts( [
        'post_type'      => 'builder',
        'post_status'    => 'any',
        'posts_per_page' => 1,
        'meta_key'       => '_elementskey_hf_template_type',
        'meta_value'     => $type,
        'fields'         => 'ids',
    ] );

    $post_id = ! empty( $existing ) ? (int) $existing[0] : wp_insert_post( [
        'post_title'  => $title,
        'post_type'   => 'builder',
        'post_status' => 'publish',
    ] );

    if ( is_wp_error( $post_id ) || ! $post_id ) {
        fwrite( STDERR, "Template insert failed for $type\n" );
        return 0;
    }

    update_post_meta( $post_id, '_elementskey_hf_template_type', $type );
    update_post_meta( $post_id, '_elementor_edit_mode', 'builder' );
    update_post_meta( $post_id, '_elementor_data', wp_json_encode( $data ) );
    update_post_meta( $post_id, '_elementskey_hf_conditions', [] );
    update_post_meta( $post_id, '_elementskey_hf_disable_theme', 'yes' );

    return $post_id;
}

$header_data = [
    [
        'id' => 'ek-header-root',
        'elType' => 'section',
        'settings' => [ 'layout' => 'full_width', 'background_background' => 'classic', 'background_color' => '#ffffff', 'padding' => [ 'unit' => 'px', 'top' => 18, 'right' => 20, 'bottom' => 18, 'left' => 20, 'isLinked' => false ], 'border_border' => 'solid', 'border_width' => [ 'unit' => 'px', 'top' => 0, 'right' => 0, 'bottom' => 1, 'left' => 0, 'isLinked' => false ] ],
        'elements' => [
            [
                'id' => 'ek-header-col',
                'elType' => 'column',
                'settings' => [ '_column_size' => 100 ],
                'elements' => [
                    [
                        'id' => 'ek-header-wrap',
                        'elType' => 'container',
                        'settings' => [ 'content_width' => 'boxed', 'max_width' => [ 'unit' => 'px', 'size' => 1200 ] ],
                        'elements' => [
                            [
                                'id' => 'ek-header-brand',
                                'elType' => 'widget',
                                'widgetType' => 'heading',
                                'settings' => [ 'title' => 'ElementKey Lite', 'header_size' => 'h4', 'title_color' => '#111827', 'typography_typography' => 'custom', 'typography_font_size' => [ 'unit' => 'px', 'size' => 28 ], 'typography_font_weight' => '700' ],
                                'elements' => [],
                                'isInner' => false,
                            ],
                            [
                                'id' => 'ek-header-nav',
                                'elType' => 'widget',
                                'widgetType' => 'html',
                                'settings' => [ 'html' => '<nav style="display:flex;gap:22px;justify-content:center;flex-wrap:wrap;"><a href="http://plugin-test.local/" style="color:#111827;text-decoration:none;font-weight:600;">Home</a><a href="http://plugin-test.local/about/" style="color:#111827;text-decoration:none;font-weight:600;">About</a><a href="http://plugin-test.local/contact/" style="color:#111827;text-decoration:none;font-weight:600;">Contact</a><a href="http://plugin-test.local/plugin/" style="color:#111827;text-decoration:none;font-weight:600;">Plugin</a></nav>' ],
                                'elements' => [],
                                'isInner' => false,
                            ],
                            [
                                'id' => 'ek-header-button',
                                'elType' => 'widget',
                                'widgetType' => 'button',
                                'settings' => [ 'text' => 'Get Started', 'link' => [ 'url' => 'http://plugin-test.local/contact/' ], 'background_color' => '#4361ee', 'border_radius' => [ 'unit' => 'px', 'size' => 999 ], 'text_padding' => [ 'unit' => 'px', 'top' => 12, 'right' => 18, 'bottom' => 12, 'left' => 18, 'isLinked' => false ] ],
                                'elements' => [],
                                'isInner' => false,
                            ],
                        ],
                        'isInner' => true,
                    ],
                ],
                'isInner' => false,
            ],
        ],
        'isInner' => false,
    ],
];

$footer_data = [
    [
        'id' => 'ek-footer-root',
        'elType' => 'section',
        'settings' => [ 'layout' => 'full_width', 'background_background' => 'classic', 'background_color' => '#0f172a', 'padding' => [ 'unit' => 'px', 'top' => 40, 'right' => 24, 'bottom' => 30, 'left' => 24, 'isLinked' => false ] ],
        'elements' => [
            [
                'id' => 'ek-footer-col',
                'elType' => 'column',
                'settings' => [ '_column_size' => 100 ],
                'elements' => [
                    [
                        'id' => 'ek-footer-wrap',
                        'elType' => 'container',
                        'settings' => [ 'content_width' => 'boxed', 'max_width' => [ 'unit' => 'px', 'size' => 1200 ] ],
                        'elements' => [
                            [
                                'id' => 'ek-footer-brand',
                                'elType' => 'widget',
                                'widgetType' => 'heading',
                                'settings' => [ 'title' => 'ElementKey Lite', 'header_size' => 'h4', 'title_color' => '#ffffff' ],
                                'elements' => [],
                                'isInner' => false,
                            ],
                            [
                                'id' => 'ek-footer-copy',
                                'elType' => 'widget',
                                'widgetType' => 'text-editor',
                                'settings' => [ 'editor' => '<p style="color:#cbd5e1;">Built for modern Wordpress teams with a clean plugin-first workflow.</p>' ],
                                'elements' => [],
                                'isInner' => false,
                            ],
                            [
                                'id' => 'ek-footer-links',
                                'elType' => 'widget',
                                'widgetType' => 'html',
                                'settings' => [ 'html' => '<div style="display:flex;gap:16px;flex-wrap:wrap;"><a href="http://plugin-test.local/" style="color:#cbd5e1;text-decoration:none;">Home</a><a href="http://plugin-test.local/about/" style="color:#cbd5e1;text-decoration:none;">About</a><a href="http://plugin-test.local/contact/" style="color:#cbd5e1;text-decoration:none;">Contact</a></div>' ],
                                'elements' => [],
                                'isInner' => false,
                            ],
                            [
                                'id' => 'ek-footer-social',
                                'elType' => 'widget',
                                'widgetType' => 'html',
                                'settings' => [ 'html' => '<div style="display:flex;gap:10px;flex-wrap:wrap;"><a href="https://facebook.com" target="_blank" style="padding:8px 10px;border-radius:999px;background:rgba(255,255,255,0.08);color:#fff;text-decoration:none;">Facebook</a><a href="https://x.com" target="_blank" style="padding:8px 10px;border-radius:999px;background:rgba(255,255,255,0.08);color:#fff;text-decoration:none;">X</a><a href="https://instagram.com" target="_blank" style="padding:8px 10px;border-radius:999px;background:rgba(255,255,255,0.08);color:#fff;text-decoration:none;">Instagram</a></div>' ],
                                'elements' => [],
                                'isInner' => false,
                            ],
                        ],
                        'isInner' => true,
                    ],
                ],
                'isInner' => false,
            ],
        ],
        'isInner' => false,
    ],
];

$header_id = elementskey_make_template( 'header', 'Header', $header_data );
$footer_id = elementskey_make_template( 'footer', 'Footer', $footer_data );

echo "HEADER:$header_id\n";
echo "FOOTER:$footer_id\n";
