<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Post_Navigation_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_post_navigation';
    }

    public function get_title() {
        return 'Post Navigation';
    }

    public function get_icon() {
        return 'eicon-post-navigation';
    }

    public function get_categories() {
        return [ 'elementstack-elements' ];
    }

    public function get_style_depends() {
        return [ 'bdea-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'bdea_post_navigation_content',
            [
                'label' => 'Content',
            ]
        );

        $this->add_control(
            'prev_label',
            [
                'label' => 'Previous Label',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Previous',
            ]
        );

        $this->add_control(
            'next_label',
            [
                'label' => 'Next Label',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Next',
            ]
        );

        $this->add_control(
            'show_arrows',
            [
                'label' => 'Show Arrows',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'layout',
            [
                'label' => 'Layout',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'horizontal',
                'options' => [
                    'horizontal' => 'Horizontal',
                    'vertical' => 'Vertical',
                ],
            ]
        );

        $this->end_controls_section();

        // Navigation Style
        $this->start_controls_section(
            'bdea_post_navigation_style',
            [
                'label' => 'Navigation',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'nav_typography',
                'selector' => '{{WRAPPER}} .bdea-post-navigation a, {{WRAPPER}} .bdea-post-navigation-label',
            ]
        );

        $this->add_control(
            'nav_color',
            [
                'label' => 'Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .bdea-post-navigation a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'nav_hover_color',
            [
                'label' => 'Hover Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-post-navigation a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'label_color',
            [
                'label' => 'Label Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-post-navigation-label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_color',
            [
                'label' => 'Arrow Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-post-navigation-arrow' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'nav_align',
            [
                'label' => 'Alignment',
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [ 'title' => 'Left', 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => 'Center', 'icon' => 'eicon-text-align-center' ],
                    'right' => [ 'title' => 'Right', 'icon' => 'eicon-text-align-right' ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .bdea-post-navigation' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Box Style
        $this->start_controls_section(
            'bdea_post_navigation_box_style',
            [
                'label' => 'Box',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'box_padding',
            [
                'label' => 'Padding',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 16,
                    'right' => 16,
                    'bottom' => 16,
                    'left' => 16,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-post-nav-prev, {{WRAPPER}} .bdea-post-nav-next' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'box_border',
                'selector' => '{{WRAPPER}} .bdea-post-navigation',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow',
                'selector' => '{{WRAPPER}} .bdea-post-navigation',
            ]
        );

        $this->add_responsive_control(
            'box_gap',
            [
                'label' => 'Gap',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-post-navigation-horizontal' => 'gap: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .bdea-post-navigation-vertical' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( ! is_singular() ) {
            echo '<div class="bdea-loop-grid-empty">Post navigation is only available on single posts.</div>';
            return;
        }

        $prev_label = ! empty( $settings['prev_label'] ) ? $settings['prev_label'] : 'Previous';
        $next_label = ! empty( $settings['next_label'] ) ? $settings['next_label'] : 'Next';
        $show_arrow = ( 'yes' === $settings['show_arrows'] );
        $layout     = ( 'vertical' === $settings['layout'] ) ? 'bdea-post-navigation-vertical' : 'bdea-post-navigation-horizontal';
        ?>
        <div class="bdea-post-navigation <?php echo esc_attr( $layout ); ?>">
            <div class="bdea-post-nav-prev">
                <?php previous_post_link( '<a href="%link" class="bdea-post-nav-link">' . ( $show_arrow ? '<span class="bdea-post-navigation-arrow">&larr;</span>' : '' ) . '<span class="bdea-post-navigation-label">%title</span></a>' ); ?>
            </div>
            <div class="bdea-post-nav-next">
                <?php next_post_link( '<a href="%link" class="bdea-post-nav-link">' . '<span class="bdea-post-navigation-label">%title</span>' . ( $show_arrow ? '<span class="bdea-post-navigation-arrow">&rarr;</span>' : '' ) . '</a>' ); ?>
            </div>
        </div>
        <?php
    }
}