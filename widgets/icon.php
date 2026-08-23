<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class ELEMENTSKEY_Icon_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'elementskey_icon';
    }

    public function get_title() {
        return 'Icon';
    }

    public function get_icon() {
        return 'eicon-favorite';
    }

    public function get_categories() {
        return [ 'elementskey-elements' ];
    }

    public function get_style_depends() {
        return [ 'elementskey-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'elementskey_icon_section',
            [
                'label' => __( 'Icon', 'elementskey' ),
            ]
        );

        $this->add_control(
            'selected_icon',
            [
                'label' => __( 'Icon', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-star',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $this->add_control(
            'icon_link',
            [
                'label' => __( 'Link', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://example.com',
                'dynamic' => [ 'active' => true ],
            ]
        );

        $this->add_responsive_control(
            'icon_align',
            [
                'label' => __( 'Alignment', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [ 'title' => __( 'Left', 'elementskey' ), 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => __( 'Center', 'elementskey' ), 'icon' => 'eicon-text-align-center' ],
                    'right' => [ 'title' => __( 'Right', 'elementskey' ), 'icon' => 'eicon-text-align-right' ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-icon-wrap' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_icon_style_section',
            [
                'label' => __( 'Style', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => __( 'Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-icon-box' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_hover_color',
            [
                'label' => __( 'Hover Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-icon-box:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label' => __( 'Size', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range' => [ 'px' => [ 'min' => 10, 'max' => 200 ], 'em' => [ 'min' => 0.5, 'max' => 12 ] ],
                'default' => [ 'size' => 34, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-icon-box i, {{WRAPPER}} .elementskey-icon-box svg' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_rotate',
            [
                'label' => __( 'Rotate (deg)', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'deg' ],
                'range' => [ 'deg' => [ 'min' => 0, 'max' => 360 ] ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-icon-box i, {{WRAPPER}} .elementskey-icon-box svg' => 'transform: rotate({{SIZE}}deg);',
                ],
            ]
        );

        $this->add_control(
            'icon_bg',
            [
                'label' => __( 'Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-icon-box' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_bg_hover',
            [
                'label' => __( 'Background Hover', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-icon-box:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_padding',
            [
                'label' => __( 'Padding', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-icon-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_radius',
            [
                'label' => __( 'Border Radius', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 100 ], '%' => [ 'min' => 0, 'max' => 100 ] ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-icon-box' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $icon   = ! empty( $settings['selected_icon']['value'] ) ? $settings['selected_icon'] : '';
        $has_link = ! empty( $settings['icon_link']['url'] );

        $this->add_render_attribute( 'icon', 'class', 'elementskey-icon-box' );
        if ( $has_link ) {
            $this->add_render_attribute( 'icon', 'href', $settings['icon_link']['url'] );
            if ( ! empty( $settings['icon_link']['is_external'] ) ) {
                $this->add_render_attribute( 'icon', 'target', '_blank' );
            }
            if ( ! empty( $settings['icon_link']['nofollow'] ) ) {
                $this->add_render_attribute( 'icon', 'rel', 'nofollow' );
            }
        }
        ?>
        <div class="elementskey-icon-wrap">
            <?php if ( $icon ) : ?>
                <?php if ( $has_link ) : ?>
                    <a <?php echo $this->get_render_attribute_string( 'icon' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Elementor render attributes are escaped internally. ?>>
                        <?php \Elementor\Icons_Manager::render_icon( $icon, [ 'aria-hidden' => 'true' ] ); ?>
                    </a>
                <?php else : ?>
                    <span <?php echo $this->get_render_attribute_string( 'icon' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Elementor render attributes are escaped internally. ?>>
                        <?php \Elementor\Icons_Manager::render_icon( $icon, [ 'aria-hidden' => 'true' ] ); ?>
                    </span>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <?php
    }
}