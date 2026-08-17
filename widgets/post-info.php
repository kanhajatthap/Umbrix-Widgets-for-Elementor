<?php
/**
 * ElementStack Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Post_Info_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_post_info';
    }

    public function get_title() {
        return 'Post Info';
    }

    public function get_icon() {
        return 'eicon-info-circle-o';
    }

    public function get_categories() {
        return [ 'elementstack-elements' ];
    }

    public function get_style_depends() {
        return [ 'bdea-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'bdea_post_info_content',
            [
                'label' => __( 'Content', 'elementstack-elementor-addons' ),
            ]
        );

        $this->add_control(
            'show_author',
            [
                'label' => __( 'Show Author', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_date',
            [
                'label' => __( 'Show Date', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_comments',
            [
                'label' => __( 'Show Comments', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_categories',
            [
                'label' => __( 'Show Categories', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_tags',
            [
                'label' => __( 'Show Tags', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'separator',
            [
                'label' => __( 'Separator', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '|',
            ]
        );

        $this->add_control(
            'layout',
            [
                'label' => __( 'Layout', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'inline',
                'options' => [
                    'inline' => __( 'Inline', 'elementstack-elementor-addons' ),
                    'block' => __( 'Block (Stacked)', 'elementstack-elementor-addons' ),
                ],
            ]
        );

        $this->end_controls_section();

        // Typography
        $this->start_controls_section(
            'bdea_post_info_typo',
            [
                'label' => __( 'Typography', 'elementstack-elementor-addons' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'info_typography',
                'selector' => '{{WRAPPER}} .bdea-post-info',
            ]
        );

        $this->add_control(
            'info_color',
            [
                'label' => __( 'Text Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4b5563',
                'selectors' => [
                    '{{WRAPPER}} .bdea-post-info' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'info_align',
            [
                'label' => __( 'Alignment', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [ 'title' => __( 'Left', 'elementstack-elementor-addons' ), 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => __( 'Center', 'elementstack-elementor-addons' ), 'icon' => 'eicon-text-align-center' ],
                    'right' => [ 'title' => __( 'Right', 'elementstack-elementor-addons' ), 'icon' => 'eicon-text-align-right' ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .bdea-post-info' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Link Colors
        $this->start_controls_section(
            'bdea_post_info_links',
            [
                'label' => __( 'Links', 'elementstack-elementor-addons' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'info_link_color',
            [
                'label' => __( 'Link Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .bdea-post-info a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'info_link_hover',
            [
                'label' => __( 'Link Hover Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-post-info a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Separator
        $this->start_controls_section(
            'bdea_post_info_sep_style',
            [
                'label' => __( 'Separator', 'elementstack-elementor-addons' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'sep_color',
            [
                'label' => __( 'Separator Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#9ca3af',
                'selectors' => [
                    '{{WRAPPER}} .bdea-post-info-sep' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'sep_typography_size',
            [
                'label' => __( 'Separator Font Size', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [ 'px' => [ 'min' => 10, 'max' => 40 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-post-info-sep' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'sep_gap',
            [
                'label' => __( 'Gap Around Separator', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 30 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-post-info-sep' => 'margin: 0 {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Item Spacing
        $this->start_controls_section(
            'bdea_post_info_spacing',
            [
                'label' => __( 'Spacing', 'elementstack-elementor-addons' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'item_gap',
            [
                'label' => __( 'Item Gap', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-post-info' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_spacing',
            [
                'label' => __( 'Spacing', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-post-info' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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

        $separator = ! empty( $settings['separator'] ) ? $settings['separator'] : '|';
        $layout    = ( 'block' === $settings['layout'] ) ? 'bdea-post-info-block' : '';

        $items = [];

        if ( 'yes' === $settings['show_author'] ) {
            $author_id   = (int) get_post_field( 'post_author', $post_id );
            $author_name = get_the_author_meta( 'display_name', $author_id );
            $items[]     = '<span class="bdea-post-info-item bdea-post-info-author"><a href="' . esc_url( get_author_posts_url( $author_id ) ) . '">' . esc_html( $author_name ) . '</a></span>';
        }

        if ( 'yes' === $settings['show_date'] ) {
            $items[] = '<span class="bdea-post-info-item bdea-post-info-date">' . esc_html( get_the_date( '', $post_id ) ) . '</span>';
        }

        if ( 'yes' === $settings['show_comments'] ) {
            $count = get_comments_number( $post_id );
            $label = ( 1 === $count ) ? 'Comment' : 'Comments';
            $items[] = '<span class="bdea-post-info-item bdea-post-info-comments"><a href="' . esc_url( get_comments_link( $post_id ) ) . '">' . esc_html( number_format_i18n( $count ) . ' ' . $label ) . '</a></span>';
        }

        if ( 'yes' === $settings['show_categories'] ) {
            $categories = get_the_category_list( ', ', '', $post_id );
            if ( $categories ) {
                $items[] = '<span class="bdea-post-info-item bdea-post-info-categories">' . $categories . '</span>';
            }
        }

        if ( 'yes' === $settings['show_tags'] ) {
            $tags = get_the_tag_list( '', ', ', '', $post_id );
            if ( $tags ) {
                $items[] = '<span class="bdea-post-info-item bdea-post-info-tags">' . $tags . '</span>';
            }
        }

        if ( empty( $items ) ) {
            return;
        }

        $output = '';

        foreach ( $items as $index => $item ) {
            if ( $index > 0 ) {
                $output .= '<span class="bdea-post-info-sep">' . esc_html( $separator ) . '</span>';
            }
            $output .= $item;
        }
        ?>
        <div class="bdea-post-info <?php echo esc_attr( $layout ); ?>"><?php echo wp_kses_post( $output ); ?></div>
        <?php
    }
}