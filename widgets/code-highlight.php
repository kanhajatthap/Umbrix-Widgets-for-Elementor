<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class ELEMENTSKEY_Code_Highlight_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'elementskey_code_highlight';
    }

    public function get_title() {
        return 'Code Highlight';
    }

    public function get_icon() {
        return 'eicon-code';
    }

    public function get_categories() {
        return [ 'elementskey-elements' ];
    }

    public function get_style_depends() {
        return [ 'elementskey-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'elementskey_code_section',
            [
                'label' => __( 'Code', 'elementskey' ),
            ]
        );

        $this->add_control(
            'code_language',
            [
                'label' => __( 'Language', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'javascript',
                'options' => [
                    'html' => __( 'HTML', 'elementskey' ),
                    'css' => __( 'CSS', 'elementskey' ),
                    'javascript' => __( 'JavaScript', 'elementskey' ),
                    'php' => __( 'PHP', 'elementskey' ),
                    'json' => __( 'JSON', 'elementskey' ),
                    'plain' => __( 'Plain Text', 'elementskey' ),
                ],
            ]
        );

        $this->add_control(
            'code_content',
            [
                'label' => __( 'Code', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => 'console.log( "Hello World" );',
                'rows' => 10,
                'placeholder' => 'Paste your code here.',
            ]
        );

        $this->add_control(
            'show_copy_btn',
            [
                'label' => __( 'Show Copy Button', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        // Code Style
        $this->start_controls_section(
            'elementskey_code_style',
            [
                'label' => __( 'Code', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'code_bg_color',
            [
                'label' => __( 'Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-code-highlight' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'code_text_color',
            [
                'label' => __( 'Text Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#f7f7f7',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-code-highlight code' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'code_typography',
                'selector' => '{{WRAPPER}} .elementskey-code-highlight code',
            ]
        );

        $this->add_responsive_control(
            'code_radius',
            [
                'label' => __( 'Border Radius', 'elementskey' ),
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
                    '{{WRAPPER}} .elementskey-code-highlight' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'code_border',
                'selector' => '{{WRAPPER}} .elementskey-code-highlight',
            ]
        );

        $this->add_responsive_control(
            'code_padding',
            [
                'label' => __( 'Padding', 'elementskey' ),
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
                    '{{WRAPPER}} .elementskey-code-highlight' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'code_shadow',
                'selector' => '{{WRAPPER}} .elementskey-code-highlight',
            ]
        );

        $this->end_controls_section();

        // Copy Button Style
        $this->start_controls_section(
            'elementskey_code_copy_style',
            [
                'label' => __( 'Copy Button', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'copy_color',
            [
                'label' => __( 'Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-code-copy' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'copy_bg',
            [
                'label' => __( 'Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-code-copy' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'copy_typography',
                'selector' => '{{WRAPPER}} .elementskey-code-copy',
            ]
        );

        $this->add_responsive_control(
            'copy_radius',
            [
                'label' => __( 'Border Radius', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-code-copy' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'copy_padding',
            [
                'label' => __( 'Padding', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-code-copy' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
        <div class="elementskey-code-highlight" data-language="<?php echo esc_attr( $language ); ?>">
            <?php if ( 'yes' === $settings['show_copy_btn'] ) : ?>
                <span class="elementskey-code-copy"><?php esc_html_e( 'Copy', 'elementskey' ); ?></span>
            <?php endif; ?>
            <pre class="elementskey-code-pre"><code class="language-<?php echo esc_attr( $language ); ?>"><?php echo esc_html( $code ); ?></code></pre>
        </div>
        <?php
    }
}