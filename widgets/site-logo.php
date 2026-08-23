<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class ELEMENTSKEY_Site_Logo_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'elementskey_site_logo';
    }

    public function get_title() {
        return 'Site Logo';
    }

    public function get_icon() {
        return 'eicon-site-logo';
    }

    public function get_categories() {
        return [ 'elementskey-elements' ];
    }

    public function get_style_depends() {
        return [ 'elementskey-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'elementskey_site_logo_section',
            [
                'label' => __( 'Site Logo', 'elementskey' ),
            ]
        );

        $this->add_control(
            'logo_width',
            [
                'label' => __( 'Width', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 20, 'max' => 400 ] ],
                'default' => [ 'size' => 150, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-site-logo img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                ],
            ]
        );

        $this->add_control(
            'logo_fallback',
            [
                'label' => __( 'Show Site Name if No Logo', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'logo_link',
            [
                'label' => __( 'Link to Homepage', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_responsive_control(
            'logo_align',
            [
                'label' => __( 'Alignment', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [ 'title' => __( 'Left', 'elementskey' ), 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => __( 'Center', 'elementskey' ), 'icon' => 'eicon-text-align-center' ],
                    'right' => [ 'title' => __( 'Right', 'elementskey' ), 'icon' => 'eicon-text-align-right' ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-site-logo' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_site_logo_style',
            [
                'label' => __( 'Logo', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'logo_fallback_typography',
                'selector' => '{{WRAPPER}} .elementskey-logo-fallback',
            ]
        );

        $this->add_control(
            'logo_fallback_color',
            [
                'label' => __( 'Fallback Text Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-logo-fallback' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'logo_border_radius',
            [
                'label' => __( 'Image Border Radius', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-site-logo img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'logo_opacity',
            [
                'label' => __( 'Image Opacity', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 1, 'step' => 0.05 ],
                ],
                'default' => [ 'size' => 1 ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-site-logo img' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'logo_padding',
            [
                'label' => __( 'Padding', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-site-logo' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $custom_logo_id = get_theme_mod( 'custom_logo' );
        $logo = $custom_logo_id ? wp_get_attachment_image( $custom_logo_id, 'full', false, [ 'class' => 'elementskey-logo-img' ] ) : '';

        if ( empty( $logo ) ) {
            if ( 'yes' !== $settings['logo_fallback'] ) {
                return;
            }
            $logo = '<span class="elementskey-logo-fallback">' . esc_html( get_bloginfo( 'name' ) ) . '</span>';
        }

        if ( 'yes' === $settings['logo_link'] ) {
            $logo = '<a href="' . esc_url( home_url( '/' ) ) . '">' . $logo . '</a>';
        }
        ?>
        <div class="elementskey-site-logo">
            <?php echo $logo; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Logo markup built with esc_url()/esc_html()/get_custom_logo(). ?>
        </div>
        <?php
    }
}
