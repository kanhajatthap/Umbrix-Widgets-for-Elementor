<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Table_Of_Content_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_table_of_content';
    }

    public function get_title() {
        return 'Table Of Content';
    }

    public function get_icon() {
        return 'eicon-table-of-contents';
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
            'bdea_toc_section',
            [
                'label' => 'Table Of Content',
            ]
        );

        $this->add_control(
            'toc_title',
            [
                'label' => 'Title',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Table of Contents',
            ]
        );

        $this->add_control(
            'toc_heading',
            [
                'label' => 'Heading to Include',
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => [
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                ],
                'default' => [ 'h2' ],
            ]
        );

        $this->add_control(
            'toc_selector',
            [
                'label' => 'Heading Selector',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '.entry-content h2, h2',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_toc_title_style',
            [
                'label' => 'Title',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'toc_title_typography',
                'selector' => '{{WRAPPER}} .bdea-toc-title',
            ]
        );

        $this->add_control(
            'toc_title_color',
            [
                'label' => 'Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .bdea-toc-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_toc_item_style',
            [
                'label' => 'Items',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'toc_item_typography',
                'selector' => '{{WRAPPER}} .bdea-toc-list a',
            ]
        );

        $this->add_control(
            'toc_item_color',
            [
                'label' => 'Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4b5563',
                'selectors' => [
                    '{{WRAPPER}} .bdea-toc-list a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'toc_item_hover_color',
            [
                'label' => 'Hover Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .bdea-toc-list a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'toc_item_padding',
            [
                'label' => 'Item Padding',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-toc-list li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'toc_item_border_radius',
            [
                'label' => 'Item Border Radius',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-toc-list a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'toc_item_spacing',
            [
                'label' => 'Item Spacing',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 20 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-toc-list li + li' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_toc_wrapper_style',
            [
                'label' => 'Wrapper',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'toc_wrapper_bg',
            [
                'label' => 'Background',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-toc' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'toc_wrapper_padding',
            [
                'label' => 'Padding',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-toc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'toc_wrapper_border',
                'selector' => '{{WRAPPER}} .bdea-toc',
            ]
        );

        $this->add_responsive_control(
            'toc_wrapper_border_radius',
            [
                'label' => 'Border Radius',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-toc' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'toc_wrapper_shadow',
                'selector' => '{{WRAPPER}} .bdea-toc',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $title    = ! empty( $settings['toc_title'] ) ? $settings['toc_title'] : '';
        $headings = ! empty( $settings['toc_heading'] ) ? (array) $settings['toc_heading'] : [ 'h2' ];
        $headings = array_map( 'sanitize_text_field', $headings );
        $selector = ! empty( $settings['toc_selector'] ) ? $settings['toc_selector'] : '.entry-content h2, h2';
        ?>
        <div class="bdea-toc"
             data-headings="<?php echo esc_attr( implode( ',', $headings ) ); ?>"
             data-selector="<?php echo esc_attr( $selector ); ?>">
            <?php if ( $title ) : ?>
                <div class="bdea-toc-title"><?php echo esc_html( $title ); ?></div>
            <?php endif; ?>
            <ul class="bdea-toc-list"></ul>
        </div>
        <?php
    }
}