<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class ELEMENTSKEY_Swiper_Carousel_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'elementskey_swiper_carousel';
    }

    public function get_title() {
        return 'Carousel';
    }

    public function get_icon() {
        return 'eicon-slider-push';
    }

    public function get_categories() {
        return [ 'elementskey-elements' ];
    }

    public function get_style_depends() {
        return [ 'swiper', 'elementskey-style' ];
    }

    public function get_script_depends() {
        return [ 'swiper', 'elementskey-carousel-script' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'content_slides_section',
            [
                'label' => __( 'Slides', 'elementskey' ),
            ]
        );

        $this->add_control(
            'layout_type',
            [
                'label' => __( 'Layout Type', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'card',
                'options' => [
                    'basic' => __( 'Basic Carousel', 'elementskey' ),
                    'card' => __( 'Card Style', 'elementskey' ),
                    'image-only' => __( 'Image Only', 'elementskey' ),
                ],
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'slide_image',
            [
                'label' => __( 'Image', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'slide_title',
            [
                'label' => __( 'Title', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Real Estate Investors', 'elementskey' ),
            ]
        );

        $repeater->add_control(
            'slide_description',
            [
                'label' => __( 'Description', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => 'Enrich property data to analyze investments and assess risk.',
            ]
        );

        $repeater->add_control(
            'slide_button_text',
            [
                'label' => __( 'Button Text', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
            ]
        );

        $repeater->add_control(
            'slide_button_url',
            [
                'label' => __( 'Button Link', 'elementskey' ),
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
                'label' => __( 'Slides', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'slide_title' => __( 'Real Estate Investors', 'elementskey' ),
                        'slide_description' => 'Enrich property data to analyze investments, assess risks, and estimate returns.',
                    ],
                    [
                        'slide_title' => __( 'Insurance Companies', 'elementskey' ),
                        'slide_description' => 'Access property details to assess risk for home, flood, and fire insurance underwriting.',
                    ],
                    [
                        'slide_title' => __( 'Mortgage Lenders', 'elementskey' ),
                        'slide_description' => 'Evaluate collateral and assess borrower risk with enriched property intelligence.',
                    ],
                    [
                        'slide_title' => __( 'Appraisers', 'elementskey' ),
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
                'label' => __( 'Behavior', 'elementskey' ),
            ]
        );

        $this->add_responsive_control(
            'slides_per_view',
            [
                'label' => __( 'Slides Per View', 'elementskey' ),
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
                'label' => __( 'Space Between (px)', 'elementskey' ),
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
                'label' => __( 'Show Arrows', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_dots',
            [
                'label' => __( 'Show Dots', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label' => __( 'Autoplay', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'autoplay_delay',
            [
                'label' => __( 'Autoplay Speed (ms)', 'elementskey' ),
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
                'label' => __( 'Pause on Hover', 'elementskey' ),
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
                'label' => __( 'Loop', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'effect',
            [
                'label' => __( 'Animation Type', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'slide',
                'options' => [
                    'slide' => __( 'Slide', 'elementskey' ),
                    'fade' => __( 'Fade', 'elementskey' ),
                ],
            ]
        );

        $this->add_control(
            'transition_speed',
            [
                'label' => __( 'Transition Speed (ms)', 'elementskey' ),
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
                'label' => __( 'Center Mode', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $this->add_control(
            'equal_height',
            [
                'label' => __( 'Equal Height', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'drag_swipe',
            [
                'label' => __( 'Drag / Swipe', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_container_section',
            [
                'label' => __( 'Container', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'container_bg',
            [
                'label' => __( 'Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-carousel-widget' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_max_width',
            [
                'label' => __( 'Max Width', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [ 'min' => 320, 'max' => 1800 ],
                    '%' => [ 'min' => 20, 'max' => 100 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-carousel-widget' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_alignment',
            [
                'label' => __( 'Alignment', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'elementskey' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'elementskey' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'elementskey' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'selectors_dictionary' => [
                    'left' => 'margin-left: 0; margin-right: auto;',
                    'center' => 'margin-left: auto; margin-right: auto;',
                    'right' => 'margin-left: auto; margin-right: 0;',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-carousel-widget' => '{{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_padding',
            [
                'label' => __( 'Outer Padding', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-carousel-widget' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_slide_section',
            [
                'label' => __( 'Slide / Card', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'slide_bg',
            [
                'label' => __( 'Background Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-carousel-slide-inner' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'slide_border',
                'selector' => '{{WRAPPER}} .elementskey-carousel-slide-inner',
            ]
        );

        $this->add_responsive_control(
            'slide_radius',
            [
                'label' => __( 'Border Radius', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 60 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-carousel-slide-inner' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'slide_shadow',
                'selector' => '{{WRAPPER}} .elementskey-carousel-slide-inner',
            ]
        );

        $this->add_responsive_control(
            'slide_padding',
            [
                'label' => __( 'Inner Padding', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-carousel-slide-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_image_section',
            [
                'label' => __( 'Image', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_height',
            [
                'label' => __( 'Image Height', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [ 'min' => 80, 'max' => 700 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-carousel-image-wrap img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'image_fit',
            [
                'label' => __( 'Object Fit', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'cover',
                'options' => [
                    'cover' => __( 'Cover', 'elementskey' ),
                    'contain' => __( 'Contain', 'elementskey' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-carousel-image-wrap img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_radius',
            [
                'label' => __( 'Border Radius', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 60 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-carousel-image-wrap img' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_spacing',
            [
                'label' => __( 'Spacing Below Image', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 80 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-carousel-image-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_typography_section',
            [
                'label' => __( 'Typography', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .elementskey-carousel-title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Title Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-carousel-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'selector' => '{{WRAPPER}} .elementskey-carousel-description',
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => __( 'Description Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-carousel-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .elementskey-carousel-button',
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => __( 'Button Text Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-carousel-button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_bg_color',
            [
                'label' => __( 'Button Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-carousel-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_radius',
            [
                'label' => __( 'Button Radius', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 60 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-carousel-button' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => __( 'Button Padding', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-carousel-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_navigation_section',
            [
                'label' => __( 'Navigation', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
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
            'arrow_size',
            [
                'label' => __( 'Arrow Size', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [ 'min' => 24, 'max' => 120 ],
                ],
                'default' => [
                    'size' => 40,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-swiper-button-prev, {{WRAPPER}} .elementskey-swiper-button-next, {{WRAPPER}} .elementskey-custom-arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrow_icon_size',
            [
                'label' => __( 'Arrow Icon Size', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [ 'min' => 10, 'max' => 60 ],
                ],
                'default' => [
                    'size' => 20,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-custom-arrow .elementskey-arrow-icon, {{WRAPPER}} .elementskey-custom-arrow .elementskey-arrow-icon i, {{WRAPPER}} .elementskey-custom-arrow .elementskey-arrow-icon svg' => 'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'prev_arrow_icon',
            [
                'label'   => __( 'Previous Arrow Icon', 'elementskey' ),
                'type'    => \Elementor\Controls_Manager::ICONS,
                'default' => [ 'value' => 'fas fa-chevron-left', 'library' => 'fa-solid' ],
            ]
        );

        $this->add_control(
            'next_arrow_icon',
            [
                'label'   => __( 'Next Arrow Icon', 'elementskey' ),
                'type'    => \Elementor\Controls_Manager::ICONS,
                'default' => [ 'value' => 'fas fa-chevron-right', 'library' => 'fa-solid' ],
            ]
        );

        $this->add_responsive_control(
            'arrow_padding',
            [
                'label' => __( 'Arrow Padding', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-swiper-button-prev, {{WRAPPER}} .elementskey-swiper-button-next' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_color',
            [
                'label' => __( 'Arrow Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-swiper-button-prev, {{WRAPPER}} .elementskey-swiper-button-next' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_bg',
            [
                'label' => __( 'Arrow Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-swiper-button-prev, {{WRAPPER}} .elementskey-swiper-button-next' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'arrow_border',
                'selector' => '{{WRAPPER}} .elementskey-custom-arrow',
            ]
        );

        $this->add_responsive_control(
            'arrow_radius',
            [
                'label'      => __( 'Arrow Border Radius', 'elementskey' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .elementskey-custom-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_border_color',
            [
                'label'     => __( 'Arrow Border Color', 'elementskey' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-custom-arrow' => 'border-style: solid; border-width: 1px; border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrow_offset',
            [
                'label' => __( 'Arrow Offset', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [ 'min' => -80, 'max' => 80 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-nav-inside .elementskey-swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementskey-nav-inside .elementskey-swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementskey-nav-outside .elementskey-swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementskey-nav-outside .elementskey-swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'dots_size',
            [
                'label' => __( 'Dots Size', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [ 'min' => 4, 'max' => 30 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-swiper-pagination .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'dots_color',
            [
                'label' => __( 'Dots Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-swiper-pagination .swiper-pagination-bullet' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'dots_active_color',
            [
                'label' => __( 'Active Dot Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-swiper-pagination .swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'dots_spacing',
            [
                'label' => __( 'Dots Spacing', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 80 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-swiper-pagination' => 'margin-top: {{SIZE}}{{UNIT}};',
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

        $previous_icon = ! empty( $settings['prev_arrow_icon'] ) ? $settings['prev_arrow_icon'] : [ 'value' => 'fas fa-chevron-left', 'library' => 'fa-solid' ];
        $next_icon     = ! empty( $settings['next_arrow_icon'] ) ? $settings['next_arrow_icon'] : [ 'value' => 'fas fa-chevron-right', 'library' => 'fa-solid' ];

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
            'elementskey-carousel-widget',
            'elementskey-layout-' . $settings['layout_type'],
            ( $settings['nav_position'] === 'outside' ) ? 'elementskey-nav-outside' : 'elementskey-nav-inside',
            ( $settings['equal_height'] === 'yes' ) ? 'elementskey-equal-height' : '',
        ];

        $widget_classes = implode( ' ', array_filter( $widget_classes ) );
        ?>

        <div class="<?php echo esc_attr( $widget_classes ); ?>" data-settings='<?php echo esc_attr( wp_json_encode( $settings_data ) ); ?>'>
            <div class="swiper elementskey-swiper">
                <div class="swiper-wrapper">
                    <?php foreach ( $settings['slides'] as $slide ) : ?>
                        <div class="swiper-slide">
                            <div class="elementskey-carousel-slide-inner">
                                <?php if ( ! empty( $slide['slide_image']['url'] ) ) : ?>
                                    <div class="elementskey-carousel-image-wrap">
                                        <img src="<?php echo esc_url( $slide['slide_image']['url'] ); ?>" alt="<?php echo esc_attr( $slide['slide_title'] ); ?>">
                                    </div>
                                <?php endif; ?>

                                <div class="elementskey-carousel-content">
                                    <?php if ( ! empty( $slide['slide_title'] ) ) : ?>
                                        <h3 class="elementskey-carousel-title"><?php echo esc_html( $slide['slide_title'] ); ?></h3>
                                    <?php endif; ?>

                                    <?php if ( ! empty( $slide['slide_description'] ) ) : ?>
                                        <p class="elementskey-carousel-description"><?php echo esc_html( $slide['slide_description'] ); ?></p>
                                    <?php endif; ?>

                                    <?php if ( ! empty( $slide['slide_button_text'] ) && ! empty( $slide['slide_button_url']['url'] ) ) : ?>
                                        <a class="elementskey-carousel-button"
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
            </div>

            <?php if ( $settings['show_arrows'] === 'yes' ) : ?>
                <button type="button" class="elementskey-swiper-button-prev elementskey-custom-arrow" aria-label="Previous Slide"><span class="elementskey-arrow-icon"><?php \Elementor\Icons_Manager::render_icon( $previous_icon, [ 'aria-hidden' => 'true' ] ); ?></span></button>
                <button type="button" class="elementskey-swiper-button-next elementskey-custom-arrow" aria-label="Next Slide"><span class="elementskey-arrow-icon"><?php \Elementor\Icons_Manager::render_icon( $next_icon, [ 'aria-hidden' => 'true' ] ); ?></span></button>
            <?php endif; ?>

            <?php if ( $settings['show_dots'] === 'yes' ) : ?>
                <div class="swiper-pagination elementskey-swiper-pagination"></div>
            <?php endif; ?>
        </div>

        <?php
    }
}
