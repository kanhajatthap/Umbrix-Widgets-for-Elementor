<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class ELEMENTSKEY_Facebook_Button_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'elementskey_facebook_button';
    }

    public function get_title() {
        return 'Facebook Button';
    }

    public function get_icon() {
        return 'eicon-facebook-like-box';
    }

    public function get_categories() {
        return [ 'elementskey-elements' ];
    }

    public function get_style_depends() {
        return [ 'elementskey-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'elementskey_facebook_button_section',
            [
                'label' => __( 'Facebook Button', 'elementskey' ),
            ]
        );

        $this->add_control(
            'fb_button_url',
            [
                'label' => __( 'Page URL', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://facebook.com/yourpage',
            ]
        );

        $this->add_control(
            'fb_button_layout',
            [
                'label' => __( 'Layout', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'standard',
                'options' => [
                    'standard' => __( 'Standard', 'elementskey' ),
                    'box_count' => __( 'Box Count', 'elementskey' ),
                    'button_count' => __( 'Button Count', 'elementskey' ),
                    'button' => __( 'Button', 'elementskey' ),
                ],
            ]
        );

        $this->add_control(
            'fb_button_action',
            [
                'label' => __( 'Action', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'like',
                'options' => [
                    'like' => __( 'Like', 'elementskey' ),
                    'recommend' => __( 'Recommend', 'elementskey' ),
                ],
            ]
        );

        $this->add_control(
            'fb_button_show_faces',
            [
                'label' => __( 'Show Faces', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_facebook_button_style',
            [
                'label' => __( 'Style', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'fb_button_align',
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
                    '{{WRAPPER}} .elementskey-facebook-button' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'fb_button_padding',
            [
                'label' => __( 'Padding', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-facebook-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'fb_button_border',
                'selector' => '{{WRAPPER}} .elementskey-facebook-button',
            ]
        );

        $this->add_responsive_control(
            'fb_button_border_radius',
            [
                'label' => __( 'Border Radius', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-facebook-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function output_sdk() {
        elementskey_maybe_print_fb_sdk( 'v17.0' );
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $url = ! empty( $settings['fb_button_url']['url'] ) ? $settings['fb_button_url']['url'] : '';

        if ( empty( $url ) ) {
            $url = get_permalink();
        }

        $this->output_sdk();
        ?>
        <div class="elementskey-facebook-button">
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
