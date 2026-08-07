<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Facebook_Embed_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_facebook_embed';
    }

    public function get_title() {
        return 'Facebook Embed';
    }

    public function get_icon() {
        return 'eicon-facebook-embed';
    }

    public function get_categories() {
        return [ 'elementstack-elements' ];
    }

    public function get_style_depends() {
        return [ 'bdea-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'bdea_fb_embed_content',
            [
                'label' => 'Facebook Embed',
            ]
        );

        $this->add_control(
            'url',
            [
                'label' => 'Post URL',
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'https://www.facebook.com/example/posts/123456789',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_fb_embed_style',
            [
                'label' => 'Wrap',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'wrap_align',
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
                    '{{WRAPPER}} .bdea-facebook-embed' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'wrap_padding',
            [
                'label' => 'Padding',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-facebook-embed' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'wrap_border',
                'selector' => '{{WRAPPER}} .bdea-facebook-embed',
            ]
        );

        $this->add_responsive_control(
            'wrap_border_radius',
            [
                'label' => 'Border Radius',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-facebook-embed' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $url = ! empty( $settings['url'] ) ? $settings['url'] : get_permalink();

        $this->maybe_render_fb_sdk();
        ?>
        <div class="bdea-facebook-embed">
            <div class="fb-post" data-href="<?php echo esc_attr( esc_url( $url ) ); ?>" data-show-text="true"></div>
        </div>
        <?php
    }

    private function maybe_render_fb_sdk() {
        bdea_maybe_print_fb_sdk( 'v18.0' );
    }
}
