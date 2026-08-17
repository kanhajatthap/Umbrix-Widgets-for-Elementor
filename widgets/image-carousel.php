<?php
/**
 * ElementStack Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Image_Carousel_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_image_carousel';
    }

    public function get_title() {
        return 'Image Carousel';
    }

    public function get_icon() {
        return 'eicon-slider-push';
    }

    public function get_categories() {
        return [ 'elementstack-elements' ];
    }

    public function get_style_depends() {
        return [ 'swiper', 'bdea-style', 'bdea-content-style' ];
    }

    public function get_script_depends() {
        return [ 'swiper', 'bdea-carousel-script' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'bdea_image_carousel_section',
            [
                'label' => __( 'Images', 'elementstack-elementor-addons' ),
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'carousel_image',
            [
                'label' => __( 'Image', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'carousel_caption',
            [
                'label' => __( 'Caption', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
            ]
        );

        $repeater->add_control(
            'carousel_link',
            [
                'label' => __( 'Link', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://example.com',
            ]
        );

        $this->add_control(
            'carousel_images',
            [
                'label' => __( 'Images', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [ 'carousel_caption' => __( 'Slide 1', 'elementstack-elementor-addons' ) ],
                    [ 'carousel_caption' => __( 'Slide 2', 'elementstack-elementor-addons' ) ],
                    [ 'carousel_caption' => __( 'Slide 3', 'elementstack-elementor-addons' ) ],
                ],
                'title_field' => '{{{ carousel_caption }}}',
            ]
        );

        $this->add_responsive_control(
            'slides_per_view',
            [
                'label' => __( 'Slides Per View', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 6,
                'step' => 1,
                'default' => 3,
                'tablet_default' => 2,
                'mobile_default' => 1,
            ]
        );

        $this->add_responsive_control(
            'space_between',
            [
                'label' => __( 'Space Between (px)', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 80 ] ],
                'default' => [ 'size' => 20, 'unit' => 'px' ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_image_carousel_behavior',
            [
                'label' => __( 'Behavior', 'elementstack-elementor-addons' ),
            ]
        );

        $this->add_control(
            'show_arrows',
            [
                'label' => __( 'Show Arrows', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_dots',
            [
                'label' => __( 'Show Dots', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label' => __( 'Autoplay', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'autoplay_delay',
            [
                'label' => __( 'Autoplay Speed (ms)', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 3500,
                'min' => 500,
                'max' => 15000,
                'step' => 100,
                'condition' => [ 'autoplay' => 'yes' ],
            ]
        );

        $this->add_control(
            'pause_on_hover',
            [
                'label' => __( 'Pause on Hover', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [ 'autoplay' => 'yes' ],
            ]
        );

        $this->add_control(
            'loop',
            [
                'label' => __( 'Loop', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'transition_speed',
            [
                'label' => __( 'Transition Speed (ms)', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 650,
                'min' => 100,
                'max' => 5000,
                'step' => 50,
            ]
        );

        $this->add_control(
            'drag_swipe',
            [
                'label' => __( 'Drag / Swipe', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_image_carousel_image_style',
            [
                'label' => __( 'Image', 'elementstack-elementor-addons' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_height',
            [
                'label' => __( 'Image Height', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 80, 'max' => 700 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-image-carousel-slide img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'image_fit',
            [
                'label' => __( 'Object Fit', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'cover',
                'options' => [
                    'cover' => __( 'Cover', 'elementstack-elementor-addons' ),
                    'contain' => __( 'Contain', 'elementstack-elementor-addons' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-image-carousel-slide img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_radius',
            [
                'label' => __( 'Border Radius', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-image-carousel-slide' => 'border-radius: {{SIZE}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_image_carousel_caption_style',
            [
                'label' => __( 'Caption', 'elementstack-elementor-addons' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'caption_typography',
                'selector' => '{{WRAPPER}} .bdea-image-carousel-caption',
            ]
        );

        $this->add_control(
            'caption_color',
            [
                'label' => __( 'Caption Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-image-carousel-caption' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_image_carousel_nav_style',
            [
                'label' => __( 'Navigation', 'elementstack-elementor-addons' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'arrow_size',
            [
                'label' => __( 'Arrow Size', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 24, 'max' => 120 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-image-carousel-prev, {{WRAPPER}} .bdea-image-carousel-next' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_color',
            [
                'label' => __( 'Arrow Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-image-carousel-prev, {{WRAPPER}} .bdea-image-carousel-next' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_bg',
            [
                'label' => __( 'Arrow Background', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'rgba(0,0,0,0.4)',
                'selectors' => [
                    '{{WRAPPER}} .bdea-image-carousel-prev, {{WRAPPER}} .bdea-image-carousel-next' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'dots_color',
            [
                'label' => __( 'Dots Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-image-carousel-pagination .swiper-pagination-bullet' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( empty( $settings['carousel_images'] ) ) {
            return;
        }

        $space_desktop = ( isset( $settings['space_between']['size'] ) ) ? (int) $settings['space_between']['size'] : 20;
        $space_tablet  = ( isset( $settings['space_between_tablet']['size'] ) ) ? (int) $settings['space_between_tablet']['size'] : $space_desktop;
        $space_mobile  = ( isset( $settings['space_between_mobile']['size'] ) ) ? (int) $settings['space_between_mobile']['size'] : $space_tablet;

        $slides_desktop = ! empty( $settings['slides_per_view'] ) ? (int) $settings['slides_per_view'] : 3;
        $slides_tablet  = ! empty( $settings['slides_per_view_tablet'] ) ? (int) $settings['slides_per_view_tablet'] : 2;
        $slides_mobile  = ! empty( $settings['slides_per_view_mobile'] ) ? (int) $settings['slides_per_view_mobile'] : 1;

        $settings_data = [
            'showArrows' => ( $settings['show_arrows'] === 'yes' ),
            'showDots' => ( $settings['show_dots'] === 'yes' ),
            'autoplay' => ( $settings['autoplay'] === 'yes' ),
            'autoplayDelay' => ! empty( $settings['autoplay_delay'] ) ? (int) $settings['autoplay_delay'] : 3500,
            'pauseOnHover' => ( $settings['pause_on_hover'] === 'yes' ),
            'loop' => ( $settings['loop'] === 'yes' ),
            'speed' => ! empty( $settings['transition_speed'] ) ? (int) $settings['transition_speed'] : 650,
            'allowTouchMove' => ( $settings['drag_swipe'] === 'yes' ),
            'slidesDesktop' => $slides_desktop,
            'slidesTablet' => $slides_tablet,
            'slidesMobile' => $slides_mobile,
            'spaceDesktop' => $space_desktop,
            'spaceTablet' => $space_tablet,
            'spaceMobile' => $space_mobile,
        ];
        ?>
        <div class="bdea-carousel-widget bdea-image-carousel-widget bdea-nav-inside" data-settings='<?php echo esc_attr( wp_json_encode( $settings_data ) ); ?>'>
            <div class="swiper bdea-swiper">
                <div class="swiper-wrapper">
                    <?php foreach ( $settings['carousel_images'] as $slide ) : ?>
                        <?php
                        $image_id  = ! empty( $slide['carousel_image']['id'] ) ? $slide['carousel_image']['id'] : '';
                        $image_url = ! empty( $slide['carousel_image']['url'] ) ? $slide['carousel_image']['url'] : '';
                        $caption   = ! empty( $slide['carousel_caption'] ) ? $slide['carousel_caption'] : '';
                        $has_link  = ! empty( $slide['carousel_link']['url'] );
                        ?>
                        <div class="swiper-slide">
                            <div class="bdea-image-carousel-slide">
                                <?php if ( $has_link ) : ?>
                                    <a href="<?php echo esc_url( $slide['carousel_link']['url'] ); ?>"
                                       <?php echo ! empty( $slide['carousel_link']['is_external'] ) ? 'target="_blank"' : ''; ?>
                                       <?php echo ! empty( $slide['carousel_link']['nofollow'] ) ? 'rel="nofollow"' : ''; ?>>
                                <?php endif; ?>

                                <?php
                                if ( $image_id ) {
                                    echo wp_get_attachment_image( $image_id, 'medium_large', false, [ 'loading' => 'lazy' ] );
                                } elseif ( $image_url ) {
                                    echo '<img src="' . esc_url( $image_url ) . '" alt="' . esc_attr( $caption ) . '" loading="lazy" />';
                                }
                                ?>

                                <?php if ( $has_link ) : ?>
                                    </a>
                                <?php endif; ?>

                                <?php if ( $caption ) : ?>
                                    <div class="bdea-image-carousel-caption"><?php echo esc_html( $caption ); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if ( $settings['show_arrows'] === 'yes' ) : ?>
                    <button type="button" class="bdea-swiper-button-prev bdea-image-carousel-prev" aria-label="Previous Slide">&#10094;</button>
                    <button type="button" class="bdea-swiper-button-next bdea-image-carousel-next" aria-label="Next Slide">&#10095;</button>
                <?php endif; ?>
            </div>

            <?php if ( $settings['show_dots'] === 'yes' ) : ?>
                <div class="swiper-pagination bdea-swiper-pagination bdea-image-carousel-pagination"></div>
            <?php endif; ?>
        </div>
        <?php
    }
}