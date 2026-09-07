<?php
if ( ! defined( 'ABSPATH' ) ) {
    require_once dirname( __DIR__, 4 ) . '/wp-load.php';
}

echo "Elementor loaded: " . ( did_action( 'elementor/loaded' ) ? 'YES' : 'NO' ) . "\n";
echo "Active: " . ( is_plugin_active( 'elementskey/elementskey-elementor-addons.php' ) ? 'YES' : 'NO' ) . "\n";

if ( did_action( 'elementor/loaded' ) ) {
    $manager = \Elementor\Plugin::$instance->widgets_manager;
    $all = $manager->get_widget_types_config();
    $ek = [];
    foreach ( $all as $name => $cfg ) {
        if ( strpos( $name, 'elementskey_' ) === 0 ) {
            $ek[] = $name;
        }
    }
    echo "Registered widgets: " . count( $ek ) . "\n";
    echo implode( ", ", array_slice( $ek, 0, 15 ) ) . "\n";
}

$page = get_post( 82 );
echo "\nPage 82: " . ( $page ? $page->post_title : 'NOT FOUND' ) . "\n";
$mode = get_post_meta( 82, '_elementor_edit_mode', true );
$data = get_post_meta( 82, '_elementor_data', true );
echo "Mode: " . ( $mode ?: 'none' ) . "\n";
echo "Data: " . strlen( $data ) . " bytes\n";
$decoded = json_decode( $data, true );
if ( is_array( $decoded ) ) {
    echo "Sections: " . count( $decoded ) . "\n";
    $widget_types = [];
    foreach ( $decoded as $sec ) {
        foreach ( $sec['elements'] ?? [] as $col ) {
            foreach ( $col['elements'] ?? [] as $w ) {
                if ( isset( $w['widgetType'] ) ) {
                    $widget_types[] = $w['widgetType'];
                }
            }
        }
    }
    echo "Widget types in data: " . count( $widget_types ) . "\n";
    echo "Types: " . implode( ", ", array_unique( $widget_types ) ) . "\n";
}

echo "\nCSS files:\n";
echo "style.css: " . ( file_exists( ELEMENTSKEY_PATH . 'assets/css/style.css' ) ? 'YES' : 'NO' ) . "\n";
echo "content-widgets.css: " . ( file_exists( ELEMENTSKEY_PATH . 'assets/css/content-widgets.css' ) ? 'YES' : 'NO' ) . "\n";
echo "share-it.css: " . ( file_exists( ELEMENTSKEY_PATH . 'assets/css/share-it.css' ) ? 'YES' : 'NO' ) . "\n";
echo "editor.css: " . ( file_exists( ELEMENTSKEY_PATH . 'assets/css/editor.css' ) ? 'YES' : 'NO' ) . "\n";

// Check what CSS is actually enqueued on frontend
global $wp_styles;
$ek_styles = [];
foreach ( $wp_styles->queue as $handle ) {
    if ( strpos( $handle, 'elementskey' ) !== false ) {
        $ek_styles[] = $handle;
    }
}
echo "\nEnqueued styles: " . implode( ", ", $ek_styles ) . "\n";
