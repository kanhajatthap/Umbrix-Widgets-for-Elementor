<?php
if ( ! defined( 'ABSPATH' ) ) {
    require_once dirname( __DIR__, 4 ) . '/wp-load.php';
}

// Simulate a proper frontend request
global $wp;
$_SERVER['REQUEST_URI'] = '/widgets-content/';
$_SERVER['HTTP_HOST'] = 'plugin-test.local';
$_SERVER['SERVER_NAME'] = 'plugin-test.local';
$wp->parse_request();

// Make sure Elementor is loaded
if ( ! did_action( 'elementor/loaded' ) ) {
    do_action( 'elementor/loaded' );
}

// Force is_frontend
add_action( 'init', function() {
    // Make sure we're not in admin or REST
});

echo "=== Elementor Frontend Render Test ===\n\n";

$frontend = \Elementor\Plugin::$instance->frontend;

// Check what methods exist
$methods = get_class_methods( $frontend );
echo "Frontend methods containing 'build' or 'render':\n";
foreach ( $methods as $m ) {
    if ( strpos( $m, 'build' ) !== false || strpos( $m, 'render' ) !== false ) {
        echo "  $m\n";
    }
}

echo "\n";

foreach ( [ 82, 84 ] as $pid ) {
    echo "--- Page $pid ---\n";
    $page = get_post( $pid );
    
    // Set global post
    $wp->query_vars['page_id'] = $pid;
    $wp->get_query_vars();
    setup_postdata( $page );
    global $post;
    $post = $page;
    
    // Try get_builder_content
    $content = $frontend->get_builder_content( $pid, true );
    echo "get_builder_content: " . strlen( $content ) . " bytes\n";
    
    if ( strlen( $content ) > 0 ) {
        echo "  Has section: " . ( strpos( $content, 'elementor-section' ) !== false ? 'YES' : 'NO' ) . "\n";
        echo "  Has elementor-widget: " . ( strpos( $content, 'elementor-widget' ) !== false ? 'YES' : 'NO' ) . "\n";
        echo "  Has elementskey-: " . ( strpos( $content, 'elementskey-' ) !== false ? 'YES' : 'NO' ) . "\n";
        echo "  First 500: " . substr( $content, 0, 500 ) . "\n";
    } else {
        echo "  EMPTY - trying get_builder_content_for_display...\n";
        $content2 = $frontend->get_builder_content_for_display( $pid );
        echo "  get_builder_content_for_display: " . strlen( $content2 ) . " bytes\n";
        if ( strlen( $content2 ) > 0 ) {
            echo "  First 500: " . substr( $content2, 0, 500 ) . "\n";
        }
    }
    
    wp_reset_postdata();
    echo "\n";
}
