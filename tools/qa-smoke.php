<?php
/**
 * Smoke test: boots WordPress and verifies the plugin loads and registers widgets.
 * Usage: tools\php.cmd tools\qa-smoke.php
 */
$wp_root = 'C:\Users\india\Local Sites\plugin-test\app\public';
require_once $wp_root . '/wp-load.php';

$fail = 0;
$ok   = function ( $msg ) { echo "[PASS] {$msg}" . PHP_EOL; };
$bad  = function ( $msg ) use ( &$fail ) { echo "[FAIL] {$msg}" . PHP_EOL; $fail++; };

$ok( 'WordPress booted ' . get_bloginfo( 'version' ) );

if ( ! defined( 'ELEMENTSKEY_PATH' ) ) {
    $bad( 'Plugin constants not defined (ELEMENTSKEY_PATH). Is the plugin active?' );
    exit( 1 );
}
$ok( 'Plugin constants defined (ELEMENTSKEY_PATH/ELEMENTSKEY_URL/ELEMENTSKEY_VERSION)' );

$widgets_dir = ELEMENTSKEY_PATH . 'widgets';
$widget_files = glob( $widgets_dir . '/*.php' );
$ok( count( $widget_files ) . ' widget files found in widgets/' );

if ( ! did_action( 'elementor/loaded' ) ) {
    $bad( 'Elementor is not loaded' );
    exit( 1 );
}
$ok( 'Elementor loaded' );

if ( ! class_exists( '\Elementor\Plugin' ) ) {
    $bad( 'Elementor\Plugin class missing' );
    exit( 1 );
}

$manager = \Elementor\Plugin::instance()->widgets_manager;
if ( method_exists( $manager, 'get_widget_types' ) ) {
    $registered = $manager->get_widget_types();
    $ours = array_filter(
        array_keys( $registered ),
        function ( $k ) { return false !== strpos( strtolower( $k ), 'elementskey' ); }
    );
    if ( $ours ) {
        $ok( 'Registered Elementor widgets (' . count( $ours ) . '): ' . implode( ', ', array_slice( $ours, 0, 12 ) ) . ( count( $ours ) > 12 ? '...' : '' ) );
    } else {
        $bad( 'No ElementsKey widgets registered in Elementor' );
    }
}

echo PHP_EOL;
if ( $fail ) {
    echo "SMOKE TEST FAILED: {$fail} check(s) failed." . PHP_EOL;
    exit( 1 );
}
echo 'SMOKE TEST PASSED.' . PHP_EOL;
exit( 0 );
