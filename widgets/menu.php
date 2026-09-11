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

    public function get_script_depends() {
        return [ 'elementskey-content-script' ];
    }

    protected function get_menus() {
        $menus = wp_get_nav_menus();
        $options = [];
        foreach ( $menus as $menu ) {
            $options[ $menu->term_id ] = $menu->name;
        }
        return $options;
    }

    protected function get_mobile_breakpoint() {
        $breakpoint = 767;

        if ( class_exists( '\Elementor\Plugin' ) && isset( \Elementor\Plugin::$instance->breakpoints ) ) {
            $breakpoints = \Elementor\Plugin::$instance->breakpoints->get_active_breakpoints();

            if ( ! empty( $breakpoints['mobile'] ) ) {
                $value = (int) $breakpoints['mobile']->get_value();

                if ( $value > 0 ) {
                    $breakpoint = $value;
                }
            }
        }

        return $breakpoint;
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
                'selectors_dictionary' => [
                    'left' => 'flex-start',
                    'center' => 'center',
                    'right' => 'flex-end',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu-widget .elementskey-menu:not(.is-vertical)' => 'justify-content: {{VALUE}};',
                    '{{WRAPPER}} .elementskey-menu-widget .elementskey-menu.is-vertical' => 'align-items: {{VALUE}};',
                    '{{WRAPPER}} .elementskey-menu-widget.is-mobile .elementskey-menu' => 'align-items: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        /* Style: Menu Items */
        $this->start_controls_section(
            'elementskey_menu_items_style',
            [
                'label' => __( 'Menu Items', 'elementskey' ),
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

        $this->start_controls_tabs( 'menu_items_tabs' );

        $this->start_controls_tab( 'menu_items_normal', [ 'label' => __( 'Normal', 'elementskey' ) ] );

        $this->add_control(
            'menu_link_color',
            [
                'label' => __( 'Text Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'menu_item_background',
            [
                'label' => __( 'Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu > li > a' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 'menu_items_hover', [ 'label' => __( 'Hover', 'elementskey' ) ] );

        $this->add_control(
            'menu_hover_color',
            [
                'label' => __( 'Text Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'menu_item_hover_background',
            [
                'label' => __( 'Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu > li > a:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'menu_item_gap',
            [
                'label' => __( 'Items Gap', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 100 ],
                    'em' => [ 'min' => 0, 'max' => 5 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu-widget .elementskey-menu' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'menu_item_padding',
            [
                'label' => __( 'Item Padding', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu-widget .elementskey-menu > li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'menu_item_radius',
            [
                'label' => __( 'Item Border Radius', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu-widget .elementskey-menu > li > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /* Style: Active Item */
        $this->start_controls_section(
            'elementskey_menu_active_style',
            [
                'label' => __( 'Active Item', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'menu_active_color',
            [
                'label' => __( 'Text Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu .current-menu-item > a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'menu_active_background',
            [
                'label' => __( 'Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu .current-menu-item > a' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        /* Style: Dropdown */
        $this->start_controls_section(
            'elementskey_menu_dropdown_style',
            [
                'label' => __( 'Dropdown', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'menu_dropdown_typography',
                'selector' => '{{WRAPPER}} .elementskey-menu .sub-menu a',
            ]
        );

        $this->add_responsive_control(
            'menu_dropdown_width',
            [
                'label' => __( 'Dropdown Width', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 600 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu .sub-menu' => 'min-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'menu_dropdown_background',
            [
                'label' => __( 'Background Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu .sub-menu' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'menu_dropdown_border',
                'selector' => '{{WRAPPER}} .elementskey-menu .sub-menu',
            ]
        );

        $this->add_responsive_control(
            'menu_dropdown_radius',
            [
                'label' => __( 'Border Radius', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu .sub-menu' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'menu_dropdown_shadow',
                'selector' => '{{WRAPPER}} .elementskey-menu .sub-menu',
            ]
        );

        $this->add_responsive_control(
            'menu_dropdown_padding',
            [
                'label' => __( 'Dropdown Padding', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu .sub-menu' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'menu_dropdown_items_heading',
            [
                'label' => __( 'Dropdown Items', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'menu_dropdown_item_padding',
            [
                'label' => __( 'Item Padding', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu .sub-menu a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'menu_dropdown_tabs' );

        $this->start_controls_tab( 'menu_dropdown_items_normal', [ 'label' => __( 'Normal', 'elementskey' ) ] );

        $this->add_control(
            'menu_dropdown_item_color',
            [
                'label' => __( 'Text Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#374151',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu .sub-menu a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'menu_dropdown_item_background',
            [
                'label' => __( 'Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu .sub-menu li' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 'menu_dropdown_items_hover', [ 'label' => __( 'Hover', 'elementskey' ) ] );

        $this->add_control(
            'menu_dropdown_item_hover_color',
            [
                'label' => __( 'Text Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu .sub-menu a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'menu_dropdown_item_hover_background',
            [
                'label' => __( 'Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#f3f4f6',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu .sub-menu li:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'menu_dropdown_separator_color',
            [
                'label' => __( 'Items Separator Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu .sub-menu li:not(:last-child)' => 'border-bottom: 1px solid {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'menu_dropdown_separator_width',
            [
                'label' => __( 'Separator Width', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 10 ],
                ],
                'default' => [ 'unit' => 'px', 'size' => 1 ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu .sub-menu li:not(:last-child)' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /* Style: Submenu Indicator */
        $this->start_controls_section(
            'elementskey_menu_caret_style',
            [
                'label' => __( 'Submenu Indicator', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'menu_caret_color',
            [
                'label' => __( 'Icon Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu .elementskey-menu-caret' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'menu_caret_size',
            [
                'label' => __( 'Icon Size', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 30 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu .elementskey-menu-caret' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'menu_caret_thickness',
            [
                'label' => __( 'Icon Thickness', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [ 'min' => 1, 'max' => 6 ],
                ],
                'default' => [ 'unit' => 'px', 'size' => 2 ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu .elementskey-menu-caret' => 'border-right-width: {{SIZE}}{{UNIT}}; border-bottom-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'menu_caret_gap',
            [
                'label' => __( 'Gap', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 30 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu .elementskey-menu-caret' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /* Style: Toggle Button */
        $this->start_controls_section(
            'elementskey_menu_toggle_style',
            [
                'label' => __( 'Toggle Button', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'menu_toggle_bar_color',
            [
                'label' => __( 'Bar Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu-toggle-bar' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'menu_toggle_bar_width',
            [
                'label' => __( 'Bar Width', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range' => [
                    'px' => [ 'min' => 10, 'max' => 50 ],
                ],
                'default' => [ 'unit' => 'px', 'size' => 24 ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu-toggle-bar' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'menu_toggle_bar_height',
            [
                'label' => __( 'Bar Height', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [ 'min' => 1, 'max' => 10 ],
                ],
                'default' => [ 'unit' => 'px', 'size' => 2 ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu-toggle-bar' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'menu_toggle_button_size',
            [
                'label' => __( 'Button Size', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range' => [
                    'px' => [ 'min' => 20, 'max' => 120 ],
                ],
                'default' => [ 'unit' => 'px', 'size' => 44 ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu-toggle' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'menu_toggle_background',
            [
                'label' => __( 'Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu-toggle' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'menu_toggle_radius',
            [
                'label' => __( 'Border Radius', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu-toggle' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'menu_toggle_align',
            [
                'label' => __( 'Alignment', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [ 'title' => __( 'Left', 'elementskey' ), 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => __( 'Center', 'elementskey' ), 'icon' => 'eicon-text-align-center' ],
                    'right' => [ 'title' => __( 'Right', 'elementskey' ), 'icon' => 'eicon-text-align-right' ],
                ],
                'default' => 'left',
                'selectors_dictionary' => [
                    'left' => 'margin-left: 0; margin-right: auto;',
                    'center' => 'margin-left: auto; margin-right: auto;',
                    'right' => 'margin-left: auto; margin-right: 0;',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu-toggle' => '{{VALUE}};',
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

        $layout      = ( 'vertical' === $settings['menu_layout'] ) ? 'is-vertical' : 'is-horizontal';
        $element_id  = $this->get_id();
        $breakpoint  = $this->get_mobile_breakpoint();

        $this->add_render_attribute(
            'elementskey-menu-wrapper',
            [
                'class'              => 'elementskey-menu-widget',
                'data-menu-breakpoint' => $breakpoint,
                'style'              => '--elementskey-menu-breakpoint: ' . $breakpoint . 'px;',
            ]
        );
        ?>
        <nav <?php $this->print_render_attribute_string( 'elementskey-menu-wrapper' ); ?> aria-label="<?php esc_attr_e( 'Menu', 'elementskey' ); ?>">
            <button class="elementskey-menu-toggle" type="button" aria-label="<?php esc_attr_e( 'Toggle menu', 'elementskey' ); ?>" aria-expanded="false" aria-controls="elementskey-menu-container-<?php echo esc_attr( $element_id ); ?>">
                <span class="elementskey-menu-toggle-bar" aria-hidden="true"></span>
                <span class="elementskey-menu-toggle-bar" aria-hidden="true"></span>
                <span class="elementskey-menu-toggle-bar" aria-hidden="true"></span>
            </button>
            <div class="elementskey-menu-container" id="elementskey-menu-container-<?php echo esc_attr( $element_id ); ?>">
                <?php
                wp_nav_menu( [
                    'menu'        => $menu_id,
                    'container'   => false,
                    'menu_class'  => 'elementskey-menu ' . $layout,
                    'menu_id'     => 'elementskey-menu-' . $element_id,
                    'fallback_cb' => false,
                    'depth'       => 3,
                    'link_after'  => '<span class="elementskey-menu-caret" aria-hidden="true"></span>',
                ] );
                ?>
            </div>
        </nav>
        <?php
    }
}
