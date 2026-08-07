<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Code_Highlight_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_code_highlight';
    }

    public function get_title() {
        return 'Code Highlight';
    }

    public function get_icon() {
        return 'eicon-code';
    }

    public function get_categories() {
        return [ 'elementstack-elements' ];
    }

    public function get_style_depends() {
        return [ 'bdea-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'bdea_code_section',
            [
                'label' => 'Code',
            ]
        );

        $this->add_control(
            'code_language',
            [
                'label' => 'Language',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'javascript',
                'options' => [
                    'html' => 'HTML',
                    'css' => 'CSS',
                    'javascript' => 'JavaScript',
                    'php' => 'PHP',
                    'json' => 'JSON',
                    'plain' => 'Plain Text',
                ],
            ]
        );

        $this->add_control(
            'code_content',
            [
                'label' => 'Code',
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => 'console.log( "Hello World" );',
                'rows' => 10,
                'placeholder' => 'Paste your code here.',
            ]
        );

        $this->add_control(
            'show_copy_btn',
            [
                'label' => 'Show Copy Button',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        // Code Style
        $this->start_controls_section(
            'bdea_code_style',
            [
                'label' => 'Code',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'code_bg_color',
            [
                'label' => 'Background',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .bdea-code-highlight' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'code_text_color',
            [
                'label' => 'Text Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#f7f7f7',
                'selectors' => [
                    '{{WRAPPER}} .bdea-code-highlight code' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'code_typography',
                'selector' => '{{WRAPPER}} .bdea-code-highlight code',
            ]
        );

        $this->add_responsive_control(
            'code_radius',
            [
                'label' => 'Border Radius',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 8,
                    'right' => 8,
                    'bottom' => 8,
                    'left' => 8,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-code-highlight' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'code_border',
                'selector' => '{{WRAPPER}} .bdea-code-highlight',
            ]
        );

        $this->add_responsive_control(
            'code_padding',
            [
                'label' => 'Padding',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 20,
                    'right' => 20,
                    'bottom' => 20,
                    'left' => 20,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-code-highlight' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'code_shadow',
                'selector' => '{{WRAPPER}} .bdea-code-highlight',
            ]
        );

        $this->end_controls_section();

        // Copy Button Style
        $this->start_controls_section(
            'bdea_code_copy_style',
            [
                'label' => 'Copy Button',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'copy_color',
            [
                'label' => 'Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-code-copy' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'copy_bg',
            [
                'label' => 'Background',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-code-copy' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'copy_typography',
                'selector' => '{{WRAPPER}} .bdea-code-copy',
            ]
        );

        $this->add_responsive_control(
            'copy_radius',
            [
                'label' => 'Border Radius',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-code-copy' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'copy_padding',
            [
                'label' => 'Padding',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-code-copy' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $language = ! empty( $settings['code_language'] ) ? $settings['code_language'] : 'plain';
        $code     = isset( $settings['code_content'] ) ? $settings['code_content'] : '';
        ?>
        <div class="bdea-code-highlight" data-language="<?php echo esc_attr( $language ); ?>">
            <?php if ( 'yes' === $settings['show_copy_btn'] ) : ?>
                <span class="bdea-code-copy"><?php esc_html_e( 'Copy', 'bdea' ); ?></span>
            <?php endif; ?>
            <pre class="bdea-code-pre"><code class="language-<?php echo esc_attr( $language ); ?>"><?php echo esc_html( $code ); ?></code></pre>
        </div>
        <?php
    }
}