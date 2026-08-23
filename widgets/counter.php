<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class ELEMENTSKEY_Counter_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'elementskey_counter';
    }

    public function get_title() {
        return 'Counter';
    }

    public function get_icon() {
        return 'eicon-counter';
    }

    public function get_categories() {
        return [ 'elementskey-elements' ];
    }

    public function get_style_depends() {
        return [ 'elementskey-content-style' ];
    }

    public function get_script_depends() {
        return [ 'elementskey-content-script' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'elementskey_counter_section',
            [
                'label' => __( 'Counter', 'elementskey' ),
            ]
        );

        $this->add_control(
            'counter_number',
            [
                'label' => __( 'Number', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 250,
                'min' => 0,
                'step' => 1,
            ]
        );

        $this->add_control(
            'counter_prefix',
            [
                'label' => __( 'Prefix', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'Enter prefix ($, €, etc.)',
            ]
        );

        $this->add_control(
            'counter_suffix',
            [
                'label' => __( 'Suffix', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'Enter suffix (+, %, etc.)',
            ]
        );

        $this->add_control(
            'counter_duration',
            [
                'label' => __( 'Animation Duration (ms)', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 2000,
                'min' => 300,
                'max' => 10000,
                'step' => 100,
            ]
        );

        $this->add_control(
            'counter_title',
            [
                'label' => __( 'Title', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Happy Customers', 'elementskey' ),
                'placeholder' => __( 'Enter a title', 'elementskey' ),
            ]
        );

        $this->end_controls_section();

        // General Style
        $this->start_controls_section(
            'elementskey_counter_general_style',
            [
                'label' => __( 'General', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'counter_align',
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
                    '{{WRAPPER}} .elementskey-counter-widget' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'counter_bg',
            [
                'label' => __( 'Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-counter-widget' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'counter_border',
                'selector' => '{{WRAPPER}} .elementskey-counter-widget',
            ]
        );

        $this->add_responsive_control(
            'counter_border_radius',
            [
                'label' => __( 'Border Radius', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-counter-widget' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'counter_shadow',
                'selector' => '{{WRAPPER}} .elementskey-counter-widget',
            ]
        );

        $this->add_responsive_control(
            'counter_padding',
            [
                'label' => __( 'Padding', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-counter-widget' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_counter_number_style',
            [
                'label' => __( 'Number', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'number_typography',
                'selector' => '{{WRAPPER}} .elementskey-counter-number',
            ]
        );

        $this->add_control(
            'number_color',
            [
                'label' => __( 'Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-counter-number' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'prefix_suffix_color',
            [
                'label' => __( 'Prefix/Suffix Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-counter-prefix, {{WRAPPER}} .elementskey-counter-suffix' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'number_shadow',
                'selector' => '{{WRAPPER}} .elementskey-counter-number',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_counter_title_style',
            [
                'label' => __( 'Title', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .elementskey-counter-title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-counter-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_spacing',
            [
                'label' => __( 'Spacing', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-counter-title' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $number   = ! empty( $settings['counter_number'] ) ? (float) $settings['counter_number'] : 0;
        $prefix   = ! empty( $settings['counter_prefix'] ) ? $settings['counter_prefix'] : '';
        $suffix   = ! empty( $settings['counter_suffix'] ) ? $settings['counter_suffix'] : '';
        $duration = ! empty( $settings['counter_duration'] ) ? (int) $settings['counter_duration'] : 2000;
        $title    = ! empty( $settings['counter_title'] ) ? $settings['counter_title'] : '';

        $settings_data = [
            'target' => $number,
            'duration' => $duration,
        ];
        ?>
        <div class="elementskey-counter-widget" data-settings='<?php echo esc_attr( wp_json_encode( $settings_data ) ); ?>'>
            <div class="elementskey-counter-number">
                <?php if ( $prefix ) : ?>
                    <span class="elementskey-counter-prefix"><?php echo esc_html( $prefix ); ?></span>
                <?php endif; ?>
                <span class="elementskey-counter-value" data-target="<?php echo esc_attr( $number ); ?>">0</span>
                <?php if ( $suffix ) : ?>
                    <span class="elementskey-counter-suffix"><?php echo esc_html( $suffix ); ?></span>
                <?php endif; ?>
            </div>
            <?php if ( $title ) : ?>
                <div class="elementskey-counter-title"><?php echo esc_html( $title ); ?></div>
            <?php endif; ?>
        </div>
        <?php
    }
}