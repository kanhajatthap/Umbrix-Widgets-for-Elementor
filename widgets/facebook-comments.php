<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Facebook_Comments_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_facebook_comments';
    }

    public function get_title() {
        return 'Facebook Comments';
    }

    public function get_icon() {
        return 'eicon-facebook-comments';
    }

    public function get_categories() {
        return [ 'elementstack-elements' ];
    }

    public function get_style_depends() {
        return [ 'bdea-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'bdea_fb_comments_content',
            [
                'label' => 'Facebook Comments',
            ]
        );

        $this->add_control(
            'url',
            [
                'label' => 'Page URL',
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'https://www.facebook.com/example',
            ]
        );

        $this->add_control(
            'width',
            [
                'label' => 'Width',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 180, 'max' => 750 ] ],
                'default' => [ 'size' => 550, 'unit' => 'px' ],
            ]
        );

        $this->add_control(
            'number_of_posts',
            [
                'label' => 'Number of Posts',
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 5,
                'min' => 1,
                'max' => 100,
            ]
        );

        $this->add_control(
            'color_scheme',
            [
                'label' => 'Color Scheme',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'light',
                'options' => [
                    'light' => 'Light',
                    'dark' => 'Dark',
                ],
            ]
        );

        $this->add_control(
            'order_by',
            [
                'label' => 'Order',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'social',
                'options' => [
                    'social' => 'Social',
                    'time' => 'Time',
                    'reverse_time' => 'Reverse Time',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_fb_comments_style',
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
                    '{{WRAPPER}} .bdea-facebook-comments' => 'text-align: {{VALUE}};',
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
                    '{{WRAPPER}} .bdea-facebook-comments' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'wrap_border',
                'selector' => '{{WRAPPER}} .bdea-facebook-comments',
            ]
        );

        $this->add_responsive_control(
            'wrap_border_radius',
            [
                'label' => 'Border Radius',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-facebook-comments' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $url = ! empty( $settings['url'] ) ? $settings['url'] : get_permalink();

        $width    = ! empty( $settings['width']['size'] ) ? (int) $settings['width']['size'] : 550;
        $numposts = ! empty( $settings['number_of_posts'] ) ? absint( $settings['number_of_posts'] ) : 5;
        $scheme   = ! empty( $settings['color_scheme'] ) ? $settings['color_scheme'] : 'light';
        $order    = ! empty( $settings['order_by'] ) ? $settings['order_by'] : 'social';

        $this->maybe_render_fb_sdk();
        ?>
        <div class="bdea-facebook-comments">
            <div class="fb-comments"
                data-href="<?php echo esc_attr( esc_url( $url ) ); ?>"
                data-width="<?php echo esc_attr( $width ); ?>"
                data-numposts="<?php echo esc_attr( $numposts ); ?>"
                data-colorscheme="<?php echo esc_attr( $scheme ); ?>"
                data-order-by="<?php echo esc_attr( $order ); ?>">
            </div>
        </div>
        <?php
    }

    private function maybe_render_fb_sdk() {
        bdea_maybe_print_fb_sdk( 'v18.0' );
    }
}
