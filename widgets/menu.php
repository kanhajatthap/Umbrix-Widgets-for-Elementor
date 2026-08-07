<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Menu_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_menu';
    }

    public function get_title() {
        return 'Menu';
    }

    public function get_icon() {
        return 'eicon-menu-bar';
    }

    public function get_categories() {
        return [ 'elementstack-elements' ];
    }

    public function get_style_depends() {
        return [ 'bdea-content-style' ];
    }

    protected function get_menus() {
        $menus = wp_get_nav_menus();
        $options = [];
        foreach ( $menus as $menu ) {
            $options[ $menu->term_id ] = $menu->name;
        }
        return $options;
    }

    protected function register_controls() {

        $this->start_controls_section(
            'bdea_menu_section',
            [
                'label' => 'Menu',
            ]
        );

        $this->add_control(
            'menu_id',
            [
                'label' => 'Select Menu',
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $this->get_menus(),
            ]
        );

        $this->add_control(
            'menu_layout',
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

        $this->add_responsive_control(
            'menu_align',
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
                    '{{WRAPPER}} .bdea-menu-widget' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_menu_style',
            [
                'label' => 'Style',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'menu_typography',
                'selector' => '{{WRAPPER}} .bdea-menu a',
            ]
        );

        $this->add_control(
            'menu_link_color',
            [
                'label' => 'Link Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .bdea-menu a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'menu_hover_color',
            [
                'label' => 'Hover Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .bdea-menu a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $menu_id = ! empty( $settings['menu_id'] ) ? absint( $settings['menu_id'] ) : 0;

        if ( ! $menu_id ) {
            ?>
            <div class="bdea-loop-grid-empty">Select a menu.</div>
            <?php
            return;
        }

        $layout = ( 'vertical' === $settings['menu_layout'] ) ? ' is-vertical' : ' is-horizontal';

        wp_nav_menu( [
            'menu'          => $menu_id,
            'container'     => false,
            'menu_class'    => 'bdea-menu' . $layout,
            'fallback_cb'   => false,
            'depth'         => 3,
        ] );
    }
}
