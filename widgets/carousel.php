<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Swiper_Carousel_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_swiper_carousel';
    }

    public function get_title() {
        return 'Carousel';
    }

    public function get_icon() {
        return 'eicon-slider-push';
    }

    public function get_categories() {
        return [ 'elementstack-elements' ];
    }

    public function get_style_depends() {
        return [ 'swiper', 'bdea-style' ];
    }

    public function get_script_depends() {
        return [ 'swiper', 'bdea-carousel-script' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'content_slides_section',
            [
                'label' => 'Slides',
            ]
        );

        $this->add_control(
            'layout_type',
            [
                'label' => 'Layout Type',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'card',
                'options' => [
                    'basic' => 'Basic Carousel',
                    'card' => 'Card Style',
                    'image-only' => 'Image Only',
                ],
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'slide_image',
            [
                'label' => 'Image',
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'slide_title',
            [
                'label' => 'Title',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Real Estate Investors',
            ]
        );

        $repeater->add_control(
            'slide_description',
            [
                'label' => 'Description',
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => 'Enrich property data to analyze investments and assess risk.',
            ]
        );

        $repeater->add_control(
            'slide_button_text',
            [
                'label' => 'Button Text',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
            ]
        );

        $repeater->add_control(
            'slide_button_url',
            [
                'label' => 'Button Link',
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://your-link.com',
                'default' => [
                    'url' => '',
                ],
            ]
        );

        $this->add_control(
            'slides',
            [
                'label' => 'Slides',
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'slide_title' => 'Real Estate Investors',
                        'slide_description' => 'Enrich property data to analyze investments, assess risks, and estimate returns.',
                    ],
                    [
                        'slide_title' => 'Insurance Companies',
                        'slide_description' => 'Access property details to assess risk for home, flood, and fire insurance underwriting.',
                    ],
                    [
                        'slide_title' => 'Mortgage Lenders',
                        'slide_description' => 'Evaluate collateral and assess borrower risk with enriched property intelligence.',
                    ],
                    [
                        'slide_title' => 'Appraisers',
                        'slide_description' => 'Gather detailed property data to support accurate and reliable valuations.',
                    ],
                ],
                'title_field' => '{{{ slide_title }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'behavior_section',
            [
                'label' => 'Behavior',
            ]
        );

        $this->add_responsive_control(
            'slides_per_view',
            [
                'label' => 'Slides Per View',
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 6,
                'step' => 1,
                'default' => 4,
                'tablet_default' => 2,
                'mobile_default' => 1,
            ]
        );

        $this->add_responsive_control(
            'space_between',
            [
                'label' => 'Space Between (px)',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 80 ],
                ],
                'default' => [
                    'size' => 24,
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'size' => 20,
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'size' => 14,
                    'unit' => 'px',
                ],
            ]
        );

        $this->add_control(
            'show_arrows',
            [
                'label' => 'Show Arrows',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_dots',
            [
                'label' => 'Show Dots',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label' => 'Autoplay',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'autoplay_delay',
            [
                'label' => 'Autoplay Speed (ms)',
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 3500,
                'min' => 500,
                'max' => 15000,
                'step' => 100,
                'condition' => [
                    'autoplay' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'pause_on_hover',
            [
                'label' => 'Pause on Hover',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'autoplay' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'loop',
            [
                'label' => 'Loop',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'effect',
            [
                'label' => 'Animation Type',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'slide',
                'options' => [
                    'slide' => 'Slide',
                    'fade' => 'Fade',
                ],
            ]
        );

        $this->add_control(
            'transition_speed',
            [
                'label' => 'Transition Speed (ms)',
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 650,
                'min' => 100,
                'max' => 5000,
                'step' => 50,
            ]
        );

        $this->add_control(
            'center_mode',
            [
                'label' => 'Center Mode',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $this->add_control(
            'equal_height',
            [
                'label' => 'Equal Height',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'drag_swipe',
            [
                'label' => 'Drag / Swipe',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_container_section',
            [
                'label' => 'Container',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'container_bg',
            [
                'label' => 'Background',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-carousel-widget' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_max_width',
            [
                'label' => 'Max Width',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [ 'min' => 320, 'max' => 1800 ],
                    '%' => [ 'min' => 20, 'max' => 100 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-carousel-widget' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_alignment',
            [
                'label' => 'Alignment',
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => [
                    'left' => [
                        'title' => 'Left',
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => 'Center',
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => 'Right',
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'selectors_dictionary' => [
                    'left' => 'margin-left: 0; margin-right: auto;',
                    'center' => 'margin-left: auto; margin-right: auto;',
                    'right' => 'margin-left: auto; margin-right: 0;',
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-carousel-widget' => '{{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_padding',
            [
                'label' => 'Outer Padding',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-carousel-widget' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_slide_section',
            [
                'label' => 'Slide / Card',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'slide_bg',
            [
                'label' => 'Background Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-carousel-slide-inner' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'slide_border',
                'selector' => '{{WRAPPER}} .bdea-carousel-slide-inner',
            ]
        );

        $this->add_responsive_control(
            'slide_radius',
            [
                'label' => 'Border Radius',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 60 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-carousel-slide-inner' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'slide_shadow',
                'selector' => '{{WRAPPER}} .bdea-carousel-slide-inner',
            ]
        );

        $this->add_responsive_control(
            'slide_padding',
            [
                'label' => 'Inner Padding',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-carousel-slide-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_image_section',
            [
                'label' => 'Image',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_height',
            [
                'label' => 'Image Height',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [ 'min' => 80, 'max' => 700 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-carousel-image-wrap img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'image_fit',
            [
                'label' => 'Object Fit',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'cover',
                'options' => [
                    'cover' => 'Cover',
                    'contain' => 'Contain',
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-carousel-image-wrap img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_radius',
            [
                'label' => 'Border Radius',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 60 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-carousel-image-wrap img' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_spacing',
            [
                'label' => 'Spacing Below Image',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 80 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-carousel-image-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_typography_section',
            [
                'label' => 'Typography',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .bdea-carousel-title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => 'Title Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-carousel-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'selector' => '{{WRAPPER}} .bdea-carousel-description',
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => 'Description Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-carousel-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .bdea-carousel-button',
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => 'Button Text Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-carousel-button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_bg_color',
            [
                'label' => 'Button Background',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-carousel-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_radius',
            [
                'label' => 'Button Radius',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 60 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-carousel-button' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => 'Button Padding',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-carousel-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_navigation_section',
            [
                'label' => 'Navigation',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'nav_position',
            [
                'label' => 'Arrow Position',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'inside',
                'options' => [
                    'inside' => 'Inside',
                    'outside' => 'Outside',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrow_size',
            [
                'label' => 'Arrow Size',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [ 'min' => 24, 'max' => 120 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-swiper-button-prev, {{WRAPPER}} .bdea-swiper-button-next' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrow_icon_size',
            [
                'label' => 'Arrow Icon Size',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [ 'min' => 10, 'max' => 60 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-swiper-button-prev, {{WRAPPER}} .bdea-swiper-button-next' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrow_padding',
            [
                'label' => 'Arrow Padding',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-swiper-button-prev, {{WRAPPER}} .bdea-swiper-button-next' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_color',
            [
                'label' => 'Arrow Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-swiper-button-prev, {{WRAPPER}} .bdea-swiper-button-next' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_bg',
            [
                'label' => 'Arrow Background',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-swiper-button-prev, {{WRAPPER}} .bdea-swiper-button-next' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrow_offset',
            [
                'label' => 'Arrow Offset',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [ 'min' => -80, 'max' => 80 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-nav-inside .bdea-swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .bdea-nav-inside .bdea-swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .bdea-nav-outside .bdea-swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .bdea-nav-outside .bdea-swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'dots_size',
            [
                'label' => 'Dots Size',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [ 'min' => 4, 'max' => 30 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-swiper-pagination .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'dots_color',
            [
                'label' => 'Dots Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-swiper-pagination .swiper-pagination-bullet' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'dots_active_color',
            [
                'label' => 'Active Dot Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-swiper-pagination .swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'dots_spacing',
            [
                'label' => 'Dots Spacing',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 80 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-swiper-pagination' => 'margin-top: {{SIZE}}{{UNIT}};',
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

        $space_desktop = ( isset( $settings['space_between']['size'] ) ) ? (int) $settings['space_between']['size'] : 24;
        $space_tablet = ( isset( $settings['space_between_tablet']['size'] ) ) ? (int) $settings['space_between_tablet']['size'] : $space_desktop;
        $space_mobile = ( isset( $settings['space_between_mobile']['size'] ) ) ? (int) $settings['space_between_mobile']['size'] : $space_tablet;

        $slides_desktop = ! empty( $settings['slides_per_view'] ) ? (int) $settings['slides_per_view'] : 4;
        $slides_tablet = ! empty( $settings['slides_per_view_tablet'] ) ? (int) $settings['slides_per_view_tablet'] : 2;
        $slides_mobile = ! empty( $settings['slides_per_view_mobile'] ) ? (int) $settings['slides_per_view_mobile'] : 1;

        $settings_data = [
            'showArrows' => ( $settings['show_arrows'] === 'yes' ),
            'showDots' => ( $settings['show_dots'] === 'yes' ),
            'autoplay' => ( $settings['autoplay'] === 'yes' ),
            'autoplayDelay' => ! empty( $settings['autoplay_delay'] ) ? (int) $settings['autoplay_delay'] : 3500,
            'pauseOnHover' => ( $settings['pause_on_hover'] === 'yes' ),
            'loop' => ( $settings['loop'] === 'yes' ),
            'effect' => ! empty( $settings['effect'] ) ? $settings['effect'] : 'slide',
            'speed' => ! empty( $settings['transition_speed'] ) ? (int) $settings['transition_speed'] : 650,
            'centerMode' => ( $settings['center_mode'] === 'yes' ),
            'equalHeight' => ( $settings['equal_height'] === 'yes' ),
            'allowTouchMove' => ( $settings['drag_swipe'] === 'yes' ),
            'slidesDesktop' => $slides_desktop,
            'slidesTablet' => $slides_tablet,
            'slidesMobile' => $slides_mobile,
            'spaceDesktop' => $space_desktop,
            'spaceTablet' => $space_tablet,
            'spaceMobile' => $space_mobile,
        ];

        $widget_classes = [
            'bdea-carousel-widget',
            'bdea-layout-' . $settings['layout_type'],
            ( $settings['nav_position'] === 'outside' ) ? 'bdea-nav-outside' : 'bdea-nav-inside',
            ( $settings['equal_height'] === 'yes' ) ? 'bdea-equal-height' : '',
        ];

        $widget_classes = implode( ' ', array_filter( $widget_classes ) );
        ?>

        <div class="<?php echo esc_attr( $widget_classes ); ?>" data-settings='<?php echo esc_attr( wp_json_encode( $settings_data ) ); ?>'>
            <div class="swiper bdea-swiper">
                <div class="swiper-wrapper">
                    <?php foreach ( $settings['slides'] as $slide ) : ?>
                        <div class="swiper-slide">
                            <div class="bdea-carousel-slide-inner">
                                <?php if ( ! empty( $slide['slide_image']['url'] ) ) : ?>
                                    <div class="bdea-carousel-image-wrap">
                                        <img src="<?php echo esc_url( $slide['slide_image']['url'] ); ?>" alt="<?php echo esc_attr( $slide['slide_title'] ); ?>">
                                    </div>
                                <?php endif; ?>

                                <div class="bdea-carousel-content">
                                    <?php if ( ! empty( $slide['slide_title'] ) ) : ?>
                                        <h3 class="bdea-carousel-title"><?php echo esc_html( $slide['slide_title'] ); ?></h3>
                                    <?php endif; ?>

                                    <?php if ( ! empty( $slide['slide_description'] ) ) : ?>
                                        <p class="bdea-carousel-description"><?php echo esc_html( $slide['slide_description'] ); ?></p>
                                    <?php endif; ?>

                                    <?php if ( ! empty( $slide['slide_button_text'] ) && ! empty( $slide['slide_button_url']['url'] ) ) : ?>
                                        <a class="bdea-carousel-button"
                                           href="<?php echo esc_url( $slide['slide_button_url']['url'] ); ?>"
                                           <?php echo $slide['slide_button_url']['is_external'] ? 'target="_blank"' : ''; ?>
                                           <?php echo $slide['slide_button_url']['nofollow'] ? 'rel="nofollow"' : ''; ?>>
                                            <?php echo esc_html( $slide['slide_button_text'] ); ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if ( $settings['show_arrows'] === 'yes' ) : ?>
                    <button type="button" class="bdea-swiper-button-prev" aria-label="Previous Slide">&#10094;</button>
                    <button type="button" class="bdea-swiper-button-next" aria-label="Next Slide">&#10095;</button>
                <?php endif; ?>
            </div>

            <?php if ( $settings['show_dots'] === 'yes' ) : ?>
                <div class="swiper-pagination bdea-swiper-pagination"></div>
            <?php endif; ?>
        </div>

        <?php
    }
}
