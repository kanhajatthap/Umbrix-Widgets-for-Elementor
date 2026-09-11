<?php
var_dump( post_type_exists( 'builder' ) );
register_post_type(
    'builder',
    [
        'labels' => [
            'name' => 'Theme Builder',
        ],
        'public' => false,
        'show_ui' => true,
        'supports' => [ 'title', 'author' ],
    ]
);
var_dump( post_type_exists( 'builder' ) );
$result = wp_insert_post( [
    'post_title' => 'Header Test',
    'post_type' => 'builder',
    'post_status' => 'publish',
] );
var_dump( $result );
if ( is_wp_error( $result ) ) {
    var_dump( $result->get_error_message() );
}
