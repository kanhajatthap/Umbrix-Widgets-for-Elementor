<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class ELEMENTSKEY_Facebook_Embed_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'elementskey_facebook_embed';
    }

    public function get_title() {
        return 'Facebook Embed';
    }

    public function get_icon() {
        return 'eicon-facebook-embed';
    }

    public function get_categories() {
        return [ 'elementskey-elements' ];
    }

    public function get_style_depends() {
        return [ 'elementskey-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'elementskey_fb_embed_content',
            [
                'label' => __( 'Facebook Embed', 'elementskey' ),
            ]
        );

        $this->add_control(
            'url',
            [
                'label' => __( 'Post URL', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'https://www.facebook.com/example/posts/123456789',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_fb_embed_style',
            [
                'label' => __( 'Wrap', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'wrap_align',
            [
                'label' => __( 'Alignment', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [ 'title' => __( 'Left', 'elementskey' ), 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => __( 'Center', 'elementskey' ), 'icon' => 'eicon-text-align-center' ],
                    'right' => [ 'title' => __( 'Right', 'elementskey' ), 'icon' => 'eicon-text-align-right' ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-facebook-embed' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'wrap_padding',
            [
                'label' => __( 'Padding', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-facebook-embed' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'wrap_border',
                'selector' => '{{WRAPPER}} .elementskey-facebook-embed',
            ]
        );

        $this->add_responsive_control(
            'wrap_border_radius',
            [
                'label' => __( 'Border Radius', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-facebook-embed' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
        <div class="elementskey-facebook-embed">
            <div class="fb-post" data-href="<?php echo esc_attr( esc_url( $url ) ); ?>" data-show-text="true"></div>
        </div>
        <?php
    }

    private function maybe_render_fb_sdk() {
        elementskey_maybe_print_fb_sdk( 'v25.0' );
    }
}
