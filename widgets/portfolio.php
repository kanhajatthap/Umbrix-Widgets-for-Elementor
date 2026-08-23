<?php
/**
 * ElementKey Lite
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class ELEMENTSKEY_Portfolio_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'elementskey_portfolio';
    }

    public function get_title() {
        return 'Portfolio';
    }

    public function get_icon() {
        return 'eicon-gallery-grid';
    }

    public function get_categories() {
        return [ 'elementkey-lite-elements' ];
    }

    public function get_style_depends() {
        return [ 'elementskey-content-style' ];
    }

    public function get_script_depends() {
        return [ 'elementskey-content-script' ];
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
                'default' => 'portfolio',
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
                'label' => __( 'Number of Items', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 9,
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
            'elementskey_filter_section',
            [
                'label' => __( 'Filter', 'elementskey' ),
            ]
        );

        $this->add_control(
            'show_filter',
            [
                'label' => __( 'Show Filter Bar', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'filter_taxonomy',
            [
                'label' => __( 'Filter Taxonomy', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => elementskey_widget_taxonomies(),
                'default' => 'category',
            ]
        );

        $this->add_control(
            'all_filter_label',
            [
                'label' => '"All" Button Label',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'All', 'elementskey' ),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_layout_section',
            [
                'label' => __( 'Layout', 'elementskey' ),
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
                    '{{WRAPPER}} .elementskey-portfolio-grid' => 'column-gap: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .elementskey-portfolio-grid' => 'row-gap: {{SIZE}}{{UNIT}};',
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
                'default' => 14,
                'min' => 3,
                'max' => 150,
                'condition' => [ 'show_excerpt' => 'yes' ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_filter_style_section',
            [
                'label' => __( 'Filter Bar', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'filter_typography',
                'selector' => '{{WRAPPER}} .elementskey-portfolio-filter-btn',
            ]
        );

        $this->add_responsive_control(
            'filter_align',
            [
                'label' => __( 'Alignment', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [ 'title' => __( 'Left', 'elementskey' ), 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => __( 'Center', 'elementskey' ), 'icon' => 'eicon-text-align-center' ],
                    'right' => [ 'title' => __( 'Right', 'elementskey' ), 'icon' => 'eicon-text-align-right' ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-portfolio-filter' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'filter_btn_tabs' );

        $this->start_controls_tab(
            'filter_btn_normal',
            [ 'label' => __( 'Normal', 'elementskey' ) ]
        );

        $this->add_control(
            'filter_btn_color',
            [
                'label' => __( 'Text Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#555555',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-portfolio-filter-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'filter_btn_bg',
            [
                'label' => __( 'Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-portfolio-filter-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'filter_btn_active',
            [ 'label' => __( 'Active', 'elementskey' ) ]
        );

        $this->add_control(
            'filter_btn_active_color',
            [
                'label' => __( 'Text Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-portfolio-filter-btn.is-active' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'filter_btn_active_bg',
            [
                'label' => __( 'Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-portfolio-filter-btn.is-active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'filter_btn_border',
                'selector' => '{{WRAPPER}} .elementskey-portfolio-filter-btn',
            ]
        );

        $this->add_responsive_control(
            'filter_btn_radius',
            [
                'label' => __( 'Border Radius', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-portfolio-filter-btn' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'filter_btn_gap',
            [
                'label' => __( 'Button Gap', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 40 ] ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-portfolio-filter-btn' => 'margin: 0 {{SIZE}}{{UNIT}}/2 {{SIZE}}{{UNIT}} 0;',
                ],
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
                'selectors' => [
                    '{{WRAPPER}} .elementskey-portfolio-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'card_border',
                'selector' => '{{WRAPPER}} .elementskey-portfolio-card',
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
                    '{{WRAPPER}} .elementskey-portfolio-card' => 'border-radius: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementskey-portfolio-thumb img' => 'border-radius: {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} 0 0;',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'card_shadow',
                'selector' => '{{WRAPPER}} .elementskey-portfolio-card',
            ]
        );

        $this->add_responsive_control(
            'card_padding',
            [
                'label' => __( 'Padding', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-portfolio-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'default' => [ 'size' => 200, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-portfolio-thumb img' => 'height: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .elementskey-portfolio-thumb img' => 'object-fit: {{VALUE}};',
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
                'selector' => '{{WRAPPER}} .elementskey-portfolio-title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Title Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-portfolio-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'excerpt_typography',
                'selector' => '{{WRAPPER}} .elementskey-portfolio-excerpt',
            ]
        );

        $this->add_control(
            'excerpt_color',
            [
                'label' => __( 'Excerpt Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-portfolio-excerpt' => 'color: {{VALUE}};',
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
            echo '<div class="elementskey-loop-grid-empty">No items found.</div>';
            return;
        }

        $columns_desktop = ! empty( $settings['columns'] ) ? (int) $settings['columns'] : 3;
        $columns_tablet  = ! empty( $settings['columns_tablet'] ) ? (int) $settings['columns_tablet'] : 2;
        $columns_mobile  = ! empty( $settings['columns_mobile'] ) ? (int) $settings['columns_mobile'] : 1;

        $filter_taxonomy = ! empty( $settings['filter_taxonomy'] ) ? $settings['filter_taxonomy'] : 'category';
        $excerpt_length = ! empty( $settings['excerpt_length'] ) ? absint( $settings['excerpt_length'] ) : 14;
        $thumbnail_size = ! empty( $settings['thumbnail_size'] ) ? $settings['thumbnail_size'] : 'medium';
        $all_label = ! empty( $settings['all_filter_label'] ) ? $settings['all_filter_label'] : 'All';

        $include_cats = ! empty( $settings['include_cats'] ) ? $settings['include_cats'] : [];

        $filter_terms = [];
        if ( ! empty( $include_cats ) && 'category' === $filter_taxonomy ) {
            $filter_terms = get_terms( [
                'taxonomy' => $filter_taxonomy,
                'hide_empty' => true,
                'include' => $include_cats,
            ] );
        } else {
            $filter_terms = get_terms( [
                'taxonomy' => $filter_taxonomy,
                'hide_empty' => true,
            ] );
        }

        if ( is_wp_error( $filter_terms ) ) {
            $filter_terms = [];
        }

        $this->add_render_attribute( 'grid', 'class', 'elementskey-portfolio-grid' );
        $this->add_render_attribute( 'grid', 'style', "--elementskey-cols: {$columns_desktop}; --elementskey-cols-tablet: {$columns_tablet}; --elementskey-cols-mobile: {$columns_mobile};" );
        ?>
        <div class="elementskey-portfolio-widget">
        <?php if ( 'yes' === $settings['show_filter'] && ! empty( $filter_terms ) ) : ?>
            <div class="elementskey-portfolio-filter">
                <button type="button" class="elementskey-portfolio-filter-btn is-active" data-filter="*">
                    <?php echo esc_html( $all_label ); ?>
                </button>
                <?php foreach ( $filter_terms as $term ) : ?>
                    <button type="button" class="elementskey-portfolio-filter-btn" data-filter="<?php echo esc_attr( $term->slug ); ?>">
                        <?php echo esc_html( $term->name ); ?>
                    </button>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div <?php echo $this->get_render_attribute_string( 'grid' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Elementor render attributes are escaped internally. ?>>
            <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                <?php
                $post_id = get_the_ID();
                $card_terms = wp_get_post_terms( $post_id, $filter_taxonomy );
                $card_data = [];
                if ( ! is_wp_error( $card_terms ) ) {
                    foreach ( $card_terms as $term ) {
                        $card_data[] = $term->slug;
                    }
                }
                ?>
                <article class="elementskey-portfolio-card" data-terms="<?php echo esc_attr( implode( ' ', $card_data ) ); ?>">
                    <?php if ( 'yes' === $settings['show_thumbnail'] && has_post_thumbnail( $post_id ) ) : ?>
                        <a class="elementskey-portfolio-thumb" href="<?php the_permalink(); ?>">
                            <?php echo get_the_post_thumbnail( $post_id, $thumbnail_size ); ?>
                        </a>
                    <?php endif; ?>

                    <div class="elementskey-portfolio-body">
                        <?php if ( 'yes' === $settings['show_title'] ) : ?>
                            <h3 class="elementskey-portfolio-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                        <?php endif; ?>

                        <?php if ( 'yes' === $settings['show_excerpt'] ) : ?>
                            <p class="elementskey-portfolio-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), $excerpt_length ) ); ?></p>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
        </div>
        <?php

        wp_reset_postdata();
    }
}
