<?php
/**
 * ElementStack Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Site_Logo_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_site_logo';
    }

    public function get_title() {
        return 'Site Logo';
    }

    public function get_icon() {
        return 'eicon-site-logo';
    }

    public function get_categories() {
        return [ 'elementstack-elements' ];
    }

    public function get_style_depends() {
        return [ 'bdea-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'bdea_site_logo_section',
            [
                'label' => __( 'Site Logo', 'elementstack-elementor-addons' ),
            ]
        );

        $this->add_control(
            'logo_width',
            [
                'label' => __( 'Width', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 20, 'max' => 400 ] ],
                'default' => [ 'size' => 150, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-site-logo img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                ],
            ]
        );

        $this->add_control(
            'logo_fallback',
            [
                'label' => __( 'Show Site Name if No Logo', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'logo_link',
            [
                'label' => __( 'Link to Homepage', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_responsive_control(
            'logo_align',
            [
                'label' => __( 'Alignment', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [ 'title' => __( 'Left', 'elementstack-elementor-addons' ), 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => __( 'Center', 'elementstack-elementor-addons' ), 'icon' => 'eicon-text-align-center' ],
                    'right' => [ 'title' => __( 'Right', 'elementstack-elementor-addons' ), 'icon' => 'eicon-text-align-right' ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .bdea-site-logo' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_site_logo_style',
            [
                'label' => __( 'Logo', 'elementstack-elementor-addons' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'logo_fallback_typography',
                'selector' => '{{WRAPPER}} .bdea-logo-fallback',
            ]
        );

        $this->add_control(
            'logo_fallback_color',
            [
                'label' => __( 'Fallback Text Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-logo-fallback' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'logo_border_radius',
            [
                'label' => __( 'Image Border Radius', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-site-logo img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'logo_opacity',
            [
                'label' => __( 'Image Opacity', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 1, 'step' => 0.05 ],
                ],
                'default' => [ 'size' => 1 ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-site-logo img' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'logo_padding',
            [
                'label' => __( 'Padding', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-site-logo' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $custom_logo_id = get_theme_mod( 'custom_logo' );
        $logo = $custom_logo_id ? wp_get_attachment_image( $custom_logo_id, 'full', false, [ 'class' => 'bdea-logo-img' ] ) : '';

        if ( empty( $logo ) ) {
            if ( 'yes' !== $settings['logo_fallback'] ) {
                return;
            }
            $logo = '<span class="bdea-logo-fallback">' . esc_html( get_bloginfo( 'name' ) ) . '</span>';
        }

        if ( 'yes' === $settings['logo_link'] ) {
            $logo = '<a href="' . esc_url( home_url( '/' ) ) . '">' . $logo . '</a>';
        }
        ?>
        <div class="bdea-site-logo">
            <?php echo $logo; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Logo markup built with esc_url()/esc_html()/get_custom_logo(). ?>
        </div>
        <?php
    }
}
