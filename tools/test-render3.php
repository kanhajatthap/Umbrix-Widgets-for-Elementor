<?php
if ( ! defined( 'ABSPATH' ) ) {
    require_once dirname( __DIR__, 4 ) . '/wp-load.php';
}

global $wp;
$wp->init();

foreach ( [ 82, 84 ] as $pid ) {
    echo "--- Page $pid ---\n";
    
    $frontend = \Elementor\Plugin::$instance->frontend;
    
    // Use get_builder_content directly
    $content = $frontend->get_builder_content( $pid, true );
    echo "get_builder_content: " . strlen( $content ) . " bytes\n";
    
    if ( strlen( $content ) > 0 ) {
        echo "  Has elementor-section: " . ( strpos( $content, 'elementor-section' ) !== false ? 'YES' : 'NO' ) . "\n";
        echo "  Has elementor-column: " . ( strpos( $content, 'elementor-column' ) !== false ? 'YES' : 'NO' ) . "\n";
        echo "  Has elementor-widget-wrap: " . ( strpos( $content, 'elementor-widget-wrap' ) !== false ? 'YES' : 'NO' ) . "\n";
        echo "  Has elementor-widget: " . ( strpos( $content, 'elementor-widget' ) !== false ? 'YES' : 'NO' ) . "\n";
        echo "  Has elementskey-: " . ( strpos( $content, 'elementskey-' ) !== false ? 'YES' : 'NO' ) . "\n";
        echo "  First 500: " . substr( $content, 0, 500 ) . "\n";
    } else {
        echo "  EMPTY!\n";
        
        // Check if data is valid
        $data = get_post_meta( $pid, '_elementor_data', true );
        $decoded = json_decode( $data, true );
        echo "  _elementor_data valid JSON: " . ( $decoded ? 'YES' : 'NO' ) . "\n";
        echo "  _elementor_data sections: " . count( $decoded ) . "\n";
        
        // Check if Elementor Kit is loaded
        echo "  Kit loaded: " . ( \Elementor\Plugin::$instance->kits_manager->get_current_kit_id() ? 'YES' : 'NO' ) . "\n";
        
        // Check for errors
        echo "  Last error: " . print_r( error_get_last(), true ) . "\n";
    }
    
    echo "\n";
}
