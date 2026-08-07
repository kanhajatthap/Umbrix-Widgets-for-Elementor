<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Shortcode_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_shortcode';
    }

    public function get_title() {
        return 'Shortcode';
    }

    public function get_icon() {
        return 'eicon-shortcode';
    }

    public function get_categories() {
        return [ 'elementstack-elements' ];
    }

    public function get_style_depends() {
        return [ 'bdea-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'bdea_shortcode_section',
            [
                'label' => 'Shortcode',
            ]
        );

        $this->add_control(
            'shortcode',
            [
                'label' => 'Shortcode',
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => '[gallery columns="3"]',
                'rows' => 3,
                'description' => 'Paste any shortcode, e.g. [contact-form-7 id="123"] or [gallery columns="3"].',
            ]
        );

        $this->add_control(
            'shortcode_align',
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
                    '{{WRAPPER}} .bdea-shortcode-widget' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $shortcode = ! empty( $settings['shortcode'] ) ? $settings['shortcode'] : '';
        if ( empty( trim( $shortcode ) ) ) {
            return;
        }
        ?>
        <div class="bdea-shortcode-widget">
            <?php echo do_shortcode( $shortcode ); ?>
        </div>
        <?php
    }
}