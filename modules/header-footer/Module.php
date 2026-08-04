<?php
namespace BDEA\Modules\HeaderFooter;

defined( 'ABSPATH' ) || exit;

class Module {

    const MODULE_SLUG = 'header_footer';

    private static $instance = null;

    private $cache;
    private $condition_manager;
    private $post_type;
    private $meta_box;
    private $admin;
    private $elementor_integration;
    private $renderer;
    private $frontend_render;

    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action( 'init', [ $this, 'init' ], 20 );
    }

    public function init() {
        if ( ! $this->is_module_enabled() ) {
            return;
        }

        $this->init_components();
    }

    public function is_module_enabled() {
        $settings = get_option( 'bdea_module_status', [] );
        $defaults = self::get_default_module_status();
        $status   = wp_parse_args( $settings, $defaults );
        return ! empty( $status[ self::MODULE_SLUG ] );
    }

    public static function get_default_module_status() {
        return [ self::MODULE_SLUG => 1 ];
    }

    public static function sanitize_module_status( $input ) {
        $defaults = self::get_default_module_status();

        if ( ! is_array( $input ) || empty( $input ) ) {
            $current = get_option( 'bdea_module_status', $defaults );
            return wp_parse_args( is_array( $current ) ? $current : [], $defaults );
        }

        $output = [];

        foreach ( $defaults as $key => $value ) {
            $output[ $key ] = ! empty( $input[ $key ] ) ? 1 : 0;
        }

        return $output;
    }

    private function init_components() {
        $this->cache             = new \BDEA\Framework\Cache\Cache();
        $this->condition_manager = new \BDEA\Framework\Conditions\ConditionManager();

        $this->load_class( 'PostType' );
        $this->post_type = new PostType();

        add_action( 'save_post_bdea_header_footer', [ $this, 'bust_cache_on_save' ] );
        add_action( 'wp_trash_post', [ $this, 'bust_cache_on_trash' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_frontend_assets' ] );

        if ( is_admin() ) {
            $this->load_admin_components();
        }

        $this->load_public_components();

        $this->register_hf_document_type();
    }

    private function load_admin_components() {
        if ( $this->load_class( 'Admin' ) ) {
            $this->admin = new Admin( $this->cache, $this->condition_manager );
        }

        if ( $this->load_class( 'MetaBox' ) ) {
            $this->meta_box = new MetaBox( $this->condition_manager );
        }
    }

    private function load_public_components() {
        $this->renderer = new \BDEA\Framework\Renderer\TemplateRenderer(
            $this->condition_manager,
            $this->cache
        );

        if ( $this->load_class( 'FrontendRender' ) ) {
            $this->frontend_render = new FrontendRender( $this->renderer, $this->cache );
        }
    }

    public function register_hf_document_type( $documents_manager = null ) {
        if ( ! $this->load_class( 'Document' ) ) {
            return;
        }

        if ( is_null( $documents_manager ) ) {
            if ( ! class_exists( '\Elementor\Plugin' ) ) {
                return;
            }
            $documents_manager = \Elementor\Plugin::$instance->documents;
        }

        if ( $documents_manager && ! $documents_manager->get_document_type( 'bdea-hf-document', false ) ) {
            $documents_manager->register_document_type( 'bdea-hf-document', Document::class );
        }
    }

    public function bust_cache_on_save( $post_id ) {
        $this->cache->flush_all();
    }

    public function bust_cache_on_trash( $post_id ) {
        if ( 'bdea_header_footer' === get_post_type( $post_id ) ) {
            $this->cache->flush_all();
        }
    }

    public function enqueue_frontend_assets() {
        if ( ! class_exists( '\Elementor\Plugin' ) ) {
            return;
        }

        $css_file = __DIR__ . '/assets/css/frontend.css';
        if ( file_exists( $css_file ) ) {
            wp_enqueue_style(
                'bdea-hf-frontend',
                plugin_dir_url( __FILE__ ) . 'assets/css/frontend.css',
                [],
                BDEA_VERSION
            );
        }
    }

    private function load_class( $name ) {
        $file = __DIR__ . '/' . $name . '.php';
        if ( file_exists( $file ) ) {
            require_once $file;
            return true;
        }
        return false;
    }

    public function get_cache() {
        return $this->cache;
    }

    public function get_condition_manager() {
        return $this->condition_manager;
    }
}
