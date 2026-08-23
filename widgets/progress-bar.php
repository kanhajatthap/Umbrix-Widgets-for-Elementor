<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class ELEMENTSKEY_Progress_Bar_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'elementskey_progress_bar';
    }

    public function get_title() {
        return 'Progress Bar';
    }

    public function get_icon() {
        return 'eicon-skill-bar';
    }

    public function get_categories() {
        return [ 'elementskey-elements' ];
    }

    public function get_style_depends() {
        return [ 'elementskey-style' ];
    }

    public function get_script_depends() {
        return [ 'elementskey-script' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => __( 'Progress Bars', 'elementskey' ),
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'title',
            [
                'label' => __( 'Title', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Skill Name', 'elementskey' ),
            ]
        );

        $repeater->add_control(
            'percentage',
            [
                'label' => __( 'Percentage', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 80,
                'min' => 0,
                'max' => 100,
            ]
        );

        $this->add_control(
            'bars',
            [
                'label' => __( 'Items', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [ 'title' => __( 'Design', 'elementskey' ), 'percentage' => 90 ],
                    [ 'title' => __( 'Development', 'elementskey' ), 'percentage' => 80 ],
                ],
                'title_field' => '{{{ title }}}',
            ]
        );

        $this->add_control(
            'show_percentage',
            [
                'label' => __( 'Show Percentage', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'animation_speed',
            [
                'label' => __( 'Animation Speed (ms)', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 1200,
            ]
        );

        $this->end_controls_section();

        // General Style
        $this->start_controls_section(
            'style_general_section',
            [
                'label' => __( 'General', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'height',
            [
                'label' => __( 'Height', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [ 'min' => 4, 'max' => 50 ],
                ],
                'default' => [
                    'size' => 8,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-progress-bar' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_spacing',
            [
                'label' => __( 'Item Spacing', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 60 ],
                ],
                'default' => [
                    'size' => 20,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-progress-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'bar_radius',
            [
                'label' => __( 'Border Radius', 'elementskey' ),
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
                    '{{WRAPPER}} .elementskey-progress-bar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .elementskey-progress-fill' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Background Style
        $this->start_controls_section(
            'style_background_section',
            [
                'label' => __( 'Background', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'bg_color',
            [
                'label' => __( 'Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#eeeeee',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-progress-bar' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'bar_shadow',
                'selector' => '{{WRAPPER}} .elementskey-progress-bar',
            ]
        );

        $this->end_controls_section();

        // Fill Style
        $this->start_controls_section(
            'style_fill_section',
            [
                'label' => __( 'Fill', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'bar_color',
            [
                'label' => __( 'Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4CAF50',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-progress-fill' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'bar_gradient',
            [
                'label' => __( 'Gradient', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-progress-fill' => 'background: linear-gradient(90deg, {{VALUE}}, transparent);',
                ],
            ]
        );

        $this->end_controls_section();

        // Typography
        $this->start_controls_section(
            'style_typography_section',
            [
                'label' => __( 'Typography', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .elementskey-progress-title span',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Title Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-progress-title span:first-child' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'percentage_color',
            [
                'label' => __( 'Percentage Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-progress-title span:last-child' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }


    protected function render() {

        $settings = $this->get_settings_for_display();

        if ( empty( $settings['bars'] ) ) return;

        ?>

        <div class="elementskey-progress-wrapper"
             data-speed="<?php echo esc_attr( $settings['animation_speed'] ); ?>">

            <?php foreach ( $settings['bars'] as $item ) : ?>

                <div class="elementskey-progress-item">

                    <div class="elementskey-progress-title">
                        <span><?php echo esc_html( $item['title'] ); ?></span>

                        <?php if ( $settings['show_percentage'] === 'yes' ) : ?>
                            <span><?php echo esc_html( $item['percentage'] ); ?>%</span>
                        <?php endif; ?>
                    </div>

                    <div class="elementskey-progress-bar">

                        <div class="elementskey-progress-fill"
                            data-width="<?php echo esc_attr( $item['percentage'] ); ?>">
                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

        <?php
    }
}