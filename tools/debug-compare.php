<?php
if ( ! defined( 'ABSPATH' ) ) {
    require_once dirname( __DIR__, 4 ) . '/wp-load.php';
}

global $wpdb;

// Compare data structures: working showcase (ID 2) vs broken pages
echo "=== Widget Showcase (ID 2) - WORKING ===\n";
$data2 = json_decode( get_post_meta( 2, '_elementor_data', true ), true );
$first2 = $data2[0];
echo "Top-level elType: " . $first2['elType'] . "\n";
echo "Top-level settings keys: " . implode( ', ', array_keys( $first2['settings'] ) ) . "\n";
echo "Widget count in first section: " . count( $first2['elements'][0]['elements'] ?? [] ) . "\n";
// Show one widget's structure
$widget2 = $first2['elements'][0]['elements'][0] ?? null;
if ( $widget2 ) {
    echo "First widget elType: " . $widget2['elType'] . "\n";
    echo "First widget widgetType: " . ( $widget2['settings']['_widget_type'] ?? $widget2['widgetType'] ?? 'N/A' ) . "\n";
    echo "First widget settings keys: " . implode( ', ', array_keys( $widget2['settings'] ) ) . "\n";
}

echo "\n=== Page 82 - BROKEN ===\n";
$data82 = json_decode( get_post_meta( 82, '_elementor_data', true ), true );
$first82 = $data82[0];
echo "Top-level elType: " . $first82['elType'] . "\n";
echo "Top-level settings keys: " . implode( ', ', array_keys( $first82['settings'] ) ) . "\n";
echo "Column count: " . count( $first82['elements'] ) . "\n";
$col82 = $first82['elements'][0];
echo "Column elType: " . $col82['elType'] . "\n";
echo "Widget count in first column: " . count( $col82['elements'] ) . "\n";
$widget82 = $col82['elements'][0] ?? null;
if ( $widget82 ) {
    echo "First widget elType: " . $widget82['elType'] . "\n";
    echo "First widget widgetType: " . ( $widget82['widgetType'] ?? 'N/A' ) . "\n";
    echo "First widget settings keys: " . implode( ', ', array_keys( $widget82['settings'] ) ) . "\n";
    echo "First widget id: " . $widget82['id'] . "\n";
    echo "First widget elType again: " . var_export( $widget82['elType'], true ) . "\n";
}

// Check if post_content is interfering
echo "\n=== Post Content Check ===\n";
foreach ( [ 82, 84 ] as $pid ) {
    $page = get_post( $pid );
    echo "Page $pid post_content length: " . strlen( $page->post_content ) . "\n";
    echo "Page $pid post_content is empty: " . ( empty( trim( $page->post_content ) ) ? 'YES' : 'NO' ) . "\n";
}

// Check what render actually produces
echo "\n=== Actual Render Output (first 1000 chars) ===\n";
$page = get_post( 82 );
$rendered = apply_filters( 'the_content', $page->post_content );
echo substr( $rendered, 0, 1000 ) . "\n";
