<?php
/**
 * One-time migration: move old bdea_header_footer templates to the new
 * 'builder' post type and backfill template type + conditions meta.
 */
$wp_root = 'C:\Users\india\Local Sites\plugin-test\app\public';
require_once $wp_root . '/wp-load.php';

if ( ! post_type_exists( 'builder' ) ) {
    echo "[SKIP] post type 'builder' not registered - plugin may be inactive." . PHP_EOL;
    exit( 1 );
}

$old = get_posts( [
    'post_type'      => 'bdea_header_footer',
    'post_status'    => 'any',
    'posts_per_page' => -1,
] );

if ( empty( $old ) ) {
    echo "[DONE] No bdea_header_footer posts found - nothing to migrate." . PHP_EOL;
    exit( 0 );
}

foreach ( $old as $post ) {
    $title_lower = strtolower( $post->post_title );
    if ( false !== strpos( $title_lower, 'header' ) ) {
        $type = 'header';
    } elseif ( false !== strpos( $title_lower, 'footer' ) ) {
        $type = 'footer';
    } elseif ( isset( $map[ $post->ID ] ) ) {
        $type = $map[ $post->ID ];
    } else {
        $type = '';
    }

    if ( ! $type ) {
        echo "[SKIP] {$post->ID} '{$post->post_title}' - could not determine type." . PHP_EOL;
        continue;
    }

    $updated = $GLOBALS['wpdb']->update(
        $GLOBALS['wpdb']->posts,
        [ 'post_type' => 'builder' ],
        [ 'ID' => $post->ID ],
        [ '%s' ],
        [ '%d' ]
    );

    if ( false === $updated ) {
        echo "[FAIL] Could not update post {$post->ID}." . PHP_EOL;
        continue;
    }

    update_post_meta( $post->ID, '_elementskey_hf_template_type', $type );
    update_post_meta( $post->ID, '_elementskey_hf_conditions', [ [ 'type' => 'include', 'condition' => 'entire_site' ] ] );

    clean_post_cache( $post->ID );

    echo "[OK] #{$post->ID} '{$post->post_title}' -> type={$type}, post_type=builder" . PHP_EOL;
}

echo "[DONE] Migration finished." . PHP_EOL;