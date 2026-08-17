<?php
/**
 * ElementStack Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Countdown_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_countdown';
    }

    public function get_title() {
        return 'Countdown';
    }

    public function get_icon() {
        return 'eicon-countdown';
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
            'bdea_countdown_section',
            [
                'label' => __( 'Countdown', 'elementstack-elementor-addons' ),
            ]
        );

        $this->add_control(
            'countdown_date',
            [
                'label' => __( 'Target Date', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::DATE_TIME,
                'default' => '2026-12-31 23:59',
            ]
        );

        $this->add_control(
            'countdown_show_days',
            [
                'label' => __( 'Show Days', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'countdown_show_hours',
            [
                'label' => __( 'Show Hours', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'countdown_show_minutes',
            [
                'label' => __( 'Show Minutes', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'countdown_show_seconds',
            [
                'label' => __( 'Show Seconds', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'countdown_days_label',
            [
                'label' => __( 'Days Label', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Days', 'elementstack-elementor-addons' ),
            ]
        );

        $this->add_control(
            'countdown_hours_label',
            [
                'label' => __( 'Hours Label', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Hours', 'elementstack-elementor-addons' ),
            ]
        );

        $this->add_control(
            'countdown_minutes_label',
            [
                'label' => __( 'Minutes Label', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Minutes', 'elementstack-elementor-addons' ),
            ]
        );

        $this->add_control(
            'countdown_seconds_label',
            [
                'label' => __( 'Seconds Label', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Seconds', 'elementstack-elementor-addons' ),
            ]
        );

        $this->end_controls_section();

        // Box Style
        $this->start_controls_section(
            'bdea_countdown_box_style',
            [
                'label' => __( 'Box', 'elementstack-elementor-addons' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'box_align',
            [
                'label' => __( 'Alignment', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [ 'title' => __( 'Left', 'elementstack-elementor-addons' ), 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => __( 'Center', 'elementstack-elementor-addons' ), 'icon' => 'eicon-text-align-center' ],
                    'right' => [ 'title' => __( 'Right', 'elementstack-elementor-addons' ), 'icon' => 'eicon-text-align-right' ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .bdea-countdown' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'box_bg',
            [
                'label' => __( 'Background', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-countdown-box' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'box_border',
                'selector' => '{{WRAPPER}} .bdea-countdown-box',
            ]
        );

        $this->add_responsive_control(
            'box_border_radius',
            [
                'label' => __( 'Border Radius', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-countdown-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow',
                'selector' => '{{WRAPPER}} .bdea-countdown-box',
            ]
        );

        $this->add_responsive_control(
            'box_padding',
            [
                'label' => __( 'Padding', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 20,
                    'right' => 20,
                    'bottom' => 20,
                    'left' => 20,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-countdown-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'box_gap',
            [
                'label' => __( 'Gap', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-countdown' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Number Style
        $this->start_controls_section(
            'bdea_countdown_number_style',
            [
                'label' => __( 'Numbers', 'elementstack-elementor-addons' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'countdown_number_typography',
                'selector' => '{{WRAPPER}} .bdea-countdown-number',
            ]
        );

        $this->add_control(
            'countdown_number_color',
            [
                'label' => __( 'Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .bdea-countdown-number' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Label Style
        $this->start_controls_section(
            'bdea_countdown_label_style',
            [
                'label' => __( 'Labels', 'elementstack-elementor-addons' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'countdown_label_typography',
                'selector' => '{{WRAPPER}} .bdea-countdown-label',
            ]
        );

        $this->add_control(
            'countdown_label_color',
            [
                'label' => __( 'Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4b5563',
                'selectors' => [
                    '{{WRAPPER}} .bdea-countdown-label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'label_spacing',
            [
                'label' => __( 'Spacing', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 20 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-countdown-label' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $date        = ! empty( $settings['countdown_date'] ) ? $settings['countdown_date'] : '';
        $show_days   = ( 'yes' === $settings['countdown_show_days'] );
        $show_hours  = ( 'yes' === $settings['countdown_show_hours'] );
        $show_min    = ( 'yes' === $settings['countdown_show_minutes'] );
        $show_sec    = ( 'yes' === $settings['countdown_show_seconds'] );
        $days_label  = ! empty( $settings['countdown_days_label'] ) ? $settings['countdown_days_label'] : 'Days';
        $hours_label = ! empty( $settings['countdown_hours_label'] ) ? $settings['countdown_hours_label'] : 'Hours';
        $min_label   = ! empty( $settings['countdown_minutes_label'] ) ? $settings['countdown_minutes_label'] : 'Minutes';
        $sec_label   = ! empty( $settings['countdown_seconds_label'] ) ? $settings['countdown_seconds_label'] : 'Seconds';
        ?>
        <div class="bdea-countdown" data-date="<?php echo esc_attr( $date ); ?>">
            <?php if ( $show_days ) : ?>
                <div class="bdea-countdown-box bdea-countdown-days" data-unit="days">
                    <span class="bdea-countdown-number">00</span>
                    <span class="bdea-countdown-label"><?php echo esc_html( $days_label ); ?></span>
                </div>
            <?php endif; ?>
            <?php if ( $show_hours ) : ?>
                <div class="bdea-countdown-box bdea-countdown-hours" data-unit="hours">
                    <span class="bdea-countdown-number">00</span>
                    <span class="bdea-countdown-label"><?php echo esc_html( $hours_label ); ?></span>
                </div>
            <?php endif; ?>
            <?php if ( $show_min ) : ?>
                <div class="bdea-countdown-box bdea-countdown-minutes" data-unit="minutes">
                    <span class="bdea-countdown-number">00</span>
                    <span class="bdea-countdown-label"><?php echo esc_html( $min_label ); ?></span>
                </div>
            <?php endif; ?>
            <?php if ( $show_sec ) : ?>
                <div class="bdea-countdown-box bdea-countdown-seconds" data-unit="seconds">
                    <span class="bdea-countdown-number">00</span>
                    <span class="bdea-countdown-label"><?php echo esc_html( $sec_label ); ?></span>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}