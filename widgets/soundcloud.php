<?php
/**
 * ElementStack Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Sound_Cloud_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_soundcloud';
    }

    public function get_title() {
        return 'SoundCloud';
    }

    public function get_icon() {
        return 'eicon-cloud-music';
    }

    public function get_categories() {
        return [ 'elementstack-elements' ];
    }

    public function get_style_depends() {
        return [ 'bdea-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'bdea_soundcloud_section',
            [
                'label' => __( 'SoundCloud', 'elementstack-elementor-addons' ),
            ]
        );

        $this->add_control(
            'soundcloud_url',
            [
                'label' => __( 'Track URL', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://soundcloud.com/artist/track',
                'default' => [
                    'url' => 'https://soundcloud.com/simplesimon-fr/let-me-go',
                ],
            ]
        );

        $this->add_control(
            'sc_visual',
            [
                'label' => __( 'Visual Player', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'description' => 'Show the large visual player with artwork.',
            ]
        );

        $this->add_control(
            'sc_autoplay',
            [
                'label' => __( 'Autoplay', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $this->add_control(
            'sc_comments',
            [
                'label' => __( 'Show Comments', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_responsive_control(
            'sc_height',
            [
                'label' => __( 'Height', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 100, 'max' => 800 ] ],
                'default' => [ 'size' => 200, 'unit' => 'px' ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_soundcloud_style',
            [
                'label' => __( 'Style', 'elementstack-elementor-addons' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'sc_align',
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
                    '{{WRAPPER}} .bdea-soundcloud-widget' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'sc_max_width',
            [
                'label' => __( 'Max Width', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [ 'px' => [ 'min' => 200, 'max' => 1600 ], '%' => [ 'min' => 20, 'max' => 100 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-soundcloud-embed' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'sc_radius',
            [
                'label' => __( 'Border Radius', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 40 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-soundcloud-embed iframe' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $track_url = ! empty( $settings['soundcloud_url']['url'] ) ? $settings['soundcloud_url']['url'] : '';
        if ( empty( $track_url ) || false === strpos( $track_url, 'soundcloud.com' ) ) {
            ?>
            <div class="bdea-loop-grid-empty">Invalid SoundCloud URL.</div>
            <?php
            return;
        }

        $visual   = ( 'yes' === $settings['sc_visual'] );
        $autoplay = ( 'yes' === $settings['sc_autoplay'] );
        $comments = ( 'yes' === $settings['sc_comments'] );
        $height   = ! empty( $settings['sc_height']['size'] ) ? (int) $settings['sc_height']['size'] : ( $visual ? 450 : 200 );

        $embed_url = add_query_arg(
            [
                'url' => rawurlencode( $track_url ),
                'visual' => $visual ? 'true' : 'false',
                'auto_play' => $autoplay ? 'true' : 'false',
                'show_comments' => $comments ? 'true' : 'false',
                'color' => '#4361ee',
            ],
            'https://w.soundcloud.com/player/'
        );

        $embed_classes = [ 'bdea-soundcloud-embed' ];
        if ( $visual ) {
            $embed_classes[] = 'is-visual';
        }
        ?>
        <div class="bdea-soundcloud-widget">
            <div class="<?php echo esc_attr( implode( ' ', $embed_classes ) ); ?>">
                <iframe src="<?php echo esc_url( $embed_url ); ?>"
                        width="100%"
                        height="<?php echo esc_attr( $height ); ?>"
                        scrolling="no"
                        frameborder="no"
                        allow="autoplay"
                        title="SoundCloud player"
                        loading="lazy"></iframe>
            </div>
        </div>
        <?php
    }
}