<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Custom_Progress_Bar_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_progress_bar';
    }

    public function get_title() {
        return 'Progress Bar';
    }

    public function get_icon() {
        return 'eicon-skill-bar';
    }

    public function get_categories() {
        return [ 'elementstack-elements' ];
    }

    public function get_style_depends() {
        return [ 'bdea-style' ];
    }

    public function get_script_depends() {
        return [ 'bdea-script' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => 'Progress Bars',
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'title',
            [
                'label' => 'Title',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Skill Name',
            ]
        );

        $repeater->add_control(
            'percentage',
            [
                'label' => 'Percentage',
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 80,
                'min' => 0,
                'max' => 100,
            ]
        );

        $this->add_control(
            'bars',
            [
                'label' => 'Items',
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [ 'title' => 'Design', 'percentage' => 90 ],
                    [ 'title' => 'Development', 'percentage' => 80 ],
                ],
                'title_field' => '{{{ title }}}',
            ]
        );

        $this->add_control(
            'show_percentage',
            [
                'label' => 'Show Percentage',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'animation_speed',
            [
                'label' => 'Animation Speed (ms)',
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 1200,
            ]
        );

        $this->end_controls_section();

        // General Style
        $this->start_controls_section(
            'style_general_section',
            [
                'label' => 'General',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'height',
            [
                'label' => 'Height',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [ 'min' => 4, 'max' => 50 ],
                ],
                'default' => [
                    'size' => 8,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-progress-bar' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_spacing',
            [
                'label' => 'Item Spacing',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 60 ],
                ],
                'default' => [
                    'size' => 20,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-progress-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'bar_radius',
            [
                'label' => 'Border Radius',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 50,
                    'right' => 50,
                    'bottom' => 50,
                    'left' => 50,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-progress-bar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .bdea-progress-fill' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Background Style
        $this->start_controls_section(
            'style_background_section',
            [
                'label' => 'Background',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'bg_color',
            [
                'label' => 'Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#eeeeee',
                'selectors' => [
                    '{{WRAPPER}} .bdea-progress-bar' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'bar_shadow',
                'selector' => '{{WRAPPER}} .bdea-progress-bar',
            ]
        );

        $this->end_controls_section();

        // Fill Style
        $this->start_controls_section(
            'style_fill_section',
            [
                'label' => 'Fill',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'bar_color',
            [
                'label' => 'Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4CAF50',
                'selectors' => [
                    '{{WRAPPER}} .bdea-progress-fill' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'bar_gradient',
            [
                'label' => 'Gradient',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-progress-fill' => 'background: linear-gradient(90deg, {{VALUE}}, transparent);',
                ],
            ]
        );

        $this->end_controls_section();

        // Typography
        $this->start_controls_section(
            'style_typography_section',
            [
                'label' => 'Typography',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .bdea-progress-title span',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => 'Title Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-progress-title span:first-child' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'percentage_color',
            [
                'label' => 'Percentage Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-progress-title span:last-child' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }


    protected function render() {

        $settings = $this->get_settings_for_display();

        if ( empty( $settings['bars'] ) ) return;

        ?>

        <div class="bdea-progress-wrapper"
             data-speed="<?php echo esc_attr( $settings['animation_speed'] ); ?>">

            <?php foreach ( $settings['bars'] as $item ) : ?>

                <div class="bdea-progress-item">

                    <div class="bdea-progress-title">
                        <span><?php echo esc_html( $item['title'] ); ?></span>

                        <?php if ( $settings['show_percentage'] === 'yes' ) : ?>
                            <span><?php echo esc_html( $item['percentage'] ); ?>%</span>
                        <?php endif; ?>
                    </div>

                    <div class="bdea-progress-bar">

                        <div class="bdea-progress-fill"
                            data-width="<?php echo esc_attr( $item['percentage'] ); ?>">
                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

        <?php
    }
}