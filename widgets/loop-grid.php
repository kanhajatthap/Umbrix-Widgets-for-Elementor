<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class ELEMENTSKEY_Loop_Grid_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'elementskey_loop_grid';
    }

    public function get_title() {
        return 'Loop Grid';
    }

    public function get_icon() {
        return 'eicon-posts-grid';
    }

    public function get_categories() {
        return [ 'elementskey-elements' ];
    }

    public function get_style_depends() {
        return [ 'elementskey-content-style' ];
    }

    public function get_script_depends() {
        return [ 'elementskey-content-script' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'elementskey_loop_template_section',
            [
                'label' => __( 'Loop Template', 'elementskey' ),
            ]
        );

        $this->add_control(
            'loop_template',
            [
                'label' => __( 'Select Loop Template', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => elementskey_loop_template_options(),
                'description' => 'Select an Elementor template to render each loop item. Leave empty to use the built-in card below.',
            ]
        );

        $this->add_control(
            'post_type',
            [
                'label' => __( 'Post Type', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'post',
                'options' => elementskey_widget_post_types(),
                'description' => 'Choose the post type this loop should query.',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_query_section',
            [
                'label' => __( 'Query', 'elementskey' ),
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
            'exclude_cats',
            [
                'label' => __( 'Exclude Categories', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => elementskey_widget_terms_list( 'category' ),
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
                'label' => __( 'Posts Per Page', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 6,
                'min' => 1,
                'max' => 100,
            ]
        );

        $this->add_control(
            'offset',
            [
                'label' => __( 'Offset', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 0,
                'min' => 0,
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
                    'comment_count' => __( 'Comment Count', 'elementskey' ),
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

        $this->add_responsive_control(
            'columns',
            [
                'label' => __( 'Columns', 'elementskey' ),
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
                    '{{WRAPPER}} .elementskey-loop-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
                ],
            ]
        );

        $this->add_responsive_control(
            'column_gap',
            [
                'label' => __( 'Column Gap', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 100 ],
                ],
                'default' => [ 'size' => 24, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-loop-grid' => 'column-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'row_gap',
            [
                'label' => __( 'Row Gap', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 100 ],
                ],
                'default' => [ 'size' => 24, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-loop-grid' => 'row-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_pagination_section',
            [
                'label' => __( 'Pagination', 'elementskey' ),
            ]
        );

        $this->add_control(
            'pagination_type',
            [
                'label' => __( 'Pagination Type', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none'            => 'None',
                    'numbers'         => __( 'Numbers', 'elementskey' ),
                    'prev_next'       => __( 'Previous / Next', 'elementskey' ),
                    'load_more'       => __( 'Load on Demand', 'elementskey' ),
                    'infinite_scroll' => __( 'Infinite Scroll', 'elementskey' ),
                ],
            ]
        );

        $this->add_control(
            'load_more_text',
            [
                'label' => __( 'Load More Text', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Load More', 'elementskey' ),
                'condition' => [ 'pagination_type' => 'load_more' ],
            ]
        );

        $this->add_control(
            'prev_text',
            [
                'label' => __( 'Previous Text', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Previous', 'elementskey' ),
                'condition' => [ 'pagination_type' => [ 'numbers', 'prev_next' ] ],
            ]
        );

        $this->add_control(
            'next_text',
            [
                'label' => __( 'Next Text', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Next', 'elementskey' ),
                'condition' => [ 'pagination_type' => [ 'numbers', 'prev_next' ] ],
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
            'show_title',
            [
                'label' => __( 'Show Title', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label' => __( 'Title Tag', 'elementskey' ),
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
                'max' => 120,
                'condition' => [ 'show_excerpt' => 'yes' ],
            ]
        );

        $this->add_control(
            'show_meta',
            [
                'label' => __( 'Show Meta (date / category)', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
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
                'selectors' => [
                    '{{WRAPPER}} .elementskey-loop-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'card_border',
                'selector' => '{{WRAPPER}} .elementskey-loop-card',
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
                    '{{WRAPPER}} .elementskey-loop-card' => 'border-radius: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementskey-loop-thumb img' => 'border-radius: {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} 0 0;',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'card_shadow',
                'selector' => '{{WRAPPER}} .elementskey-loop-card',
            ]
        );

        $this->add_responsive_control(
            'card_padding',
            [
                'label' => __( 'Padding', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-loop-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'default' => [ 'size' => 220, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-loop-thumb img' => 'height: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .elementskey-loop-thumb img' => 'object-fit: {{VALUE}};',
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
                'selector' => '{{WRAPPER}} .elementskey-loop-title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Title Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-loop-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'excerpt_typography',
                'selector' => '{{WRAPPER}} .elementskey-loop-excerpt',
            ]
        );

        $this->add_control(
            'excerpt_color',
            [
                'label' => __( 'Excerpt Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-loop-excerpt' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'meta_typography',
                'selector' => '{{WRAPPER}} .elementskey-loop-meta',
            ]
        );

        $this->add_control(
            'meta_color',
            [
                'label' => __( 'Meta Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-loop-meta' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .elementskey-loop-more',
            ]
        );

        $this->add_control(
            'button_color',
            [
                'label' => __( 'Button Text Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-loop-more' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_bg',
            [
                'label' => __( 'Button Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-loop-more' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_pagination_style_section',
            [
                'label' => __( 'Pagination', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'pagination_align',
            [
                'label' => __( 'Alignment', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left'   => [ 'title' => __( 'Left', 'elementskey' ), 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => __( 'Center', 'elementskey' ), 'icon' => 'eicon-text-align-center' ],
                    'right'  => [ 'title' => __( 'Right', 'elementskey' ), 'icon' => 'eicon-text-align-right' ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-loop-pagination' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'pagination_color',
            [
                'label' => __( 'Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-loop-pagination .page-numbers' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementskey-loop-load-more' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'pagination_bg',
            [
                'label' => __( 'Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-loop-pagination .page-numbers' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .elementskey-loop-load-more' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'pagination_active_color',
            [
                'label' => __( 'Active / Hover Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-loop-pagination .page-numbers.current' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementskey-loop-pagination .page-numbers:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementskey-loop-load-more:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'pagination_active_bg',
            [
                'label' => __( 'Active / Hover Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-loop-pagination .page-numbers.current' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .elementskey-loop-pagination .page-numbers:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .elementskey-loop-load-more:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_radius',
            [
                'label' => __( 'Border Radius', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
                'default' => [ 'size' => 6, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-loop-pagination .page-numbers' => 'border-radius: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementskey-loop-load-more' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_gap',
            [
                'label' => __( 'Spacing', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 40 ] ],
                'default' => [ 'size' => 4, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-loop-pagination' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_template_prompt_style_section',
            [
                'label' => __( 'Template Prompt', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'prompt_border_color',
            [
                'label' => __( 'Border Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#cbd5e1',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-loop-template-prompt' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'prompt_bg',
            [
                'label' => __( 'Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#f8fafc',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-loop-template-prompt' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'prompt_title_color',
            [
                'label' => __( 'Title Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-loop-template-prompt-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'prompt_note_color',
            [
                'label' => __( 'Note Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#6b7280',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-loop-template-prompt-note' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'create_btn_bg',
            [
                'label' => __( 'Button Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-create-loop-template' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'create_btn_hover_bg',
            [
                'label' => __( 'Button Hover Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#3549c9',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-create-loop-template:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'create_btn_color',
            [
                'label' => __( 'Button Text Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-create-loop-template' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'loading_text_color',
            [
                'label' => __( 'Loading Text Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#6b7280',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-loop-loading' => 'color: {{VALUE}};',
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
                class="elementskey-loop-template-prompt"
                data-elementskey-ajaxurl="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>"
                data-elementskey-nonce="<?php echo esc_attr( wp_create_nonce( 'elementskey_editor' ) ); ?>"
            >
                <p class="elementskey-loop-template-prompt-title">This loop has no template yet.</p>
                <p class="elementskey-loop-template-prompt-note">Create a template to design how each post is displayed. Frontend keeps using the built-in card until one is selected.</p>
                <button type="button" class="elementskey-create-loop-template">Create Template</button>
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
            $param        = 'elementskey_page_' . $widget_id;
            $current_page = isset( $_GET[ $param ] ) ? max( 1, absint( wp_unslash( $_GET[ $param ] ) ) ) : 1; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Pagination GET parameter, read-only.
        }

        $query_args = elementskey_widget_query_args( $settings );

        if ( 'none' !== $pagination_type ) {
            if ( ! empty( $query_args['offset'] ) ) {
                $query_args['offset'] = $query_args['offset'] + ( $current_page - 1 ) * $query_args['posts_per_page'];
            } else {
                $query_args['paged'] = $current_page;
            }
        }

        $query = new \WP_Query( $query_args );

        if ( ! $query->have_posts() ) {
            echo '<div class="elementskey-loop-grid-empty">No posts found.</div>';
            return;
        }

        $template_id = ! empty( $settings['loop_template'] ) ? absint( $settings['loop_template'] ) : 0;
        $grid_class  = 'elementskey-loop-grid' . ( $template_id ? ' elementskey-loop-grid-template' : '' );
        ?>
        <div class="<?php echo esc_attr( $grid_class ); ?>">
            <?php elementskey_render_loop_items( $query, $settings ); ?>
        </div>
        <?php

        $total_pages = (int) $query->max_num_pages;

        if ( 'none' === $pagination_type || $total_pages <= 1 ) {
            return;
        }

        $prev_text = ! empty( $settings['prev_text'] ) ? $settings['prev_text'] : 'Previous';
        $next_text = ! empty( $settings['next_text'] ) ? $settings['next_text'] : 'Next';

        if ( 'numbers' === $pagination_type || 'prev_next' === $pagination_type ) {
            $base = remove_query_arg( 'elementskey_page_' . $widget_id );
            ?>
            <nav class="elementskey-loop-pagination elementskey-loop-pagination-<?php echo esc_attr( $pagination_type ); ?>" aria-label="Pagination">
                <?php if ( $current_page > 1 ) : ?>
                    <a class="page-numbers prev" href="<?php echo esc_url( add_query_arg( 'elementskey_page_' . $widget_id, $current_page - 1, $base ) ); ?>">
                        <?php echo esc_html( $prev_text ); ?>
                    </a>
                <?php endif; ?>

                <?php if ( 'numbers' === $pagination_type ) : ?>
                    <?php foreach ( range( 1, $total_pages ) as $page_num ) : ?>
                        <?php if ( $page_num === $current_page ) : ?>
                            <span class="page-numbers current" aria-current="page"><?php echo esc_html( $page_num ); ?></span>
                        <?php else : ?>
                            <a class="page-numbers" href="<?php echo esc_url( add_query_arg( 'elementskey_page_' . $widget_id, $page_num, $base ) ); ?>">
                                <?php echo esc_html( $page_num ); ?>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>

                <?php if ( $current_page < $total_pages ) : ?>
                    <a class="page-numbers next" href="<?php echo esc_url( add_query_arg( 'elementskey_page_' . $widget_id, $current_page + 1, $base ) ); ?>">
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
                class="elementskey-loop-pagination elementskey-loop-pagination-<?php echo esc_attr( $pagination_type ); ?>"
                data-elementskey-ajax="<?php echo esc_attr( $pagination_type ); ?>"
                data-elementskey-ajaxurl="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>"
                data-elementskey-page="1"
                data-elementskey-max="<?php echo esc_attr( $total_pages ); ?>"
                data-elementskey-nonce="<?php echo esc_attr( wp_create_nonce( 'elementskey_loop_load' ) ); ?>"
                data-elementskey-settings="<?php echo esc_attr( wp_json_encode( $ajax_settings ) ); ?>"
            >
                <button type="button" class="elementskey-loop-load-more">
                    <?php echo esc_html( ! empty( $settings['load_more_text'] ) ? $settings['load_more_text'] : 'Load More' ); ?>
                </button>
                <span class="elementskey-loop-loading" aria-hidden="true">Loading...</span>
            </nav>
            <?php
        }
    }
}
