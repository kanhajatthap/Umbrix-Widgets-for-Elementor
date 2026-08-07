<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Media_Carousel_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_media_carousel';
    }

    public function get_title() {
        return 'Media Carousel';
    }

    public function get_icon() {
        return 'eicon-media-carousel';
    }

    public function get_categories() {
        return [ 'elementstack-elements' ];
    }

    public function get_style_depends() {
        return [ 'bdea-content-style' ];
    }

    public function get_script_depends() {
        return [ 'swiper', 'bdea-content-script' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'bdea_media_carousel_section',
            [
                'label' => 'Media Carousel',
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'mc_image',
            [
                'label' => 'Image',
                'type' => \Elementor\Controls_Manager::MEDIA,
            ]
        );

        $repeater->add_control(
            'mc_title',
            [
                'label' => 'Title',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Media Title',
            ]
        );

        $repeater->add_control(
            'mc_caption',
            [
                'label' => 'Caption',
                'type' => \Elementor\Controls_Manager::TEXT,
            ]
        );

        $this->add_control(
            'media_items',
            [
                'label' => 'Items',
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [ 'mc_title' => 'Image One' ],
                    [ 'mc_title' => 'Image Two' ],
                    [ 'mc_title' => 'Image Three' ],
                ],
                'title_field' => '{{{ mc_title }}}',
            ]
        );

        $this->add_control(
            'mc_slides_view',
            [
                'label' => 'Slides to Show',
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 3,
                'min' => 1,
                'max' => 6,
            ]
        );

        $this->add_control(
            'mc_autoplay',
            [
                'label' => 'Autoplay',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'mc_dots',
            [
                'label' => 'Show Dots',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'mc_arrows',
            [
                'label' => 'Show Arrows',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_media_carousel_style',
            [
                'label' => 'Style',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'mc_radius',
            [
                'label' => 'Border Radius',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
                'default' => [ 'size' => 8, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-media-card' => 'border-radius: {{SIZE}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );

        $this->add_control(
            'mc_caption_color',
            [
                'label' => 'Caption Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4b5563',
                'selectors' => [
                    '{{WRAPPER}} .bdea-media-caption' => 'color: {{VALUE}};',
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

        $slider_data = [
            'autoplay' => ( 'yes' === $settings['mc_autoplay'] ),
            'dots'     => ( 'yes' === $settings['mc_dots'] ),
            'arrows'   => ( 'yes' === $settings['mc_arrows'] ),
            'slides'   => max( 1, (int) $settings['mc_slides_view'] ),
            'slidesTablet' => 2,
            'slidesMobile' => 1,
        ];
        ?>
        <div class="bdea-media-carousel-widget">
            <div class="bdea-media-carousel swiper" data-settings='<?php echo esc_attr( wp_json_encode( $slider_data ) ); ?>'>
                <div class="swiper-wrapper">
                    <?php foreach ( $settings['media_items'] as $item ) : ?>
                        <div class="swiper-slide">
                            <div class="bdea-media-card">
                                <?php if ( ! empty( $item['mc_image']['url'] ) ) : ?>
                                    <div class="bdea-media-image">
                                        <img src="<?php echo esc_url( $item['mc_image']['url'] ); ?>"
                                             alt="<?php echo esc_attr( $item['mc_title'] ); ?>" loading="lazy" />
                                    </div>
                                <?php endif; ?>
                                <?php if ( ! empty( $item['mc_title'] ) || ! empty( $item['mc_caption'] ) ) : ?>
                                    <div class="bdea-media-caption">
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
                <?php if ( $slider_data['arrows'] ) : ?>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                <?php endif; ?>
                <?php if ( $slider_data['dots'] ) : ?>
                    <div class="swiper-pagination"></div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}
