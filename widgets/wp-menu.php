<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Wp_Menu_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_wp_menu';
    }

    public function get_title() {
        return 'WordPress Menu';
    }

    public function get_icon() {
        return 'eicon-nav-menu';
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

    protected function get_locations() {
        return get_registered_nav_menus();
    }

    protected function register_controls() {

        $this->start_controls_section(
            'bdea_wp_menu_section',
            [
                'label' => 'WordPress Menu',
            ]
        );

        $this->add_control(
            'wp_menu_source',
            [
                'label' => 'Source',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'menu',
                'options' => [
                    'menu' => 'Specific Menu',
                    'location' => 'Theme Location',
                ],
            ]
        );

        $this->add_control(
            'wp_menu_id',
            [
                'label' => 'Select Menu',
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $this->get_menus(),
                'condition' => [ 'wp_menu_source' => 'menu' ],
            ]
        );

        $this->add_control(
            'wp_menu_location',
            [
                'label' => 'Theme Location',
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $this->get_locations(),
                'condition' => [ 'wp_menu_source' => 'location' ],
            ]
        );

        $this->add_responsive_control(
            'wp_menu_align',
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
                    '{{WRAPPER}} .bdea-wp-menu' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_wp_menu_style',
            [
                'label' => 'Style',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'wp_menu_typography',
                'selector' => '{{WRAPPER}} .bdea-wp-menu a',
            ]
        );

        $this->add_control(
            'wp_menu_color',
            [
                'label' => 'Link Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .bdea-wp-menu a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'wp_menu_hover_color',
            [
                'label' => 'Hover Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .bdea-wp-menu a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $args = [
            'container'     => false,
            'menu_class'    => 'bdea-wp-menu',
            'fallback_cb'   => false,
            'depth'         => 3,
        ];

        if ( 'location' === $settings['wp_menu_source'] ) {
            $args['theme_location'] = ! empty( $settings['wp_menu_location'] ) ? $settings['wp_menu_location'] : '';
        } else {
            $args['menu'] = ! empty( $settings['wp_menu_id'] ) ? absint( $settings['wp_menu_id'] ) : 0;
        }

        if ( empty( $args['menu'] ) && empty( $args['theme_location'] ) ) {
            ?>
            <div class="bdea-loop-grid-empty">Select a menu.</div>
            <?php
            return;
        }

        wp_nav_menu( $args );
    }
}
