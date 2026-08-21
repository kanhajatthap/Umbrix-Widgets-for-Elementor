<?php
/**
 * ElementKey Lite
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Search_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_search';
    }

    public function get_title() {
        return 'Search';
    }

    public function get_icon() {
        return 'eicon-search';
    }

    public function get_categories() {
        return [ 'elementkey-lite-elements' ];
    }

    public function get_style_depends() {
        return [ 'bdea-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'bdea_search_section',
            [
                'label' => __( 'Search', 'elementkey-lite' ),
            ]
        );

        $this->add_control(
            'search_placeholder',
            [
                'label' => __( 'Placeholder', 'elementkey-lite' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Search...',
            ]
        );

        $this->add_control(
            'search_button_text',
            [
                'label' => __( 'Button Text', 'elementkey-lite' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Search', 'elementkey-lite' ),
            ]
        );

        $this->add_control(
            'search_show_icon',
            [
                'label' => __( 'Show Icon', 'elementkey-lite' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        // Input Style
        $this->start_controls_section(
            'bdea_search_input_style',
            [
                'label' => __( 'Input', 'elementkey-lite' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'input_typography',
                'selector' => '{{WRAPPER}} .bdea-search-field',
            ]
        );

        $this->add_control(
            'input_text_color',
            [
                'label' => __( 'Text Color', 'elementkey-lite' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-search-field' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_placeholder_color',
            [
                'label' => __( 'Placeholder Color', 'elementkey-lite' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-search-field::placeholder' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_bg_color',
            [
                'label' => __( 'Background', 'elementkey-lite' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-search-field' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_border_color',
            [
                'label' => __( 'Border Color', 'elementkey-lite' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#d1d5db',
                'selectors' => [
                    '{{WRAPPER}} .bdea-search-field' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_focus_border_color',
            [
                'label' => __( 'Focus Border Color', 'elementkey-lite' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .bdea-search-field:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'input_border_width',
            [
                'label' => __( 'Border Width', 'elementkey-lite' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 10 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-search-field' => 'border-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'input_border_radius',
            [
                'label' => __( 'Border Radius', 'elementkey-lite' ),
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
                    '{{WRAPPER}} .bdea-search-field' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'input_padding',
            [
                'label' => __( 'Padding', 'elementkey-lite' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 12,
                    'right' => 16,
                    'bottom' => 12,
                    'left' => 16,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-search-field' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Button Style
        $this->start_controls_section(
            'bdea_search_btn_style',
            [
                'label' => __( 'Button', 'elementkey-lite' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'btn_typography',
                'selector' => '{{WRAPPER}} .bdea-search-btn',
            ]
        );

        $this->add_control(
            'search_btn_bg',
            [
                'label' => __( 'Background', 'elementkey-lite' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .bdea-search-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_hover_bg',
            [
                'label' => __( 'Hover Background', 'elementkey-lite' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-search-btn:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'search_btn_color',
            [
                'label' => __( 'Text Color', 'elementkey-lite' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .bdea-search-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_hover_text',
            [
                'label' => __( 'Hover Text Color', 'elementkey-lite' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-search-btn:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'btn_border_radius',
            [
                'label' => __( 'Border Radius', 'elementkey-lite' ),
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
                    '{{WRAPPER}} .bdea-search-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'btn_padding',
            [
                'label' => __( 'Padding', 'elementkey-lite' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 12,
                    'right' => 24,
                    'bottom' => 12,
                    'left' => 24,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-search-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Form Style
        $this->start_controls_section(
            'bdea_search_form_style',
            [
                'label' => __( 'Form', 'elementkey-lite' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'form_align',
            [
                'label' => __( 'Alignment', 'elementkey-lite' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [ 'title' => __( 'Left', 'elementkey-lite' ), 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => __( 'Center', 'elementkey-lite' ), 'icon' => 'eicon-text-align-center' ],
                    'right' => [ 'title' => __( 'Right', 'elementkey-lite' ), 'icon' => 'eicon-text-align-right' ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .bdea-search-widget' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'form_gap',
            [
                'label' => __( 'Input/Button Gap', 'elementkey-lite' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-search-wrap' => 'display: flex; gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $placeholder = ! empty( $settings['search_placeholder'] ) ? $settings['search_placeholder'] : 'Search...';
        $btn_text    = ! empty( $settings['search_button_text'] ) ? $settings['search_button_text'] : 'Search';
        ?>
        <div class="bdea-search-widget">
            <form role="search" method="get" class="bdea-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                <div class="bdea-search-wrap">
                    <input type="search"
                           class="bdea-search-field"
                           placeholder="<?php echo esc_attr( $placeholder ); ?>"
                           value="<?php echo esc_attr( get_search_query() ); ?>"
                           name="s" />
                    <button type="submit" class="bdea-search-btn">
                        <?php if ( 'yes' === $settings['search_show_icon'] ) : ?>
                            <i class="fas fa-search" aria-hidden="true"></i>
                        <?php endif; ?>
                        <span><?php echo esc_html( $btn_text ); ?></span>
                    </button>
                </div>
            </form>
        </div>
        <?php
    }
}
