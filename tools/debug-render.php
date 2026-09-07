<?php
if ( ! defined( 'ABSPATH' ) ) {
    require_once dirname( __DIR__, 4 ) . '/wp-load.php';
}

global $wpdb;

foreach ( [ 82, 84 ] as $pid ) {
    $page = get_post( $pid );
    echo "=== Page $pid: {$page->post_title} ===\n";

    // Check post_content
    $content = $page->post_content;
    echo "post_content length: " . strlen( $content ) . " bytes\n";
    echo "post_content first 200: " . substr( $content, 0, 200 ) . "\n\n";

    // Check all meta keys
    $metas = $wpdb->get_results( $wpdb->prepare(
        "SELECT meta_key, LENGTH(meta_value) as len FROM {$wpdb->postmeta} WHERE post_id=%d ORDER BY meta_key",
        $pid
    ), ARRAY_A );
    echo "Meta keys:\n";
    foreach ( $metas as $m ) {
        echo "  {$m['meta_key']} = {$m['len']} bytes\n";
    }
    echo "\n";

    // Simulate what happens on frontend
    // Set up the global post
    setup_postdata( $page );

    // Check if Elementor's frontend renderer is active
    echo "Elementor hooks on 'the_content':\n";
    global $wp_filter;
    if ( isset( $wp_filter['the_content'] ) ) {
        foreach ( $wp_filter['the_content']->callbacks as $priority => $hooks ) {
            foreach ( $hooks as $hook ) {
                $func = is_array( $hook['function'] ) ? get_class( $hook['function'][0] ) . '::' . $hook['function'][1] : $hook['function'];
                if ( is_string( $func ) && ( strpos( $func, 'elementor' ) !== false || strpos( $func, 'Elementor' ) !== false ) ) {
                    echo "  [$priority] $func\n";
                }
            }
        }
    }

    // Try to render the content like Elementor would
    echo "\nRendering with Elementor:\n";
    $rendered = apply_filters( 'the_content', $page->post_content );
    echo "Rendered length: " . strlen( $rendered ) . " bytes\n";
    echo "Contains elementor-section: " . ( strpos( $rendered, 'elementor-section' ) !== false ? 'YES' : 'NO' ) . "\n";
    echo "Contains elementor-widget: " . ( strpos( $rendered, 'elementor-widget' ) !== false ? 'YES' : 'NO' ) . "\n";
    echo "Contains elementskey-: " . ( strpos( $rendered, 'elementskey-' ) !== false ? 'YES' : 'NO' ) . "\n";

    // Check for Elementor CSS files in uploads
    $upload_dir = wp_upload_dir();
    $ek_css_dir = $upload_dir['basedir'] . '/elementor/css';
    if ( is_dir( $ek_css_dir ) ) {
        $css_files = glob( $ek_css_dir . '/*.css' );
        echo "\nElementor CSS files in uploads: " . count( $css_files ) . "\n";
        if ( ! empty( $css_files ) ) {
            echo "Latest: " . basename( end( $css_files ) ) . " (" . filesize( end( $css_files ) ) . " bytes)\n";
        }
    } else {
        echo "\nNo Elementor CSS directory found!\n";
    }

    wp_reset_postdata();
    echo "\n";
}
