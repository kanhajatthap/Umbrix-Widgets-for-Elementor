<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class ELEMENTSKEY_Button_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'elementskey_button';
    }

    public function get_title() {
        return 'Button';
    }

    public function get_icon() {
        return 'eicon-button';
    }

    public function get_categories() {
        return [ 'elementskey-elements' ];
    }

    public function get_style_depends() {
        return [ 'elementskey-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'elementskey_button_content_section',
            [
                'label' => __( 'Button', 'elementskey' ),
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => __( 'Text', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Click Here', 'elementskey' ),
                'placeholder' => __( 'Click Here', 'elementskey' ),
            ]
        );

        $this->add_control(
            'button_link',
            [
                'label' => __( 'Link', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://example.com',
                'dynamic' => [ 'active' => true ],
            ]
        );

        $this->add_control(
            'button_icon',
            [
                'label' => __( 'Icon', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::ICONS,
            ]
        );

        $this->add_control(
            'icon_position',
            [
                'label' => __( 'Icon Position', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'before',
                'options' => [
                    'before' => __( 'Before Text', 'elementskey' ),
                    'after' => __( 'After Text', 'elementskey' ),
                ],
                'condition' => [ 'button_icon[value]!' => '' ],
            ]
        );

        $this->add_responsive_control(
            'icon_gap',
            [
                'label' => __( 'Icon Spacing', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 30 ] ],
                'default' => [ 'size' => 8, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-button .elementskey-button-icon.elementskey-icon-before' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementskey-button .elementskey-button-icon.elementskey-icon-after' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [ 'button_icon[value]!' => '' ],
            ]
        );

        $this->add_control(
            'button_size',
            [
                'label' => __( 'Size', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'md',
                'options' => [
                    'sm' => __( 'Small', 'elementskey' ),
                    'md' => __( 'Medium', 'elementskey' ),
                    'lg' => __( 'Large', 'elementskey' ),
                ],
            ]
        );

        $this->add_control(
            'full_width',
            [
                'label' => __( 'Full Width', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_button_style_section',
            [
                'label' => __( 'Style', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .elementskey-button',
            ]
        );

        $this->start_controls_tabs( 'button_tabs' );

        $this->start_controls_tab(
            'button_normal',
            [ 'label' => __( 'Normal', 'elementskey' ) ]
        );

        $this->add_control(
            'button_color',
            [
                'label' => __( 'Text Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_bg',
            [
                'label' => __( 'Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_shadow',
                'selector' => '{{WRAPPER}} .elementskey-button',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'button_hover',
            [ 'label' => __( 'Hover', 'elementskey' ) ]
        );

        $this->add_control(
            'button_hover_color',
            [
                'label' => __( 'Text Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_bg',
            [
                'label' => __( 'Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_border',
            [
                'label' => __( 'Border Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-button:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_translate',
            [
                'label' => __( 'Hover Animation', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => 'None',
                    'grow' => __( 'Grow', 'elementskey' ),
                    'shrink' => __( 'Shrink', 'elementskey' ),
                    'lift' => __( 'Lift Up', 'elementskey' ),
                    'fade' => __( 'Fade', 'elementskey' ),
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'selector' => '{{WRAPPER}} .elementskey-button',
            ]
        );

        $this->add_responsive_control(
            'button_radius',
            [
                'label' => __( 'Border Radius', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
                'default' => [ 'size' => 6, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-button' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => __( 'Padding', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_align',
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
        $icon         = ! empty( $settings['button_icon']['value'] ) ? $settings['button_icon'] : '';
        $icon_pos     = ! empty( $settings['icon_position'] ) ? $settings['icon_position'] : 'before';
        $size         = ! empty( $settings['button_size'] ) ? $settings['button_size'] : 'md';
        $full_width   = ( 'yes' === $settings['full_width'] );
        $hover_anim   = ! empty( $settings['button_hover_translate'] ) ? $settings['button_hover_translate'] : 'none';

        $classes = [ 'elementskey-button', 'elementskey-button-size-' . $size ];
        if ( $full_width ) {
            $classes[] = 'elementskey-button-full';
        }
        if ( 'none' !== $hover_anim ) {
            $classes[] = 'elementskey-button-hover-' . $hover_anim;
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
        <div class="elementskey-button-wrap">
            <a <?php echo $this->get_render_attribute_string( 'button' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Elementor render attributes are escaped internally. ?>>
                <?php if ( $icon && 'before' === $icon_pos ) : ?>
                    <span class="elementskey-button-icon elementskey-icon-before"><?php \Elementor\Icons_Manager::render_icon( $icon, [ 'aria-hidden' => 'true' ] ); ?></span>
                <?php endif; ?>
                <span class="elementskey-button-text"><?php echo esc_html( $text ); ?></span>
                <?php if ( $icon && 'after' === $icon_pos ) : ?>
                    <span class="elementskey-button-icon elementskey-icon-after"><?php \Elementor\Icons_Manager::render_icon( $icon, [ 'aria-hidden' => 'true' ] ); ?></span>
                <?php endif; ?>
            </a>
        </div>
        <?php
    }
}