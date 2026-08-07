<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Off_Canvas_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_off_canvas';
    }

    public function get_title() {
        return 'Off-Canvas';
    }

    public function get_icon() {
        return 'eicon-sidebar';
    }

    public function get_categories() {
        return [ 'elementstack-elements' ];
    }

    public function get_style_depends() {
        return [ 'bdea-content-style' ];
    }

    public function get_script_depends() {
        return [ 'bdea-content-script' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'bdea_trigger_section',
            [
                'label' => 'Trigger',
            ]
        );

        $this->add_control(
            'trigger_text',
            [
                'label' => 'Button Text',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Open Panel',
                'placeholder' => 'Open Panel',
            ]
        );

        $this->add_control(
            'trigger_icon',
            [
                'label' => 'Icon (optional)',
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-bars',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_content_section',
            [
                'label' => 'Content',
            ]
        );

        $this->add_control(
            'panel_title',
            [
                'label' => 'Panel Title',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Menu',
                'placeholder' => 'Menu',
            ]
        );

        $this->add_control(
            'panel_content',
            [
                'label' => 'Panel Content',
                'type' => \Elementor\Controls_Manager::WYSIWYG,
                'default' => 'Add your content here. This panel works for menus, filters, forms and more.',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_behavior_section',
            [
                'label' => 'Behavior',
            ]
        );

        $this->add_control(
            'position',
            [
                'label' => 'Position',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'right',
                'options' => [
                    'left' => 'Left',
                    'right' => 'Right',
                    'top' => 'Top',
                    'bottom' => 'Bottom',
                ],
            ]
        );

        $this->add_responsive_control(
            'panel_width',
            [
                'label' => 'Panel Width (side panels)',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'vw' ],
                'range' => [ 'px' => [ 'min' => 200, 'max' => 1200 ], '%' => [ 'min' => 10, 'max' => 100 ], 'vw' => [ 'min' => 10, 'max' => 100 ] ],
                'default' => [ 'size' => 340, 'unit' => 'px' ],
                'condition' => [
                    'position' => [ 'left', 'right' ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-off-canvas-panel.bdea-pos-left, {{WRAPPER}} .bdea-off-canvas-panel.bdea-pos-right' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'panel_height',
            [
                'label' => 'Panel Height (top/bottom)',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'vh' ],
                'range' => [ 'px' => [ 'min' => 100, 'max' => 1200 ], '%' => [ 'min' => 10, 'max' => 100 ], 'vh' => [ 'min' => 10, 'max' => 100 ] ],
                'default' => [ 'size' => 320, 'unit' => 'px' ],
                'condition' => [
                    'position' => [ 'top', 'bottom' ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-off-canvas-panel.bdea-pos-top, {{WRAPPER}} .bdea-off-canvas-panel.bdea-pos-bottom' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'close_on_overlay',
            [
                'label' => 'Close on Overlay Click',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'close_on_esc',
            [
                'label' => 'Close on ESC',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_trigger_style_section',
            [
                'label' => 'Trigger Button',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'trigger_typography',
                'selector' => '{{WRAPPER}} .bdea-off-canvas-trigger',
            ]
        );

        $this->start_controls_tabs( 'trigger_tabs' );

        $this->start_controls_tab(
            'trigger_normal',
            [ 'label' => 'Normal' ]
        );

        $this->add_control(
            'trigger_color',
            [
                'label' => 'Text Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-off-canvas-trigger' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'trigger_bg',
            [
                'label' => 'Background',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-off-canvas-trigger' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'trigger_hover',
            [ 'label' => 'Hover' ]
        );

        $this->add_control(
            'trigger_hover_color',
            [
                'label' => 'Text Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-off-canvas-trigger:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'trigger_hover_bg',
            [
                'label' => 'Background',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-off-canvas-trigger:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'trigger_border',
                'selector' => '{{WRAPPER}} .bdea-off-canvas-trigger',
            ]
        );

        $this->add_responsive_control(
            'trigger_radius',
            [
                'label' => 'Border Radius',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-off-canvas-trigger' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'trigger_padding',
            [
                'label' => 'Padding',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-off-canvas-trigger' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_panel_style_section',
            [
                'label' => 'Panel',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'panel_bg',
            [
                'label' => 'Background',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-off-canvas-panel' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'panel_padding',
            [
                'label' => 'Padding',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-off-canvas-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'overlay_color',
            [
                'label' => 'Overlay Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-off-canvas-overlay' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'close_color',
            [
                'label' => 'Close Button Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-off-canvas-close' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'close_bg',
            [
                'label' => 'Close Button Background',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-off-canvas-close' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_panel_typography_section',
            [
                'label' => 'Panel Title & Content',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'panel_title_typography',
                'selector' => '{{WRAPPER}} .bdea-off-canvas-title',
            ]
        );

        $this->add_control(
            'panel_title_color',
            [
                'label' => 'Title Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-off-canvas-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'panel_content_typography',
                'selector' => '{{WRAPPER}} .bdea-off-canvas-content',
            ]
        );

        $this->add_control(
            'panel_content_color',
            [
                'label' => 'Content Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-off-canvas-content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'content_links_color',
            [
                'label' => 'Link Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-off-canvas-content a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $position = ! empty( $settings['position'] ) ? $settings['position'] : 'right';
        $close_on_overlay = ( 'yes' === $settings['close_on_overlay'] );
        $close_on_esc = ( 'yes' === $settings['close_on_esc'] );

        $this->add_render_attribute( 'wrapper', [
            'class' => 'bdea-off-canvas-widget',
            'data-close-overlay' => $close_on_overlay ? 'yes' : 'no',
            'data-close-esc' => $close_on_esc ? 'yes' : 'no',
            'data-position' => $position,
        ] );

        $icon = ! empty( $settings['trigger_icon']['value'] ) ? $settings['trigger_icon']['value'] : '';
        ?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
            <button type="button" class="bdea-off-canvas-trigger">
                <?php if ( $icon ) : ?>
                    <span class="bdea-off-canvas-trigger-icon"><i class="<?php echo esc_attr( $icon ); ?>"></i></span>
                <?php endif; ?>
                <span class="bdea-off-canvas-trigger-text"><?php echo esc_html( $settings['trigger_text'] ); ?></span>
            </button>

            <div class="bdea-off-canvas-overlay"></div>

            <div class="bdea-off-canvas-panel bdea-pos-<?php echo esc_attr( $position ); ?>">
                <div class="bdea-off-canvas-header">
                    <h4 class="bdea-off-canvas-title"><?php echo esc_html( $settings['panel_title'] ); ?></h4>
                    <button type="button" class="bdea-off-canvas-close" aria-label="Close">&#10005;</button>
                </div>
                <div class="bdea-off-canvas-body">
                    <div class="bdea-off-canvas-content"><?php echo wp_kses_post( $settings['panel_content'] ); ?></div>
                </div>
            </div>
        </div>
        <?php
    }
}