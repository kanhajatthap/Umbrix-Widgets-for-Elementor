<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class ELEMENTSKEY_Post_Comments_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'elementskey_post_comments';
    }

    public function get_title() {
        return 'Post Comments';
    }

    public function get_icon() {
        return 'eicon-comments';
    }

    public function get_categories() {
        return [ 'elementskey-elements' ];
    }

    public function get_style_depends() {
        return [ 'elementskey-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'elementskey_post_comments_content',
            [
                'label' => __( 'Content', 'elementskey' ),
            ]
        );

        $this->add_control(
            'show_count',
            [
                'label' => __( 'Show Comment Count', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'avatar_size',
            [
                'label' => __( 'Avatar Size', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 48,
                'min' => 16,
                'max' => 128,
            ]
        );

        $this->end_controls_section();

        // Title Style
        $this->start_controls_section(
            'elementskey_post_comments_title_style',
            [
                'label' => __( 'Title', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .elementskey-post-comments-title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-post-comments-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_spacing',
            [
                'label' => __( 'Spacing', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-post-comments-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Comment Style
        $this->start_controls_section(
            'elementskey_post_comments_comment_style',
            [
                'label' => __( 'Comment', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'comment_text_typography',
                'selector' => '{{WRAPPER}} .elementskey-post-comments .comment',
            ]
        );

        $this->add_control(
            'comment_text_color',
            [
                'label' => __( 'Text Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-post-comments .comment .comment-content, {{WRAPPER}} .elementskey-post-comments .comment .comment-meta' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'comment_bg',
            [
                'label' => __( 'Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-post-comments .comment' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'comment_border_color',
            [
                'label' => __( 'Comment Border Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#f3f4f6',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-post-comments .comment' => 'border-bottom-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'comment_padding',
            [
                'label' => __( 'Padding', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-post-comments .comment' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'comment_spacing',
            [
                'label' => __( 'Spacing', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-post-comments .comment + .comment' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'avatar_border_radius',
            [
                'label' => __( 'Avatar Border Radius', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-post-comments img.avatar' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Form Style
        $this->start_controls_section(
            'elementskey_post_comments_form_style',
            [
                'label' => __( 'Form', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'form_typography',
                'selector' => '{{WRAPPER}} .elementskey-post-comments input[type="text"], {{WRAPPER}} .elementskey-post-comments input[type="email"], {{WRAPPER}} .elementskey-post-comments input[type="url"], {{WRAPPER}} .elementskey-post-comments textarea',
            ]
        );

        $this->add_control(
            'form_input_bg',
            [
                'label' => __( 'Input Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-post-comments input[type="text"], {{WRAPPER}} .elementskey-post-comments input[type="email"], {{WRAPPER}} .elementskey-post-comments input[type="url"], {{WRAPPER}} .elementskey-post-comments textarea' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'form_input_border_color',
            [
                'label' => __( 'Input Border Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-post-comments input[type="text"], {{WRAPPER}} .elementskey-post-comments input[type="email"], {{WRAPPER}} .elementskey-post-comments input[type="url"], {{WRAPPER}} .elementskey-post-comments textarea' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'form_btn_bg',
            [
                'label' => __( 'Button Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-post-comments input[type="submit"], {{WRAPPER}} .elementskey-post-comments button[type="submit"]' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'form_btn_color',
            [
                'label' => __( 'Button Text Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-post-comments input[type="submit"], {{WRAPPER}} .elementskey-post-comments button[type="submit"]' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( ! is_singular() ) {
            echo '<div class="elementskey-loop-grid-empty">Comments are only available on single posts.</div>';
            return;
        }

        if ( post_password_required() ) {
            return;
        }

        $avatar_size = ! empty( $settings['avatar_size'] ) ? absint( $settings['avatar_size'] ) : 48;
        ?>
        <div class="elementskey-post-comments">
            <?php if ( 'yes' === $settings['show_count'] ) : ?>
                <h3 class="elementskey-post-comments-title">
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