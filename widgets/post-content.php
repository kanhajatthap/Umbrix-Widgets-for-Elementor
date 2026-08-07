<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Post_Content_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_post_content';
    }

    public function get_title() {
        return 'Post Content';
    }

    public function get_icon() {
        return 'eicon-post-content';
    }

    public function get_categories() {
        return [ 'elementstack-elements' ];
    }

    public function get_style_depends() {
        return [ 'bdea-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'bdea_post_content_style',
            [
                'label' => 'Content',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'content_typography',
                'selector' => '{{WRAPPER}} .bdea-post-content',
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label' => 'Text Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .bdea-post-content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_align',
            [
                'label' => 'Alignment',
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [ 'title' => 'Left', 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => 'Center', 'icon' => 'eicon-text-align-center' ],
                    'right' => [ 'title' => 'Right', 'icon' => 'eicon-text-align-right' ],
                    'justify' => [ 'title' => 'Justified', 'icon' => 'eicon-text-align-justify' ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-post-content' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_spacing',
            [
                'label' => 'Spacing',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-post-content' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Links
        $this->start_controls_section(
            'bdea_post_content_links_style',
            [
                'label' => 'Links',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'link_color',
            [
                'label' => 'Link Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .bdea-post-content a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'link_hover_color',
            [
                'label' => 'Hover Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-post-content a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'link_decoration',
            [
                'label' => 'Text Decoration',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'underline',
                'options' => [
                    'none' => 'None',
                    'underline' => 'Underline',
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-post-content a' => 'text-decoration: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Lists
        $this->start_controls_section(
            'bdea_post_content_lists_style',
            [
                'label' => 'Lists',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'list_color',
            [
                'label' => 'List Marker Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-post-content ul li::marker' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'list_spacing',
            [
                'label' => 'List Item Spacing',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-post-content ul li' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
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

        $content = get_post_field( 'post_content', $post_id );

        if ( '' === trim( $content ) ) {
            echo '<div class="bdea-loop-grid-empty">Post content is empty.</div>';
            return;
        }

        $content = do_shortcode( $content );
        ?>
        <div class="bdea-post-content"><?php echo wp_kses_post( $content ); ?></div>
        <?php
    }
}