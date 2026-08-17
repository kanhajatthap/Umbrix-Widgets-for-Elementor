<?php
/**
 * ElementStack Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Post_Comments_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_post_comments';
    }

    public function get_title() {
        return 'Post Comments';
    }

    public function get_icon() {
        return 'eicon-comments';
    }

    public function get_categories() {
        return [ 'elementstack-elements' ];
    }

    public function get_style_depends() {
        return [ 'bdea-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'bdea_post_comments_content',
            [
                'label' => __( 'Content', 'elementstack-elementor-addons' ),
            ]
        );

        $this->add_control(
            'show_count',
            [
                'label' => __( 'Show Comment Count', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'avatar_size',
            [
                'label' => __( 'Avatar Size', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 48,
                'min' => 16,
                'max' => 128,
            ]
        );

        $this->end_controls_section();

        // Title Style
        $this->start_controls_section(
            'bdea_post_comments_title_style',
            [
                'label' => __( 'Title', 'elementstack-elementor-addons' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .bdea-post-comments-title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .bdea-post-comments-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_spacing',
            [
                'label' => __( 'Spacing', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-post-comments-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Comment Style
        $this->start_controls_section(
            'bdea_post_comments_comment_style',
            [
                'label' => __( 'Comment', 'elementstack-elementor-addons' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'comment_text_typography',
                'selector' => '{{WRAPPER}} .bdea-post-comments .comment',
            ]
        );

        $this->add_control(
            'comment_text_color',
            [
                'label' => __( 'Text Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-post-comments .comment .comment-content, {{WRAPPER}} .bdea-post-comments .comment .comment-meta' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'comment_bg',
            [
                'label' => __( 'Background', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-post-comments .comment' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'comment_padding',
            [
                'label' => __( 'Padding', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-post-comments .comment' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'comment_spacing',
            [
                'label' => __( 'Spacing', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-post-comments .comment + .comment' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'avatar_border_radius',
            [
                'label' => __( 'Avatar Border Radius', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-post-comments img.avatar' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Form Style
        $this->start_controls_section(
            'bdea_post_comments_form_style',
            [
                'label' => __( 'Form', 'elementstack-elementor-addons' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'form_typography',
                'selector' => '{{WRAPPER}} .bdea-post-comments input[type="text"], {{WRAPPER}} .bdea-post-comments input[type="email"], {{WRAPPER}} .bdea-post-comments input[type="url"], {{WRAPPER}} .bdea-post-comments textarea',
            ]
        );

        $this->add_control(
            'form_input_bg',
            [
                'label' => __( 'Input Background', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-post-comments input[type="text"], {{WRAPPER}} .bdea-post-comments input[type="email"], {{WRAPPER}} .bdea-post-comments input[type="url"], {{WRAPPER}} .bdea-post-comments textarea' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'form_input_border_color',
            [
                'label' => __( 'Input Border Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-post-comments input[type="text"], {{WRAPPER}} .bdea-post-comments input[type="email"], {{WRAPPER}} .bdea-post-comments input[type="url"], {{WRAPPER}} .bdea-post-comments textarea' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'form_btn_bg',
            [
                'label' => __( 'Button Background', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .bdea-post-comments input[type="submit"], {{WRAPPER}} .bdea-post-comments button[type="submit"]' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'form_btn_color',
            [
                'label' => __( 'Button Text Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .bdea-post-comments input[type="submit"], {{WRAPPER}} .bdea-post-comments button[type="submit"]' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( ! is_singular() ) {
            echo '<div class="bdea-loop-grid-empty">Comments are only available on single posts.</div>';
            return;
        }

        if ( post_password_required() ) {
            return;
        }

        $avatar_size = ! empty( $settings['avatar_size'] ) ? absint( $settings['avatar_size'] ) : 48;
        ?>
        <div class="bdea-post-comments">
            <?php if ( 'yes' === $settings['show_count'] ) : ?>
                <h3 class="bdea-post-comments-title">
                    <?php comments_number( 'No comments', '1 Comment', '% Comments' ); ?>
                </h3>
            <?php endif; ?>

            <?php if ( have_comments() ) : ?>
                <?php wp_list_comments( [ 'avatar_size' => $avatar_size ] ); ?>
                <?php the_comments_navigation(); ?>
            <?php endif; ?>

            <?php comment_form(); ?>
        </div>
        <?php
    }
}