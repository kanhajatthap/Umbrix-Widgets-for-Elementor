<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class ELEMENTSKEY_Flip_Box_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'elementskey_flip_box';
    }

    public function get_title() {
        return 'Flip Box';
    }

    public function get_icon() {
        return 'eicon-flip-box';
    }

    public function get_categories() {
        return [ 'elementskey-elements' ];
    }

    public function get_style_depends() {
        return [ 'elementskey-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'elementskey_flip_box_front',
            [
                'label' => __( 'Front', 'elementskey' ),
            ]
        );

        $this->add_control(
            'flip_icon',
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
            'flip_front_title',
            [
                'label' => __( 'Title', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Front Title', 'elementskey' ),
            ]
        );

        $this->add_control(
            'flip_front_text',
            [
                'label' => __( 'Description', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => 'Hover or tap to flip the box.',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_flip_box_back',
            [
                'label' => __( 'Back', 'elementskey' ),
            ]
        );

        $this->add_control(
            'flip_back_title',
            [
                'label' => __( 'Title', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Back Title', 'elementskey' ),
            ]
        );

        $this->add_control(
            'flip_back_text',
            [
                'label' => __( 'Description', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => 'This is the back side content.',
            ]
        );

        $this->add_control(
            'flip_btn_text',
            [
                'label' => __( 'Button Text', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Learn More', 'elementskey' ),
            ]
        );

        $this->add_control(
            'flip_btn_url',
            [
                'label' => __( 'Button Link', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://example.com',
            ]
        );

        $this->end_controls_section();

        // General Style
        $this->start_controls_section(
            'elementskey_flip_general_style',
            [
                'label' => __( 'General', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'flip_height',
            [
                'label' => __( 'Height', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 200, 'max' => 600 ] ],
                'default' => [ 'size' => 300, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-flip-box-inner' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'flip_border',
                'selector' => '{{WRAPPER}} .elementskey-flip-box',
            ]
        );

        $this->add_responsive_control(
            'flip_border_radius',
            [
                'label' => __( 'Border Radius', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-flip-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'flip_shadow',
                'selector' => '{{WRAPPER}} .elementskey-flip-box',
            ]
        );

        $this->end_controls_section();

        // Front Style
        $this->start_controls_section(
            'elementskey_flip_front_style',
            [
                'label' => __( 'Front', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'flip_front_bg',
            [
                'label' => __( 'Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-flip-face-front' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'flip_front_color',
            [
                'label' => __( 'Text Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-flip-face-front' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'front_icon_color',
            [
                'label' => __( 'Icon Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-flip-face-front .elementskey-flip-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'front_icon_size',
            [
                'label' => __( 'Icon Size', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 16, 'max' => 80 ] ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-flip-face-front .elementskey-flip-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'front_title_typo',
                'selector' => '{{WRAPPER}} .elementskey-flip-face-front .elementskey-flip-title',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'front_text_typo',
                'selector' => '{{WRAPPER}} .elementskey-flip-face-front .elementskey-flip-text',
            ]
        );

        $this->add_responsive_control(
            'front_padding',
            [
                'label' => __( 'Padding', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 30,
                    'right' => 20,
                    'bottom' => 30,
                    'left' => 20,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-flip-face-front' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Back Style
        $this->start_controls_section(
            'elementskey_flip_back_style',
            [
                'label' => __( 'Back', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'flip_back_bg',
            [
                'label' => __( 'Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-flip-face-back' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'flip_back_color',
            [
                'label' => __( 'Text Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-flip-face-back' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'back_title_typo',
                'selector' => '{{WRAPPER}} .elementskey-flip-face-back .elementskey-flip-title',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'back_text_typo',
                'selector' => '{{WRAPPER}} .elementskey-flip-face-back .elementskey-flip-text',
            ]
        );

        $this->add_responsive_control(
            'back_padding',
            [
                'label' => __( 'Padding', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 30,
                    'right' => 20,
                    'bottom' => 30,
                    'left' => 20,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-flip-face-back' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Back Button Style
        $this->start_controls_section(
            'elementskey_flip_btn_style',
            [
                'label' => __( 'Button', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'btn_bg',
            [
                'label' => __( 'Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-flip-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_color',
            [
                'label' => __( 'Text Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-flip-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_hover_bg',
            [
                'label' => __( 'Hover Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-flip-btn:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_hover_color',
            [
                'label' => __( 'Hover Text Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-flip-btn:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'btn_typo',
                'selector' => '{{WRAPPER}} .elementskey-flip-btn',
            ]
        );

        $this->add_responsive_control(
            'btn_border_radius',
            [
                'label' => __( 'Border Radius', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 6,
                    'right' => 6,
                    'bottom' => 6,
                    'left' => 6,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-flip-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'btn_padding',
            [
                'label' => __( 'Padding', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 10,
                    'right' => 20,
                    'bottom' => 10,
                    'left' => 20,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-flip-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'btn_spacing',
            [
                'label' => __( 'Top Spacing', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-flip-btn' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="elementskey-flip-box-widget">
            <div class="elementskey-flip-box">
                <div class="elementskey-flip-box-inner">
                    <div class="elementskey-flip-face elementskey-flip-face-front">
                        <div class="elementskey-flip-content">
                            <?php if ( ! empty( $settings['flip_icon']['value'] ) ) : ?>
                                <div class="elementskey-flip-icon"><?php \Elementor\Icons_Manager::render_icon( $settings['flip_icon'], [ 'aria-hidden' => 'true' ] ); ?></div>
                            <?php endif; ?>
                            <?php if ( ! empty( $settings['flip_front_title'] ) ) : ?>
                                <h3 class="elementskey-flip-title"><?php echo esc_html( $settings['flip_front_title'] ); ?></h3>
                            <?php endif; ?>
                            <?php if ( ! empty( $settings['flip_front_text'] ) ) : ?>
                                <p class="elementskey-flip-text"><?php echo esc_html( $settings['flip_front_text'] ); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="elementskey-flip-face elementskey-flip-face-back">
                        <div class="elementskey-flip-content">
                            <?php if ( ! empty( $settings['flip_back_title'] ) ) : ?>
                                <h3 class="elementskey-flip-title"><?php echo esc_html( $settings['flip_back_title'] ); ?></h3>
                            <?php endif; ?>
                            <?php if ( ! empty( $settings['flip_back_text'] ) ) : ?>
                                <p class="elementskey-flip-text"><?php echo esc_html( $settings['flip_back_text'] ); ?></p>
                            <?php endif; ?>
                            <?php if ( ! empty( $settings['flip_btn_text'] ) ) : ?>
                                <a class="elementskey-flip-btn" href="<?php echo esc_url( ! empty( $settings['flip_btn_url']['url'] ) ? $settings['flip_btn_url']['url'] : '#' ); ?>">
                                    <?php echo esc_html( $settings['flip_btn_text'] ); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}