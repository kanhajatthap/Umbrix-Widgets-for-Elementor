<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Loop_Grid_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_loop_grid';
    }

    public function get_title() {
        return 'Loop Grid';
    }

    public function get_icon() {
        return 'eicon-posts-grid';
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
            'bdea_loop_template_section',
            [
                'label' => 'Loop Template',
            ]
        );

        $this->add_control(
            'loop_template',
            [
                'label' => 'Select Loop Template',
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => bdea_loop_template_options(),
                'description' => 'Select an Elementor template to render each loop item. Leave empty to use the built-in card below.',
            ]
        );

        $this->add_control(
            'post_type',
            [
                'label' => 'Post Type',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'post',
                'options' => bdea_widget_post_types(),
                'description' => 'Choose the post type this loop should query.',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_query_section',
            [
                'label' => 'Query',
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
            'include_tags',
            [
                'label' => 'Tags',
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => bdea_widget_terms_list( 'post_tag' ),
                'description' => 'Leave empty for all tags.',
            ]
        );

        $this->add_control(
            'exclude_cats',
            [
                'label' => 'Exclude Categories',
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => bdea_widget_terms_list( 'category' ),
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
                'label' => 'Posts Per Page',
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 6,
                'min' => 1,
                'max' => 100,
            ]
        );

        $this->add_control(
            'offset',
            [
                'label' => 'Offset',
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 0,
                'min' => 0,
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
                    'comment_count' => 'Comment Count',
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
            'bdea_layout_section',
            [
                'label' => 'Layout',
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label' => 'Columns',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '3',
                'tablet_default' => '2',
                'mobile_default' => '1',
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-loop-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
                ],
            ]
        );

        $this->add_responsive_control(
            'column_gap',
            [
                'label' => 'Column Gap',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 100 ],
                ],
                'default' => [ 'size' => 24, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-loop-grid' => 'column-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'row_gap',
            [
                'label' => 'Row Gap',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 100 ],
                ],
                'default' => [ 'size' => 24, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-loop-grid' => 'row-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_pagination_section',
            [
                'label' => 'Pagination',
            ]
        );

        $this->add_control(
            'pagination_type',
            [
                'label' => 'Pagination Type',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none'            => 'None',
                    'numbers'         => 'Numbers',
                    'prev_next'       => 'Previous / Next',
                    'load_more'       => 'Load on Demand',
                    'infinite_scroll' => 'Infinite Scroll',
                ],
            ]
        );

        $this->add_control(
            'load_more_text',
            [
                'label' => 'Load More Text',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Load More',
                'condition' => [ 'pagination_type' => 'load_more' ],
            ]
        );

        $this->add_control(
            'prev_text',
            [
                'label' => 'Previous Text',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Previous',
                'condition' => [ 'pagination_type' => [ 'numbers', 'prev_next' ] ],
            ]
        );

        $this->add_control(
            'next_text',
            [
                'label' => 'Next Text',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Next',
                'condition' => [ 'pagination_type' => [ 'numbers', 'prev_next' ] ],
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
            'show_title',
            [
                'label' => 'Show Title',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label' => 'Title Tag',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'h3',
                'options' => [
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'div' => 'DIV',
                ],
                'condition' => [ 'show_title' => 'yes' ],
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
                'default' => 18,
                'min' => 3,
                'max' => 120,
                'condition' => [ 'show_excerpt' => 'yes' ],
            ]
        );

        $this->add_control(
            'show_meta',
            [
                'label' => 'Show Meta (date / category)',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_read_more',
            [
                'label' => 'Show Read More',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'read_more_text',
            [
                'label' => 'Read More Text',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Read More',
                'condition' => [ 'show_read_more' => 'yes' ],
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
                    '{{WRAPPER}} .bdea-loop-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'card_border',
                'selector' => '{{WRAPPER}} .bdea-loop-card',
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
                    '{{WRAPPER}} .bdea-loop-card' => 'border-radius: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .bdea-loop-thumb img' => 'border-radius: {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} 0 0;',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'card_shadow',
                'selector' => '{{WRAPPER}} .bdea-loop-card',
            ]
        );

        $this->add_responsive_control(
            'card_padding',
            [
                'label' => 'Padding',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-loop-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'default' => [ 'size' => 220, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-loop-thumb img' => 'height: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .bdea-loop-thumb img' => 'object-fit: {{VALUE}};',
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
                'selector' => '{{WRAPPER}} .bdea-loop-title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => 'Title Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-loop-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'excerpt_typography',
                'selector' => '{{WRAPPER}} .bdea-loop-excerpt',
            ]
        );

        $this->add_control(
            'excerpt_color',
            [
                'label' => 'Excerpt Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-loop-excerpt' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'meta_typography',
                'selector' => '{{WRAPPER}} .bdea-loop-meta',
            ]
        );

        $this->add_control(
            'meta_color',
            [
                'label' => 'Meta Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-loop-meta' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .bdea-loop-more',
            ]
        );

        $this->add_control(
            'button_color',
            [
                'label' => 'Button Text Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-loop-more' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_bg',
            [
                'label' => 'Button Background',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-loop-more' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_pagination_style_section',
            [
                'label' => 'Pagination',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'pagination_align',
            [
                'label' => 'Alignment',
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left'   => [ 'title' => 'Left', 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => 'Center', 'icon' => 'eicon-text-align-center' ],
                    'right'  => [ 'title' => 'Right', 'icon' => 'eicon-text-align-right' ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .bdea-loop-pagination' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'pagination_color',
            [
                'label' => 'Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-loop-pagination .page-numbers' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .bdea-loop-load-more' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'pagination_bg',
            [
                'label' => 'Background',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-loop-pagination .page-numbers' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .bdea-loop-load-more' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'pagination_active_color',
            [
                'label' => 'Active / Hover Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-loop-pagination .page-numbers.current' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .bdea-loop-pagination .page-numbers:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .bdea-loop-load-more:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'pagination_active_bg',
            [
                'label' => 'Active / Hover Background',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-loop-pagination .page-numbers.current' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .bdea-loop-pagination .page-numbers:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .bdea-loop-load-more:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_radius',
            [
                'label' => 'Border Radius',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
                'default' => [ 'size' => 6, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-loop-pagination .page-numbers' => 'border-radius: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .bdea-loop-load-more' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_gap',
            [
                'label' => 'Spacing',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 40 ] ],
                'default' => [ 'size' => 4, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-loop-pagination' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( empty( $settings['loop_template'] ) && \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
            ?>
            <div
                class="bdea-loop-template-prompt"
                data-bdea-ajaxurl="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>"
                data-bdea-nonce="<?php echo esc_attr( wp_create_nonce( 'bdea_editor' ) ); ?>"
            >
                <p class="bdea-loop-template-prompt-title">This loop has no template yet.</p>
                <p class="bdea-loop-template-prompt-note">Create a template to design how each post is displayed. Frontend keeps using the built-in card until one is selected.</p>
                <button type="button" class="bdea-create-loop-template">Create Template</button>
            </div>
            <?php
            return;
        }

        if ( ! empty( $settings['exclude_ids'] ) ) {
            $settings['exclude_ids'] = array_map( 'trim', explode( ',', $settings['exclude_ids'] ) );
        }

        $pagination_type = ! empty( $settings['pagination_type'] ) ? $settings['pagination_type'] : 'none';
        $current_page    = 1;
        $widget_id       = $this->get_id();

        if ( in_array( $pagination_type, [ 'numbers', 'prev_next' ], true ) ) {
            $param        = 'bdea_page_' . $widget_id;
            $current_page = isset( $_GET[ $param ] ) ? max( 1, absint( wp_unslash( $_GET[ $param ] ) ) ) : 1;
        }

        $query_args = bdea_widget_query_args( $settings );

        if ( 'none' !== $pagination_type ) {
            if ( ! empty( $query_args['offset'] ) ) {
                $query_args['offset'] = $query_args['offset'] + ( $current_page - 1 ) * $query_args['posts_per_page'];
            } else {
                $query_args['paged'] = $current_page;
            }
        }

        $query = new \WP_Query( $query_args );

        if ( ! $query->have_posts() ) {
            echo '<div class="bdea-loop-grid-empty">No posts found.</div>';
            return;
        }

        $template_id = ! empty( $settings['loop_template'] ) ? absint( $settings['loop_template'] ) : 0;
        $grid_class  = 'bdea-loop-grid' . ( $template_id ? ' bdea-loop-grid-template' : '' );
        ?>
        <div class="<?php echo esc_attr( $grid_class ); ?>">
            <?php bdea_render_loop_items( $query, $settings ); ?>
        </div>
        <?php

        $total_pages = (int) $query->max_num_pages;

        if ( 'none' === $pagination_type || $total_pages <= 1 ) {
            return;
        }

        $prev_text = ! empty( $settings['prev_text'] ) ? $settings['prev_text'] : 'Previous';
        $next_text = ! empty( $settings['next_text'] ) ? $settings['next_text'] : 'Next';

        if ( 'numbers' === $pagination_type || 'prev_next' === $pagination_type ) {
            $base = remove_query_arg( 'bdea_page_' . $widget_id );
            ?>
            <nav class="bdea-loop-pagination bdea-loop-pagination-<?php echo esc_attr( $pagination_type ); ?>" aria-label="Pagination">
                <?php if ( $current_page > 1 ) : ?>
                    <a class="page-numbers prev" href="<?php echo esc_url( add_query_arg( 'bdea_page_' . $widget_id, $current_page - 1, $base ) ); ?>">
                        <?php echo esc_html( $prev_text ); ?>
                    </a>
                <?php endif; ?>

                <?php if ( 'numbers' === $pagination_type ) : ?>
                    <?php foreach ( range( 1, $total_pages ) as $page_num ) : ?>
                        <?php if ( $page_num === $current_page ) : ?>
                            <span class="page-numbers current" aria-current="page"><?php echo esc_html( $page_num ); ?></span>
                        <?php else : ?>
                            <a class="page-numbers" href="<?php echo esc_url( add_query_arg( 'bdea_page_' . $widget_id, $page_num, $base ) ); ?>">
                                <?php echo esc_html( $page_num ); ?>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>

                <?php if ( $current_page < $total_pages ) : ?>
                    <a class="page-numbers next" href="<?php echo esc_url( add_query_arg( 'bdea_page_' . $widget_id, $current_page + 1, $base ) ); ?>">
                        <?php echo esc_html( $next_text ); ?>
                    </a>
                <?php endif; ?>
            </nav>
            <?php
            return;
        }

        if ( 'load_more' === $pagination_type || 'infinite_scroll' === $pagination_type ) {
            $ajax_settings = [
                'post_type'      => ! empty( $settings['post_type'] ) ? $settings['post_type'] : 'post',
                'posts_per_page' => ! empty( $settings['posts_per_page'] ) ? absint( $settings['posts_per_page'] ) : 6,
                'pagination_type' => $pagination_type,
                'orderby'        => ! empty( $settings['orderby'] ) ? $settings['orderby'] : 'date',
                'order'          => ! empty( $settings['order'] ) ? $settings['order'] : 'DESC',
                'offset'         => ! empty( $settings['offset'] ) ? absint( $settings['offset'] ) : 0,
                'exclude_ids'    => isset( $settings['exclude_ids'] ) ? implode( ',', array_map( 'intval', (array) $settings['exclude_ids'] ) ) : '',
                'include_cats'   => isset( $settings['include_cats'] ) ? array_map( 'intval', (array) $settings['include_cats'] ) : [],
                'include_tags'   => isset( $settings['include_tags'] ) ? array_map( 'intval', (array) $settings['include_tags'] ) : [],
                'exclude_cats'   => isset( $settings['exclude_cats'] ) ? array_map( 'intval', (array) $settings['exclude_cats'] ) : [],
                'loop_template'  => $template_id,
                'excerpt_length' => ! empty( $settings['excerpt_length'] ) ? absint( $settings['excerpt_length'] ) : 18,
                'show_thumbnail' => ! empty( $settings['show_thumbnail'] ) ? $settings['show_thumbnail'] : 'yes',
                'show_title'     => ! empty( $settings['show_title'] ) ? $settings['show_title'] : 'yes',
                'title_tag'      => ! empty( $settings['title_tag'] ) ? $settings['title_tag'] : 'h3',
                'show_excerpt'   => ! empty( $settings['show_excerpt'] ) ? $settings['show_excerpt'] : 'yes',
                'show_meta'      => ! empty( $settings['show_meta'] ) ? $settings['show_meta'] : 'yes',
                'show_read_more' => ! empty( $settings['show_read_more'] ) ? $settings['show_read_more'] : 'yes',
                'read_more_text' => ! empty( $settings['read_more_text'] ) ? $settings['read_more_text'] : 'Read More',
            ];
            ?>
            <nav
                class="bdea-loop-pagination bdea-loop-pagination-<?php echo esc_attr( $pagination_type ); ?>"
                data-bdea-ajax="<?php echo esc_attr( $pagination_type ); ?>"
                data-bdea-ajaxurl="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>"
                data-bdea-page="1"
                data-bdea-max="<?php echo esc_attr( $total_pages ); ?>"
                data-bdea-nonce="<?php echo esc_attr( wp_create_nonce( 'bdea_loop_load' ) ); ?>"
                data-bdea-settings="<?php echo esc_attr( wp_json_encode( $ajax_settings ) ); ?>"
            >
                <button type="button" class="bdea-loop-load-more">
                    <?php echo esc_html( ! empty( $settings['load_more_text'] ) ? $settings['load_more_text'] : 'Load More' ); ?>
                </button>
                <span class="bdea-loop-loading" aria-hidden="true">Loading...</span>
            </nav>
            <?php
        }
    }
}
