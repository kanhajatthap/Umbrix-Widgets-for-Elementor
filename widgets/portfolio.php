<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Portfolio_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_portfolio';
    }

    public function get_title() {
        return 'Portfolio';
    }

    public function get_icon() {
        return 'eicon-gallery-grid';
    }

    public function get_categories() {
        return [ 'elementstack-elements' ];
    }

    public function get_style_depends() {
        return [ 'bdea-content-style' ];
    }

    public function get_script_depends() {
        return [ 'bdea-content-script' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'bdea_query_section',
            [
                'label' => 'Query',
            ]
        );

        $this->add_control(
            'post_type',
            [
                'label' => 'Post Type',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'portfolio',
                'options' => bdea_widget_post_types(),
            ]
        );

        $this->add_control(
            'include_cats',
            [
                'label' => 'Categories',
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => bdea_widget_terms_list( 'category' ),
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
                'label' => 'Number of Items',
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 9,
                'min' => 1,
                'max' => 100,
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label' => 'Order By',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'date',
                'options' => [
                    'date' => 'Date',
                    'modified' => 'Modified Date',
                    'title' => 'Title',
                    'menu_order' => 'Menu Order',
                    'rand' => 'Random',
                ],
            ]
        );

        $this->add_control(
            'order',
            [
                'label' => 'Order',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'DESC',
                'options' => [
                    'ASC' => 'Ascending',
                    'DESC' => 'Descending',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_filter_section',
            [
                'label' => 'Filter',
            ]
        );

        $this->add_control(
            'show_filter',
            [
                'label' => 'Show Filter Bar',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'filter_taxonomy',
            [
                'label' => 'Filter Taxonomy',
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => bdea_widget_taxonomies(),
                'default' => 'category',
            ]
        );

        $this->add_control(
            'all_filter_label',
            [
                'label' => '"All" Button Label',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'All',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_layout_section',
            [
                'label' => 'Layout',
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label' => 'Columns',
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
                'label' => 'Column Gap',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 80 ] ],
                'default' => [ 'size' => 24, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-portfolio-grid' => 'column-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'row_gap',
            [
                'label' => 'Row Gap',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 80 ] ],
                'default' => [ 'size' => 24, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-portfolio-grid' => 'row-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_card_content_section',
            [
                'label' => 'Card Content',
            ]
        );

        $this->add_control(
            'show_thumbnail',
            [
                'label' => 'Show Thumbnail',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'thumbnail_size',
            [
                'label' => 'Thumbnail Size',
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'thumbnail' => 'Thumbnail',
                    'medium' => 'Medium',
                    'large' => 'Large',
                    'medium_large' => 'Medium Large',
                    'full' => 'Full',
                ],
                'default' => 'medium',
                'condition' => [ 'show_thumbnail' => 'yes' ],
            ]
        );

        $this->add_control(
            'show_title',
            [
                'label' => 'Show Title',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_excerpt',
            [
                'label' => 'Show Excerpt',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'excerpt_length',
            [
                'label' => 'Excerpt Length (words)',
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 14,
                'min' => 3,
                'max' => 150,
                'condition' => [ 'show_excerpt' => 'yes' ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_filter_style_section',
            [
                'label' => 'Filter Bar',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'filter_typography',
                'selector' => '{{WRAPPER}} .bdea-portfolio-filter-btn',
            ]
        );

        $this->add_responsive_control(
            'filter_align',
            [
                'label' => 'Alignment',
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [ 'title' => 'Left', 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => 'Center', 'icon' => 'eicon-text-align-center' ],
                    'right' => [ 'title' => 'Right', 'icon' => 'eicon-text-align-right' ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .bdea-portfolio-filter' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'filter_btn_tabs' );

        $this->start_controls_tab(
            'filter_btn_normal',
            [ 'label' => 'Normal' ]
        );

        $this->add_control(
            'filter_btn_color',
            [
                'label' => 'Text Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-portfolio-filter-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'filter_btn_bg',
            [
                'label' => 'Background',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-portfolio-filter-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'filter_btn_active',
            [ 'label' => 'Active' ]
        );

        $this->add_control(
            'filter_btn_active_color',
            [
                'label' => 'Text Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-portfolio-filter-btn.is-active' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'filter_btn_active_bg',
            [
                'label' => 'Background',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-portfolio-filter-btn.is-active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'filter_btn_border',
                'selector' => '{{WRAPPER}} .bdea-portfolio-filter-btn',
            ]
        );

        $this->add_responsive_control(
            'filter_btn_radius',
            [
                'label' => 'Border Radius',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-portfolio-filter-btn' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'filter_btn_gap',
            [
                'label' => 'Button Gap',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 40 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-portfolio-filter-btn' => 'margin: 0 {{SIZE}}{{UNIT}}/2 {{SIZE}}{{UNIT}} 0;',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_card_style_section',
            [
                'label' => 'Card',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'card_bg',
            [
                'label' => 'Background',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-portfolio-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'card_border',
                'selector' => '{{WRAPPER}} .bdea-portfolio-card',
            ]
        );

        $this->add_responsive_control(
            'card_radius',
            [
                'label' => 'Border Radius',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-portfolio-card' => 'border-radius: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .bdea-portfolio-thumb img' => 'border-radius: {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} 0 0;',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'card_shadow',
                'selector' => '{{WRAPPER}} .bdea-portfolio-card',
            ]
        );

        $this->add_responsive_control(
            'card_padding',
            [
                'label' => 'Padding',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-portfolio-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_image_style_section',
            [
                'label' => 'Thumbnail',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_height',
            [
                'label' => 'Height',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 80, 'max' => 700 ] ],
                'default' => [ 'size' => 200, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-portfolio-thumb img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'image_fit',
            [
                'label' => 'Object Fit',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'cover',
                'options' => [
                    'cover' => 'Cover',
                    'contain' => 'Contain',
                    'fill' => 'Fill',
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-portfolio-thumb img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_typography_section',
            [
                'label' => 'Typography',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .bdea-portfolio-title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => 'Title Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-portfolio-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'excerpt_typography',
                'selector' => '{{WRAPPER}} .bdea-portfolio-excerpt',
            ]
        );

        $this->add_control(
            'excerpt_color',
            [
                'label' => 'Excerpt Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-portfolio-excerpt' => 'color: {{VALUE}};',
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

        $query = new \WP_Query( bdea_widget_query_args( $settings ) );

        if ( ! $query->have_posts() ) {
            echo '<div class="bdea-loop-grid-empty">No items found.</div>';
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

        $this->add_render_attribute( 'grid', 'class', 'bdea-portfolio-grid' );
        $this->add_render_attribute( 'grid', 'style', "--bdea-cols: {$columns_desktop}; --bdea-cols-tablet: {$columns_tablet}; --bdea-cols-mobile: {$columns_mobile};" );
        ?>
        <div class="bdea-portfolio-widget">
        <?php if ( 'yes' === $settings['show_filter'] && ! empty( $filter_terms ) ) : ?>
            <div class="bdea-portfolio-filter">
                <button type="button" class="bdea-portfolio-filter-btn is-active" data-filter="*">
                    <?php echo esc_html( $all_label ); ?>
                </button>
                <?php foreach ( $filter_terms as $term ) : ?>
                    <button type="button" class="bdea-portfolio-filter-btn" data-filter="<?php echo esc_attr( $term->slug ); ?>">
                        <?php echo esc_html( $term->name ); ?>
                    </button>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div <?php echo $this->get_render_attribute_string( 'grid' ); ?>>
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
                <article class="bdea-portfolio-card" data-terms="<?php echo esc_attr( implode( ' ', $card_data ) ); ?>">
                    <?php if ( 'yes' === $settings['show_thumbnail'] && has_post_thumbnail( $post_id ) ) : ?>
                        <a class="bdea-portfolio-thumb" href="<?php the_permalink(); ?>">
                            <?php echo get_the_post_thumbnail( $post_id, $thumbnail_size ); ?>
                        </a>
                    <?php endif; ?>

                    <div class="bdea-portfolio-body">
                        <?php if ( 'yes' === $settings['show_title'] ) : ?>
                            <h3 class="bdea-portfolio-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                        <?php endif; ?>

                        <?php if ( 'yes' === $settings['show_excerpt'] ) : ?>
                            <p class="bdea-portfolio-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), $excerpt_length ) ); ?></p>
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