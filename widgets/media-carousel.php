<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class ELEMENTSKEY_Media_Carousel_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'elementskey_media_carousel';
    }

    public function get_title() {
        return 'Media Carousel';
    }

    public function get_icon() {
        return 'eicon-media-carousel';
    }

    public function get_categories() {
        return [ 'elementskey-elements' ];
    }

    public function get_style_depends() {
        return [ 'elementskey-content-style' ];
    }

    public function get_script_depends() {
        return [ 'swiper', 'elementskey-content-script' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'elementskey_media_carousel_section',
            [
                'label' => __( 'Media Carousel', 'elementskey' ),
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'mc_image',
            [
                'label' => __( 'Image', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
            ]
        );

        $repeater->add_control(
            'mc_title',
            [
                'label' => __( 'Title', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Media Title', 'elementskey' ),
            ]
        );

        $repeater->add_control(
            'mc_caption',
            [
                'label' => __( 'Caption', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
            ]
        );

        $this->add_control(
            'media_items',
            [
                'label' => __( 'Items', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [ 'mc_title' => __( 'Image One', 'elementskey' ) ],
                    [ 'mc_title' => __( 'Image Two', 'elementskey' ) ],
                    [ 'mc_title' => __( 'Image Three', 'elementskey' ) ],
                ],
                'title_field' => '{{{ mc_title }}}',
            ]
        );

        $this->add_responsive_control(
            'mc_slides_view',
            [
                'label' => __( 'Slides to Show', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 6,
                'default' => 3,
                'tablet_default' => 2,
                'mobile_default' => 1,
            ]
        );

        $this->add_control(
            'mc_autoplay',
            [
                'label' => __( 'Autoplay', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'mc_dots',
            [
                'label' => __( 'Show Dots', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'mc_arrows',
            [
                'label' => __( 'Show Arrows', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_media_carousel_style',
            [
                'label' => __( 'Style', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'mc_radius',
            [
                'label' => __( 'Border Radius', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
                'default' => [ 'size' => 8, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-media-card' => 'border-radius: {{SIZE}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );

        $this->add_control(
            'mc_caption_color',
            [
                'label' => __( 'Caption Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4b5563',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-media-caption' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_media_carousel_nav_style',
            [
                'label' => __( 'Navigation', 'elementskey' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'nav_position',
            [
                'label' => __( 'Arrow Position', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'inside',
                'options' => [
                    'inside' => __( 'Inside', 'elementskey' ),
                    'outside' => __( 'Outside', 'elementskey' ),
                ],
            ]
        );

        $this->add_responsive_control(
            'mc_arrow_size',
            [
                'label'      => __( 'Arrow Size', 'elementskey' ),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 24, 'max' => 120 ] ],
                'default'    => [
                    'size' => 40,
                    'unit' => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .elementskey-media-carousel-widget .swiper-button-prev, {{WRAPPER}} .elementskey-media-carousel-widget .swiper-button-next, {{WRAPPER}} .elementskey-custom-arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'mc_arrow_icon_size',
            [
                'label'      => __( 'Arrow Icon Size', 'elementskey' ),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 10, 'max' => 60 ] ],
                'default'    => [
                    'size' => 20,
                    'unit' => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .elementskey-media-carousel-widget .elementskey-custom-arrow .elementskey-arrow-icon, {{WRAPPER}} .elementskey-media-carousel-widget .elementskey-custom-arrow .elementskey-arrow-icon i, {{WRAPPER}} .elementskey-media-carousel-widget .elementskey-custom-arrow .elementskey-arrow-icon svg' => 'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'mc_prev_arrow_icon',
            [
                'label'   => __( 'Previous Arrow Icon', 'elementskey' ),
                'type'    => \Elementor\Controls_Manager::ICONS,
                'default' => [ 'value' => 'fas fa-chevron-left', 'library' => 'fa-solid' ],
            ]
        );

        $this->add_control(
            'mc_next_arrow_icon',
            [
                'label'   => __( 'Next Arrow Icon', 'elementskey' ),
                'type'    => \Elementor\Controls_Manager::ICONS,
                'default' => [ 'value' => 'fas fa-chevron-right', 'library' => 'fa-solid' ],
            ]
        );

        $this->add_control(
            'mc_arrow_color',
            [
                'label'     => __( 'Arrow Color', 'elementskey' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-media-carousel-widget .swiper-button-prev, {{WRAPPER}} .elementskey-media-carousel-widget .swiper-button-next' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'mc_arrow_background',
            [
                'label'     => __( 'Arrow Background', 'elementskey' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => 'rgba(0,0,0,0.4)',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-media-carousel-widget .swiper-button-prev, {{WRAPPER}} .elementskey-media-carousel-widget .swiper-button-next' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'mc_arrow_border',
                'selector' => '{{WRAPPER}} .elementskey-media-carousel-widget .elementskey-custom-arrow',
            ]
        );

        $this->add_responsive_control(
            'mc_arrow_radius',
            [
                'label'      => __( 'Arrow Border Radius', 'elementskey' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .elementskey-media-carousel-widget .elementskey-custom-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'mc_arrow_border_color',
            [
                'label'     => __( 'Arrow Border Color', 'elementskey' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-media-carousel-widget .elementskey-custom-arrow' => 'border-style: solid; border-width: 1px; border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'mc_arrow_offset',
            [
                'label'      => __( 'Arrow Offset', 'elementskey' ),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
                'selectors'  => [
                    '{{WRAPPER}} .elementskey-nav-inside .elementskey-media-carousel-widget .swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementskey-nav-inside .elementskey-media-carousel-widget .swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'mc_dots_color',
            [
                'label'     => __( 'Dots Color', 'elementskey' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#d9d9d9',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-media-carousel-widget .swiper-pagination-bullet' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'mc_dots_active_color',
            [
                'label'     => __( 'Active Dot Color', 'elementskey' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-media-carousel-widget .swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( empty( $settings['media_items'] ) ) {
            return;
        }

        $previous_icon = ! empty( $settings['mc_prev_arrow_icon'] ) ? $settings['mc_prev_arrow_icon'] : [ 'value' => 'fas fa-chevron-left', 'library' => 'fa-solid' ];
        $next_icon     = ! empty( $settings['mc_next_arrow_icon'] ) ? $settings['mc_next_arrow_icon'] : [ 'value' => 'fas fa-chevron-right', 'library' => 'fa-solid' ];

        $slides_desktop = ! empty( $settings['mc_slides_view'] ) ? (int) $settings['mc_slides_view'] : 3;
        $slides_tablet  = ! empty( $settings['mc_slides_view_tablet'] ) ? (int) $settings['mc_slides_view_tablet'] : 2;
        $slides_mobile  = ! empty( $settings['mc_slides_view_mobile'] ) ? (int) $settings['mc_slides_view_mobile'] : 1;

        $slider_data = [
            'autoplay' => ( 'yes' === $settings['mc_autoplay'] ),
            'dots'     => ( 'yes' === $settings['mc_dots'] ),
            'arrows'   => ( 'yes' === $settings['mc_arrows'] ),
            'slides'        => max( 1, $slides_desktop ),
            'slidesDesktop' => max( 1, $slides_desktop ),
            'slidesTablet'  => max( 1, $slides_tablet ),
            'slidesMobile'  => max( 1, $slides_mobile ),
        ];
        $nav_class = ( isset( $settings['nav_position'] ) && 'outside' === $settings['nav_position'] ) ? 'elementskey-nav-outside' : 'elementskey-nav-inside';
        ?>
        <div class="elementskey-media-carousel-widget <?php echo esc_attr( $nav_class ); ?>">
            <div class="elementskey-media-carousel swiper" data-settings='<?php echo esc_attr( wp_json_encode( $slider_data ) ); ?>'>
                <div class="swiper-wrapper">
                    <?php foreach ( $settings['media_items'] as $item ) : ?>
                        <div class="swiper-slide">
                            <div class="elementskey-media-card">
                                <?php if ( ! empty( $item['mc_image']['url'] ) ) : ?>
                                    <div class="elementskey-media-image">
                                        <img src="<?php echo esc_url( $item['mc_image']['url'] ); ?>"
                                             alt="<?php echo esc_attr( $item['mc_title'] ); ?>" loading="lazy" />
                                    </div>
                                <?php endif; ?>
                                <?php if ( ! empty( $item['mc_title'] ) || ! empty( $item['mc_caption'] ) ) : ?>
                                    <div class="elementskey-media-caption">
                                        <?php if ( ! empty( $item['mc_title'] ) ) : ?>
                                            <h3><?php echo esc_html( $item['mc_title'] ); ?></h3>
                                        <?php endif; ?>
                                        <?php if ( ! empty( $item['mc_caption'] ) ) : ?>
                                            <p><?php echo esc_html( $item['mc_caption'] ); ?></p>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php if ( $slider_data['dots'] ) : ?>
                <div class="swiper-pagination"></div>
            <?php endif; ?>
            <?php if ( $slider_data['arrows'] ) : ?>
                <button type="button" class="swiper-button-prev elementskey-custom-arrow" aria-label="Previous Slide"><span class="elementskey-arrow-icon"><?php \Elementor\Icons_Manager::render_icon( $previous_icon, [ 'aria-hidden' => 'true' ] ); ?></span></button>
                <button type="button" class="swiper-button-next elementskey-custom-arrow" aria-label="Next Slide"><span class="elementskey-arrow-icon"><?php \Elementor\Icons_Manager::render_icon( $next_icon, [ 'aria-hidden' => 'true' ] ); ?></span></button>
            <?php endif; ?>
        </div>
        <?php
    }
}
