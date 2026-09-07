<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */
namespace ElementsKey\Modules\HeaderFooter;

defined( 'ABSPATH' ) || exit;

class PostType {

    public function __construct() {
        $this->register();
        add_action( 'admin_init', [ $this, 'hide_editor_support' ] );
    }

    public function register() {
        $labels = [
            'name'               => __( 'Builder', 'elementskey' ),
            'singular_name'      => __( 'Template', 'elementskey' ),
            'add_new'            => __( 'Add New', 'elementskey' ),
            'add_new_item'       => __( 'Add New Template', 'elementskey' ),
            'edit_item'          => __( 'Edit Template', 'elementskey' ),
            'view_item'          => __( 'View Template', 'elementskey' ),
            'search_items'       => __( 'Search Templates', 'elementskey' ),
            'not_found'          => __( 'No templates found', 'elementskey' ),
            'not_found_in_trash' => __( 'No templates found in Trash', 'elementskey' ),
            'all_items'          => __( 'Builder', 'elementskey' ),
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

        register_post_type( 'elementskey_header_footer', $args );

        add_post_type_support( 'elementskey_header_footer', 'elementor' );
    }

    public function hide_editor_support() {
        remove_post_type_support( 'elementskey_header_footer', 'editor' );
        remove_post_type_support( 'elementskey_header_footer', 'comments' );
        remove_post_type_support( 'elementskey_header_footer', 'custom-fields' );
        remove_post_type_support( 'elementskey_header_footer', 'trackbacks' );
    }
}
