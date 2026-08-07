<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Featured_Image_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_featured_image';
    }

    public function get_title() {
        return 'Featured Image';
    }

    public function get_icon() {
        return 'eicon-featured-image';
    }

    public function get_categories() {
        return [ 'elementstack-elements' ];
    }

    public function get_style_depends() {
        return [ 'bdea-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'bdea_featured_image_content',
            [
                'label' => 'Content',
            ]
        );

        $this->add_control(
            'link_to_post',
            [
                'label' => 'Link to Post',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'image_size',
            [
                'label' => 'Image Size',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'large',
                'options' => [
                    'thumbnail' => 'Thumbnail',
                    'medium' => 'Medium',
                    'large' => 'Large',
                    'full' => 'Full',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_featured_image_style',
            [
                'label' => 'Image',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_width',
            [
                'label' => 'Width',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [ 'px' => [ 'min' => 1, 'max' => 1200 ], '%' => [ 'min' => 1, 'max' => 100 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-featured-image img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_height',
            [
                'label' => 'Height',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [ 'px' => [ 'min' => 1, 'max' => 1200 ], '%' => [ 'min' => 1, 'max' => 100 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-featured-image img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'object_fit',
            [
                'label' => 'Object Fit',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => 'Default',
                    'cover' => 'Cover',
                    'contain' => 'Contain',
                    'fill' => 'Fill',
                ],
                'condition' => [
                    'image_height!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-featured-image img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_radius',
            [
                'label' => 'Border Radius',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
                'default' => [ 'size' => 0, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-featured-image img' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'selector' => '{{WRAPPER}} .bdea-featured-image img',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_shadow',
                'selector' => '{{WRAPPER}} .bdea-featured-image img',
            ]
        );

        $this->add_responsive_control(
            'image_spacing',
            [
                'label' => 'Spacing',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-featured-image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_align',
            [
                'label' => 'Alignment',
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [ 'title' => 'Left', 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => 'Center', 'icon' => 'eicon-text-align-center' ],
                    'right' => [ 'title' => 'Right', 'icon' => 'eicon-text-align-right' ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .bdea-featured-image' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Hover Effect
        $this->start_controls_section(
            'bdea_featured_image_hover_style',
            [
                'label' => 'Hover',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'hover_scale',
            [
                'label' => 'Hover Scale',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ '' ],
                'range' => [ '' => [ 'min' => 1, 'max' => 2, 'step' => 0.01 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-featured-image img' => 'transition: transform 0.3s ease;',
                    '{{WRAPPER}} .bdea-featured-image:hover img' => 'transform: scale({{SIZE}});',
                ],
            ]
        );

        $this->add_control(
            'hover_opacity',
            [
                'label' => 'Hover Opacity',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ '' ],
                'range' => [ '' => [ 'min' => 0.1, 'max' => 1, 'step' => 0.01 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-featured-image img' => 'transition: opacity 0.3s ease;',
                    '{{WRAPPER}} .bdea-featured-image:hover img' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $post_id = get_the_ID();

        if ( ! $post_id ) {
            $queried = get_queried_object();
            if ( $queried instanceof \WP_Post ) {
                $post_id = $queried->ID;
            }
        }

        if ( ! $post_id ) {
            echo '<div class="bdea-loop-grid-empty">No post found.</div>';
            return;
        }

        if ( ! has_post_thumbnail( $post_id ) ) {
            echo '<div class="bdea-loop-grid-empty">No featured image.</div>';
            return;
        }

        $size = ! empty( $settings['image_size'] ) ? $settings['image_size'] : 'large';

        if ( ! in_array( $size, [ 'thumbnail', 'medium', 'large', 'full' ], true ) ) {
            $size = 'large';
        }

        $image = get_the_post_thumbnail( $post_id, $size, [ 'class' => 'bdea-featured-image-img' ] );

        if ( 'yes' === $settings['link_to_post'] ) {
            echo '<a class="bdea-featured-image" href="' . esc_url( get_permalink( $post_id ) ) . '">' . $image . '</a>';
        } else {
            echo '<div class="bdea-featured-image">' . $image . '</div>';
        }
    }
}