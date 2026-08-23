<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class ELEMENTSKEY_Featured_Image_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'elementskey_featured_image';
    }

    public function get_title() {
        return 'Featured Image';
    }

    public function get_icon() {
        return 'eicon-featured-image';
    }

    public function get_categories() {
        return [ 'elementskey-elements' ];
    }

    public function get_style_depends() {
        return [ 'elementskey-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'elementskey_featured_image_content',
            [
                'label' => __( 'Content', 'elementskey' ),
            ]
        );

        $this->add_control(
            'link_to_post',
            [
                'label' => __( 'Link to Post', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'image_size',
            [
                'label' => __( 'Image Size', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'large',
                'options' => [
                    'thumbnail' => __( 'Thumbnail', 'elementskey' ),
                    'medium' => __( 'Medium', 'elementskey' ),
                    'large' => __( 'Large', 'elementskey' ),
                    'full' => __( 'Full', 'elementskey' ),
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_featured_image_style',
            [
                'label' => __( 'Image', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_width',
            [
                'label' => __( 'Width', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [ 'px' => [ 'min' => 1, 'max' => 1200 ], '%' => [ 'min' => 1, 'max' => 100 ] ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-featured-image img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_height',
            [
                'label' => __( 'Height', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [ 'px' => [ 'min' => 1, 'max' => 1200 ], '%' => [ 'min' => 1, 'max' => 100 ] ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-featured-image img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'object_fit',
            [
                'label' => __( 'Object Fit', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => __( 'Default', 'elementskey' ),
                    'cover' => __( 'Cover', 'elementskey' ),
                    'contain' => __( 'Contain', 'elementskey' ),
                    'fill' => __( 'Fill', 'elementskey' ),
                ],
                'condition' => [
                    'image_height!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-featured-image img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_radius',
            [
                'label' => __( 'Border Radius', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
                'default' => [ 'size' => 0, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-featured-image img' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'selector' => '{{WRAPPER}} .elementskey-featured-image img',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_shadow',
                'selector' => '{{WRAPPER}} .elementskey-featured-image img',
            ]
        );

        $this->add_responsive_control(
            'image_spacing',
            [
                'label' => __( 'Spacing', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-featured-image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_align',
            [
                'label' => __( 'Alignment', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [ 'title' => __( 'Left', 'elementskey' ), 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => __( 'Center', 'elementskey' ), 'icon' => 'eicon-text-align-center' ],
                    'right' => [ 'title' => __( 'Right', 'elementskey' ), 'icon' => 'eicon-text-align-right' ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-featured-image' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Hover Effect
        $this->start_controls_section(
            'elementskey_featured_image_hover_style',
            [
                'label' => __( 'Hover', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'hover_scale',
            [
                'label' => __( 'Hover Scale', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ '' ],
                'range' => [ '' => [ 'min' => 1, 'max' => 2, 'step' => 0.01 ] ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-featured-image img' => 'transition: transform 0.3s ease;',
                    '{{WRAPPER}} .elementskey-featured-image:hover img' => 'transform: scale({{SIZE}});',
                ],
            ]
        );

        $this->add_control(
            'hover_opacity',
            [
                'label' => __( 'Hover Opacity', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ '' ],
                'range' => [ '' => [ 'min' => 0.1, 'max' => 1, 'step' => 0.01 ] ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-featured-image img' => 'transition: opacity 0.3s ease;',
                    '{{WRAPPER}} .elementskey-featured-image:hover img' => 'opacity: {{SIZE}};',
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
            echo '<div class="elementskey-loop-grid-empty">No post found.</div>';
            return;
        }

        if ( ! has_post_thumbnail( $post_id ) ) {
            echo '<div class="elementskey-loop-grid-empty">No featured image.</div>';
            return;
        }

        $size = ! empty( $settings['image_size'] ) ? $settings['image_size'] : 'large';

        if ( ! in_array( $size, [ 'thumbnail', 'medium', 'large', 'full' ], true ) ) {
            $size = 'large';
        }

        $image = get_the_post_thumbnail( $post_id, $size, [ 'class' => 'elementskey-featured-image-img' ] );

        if ( 'yes' === $settings['link_to_post'] ) {
            echo '<a class="elementskey-featured-image" href="' . esc_url( get_permalink( $post_id ) ) . '">' . $image . '</a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_the_post_thumbnail() output is safe.
        } else {
            echo '<div class="elementskey-featured-image">' . $image . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_the_post_thumbnail() output is safe.
        }
    }
}