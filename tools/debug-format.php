<?php
if ( ! defined( 'ABSPATH' ) ) {
    require_once dirname( __DIR__, 4 ) . '/wp-load.php';
}

// Check Elementor version and container support
echo "Elementor version: " . ELEMENTOR_VERSION . "\n";

// Check if containers are enabled
$containers_active = \Elementor\Plugin::$instance->experiments->is_feature_active( 'containers' );
echo "Containers active: " . ( $containers_active ? 'YES' : 'NO' ) . "\n";

// Check what data format the Widget Showcase page (ID 2) uses
$page2_data = get_post_meta( 2, '_elementor_data', true );
$decoded = json_decode( $page2_data, true );
if ( is_array( $decoded ) && ! empty( $decoded[0] ) ) {
    echo "\nWidget Showcase (ID 2) first element:\n";
    echo "  elType: " . ( $decoded[0]['elType'] ?? 'N/A' ) . "\n";
    if ( isset( $decoded[0]['elements'][0] ) ) {
        echo "  First column elType: " . ( $decoded[0]['elements'][0]['elType'] ?? 'N/A' ) . "\n";
    }
}

// Check page 82 data format
$page82_data = get_post_meta( 82, '_elementor_data', true );
$decoded82 = json_decode( $page82_data, true );
if ( is_array( $decoded82 ) && ! empty( $decoded82[0] ) ) {
    echo "\nPage 82 first element:\n";
    echo "  elType: " . ( $decoded82[0]['elType'] ?? 'N/A' ) . "\n";
    if ( isset( $decoded82[0]['elements'][0] ) ) {
        echo "  First column elType: " . ( $decoded82[0]['elements'][0]['elType'] ?? 'N/A' ) . "\n";
    }
}

// Check what the frontend actually renders
$page = get_post( 82 );
$rendered = apply_filters( 'the_content', $page->post_content );
echo "\nRendered content check:\n";
echo "  Has container: " . ( strpos( $rendered, 'e-con' ) !== false ? 'YES' : 'NO' ) . "\n";
echo "  Has section: " . ( strpos( $rendered, 'elementor-section' ) !== false ? 'YES' : 'NO' ) . "\n";
echo "  Has widget: " . ( strpos( $rendered, 'elementor-widget' ) !== false ? 'YES' : 'NO' ) . "\n";
echo "  Has elementskey: " . ( strpos( $rendered, 'elementskey-' ) !== false ? 'YES' : 'NO' ) . "\n";

// Check _elementor_template_type
$template_type = get_post_meta( 82, '_elementor_template_type', true );
echo "\nElementor template type: '$template_type'\n";
