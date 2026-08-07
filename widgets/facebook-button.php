<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Facebook_Button_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_facebook_button';
    }

    public function get_title() {
        return 'Facebook Button';
    }

    public function get_icon() {
        return 'eicon-facebook-like-box';
    }

    public function get_categories() {
        return [ 'elementstack-elements' ];
    }

    public function get_style_depends() {
        return [ 'bdea-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'bdea_facebook_button_section',
            [
                'label' => 'Facebook Button',
            ]
        );

        $this->add_control(
            'fb_button_url',
            [
                'label' => 'Page URL',
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://facebook.com/yourpage',
            ]
        );

        $this->add_control(
            'fb_button_layout',
            [
                'label' => 'Layout',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'standard',
                'options' => [
                    'standard' => 'Standard',
                    'box_count' => 'Box Count',
                    'button_count' => 'Button Count',
                    'button' => 'Button',
                ],
            ]
        );

        $this->add_control(
            'fb_button_action',
            [
                'label' => 'Action',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'like',
                'options' => [
                    'like' => 'Like',
                    'recommend' => 'Recommend',
                ],
            ]
        );

        $this->add_control(
            'fb_button_show_faces',
            [
                'label' => 'Show Faces',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_facebook_button_style',
            [
                'label' => 'Style',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'fb_button_align',
            [
                'label' => 'Alignment',
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [ 'title' => 'Left', 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => 'Center', 'icon' => 'eicon-text-align-center' ],
                    'right' => [ 'title' => 'Right', 'icon' => 'eicon-text-align-right' ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .bdea-facebook-button' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'fb_button_padding',
            [
                'label' => 'Padding',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-facebook-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'fb_button_border',
                'selector' => '{{WRAPPER}} .bdea-facebook-button',
            ]
        );

        $this->add_responsive_control(
            'fb_button_border_radius',
            [
                'label' => 'Border Radius',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-facebook-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function output_sdk() {
        bdea_maybe_print_fb_sdk( 'v17.0' );
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $url = ! empty( $settings['fb_button_url']['url'] ) ? $settings['fb_button_url']['url'] : '';

        if ( empty( $url ) ) {
            $url = get_permalink();
        }

        $this->output_sdk();
        ?>
        <div class="bdea-facebook-button">
            <div class="fb-like"
                 data-href="<?php echo esc_url( $url ); ?>"
                 data-layout="<?php echo esc_attr( $settings['fb_button_layout'] ); ?>"
                 data-action="<?php echo esc_attr( $settings['fb_button_action'] ); ?>"
                 data-show-faces="<?php echo 'yes' === $settings['fb_button_show_faces'] ? 'true' : 'false'; ?>"
                 data-share="true"></div>
        </div>
        <?php
    }
}
