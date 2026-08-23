<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class ELEMENTSKEY_Video_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'elementskey_video';
    }

    public function get_title() {
        return 'Video';
    }

    public function get_icon() {
        return 'eicon-video-player';
    }

    public function get_categories() {
        return [ 'elementskey-elements' ];
    }

    public function get_style_depends() {
        return [ 'elementskey-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'elementskey_video_section',
            [
                'label' => __( 'Video', 'elementskey' ),
            ]
        );

        $this->add_control(
            'video_source',
            [
                'label' => __( 'Source', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'youtube',
                'options' => [
                    'youtube' => __( 'YouTube', 'elementskey' ),
                    'vimeo' => __( 'Vimeo', 'elementskey' ),
                    'self_hosted' => __( 'Self Hosted', 'elementskey' ),
                ],
            ]
        );

        $this->add_control(
            'youtube_url',
            [
                'label' => __( 'YouTube URL', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://www.youtube.com/watch?v=VIDEO_ID',
                'description' => 'Paste your YouTube video URL.',
                'condition' => [ 'video_source' => 'youtube' ],
            ]
        );

        $this->add_control(
            'vimeo_url',
            [
                'label' => __( 'Vimeo URL', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://vimeo.com/VIDEO_ID',
                'description' => 'Paste your Vimeo video URL.',
                'condition' => [ 'video_source' => 'vimeo' ],
            ]
        );

        $this->add_control(
            'self_url',
            [
                'label' => __( 'Video File URL (mp4 / webm / ogg)', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://example.com/video.mp4',
                'condition' => [ 'video_source' => 'self_hosted' ],
            ]
        );

        $this->add_control(
            'poster',
            [
                'label' => __( 'Poster Image', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'condition' => [ 'video_source' => 'self_hosted' ],
            ]
        );

        $this->add_control(
            'aspect_ratio',
            [
                'label' => __( 'Aspect Ratio', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '16-9',
                'options' => [
                    '16-9' => '16:9',
                    '4-3' => '4:3',
                    '3-2' => '3:2',
                    'custom' => __( 'Custom Height', 'elementskey' ),
                ],
            ]
        );

        $this->add_control(
            'custom_height',
            [
                'label' => __( 'Height', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 120, 'max' => 900 ] ],
                'default' => [ 'size' => 420, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-video-wrapper' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [ 'aspect_ratio' => 'custom' ],
            ]
        );

        $this->end_controls_section();

        // Player Settings
        $this->start_controls_section(
            'elementskey_video_settings_section',
            [
                'label' => __( 'Player Settings', 'elementskey' ),
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label' => __( 'Autoplay', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $this->add_control(
            'mute',
            [
                'label' => __( 'Mute', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $this->add_control(
            'loop',
            [
                'label' => __( 'Loop', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $this->add_control(
            'show_controls',
            [
                'label' => __( 'Show Player Controls', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        // Style
        $this->start_controls_section(
            'elementskey_video_style_section',
            [
                'label' => __( 'Style', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'video_width',
            [
                'label' => __( 'Width', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [ 'px' => [ 'min' => 100, 'max' => 1200 ], '%' => [ 'min' => 10, 'max' => 100 ] ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-video-wrapper' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'video_spacing',
            [
                'label' => __( 'Spacing', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-video-widget' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'video_radius',
            [
                'label' => __( 'Border Radius', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-video-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .elementskey-video-wrapper iframe, {{WRAPPER}} .elementskey-video-wrapper video' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'video_border',
                'selector' => '{{WRAPPER}} .elementskey-video-wrapper',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'video_shadow',
                'selector' => '{{WRAPPER}} .elementskey-video-wrapper',
            ]
        );

        $this->add_responsive_control(
            'video_align',
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
                    '{{WRAPPER}}' => 'text-align: {{VALUE}};',
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

    protected function render() {
        $settings = $this->get_settings_for_display();
        $source   = $settings['video_source'];

        $autoplay   = ( 'yes' === $settings['autoplay'] );
        $mute       = ( 'yes' === $settings['mute'] );
        $loop       = ( 'yes' === $settings['loop'] );
        $controls   = ( 'yes' === $settings['show_controls'] );
        $ratio      = ! empty( $settings['aspect_ratio'] ) ? $settings['aspect_ratio'] : '16-9';

        $ratio_class = 'elementskey-video-ratio-16-9';
        if ( '4-3' === $ratio ) { $ratio_class = 'elementskey-video-ratio-4-3'; }
        elseif ( '3-2' === $ratio ) { $ratio_class = 'elementskey-video-ratio-3-2'; }
        elseif ( 'custom' === $ratio ) { $ratio_class = 'elementskey-video-ratio-custom'; }
        ?>
        <div class="elementskey-video-widget">
            <div class="elementskey-video-wrapper <?php echo esc_attr( $ratio_class ); ?>">
                <?php if ( 'youtube' === $source ) : ?>
                    <?php
                    $youtube_url = ! empty( $settings['youtube_url']['url'] ) ? $settings['youtube_url']['url'] : '';
                    $video_id    = $this->get_youtube_id( $youtube_url );
                    if ( $video_id ) :
                        $params = [
                            'playsinline' => 1,
                        ];
                        if ( $autoplay ) { $params['autoplay'] = 1; }
                        if ( $mute ) { $params['mute'] = 1; }
                        if ( $loop ) { $params['loop'] = 1; }
                        if ( ! $controls ) { $params['controls'] = 0; }
                        ?>
                        <iframe src="https://www.youtube-nocookie.com/embed/<?php echo esc_attr( $video_id ); ?>?<?php echo http_build_query( $params ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Params are static integers. ?>"
                                title="YouTube video player" frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen></iframe>
                    <?php else : ?>
                        <div class="elementskey-loop-grid-empty"><?php esc_html_e( 'Invalid YouTube URL.', 'elementskey' ); ?></div>
                    <?php endif; ?>
                <?php elseif ( 'vimeo' === $source ) : ?>
                    <?php
                    $vimeo_url = ! empty( $settings['vimeo_url']['url'] ) ? $settings['vimeo_url']['url'] : '';
                    $video_id  = $this->get_vimeo_id( $vimeo_url );
                    if ( $video_id ) :
                        $params = [];
                        if ( $autoplay ) { $params['autoplay'] = 1; }
                        if ( $mute ) { $params['muted'] = 1; }
                        if ( $loop ) { $params['loop'] = 1; }
                        if ( ! $controls ) { $params['controls'] = 0; }
                        ?>
                        <iframe src="https://player.vimeo.com/video/<?php echo esc_attr( $video_id ); ?><?php echo $params ? '?' . http_build_query( $params ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Params are static integers. ?>"
                                frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                    <?php else : ?>
                        <div class="elementskey-loop-grid-empty"><?php esc_html_e( 'Invalid Vimeo URL.', 'elementskey' ); ?></div>
                    <?php endif; ?>
                <?php elseif ( 'self_hosted' === $source ) : ?>
                    <?php
                    $video_url = ! empty( $settings['self_url']['url'] ) ? $settings['self_url']['url'] : '';
                    if ( $video_url ) :
                        $attrs = ' playsinline';
                        if ( $autoplay ) { $attrs .= ' autoplay'; }
                        if ( $mute ) { $attrs .= ' muted'; }
                        if ( $loop ) { $attrs .= ' loop'; }
                        if ( $controls ) { $attrs .= ' controls'; }
                        $poster = ! empty( $settings['poster']['url'] ) ? $settings['poster']['url'] : '';
                        ?>
                        <video<?php echo $attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static attribute tokens. ?><?php echo $poster ? ' poster="' . esc_attr( $poster ) . '"' : ''; ?>>
                            <source src="<?php echo esc_url( $video_url ); ?>" type="video/mp4">
                            <?php esc_html_e( 'Your browser does not support the video tag.', 'elementskey' ); ?>
                        </video>
                    <?php else : ?>
                        <div class="elementskey-loop-grid-empty"><?php esc_html_e( 'No video file selected.', 'elementskey' ); ?></div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}