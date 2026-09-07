<?php
/**
 * Fix _elementor_edit_mode for both pages and clear Elementor cache.
 */
if ( ! defined( 'ABSPATH' ) ) {
    require_once dirname( __DIR__, 4 ) . '/wp-load.php';
}

global $wpdb;

foreach ( [ 82, 84 ] as $pid ) {
    // Delete old meta and re-insert
    $wpdb->delete( $wpdb->postmeta, [ 'post_id' => $pid, 'meta_key' => '_elementor_edit_mode' ], [ '%d', '%s' ] );
    $wpdb->insert( $wpdb->postmeta, [ 'post_id' => $pid, 'meta_key' => '_elementor_edit_mode', 'meta_value' => 'builder' ], [ '%d', '%s' ] );

    $wpdb->delete( $wpdb->postmeta, [ 'post_id' => $pid, 'meta_key' => '_elementor_version' ], [ '%d', '%s' ] );
    $wpdb->insert( $wpdb->postmeta, [ 'post_id' => $pid, 'meta_key' => '_elementor_version', 'meta_value' => ELEMENTOR_VERSION ], [ '%d', '%s' ] );

    $wpdb->delete( $wpdb->postmeta, [ 'post_id' => $pid, 'meta_key' => '_wp_page_template' ], [ '%d', '%s' ] );
    $wpdb->insert( $wpdb->postmeta, [ 'post_id' => $pid, 'meta_key' => '_wp_page_template', 'meta_value' => '' ], [ '%d', '%s' ] );

    $mode = get_post_meta( $pid, '_elementor_edit_mode', true );
    echo "Page $pid: mode=$mode\n";
}

// Clear Elementor cache
if ( class_exists( '\Elementor\Plugin' ) ) {
    \Elementor\Plugin::$instance->files_manager->clear_cache();
    echo "Elementor cache cleared.\n";
}

// Force regenerate CSS
if ( class_exists( '\Elementor\Plugin' ) ) {
    $css_file = \Elementor\Plugin::$instance->css_file;
    if ( method_exists( $css_file, 'clear' ) ) {
        $css_file->clear();
        echo "CSS cache cleared.\n";
    }
}

echo "Done!\n";
