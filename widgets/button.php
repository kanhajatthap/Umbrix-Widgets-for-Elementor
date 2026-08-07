<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Button_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_button';
    }

    public function get_title() {
        return 'Button';
    }

    public function get_icon() {
        return 'eicon-button';
    }

    public function get_categories() {
        return [ 'elementstack-elements' ];
    }

    public function get_style_depends() {
        return [ 'bdea-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'bdea_button_content_section',
            [
                'label' => 'Button',
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => 'Text',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Click Here',
                'placeholder' => 'Click Here',
            ]
        );

        $this->add_control(
            'button_link',
            [
                'label' => 'Link',
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://example.com',
                'dynamic' => [ 'active' => true ],
            ]
        );

        $this->add_control(
            'button_icon',
            [
                'label' => 'Icon',
                'type' => \Elementor\Controls_Manager::ICONS,
            ]
        );

        $this->add_control(
            'icon_position',
            [
                'label' => 'Icon Position',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'before',
                'options' => [
                    'before' => 'Before Text',
                    'after' => 'After Text',
                ],
                'condition' => [ 'button_icon[value]!' => '' ],
            ]
        );

        $this->add_responsive_control(
            'icon_gap',
            [
                'label' => 'Icon Spacing',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 30 ] ],
                'default' => [ 'size' => 8, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-button .bdea-button-icon.bdea-icon-before' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .bdea-button .bdea-button-icon.bdea-icon-after' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [ 'button_icon[value]!' => '' ],
            ]
        );

        $this->add_control(
            'button_size',
            [
                'label' => 'Size',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'md',
                'options' => [
                    'sm' => 'Small',
                    'md' => 'Medium',
                    'lg' => 'Large',
                ],
            ]
        );

        $this->add_control(
            'full_width',
            [
                'label' => 'Full Width',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_button_style_section',
            [
                'label' => 'Style',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .bdea-button',
            ]
        );

        $this->start_controls_tabs( 'button_tabs' );

        $this->start_controls_tab(
            'button_normal',
            [ 'label' => 'Normal' ]
        );

        $this->add_control(
            'button_color',
            [
                'label' => 'Text Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_bg',
            [
                'label' => 'Background',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_shadow',
                'selector' => '{{WRAPPER}} .bdea-button',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'button_hover',
            [ 'label' => 'Hover' ]
        );

        $this->add_control(
            'button_hover_color',
            [
                'label' => 'Text Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_bg',
            [
                'label' => 'Background',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_border',
            [
                'label' => 'Border Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-button:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_translate',
            [
                'label' => 'Hover Animation',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => 'None',
                    'grow' => 'Grow',
                    'shrink' => 'Shrink',
                    'lift' => 'Lift Up',
                    'fade' => 'Fade',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'selector' => '{{WRAPPER}} .bdea-button',
            ]
        );

        $this->add_responsive_control(
            'button_radius',
            [
                'label' => 'Border Radius',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
                'default' => [ 'size' => 6, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-button' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => 'Padding',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_align',
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
                    '{{WRAPPER}}' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $text         = ! empty( $settings['button_text'] ) ? $settings['button_text'] : 'Click Here';
        $link         = ! empty( $settings['button_link']['url'] ) ? $settings['button_link']['url'] : '#';
        $is_external  = ! empty( $settings['button_link']['is_external'] );
        $nofollow     = ! empty( $settings['button_link']['nofollow'] );
        $icon         = ! empty( $settings['button_icon']['value'] ) ? $settings['button_icon']['value'] : '';
        $icon_pos     = ! empty( $settings['icon_position'] ) ? $settings['icon_position'] : 'before';
        $size         = ! empty( $settings['button_size'] ) ? $settings['button_size'] : 'md';
        $full_width   = ( 'yes' === $settings['full_width'] );
        $hover_anim   = ! empty( $settings['button_hover_translate'] ) ? $settings['button_hover_translate'] : 'none';

        $classes = [ 'bdea-button', 'bdea-button-size-' . $size ];
        if ( $full_width ) {
            $classes[] = 'bdea-button-full';
        }
        if ( 'none' !== $hover_anim ) {
            $classes[] = 'bdea-button-hover-' . $hover_anim;
        }

        $this->add_render_attribute( 'button', 'class', $classes );

        if ( ! empty( $settings['button_link']['url'] ) ) {
            $this->add_render_attribute( 'button', 'href', $settings['button_link']['url'] );
            if ( $is_external ) {
                $this->add_render_attribute( 'button', 'target', '_blank' );
            }
            if ( $nofollow ) {
                $this->add_render_attribute( 'button', 'rel', 'nofollow' );
            }
        } else {
            $this->add_render_attribute( 'button', 'role', 'button' );
        }
        ?>
        <div class="bdea-button-wrap">
            <a <?php echo $this->get_render_attribute_string( 'button' ); ?>>
                <?php if ( $icon && 'before' === $icon_pos ) : ?>
                    <span class="bdea-button-icon bdea-icon-before"><i class="<?php echo esc_attr( $icon ); ?>"></i></span>
                <?php endif; ?>
                <span class="bdea-button-text"><?php echo esc_html( $text ); ?></span>
                <?php if ( $icon && 'after' === $icon_pos ) : ?>
                    <span class="bdea-button-icon bdea-icon-after"><i class="<?php echo esc_attr( $icon ); ?>"></i></span>
                <?php endif; ?>
            </a>
        </div>
        <?php
    }
}