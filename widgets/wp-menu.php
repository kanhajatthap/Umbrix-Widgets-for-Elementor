<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class ELEMENTSKEY_Wp_Menu_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'elementskey_wp_menu';
    }

    public function get_title() {
        return 'WordPress Menu';
    }

    public function get_icon() {
        return 'eicon-nav-menu';
    }

    public function get_categories() {
        return [ 'elementskey-elements' ];
    }

    public function get_style_depends() {
        return [ 'elementskey-content-style' ];
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
            'elementskey_wp_menu_section',
            [
                'label' => __( 'WordPress Menu', 'elementskey' ),
            ]
        );

        $this->add_control(
            'wp_menu_source',
            [
                'label' => __( 'Source', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'menu',
                'options' => [
                    'menu' => __( 'Specific Menu', 'elementskey' ),
                    'location' => __( 'Theme Location', 'elementskey' ),
                ],
            ]
        );

        $this->add_control(
            'wp_menu_id',
            [
                'label' => __( 'Select Menu', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $this->get_menus(),
                'condition' => [ 'wp_menu_source' => 'menu' ],
            ]
        );

        $this->add_control(
            'wp_menu_location',
            [
                'label' => __( 'Theme Location', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $this->get_locations(),
                'condition' => [ 'wp_menu_source' => 'location' ],
            ]
        );

        $this->add_responsive_control(
            'wp_menu_align',
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
                    '{{WRAPPER}} .elementskey-wp-menu' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_wp_menu_style',
            [
                'label' => __( 'Style', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'wp_menu_typography',
                'selector' => '{{WRAPPER}} .elementskey-wp-menu a',
            ]
        );

        $this->add_control(
            'wp_menu_color',
            [
                'label' => __( 'Link Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-wp-menu a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'wp_menu_hover_color',
            [
                'label' => __( 'Hover Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-wp-menu a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $args = [
            'container'     => false,
            'menu_class'    => 'elementskey-wp-menu',
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
            <div class="elementskey-loop-grid-empty">Select a menu.</div>
            <?php
            return;
        }

        wp_nav_menu( $args );
    }
}
