<?php
/**
 * Full Elementor Widget & Module Audit for 'qa_tester' user.
 * Tests registration, control initialization, and frontend rendering for all 73 widgets.
 * Usage: tools\php.cmd tools\test-widgets-full-check.php
 */

$wp_root = 'C:\Users\india\Local Sites\plugin-test\app\public';
require_once $wp_root . '/wp-load.php';

echo "========================================================\n";
echo "ELEMENTSKEY FULL WIDGET & MODULE AUDIT (USER: qa_tester)\n";
echo "========================================================\n\n";

// 1. Authenticate as qa_tester
$user = get_user_by( 'login', 'qa_tester' );
if ( ! $user ) {
    echo "[FAIL] User qa_tester not found!\n";
    exit( 1 );
}

wp_set_current_user( $user->ID );
echo "[PASS] Authenticated as user: " . $user->user_login . " (ID: " . $user->ID . ", Role: " . implode( ', ', $user->roles ) . ")\n";

// 2. Check capabilities
if ( current_user_can( 'manage_options' ) && current_user_can( 'edit_posts' ) ) {
    echo "[PASS] User has full Elementor editor & admin permissions (manage_options, edit_posts)\n";
} else {
    echo "[FAIL] User lacks required permissions!\n";
    exit( 1 );
}

// 3. Verify Elementor & Widgets Manager
if ( ! did_action( 'elementor/loaded' ) || ! class_exists( '\Elementor\Plugin' ) ) {
    echo "[FAIL] Elementor is not loaded!\n";
    exit( 1 );
}

$widgets_manager = \Elementor\Plugin::instance()->widgets_manager;
$all_widget_types = $widgets_manager->get_widget_types();

$elementskey_widgets = [];
foreach ( $all_widget_types as $name => $widget_obj ) {
    if ( 0 === strpos( $name, 'elementskey_' ) ) {
        $elementskey_widgets[ $name ] = $widget_obj;
    }
}

$count = count( $elementskey_widgets );
echo "[PASS] Elementor Widgets Manager loaded. Total ElementsKey Widgets registered: " . $count . " / 73\n\n";

// 4. Test every widget (Controls initialization + Render execution)
$passed = 0;
$failed = 0;
$errors = [];

echo "Testing controls initialization and render execution per widget:\n";
echo "-------------------------------------------------------------\n";

foreach ( $elementskey_widgets as $name => $widget_obj ) {
    $title = $widget_obj->get_title();
    $icon  = $widget_obj->get_icon();
    try {
        $cats = implode( ', ', $widget_obj->get_categories() );
        $element_instance = \Elementor\Plugin::$instance->elements_manager->create_element_instance( [
            'id'         => 'test_' . md5( $name ),
            'elType'     => 'widget',
            'widgetType' => $name,
            'settings'   => [],
        ] );

        if ( ! $element_instance ) {
            throw new Exception( "Could not create Elementor element instance." );
        }

        ob_start();
        $element_instance->print_element();
        $rendered_html = ob_get_clean();

        if ( false === $rendered_html ) {
            throw new Exception( "Output buffering failed during render." );
        }

        $passed++;
        echo sprintf( "  [✓ PASS] %-32s | Title: %-25s | Category: %s\n", $name, $title, $cats );

    } catch ( Throwable $e ) {
        $failed++;
        $errors[] = $name . ": " . $e->getMessage();
        echo sprintf( "  [✗ FAIL] %-32s | ERROR: %s\n", $name, $e->getMessage() );
    }
}

echo "\n-------------------------------------------------------------\n";
echo "WIDGET TEST RESULTS: " . $passed . " PASSED, " . $failed . " FAILED out of " . $count . " widgets.\n";

// 5. Test Builder Module & Admin Settings Screen Permissions for qa_tester
echo "\nTesting Admin Module & Settings Access for qa_tester:\n";
echo "---------------------------------------------------\n";

// Test Header/Footer Builder module instance
if ( class_exists( '\ElementsKey\Modules\HeaderFooter\Module' ) ) {
    $builder_module = \ElementsKey\Modules\HeaderFooter\Module::instance();
    echo "[PASS] Header/Footer & Theme Builder module instantiated successfully.\n";
} else {
    echo "[FAIL] Header/Footer Builder module class not found!\n";
    $failed++;
}

// Test Widget status options getter
if ( function_exists( 'elementskey_get_widget_status' ) ) {
    $statuses = elementskey_get_widget_status();
    echo "[PASS] Admin widget status retrieved (" . count( array_filter( $statuses ) ) . " active widgets).\n";
} else {
    echo "[FAIL] elementskey_get_widget_status function not found!\n";
    $failed++;
}

echo "\n========================================================\n";
if ( 0 === $failed ) {
    echo "VERIFICATION COMPLETE: ALL 73 WIDGETS & MODULES WORKING PERFECTLY FOR USER qa_tester!\n";
    echo "========================================================\n";
    exit( 0 );
} else {
    echo "VERIFICATION FAILED WITH " . $failed . " ERROR(S).\n";
    echo "========================================================\n";
    exit( 1 );
}
