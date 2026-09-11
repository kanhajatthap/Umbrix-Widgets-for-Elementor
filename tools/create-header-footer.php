<?php
/**
 * Creates a live Header and Footer template for the ElementsKey Theme Builder.
 * Usage: tools\wp.cmd eval-file tools/create-header-footer.php
 */

if ( ! defined( 'ABSPATH' ) ) {
    require_once dirname( __DIR__, 4 ) . '/wp-load.php';
}

if ( ! did_action( 'elementor/loaded' ) ) {
    die( "Elementor must be active.\n" );
}

function elementskey_create_theme_builder_template( $type, $title, $json_data ) {
    $existing = get_posts( [
        'post_type'      => 'builder',
        'post_status'    => 'any',
        'posts_per_page' => 1,
        'meta_key'       => '_elementskey_hf_template_type',
        'meta_value'     => $type,
        'fields'         => 'ids',
    ] );

    if ( ! empty( $existing ) ) {
        $post_id = (int) $existing[0];
    } else {
        $post_id = wp_insert_post( [
            'post_type'    => 'builder',
            'post_status'  => 'publish',
            'post_title'   => $title,
            'post_content' => '',
        ] );
    }

    if ( is_wp_error( $post_id ) ) {
        fwrite( STDERR, "Failed to create $type template: " . $post_id->get_error_message() . PHP_EOL );
        return 0;
    }

    update_post_meta( $post_id, '_elementskey_hf_template_type', $type );
    update_post_meta( $post_id, '_elementor_edit_mode', 'builder' );
    update_post_meta( $post_id, '_elementor_version', \Elementor\Plugin::$instance->version );
    update_post_meta( $post_id, '_elementor_data', wp_json_encode( $json_data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) );
    update_post_meta( $post_id, '_elementskey_hf_conditions', [] );

    return $post_id;
}

$header_json = [
    [
        'id' => 'ek-header-section',
        'elType' => 'section',
        'settings' => [
            'layout' => 'full_width',
            'background_background' => 'classic',
            'background_color' => '#ffffff',
            'padding' => [ 'unit' => 'px', 'top' => 18, 'right' => 24, 'bottom' => 18, 'left' => 24, 'isLinked' => false ],
            'border_border' => 'solid',
            'border_width' => [ 'unit' => 'px', 'top' => 0, 'right' => 0, 'bottom' => 1, 'left' => 0, 'isLinked' => false ],
            'border_color' => '#e5e7eb',
        ],
        'elements' => [
            [
                'id' => 'ek-header-column',
                'elType' => 'column',
                'settings' => [ '_column_size' => 100 ],
                'elements' => [
                    [
                        'id' => 'ek-header-inner',
                        'elType' => 'container',
                        'settings' => [
                            'content_width' => 'boxed',
                            'padding' => [ 'unit' => 'px', 'top' => 0, 'right' => 0, 'bottom' => 0, 'left' => 0, 'isLinked' => true ],
                            'max_width' => [ 'unit' => 'px', 'size' => 1180 ],
                        ],
                        'elements' => [
                            [
                                'id' => 'ek-header-brand-col',
                                'elType' => 'column',
                                'settings' => [ '_column_size' => 25 ],
                                'elements' => [
                                    [
                                        'id' => 'ek-brand-heading',
                                        'elType' => 'widget',
                                        'widgetType' => 'heading',
                                        'settings' => [
                                            'title' => 'ElementKey Lite',
                                            'header_size' => 'h4',
                                            'align' => 'left',
                                            'title_color' => '#111827',
                                            'typography_typography' => 'custom',
                                            'typography_font_size' => [ 'unit' => 'px', 'size' => 28 ],
                                            'typography_font_weight' => '700',
                                            'typography_letter_spacing' => [ 'unit' => 'px', 'size' => -1 ],
                                        ],
                                        'elements' => [],
                                        'isInner' => false,
                                    ],
                                ],
                                'isInner' => false,
                            ],
                            [
                                'id' => 'ek-header-nav-col',
                                'elType' => 'column',
                                'settings' => [ '_column_size' => 50 ],
                                'elements' => [
                                    [
                                        'id' => 'ek-nav-html',
                                        'elType' => 'widget',
                                        'widgetType' => 'html',
                                        'settings' => [
                                            'html' => '<nav style="display:flex;justify-content:center;gap:26px;align-items:center;flex-wrap:wrap;font-size:15px;font-weight:600;line-height:1.4;"><a href="http://plugin-test.local/" style="color:#111827;text-decoration:none;">Home</a><a href="http://plugin-test.local/about/" style="color:#111827;text-decoration:none;">About</a><a href="http://plugin-test.local/contact/" style="color:#111827;text-decoration:none;">Contact</a><a href="http://plugin-test.local/plugin/" style="color:#111827;text-decoration:none;">Plugin</a></nav>',
                                        ],
                                        'elements' => [],
                                        'isInner' => false,
                                    ],
                                ],
                                'isInner' => false,
                            ],
                            [
                                'id' => 'ek-header-cta-col',
                                'elType' => 'column',
                                'settings' => [ '_column_size' => 25 ],
                                'elements' => [
                                    [
                                        'id' => 'ek-cta-button',
                                        'elType' => 'widget',
                                        'widgetType' => 'button',
                                        'settings' => [
                                            'text' => 'Get Started',
                                            'link' => [ 'url' => 'http://plugin-test.local/contact/' ],
                                            'size' => 'sm',
                                            'icon_align' => 'right',
                                            'typography_typography' => 'custom',
                                            'typography_font_size' => [ 'unit' => 'px', 'size' => 15 ],
                                            'typography_font_weight' => '600',
                                            'background_color' => '#4361ee',
                                            'border_radius' => [ 'unit' => 'px', 'size' => 999 ],
                                            'text_padding' => [ 'unit' => 'px', 'top' => 12, 'right' => 22, 'bottom' => 12, 'left' => 22, 'isLinked' => false ],
                                        ],
                                        'elements' => [],
                                        'isInner' => false,
                                    ],
                                ],
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

$footer_json = [
    [
        'id' => 'ek-footer-section',
        'elType' => 'section',
        'settings' => [
            'layout' => 'full_width',
            'background_background' => 'classic',
            'background_color' => '#0f172a',
            'padding' => [ 'unit' => 'px', 'top' => 54, 'right' => 20, 'bottom' => 32, 'left' => 20, 'isLinked' => false ],
        ],
        'elements' => [
            [
                'id' => 'ek-footer-column',
                'elType' => 'column',
                'settings' => [ '_column_size' => 100 ],
                'elements' => [
                    [
                        'id' => 'ek-footer-inner',
                        'elType' => 'container',
                        'settings' => [ 'content_width' => 'boxed', 'max_width' => [ 'unit' => 'px', 'size' => 1180 ] ],
                        'elements' => [
                            [
                                'id' => 'ek-footer-brand-col',
                                'elType' => 'column',
                                'settings' => [ '_column_size' => 35 ],
                                'elements' => [
                                    [
                                        'id' => 'ek-footer-brand',
                                        'elType' => 'widget',
                                        'widgetType' => 'heading',
                                        'settings' => [
                                            'title' => 'ElementKey Lite',
                                            'header_size' => 'h4',
                                            'title_color' => '#ffffff',
                                            'typography_typography' => 'custom',
                                            'typography_font_size' => [ 'unit' => 'px', 'size' => 28 ],
                                            'typography_font_weight' => '700',
                                        ],
                                        'elements' => [],
                                        'isInner' => false,
                                    ],
                                    [
                                        'id' => 'ek-footer-copy',
                                        'elType' => 'widget',
                                        'widgetType' => 'text-editor',
                                        'settings' => [
                                            'editor' => '<p style="color:#cbd5e1;">Professional Elementor add-ons, crisp design, and fast front-end workflows built for modern WordPress teams.</p>',
                                        ],
                                        'elements' => [],
                                        'isInner' => false,
                                    ],
                                ],
                                'isInner' => false,
                            ],
                            [
                                'id' => 'ek-footer-links-col',
                                'elType' => 'column',
                                'settings' => [ '_column_size' => 20 ],
                                'elements' => [
                                    [
                                        'id' => 'ek-footer-links',
                                        'elType' => 'widget',
                                        'widgetType' => 'html',
                                        'settings' => [
                                            'html' => '<h4 style="color:#ffffff;margin:0 0 16px;">Explore</h4><ul style="list-style:none;padding:0;margin:0;display:grid;gap:10px;"><li><a href="http://plugin-test.local/" style="color:#cbd5e1;text-decoration:none;">Home</a></li><li><a href="http://plugin-test.local/about/" style="color:#cbd5e1;text-decoration:none;">About</a></li><li><a href="http://plugin-test.local/contact/" style="color:#cbd5e1;text-decoration:none;">Contact</a></li><li><a href="http://plugin-test.local/plugin/" style="color:#cbd5e1;text-decoration:none;">Plugin</a></li></ul>',
                                        ],
                                        'elements' => [],
                                        'isInner' => false,
                                    ],
                                ],
                                'isInner' => false,
                            ],
                            [
                                'id' => 'ek-footer-news-col',
                                'elType' => 'column',
                                'settings' => [ '_column_size' => 25 ],
                                'elements' => [
                                    [
                                        'id' => 'ek-footer-news',
                                        'elType' => 'widget',
                                        'widgetType' => 'html',
                                        'settings' => [
                                            'html' => '<h4 style="color:#ffffff;margin:0 0 16px;">Newsletter</h4><form style="display:flex;gap:10px;flex-wrap:wrap;"><input type="email" placeholder="Your email" style="width:100%;min-width:180px;padding:12px 14px;border-radius:10px;border:1px solid rgba(255,255,255,0.12);background:white;color:#111827;" /><button type="submit" style="padding:12px 18px;border:0;border-radius:10px;background:#4361ee;color:#fff;font-weight:700;cursor:pointer;">Join</button></form>',
                                        ],
                                        'elements' => [],
                                        'isInner' => false,
                                    ],
                                ],
                                'isInner' => false,
                            ],
                            [
                                'id' => 'ek-footer-social-col',
                                'elType' => 'column',
                                'settings' => [ '_column_size' => 20 ],
                                'elements' => [
                                    [
                                        'id' => 'ek-footer-social',
                                        'elType' => 'widget',
                                        'widgetType' => 'html',
                                        'settings' => [
                                            'html' => '<h4 style="color:#ffffff;margin:0 0 16px;">Follow</h4><div style="display:flex;flex-wrap:wrap;gap:10px;"><a href="https://facebook.com" target="_blank" rel="noopener noreferrer" style="padding:8px 10px;border-radius:999px;background:rgba(255,255,255,0.08);color:#fff;text-decoration:none;">Facebook</a><a href="https://x.com" target="_blank" rel="noopener noreferrer" style="padding:8px 10px;border-radius:999px;background:rgba(255,255,255,0.08);color:#fff;text-decoration:none;">X</a><a href="https://instagram.com" target="_blank" rel="noopener noreferrer" style="padding:8px 10px;border-radius:999px;background:rgba(255,255,255,0.08);color:#fff;text-decoration:none;">Instagram</a></div>',
                                        ],
                                        'elements' => [],
                                        'isInner' => false,
                                    ],
                                ],
                                'isInner' => false,
                            ],
                        ],
                        'isInner' => true,
                    ],
                    [
                        'id' => 'ek-footer-bottom',
                        'elType' => 'widget',
                        'widgetType' => 'html',
                        'settings' => [
                            'html' => '<div style="border-top:1px solid rgba(255,255,255,0.12);margin-top:30px;padding-top:18px;display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap;color:#cbd5e1;font-size:14px;"><span>© 2026 ElementKey Lite</span><span>Built with Elementor styling and plugin-first workflows</span></div>',
                        ],
                        'elements' => [],
                        'isInner' => false,
                    ],
                ],
                'isInner' => false,
            ],
        ],
        'isInner' => false,
    ],
];

$header_id = elementskey_create_theme_builder_template( 'header', 'Header', $header_json );
$footer_id = elementskey_create_theme_builder_template( 'footer', 'Footer', $footer_json );

echo "HEADER:$header_id\n";
echo "FOOTER:$footer_id\n";
