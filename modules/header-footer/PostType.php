<?php
/**
 * ElementStack Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */
namespace BDEA\Modules\HeaderFooter;

defined( 'ABSPATH' ) || exit;

class PostType {

    public function __construct() {
        $this->register();
        add_action( 'admin_init', [ $this, 'hide_editor_support' ] );
    }

    public function register() {
        $labels = [
            'name'               => __( 'Theme Builder', 'elementstack-elementor-addons' ),
            'singular_name'      => __( 'Template', 'elementstack-elementor-addons' ),
            'add_new'            => __( 'Add New', 'elementstack-elementor-addons' ),
            'add_new_item'       => __( 'Add New Template', 'elementstack-elementor-addons' ),
            'edit_item'          => __( 'Edit Template', 'elementstack-elementor-addons' ),
            'view_item'          => __( 'View Template', 'elementstack-elementor-addons' ),
            'search_items'       => __( 'Search Templates', 'elementstack-elementor-addons' ),
            'not_found'          => __( 'No templates found', 'elementstack-elementor-addons' ),
            'not_found_in_trash' => __( 'No templates found in Trash', 'elementstack-elementor-addons' ),
            'all_items'          => __( 'Theme Builder', 'elementstack-elementor-addons' ),
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
