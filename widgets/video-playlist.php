<?php
/**
 * ElementStack Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Video_Playlist_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_video_playlist';
    }

    public function get_title() {
        return 'Video Playlist';
    }

    public function get_icon() {
        return 'eicon-video-playlist';
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
            'bdea_video_playlist_section',
            [
                'label' => __( 'Video Playlist', 'elementstack-elementor-addons' ),
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'vp_title',
            [
                'label' => __( 'Title', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Video Title', 'elementstack-elementor-addons' ),
            ]
        );

        $repeater->add_control(
            'vp_source',
            [
                'label' => __( 'Source', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'youtube',
                'options' => [
                    'youtube' => __( 'YouTube', 'elementstack-elementor-addons' ),
                    'vimeo' => __( 'Vimeo', 'elementstack-elementor-addons' ),
                ],
            ]
        );

        $repeater->add_control(
            'vp_url',
            [
                'label' => __( 'Video URL', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://www.youtube.com/watch?v=VIDEO_ID',
            ]
        );

        $this->add_control(
            'playlist',
            [
                'label' => __( 'Playlist', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [ 'vp_title' => __( 'Intro Video', 'elementstack-elementor-addons' ) ],
                    [ 'vp_title' => __( 'Getting Started', 'elementstack-elementor-addons' ) ],
                    [ 'vp_title' => __( 'Advanced Tips', 'elementstack-elementor-addons' ) ],
                ],
                'title_field' => '{{{ vp_title }}}',
            ]
        );

        $this->add_control(
            'vp_layout',
            [
                'label' => __( 'Layout', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'side',
                'options' => [
                    'side' => __( 'Player Left / List Right', 'elementstack-elementor-addons' ),
                    'top' => __( 'Player Top / List Bottom', 'elementstack-elementor-addons' ),
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_video_playlist_style',
            [
                'label' => __( 'Style', 'elementstack-elementor-addons' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'vp_item_color',
            [
                'label' => __( 'Item Text Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .bdea-playlist-item' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'vp_item_active_bg',
            [
                'label' => __( 'Active Item Background', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#eef1ff',
                'selectors' => [
                    '{{WRAPPER}} .bdea-playlist-item.is-active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'vp_item_active_color',
            [
                'label' => __( 'Active Item Text Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-playlist-item.is-active' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'vp_item_hover_bg',
            [
                'label' => __( 'Hover Background', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-playlist-item:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'vp_item_hover_color',
            [
                'label' => __( 'Hover Text Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-playlist-item:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'vp_item_typography',
                'selector' => '{{WRAPPER}} .bdea-playlist-item',
            ]
        );

        $this->add_responsive_control(
            'vp_item_padding',
            [
                'label' => __( 'Item Padding', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-playlist-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'vp_item_border_radius',
            [
                'label' => __( 'Item Border Radius', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-playlist-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'vp_player_border_radius',
            [
                'label' => __( 'Player Border Radius', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-video-player iframe' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function get_youtube_id( $url ) {
        $patterns = [
            '~youtube\.com/watch\?v=([\w-]+)~',
            '~youtu\.be/([\w-]+)~',
            '~youtube\.com/embed/([\w-]+)~',
            '~youtube\.com/shorts/([\w-]+)~',
        ];
        foreach ( $patterns as $pattern ) {
            if ( preg_match( $pattern, $url, $m ) ) {
                return $m[1];
            }
        }
        return '';
    }

    private function get_vimeo_id( $url ) {
        if ( preg_match( '~vimeo\.com/(?:video/)?(\d+)~', $url, $m ) ) {
            return $m[1];
        }
        return '';
    }

    private function build_embed( $source, $url ) {
        if ( 'youtube' === $source ) {
            $id = $this->get_youtube_id( $url );
            if ( $id ) {
                return 'https://www.youtube-nocookie.com/embed/' . $id;
            }
        } elseif ( 'vimeo' === $source ) {
            $id = $this->get_vimeo_id( $url );
            if ( $id ) {
                return 'https://player.vimeo.com/video/' . $id;
            }
        }
        return '';
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( empty( $settings['playlist'] ) ) {
            return;
        }

        $layout = ( 'top' === $settings['vp_layout'] ) ? ' is-top' : ' is-side';
        $first  = $settings['playlist'][0];
        $first_src = ! empty( $first['vp_url']['url'] ) ? $this->build_embed( $first['vp_source'], $first['vp_url']['url'] ) : '';
        ?>
        <div class="bdea-video-playlist-widget<?php echo esc_attr( $layout ); ?>">
            <div class="bdea-video-player">
                <?php if ( $first_src ) : ?>
                    <iframe src="<?php echo esc_url( $first_src ); ?>" frameborder="0" allowfullscreen
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            title="<?php echo esc_attr( $first['vp_title'] ); ?>"></iframe>
                <?php else : ?>
                    <div class="bdea-loop-grid-empty">No video found.</div>
                <?php endif; ?>
            </div>
            <div class="bdea-video-playlist-list">
                <?php foreach ( $settings['playlist'] as $index => $item ) : ?>
                    <?php
                    $embed = ! empty( $item['vp_url']['url'] ) ? $this->build_embed( $item['vp_source'], $item['vp_url']['url'] ) : '';
                    ?>
                    <div class="bdea-playlist-item<?php echo 0 === $index ? ' is-active' : ''; ?>"
                         data-src="<?php echo esc_attr( $embed ); ?>"
                         role="button" tabindex="0">
                        <span class="bdea-playlist-icon" aria-hidden="true">&#9654;</span>
                        <span class="bdea-playlist-title"><?php echo esc_html( $item['vp_title'] ); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}
