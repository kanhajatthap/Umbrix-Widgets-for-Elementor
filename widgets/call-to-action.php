<?php
/**
 * ElementKey Lite
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class ELEMENTSKEY_Call_To_Action_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'elementskey_call_to_action';
    }

    public function get_title() {
        return 'Call to Action';
    }

    public function get_icon() {
        return 'eicon-call-to-action';
    }

    public function get_categories() {
        return [ 'elementskey-elements' ];
    }

    public function get_style_depends() {
        return [ 'elementskey-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'elementskey_cta_section',
            [
                'label' => __( 'Call to Action', 'elementskey' ),
            ]
        );

        $this->add_control(
            'cta_bg',
            [
                'label' => __( 'Background Image', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
            ]
        );

        $this->add_control(
            'cta_title',
            [
                'label' => __( 'Title', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Ready to Get Started?',
            ]
        );

        $this->add_control(
            'cta_description',
            [
                'label' => __( 'Description', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => 'Join thousands of happy customers today.',
            ]
        );

        $this->add_control(
            'cta_btn_text',
            [
                'label' => __( 'Button Text', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Get Started', 'elementskey' ),
            ]
        );

        $this->add_control(
            'cta_btn_url',
            [
                'label' => __( 'Button Link', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://example.com',
            ]
        );

        $this->add_control(
            'cta_btn2_text',
            [
                'label' => __( 'Second Button Text', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
            ]
        );

        $this->add_control(
            'cta_btn2_url',
            [
                'label' => __( 'Second Button Link', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://example.com',
            ]
        );

        $this->end_controls_section();

        // General Style
        $this->start_controls_section(
            'elementskey_cta_general_style',
            [
                'label' => __( 'General', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'cta_overlay',
            [
                'label' => __( 'Overlay Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'rgba(31, 41, 55, 0.75)',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-cta::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'cta_align',
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
                    '{{WRAPPER}} .elementskey-cta-content' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => __( 'Content Padding', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 60,
                    'right' => 40,
                    'bottom' => 60,
                    'left' => 40,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-cta-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'cta_border',
                'selector' => '{{WRAPPER}} .elementskey-cta',
            ]
        );

        $this->add_responsive_control(
            'cta_border_radius',
            [
                'label' => __( 'Border Radius', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-cta' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'cta_shadow',
                'selector' => '{{WRAPPER}} .elementskey-cta',
            ]
        );

        $this->end_controls_section();

        // Typography
        $this->start_controls_section(
            'elementskey_cta_typo',
            [
                'label' => __( 'Typography', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'cta_title_color',
            [
                'label' => __( 'Title Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-cta-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typo',
                'selector' => '{{WRAPPER}} .elementskey-cta-title',
            ]
        );

        $this->add_control(
            'cta_desc_color',
            [
                'label' => __( 'Description Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#e5e7eb',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-cta-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'desc_typo',
                'selector' => '{{WRAPPER}} .elementskey-cta-description',
            ]
        );

        $this->add_responsive_control(
            'desc_spacing',
            [
                'label' => __( 'Description Spacing', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-cta-description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Button Style
        $this->start_controls_section(
            'elementskey_cta_btn_style',
            [
                'label' => __( 'Button', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'cta_btn_bg',
            [
                'label' => __( 'Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-cta-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_text_color',
            [
                'label' => __( 'Text Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-cta-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_hover_bg',
            [
                'label' => __( 'Hover Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-cta-btn:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_hover_text',
            [
                'label' => __( 'Hover Text Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-cta-btn:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn2_bg',
            [
                'label' => __( 'Secondary Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'transparent',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-cta-btn-secondary' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn2_text_color',
            [
                'label' => __( 'Secondary Text Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-cta-btn-secondary' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn2_border_color',
            [
                'label' => __( 'Secondary Border Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'rgba(255, 255, 255, 0.7)',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-cta-btn-secondary' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'btn2_border_width',
            [
                'label' => __( 'Secondary Border Width', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'default' => [
                    'size' => 1,
                    'unit' => 'px',
                ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 5 ] ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-cta-btn-secondary' => 'border-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'btn2_hover_bg',
            [
                'label' => __( 'Secondary Hover Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'rgba(255, 255, 255, 0.15)',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-cta-btn-secondary:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn2_hover_text',
            [
                'label' => __( 'Secondary Hover Text Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-cta-btn-secondary:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'btn_typo',
                'selector' => '{{WRAPPER}} .elementskey-cta-btn',
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
                    '{{WRAPPER}} .elementskey-cta-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    'top' => 14,
                    'right' => 28,
                    'bottom' => 14,
                    'left' => 28,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-cta-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'btn_spacing',
            [
                'label' => __( 'Button Gap', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 30 ] ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-cta-buttons' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'btn_align',
            [
                'label' => __( 'Button Alignment', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [ 'title' => __( 'Left', 'elementskey' ), 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => __( 'Center', 'elementskey' ), 'icon' => 'eicon-text-align-center' ],
                    'flex-end' => [ 'title' => __( 'Right', 'elementskey' ), 'icon' => 'eicon-text-align-right' ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-cta-buttons' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $bg_url   = ! empty( $settings['cta_bg']['url'] ) ? $settings['cta_bg']['url'] : '';
        $bg_style = $bg_url ? ' style="background-image:url(\'' . esc_url( $bg_url ) . '\');"' : '';
        ?>
        <div class="elementskey-cta-widget">
            <div class="elementskey-cta"<?php echo $bg_style; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Style string built with esc_url(). ?>>
                <div class="elementskey-cta-content">
                    <?php if ( ! empty( $settings['cta_title'] ) ) : ?>
                        <h2 class="elementskey-cta-title"><?php echo esc_html( $settings['cta_title'] ); ?></h2>
                    <?php endif; ?>
                    <?php if ( ! empty( $settings['cta_description'] ) ) : ?>
                        <p class="elementskey-cta-description"><?php echo esc_html( $settings['cta_description'] ); ?></p>
                    <?php endif; ?>
                    <div class="elementskey-cta-buttons">
                        <?php if ( ! empty( $settings['cta_btn_text'] ) ) : ?>
                            <a class="elementskey-cta-btn" href="<?php echo esc_url( ! empty( $settings['cta_btn_url']['url'] ) ? $settings['cta_btn_url']['url'] : '#' ); ?>">
                                <?php echo esc_html( $settings['cta_btn_text'] ); ?>
                            </a>
                        <?php endif; ?>
                        <?php if ( ! empty( $settings['cta_btn2_text'] ) ) : ?>
                            <a class="elementskey-cta-btn elementskey-cta-btn-secondary" href="<?php echo esc_url( ! empty( $settings['cta_btn2_url']['url'] ) ? $settings['cta_btn2_url']['url'] : '#' ); ?>">
                                <?php echo esc_html( $settings['cta_btn2_text'] ); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
