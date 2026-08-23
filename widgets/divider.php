<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class ELEMENTSKEY_Divider_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'elementskey_divider';
    }

    public function get_title() {
        return 'Divider';
    }

    public function get_icon() {
        return 'eicon-divider';
    }

    public function get_categories() {
        return [ 'elementskey-elements' ];
    }

    public function get_style_depends() {
        return [ 'elementskey-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'elementskey_divider_content_section',
            [
                'label' => __( 'Divider', 'elementskey' ),
            ]
        );

        $this->add_control(
            'divider_style',
            [
                'label' => __( 'Style', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'solid',
                'options' => [
                    'solid' => __( 'Solid', 'elementskey' ),
                    'dashed' => __( 'Dashed', 'elementskey' ),
                    'dotted' => __( 'Dotted', 'elementskey' ),
                    'double' => __( 'Double', 'elementskey' ),
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_divider_style_section',
            [
                'label' => __( 'Style', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'divider_width',
            [
                'label' => __( 'Width', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [ 'px' => [ 'min' => 1, 'max' => 1200 ], '%' => [ 'min' => 1, 'max' => 100 ] ],
                'default' => [ 'size' => 100, 'unit' => '%' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-divider' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'divider_weight',
            [
                'label' => __( 'Weight (Height)', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 1, 'max' => 20 ] ],
                'default' => [ 'size' => 2, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-divider' => 'border-top-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'divider_align',
            [
                'label' => __( 'Alignment', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [ 'title' => __( 'Left', 'elementskey' ), 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => __( 'Center', 'elementskey' ), 'icon' => 'eicon-text-align-center' ],
                    'right' => [ 'title' => __( 'Right', 'elementskey' ), 'icon' => 'eicon-text-align-right' ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-divider-wrap' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'divider_color',
            [
                'label' => __( 'Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#d9d9d9',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-divider' => 'border-top-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'divider_gap',
            [
                'label' => __( 'Gap Around', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
                'default' => [ 'size' => 20, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-divider-wrap' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'divider_border_radius',
            [
                'label' => __( 'Border Radius', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-divider' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $style = ! empty( $settings['divider_style'] ) ? $settings['divider_style'] : 'solid';
        ?>
        <div class="elementskey-divider-wrap">
            <div class="elementskey-divider elementskey-divider-style-<?php echo esc_attr( $style ); ?>"></div>
        </div>
        <?php
    }
}