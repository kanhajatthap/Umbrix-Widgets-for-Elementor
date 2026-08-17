<?php
/**
 * ElementStack Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */
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
                'label' => __( 'Facebook Comments', 'elementstack-elementor-addons' ),
            ]
        );

        $this->add_control(
            'url',
            [
                'label' => __( 'Page URL', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'https://www.facebook.com/example',
            ]
        );

        $this->add_control(
            'width',
            [
                'label' => __( 'Width', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 180, 'max' => 750 ] ],
                'default' => [ 'size' => 550, 'unit' => 'px' ],
            ]
        );

        $this->add_control(
            'number_of_posts',
            [
                'label' => __( 'Number of Posts', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 5,
                'min' => 1,
                'max' => 100,
            ]
        );

        $this->add_control(
            'color_scheme',
            [
                'label' => __( 'Color Scheme', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'light',
                'options' => [
                    'light' => __( 'Light', 'elementstack-elementor-addons' ),
                    'dark' => __( 'Dark', 'elementstack-elementor-addons' ),
                ],
            ]
        );

        $this->add_control(
            'order_by',
            [
                'label' => __( 'Order', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'social',
                'options' => [
                    'social' => __( 'Social', 'elementstack-elementor-addons' ),
                    'time' => __( 'Time', 'elementstack-elementor-addons' ),
                    'reverse_time' => __( 'Reverse Time', 'elementstack-elementor-addons' ),
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_fb_comments_style',
            [
                'label' => __( 'Wrap', 'elementstack-elementor-addons' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'wrap_align',
            [
                'label' => __( 'Alignment', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [ 'title' => __( 'Left', 'elementstack-elementor-addons' ), 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => __( 'Center', 'elementstack-elementor-addons' ), 'icon' => 'eicon-text-align-center' ],
                    'right' => [ 'title' => __( 'Right', 'elementstack-elementor-addons' ), 'icon' => 'eicon-text-align-right' ],
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
                'label' => __( 'Padding', 'elementstack-elementor-addons' ),
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
                'label' => __( 'Border Radius', 'elementstack-elementor-addons' ),
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
        bdea_maybe_print_fb_sdk( 'v25.0' );
    }
}
