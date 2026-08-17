<?php
/**
 * ElementStack Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */
namespace BDEA\Modules\HeaderFooter;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class Document extends \BDEA\Framework\Elementor\ThemeBuilderDocument {

    public function get_name() {
        return 'bdea-hf-document';
    }

    public static function get_title() {
        return __( 'Theme Builder Template', 'elementstack-elementor-addons' );
    }

    protected static function get_cpt() {
        return 'bdea_header_footer';
    }

    protected function register_document_controls() {
        parent::register_document_controls();

        $post_id = $this->get_main_id();
        $type    = get_post_meta( $post_id, '_bdea_hf_template_type', true );

        if ( 'header' === $type ) {
            $this->start_controls_section(
                'bdea_header_settings',
                [
                    'label' => __( 'Header Settings', 'elementstack-elementor-addons' ),
                    'tab'   => Controls_Manager::TAB_SETTINGS,
                ]
            );

            $this->add_control(
                'sticky',
                [
                    'label'        => __( 'Sticky Header', 'elementstack-elementor-addons' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'default'      => $this->legacy( 'sticky' ),
                    'description'  => __( 'Header stays fixed at the top on scroll.', 'elementstack-elementor-addons' ),
                ]
            );

            $this->add_control(
                'sticky_offset',
                [
                    'label'       => __( 'Sticky Offset (px)', 'elementstack-elementor-addons' ),
                    'type'        => Controls_Manager::NUMBER,
                    'min'         => 0,
                    'max'         => 999,
                    'step'        => 1,
                    'default'     => (int) $this->legacy( 'sticky_offset', 0 ),
                    'condition'   => [ 'sticky' => 'yes' ],
                ]
            );

            $this->add_control(
                'transparent',
                [
                    'label'        => __( 'Transparent Header', 'elementstack-elementor-addons' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'default'      => $this->legacy( 'transparent' ),
                    'description'  => __( 'Overlays the hero section, becomes solid on scroll.', 'elementstack-elementor-addons' ),
                    'separator'    => 'before',
                ]
            );

            $this->add_control(
                'sticky_shrink',
                [
                    'label'        => __( 'Shrink on Scroll', 'elementstack-elementor-addons' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'default'      => $this->legacy( 'sticky_shrink' ),
                    'description'  => __( 'Header becomes compact after scrolling.', 'elementstack-elementor-addons' ),
                ]
            );

            $this->add_control(
                'sticky_hide_scroll',
                [
                    'label'        => __( 'Hide on Scroll Down', 'elementstack-elementor-addons' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'default'      => $this->legacy( 'sticky_hide_scroll' ),
                    'description'  => __( 'Header hides while scrolling down and reappears on scroll up.', 'elementstack-elementor-addons' ),
                ]
            );

            $this->add_control(
                'logo_switcher',
                [
                    'label'        => __( 'Logo Switcher', 'elementstack-elementor-addons' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'default'      => $this->legacy( 'logo_switcher' ),
                    'description'  => __( 'Swap between normal and sticky logo. Add classes "bdea-hf-logo-default" and "bdea-hf-logo-sticky" to logo widgets.', 'elementstack-elementor-addons' ),
                ]
            );

            $this->add_control(
                'scroll_animation',
                [
                    'label'        => __( 'Scroll Animation', 'elementstack-elementor-addons' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'default'      => $this->legacy( 'scroll_animation' ),
                    'description'  => __( 'Legacy smooth hide/show animation on scroll.', 'elementstack-elementor-addons' ),
                ]
            );

            $this->end_controls_section();
        }

        if ( 'announcement' === $type ) {
            $this->start_controls_section(
                'bdea_announcement_settings',
                [
                    'label' => __( 'Announcement Settings', 'elementstack-elementor-addons' ),
                    'tab'   => Controls_Manager::TAB_SETTINGS,
                ]
            );

            $this->add_control(
                'dismissible',
                [
                    'label'        => __( 'Dismissible', 'elementstack-elementor-addons' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'default'      => $this->legacy( 'dismissible' ),
                    'description'  => __( 'Show a close button and remember the dismissal via cookie.', 'elementstack-elementor-addons' ),
                ]
            );

            $this->add_control(
                'cookie_days',
                [
                    'label'       => __( 'Remember Dismissal (days)', 'elementstack-elementor-addons' ),
                    'type'        => Controls_Manager::NUMBER,
                    'min'         => 1,
                    'max'         => 365,
                    'step'        => 1,
                    'default'     => (int) $this->legacy( 'cookie_days', 1 ),
                    'condition'   => [ 'dismissible' => 'yes' ],
                ]
            );

            $this->end_controls_section();
        }

        if ( in_array( $type, [ 'header', 'footer' ], true ) ) {
            $this->start_controls_section(
                'bdea_template_settings',
                [
                    'label' => __( 'Template Settings', 'elementstack-elementor-addons' ),
                    'tab'   => Controls_Manager::TAB_SETTINGS,
                ]
            );

            $this->add_control(
                'disable_theme',
                [
                    'label'        => __( 'Disable Theme Header', 'elementstack-elementor-addons' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'default'      => $this->legacy( 'disable_theme' ),
                    'description'  => __( 'Hide the theme header with CSS.', 'elementstack-elementor-addons' ),
                ]
            );

            $this->end_controls_section();
        }

        $this->start_controls_section(
            'bdea_schedule_settings',
            [
                'label' => __( 'Schedule Display', 'elementstack-elementor-addons' ),
                'tab'   => Controls_Manager::TAB_SETTINGS,
            ]
        );

        $this->add_control(
            'schedule_enabled',
            [
                'label'        => __( 'Enable Schedule', 'elementstack-elementor-addons' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => $this->legacy( 'schedule_enabled' ),
                'description'  => __( 'Show this template only between the start and end date.', 'elementstack-elementor-addons' ),
            ]
        );

        $this->add_control(
            'schedule_start',
            [
                'label'     => __( 'Start Date', 'elementstack-elementor-addons' ),
                'type'      => Controls_Manager::DATE_TIME,
                'default'   => $this->legacy_datetime( 'schedule_start' ),
                'condition' => [ 'schedule_enabled' => 'yes' ],
            ]
        );

        $this->add_control(
            'schedule_end',
            [
                'label'     => __( 'End Date', 'elementstack-elementor-addons' ),
                'type'      => Controls_Manager::DATE_TIME,
                'default'   => $this->legacy_datetime( 'schedule_end' ),
                'condition' => [ 'schedule_enabled' => 'yes' ],
            ]
        );

        $this->end_controls_section();
    }

    private function legacy( $key, $default = '' ) {
        $value = get_post_meta( $this->get_main_id(), '_bdea_hf_' . $key, true );

        return '' !== $value ? $value : $default;
    }

    private function legacy_datetime( $key ) {
        $value = get_post_meta( $this->get_main_id(), '_bdea_hf_' . $key, true );

        if ( empty( $value ) ) {
            return '';
        }

        $timestamp = is_numeric( $value ) ? (int) $value : strtotime( $value );

        if ( false === $timestamp ) {
            return '';
        }

        return gmdate( 'Y-m-d H:i', $timestamp );
    }
}
