<?php
if ( ! defined( 'ABSPATH' ) ) {
    require_once dirname( __DIR__, 4 ) . '/wp-load.php';
}

global $wpdb;

echo "=== Fix: Clear post_content + Force Elementor render ===\n\n";

foreach ( [ 82, 84 ] as $pid ) {
    $page = get_post( $pid );
    echo "Page $pid: {$page->post_title}\n";
    echo "  Before: post_content = " . strlen( $page->post_content ) . " bytes\n";

    // 1. Clear post_content — Elementor pages should have empty post_content
    $wpdb->update( $wpdb->posts,
        [ 'post_content' => '' ],
        [ 'ID' => $pid ],
        [ '%s' ],
        [ '%d' ]
    );
    echo "  After: post_content = 0 bytes\n";

    // 2. Clear ALL Elementor caches for this post
    $wpdb->delete( $wpdb->postmeta, [ 'post_id' => $pid, 'meta_key' => '_elementor_css' ], [ '%d', '%s' ] );
    $wpdb->delete( $wpdb->postmeta, [ 'post_id' => $pid, 'meta_key' => '_elementor_page_assets' ], [ '%d', '%s' ] );

    // 3. Force Elementor to treat this as a new render
    $wpdb->delete( $wpdb->options, [ 'option_name' => 'elementorCssPrint_' . $pid ], [ '%s' ] );
    $wpdb->delete( $wpdb->options, [ 'option_name' => 'elementor_page_assets_' . $pid ], [ '%s' ] );

    echo "  Cleared all caches\n\n";
}

// Clear global Elementor CSS cache
$upload_dir = wp_upload_dir();
$css_dir = $upload_dir['basedir'] . '/elementor/css';
if ( is_dir( $css_dir ) ) {
    $files = glob( $css_dir . '/*.css' );
    foreach ( $files as $f ) {
        unlink( $f );
    }
    echo "Deleted " . count( $files ) . " global CSS files\n";
}

// Verify
foreach ( [ 82, 84 ] as $pid ) {
    $page = get_post( $pid );
    echo "\nPage $pid verification:\n";
    echo "  post_content empty: " . ( empty( $page->post_content ) ? 'YES' : 'NO' ) . "\n";
    echo "  _elementor_edit_mode: " . get_post_meta( $pid, '_elementor_edit_mode', true ) . "\n";
    $data = get_post_meta( $pid, '_elementor_data', true );
    echo "  _elementor_data: " . strlen( $data ) . " bytes\n";
    $decoded = json_decode( $data, true );
    echo "  section count: " . count( $decoded ) . "\n";
    echo "  first section elType: " . ( $decoded[0]['elType'] ?? 'N/A' ) . "\n";
}

// Now test rendering
echo "\n=== Test Render ===\n";
foreach ( [ 82, 84 ] as $pid ) {
    $page = get_post( $pid );
    setup_postdata( $page );
    $rendered = apply_filters( 'the_content', '' );
    echo "\nPage $pid rendered: " . strlen( $rendered ) . " bytes\n";
    echo "  Has elementor-section: " . ( strpos( $rendered, 'elementor-section' ) !== false ? 'YES' : 'NO' ) . "\n";
    echo "  Has elementor-column: " . ( strpos( $rendered, 'elementor-column' ) !== false ? 'YES' : 'NO' ) . "\n";
    echo "  Has elementor-widget-wrap: " . ( strpos( $rendered, 'elementor-widget-wrap' ) !== false ? 'YES' : 'NO' ) . "\n";
    echo "  Has elementor-widget: " . ( strpos( $rendered, 'elementor-widget' ) !== false ? 'YES' : 'NO' ) . "\n";
    echo "  Has elementskey-: " . ( strpos( $rendered, 'elementskey-' ) !== false ? 'YES' : 'NO' ) . "\n";
    echo "  First 500: " . substr( $rendered, 0, 500 ) . "\n";
    wp_reset_postdata();
}
