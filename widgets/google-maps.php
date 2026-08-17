<?php
/**
 * ElementStack Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Google_Maps_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_google_maps';
    }

    public function get_title() {
        return 'Google Maps';
    }

    public function get_icon() {
        return 'eicon-google-maps';
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
            'bdea_maps_content_section',
            [
                'label' => __( 'Map', 'elementstack-elementor-addons' ),
            ]
        );

        $this->add_control(
            'map_address',
            [
                'label' => __( 'Address', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Times Square, New York',
                'placeholder' => __( 'Enter address or coordinates', 'elementstack-elementor-addons' ),
                'description' => 'Use a full address, place name or "lat,lng" coordinates.',
            ]
        );

        $this->add_control(
            'map_zoom',
            [
                'label' => __( 'Zoom', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ '' ],
                'range' => [ '' => [ 'min' => 1, 'max' => 20, 'step' => 1 ] ],
                'default' => [ 'size' => 12 ],
            ]
        );

        $this->add_responsive_control(
            'map_height',
            [
                'label' => __( 'Height', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'vh' ],
                'range' => [ 'px' => [ 'min' => 100, 'max' => 1000 ], 'vh' => [ 'min' => 10, 'max' => 100 ] ],
                'default' => [ 'size' => 350, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-map-embed' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'map_align',
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
                    '{{WRAPPER}} .bdea-map-wrap' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'prevent_scroll',
            [
                'label' => __( 'Prevent Scroll While Zooming', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'description' => 'Requires holding Ctrl/Cmd to scroll-zoom over the map.',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_maps_style_section',
            [
                'label' => __( 'Style', 'elementstack-elementor-addons' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'map_radius',
            [
                'label' => __( 'Border Radius', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-map-embed' => 'border-radius: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .bdea-map-embed iframe' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'map_filter',
            [
                'label' => __( 'Map Style Filter', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => __( 'Normal', 'elementstack-elementor-addons' ),
                    'grayscale' => __( 'Grayscale', 'elementstack-elementor-addons' ),
                    'invert' => __( 'Inverted', 'elementstack-elementor-addons' ),
                    'sepia' => __( 'Sepia', 'elementstack-elementor-addons' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-map-embed iframe' => 'filter: {{VALUE}};',
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
        <div class="bdea-map-wrap">
            <div class="bdea-map-embed" data-prevent-scroll="<?php echo esc_attr( $prevent ? 'yes' : 'no' ); ?>">
                <iframe src="<?php echo esc_url( $map_url ); ?>"
                        title="<?php echo esc_attr( $address ); ?>"
                        width="100%" height="100%" style="border:0;"
                        loading="lazy" allowfullscreen></iframe>
            </div>
        </div>
        <?php
    }
}