<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */
namespace ElementsKey\Modules\HeaderFooter;

use ElementsKey\Framework\Cache\Cache;
use ElementsKey\Framework\Renderer\TemplateRenderer;

defined( 'ABSPATH' ) || exit;

class FrontendRender {

    private $renderer;
    private $cache;

    private $disable_theme = false;
    private $current_404_id = 0;
    private $current_archive_id = 0;

    public function __construct( TemplateRenderer $renderer, Cache $cache ) {
        $this->renderer = $renderer;
        $this->cache    = $cache;

        add_action( 'wp', [ $this, 'check_disable_theme' ] );
        add_action( 'wp_body_open', [ $this, 'render_announcement' ], -5 );
        add_action( 'wp_body_open', [ $this, 'render_header' ], 0 );
        add_action( 'wp_footer', [ $this, 'render_footer' ], 0 );
        add_action( 'wp_footer', [ $this, 'render_bottom_bar' ], 100 );
        add_action( 'wp_enqueue_scripts', [ $this, 'suppress_theme_css' ], 999 );
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
        add_filter( 'the_content', [ $this, 'filter_single_content' ], 10 );
        add_filter( 'template_include', [ $this, 'override_archive_template' ], 15 );
        add_action( 'elementskey_hf_render_archive', [ $this, 'render_archive_content' ] );
        add_filter( 'template_include', [ $this, 'override_404_template' ], 20 );
        add_action( 'elementskey_hf_render_404', [ $this, 'render_404_content' ] );
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
            if ( $id && $this->check_schedule( $id ) && 'yes' === $this->get_setting( $id, 'disable_theme' ) ) {
                $this->disable_theme = true;
                break;
            }
        }
    }

    public function suppress_theme_css() {
        if ( ! $this->disable_theme ) {
            return;
        }

        if ( ! wp_style_is( 'elementskey-hf-frontend', 'enqueued' ) ) {
            return;
        }

        wp_add_inline_style( 'elementskey-hf-frontend', '
            header.site-header, .site-header, #site-header, #masthead, .header,
            footer.site-footer, .site-footer, #site-footer, #colophon, .footer {
                display: none !important;
            }
        ' );
    }

    private function get_setting( $post_id, $key, $default = '' ) {
        $page_settings = get_post_meta( $post_id, '_elementor_page_settings', true );

        if ( is_array( $page_settings ) && array_key_exists( $key, $page_settings ) ) {
            return '' !== $page_settings[ $key ] ? $page_settings[ $key ] : $default;
        }

        $legacy = get_post_meta( $post_id, '_elementskey_hf_' . $key, true );

        return '' !== $legacy ? $legacy : $default;
    }

    private function check_device_visibility( $post_id ) {
        $device_vis = get_post_meta( $post_id, '_elementskey_hf_device_visibility', true );

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
            $ua = sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) );
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

    public function render_announcement() {
        if ( ! $this->can_render() ) {
            return;
        }

        $post_id = $this->renderer->get_matching_template_id( 'announcement' );

        if ( ! $post_id || ! $this->check_schedule( $post_id ) || ! $this->check_device_visibility( $post_id ) ) {
            return;
        }

        if ( $this->is_announcement_dismissed( $post_id ) ) {
            return;
        }

        $dismissible = 'yes' === $this->get_setting( $post_id, 'dismissible' );
        $cookie_days = max( 1, (int) $this->get_setting( $post_id, 'cookie_days', 1 ) );
        $classes     = 'elementskey-hf-announcement';

        echo '<div class="' . esc_attr( $classes ) . '" data-id="' . esc_attr( $post_id ) . '">';
        $this->renderer->render( $post_id );

        if ( $dismissible ) {
            echo '<button type="button" class="elementskey-hf-announcement-close" aria-label="' . esc_attr__( 'Dismiss', 'elementskey' ) . '" data-days="' . esc_attr( $cookie_days ) . '">';
            echo '<svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M3.5 3.5l7 7M10.5 3.5l-7 7"/></svg>';
            echo '</button>';
        }

        echo '</div>';
    }

    public function render_header() {
        if ( ! $this->can_render() ) {
            return;
        }

        $post_id = $this->renderer->get_matching_template_id( 'header' );

        if ( ! $post_id || ! $this->check_schedule( $post_id ) || ! $this->check_device_visibility( $post_id ) ) {
            return;
        }

        $sticky       = 'yes' === $this->get_setting( $post_id, 'sticky' );
        $transparent  = 'yes' === $this->get_setting( $post_id, 'transparent' );
        $shrink       = 'yes' === $this->get_setting( $post_id, 'sticky_shrink' );
        $hide_scroll  = 'yes' === $this->get_setting( $post_id, 'sticky_hide_scroll' );
        $logo_switch  = 'yes' === $this->get_setting( $post_id, 'logo_switcher' );
        $scroll_anim  = 'yes' === $this->get_setting( $post_id, 'scroll_animation' );
        $offset       = absint( $this->get_setting( $post_id, 'sticky_offset', 0 ) );

        $classes = 'elementskey-hf-header';

        if ( $sticky ) {
            $classes .= ' elementskey-hf-sticky';
        }

        if ( $transparent ) {
            $classes .= ' elementskey-hf-transparent';
        }

        if ( $shrink ) {
            $classes .= ' elementskey-hf-shrink';
        }

        if ( $hide_scroll ) {
            $classes .= ' elementskey-hf-hide-scroll';
        }

        if ( $logo_switch ) {
            $classes .= ' elementskey-hf-logo-switch';
        }

        if ( $scroll_anim ) {
            $classes .= ' elementskey-hf-scroll-anim';
        }

        $attrs = ' data-id="' . esc_attr( $post_id ) . '"';

        if ( $offset > 0 ) {
            $attrs .= ' data-offset="' . esc_attr( $offset ) . '"';
        }

        echo '<header class="' . esc_attr( $classes ) . '"' . $attrs . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Attributes built with esc_attr() above.
        $this->renderer->render( $post_id );
        echo '</header>';
    }

    public function render_footer() {
        if ( ! $this->can_render() ) {
            return;
        }

        $post_id = $this->renderer->get_matching_template_id( 'footer' );

        if ( ! $post_id || ! $this->check_schedule( $post_id ) || ! $this->check_device_visibility( $post_id ) ) {
            return;
        }

        echo '<footer class="elementskey-hf-footer">';
        $this->renderer->render( $post_id );
        echo '</footer>';
    }

    public function render_bottom_bar() {
        if ( ! $this->can_render() ) {
            return;
        }

        $post_id = $this->renderer->get_matching_template_id( 'bottom_bar' );

        if ( ! $post_id || ! $this->check_schedule( $post_id ) || ! $this->check_device_visibility( $post_id ) ) {
            return;
        }

        echo '<div class="elementskey-hf-bottom-bar">';
        $this->renderer->render( $post_id );
        echo '</div>';
    }

    public function filter_single_content( $content ) {
        if ( ! $this->can_render() || ! is_singular() || ! in_the_loop() || ! is_main_query() ) {
            return $content;
        }

        $post_id = $this->renderer->get_matching_template_id( 'single' );

        if ( ! $post_id || ! $this->check_schedule( $post_id ) ) {
            return $content;
        }

        if ( $this->renderer->has_rendered( $post_id ) ) {
            return $content;
        }

        ob_start();
        $this->renderer->render( $post_id );
        $template = ob_get_clean();

        if ( $template ) {
            return '<div class="elementskey-hf-single">' . $template . '</div>';
        }

        return $content;
    }

    public function override_archive_template( $template ) {
        if ( ! is_archive() || ! $this->can_render() ) {
            return $template;
        }

        $post_id = $this->renderer->get_matching_template_id( 'archive' );

        if ( ! $post_id || ! $this->check_schedule( $post_id ) ) {
            return $template;
        }

        $this->current_archive_id = $post_id;

        $file = __DIR__ . '/templates/archive-template.php';

        return file_exists( $file ) ? $file : $template;
    }

    public function render_archive_content() {
        if ( empty( $this->current_archive_id ) ) {
            return;
        }

        $this->renderer->render( $this->current_archive_id );
    }

    public function override_404_template( $template ) {
        if ( ! is_404() || ! $this->can_render() ) {
            return $template;
        }

        $post_id = $this->renderer->get_matching_template_id( '404' );

        if ( ! $post_id || ! $this->check_schedule( $post_id ) ) {
            return $template;
        }

        $this->current_404_id = $post_id;

        $file = __DIR__ . '/templates/404-template.php';

        return file_exists( $file ) ? $file : $template;
    }

    public function render_404_content() {
        if ( empty( $this->current_404_id ) ) {
            return;
        }

        $this->renderer->render( $this->current_404_id );
    }

    private function can_render() {
        if ( ! class_exists( '\Elementor\Plugin' ) ) {
            return false;
        }

        $plugin = \Elementor\Plugin::$instance;

        if ( $plugin->preview->is_preview_mode() || $plugin->editor->is_edit_mode() ) {
            return false;
        }

        return true;
    }

    private function check_schedule( $post_id ) {
        if ( 'yes' !== $this->get_setting( $post_id, 'schedule_enabled' ) ) {
            return true;
        }

        $now   = current_time( 'timestamp' );
        $start = $this->schedule_timestamp( $this->get_setting( $post_id, 'schedule_start' ) );
        $end   = $this->schedule_timestamp( $this->get_setting( $post_id, 'schedule_end' ) );

        if ( $start && $now < $start ) {
            return false;
        }

        if ( $end && $now > $end ) {
            return false;
        }

        return true;
    }

    private function schedule_timestamp( $value ) {
        if ( empty( $value ) ) {
            return 0;
        }

        if ( is_numeric( $value ) ) {
            return (int) $value;
        }

        $timestamp = strtotime( $value );

        return false === $timestamp ? 0 : $timestamp;
    }

    private function is_announcement_dismissed( $post_id ) {
        $cookie_name = 'elementskey_hf_dismiss_' . (int) $post_id;

        return isset( $_COOKIE[ $cookie_name ] );
    }

    public function enqueue_scripts() {
        if ( ! class_exists( '\Elementor\Plugin' ) ) {
            return;
        }

        $header_id   = $this->renderer->get_matching_template_id( 'header' );
        $has_sticky  = false;
        $needs_js    = false;

        if ( $header_id ) {
            $has_sticky  = 'yes' === $this->get_setting( $header_id, 'sticky' );
            $needs_js    = $has_sticky
                || 'yes' === $this->get_setting( $header_id, 'transparent' )
                || 'yes' === $this->get_setting( $header_id, 'sticky_shrink' )
                || 'yes' === $this->get_setting( $header_id, 'sticky_hide_scroll' )
                || 'yes' === $this->get_setting( $header_id, 'logo_switcher' );
        }

        $announcement_id = $this->renderer->get_matching_template_id( 'announcement' );

        if ( $announcement_id && 'yes' === $this->get_setting( $announcement_id, 'dismissible' ) ) {
            $needs_js = true;
        }

        $css_file = __DIR__ . '/assets/css/frontend.css';
        if ( file_exists( $css_file ) ) {
            wp_enqueue_style(
                'elementskey-hf-frontend',
                plugin_dir_url( __FILE__ ) . 'assets/css/frontend.css',
                [],
                ELEMENTSKEY_VERSION
            );
        }

        $js_file = __DIR__ . '/assets/js/frontend.js';
        if ( $needs_js && file_exists( $js_file ) ) {
            wp_enqueue_script(
                'elementskey-hf-frontend-js',
                plugin_dir_url( __FILE__ ) . 'assets/js/frontend.js',
                [],
                ELEMENTSKEY_VERSION,
                true
            );
        }

        wp_add_inline_style( 'elementskey-hf-frontend', '
            .elementskey-hf-sticky {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                z-index: 9999;
            }
            .admin-bar .elementskey-hf-sticky {
                top: 32px;
            }
            @media screen and (max-width: 782px) {
                .admin-bar .elementskey-hf-sticky {
                    top: 46px;
                }
            }
            .elementskey-hf-scroll-anim {
                transition: transform 0.3s ease;
            }
            .elementskey-hf-scroll-anim.elementskey-hf-hidden {
                transform: translateY(-100%);
            }
            .elementskey-hf-hide-scroll {
                transition: transform 0.3s ease;
            }
            .elementskey-hf-hide-scroll.elementskey-hf-scroll-down {
                transform: translateY(-100%);
            }
            .elementskey-hf-shrink .elementor-section,
            .elementskey-hf-shrink .elementor-container {
                transition: min-height 0.3s ease, padding 0.3s ease;
            }
            .elementskey-hf-shrink.elementskey-hf-scrolled .elementor-section,
            .elementskey-hf-shrink.elementskey-hf-scrolled .elementor-container {
                min-height: 60px !important;
                padding-top: 0 !important;
                padding-bottom: 0 !important;
            }
            .elementskey-hf-transparent {
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                z-index: 9998;
                background: transparent;
            }
            .admin-bar .elementskey-hf-transparent {
                top: 32px;
            }
            @media screen and (max-width: 782px) {
                .admin-bar .elementskey-hf-transparent {
                    top: 46px;
                }
            }
            .elementskey-hf-transparent.elementskey-hf-scrolled {
                position: fixed;
                background: #ffffff;
                box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            }
            .elementskey-hf-logo-switch .elementskey-hf-logo-sticky {
                display: none !important;
            }
            .elementskey-hf-logo-switch.elementskey-hf-scrolled .elementskey-hf-logo-default {
                display: none !important;
            }
            .elementskey-hf-logo-switch.elementskey-hf-scrolled .elementskey-hf-logo-sticky {
                display: block !important;
            }
        ' );
    }
}
