<?php
if ( ! defined( 'ABSPATH' ) ) {
    require_once dirname( __DIR__, 4 ) . '/wp-load.php';
}

global $wpdb;

// Force Elementor to regenerate CSS for pages 82 and 84
if ( class_exists( '\Elementor\Plugin' ) ) {
    $file_manager = \Elementor\Plugin::$instance->files_manager;

    foreach ( [ 82, 84 ] as $pid ) {
        // Clear the cached CSS meta so Elementor regenerates
        $wpdb->delete( $wpdb->postmeta, [
            'post_id'  => $pid,
            'meta_key' => '_elementor_css',
        ], [ '%d', '%s' ] );

        // Also clear page assets
        $wpdb->delete( $wpdb->postmeta, [
            'post_id'  => $pid,
            'meta_key' => '_elementor_page_assets',
        ], [ '%d', '%s' ] );

        echo "Cleared CSS meta for page $pid\n";
    }

    // Clear global Elementor CSS cache
    $upload_dir = wp_upload_dir();
    $css_dir = $upload_dir['basedir'] . '/elementor/css';
    if ( is_dir( $css_dir ) ) {
        $files = glob( $css_dir . '/*.css' );
        foreach ( $files as $f ) {
            unlink( $f );
        }
        echo "Deleted " . count( $files ) . " CSS files from $css_dir\n";
    }

    // Now regenerate CSS by forcing a render of each page
    foreach ( [ 82, 84 ] as $pid ) {
        $page = get_post( $pid );
        if ( ! $page ) continue;

        // Setup Elementor for this page
        \Elementor\Plugin::$instance->preview->update_post_settings( $page );
        
        // Force CSS generation
        $file_manager->clear_cache();
        
        // Render the page to trigger CSS generation
        \Elementor\Plugin::$instance->preview->update_post_settings( $page );
        ob_start();
        \Elementor\Plugin::$instance->preview->builder->render_display_mode_content();
        ob_end_clean();

        echo "Regenerated CSS for page $pid\n";
    }

    // Check if CSS files were created
    if ( is_dir( $css_dir ) ) {
        $files = glob( $css_dir . '/*.css' );
        echo "\nCSS files after regeneration: " . count( $files ) . "\n";
        foreach ( $files as $f ) {
            echo "  " . basename( $f ) . " (" . filesize( $f ) . " bytes)\n";
        }
    }
}

echo "\nDone! Hard-refresh pages in browser.\n";
