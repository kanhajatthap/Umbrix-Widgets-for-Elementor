<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Icon_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_icon';
    }

    public function get_title() {
        return 'Icon';
    }

    public function get_icon() {
        return 'eicon-favorite';
    }

    public function get_categories() {
        return [ 'elementstack-elements' ];
    }

    public function get_style_depends() {
        return [ 'bdea-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'bdea_icon_section',
            [
                'label' => 'Icon',
            ]
        );

        $this->add_control(
            'selected_icon',
            [
                'label' => 'Icon',
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
                'label' => 'Link',
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://example.com',
                'dynamic' => [ 'active' => true ],
            ]
        );

        $this->add_responsive_control(
            'icon_align',
            [
                'label' => 'Alignment',
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [ 'title' => 'Left', 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => 'Center', 'icon' => 'eicon-text-align-center' ],
                    'right' => [ 'title' => 'Right', 'icon' => 'eicon-text-align-right' ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .bdea-icon-wrap' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_icon_style_section',
            [
                'label' => 'Style',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => 'Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .bdea-icon-box' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_hover_color',
            [
                'label' => 'Hover Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-icon-box:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label' => 'Size',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range' => [ 'px' => [ 'min' => 10, 'max' => 200 ], 'em' => [ 'min' => 0.5, 'max' => 12 ] ],
                'default' => [ 'size' => 34, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-icon-box i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_rotate',
            [
                'label' => 'Rotate (deg)',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'deg' ],
                'range' => [ 'deg' => [ 'min' => 0, 'max' => 360 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-icon-box i' => 'transform: rotate({{SIZE}}deg);',
                ],
            ]
        );

        $this->add_control(
            'icon_bg',
            [
                'label' => 'Background',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-icon-box' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_bg_hover',
            [
                'label' => 'Background Hover',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-icon-box:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_padding',
            [
                'label' => 'Padding',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-icon-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_radius',
            [
                'label' => 'Border Radius',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 100 ], '%' => [ 'min' => 0, 'max' => 100 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-icon-box' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $icon   = ! empty( $settings['selected_icon']['value'] ) ? $settings['selected_icon']['value'] : '';
        $has_link = ! empty( $settings['icon_link']['url'] );

        $this->add_render_attribute( 'icon', 'class', 'bdea-icon-box' );
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
        <div class="bdea-icon-wrap">
            <?php if ( $icon ) : ?>
                <?php if ( $has_link ) : ?>
                    <a <?php echo $this->get_render_attribute_string( 'icon' ); ?>>
                        <i class="<?php echo esc_attr( $icon ); ?>"></i>
                    </a>
                <?php else : ?>
                    <span <?php echo $this->get_render_attribute_string( 'icon' ); ?>>
                        <i class="<?php echo esc_attr( $icon ); ?>"></i>
                    </span>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <?php
    }
}