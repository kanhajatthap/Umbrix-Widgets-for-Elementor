<?php
/**
 * ElementStack Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Facebook_Page_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_facebook_page';
    }

    public function get_title() {
        return 'Facebook Page';
    }

    public function get_icon() {
        return 'eicon-facebook-page';
    }

    public function get_categories() {
        return [ 'elementstack-elements' ];
    }

    public function get_style_depends() {
        return [ 'bdea-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'bdea_fb_page_content',
            [
                'label' => __( 'Facebook Page', 'elementstack-elementor-addons' ),
            ]
        );

        $this->add_control(
            'url',
            [
                'label' => __( 'Page URL', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'https://www.facebook.com/facebook',
            ]
        );

        $this->add_control(
            'tabs',
            [
                'label' => __( 'Tabs', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'default' => [ 'timeline' ],
                'options' => [
                    'timeline' => __( 'Timeline', 'elementstack-elementor-addons' ),
                    'events' => __( 'Events', 'elementstack-elementor-addons' ),
                    'messages' => __( 'Messages', 'elementstack-elementor-addons' ),
                ],
            ]
        );

        $this->add_control(
            'height',
            [
                'label' => __( 'Height', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 70, 'max' => 1000 ] ],
                'default' => [ 'size' => 500, 'unit' => 'px' ],
            ]
        );

        $this->add_control(
            'small_header',
            [
                'label' => __( 'Small Header', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'no',
            ]
        );

        $this->add_control(
            'hide_cover',
            [
                'label' => __( 'Hide Cover Photo', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'no',
            ]
        );

        $this->add_control(
            'hide_cta',
            [
                'label' => __( 'Hide CTA Button', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'no',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_fb_page_style',
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
                    '{{WRAPPER}} .bdea-facebook-page' => 'text-align: {{VALUE}};',
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
                    '{{WRAPPER}} .bdea-facebook-page' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'wrap_border',
                'selector' => '{{WRAPPER}} .bdea-facebook-page',
            ]
        );

        $this->add_responsive_control(
            'wrap_border_radius',
            [
                'label' => __( 'Border Radius', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-facebook-page' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $url    = ! empty( $settings['url'] ) ? $settings['url'] : get_permalink();
        $height = ! empty( $settings['height']['size'] ) ? (int) $settings['height']['size'] : 500;

        $tabs = ! empty( $settings['tabs'] ) ? (array) $settings['tabs'] : [];

        $tabs_attr    = implode( ', ', $tabs );
        $small_header = ( 'yes' === $settings['small_header'] ) ? 'true' : 'false';
        $hide_cover   = ( 'yes' === $settings['hide_cover'] ) ? 'true' : 'false';
        $hide_cta     = ( 'yes' === $settings['hide_cta'] ) ? 'true' : 'false';

        $this->maybe_render_fb_sdk();
        ?>
        <div class="bdea-facebook-page">
            <div class="fb-page"
                data-href="<?php echo esc_attr( esc_url( $url ) ); ?>"
                data-tabs="<?php echo esc_attr( $tabs_attr ); ?>"
                data-height="<?php echo esc_attr( $height ); ?>"
                data-small-header="<?php echo esc_attr( $small_header ); ?>"
                data-hide-cover="<?php echo esc_attr( $hide_cover ); ?>"
                data-hide-cta="<?php echo esc_attr( $hide_cta ); ?>"
                data-show-facepile="true">
            </div>
        </div>
        <?php
    }

    private function maybe_render_fb_sdk() {
        bdea_maybe_print_fb_sdk( 'v25.0' );
    }
}
