<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class ELEMENTSKEY_Post_Excerpt_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'elementskey_post_excerpt';
    }

    public function get_title() {
        return 'Post Excerpt';
    }

    public function get_icon() {
        return 'eicon-post-excerpt';
    }

    public function get_categories() {
        return [ 'elementskey-elements' ];
    }

    public function get_style_depends() {
        return [ 'elementskey-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'elementskey_post_excerpt_content',
            [
                'label' => __( 'Content', 'elementskey' ),
            ]
        );

        $this->add_control(
            'word_limit',
            [
                'label' => __( 'Word Limit', 'elementskey' ),
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
                'label' => __( 'Show Read More', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'read_more_text',
            [
                'label' => __( 'Read More Text', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Read More', 'elementskey' ),
                'condition' => [
                    'show_read_more' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_post_excerpt_style',
            [
                'label' => __( 'Excerpt', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'excerpt_typography',
                'selector' => '{{WRAPPER}} .elementskey-post-excerpt',
            ]
        );

        $this->add_control(
            'excerpt_color',
            [
                'label' => __( 'Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4b5563',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-post-excerpt' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'excerpt_align',
            [
                'label' => __( 'Alignment', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [ 'title' => __( 'Left', 'elementskey' ), 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => __( 'Center', 'elementskey' ), 'icon' => 'eicon-text-align-center' ],
                    'right' => [ 'title' => __( 'Right', 'elementskey' ), 'icon' => 'eicon-text-align-right' ],
                    'justify' => [ 'title' => __( 'Justified', 'elementskey' ), 'icon' => 'eicon-text-align-justify' ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-post-excerpt' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'excerpt_spacing',
            [
                'label' => __( 'Spacing', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-post-excerpt' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Read More Style
        $this->start_controls_section(
            'elementskey_post_excerpt_readmore_style',
            [
                'label' => __( 'Read More', 'elementskey' ),
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
                'selector' => '{{WRAPPER}} .elementskey-post-excerpt-readmore a',
            ]
        );

        $this->add_control(
            'readmore_color',
            [
                'label' => __( 'Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-post-excerpt-readmore a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'readmore_hover_color',
            [
                'label' => __( 'Hover Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-post-excerpt-readmore a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'readmore_spacing',
            [
                'label' => __( 'Spacing', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-post-excerpt-readmore' => 'margin-top: {{SIZE}}{{UNIT}};',
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

        $excerpt = get_the_excerpt( $post_id );

        if ( '' === trim( $excerpt ) ) {
            $excerpt = wp_strip_all_tags( get_post_field( 'post_content', $post_id ) );
        }

        $word_limit = ! empty( $settings['word_limit'] ) ? absint( $settings['word_limit'] ) : 0;

        if ( $word_limit > 0 ) {
            $excerpt = wp_trim_words( $excerpt, $word_limit );
        }
        ?>
        <div class="elementskey-post-excerpt"><?php echo esc_html( $excerpt ); ?></div>
        <?php if ( 'yes' === $settings['show_read_more'] ) : ?>
            <div class="elementskey-post-excerpt-readmore">
                <a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>"><?php echo esc_html( ! empty( $settings['read_more_text'] ) ? $settings['read_more_text'] : 'Read More' ); ?></a>
            </div>
        <?php endif;
    }
}