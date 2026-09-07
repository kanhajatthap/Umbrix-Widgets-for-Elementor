<?php
if ( ! defined( 'ABSPATH' ) ) {
    require_once dirname( __DIR__, 4 ) . '/wp-load.php';
}

global $wpdb;

foreach ( [ 82, 84 ] as $pid ) {
    // Delete old row if exists
    $wpdb->delete( $wpdb->postmeta, [
        'post_id'    => $pid,
        'meta_key'   => '_elementor_edit_mode',
    ], [ '%d', '%s' ] );

    // Insert fresh with raw SQL — avoids intval casting
    $wpdb->query( $wpdb->prepare(
        "INSERT INTO {$wpdb->postmeta} (post_id, meta_key, meta_value) VALUES (%d, '_elementor_edit_mode', 'builder')",
        $pid
    ) );

    // Verify
    $mode = $wpdb->get_var( $wpdb->prepare(
        "SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id=%d AND meta_key='_elementor_edit_mode'",
        $pid
    ) );
    echo "Page $pid: _elementor_edit_mode = '$mode'\n";
}

// Clear Elementor CSS cache
$upload_dir = wp_upload_dir();
$ek_css_dir = $upload_dir['basedir'] . '/elementor/css';
if ( is_dir( $ek_css_dir ) ) {
    $files = glob( $ek_css_dir . '/*.css' );
    foreach ( $files as $f ) {
        unlink( $f );
    }
    echo "Cleared " . count( $files ) . " Elementor CSS cache files.\n";
}

echo "\nDone! Now hard-refresh both pages in browser (Ctrl+Shift+R).\n";
