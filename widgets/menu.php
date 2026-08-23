<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class ELEMENTSKEY_Menu_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'elementskey_menu';
    }

    public function get_title() {
        return 'Menu';
    }

    public function get_icon() {
        return 'eicon-menu-bar';
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

    protected function register_controls() {

        $this->start_controls_section(
            'elementskey_menu_section',
            [
                'label' => __( 'Menu', 'elementskey' ),
            ]
        );

        $this->add_control(
            'menu_id',
            [
                'label' => __( 'Select Menu', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $this->get_menus(),
            ]
        );

        $this->add_control(
            'menu_layout',
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

        $this->add_responsive_control(
            'menu_align',
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
                    '{{WRAPPER}} .elementskey-menu-widget' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_menu_style',
            [
                'label' => __( 'Style', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'menu_typography',
                'selector' => '{{WRAPPER}} .elementskey-menu a',
            ]
        );

        $this->add_control(
            'menu_link_color',
            [
                'label' => __( 'Link Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'menu_hover_color',
            [
                'label' => __( 'Hover Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu a:hover' => 'color: {{VALUE}};',
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
            <div class="elementskey-loop-grid-empty">Select a menu.</div>
            <?php
            return;
        }

        $layout = ( 'vertical' === $settings['menu_layout'] ) ? ' is-vertical' : ' is-horizontal';

        wp_nav_menu( [
            'menu'          => $menu_id,
            'container'     => false,
            'menu_class'    => 'elementskey-menu' . $layout,
            'fallback_cb'   => false,
            'depth'         => 3,
        ] );
    }
}
