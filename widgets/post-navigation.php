<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class ELEMENTSKEY_Post_Navigation_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'elementskey_post_navigation';
    }

    public function get_title() {
        return 'Post Navigation';
    }

    public function get_icon() {
        return 'eicon-post-navigation';
    }

    public function get_categories() {
        return [ 'elementskey-elements' ];
    }

    public function get_style_depends() {
        return [ 'elementskey-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'elementskey_post_navigation_content',
            [
                'label' => __( 'Content', 'elementskey' ),
            ]
        );

        $this->add_control(
            'prev_label',
            [
                'label' => __( 'Previous Label', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Previous', 'elementskey' ),
            ]
        );

        $this->add_control(
            'next_label',
            [
                'label' => __( 'Next Label', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Next', 'elementskey' ),
            ]
        );

        $this->add_control(
            'show_arrows',
            [
                'label' => __( 'Show Arrows', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'layout',
            [
                'label' => __( 'Layout', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'horizontal',
                'options' => [
                    'horizontal' => __( 'Horizontal', 'elementskey' ),
                    'vertical' => __( 'Vertical', 'elementskey' ),
                ],
            ]
        );

        $this->end_controls_section();

        // Navigation Style
        $this->start_controls_section(
            'elementskey_post_navigation_style',
            [
                'label' => __( 'Navigation', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'nav_typography',
                'selector' => '{{WRAPPER}} .elementskey-post-navigation a, {{WRAPPER}} .elementskey-post-navigation-label',
            ]
        );

        $this->add_control(
            'nav_color',
            [
                'label' => __( 'Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-post-navigation a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'nav_hover_color',
            [
                'label' => __( 'Hover Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-post-navigation a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'label_color',
            [
                'label' => __( 'Label Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-post-navigation-label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_color',
            [
                'label' => __( 'Arrow Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-post-navigation-arrow' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'nav_align',
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
                    '{{WRAPPER}} .elementskey-post-navigation' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Box Style
        $this->start_controls_section(
            'elementskey_post_navigation_box_style',
            [
                'label' => __( 'Box', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'box_padding',
            [
                'label' => __( 'Padding', 'elementskey' ),
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
                    '{{WRAPPER}} .elementskey-post-nav-prev, {{WRAPPER}} .elementskey-post-nav-next' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'box_border',
                'selector' => '{{WRAPPER}} .elementskey-post-navigation',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow',
                'selector' => '{{WRAPPER}} .elementskey-post-navigation',
            ]
        );

        $this->add_responsive_control(
            'box_gap',
            [
                'label' => __( 'Gap', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-post-navigation-horizontal' => 'gap: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementskey-post-navigation-vertical' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( ! is_singular() ) {
            echo '<div class="elementskey-loop-grid-empty">Post navigation is only available on single posts.</div>';
            return;
        }

        $prev_label = ! empty( $settings['prev_label'] ) ? $settings['prev_label'] : 'Previous';
        $next_label = ! empty( $settings['next_label'] ) ? $settings['next_label'] : 'Next';
        $show_arrow = ( 'yes' === $settings['show_arrows'] );
        $layout     = ( 'vertical' === $settings['layout'] ) ? 'elementskey-post-navigation-vertical' : 'elementskey-post-navigation-horizontal';
        ?>
        <div class="elementskey-post-navigation <?php echo esc_attr( $layout ); ?>">
            <div class="elementskey-post-nav-prev">
                <?php previous_post_link( '<a href="%link" class="elementskey-post-nav-link">' . ( $show_arrow ? '<span class="elementskey-post-navigation-arrow">&larr;</span>' : '' ) . '<span class="elementskey-post-navigation-label">%title</span></a>' ); ?>
            </div>
            <div class="elementskey-post-nav-next">
                <?php next_post_link( '<a href="%link" class="elementskey-post-nav-link">' . '<span class="elementskey-post-navigation-label">%title</span>' . ( $show_arrow ? '<span class="elementskey-post-navigation-arrow">&rarr;</span>' : '' ) . '</a>' ); ?>
            </div>
        </div>
        <?php
    }
}