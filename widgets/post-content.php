<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class ELEMENTSKEY_Post_Content_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'elementskey_post_content';
    }

    public function get_title() {
        return 'Post Content';
    }

    public function get_icon() {
        return 'eicon-post-content';
    }

    public function get_categories() {
        return [ 'elementskey-elements' ];
    }

    public function get_style_depends() {
        return [ 'elementskey-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'elementskey_post_content_style',
            [
                'label' => __( 'Content', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'content_typography',
                'selector' => '{{WRAPPER}} .elementskey-post-content',
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label' => __( 'Text Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-post-content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_align',
            [
                'label' => __( 'Alignment', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [ 'title' => __( 'Left', 'elementskey' ), 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => __( 'Center', 'elementskey' ), 'icon' => 'eicon-text-align-center' ],
                    'right' => [ 'title' => __( 'Right', 'elementskey' ), 'icon' => 'eicon-text-align-right' ],
                    'justify' => [ 'title' => __( 'Justified', 'elementskey' ), 'icon' => 'eicon-text-align-justify' ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-post-content' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_spacing',
            [
                'label' => __( 'Spacing', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-post-content' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Links
        $this->start_controls_section(
            'elementskey_post_content_links_style',
            [
                'label' => __( 'Links', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'link_color',
            [
                'label' => __( 'Link Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-post-content a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'link_hover_color',
            [
                'label' => __( 'Hover Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-post-content a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'link_decoration',
            [
                'label' => __( 'Text Decoration', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'underline',
                'options' => [
                    'none' => 'None',
                    'underline' => __( 'Underline', 'elementskey' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-post-content a' => 'text-decoration: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Lists
        $this->start_controls_section(
            'elementskey_post_content_lists_style',
            [
                'label' => __( 'Lists', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'list_color',
            [
                'label' => __( 'List Marker Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-post-content ul li::marker' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'list_spacing',
            [
                'label' => __( 'List Item Spacing', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-post-content ul li' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
            echo '<div class="elementskey-loop-grid-empty">No post found.</div>';
            return;
        }

        $content = get_post_field( 'post_content', $post_id );

        if ( '' === trim( $content ) ) {
            echo '<div class="elementskey-loop-grid-empty">Post content is empty.</div>';
            return;
        }

        $content = do_shortcode( $content );
        ?>
        <div class="elementskey-post-content"><?php echo wp_kses_post( $content ); ?></div>
        <?php
    }
}