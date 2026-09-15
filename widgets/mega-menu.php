<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class ELEMENTSKEY_Mega_Menu_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'elementskey_mega_menu';
    }

    public function get_title() {
        return 'Mega Menu';
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

    protected function get_menu_item_options() {
        static $options = null;

        if ( null !== $options ) {
            return $options;
        }

        $options = [];
        $menus = wp_get_nav_menus();

        foreach ( $menus as $menu ) {
            $items = wp_get_nav_menu_items( $menu->term_id );

            if ( ! $items ) {
                continue;
            }

            foreach ( $items as $item ) {
                if ( 0 !== (int) $item->menu_item_parent ) {
                    continue;
                }

                $options[ $item->ID ] = $menu->name . ' — ' . esc_html( $item->title );
            }
        }

        return $options;
    }

    protected function get_templates() {
        static $templates = null;

        if ( null !== $templates ) {
            return $templates;
        }

        $templates = [];

        $args = [
            'post_type'      => 'elementor_library',
            'post_status'    => 'publish',
            'posts_per_page' => 100,
            'orderby'        => 'title',
            'order'          => 'ASC',
        ];

        $items = get_posts( $args );

        foreach ( $items as $item ) {
            $type = get_post_meta( $item->ID, '_elementor_template_type', true );

            if ( $type && ! in_array( $type, [ 'section', 'container', 'page' ], true ) ) {
                continue;
            }

            $templates[ $item->ID ] = $item->post_title . ( $type ? ' (' . $type . ')' : '' );
        }

        return $templates;
    }

    protected function get_current_document_sections() {
        $sections = [];

        if ( ! class_exists( '\Elementor\Plugin' ) ) {
            return $sections;
        }

        $document = \Elementor\Plugin::$instance->documents->get_current();

        if ( ! $document ) {
            $post_id = 0;

            if ( ! empty( $_GET['post'] ) ) {
                $post_id = absint( $_GET['post'] );
            } elseif ( ! empty( $_POST['post_id'] ) ) {
                $post_id = absint( $_POST['post_id'] );
            } elseif ( function_exists( 'get_the_ID' ) && get_the_ID() ) {
                $post_id = get_the_ID();
            }

            if ( $post_id ) {
                $document = \Elementor\Plugin::$instance->documents->get( $post_id );
            }
        }

        if ( ! $document || ! method_exists( $document, 'get_elements_data' ) ) {
            return $sections;
        }

        $elements = $document->get_elements_data();

        if ( empty( $elements ) ) {
            return $sections;
        }

        $exclude  = [];
        $counters = [];

        $this->collect_widget_ancestors( $elements, $this->get_id(), $exclude );
        $this->collect_section_options( $elements, $sections, $counters, $exclude );

        return $sections;
    }

    protected function collect_widget_ancestors( $elements, $widget_id, &$ancestors ) {
        if ( ! is_array( $elements ) ) {
            return;
        }

        foreach ( $elements as $element ) {
            if ( empty( $element['id'] ) || empty( $element['elements'] ) || ! is_array( $element['elements'] ) ) {
                continue;
            }

            if ( $this->tree_contains_widget( $element['elements'], $widget_id ) ) {
                if ( in_array( $element['elType'], [ 'section', 'container' ], true ) ) {
                    $ancestors[ $element['id'] ] = true;
                }

                $this->collect_widget_ancestors( $element['elements'], $widget_id, $ancestors );
            }
        }
    }

    protected function tree_contains_widget( $elements, $widget_id ) {
        if ( ! is_array( $elements ) ) {
            return false;
        }

        foreach ( $elements as $element ) {
            if ( (string) $element['id'] === (string) $widget_id ) {
                return true;
            }

            if ( ! empty( $element['elements'] ) && is_array( $element['elements'] ) && $this->tree_contains_widget( $element['elements'], $widget_id ) ) {
                return true;
            }
        }

        return false;
    }

    protected function collect_section_options( $elements, &$options, &$counters, $exclude ) {
        if ( ! is_array( $elements ) ) {
            return;
        }

        foreach ( $elements as $element ) {
            if ( empty( $element['id'] ) || empty( $element['elType'] ) ) {
                continue;
            }

            if ( in_array( $element['elType'], [ 'section', 'container' ], true ) && empty( $exclude[ $element['id'] ] ) ) {
                $type = ( 'container' === $element['elType'] ) ? 'Container' : 'Section';
                $counters[ $type ] = ( isset( $counters[ $type ] ) ? $counters[ $type ] : 0 ) + 1;

                $label = $type . ' ' . $counters[ $type ];

                $text = $this->first_heading_text( $element );

                if ( $text ) {
                    $label .= ' — ' . $text;
                }

                $options[ $element['id'] ] = $label;
            }

            if ( ! empty( $element['elements'] ) && is_array( $element['elements'] ) ) {
                $this->collect_section_options( $element['elements'], $options, $counters, $exclude );
            }
        }
    }

    protected function first_heading_text( $element, $depth = 0 ) {
        if ( ! is_array( $element ) || $depth > 4 ) {
            return '';
        }

        if ( ! empty( $element['widgetType'] ) && in_array( $element['widgetType'], [ 'heading', 'text-editor', 'call-to-action', 'icon-box', 'image-box' ], true ) ) {
            $settings = ! empty( $element['settings'] ) ? $element['settings'] : [];

            if ( ! empty( $settings['title'] ) ) {
                return wp_strip_all_tags( $settings['title'] );
            }

            if ( ! empty( $settings['editor'] ) ) {
                return wp_strip_all_tags( $settings['editor'] );
            }
        }

        if ( ! empty( $element['elements'] ) && is_array( $element['elements'] ) ) {
            foreach ( $element['elements'] as $child ) {
                $text = $this->first_heading_text( $child, $depth + 1 );

                if ( $text ) {
                    return $text;
                }
            }
        }

        return '';
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
            'elementskey_mega_menu_section',
            [
                'label' => __( 'Mega Menu', 'elementskey' ),
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
            'megamenu_trigger',
            [
                'label' => __( 'Dropdown Trigger', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'hover',
                'options' => [
                    'hover' => __( 'Hover', 'elementskey' ),
                    'click' => __( 'Click', 'elementskey' ),
                ],
            ]
        );

        $this->add_control(
            'mega_width',
            [
                'label' => __( 'Default Panel Width', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'auto',
                'options' => [
                    'auto' => __( 'Auto (Fit Content)', 'elementskey' ),
                    'full' => __( 'Full Width', 'elementskey' ),
                    'custom' => __( 'Custom', 'elementskey' ),
                ],
                'description' => __( 'Default width for the mega menu panels. Can be overridden for each menu item.', 'elementskey' ),
            ]
        );

        $this->add_responsive_control(
            'mega_custom_width',
            [
                'label' => __( 'Custom Panel Width', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [ 'min' => 300, 'max' => 1600 ],
                ],
                'default' => [ 'unit' => 'px', 'size' => 920 ],
                'condition' => [
                    'mega_width' => 'custom',
                ],
                'selectors' => [
                    '{{WRAPPER}} .sub-menu.elementskey-mega-menu-sub:not(.is-mega-auto)' => 'width: {{SIZE}}{{UNIT}}; min-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'mega_items_heading',
            [
                'label' => __( 'Mega Menu Content', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $mega_repeater = new \Elementor\Repeater();

        $mega_repeater->add_control(
            'menu_item',
            [
                'label' => __( 'Menu Item', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $this->get_menu_item_options(),
            ]
        );

        $mega_repeater->add_control(
            'content_type',
            [
                'label' => __( 'Content Type', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'template',
                'options' => [
                    'template' => __( 'Elementor Template', 'elementskey' ),
                    'section' => __( 'Section on Current Page', 'elementskey' ),
                    'default' => __( 'Default Dropdown (Sub Menu)', 'elementskey' ),
                ],
            ]
        );

        $mega_repeater->add_control(
            'template',
            [
                'label' => __( 'Elementor Section', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $this->get_templates(),
                'condition' => [
                    'content_type' => 'template',
                ],
            ]
        );

        $mega_repeater->add_control(
            'same_section',
            [
                'label' => __( 'Section on this Page', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $this->get_current_document_sections(),
                'condition' => [
                    'content_type' => 'section',
                ],
            ]
        );

        $mega_repeater->add_control(
            'width',
            [
                'label' => __( 'Panel Width', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => __( 'Use Widget Default', 'elementskey' ),
                    'auto' => __( 'Auto (Fit Content)', 'elementskey' ),
                    'full' => __( 'Full Width', 'elementskey' ),
                    'custom' => __( 'Custom', 'elementskey' ),
                ],
                'condition' => [
                    'content_type' => [ 'template', 'section' ],
                ],
            ]
        );

        $mega_repeater->add_control(
            'custom_width',
            [
                'label' => __( 'Custom Width', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [ 'min' => 300, 'max' => 1600 ],
                ],
                'default' => [ 'unit' => 'px', 'size' => 920 ],
                'condition' => [
                    'content_type' => [ 'template', 'section' ],
                    'width' => 'custom',
                ],
            ]
        );

        $mega_repeater->add_control(
            'icon',
            [
                'label' => __( 'Menu Item Icon', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::ICONS,
            ]
        );

        $mega_repeater->add_control(
            'badge',
            [
                'label' => __( 'Menu Item Badge', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __( 'e.g. New', 'elementskey' ),
            ]
        );

        $this->add_control(
            'mega_items',
            [
                'label' => __( 'Assign Templates', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $mega_repeater->get_controls(),
                'title_field' => '{{{ menu_item }}}',
                'description' => __( 'Map each top-level menu item to an Elementor saved Section/Container (Templates → Saved Templates) or to a Section/Container built on this same page. Per item you can also add an icon, a badge label and override the panel width.', 'elementskey' ),
            ]
        );

        $this->end_controls_section();

        /* Style: Menu Items */
        $this->start_controls_section(
            'elementskey_mega_items_style',
            [
                'label' => __( 'Menu Items', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'mega_menu_typography',
                'selector' => '{{WRAPPER}} .elementskey-menu > li > a',
            ]
        );

        $this->start_controls_tabs( 'mega_menu_items_tabs' );

        $this->start_controls_tab( 'mega_menu_items_normal', [ 'label' => __( 'Normal', 'elementskey' ) ] );

        $this->add_control(
            'mega_menu_link_color',
            [
                'label' => __( 'Text Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu > li > a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'mega_menu_item_background',
            [
                'label' => __( 'Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu > li > a' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 'mega_menu_items_hover', [ 'label' => __( 'Hover', 'elementskey' ) ] );

        $this->add_control(
            'mega_menu_hover_color',
            [
                'label' => __( 'Text Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu > li > a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'mega_menu_item_hover_background',
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
            'mega_menu_item_gap',
            [
                'label' => __( 'Items Gap', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 100 ],
                    'em' => [ 'min' => 0, 'max' => 5 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'mega_menu_item_padding',
            [
                'label' => __( 'Item Padding', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu > li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'mega_menu_item_radius',
            [
                'label' => __( 'Item Border Radius', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu > li > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /* Style: Menu Item Icon */
        $this->start_controls_section(
            'elementskey_mega_icon_style',
            [
                'label' => __( 'Menu Item Icon', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'mega_icon_size',
            [
                'label' => __( 'Icon Size', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem' ],
                'range' => [
                    'px' => [ 'min' => 8, 'max' => 48 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-mega-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'mega_icon_color',
            [
                'label' => __( 'Icon Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-mega-icon i, {{WRAPPER}} .elementskey-mega-icon svg' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'mega_icon_hover_color',
            [
                'label' => __( 'Icon Hover Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu > li > a:hover .elementskey-mega-icon i, {{WRAPPER}} .elementskey-menu > li > a:hover .elementskey-mega-icon svg' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'mega_icon_gap',
            [
                'label' => __( 'Icon Gap', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 40 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-mega-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /* Style: Menu Item Badge */
        $this->start_controls_section(
            'elementskey_mega_badge_style',
            [
                'label' => __( 'Menu Item Badge', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'mega_badge_typography',
                'selector' => '{{WRAPPER}} .elementskey-menu-badge',
            ]
        );

        $this->add_control(
            'mega_badge_color',
            [
                'label' => __( 'Text Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu-badge' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'mega_badge_background',
            [
                'label' => __( 'Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu-badge' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'mega_badge_radius',
            [
                'label' => __( 'Border Radius', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'mega_badge_padding',
            [
                'label' => __( 'Padding', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'mega_badge_gap',
            [
                'label' => __( 'Badge Gap', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 30 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu-badge' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /* Style: Mega Menu Panel */
        $this->start_controls_section(
            'elementskey_mega_panel_style',
            [
                'label' => __( 'Mega Menu Panel', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'mega_panel_align',
            [
                'label' => __( 'Panel Alignment', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => __( 'Left', 'elementskey' ),
                    'center' => __( 'Center (Wide)', 'elementskey' ),
                ],
                'description' => __( 'Center expands the panel evenly around the menu item for a full-width look.', 'elementskey' ),
            ]
        );

        $this->add_control(
            'mega_panel_background',
            [
                'label' => __( 'Background Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .sub-menu.elementskey-mega-menu-sub' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'mega_panel_border',
                'selector' => '{{WRAPPER}} .sub-menu.elementskey-mega-menu-sub',
            ]
        );

        $this->add_responsive_control(
            'mega_panel_radius',
            [
                'label' => __( 'Border Radius', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .sub-menu.elementskey-mega-menu-sub' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'mega_panel_shadow',
                'selector' => '{{WRAPPER}} .sub-menu.elementskey-mega-menu-sub',
            ]
        );

        $this->add_responsive_control(
            'mega_panel_padding',
            [
                'label' => __( 'Padding', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'default' => [ 'unit' => 'px', 'top' => 0, 'right' => 0, 'bottom' => 0, 'left' => 0 ],
                'selectors' => [
                    '{{WRAPPER}} .sub-menu.elementskey-mega-menu-sub' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'mega_caret_color',
            [
                'label' => __( 'Dropdown Indicator Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu .elementskey-menu-caret' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        /* Style: Mobile Menu */
        $this->start_controls_section(
            'elementskey_mega_mobile_style',
            [
                'label' => __( 'Mobile Menu', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'mega_mobile_panel_background',
            [
                'label' => __( 'Panel Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu-widget.is-mobile .elementskey-menu-container' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'mega_mobile_link_color',
            [
                'label' => __( 'Link Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu-widget.is-mobile .elementskey-menu > li > a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'mega_mobile_item_gap',
            [
                'label' => __( 'Items Gap', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 50 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu-widget.is-mobile .elementskey-menu' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'mega_mobile_item_padding',
            [
                'label' => __( 'Item Padding', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu-widget.is-mobile .elementskey-menu > li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'mega_mobile_template_heading',
            [
                'label' => __( 'Mega Panels on Mobile', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'mega_mobile_show_template',
            [
                'label' => __( 'Show Template Content', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'elementskey' ),
                'label_off' => __( 'Hide', 'elementskey' ),
                'return_value' => 'yes',
                'default' => 'yes',
                'description' => __( 'On mobile the mega panel collapses under its menu item. Disable to keep the mobile menu compact.', 'elementskey' ),
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $menu_id = ! empty( $settings['menu_id'] ) ? absint( $settings['menu_id'] ) : 0;

        if ( ! $menu_id ) {
            ?>
            <div class="elementskey-loop-grid-empty"><?php esc_html_e( 'Select a menu.', 'elementskey' ); ?></div>
            <?php
            return;
        }

        $element_id = $this->get_id();
        $breakpoint = $this->get_mobile_breakpoint();
        $trigger    = ! empty( $settings['megamenu_trigger'] ) ? $settings['megamenu_trigger'] : 'hover';
        $panel_align = ! empty( $settings['mega_panel_align'] ) ? $settings['mega_panel_align'] : 'left';
        $default_width = ! empty( $settings['mega_width'] ) ? $settings['mega_width'] : 'auto';
        $show_mobile_template = ! empty( $settings['mega_mobile_show_template'] ) && 'yes' === $settings['mega_mobile_show_template'];

        $mega_map = [];
        if ( ! empty( $settings['mega_items'] ) && is_array( $settings['mega_items'] ) ) {
            foreach ( $settings['mega_items'] as $row ) {
                $item_id     = ! empty( $row['menu_item'] ) ? absint( $row['menu_item'] ) : 0;
                $content_type = ! empty( $row['content_type'] ) ? $row['content_type'] : 'template';
                $template_id = ! empty( $row['template'] ) ? absint( $row['template'] ) : 0;
                $same_section = ! empty( $row['same_section'] ) ? sanitize_text_field( $row['same_section'] ) : '';
                $width       = ! empty( $row['width'] ) ? $row['width'] : '';
                $custom_w    = ! empty( $row['custom_width']['size'] ) ? (int) $row['custom_width']['size'] : 0;
                $badge       = ! empty( $row['badge'] ) ? $row['badge'] : '';
                $icon        = ! empty( $row['icon'] ) ? $row['icon'] : [];

                if ( ! $item_id ) {
                    continue;
                }

                $mega_map[ $item_id ] = [
                    'content_type' => $content_type,
                    'template'     => $template_id,
                    'same_section' => $same_section,
                    'width'        => $width ? $width : $default_width,
                    'custom_width' => $custom_w,
                    'badge'        => $badge,
                    'icon'         => $icon,
                ];
            }
        }

        $hide_sections = [];

        if ( ! empty( $settings['mega_items'] ) && is_array( $settings['mega_items'] ) ) {
            foreach ( $settings['mega_items'] as $row ) {
                if ( ! empty( $row['content_type'] ) && 'section' === $row['content_type'] && ! empty( $row['same_section'] ) ) {
                    $hide_sections[] = sanitize_text_field( $row['same_section'] );
                }
            }
        }

        $wrapper_attrs = [
            'class'              => 'elementskey-menu-widget elementskey-mega-menu-widget' . ( 'center' === $panel_align ? ' is-mega-center' : '' ) . ( $show_mobile_template ? '' : ' is-hide-mobile-template' ),
            'data-menu-breakpoint' => $breakpoint,
            'data-menu-trigger'    => $trigger,
            'style'              => '--elementskey-menu-breakpoint: ' . $breakpoint . 'px;',
        ];

        if ( ! empty( $hide_sections ) ) {
            $wrapper_attrs['data-hide-sections'] = implode( ',', array_unique( $hide_sections ) );
        }

        $this->add_render_attribute(
            'elementskey-menu-wrapper',
            $wrapper_attrs
        );

        $walker  = new ELEMENTSKEY_Mega_Menu_Walker( $mega_map );
        $nav_args = [
            'menu'        => $menu_id,
            'container'   => false,
            'menu_class'  => 'elementskey-menu is-horizontal',
            'menu_id'     => 'elementskey-mega-menu-' . $element_id,
            'fallback_cb' => false,
            'depth'       => 0,
            'echo'        => false,
            'walker'      => $walker,
            'link_after'  => '<span class="elementskey-menu-caret" aria-hidden="true"></span>',
        ];

        $menu_html = wp_nav_menu( $nav_args );

        if ( ! $menu_html ) {
            ?>
            <div class="elementskey-loop-grid-empty"><?php esc_html_e( 'Select a menu.', 'elementskey' ); ?></div>
            <?php
            return;
        }
        ?>
        <nav <?php $this->print_render_attribute_string( 'elementskey-menu-wrapper' ); ?> aria-label="<?php esc_attr_e( 'Mega Menu', 'elementskey' ); ?>">
            <button class="elementskey-menu-toggle" type="button" aria-label="<?php esc_attr_e( 'Toggle menu', 'elementskey' ); ?>" aria-expanded="false" aria-controls="elementskey-mega-menu-container-<?php echo esc_attr( $element_id ); ?>">
                <span class="elementskey-menu-toggle-bar" aria-hidden="true"></span>
                <span class="elementskey-menu-toggle-bar" aria-hidden="true"></span>
                <span class="elementskey-menu-toggle-bar" aria-hidden="true"></span>
            </button>
            <div class="elementskey-menu-container" id="elementskey-mega-menu-container-<?php echo esc_attr( $element_id ); ?>">
                <?php echo $menu_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Menu and Elementor template HTML rendered by the walker. ?>
            </div>
        </nav>
        <?php
    }
}

if ( ! class_exists( 'ELEMENTSKEY_Mega_Menu_Walker' ) ) {
    class ELEMENTSKEY_Mega_Menu_Walker extends \Walker_Nav_Menu {

        private $mega_map = [];

        public function __construct( $mega_map = [] ) {
            $this->mega_map = $mega_map;
        }

        private function item_config( $item_id ) {
            if ( empty( $this->mega_map[ $item_id ] ) ) {
                return false;
            }

            return $this->mega_map[ $item_id ];
        }

        private function is_mega_config( $config, $depth ) {
            if ( 0 !== (int) $depth || ! $config || empty( $config['content_type'] ) ) {
                return false;
            }

            if ( 'template' === $config['content_type'] && ! empty( $config['template'] ) ) {
                return true;
            }

            if ( 'section' === $config['content_type'] && ! empty( $config['same_section'] ) ) {
                return true;
            }

            return false;
        }

        public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
            if ( ! $element ) {
                return;
            }

            $id_field = $this->db_fields['id'];
            $id       = $element->$id_field;

            $config      = $this->item_config( $id );
            $has_children = ! empty( $children_elements[ $id ] );
            $is_mega     = $this->is_mega_config( $config, $depth );

            $classes = is_array( $element->classes ) ? $element->classes : [];
            if ( $is_mega || $has_children ) {
                $classes[] = 'menu-item-has-children';
            }
            if ( $is_mega ) {
                $classes[] = 'elementskey-mega-menu-item';
                if ( 'full' === $config['width'] ) {
                    $classes[] = 'elementskey-mega-full';
                } elseif ( 'custom' === $config['width'] ) {
                    $classes[] = 'elementskey-mega-custom';
                } else {
                    $classes[] = 'elementskey-mega-auto';
                }
            }
            $element->classes = $classes;

            $args_obj = is_array( $args ) ? ( isset( $args[0] ) ? $args[0] : (object) $args ) : $args;

            $this->start_el( $output, $element, $depth, $args_obj );

            if ( $is_mega ) {
                $this->maybe_mega_content( $output, $id, $config );
            } elseif ( $has_children && ( empty( $max_depth ) || (int) $depth < (int) $max_depth ) ) {
                $this->start_lvl( $output, $depth, $args_obj );
                foreach ( $children_elements[ $id ] as $child ) {
                    $this->display_element( $child, $children_elements, $max_depth, $depth + 1, $args_obj, $output );
                }
                $this->end_lvl( $output, $depth, $args_obj );
            }

            $this->end_el( $output, $element, $depth, $args_obj );
        }

        public function start_el( &$output, $item, $depth = 0, $args = [], $id = 0 ) {
            $indent  = ( $depth ) ? str_repeat( "\t", $depth ) : '';
            $classes = empty( $item->classes ) ? [] : (array) $item->classes;
            $classes[] = 'menu-item-' . $item->ID;

            $args = (object) $args;

            $class_names = implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
            $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

            $id_attr = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
            $id_attr = $id_attr ? ' id="' . esc_attr( $id_attr ) . '"' : '';

            $output .= $indent . '<li' . $id_attr . $class_names . '>';

            $atts = [];
            $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
            $atts['target'] = ! empty( $item->target ) ? $item->target : '';
            $atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
            $atts['href']   = ! empty( $item->url ) ? $item->url : '';
            $atts['aria-current'] = $item->current ? 'page' : '';

            $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

            $attributes = '';
            foreach ( $atts as $attr => $value ) {
                if ( ! empty( $value ) ) {
                    $value      = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                    $attributes .= ' ' . $attr . '="' . $value . '"';
                }
            }

            $title = apply_filters( 'the_title', $item->title, $item->ID );

            $config = ( 0 === (int) $depth ) ? $this->item_config( $item->ID ) : false;

            $item_output  = $args->before;
            $item_output .= '<a' . $attributes . '>';

            if ( $config && ! empty( $config['icon']['value'] ) ) {
                $item_output .= '<span class="elementskey-mega-icon" aria-hidden="true">';
                ob_start();
                \Elementor\Icons_Manager::render_icon( $config['icon'], [ 'aria-hidden' => 'true' ] );
                $item_output .= ob_get_clean();
                $item_output .= '</span>';
            }

            $item_output .= $args->link_before . $title;
            $item_output .= $args->link_after;

            if ( $config && '' !== (string) $config['badge'] ) {
                $item_output .= '<span class="elementskey-menu-badge">' . esc_html( $config['badge'] ) . '</span>';
            }

            $item_output .= '</a>';
            $item_output .= $args->after;

            $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
        }

        private function maybe_mega_content( &$output, $item_id, $config ) {
            if ( ! class_exists( '\Elementor\Plugin' ) ) {
                return;
            }

            $content = '';

            if ( 'template' === $config['content_type'] && ! empty( $config['template'] ) ) {
                $template_id = absint( $config['template'] );

                if ( 'publish' === get_post_status( $template_id ) ) {
                    $content = \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $template_id, true );
                }
            } elseif ( 'section' === $config['content_type'] && ! empty( $config['same_section'] ) ) {
                $content = $this->render_same_page_section( $config['same_section'] );
            }

            if ( ! $content ) {
                return;
            }

            $classes = 'sub-menu elementskey-mega-menu-sub';

            if ( 'full' === $config['width'] ) {
                $classes .= ' is-mega-fullwidth';
            }

            $style = '';
            if ( 'custom' === $config['width'] && ! empty( $config['custom_width'] ) ) {
                $style = ' style="width: ' . esc_attr( $config['custom_width'] ) . 'px; min-width: ' . esc_attr( $config['custom_width'] ) . 'px; max-width: calc(100vw - 32px);"';
            }

            $output .= '<div class="' . esc_attr( $classes ) . '"' . $style . '><div class="elementskey-mega-menu-content">' . $content . '</div></div>';
        }

        private function render_same_page_section( $section_id ) {
            if ( ! class_exists( '\Elementor\Plugin' ) ) {
                return '';
            }

            $document = \Elementor\Plugin::$instance->documents->get_current();

            if ( ! is_admin() && ! $document ) {
                $post_id = get_the_ID();

                if ( $post_id ) {
                    $document = \Elementor\Plugin::$instance->documents->get( $post_id );
                }
            }

            if ( ! $document || ! method_exists( $document, 'get_elements_data' ) ) {
                return '';
            }

            $elements = $document->get_elements_data();

            if ( empty( $elements ) ) {
                return '';
            }

            $element_data = $this->find_element_data( $elements, $section_id );

            if ( ! $element_data ) {
                return '';
            }

            $element = \Elementor\Plugin::$instance->elements_manager->create_element_instance( $element_data );

            if ( ! $element ) {
                return '';
            }

            ob_start();
            $element->print_element();
            return ob_get_clean();
        }

        private function find_element_data( $elements, $id ) {
            if ( ! is_array( $elements ) ) {
                return null;
            }

            foreach ( $elements as $element ) {
                if ( (string) $element['id'] === (string) $id ) {
                    return $element;
                }

                if ( ! empty( $element['elements'] ) && is_array( $element['elements'] ) ) {
                    $found = $this->find_element_data( $element['elements'], $id );

                    if ( $found ) {
                        return $found;
                    }
                }
            }

            return null;
        }
    }
}