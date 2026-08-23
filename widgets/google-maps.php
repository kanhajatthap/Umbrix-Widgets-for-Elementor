<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class ELEMENTSKEY_Google_Maps_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'elementskey_google_maps';
    }

    public function get_title() {
        return 'Google Maps';
    }

    public function get_icon() {
        return 'eicon-google-maps';
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
            'elementskey_maps_content_section',
            [
                'label' => __( 'Map', 'elementskey' ),
            ]
        );

        $this->add_control(
            'map_address',
            [
                'label' => __( 'Address', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Times Square, New York',
                'placeholder' => __( 'Enter address or coordinates', 'elementskey' ),
                'description' => 'Use a full address, place name or "lat,lng" coordinates.',
            ]
        );

        $this->add_control(
            'map_zoom',
            [
                'label' => __( 'Zoom', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ '' ],
                'range' => [ '' => [ 'min' => 1, 'max' => 20, 'step' => 1 ] ],
                'default' => [ 'size' => 12 ],
            ]
        );

        $this->add_responsive_control(
            'map_height',
            [
                'label' => __( 'Height', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'vh' ],
                'range' => [ 'px' => [ 'min' => 100, 'max' => 1000 ], 'vh' => [ 'min' => 10, 'max' => 100 ] ],
                'default' => [ 'size' => 350, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-map-embed' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'map_align',
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
                    '{{WRAPPER}} .elementskey-map-wrap' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'prevent_scroll',
            [
                'label' => __( 'Prevent Scroll While Zooming', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'description' => 'Requires holding Ctrl/Cmd to scroll-zoom over the map.',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_maps_style_section',
            [
                'label' => __( 'Style', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'map_radius',
            [
                'label' => __( 'Border Radius', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-map-embed' => 'border-radius: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementskey-map-embed iframe' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'map_filter',
            [
                'label' => __( 'Map Style Filter', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => __( 'Normal', 'elementskey' ),
                    'grayscale' => __( 'Grayscale', 'elementskey' ),
                    'invert' => __( 'Inverted', 'elementskey' ),
                    'sepia' => __( 'Sepia', 'elementskey' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-map-embed iframe' => 'filter: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $address = ! empty( $settings['map_address'] ) ? $settings['map_address'] : 'New York, NY';
        $zoom    = ! empty( $settings['map_zoom']['size'] ) ? (int) $settings['map_zoom']['size'] : 12;
        $prevent = ( 'yes' === $settings['prevent_scroll'] );

        $query = preg_replace( '/\s+/', ' ', trim( $address ) );
        $map_url = add_query_arg(
            [
                'q' => $query,
                'z' => $zoom,
                'output' => 'embed',
            ],
            'https://maps.google.com/maps'
        );
        ?>
        <div class="elementskey-map-wrap">
            <div class="elementskey-map-embed" data-prevent-scroll="<?php echo esc_attr( $prevent ? 'yes' : 'no' ); ?>">
                <iframe src="<?php echo esc_url( $map_url ); ?>"
                        title="<?php echo esc_attr( $address ); ?>"
                        width="100%" height="100%" style="border:0;"
                        loading="lazy" allowfullscreen></iframe>
            </div>
        </div>
        <?php
    }
}