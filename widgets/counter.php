<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Counter_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_counter';
    }

    public function get_title() {
        return 'Counter';
    }

    public function get_icon() {
        return 'eicon-counter';
    }

    public function get_categories() {
        return [ 'elementstack-elements' ];
    }

    public function get_style_depends() {
        return [ 'bdea-content-style' ];
    }

    public function get_script_depends() {
        return [ 'bdea-content-script' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'bdea_counter_section',
            [
                'label' => 'Counter',
            ]
        );

        $this->add_control(
            'counter_number',
            [
                'label' => 'Number',
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 250,
                'min' => 0,
                'step' => 1,
            ]
        );

        $this->add_control(
            'counter_prefix',
            [
                'label' => 'Prefix',
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'Enter prefix ($, €, etc.)',
            ]
        );

        $this->add_control(
            'counter_suffix',
            [
                'label' => 'Suffix',
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'Enter suffix (+, %, etc.)',
            ]
        );

        $this->add_control(
            'counter_duration',
            [
                'label' => 'Animation Duration (ms)',
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
                'label' => 'Title',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Happy Customers',
                'placeholder' => 'Enter a title',
            ]
        );

        $this->end_controls_section();

        // General Style
        $this->start_controls_section(
            'bdea_counter_general_style',
            [
                'label' => 'General',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'counter_align',
            [
                'label' => 'Alignment',
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [ 'title' => 'Left', 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => 'Center', 'icon' => 'eicon-text-align-center' ],
                    'right' => [ 'title' => 'Right', 'icon' => 'eicon-text-align-right' ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .bdea-counter-widget' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'counter_bg',
            [
                'label' => 'Background',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-counter-widget' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'counter_border',
                'selector' => '{{WRAPPER}} .bdea-counter-widget',
            ]
        );

        $this->add_responsive_control(
            'counter_border_radius',
            [
                'label' => 'Border Radius',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-counter-widget' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'counter_shadow',
                'selector' => '{{WRAPPER}} .bdea-counter-widget',
            ]
        );

        $this->add_responsive_control(
            'counter_padding',
            [
                'label' => 'Padding',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-counter-widget' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_counter_number_style',
            [
                'label' => 'Number',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'number_typography',
                'selector' => '{{WRAPPER}} .bdea-counter-number',
            ]
        );

        $this->add_control(
            'number_color',
            [
                'label' => 'Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .bdea-counter-number' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'prefix_suffix_color',
            [
                'label' => 'Prefix/Suffix Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-counter-prefix, {{WRAPPER}} .bdea-counter-suffix' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'number_shadow',
                'selector' => '{{WRAPPER}} .bdea-counter-number',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_counter_title_style',
            [
                'label' => 'Title',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .bdea-counter-title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => 'Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-counter-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_spacing',
            [
                'label' => 'Spacing',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-counter-title' => 'margin-top: {{SIZE}}{{UNIT}};',
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
        <div class="bdea-counter-widget" data-settings='<?php echo esc_attr( wp_json_encode( $settings_data ) ); ?>'>
            <div class="bdea-counter-number">
                <?php if ( $prefix ) : ?>
                    <span class="bdea-counter-prefix"><?php echo esc_html( $prefix ); ?></span>
                <?php endif; ?>
                <span class="bdea-counter-value" data-target="<?php echo esc_attr( $number ); ?>">0</span>
                <?php if ( $suffix ) : ?>
                    <span class="bdea-counter-suffix"><?php echo esc_html( $suffix ); ?></span>
                <?php endif; ?>
            </div>
            <?php if ( $title ) : ?>
                <div class="bdea-counter-title"><?php echo esc_html( $title ); ?></div>
            <?php endif; ?>
        </div>
        <?php
    }
}