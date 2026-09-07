<?php
/**
 * ElementKey Lite
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class ELEMENTSKEY_Posts_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'elementskey_posts';
    }

    public function get_title() {
        return 'Posts';
    }

    public function get_icon() {
        return 'eicon-post-list';
    }

    public function get_categories() {
        return [ 'elementskey-elements' ];
    }

    public function get_style_depends() {
        return [ 'elementskey-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'elementskey_query_section',
            [
                'label' => __( 'Query', 'elementskey' ),
            ]
        );

        $this->add_control(
            'post_type',
            [
                'label' => __( 'Post Type', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'post',
                'options' => elementskey_widget_post_types(),
            ]
        );

        $this->add_control(
            'include_cats',
            [
                'label' => __( 'Categories', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => elementskey_widget_terms_list( 'category' ),
                'description' => 'Leave empty for all categories.',
            ]
        );

        $this->add_control(
            'include_tags',
            [
                'label' => __( 'Tags', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => elementskey_widget_terms_list( 'post_tag' ),
                'description' => 'Leave empty for all tags.',
            ]
        );

        $this->add_control(
            'exclude_ids',
            [
                'label' => 'Exclude Posts (IDs, comma separated)',
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => '12, 45, 89',
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label' => __( 'Number of Posts', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 6,
                'min' => 1,
                'max' => 100,
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label' => __( 'Order By', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'date',
                'options' => [
                    'date' => __( 'Date', 'elementskey' ),
                    'modified' => __( 'Modified Date', 'elementskey' ),
                    'title' => __( 'Title', 'elementskey' ),
                    'menu_order' => __( 'Menu Order', 'elementskey' ),
                    'rand' => __( 'Random', 'elementskey' ),
                ],
            ]
        );

        $this->add_control(
            'order',
            [
                'label' => __( 'Order', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'DESC',
                'options' => [
                    'ASC' => __( 'Ascending', 'elementskey' ),
                    'DESC' => __( 'Descending', 'elementskey' ),
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_layout_section',
            [
                'label' => __( 'Layout', 'elementskey' ),
            ]
        );

        $this->add_control(
            'layout',
            [
                'label' => __( 'Layout', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'grid',
                'options' => [
                    'grid' => __( 'Grid', 'elementskey' ),
                    'list' => __( 'List', 'elementskey' ),
                ],
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label' => __( 'Columns', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 6,
                'default' => 3,
                'tablet_default' => 2,
                'mobile_default' => 1,
                'condition' => [ 'layout' => 'grid' ],
            ]
        );

        $this->add_responsive_control(
            'column_gap',
            [
                'label' => __( 'Column Gap', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 80 ] ],
                'default' => [ 'size' => 24, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-posts-grid' => 'column-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'row_gap',
            [
                'label' => __( 'Row Gap', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 80 ] ],
                'default' => [ 'size' => 24, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-posts-grid' => 'row-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_card_content_section',
            [
                'label' => __( 'Card Content', 'elementskey' ),
            ]
        );

        $this->add_control(
            'show_thumbnail',
            [
                'label' => __( 'Show Thumbnail', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'thumbnail_size',
            [
                'label' => __( 'Thumbnail Size', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'thumbnail' => __( 'Thumbnail', 'elementskey' ),
                    'medium' => __( 'Medium', 'elementskey' ),
                    'large' => __( 'Large', 'elementskey' ),
                    'medium_large' => __( 'Medium Large', 'elementskey' ),
                    'full' => __( 'Full', 'elementskey' ),
                ],
                'default' => 'medium',
                'condition' => [ 'show_thumbnail' => 'yes' ],
            ]
        );

        $this->add_control(
            'show_meta',
            [
                'label' => __( 'Show Meta', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_title',
            [
                'label' => __( 'Show Title', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_excerpt',
            [
                'label' => __( 'Show Excerpt', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'excerpt_length',
            [
                'label' => __( 'Excerpt Length (words)', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 18,
                'min' => 3,
                'max' => 150,
                'condition' => [ 'show_excerpt' => 'yes' ],
            ]
        );

        $this->add_control(
            'show_read_more',
            [
                'label' => __( 'Show Read More', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'read_more_text',
            [
                'label' => __( 'Read More Text', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Read More', 'elementskey' ),
                'condition' => [ 'show_read_more' => 'yes' ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_card_style_section',
            [
                'label' => __( 'Card', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'card_bg',
            [
                'label' => __( 'Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-post-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'card_border',
                'selector' => '{{WRAPPER}} .elementskey-post-card',
            ]
        );

        $this->add_responsive_control(
            'card_radius',
            [
                'label' => __( 'Border Radius', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-post-card' => 'border-radius: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementskey-post-thumb img' => 'border-radius: {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} 0 0;',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'card_shadow',
                'selector' => '{{WRAPPER}} .elementskey-post-card',
            ]
        );

        $this->add_responsive_control(
            'card_padding',
            [
                'label' => __( 'Padding', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-post-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_image_style_section',
            [
                'label' => __( 'Thumbnail', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_height',
            [
                'label' => __( 'Height', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 80, 'max' => 700 ] ],
                'default' => [ 'size' => 180, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-post-thumb img' => 'height: {{SIZE}}{{UNIT}};',
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
                    'fill' => __( 'Fill', 'elementskey' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-post-thumb img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_typography_section',
            [
                'label' => __( 'Typography', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .elementskey-post-title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Title Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-post-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_hover_color',
            [
                'label' => __( 'Title Hover Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-post-title a:hover' => 'color: {{VALUE}};',
                ],
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
                'label' => __( 'Excerpt Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-post-excerpt' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'meta_typography',
                'selector' => '{{WRAPPER}} .elementskey-post-meta',
            ]
        );

        $this->add_control(
            'meta_color',
            [
                'label' => __( 'Meta Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-post-meta' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'readmore_typography',
                'selector' => '{{WRAPPER}} .elementskey-post-more',
            ]
        );

        $this->add_control(
            'readmore_color',
            [
                'label' => __( 'Read More Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-post-more' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( ! empty( $settings['exclude_ids'] ) ) {
            $settings['exclude_ids'] = array_map( 'trim', explode( ',', $settings['exclude_ids'] ) );
        }

        $query = new \WP_Query( elementskey_widget_query_args( $settings ) );

        if ( ! $query->have_posts() ) {
            echo '<div class="elementskey-loop-grid-empty">No posts found.</div>';
            return;
        }

        $layout = ! empty( $settings['layout'] ) ? $settings['layout'] : 'grid';
        $columns_desktop = ! empty( $settings['columns'] ) ? (int) $settings['columns'] : 3;
        $columns_tablet  = ! empty( $settings['columns_tablet'] ) ? (int) $settings['columns_tablet'] : 2;
        $columns_mobile  = ! empty( $settings['columns_mobile'] ) ? (int) $settings['columns_mobile'] : 1;

        $excerpt_length = ! empty( $settings['excerpt_length'] ) ? absint( $settings['excerpt_length'] ) : 18;
        $thumbnail_size = ! empty( $settings['thumbnail_size'] ) ? $settings['thumbnail_size'] : 'medium';
        $read_more_text = ! empty( $settings['read_more_text'] ) ? $settings['read_more_text'] : 'Read More';

        $wrapper_class = ( 'list' === $layout ) ? 'elementskey-posts-list' : 'elementskey-posts-grid';

        $this->add_render_attribute( 'wrapper', 'class', $wrapper_class );
        $this->add_render_attribute( 'wrapper', 'style', "--elementskey-cols: {$columns_desktop}; --elementskey-cols-tablet: {$columns_tablet}; --elementskey-cols-mobile: {$columns_mobile};" );
        ?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Elementor render attributes are escaped internally. ?>>
            <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                <?php $post_id = get_the_ID(); ?>
                <article class="elementskey-post-card">
                    <?php if ( 'yes' === $settings['show_thumbnail'] && has_post_thumbnail( $post_id ) ) : ?>
                        <a class="elementskey-post-thumb" href="<?php the_permalink(); ?>">
                            <?php echo get_the_post_thumbnail( $post_id, $thumbnail_size ); ?>
                        </a>
                    <?php endif; ?>

                    <div class="elementskey-post-body">
                        <?php if ( 'yes' === $settings['show_meta'] ) : ?>
                            <div class="elementskey-post-meta"><?php echo esc_html( get_the_date() ); ?></div>
                        <?php endif; ?>

                        <?php if ( 'yes' === $settings['show_title'] ) : ?>
                            <h3 class="elementskey-post-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                        <?php endif; ?>

                        <?php if ( 'yes' === $settings['show_excerpt'] ) : ?>
                            <p class="elementskey-post-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), $excerpt_length ) ); ?></p>
                        <?php endif; ?>

                        <?php if ( 'yes' === $settings['show_read_more'] ) : ?>
                            <a class="elementskey-post-more" href="<?php the_permalink(); ?>">
                                <?php echo esc_html( $read_more_text ); ?> &#8594;
                            </a>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
        <?php

        wp_reset_postdata();
    }
}
