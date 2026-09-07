<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */
namespace ElementsKey\Modules\HeaderFooter;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class Document extends \ElementsKey\Framework\Elementor\ThemeBuilderDocument {

    public function get_name() {
        return 'elementskey-hf-document';
    }

    public static function get_title() {
        return __( 'Builder Template', 'elementskey' );
    }

    protected static function get_cpt() {
        return 'elementskey_header_footer';
    }

    protected function register_document_controls() {
        parent::register_document_controls();

        $post_id = $this->get_main_id();
        $type    = get_post_meta( $post_id, '_elementskey_hf_template_type', true );

        if ( 'header' === $type ) {
            $this->start_controls_section(
                'elementskey_header_settings',
                [
                    'label' => __( 'Header Settings', 'elementskey' ),
                    'tab'   => Controls_Manager::TAB_SETTINGS,
                ]
            );

            $this->add_control(
                'sticky',
                [
                    'label'        => __( 'Sticky Header', 'elementskey' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'default'      => $this->legacy( 'sticky' ),
                    'description'  => __( 'Header stays fixed at the top on scroll.', 'elementskey' ),
                ]
            );

            $this->add_control(
                'sticky_offset',
                [
                    'label'       => __( 'Sticky Offset (px)', 'elementskey' ),
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
                    'label'        => __( 'Transparent Header', 'elementskey' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'default'      => $this->legacy( 'transparent' ),
                    'description'  => __( 'Overlays the hero section, becomes solid on scroll.', 'elementskey' ),
                    'separator'    => 'before',
                ]
            );

            $this->add_control(
                'sticky_shrink',
                [
                    'label'        => __( 'Shrink on Scroll', 'elementskey' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'default'      => $this->legacy( 'sticky_shrink' ),
                    'description'  => __( 'Header becomes compact after scrolling.', 'elementskey' ),
                ]
            );

            $this->add_control(
                'sticky_hide_scroll',
                [
                    'label'        => __( 'Hide on Scroll Down', 'elementskey' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'default'      => $this->legacy( 'sticky_hide_scroll' ),
                    'description'  => __( 'Header hides while scrolling down and reappears on scroll up.', 'elementskey' ),
                ]
            );

            $this->add_control(
                'logo_switcher',
                [
                    'label'        => __( 'Logo Switcher', 'elementskey' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'default'      => $this->legacy( 'logo_switcher' ),
                    'description'  => __( 'Swap between normal and sticky logo. Add classes "elementskey-hf-logo-default" and "elementskey-hf-logo-sticky" to logo widgets.', 'elementskey' ),
                ]
            );

            $this->add_control(
                'scroll_animation',
                [
                    'label'        => __( 'Scroll Animation', 'elementskey' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'default'      => $this->legacy( 'scroll_animation' ),
                    'description'  => __( 'Legacy smooth hide/show animation on scroll.', 'elementskey' ),
                ]
            );

            $this->end_controls_section();
        }

        if ( 'announcement' === $type ) {
            $this->start_controls_section(
                'elementskey_announcement_settings',
                [
                    'label' => __( 'Announcement Settings', 'elementskey' ),
                    'tab'   => Controls_Manager::TAB_SETTINGS,
                ]
            );

            $this->add_control(
                'dismissible',
                [
                    'label'        => __( 'Dismissible', 'elementskey' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'default'      => $this->legacy( 'dismissible' ),
                    'description'  => __( 'Show a close button and remember the dismissal via cookie.', 'elementskey' ),
                ]
            );

            $this->add_control(
                'cookie_days',
                [
                    'label'       => __( 'Remember Dismissal (days)', 'elementskey' ),
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
                'elementskey_template_settings',
                [
                    'label' => __( 'Template Settings', 'elementskey' ),
                    'tab'   => Controls_Manager::TAB_SETTINGS,
                ]
            );

            $this->add_control(
                'disable_theme',
                [
                    'label'        => __( 'Disable Theme Header', 'elementskey' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'default'      => $this->legacy( 'disable_theme' ),
                    'description'  => __( 'Hide the theme header with CSS.', 'elementskey' ),
                ]
            );

            $this->end_controls_section();
        }

        $this->start_controls_section(
            'elementskey_schedule_settings',
            [
                'label' => __( 'Schedule Display', 'elementskey' ),
                'tab'   => Controls_Manager::TAB_SETTINGS,
            ]
        );

        $this->add_control(
            'schedule_enabled',
            [
                'label'        => __( 'Enable Schedule', 'elementskey' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => $this->legacy( 'schedule_enabled' ),
                'description'  => __( 'Show this template only between the start and end date.', 'elementskey' ),
            ]
        );

        $this->add_control(
            'schedule_start',
            [
                'label'     => __( 'Start Date', 'elementskey' ),
                'type'      => Controls_Manager::DATE_TIME,
                'default'   => $this->legacy_datetime( 'schedule_start' ),
                'condition' => [ 'schedule_enabled' => 'yes' ],
            ]
        );

        $this->add_control(
            'schedule_end',
            [
                'label'     => __( 'End Date', 'elementskey' ),
                'type'      => Controls_Manager::DATE_TIME,
                'default'   => $this->legacy_datetime( 'schedule_end' ),
                'condition' => [ 'schedule_enabled' => 'yes' ],
            ]
        );

        $this->end_controls_section();
    }

    private function legacy( $key, $default = '' ) {
        $value = get_post_meta( $this->get_main_id(), '_elementskey_hf_' . $key, true );

        return '' !== $value ? $value : $default;
    }

    private function legacy_datetime( $key ) {
        $value = get_post_meta( $this->get_main_id(), '_elementskey_hf_' . $key, true );

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
