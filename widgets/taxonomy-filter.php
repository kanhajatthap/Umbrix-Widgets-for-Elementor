<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Taxonomy_Filter_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_taxonomy_filter';
    }

    public function get_title() {
        return 'Taxonomy Filter';
    }

    public function get_icon() {
        return 'eicon-filter';
    }

    public function get_categories() {
        return [ 'elementstack-elements' ];
    }

    public function get_style_depends() {
        return [ 'bdea-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'bdea_taxonomy_filter_section',
            [
                'label' => 'Taxonomy Filter',
            ]
        );

        $this->add_control(
            'filter_taxonomy',
            [
                'label' => 'Taxonomy',
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => bdea_widget_taxonomies(),
                'default' => 'category',
            ]
        );

        $this->add_control(
            'filter_layout',
            [
                'label' => 'Layout',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'buttons',
                'options' => [
                    'buttons' => 'Buttons',
                    'dropdown' => 'Dropdown',
                ],
            ]
        );

        $this->add_control(
            'filter_show_count',
            [
                'label' => 'Show Term Count',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'filter_all_label',
            [
                'label' => '"All" Label',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'All',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_taxonomy_filter_style',
            [
                'label' => 'Style',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'filter_active_bg',
            [
                'label' => 'Active Background',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .bdea-tax-filter .is-active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'filter_active_color',
            [
                'label' => 'Active Text Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .bdea-tax-filter .is-active' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'filter_color',
            [
                'label' => 'Text Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .bdea-tax-filter a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'filter_hover_color',
            [
                'label' => 'Hover Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-tax-filter a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'filter_hover_bg',
            [
                'label' => 'Hover Background',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-tax-filter a:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'filter_typography',
                'selector' => '{{WRAPPER}} .bdea-tax-filter a',
            ]
        );

        $this->add_responsive_control(
            'filter_padding',
            [
                'label' => 'Item Padding',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-tax-filter a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'filter_border',
                'selector' => '{{WRAPPER}} .bdea-tax-filter',
            ]
        );

        $this->add_responsive_control(
            'filter_border_radius',
            [
                'label' => 'Border Radius',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-tax-filter' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .bdea-tax-filter a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'filter_gap',
            [
                'label' => 'Item Spacing',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 30 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-tax-filter a' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $taxonomy = ! empty( $settings['filter_taxonomy'] ) ? $settings['filter_taxonomy'] : 'category';

        $terms = get_terms( [
            'taxonomy'   => $taxonomy,
            'hide_empty' => false,
        ] );

        if ( is_wp_error( $terms ) || empty( $terms ) ) {
            ?>
            <div class="bdea-loop-grid-empty">No terms found for this taxonomy.</div>
            <?php
            return;
        }

        $all_label = ! empty( $settings['filter_all_label'] ) ? $settings['filter_all_label'] : 'All';
        $current   = get_queried_object();
        $current_id = ( $current instanceof \WP_Term ) ? $current->term_id : 0;

        if ( 'dropdown' === $settings['filter_layout'] ) {
            ?>
            <div class="bdea-tax-filter bdea-tax-filter-dropdown">
                <select class="bdea-tax-filter-select" onchange="if (this.value) location.href=this.value;">
                    <option value="<?php echo esc_url( get_post_type_archive_link( 'post' ) ?: home_url( '/' ) ); ?>"><?php echo esc_html( $all_label ); ?></option>
                    <?php foreach ( $terms as $term ) : ?>
                        <option value="<?php echo esc_url( get_term_link( $term ) ); ?>" <?php selected( $current_id, $term->term_id ); ?>>
                            <?php echo esc_html( $term->name ); ?>
                            <?php if ( 'yes' === $settings['filter_show_count'] ) : ?>
                                (<?php echo esc_html( $term->count ); ?>)
                            <?php endif; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php
            return;
        }
        ?>
        <div class="bdea-tax-filter">
            <a class="bdea-tax-filter-item<?php echo 0 === $current_id ? ' is-active' : ''; ?>"
               href="<?php echo esc_url( get_post_type_archive_link( 'post' ) ?: home_url( '/' ) ); ?>">
                <?php echo esc_html( $all_label ); ?>
            </a>
            <?php foreach ( $terms as $term ) : ?>
                <a class="bdea-tax-filter-item<?php echo $current_id === $term->term_id ? ' is-active' : ''; ?>"
                   href="<?php echo esc_url( get_term_link( $term ) ); ?>">
                    <?php echo esc_html( $term->name ); ?>
                    <?php if ( 'yes' === $settings['filter_show_count'] ) : ?>
                        <span class="bdea-tax-filter-count">(<?php echo esc_html( $term->count ); ?>)</span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </div>
        <?php
    }
}
