<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class ELEMENTSKEY_Shortcode_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'elementskey_shortcode';
    }

    public function get_title() {
        return 'Shortcode';
    }

    public function get_icon() {
        return 'eicon-shortcode';
    }

    public function get_categories() {
        return [ 'elementskey-elements' ];
    }

    public function get_style_depends() {
        return [ 'elementskey-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'elementskey_shortcode_section',
            [
                'label' => __( 'Shortcode', 'elementskey' ),
            ]
        );

        $this->add_control(
            'shortcode',
            [
                'label' => __( 'Shortcode', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => '[gallery columns="3"]',
                'rows' => 3,
                'description' => 'Paste any shortcode, e.g. [contact-form-7 id="123"] or [gallery columns="3"].',
            ]
        );

        $this->add_control(
            'shortcode_align',
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
                    '{{WRAPPER}} .elementskey-shortcode-widget' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_shortcode_style_section',
            [
                'label' => __( 'Style', 'elementskey' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
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
        <div class="elementskey-shortcode-widget">
            <?php echo do_shortcode( $shortcode ); ?>
        </div>
        <?php
    }
}