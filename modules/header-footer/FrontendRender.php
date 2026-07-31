<?php
namespace BDEA\Modules\HeaderFooter;

use BDEA\Framework\Cache\Cache;
use BDEA\Framework\Renderer\TemplateRenderer;

defined( 'ABSPATH' ) || exit;

class FrontendRender {

    private $renderer;
    private $cache;

    private $disable_theme = false;

    public function __construct( TemplateRenderer $renderer, Cache $cache ) {
        $this->renderer = $renderer;
        $this->cache    = $cache;

        add_action( 'wp', [ $this, 'check_disable_theme' ] );
        add_action( 'wp_body_open', [ $this, 'render_header' ], 0 );
        add_action( 'wp_footer', [ $this, 'render_footer' ], 0 );
        add_action( 'wp_enqueue_scripts', [ $this, 'suppress_theme_css' ], 999 );
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
    }

    public function check_disable_theme() {
        if ( ! class_exists( '\Elementor\Plugin' ) ) {
            return;
        }

        $plugin = \Elementor\Plugin::$instance;
        if ( $plugin->preview->is_preview_mode() || $plugin->editor->is_edit_mode() ) {
            return;
        }

        $header_id = $this->renderer->get_matching_template_id( 'header' );
        $footer_id = $this->renderer->get_matching_template_id( 'footer' );

        foreach ( [ $header_id, $footer_id ] as $id ) {
            if ( $id && 'yes' === get_post_meta( $id, '_bdea_hf_disable_theme', true ) ) {
                $this->disable_theme = true;
                break;
            }
        }
    }

    public function suppress_theme_css() {
        if ( ! $this->disable_theme ) {
            return;
        }

        if ( ! wp_style_is( 'bdea-hf-frontend', 'enqueued' ) ) {
            return;
        }

        wp_add_inline_style( 'bdea-hf-frontend', '
            header.site-header, .site-header, #site-header, #masthead, .header,
            footer.site-footer, .site-footer, #site-footer, #colophon, .footer {
                display: none !important;
            }
        ' );
    }

    private function check_device_visibility( $post_id ) {
        $device_vis = get_post_meta( $post_id, '_bdea_hf_device_visibility', true );

        if ( ! is_array( $device_vis ) ) {
            return true;
        }

        $is_desktop = ! empty( $device_vis['desktop'] );
        $is_tablet  = ! empty( $device_vis['tablet'] );
        $is_mobile  = ! empty( $device_vis['mobile'] );

        if ( $is_desktop && $is_tablet && $is_mobile ) {
            return true;
        }

        $detect = false;
        if ( isset( $_SERVER['HTTP_USER_AGENT'] ) ) {
            $ua = $_SERVER['HTTP_USER_AGENT'];
            $is_desktop_ua = ! preg_match( '/Mobile|Android|iPad|iPhone|iPod|Tablet/i', $ua );
            $is_tablet_ua  = preg_match( '/iPad|Tablet|Android(?!.*Mobile)/i', $ua );
            $is_mobile_ua  = preg_match( '/Mobile|iPhone|iPod|Android.*Mobile/i', $ua );

            if ( $is_desktop_ua && $is_desktop ) {
                $detect = true;
            } elseif ( $is_tablet_ua && $is_tablet ) {
                $detect = true;
            } elseif ( $is_mobile_ua && $is_mobile ) {
                $detect = true;
            }
        }

        return $detect;
    }

    public function render_header() {
        if ( ! class_exists( '\Elementor\Plugin' ) ) {
            return;
        }

        $plugin = \Elementor\Plugin::$instance;

        if ( $plugin->preview->is_preview_mode() || $plugin->editor->is_edit_mode() ) {
            return;
        }

        $post_id = $this->renderer->get_matching_template_id( 'header' );

        if ( $post_id && $this->check_device_visibility( $post_id ) ) {
            $sticky      = get_post_meta( $post_id, '_bdea_hf_sticky', true ) === 'yes';
            $scroll_anim = get_post_meta( $post_id, '_bdea_hf_scroll_animation', true ) === 'yes';
            $classes     = 'bdea-hf-header';
            $attrs       = '';

            if ( $sticky ) {
                $classes .= ' bdea-hf-sticky';
            }

            if ( $scroll_anim ) {
                $classes .= ' bdea-hf-scroll-anim';
            }

            echo '<header class="' . esc_attr( $classes ) . '"' . $attrs . '>';
            $this->renderer->render( $post_id );
            echo '</header>';
        }
    }

    public function render_footer() {
        if ( ! class_exists( '\Elementor\Plugin' ) ) {
            return;
        }

        $plugin = \Elementor\Plugin::$instance;

        if ( $plugin->preview->is_preview_mode() || $plugin->editor->is_edit_mode() ) {
            return;
        }

        $post_id = $this->renderer->get_matching_template_id( 'footer' );

        if ( $post_id && $this->check_device_visibility( $post_id ) ) {
            echo '<footer class="bdea-hf-footer">';
            $this->renderer->render( $post_id );
            echo '</footer>';
        }
    }

    public function enqueue_scripts() {
        if ( ! class_exists( '\Elementor\Plugin' ) ) {
            return;
        }

        $header_id = $this->renderer->get_matching_template_id( 'header' );
        $has_sticky = false;

        if ( $header_id ) {
            $has_sticky = get_post_meta( $header_id, '_bdea_hf_sticky', true ) === 'yes';
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

        if ( $has_sticky ) {
            wp_add_inline_style( 'bdea-hf-frontend', '
                .bdea-hf-sticky {
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    z-index: 9999;
                }
                .admin-bar .bdea-hf-sticky {
                    top: 32px;
                }
                @media screen and (max-width: 782px) {
                    .admin-bar .bdea-hf-sticky {
                        top: 46px;
                    }
                }
                .bdea-hf-scroll-anim {
                    transition: transform 0.3s ease;
                }
                .bdea-hf-scroll-anim.bdea-hf-hidden {
                    transform: translateY(-100%);
                }
            ' );
        }
    }
}
