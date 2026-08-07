<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Slides_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_slides';
    }

    public function get_title() {
        return 'Slides';
    }

    public function get_icon() {
        return 'eicon-slides';
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
            'bdea_slides_section',
            [
                'label' => 'Slides',
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'slide_bg',
            [
                'label' => 'Background Image',
                'type' => \Elementor\Controls_Manager::MEDIA,
            ]
        );

        $repeater->add_control(
            'slide_title',
            [
                'label' => 'Title',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Slide Title',
            ]
        );

        $repeater->add_control(
            'slide_subtitle',
            [
                'label' => 'Subtitle',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Slide subtitle text here.',
            ]
        );

        $repeater->add_control(
            'slide_btn_text',
            [
                'label' => 'Button Text',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Learn More',
            ]
        );

        $repeater->add_control(
            'slide_btn_url',
            [
                'label' => 'Button Link',
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://example.com',
            ]
        );

        $this->add_control(
            'slides',
            [
                'label' => 'Slides',
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [ 'slide_title' => 'Slide One', 'slide_subtitle' => 'Welcome to our first slide.' ],
                    [ 'slide_title' => 'Slide Two', 'slide_subtitle' => 'Here is the second slide.' ],
                ],
                'title_field' => '{{{ slide_title }}}',
            ]
        );

        $this->add_control(
            'slides_autoplay',
            [
                'label' => 'Autoplay',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'slides_dots',
            [
                'label' => 'Show Dots',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'slides_arrows',
            [
                'label' => 'Show Arrows',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_slides_height_section',
            [
                'label' => 'Height',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'slides_height',
            [
                'label' => 'Slide Height',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'vh' ],
                'range' => [ 'px' => [ 'min' => 200, 'max' => 900 ], 'vh' => [ 'min' => 30, 'max' => 100 ] ],
                'default' => [ 'size' => 500, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-slides .bdea-slide' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'slides_overlay',
            [
                'label' => 'Overlay Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'rgba(15, 23, 42, 0.45)',
                'selectors' => [
                    '{{WRAPPER}} .bdea-slide-bg::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_slides_title_style',
            [
                'label' => 'Title',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'slide_title_typography',
                'selector' => '{{WRAPPER}} .bdea-slide-title',
            ]
        );

        $this->add_control(
            'slide_title_color',
            [
                'label' => 'Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .bdea-slide-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_slides_subtitle_style',
            [
                'label' => 'Subtitle',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'slide_subtitle_typography',
                'selector' => '{{WRAPPER}} .bdea-slide-subtitle',
            ]
        );

        $this->add_control(
            'slide_subtitle_color',
            [
                'label' => 'Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#e5e7eb',
                'selectors' => [
                    '{{WRAPPER}} .bdea-slide-subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( empty( $settings['slides'] ) ) {
            return;
        }

        $slider_data = [
            'autoplay' => ( 'yes' === $settings['slides_autoplay'] ),
            'dots'     => ( 'yes' === $settings['slides_dots'] ),
            'arrows'   => ( 'yes' === $settings['slides_arrows'] ),
        ];
        ?>
        <div class="bdea-slides-widget">
            <div class="bdea-slides swiper" data-settings='<?php echo esc_attr( wp_json_encode( $slider_data ) ); ?>'>
                <div class="swiper-wrapper">
                    <?php foreach ( $settings['slides'] as $slide ) : ?>
                        <div class="swiper-slide bdea-slide">
                            <?php if ( ! empty( $slide['slide_bg']['url'] ) ) : ?>
                                <div class="bdea-slide-bg" style="background-image:url('<?php echo esc_url( $slide['slide_bg']['url'] ); ?>');"></div>
                            <?php endif; ?>
                            <div class="bdea-slide-content">
                                <?php if ( ! empty( $slide['slide_title'] ) ) : ?>
                                    <h2 class="bdea-slide-title"><?php echo esc_html( $slide['slide_title'] ); ?></h2>
                                <?php endif; ?>
                                <?php if ( ! empty( $slide['slide_subtitle'] ) ) : ?>
                                    <p class="bdea-slide-subtitle"><?php echo esc_html( $slide['slide_subtitle'] ); ?></p>
                                <?php endif; ?>
                                <?php if ( ! empty( $slide['slide_btn_text'] ) ) : ?>
                                    <?php $btn_url = ! empty( $slide['slide_btn_url']['url'] ) ? $slide['slide_btn_url']['url'] : '#'; ?>
                                    <a class="bdea-slide-btn" href="<?php echo esc_url( $btn_url ); ?>"><?php echo esc_html( $slide['slide_btn_text'] ); ?></a>
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
