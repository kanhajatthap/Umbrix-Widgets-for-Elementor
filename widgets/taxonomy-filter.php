<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class ELEMENTSKEY_Taxonomy_Filter_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'elementskey_taxonomy_filter';
    }

    public function get_title() {
        return 'Taxonomy Filter';
    }

    public function get_icon() {
        return 'eicon-filter';
    }

    public function get_categories() {
        return [ 'elementskey-elements' ];
    }

    public function get_style_depends() {
        return [ 'elementskey-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'elementskey_taxonomy_filter_section',
            [
                'label' => __( 'Taxonomy Filter', 'elementskey' ),
            ]
        );

        $this->add_control(
            'filter_taxonomy',
            [
                'label' => __( 'Taxonomy', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => elementskey_widget_taxonomies(),
                'default' => 'category',
            ]
        );

        $this->add_control(
            'filter_layout',
            [
                'label' => __( 'Layout', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'buttons',
                'options' => [
                    'buttons' => __( 'Buttons', 'elementskey' ),
                    'dropdown' => __( 'Dropdown', 'elementskey' ),
                ],
            ]
        );

        $this->add_control(
            'filter_show_count',
            [
                'label' => __( 'Show Term Count', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'filter_all_label',
            [
                'label' => '"All" Label',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'All', 'elementskey' ),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_taxonomy_filter_style',
            [
                'label' => __( 'Style', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'filter_active_bg',
            [
                'label' => __( 'Active Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-tax-filter .is-active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'filter_active_color',
            [
                'label' => __( 'Active Text Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-tax-filter .is-active' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'filter_color',
            [
                'label' => __( 'Text Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-tax-filter a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'filter_hover_color',
            [
                'label' => __( 'Hover Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-tax-filter a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'filter_hover_bg',
            [
                'label' => __( 'Hover Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-tax-filter a:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'select_border_color',
            [
                'label' => __( 'Select Border Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#d1d5db',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-tax-filter-select' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'filter_typography',
                'selector' => '{{WRAPPER}} .elementskey-tax-filter a',
            ]
        );

        $this->add_responsive_control(
            'filter_padding',
            [
                'label' => __( 'Item Padding', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-tax-filter a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'filter_border',
                'selector' => '{{WRAPPER}} .elementskey-tax-filter',
            ]
        );

        $this->add_responsive_control(
            'filter_border_radius',
            [
                'label' => __( 'Border Radius', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-tax-filter' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .elementskey-tax-filter a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'filter_gap',
            [
                'label' => __( 'Item Spacing', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 30 ] ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-tax-filter a' => 'margin-right: {{SIZE}}{{UNIT}};',
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
            <div class="elementskey-loop-grid-empty">No terms found for this taxonomy.</div>
            <?php
            return;
        }

        $all_label = ! empty( $settings['filter_all_label'] ) ? $settings['filter_all_label'] : 'All';
        $current   = get_queried_object();
        $current_id = ( $current instanceof \WP_Term ) ? $current->term_id : 0;

        if ( 'dropdown' === $settings['filter_layout'] ) {
            ?>
            <div class="elementskey-tax-filter elementskey-tax-filter-dropdown">
                <select class="elementskey-tax-filter-select" onchange="if (this.value) location.href=this.value;">
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
        <div class="elementskey-tax-filter">
            <a class="elementskey-tax-filter-item<?php echo 0 === $current_id ? ' is-active' : ''; ?>"
               href="<?php echo esc_url( get_post_type_archive_link( 'post' ) ?: home_url( '/' ) ); ?>">
                <?php echo esc_html( $all_label ); ?>
            </a>
            <?php foreach ( $terms as $term ) : ?>
                <a class="elementskey-tax-filter-item<?php echo $current_id === $term->term_id ? ' is-active' : ''; ?>"
                   href="<?php echo esc_url( get_term_link( $term ) ); ?>">
                    <?php echo esc_html( $term->name ); ?>
                    <?php if ( 'yes' === $settings['filter_show_count'] ) : ?>
                        <span class="elementskey-tax-filter-count">(<?php echo esc_html( $term->count ); ?>)</span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </div>
        <?php
    }
}
