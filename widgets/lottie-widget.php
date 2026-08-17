<?php
/**
 * ElementStack Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Lottie_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_lottie';
    }

    public function get_title() {
        return 'Lottie';
    }

    public function get_icon() {
        return 'eicon-lottie';
    }

    public function get_categories() {
        return [ 'elementstack-elements' ];
    }

    public function get_style_depends() {
        return [ 'bdea-content-style' ];
    }

    public function get_script_depends() {
        return [ 'bdea-content-script' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'bdea_lottie_section',
            [
                'label' => __( 'Lottie', 'elementstack-elementor-addons' ),
            ]
        );

        $this->add_control(
            'lottie_source',
            [
                'label' => __( 'Source', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'url',
                'options' => [
                    'url' => __( 'Animation URL', 'elementstack-elementor-addons' ),
                    'json' => __( 'JSON File', 'elementstack-elementor-addons' ),
                ],
            ]
        );

        $this->add_control(
            'lottie_url',
            [
                'label' => __( 'Animation URL', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://assets.mixkit.co/.../animation.json',
                'description' => 'Paste a direct link to a .json Lottie animation.',
                'condition' => [ 'lottie_source' => 'url' ],
            ]
        );

        $this->add_control(
            'lottie_json',
            [
                'label' => __( 'JSON File', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'description' => 'Upload your .json Lottie file.',
                'condition' => [ 'lottie_source' => 'json' ],
            ]
        );

        $this->add_control(
            'lottie_loop',
            [
                'label' => __( 'Loop', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'lottie_autoplay',
            [
                'label' => __( 'Autoplay', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'lottie_speed',
            [
                'label' => __( 'Speed', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0.1, 'max' => 3, 'step' => 0.1 ] ],
                'default' => [ 'size' => 1, 'unit' => 'px' ],
            ]
        );

        $this->add_responsive_control(
            'lottie_size',
            [
                'label' => __( 'Size', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [ 'px' => [ 'min' => 50, 'max' => 800 ], '%' => [ 'min' => 10, 'max' => 100 ] ],
                'default' => [ 'size' => 200, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-lottie' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'lottie_align',
            [
                'label' => __( 'Alignment', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [ 'title' => __( 'Left', 'elementstack-elementor-addons' ), 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => __( 'Center', 'elementstack-elementor-addons' ), 'icon' => 'eicon-text-align-center' ],
                    'right' => [ 'title' => __( 'Right', 'elementstack-elementor-addons' ), 'icon' => 'eicon-text-align-right' ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .bdea-lottie-widget' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $source = ! empty( $settings['lottie_source'] ) ? $settings['lottie_source'] : 'url';

        if ( 'url' === $source ) {
            $src = ! empty( $settings['lottie_url']['url'] ) ? $settings['lottie_url']['url'] : '';
        } else {
            $src = ! empty( $settings['lottie_json']['url'] ) ? $settings['lottie_json']['url'] : '';
        }

        if ( empty( $src ) ) {
            ?>
            <div class="bdea-loop-grid-empty">Add a Lottie animation URL or JSON file.</div>
            <?php
            return;
        }

        $data = [
            'src'      => $src,
            'loop'     => ( 'yes' === $settings['lottie_loop'] ),
            'autoplay' => ( 'yes' === $settings['lottie_autoplay'] ),
            'speed'    => ! empty( $settings['lottie_speed']['size'] ) ? (float) $settings['lottie_speed']['size'] : 1,
        ];
        ?>
        <div class="bdea-lottie-widget">
            <div class="bdea-lottie" data-settings='<?php echo esc_attr( wp_json_encode( $data ) ); ?>'></div>
        </div>
        <?php
    }
}
