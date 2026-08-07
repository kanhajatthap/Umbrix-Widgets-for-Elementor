<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Post_Excerpt_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_post_excerpt';
    }

    public function get_title() {
        return 'Post Excerpt';
    }

    public function get_icon() {
        return 'eicon-post-excerpt';
    }

    public function get_categories() {
        return [ 'elementstack-elements' ];
    }

    public function get_style_depends() {
        return [ 'bdea-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'bdea_post_excerpt_content',
            [
                'label' => 'Content',
            ]
        );

        $this->add_control(
            'word_limit',
            [
                'label' => 'Word Limit',
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 25,
                'min' => 0,
                'max' => 200,
                'description' => 'Set 0 for no limit.',
            ]
        );

        $this->add_control(
            'show_read_more',
            [
                'label' => 'Show Read More',
                'type' => \Elementor\Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'read_more_text',
            [
                'label' => 'Read More Text',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Read More',
                'condition' => [
                    'show_read_more' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_post_excerpt_style',
            [
                'label' => 'Excerpt',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'excerpt_typography',
                'selector' => '{{WRAPPER}} .bdea-post-excerpt',
            ]
        );

        $this->add_control(
            'excerpt_color',
            [
                'label' => 'Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4b5563',
                'selectors' => [
                    '{{WRAPPER}} .bdea-post-excerpt' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'excerpt_align',
            [
                'label' => 'Alignment',
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [ 'title' => 'Left', 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => 'Center', 'icon' => 'eicon-text-align-center' ],
                    'right' => [ 'title' => 'Right', 'icon' => 'eicon-text-align-right' ],
                    'justify' => [ 'title' => 'Justified', 'icon' => 'eicon-text-align-justify' ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .bdea-post-excerpt' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'excerpt_spacing',
            [
                'label' => 'Spacing',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-post-excerpt' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Read More Style
        $this->start_controls_section(
            'bdea_post_excerpt_readmore_style',
            [
                'label' => 'Read More',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_read_more' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'readmore_typography',
                'selector' => '{{WRAPPER}} .bdea-post-excerpt-readmore a',
            ]
        );

        $this->add_control(
            'readmore_color',
            [
                'label' => 'Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .bdea-post-excerpt-readmore a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'readmore_hover_color',
            [
                'label' => 'Hover Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-post-excerpt-readmore a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'readmore_spacing',
            [
                'label' => 'Spacing',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-post-excerpt-readmore' => 'margin-top: {{SIZE}}{{UNIT}};',
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

        $excerpt = get_the_excerpt( $post_id );

        if ( '' === trim( $excerpt ) ) {
            $excerpt = wp_strip_all_tags( get_post_field( 'post_content', $post_id ) );
        }

        $word_limit = ! empty( $settings['word_limit'] ) ? absint( $settings['word_limit'] ) : 0;

        if ( $word_limit > 0 ) {
            $excerpt = wp_trim_words( $excerpt, $word_limit );
        }
        ?>
        <div class="bdea-post-excerpt"><?php echo esc_html( $excerpt ); ?></div>
        <?php if ( 'yes' === $settings['show_read_more'] ) : ?>
            <div class="bdea-post-excerpt-readmore">
                <a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>"><?php echo esc_html( ! empty( $settings['read_more_text'] ) ? $settings['read_more_text'] : 'Read More' ); ?></a>
            </div>
        <?php endif;
    }
}