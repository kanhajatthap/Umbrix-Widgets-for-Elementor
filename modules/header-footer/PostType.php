<?php
namespace BDEA\Modules\HeaderFooter;

defined( 'ABSPATH' ) || exit;

class PostType {

    public function __construct() {
        $this->register();
        add_action( 'admin_init', [ $this, 'hide_editor_support' ] );
    }

    public function register() {
        $labels = [
            'name'               => __( 'Header & Footer', 'bdea' ),
            'singular_name'      => __( 'Template', 'bdea' ),
            'add_new'            => __( 'Add New', 'bdea' ),
            'add_new_item'       => __( 'Add New Template', 'bdea' ),
            'edit_item'          => __( 'Edit Template', 'bdea' ),
            'view_item'          => __( 'View Template', 'bdea' ),
            'search_items'       => __( 'Search Templates', 'bdea' ),
            'not_found'          => __( 'No templates found', 'bdea' ),
            'not_found_in_trash' => __( 'No templates found in Trash', 'bdea' ),
            'all_items'          => __( 'Header & Footer', 'bdea' ),
        ];

        $args = [
            'labels'              => $labels,
            'public'              => false,
            'publicly_queryable'  => is_super_admin(),
            'show_ui'             => true,
            'show_in_menu'        => false,
            'show_in_admin_bar'   => false,
            'show_in_nav_menus'   => false,
            'exclude_from_search' => true,
            'capability_type'     => 'post',
            'map_meta_cap'        => true,
            'hierarchical'        => false,
            'supports'            => [ 'title', 'revisions', 'author' ],
            'menu_icon'           => 'dashicons-editor-kitchensink',
            'rewrite'             => false,
            'query_var'           => false,
        ];

        register_post_type( 'bdea_header_footer', $args );

        add_post_type_support( 'bdea_header_footer', 'elementor' );
    }

    public function hide_editor_support() {
        remove_post_type_support( 'bdea_header_footer', 'editor' );
        remove_post_type_support( 'bdea_header_footer', 'comments' );
        remove_post_type_support( 'bdea_header_footer', 'custom-fields' );
        remove_post_type_support( 'bdea_header_footer', 'trackbacks' );
    }
}
