<?php
if ( ! defined( 'ABSPATH' ) ) {
    require_once dirname( __DIR__, 4 ) . '/wp-load.php';
}

global $wpdb;

foreach ( [ 82, 84 ] as $pid ) {
    $mode = $wpdb->get_var( $wpdb->prepare(
        "SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id=%d AND meta_key='_elementor_edit_mode'",
        $pid
    ) );
    $data_len = $wpdb->get_var( $wpdb->prepare(
        "SELECT LENGTH(meta_value) FROM {$wpdb->postmeta} WHERE post_id=%d AND meta_key='_elementor_data'",
        $pid
    ) );
    $ver = $wpdb->get_var( $wpdb->prepare(
        "SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id=%d AND meta_key='_elementor_version'",
        $pid
    ) );
    echo "Page $pid: mode='$mode' data={$data_len}b ver=$ver\n";
}

// Check if styles are registered
global $wp_styles;
$registered = [];
foreach ( $wp_styles->registered as $handle => $obj ) {
    if ( strpos( $handle, 'elementskey' ) !== false ) {
        $registered[] = $handle . ' => ' . $obj->src;
    }
}
echo "\nRegistered Elementor styles: " . count( $registered ) . "\n";
foreach ( $registered as $r ) echo "  $r\n";

// Check if styles are queued
$queued = [];
foreach ( $wp_styles->queue as $handle ) {
    if ( strpos( $handle, 'elementskey' ) !== false ) {
        $queued[] = $handle;
    }
}
echo "\nQueued Elementor styles: " . count( $queued ) . "\n";
foreach ( $queued as $q ) echo "  $q\n";

// Check if content-widgets.css exists and has content
$css_file = ELEMENTSKEY_PATH . 'assets/css/content-widgets.css';
$style_file = ELEMENTSKEY_PATH . 'assets/css/style.css';
echo "\ncontent-widgets.css: " . ( file_exists( $css_file ) ? filesize( $css_file ) . ' bytes' : 'MISSING' ) . "\n";
echo "style.css: " . ( file_exists( $style_file ) ? filesize( $style_file ) . ' bytes' : 'MISSING' ) . "\n";

// Check frontend page source for CSS links
$page_url = get_permalink( 82 );
echo "\nPage URL: $page_url\n";

// Simulate frontend load
if ( isset( $_SERVER ) ) {
    $_SERVER['HTTP_HOST'] = 'plugin-test.local';
    $_SERVER['REQUEST_URI'] = '/widgets-content/';
}

// Check what wp_head would output
ob_start();
do_action( 'wp_enqueue_scripts' );
$head = ob_get_clean();

$css_lines = [];
foreach ( explode( "\n", $head ) as $line ) {
    if ( strpos( $line, 'elementskey' ) !== false ) {
        $css_lines[] = trim( $line );
    }
}
echo "\nwp_enqueue_scripts output (elementskey lines): " . count( $css_lines ) . "\n";
foreach ( $css_lines as $l ) echo "  $l\n";
