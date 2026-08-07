<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Flip_Box_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_flip_box';
    }

    public function get_title() {
        return 'Flip Box';
    }

    public function get_icon() {
        return 'eicon-flip-box';
    }

    public function get_categories() {
        return [ 'elementstack-elements' ];
    }

    public function get_style_depends() {
        return [ 'bdea-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'bdea_flip_box_front',
            [
                'label' => 'Front',
            ]
        );

        $this->add_control(
            'flip_icon',
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
            'flip_front_title',
            [
                'label' => 'Title',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Front Title',
            ]
        );

        $this->add_control(
            'flip_front_text',
            [
                'label' => 'Description',
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => 'Hover or tap to flip the box.',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_flip_box_back',
            [
                'label' => 'Back',
            ]
        );

        $this->add_control(
            'flip_back_title',
            [
                'label' => 'Title',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Back Title',
            ]
        );

        $this->add_control(
            'flip_back_text',
            [
                'label' => 'Description',
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => 'This is the back side content.',
            ]
        );

        $this->add_control(
            'flip_btn_text',
            [
                'label' => 'Button Text',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Learn More',
            ]
        );

        $this->add_control(
            'flip_btn_url',
            [
                'label' => 'Button Link',
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://example.com',
            ]
        );

        $this->end_controls_section();

        // General Style
        $this->start_controls_section(
            'bdea_flip_general_style',
            [
                'label' => 'General',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'flip_height',
            [
                'label' => 'Height',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 200, 'max' => 600 ] ],
                'default' => [ 'size' => 300, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-flip-box-inner' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'flip_border',
                'selector' => '{{WRAPPER}} .bdea-flip-box',
            ]
        );

        $this->add_responsive_control(
            'flip_border_radius',
            [
                'label' => 'Border Radius',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-flip-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'flip_shadow',
                'selector' => '{{WRAPPER}} .bdea-flip-box',
            ]
        );

        $this->end_controls_section();

        // Front Style
        $this->start_controls_section(
            'bdea_flip_front_style',
            [
                'label' => 'Front',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'flip_front_bg',
            [
                'label' => 'Background',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .bdea-flip-face-front' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'flip_front_color',
            [
                'label' => 'Text Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .bdea-flip-face-front' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'front_icon_color',
            [
                'label' => 'Icon Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-flip-face-front .bdea-flip-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'front_icon_size',
            [
                'label' => 'Icon Size',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 16, 'max' => 80 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-flip-face-front .bdea-flip-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'front_title_typo',
                'selector' => '{{WRAPPER}} .bdea-flip-face-front .bdea-flip-title',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'front_text_typo',
                'selector' => '{{WRAPPER}} .bdea-flip-face-front .bdea-flip-text',
            ]
        );

        $this->add_responsive_control(
            'front_padding',
            [
                'label' => 'Padding',
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
                    '{{WRAPPER}} .bdea-flip-face-front' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Back Style
        $this->start_controls_section(
            'bdea_flip_back_style',
            [
                'label' => 'Back',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'flip_back_bg',
            [
                'label' => 'Background',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .bdea-flip-face-back' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'flip_back_color',
            [
                'label' => 'Text Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .bdea-flip-face-back' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'back_title_typo',
                'selector' => '{{WRAPPER}} .bdea-flip-face-back .bdea-flip-title',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'back_text_typo',
                'selector' => '{{WRAPPER}} .bdea-flip-face-back .bdea-flip-text',
            ]
        );

        $this->add_responsive_control(
            'back_padding',
            [
                'label' => 'Padding',
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
                    '{{WRAPPER}} .bdea-flip-face-back' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Back Button Style
        $this->start_controls_section(
            'bdea_flip_btn_style',
            [
                'label' => 'Button',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'btn_bg',
            [
                'label' => 'Background',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-flip-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_color',
            [
                'label' => 'Text Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-flip-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_hover_bg',
            [
                'label' => 'Hover Background',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-flip-btn:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_hover_color',
            [
                'label' => 'Hover Text Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-flip-btn:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'btn_typo',
                'selector' => '{{WRAPPER}} .bdea-flip-btn',
            ]
        );

        $this->add_responsive_control(
            'btn_border_radius',
            [
                'label' => 'Border Radius',
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
                    '{{WRAPPER}} .bdea-flip-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'btn_padding',
            [
                'label' => 'Padding',
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
                    '{{WRAPPER}} .bdea-flip-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'btn_spacing',
            [
                'label' => 'Top Spacing',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-flip-btn' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="bdea-flip-box-widget">
            <div class="bdea-flip-box">
                <div class="bdea-flip-box-inner">
                    <div class="bdea-flip-face bdea-flip-face-front">
                        <div class="bdea-flip-content">
                            <?php if ( ! empty( $settings['flip_icon']['value'] ) ) : ?>
                                <div class="bdea-flip-icon"><i class="<?php echo esc_attr( $settings['flip_icon']['value'] ); ?>"></i></div>
                            <?php endif; ?>
                            <?php if ( ! empty( $settings['flip_front_title'] ) ) : ?>
                                <h3 class="bdea-flip-title"><?php echo esc_html( $settings['flip_front_title'] ); ?></h3>
                            <?php endif; ?>
                            <?php if ( ! empty( $settings['flip_front_text'] ) ) : ?>
                                <p class="bdea-flip-text"><?php echo esc_html( $settings['flip_front_text'] ); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="bdea-flip-face bdea-flip-face-back">
                        <div class="bdea-flip-content">
                            <?php if ( ! empty( $settings['flip_back_title'] ) ) : ?>
                                <h3 class="bdea-flip-title"><?php echo esc_html( $settings['flip_back_title'] ); ?></h3>
                            <?php endif; ?>
                            <?php if ( ! empty( $settings['flip_back_text'] ) ) : ?>
                                <p class="bdea-flip-text"><?php echo esc_html( $settings['flip_back_text'] ); ?></p>
                            <?php endif; ?>
                            <?php if ( ! empty( $settings['flip_btn_text'] ) ) : ?>
                                <a class="bdea-flip-btn" href="<?php echo esc_url( ! empty( $settings['flip_btn_url']['url'] ) ? $settings['flip_btn_url']['url'] : '#' ); ?>">
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