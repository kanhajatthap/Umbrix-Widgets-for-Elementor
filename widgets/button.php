<?php
/**
 * ElementStack Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

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
                'label' => __( 'Button', 'elementstack-elementor-addons' ),
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => __( 'Text', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Click Here', 'elementstack-elementor-addons' ),
                'placeholder' => __( 'Click Here', 'elementstack-elementor-addons' ),
            ]
        );

        $this->add_control(
            'button_link',
            [
                'label' => __( 'Link', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://example.com',
                'dynamic' => [ 'active' => true ],
            ]
        );

        $this->add_control(
            'button_icon',
            [
                'label' => __( 'Icon', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::ICONS,
            ]
        );

        $this->add_control(
            'icon_position',
            [
                'label' => __( 'Icon Position', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'before',
                'options' => [
                    'before' => __( 'Before Text', 'elementstack-elementor-addons' ),
                    'after' => __( 'After Text', 'elementstack-elementor-addons' ),
                ],
                'condition' => [ 'button_icon[value]!' => '' ],
            ]
        );

        $this->add_responsive_control(
            'icon_gap',
            [
                'label' => __( 'Icon Spacing', 'elementstack-elementor-addons' ),
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
                'label' => __( 'Size', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'md',
                'options' => [
                    'sm' => __( 'Small', 'elementstack-elementor-addons' ),
                    'md' => __( 'Medium', 'elementstack-elementor-addons' ),
                    'lg' => __( 'Large', 'elementstack-elementor-addons' ),
                ],
            ]
        );

        $this->add_control(
            'full_width',
            [
                'label' => __( 'Full Width', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_button_style_section',
            [
                'label' => __( 'Style', 'elementstack-elementor-addons' ),
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
            [ 'label' => __( 'Normal', 'elementstack-elementor-addons' ) ]
        );

        $this->add_control(
            'button_color',
            [
                'label' => __( 'Text Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_bg',
            [
                'label' => __( 'Background', 'elementstack-elementor-addons' ),
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
            [ 'label' => __( 'Hover', 'elementstack-elementor-addons' ) ]
        );

        $this->add_control(
            'button_hover_color',
            [
                'label' => __( 'Text Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_bg',
            [
                'label' => __( 'Background', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_border',
            [
                'label' => __( 'Border Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-button:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_translate',
            [
                'label' => __( 'Hover Animation', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => 'None',
                    'grow' => __( 'Grow', 'elementstack-elementor-addons' ),
                    'shrink' => __( 'Shrink', 'elementstack-elementor-addons' ),
                    'lift' => __( 'Lift Up', 'elementstack-elementor-addons' ),
                    'fade' => __( 'Fade', 'elementstack-elementor-addons' ),
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
                'label' => __( 'Border Radius', 'elementstack-elementor-addons' ),
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
                'label' => __( 'Padding', 'elementstack-elementor-addons' ),
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
                'label' => __( 'Alignment', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [ 'title' => __( 'Left', 'elementstack-elementor-addons' ), 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => __( 'Center', 'elementstack-elementor-addons' ), 'icon' => 'eicon-text-align-center' ],
                    'right' => [ 'title' => __( 'Right', 'elementstack-elementor-addons' ), 'icon' => 'eicon-text-align-right' ],
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
            <a <?php echo $this->get_render_attribute_string( 'button' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Elementor render attributes are escaped internally. ?>>
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