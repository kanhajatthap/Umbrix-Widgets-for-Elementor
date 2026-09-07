<?php
if ( ! defined( 'ABSPATH' ) ) {
    require_once dirname( __DIR__, 4 ) . '/wp-load.php';
}

global $wpdb;

foreach ( [ 82, 84 ] as $pid ) {
    // Force update with raw SQL
    $wpdb->query( $wpdb->prepare(
        "UPDATE {$wpdb->postmeta} SET meta_value = 'builder' WHERE post_id = %d AND meta_key = '_elementor_edit_mode'",
        $pid
    ) );

    // If no row was updated, insert it
    $exists = $wpdb->get_var( $wpdb->prepare(
        "SELECT COUNT(*) FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key = '_elementor_edit_mode'",
        $pid
    ) );
    if ( ! $exists ) {
        $wpdb->insert( $wpdb->postmeta, [
            'post_id'    => $pid,
            'meta_key'   => '_elementor_edit_mode',
            'meta_value' => 'builder',
        ], [ '%d', '%s', '%s' ] );
    }

    // Verify
    $mode = $wpdb->get_var( $wpdb->prepare(
        "SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key = '_elementor_edit_mode'",
        $pid
    ) );
    echo "Page $pid: _elementor_edit_mode = '$mode'\n";
}

// Also set the _elementor_data as a string (not serialized)
foreach ( [ 82, 84 ] as $pid ) {
    $data = $wpdb->get_var( $wpdb->prepare(
        "SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key = '_elementor_data'",
        $pid
    ) );
    echo "Page $pid: data length = " . strlen( $data ) . " bytes\n";

    // Verify JSON is valid
    $decoded = json_decode( $data, true );
    echo "Page $pid: JSON valid = " . ( $decoded !== null ? 'YES' : 'NO' ) . "\n";
}

// Clear all Elementor caches
$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '%_elementor_%'" );
echo "Cleared Elementor transients.\n";

echo "\nDone! Now visit the pages in browser and click 'Edit with Elementor'.\n";
