<?php
if ( ! defined( 'ABSPATH' ) ) {
    require_once dirname( __DIR__, 4 ) . '/wp-load.php';
}

$pages = get_pages(['post_status' => 'publish', 'number' => 50]);
foreach ($pages as $p) {
    $has_el = get_post_meta($p->ID, '_elementor_edit_mode', true);
    echo "ID:{$p->ID} | {$p->post_title} | slug:{$p->post_name} | elementor:" . ($has_el ?: 'no') . PHP_EOL;
}
