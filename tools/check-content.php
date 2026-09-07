<?php
if ( ! defined( 'ABSPATH' ) ) { require_once dirname( __DIR__, 4 ) . '/wp-load.php'; }
global $wpdb;
foreach([82,84] as $pid) {
    $c = $wpdb->get_var($wpdb->prepare("SELECT post_content FROM {$wpdb->posts} WHERE ID=%d", $pid));
    echo "Page $pid post_content: " . strlen($c) . " bytes\n";
}
