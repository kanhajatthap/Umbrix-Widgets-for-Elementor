<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class ELEMENTSKEY_Lottie_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'elementskey_lottie';
    }

    public function get_title() {
        return 'Lottie';
    }

    public function get_icon() {
        return 'eicon-lottie';
    }

    public function get_categories() {
        return [ 'elementskey-elements' ];
    }

    public function get_style_depends() {
        return [ 'elementskey-content-style' ];
    }

    public function get_script_depends() {
        return [ 'elementskey-content-script' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'elementskey_lottie_section',
            [
                'label' => __( 'Lottie', 'elementskey' ),
            ]
        );

        $this->add_control(
            'lottie_source',
            [
                'label' => __( 'Source', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'url',
                'options' => [
                    'url' => __( 'Animation URL', 'elementskey' ),
                    'json' => __( 'JSON File', 'elementskey' ),
                ],
            ]
        );

        $this->add_control(
            'lottie_url',
            [
                'label' => __( 'Animation URL', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://assets.mixkit.co/.../animation.json',
                'description' => 'Paste a direct link to a .json Lottie animation.',
                'condition' => [ 'lottie_source' => 'url' ],
            ]
        );

        $this->add_control(
            'lottie_json',
            [
                'label' => __( 'JSON File', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'description' => 'Upload your .json Lottie file.',
                'condition' => [ 'lottie_source' => 'json' ],
            ]
        );

        $this->add_control(
            'lottie_loop',
            [
                'label' => __( 'Loop', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'lottie_autoplay',
            [
                'label' => __( 'Autoplay', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'lottie_speed',
            [
                'label' => __( 'Speed', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0.1, 'max' => 3, 'step' => 0.1 ] ],
                'default' => [ 'size' => 1, 'unit' => 'px' ],
            ]
        );

        $this->add_responsive_control(
            'lottie_size',
            [
                'label' => __( 'Size', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [ 'px' => [ 'min' => 50, 'max' => 800 ], '%' => [ 'min' => 10, 'max' => 100 ] ],
                'default' => [ 'size' => 200, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-lottie' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'lottie_align',
            [
                'label' => __( 'Alignment', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [ 'title' => __( 'Left', 'elementskey' ), 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => __( 'Center', 'elementskey' ), 'icon' => 'eicon-text-align-center' ],
                    'right' => [ 'title' => __( 'Right', 'elementskey' ), 'icon' => 'eicon-text-align-right' ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-lottie-widget' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_lottie_style_section',
            [
                'label' => __( 'Style', 'elementskey' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
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
            <div class="elementskey-loop-grid-empty">Add a Lottie animation URL or JSON file.</div>
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
        <div class="elementskey-lottie-widget">
            <div class="elementskey-lottie" data-settings='<?php echo esc_attr( wp_json_encode( $data ) ); ?>'></div>
        </div>
        <?php
    }
}
