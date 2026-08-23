<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class ELEMENTSKEY_Facebook_Comments_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'elementskey_facebook_comments';
    }

    public function get_title() {
        return 'Facebook Comments';
    }

    public function get_icon() {
        return 'eicon-facebook-comments';
    }

    public function get_categories() {
        return [ 'elementskey-elements' ];
    }

    public function get_style_depends() {
        return [ 'elementskey-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'elementskey_fb_comments_content',
            [
                'label' => __( 'Facebook Comments', 'elementskey' ),
            ]
        );

        $this->add_control(
            'url',
            [
                'label' => __( 'Page URL', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'https://www.facebook.com/example',
            ]
        );

        $this->add_control(
            'width',
            [
                'label' => __( 'Width', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 180, 'max' => 750 ] ],
                'default' => [ 'size' => 550, 'unit' => 'px' ],
            ]
        );

        $this->add_control(
            'number_of_posts',
            [
                'label' => __( 'Number of Posts', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 5,
                'min' => 1,
                'max' => 100,
            ]
        );

        $this->add_control(
            'color_scheme',
            [
                'label' => __( 'Color Scheme', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'light',
                'options' => [
                    'light' => __( 'Light', 'elementskey' ),
                    'dark' => __( 'Dark', 'elementskey' ),
                ],
            ]
        );

        $this->add_control(
            'order_by',
            [
                'label' => __( 'Order', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'social',
                'options' => [
                    'social' => __( 'Social', 'elementskey' ),
                    'time' => __( 'Time', 'elementskey' ),
                    'reverse_time' => __( 'Reverse Time', 'elementskey' ),
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_fb_comments_style',
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
                    '{{WRAPPER}} .elementskey-facebook-comments' => 'text-align: {{VALUE}};',
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
                    '{{WRAPPER}} .elementskey-facebook-comments' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'wrap_border',
                'selector' => '{{WRAPPER}} .elementskey-facebook-comments',
            ]
        );

        $this->add_responsive_control(
            'wrap_border_radius',
            [
                'label' => __( 'Border Radius', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-facebook-comments' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
        <div class="elementskey-facebook-comments">
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
        elementskey_maybe_print_fb_sdk( 'v25.0' );
    }
}
