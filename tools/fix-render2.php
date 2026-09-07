<?php
if ( ! defined( 'ABSPATH' ) ) {
    require_once dirname( __DIR__, 4 ) . '/wp-load.php';
}

global $wpdb;

// Check if post_content really cleared
foreach ( [ 82, 84 ] as $pid ) {
    $raw = $wpdb->get_var( $wpdb->prepare( "SELECT post_content FROM {$wpdb->posts} WHERE ID=%d", $pid ) );
    echo "Page $pid raw post_content length: " . strlen( $raw ) . "\n";
    echo "  First 100: " . substr( $raw, 0, 100 ) . "\n";
}

// Force clear using direct SQL
echo "\n=== Force clearing post_content ===\n";
$wpdb->query( "UPDATE {$wpdb->posts} SET post_content='' WHERE ID IN (82,84)" );
$wpdb->query( "UPDATE {$wpdb->posts} SET post_content_gist=NULL WHERE ID IN (82,84)" );

foreach ( [ 82, 84 ] as $pid ) {
    // Force re-read
    clean_post_cache( $pid );
    $page = get_post( $pid );
    echo "Page $pid after clear: post_content length = " . strlen( $page->post_content ) . "\n";
    echo "  is_empty: " . ( $page->post_content === '' ? 'YES (exact empty)' : 'NO' ) . "\n";
    echo "  trimmed: '" . trim( $page->post_content ) . "'\n";
}

// Check what Elementor's apply_builder_in_content actually sees
echo "\n=== Elementor Builder Hook Check ===\n";
if ( class_exists( '\Elementor\Frontend' ) ) {
    $frontend = \Elementor\Plugin::$instance->frontend;
    echo "Frontend class loaded: YES\n";
    
    // Check if is_builder_mode works
    foreach ( [ 82, 84 ] as $pid ) {
        $page = get_post( $pid );
        setup_postdata( $page );
        
        $is_builder = $frontend->is_builder_mode( $page );
        echo "Page $pid is_builder_mode: " . ( $is_builder ? 'YES' : 'NO' ) . "\n";
        
        // Try to get builder content directly
        $content = $frontend->get_builder_content( $pid, true );
        echo "Page $pid get_builder_content length: " . strlen( $content ) . "\n";
        if ( strlen( $content ) > 0 ) {
            echo "  Has section: " . ( strpos( $content, 'elementor-section' ) !== false ? 'YES' : 'NO' ) . "\n";
            echo "  Has widget: " . ( strpos( $content, 'elementor-widget' ) !== false ? 'YES' : 'NO' ) . "\n";
            echo "  First 500: " . substr( $content, 0, 500 ) . "\n";
        }
        
        wp_reset_postdata();
    }
}
