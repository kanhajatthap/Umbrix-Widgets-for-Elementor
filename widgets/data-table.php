<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Data_Table_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_data_table';
    }

    public function get_title() {
        return 'Data Table';
    }

    public function get_icon() {
        return 'eicon-table';
    }

    public function get_categories() {
        return [ 'elementstack-elements' ];
    }

    public function get_style_depends() {
        return [ 'bdea-style' ];
    }

    protected function register_controls() {

        // ================= CONTENT =================
        $this->start_controls_section(
            'content_section',
            [
                'label' => 'Data Table',
            ]
        );

        $this->add_control(
            'table_title',
            [
                'label' => 'Table Title',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'ALL INVESTORS',
            ]
        );

        $row_repeater = new \Elementor\Repeater();

        $row_repeater->add_control(
            'row_label',
            [
                'label' => 'Label',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Buy from Other Investors',
            ]
        );

        $row_repeater->add_control(
            'row_value',
            [
                'label' => 'Value',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '12.94%',
            ]
        );

        $this->add_control(
            'table_rows',
            [
                'label' => 'Rows',
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $row_repeater->get_controls(),
                'default' => [
                    [ 'row_label' => 'Buy from Other Investors', 'row_value' => '12.94%' ],
                    [ 'row_label' => 'Sell to Other Investors', 'row_value' => '39%' ],
                    [ 'row_label' => 'Sell to Traditional Buyers', 'row_value' => '62%' ],
                    [ 'row_label' => 'Buy/Sell Ratio', 'row_value' => '3.6x' ],
                ],
                'title_field' => '{{{ row_label }}}',
            ]
        );

        $this->end_controls_section();

        // ================= STYLE =================
        $this->start_controls_section(
            'style_general_section',
            [
                'label' => 'General',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'table_bg',
            [
                'label' => 'Table Background',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .bdea-table-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'table_border',
            [
                'label' => 'Table Border Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#d8e0ed',
                'selectors' => [
                    '{{WRAPPER}} .bdea-table-card' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'table_radius',
            [
                'label' => 'Border Radius',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 100 ],
                ],
                'default' => [
                    'size' => 24,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-table-card' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'table_content_padding',
            [
                'label' => 'Content Padding',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'top' => 16,
                    'right' => 24,
                    'bottom' => 16,
                    'left' => 24,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-table-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_title_section',
            [
                'label' => 'Title',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => 'Title Typography',
                'selector' => '{{WRAPPER}} .bdea-table-card-title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => 'Title Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#0d234a',
                'selectors' => [
                    '{{WRAPPER}} .bdea-table-card-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_underline_color',
            [
                'label' => 'Title Underline Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#0d234a',
                'selectors' => [
                    '{{WRAPPER}} .bdea-table-card-title::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_underline_height',
            [
                'label' => 'Title Underline Height',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [ 'min' => 1, 'max' => 10 ],
                ],
                'default' => [
                    'size' => 2,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-table-card-title::after' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'title_underline_width',
            [
                'label' => 'Title Underline Width',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [ 'min' => 10, 'max' => 300 ],
                    '%' => [ 'min' => 10, 'max' => 100 ],
                ],
                'default' => [
                    'size' => 52,
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-table-card-title::after' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_row_section',
            [
                'label' => 'Row',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'row_bg',
            [
                'label' => 'Row Background',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .bdea-table-row' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'row_alt_bg',
            [
                'label' => 'Row Highlight Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#f7fbff',
                'selectors' => [
                    '{{WRAPPER}} .bdea-table-row:nth-child(even)' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'row_border_color',
            [
                'label' => 'Row Border Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#e5ecf7',
                'selectors' => [
                    '{{WRAPPER}} .bdea-table-row' => 'border-bottom-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'row_border_width',
            [
                'label' => 'Row Border Width',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 10 ],
                ],
                'default' => [
                    'size' => 1,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-table-row' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'row_padding',
            [
                'label' => 'Row Padding',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'top' => 14,
                    'right' => 0,
                    'bottom' => 14,
                    'left' => 0,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-table-row' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_column_section',
            [
                'label' => 'Column',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'label_typography',
                'label' => 'Label Typography',
                'selector' => '{{WRAPPER}} .bdea-table-label',
            ]
        );

        $this->add_control(
            'label_color',
            [
                'label' => 'Label Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1a2d51',
                'selectors' => [
                    '{{WRAPPER}} .bdea-table-label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'value_typography',
                'label' => 'Value Typography',
                'selector' => '{{WRAPPER}} .bdea-table-value',
            ]
        );

        $this->add_control(
            'value_color',
            [
                'label' => 'Value Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#0f3a80',
                'selectors' => [
                    '{{WRAPPER}} .bdea-table-value' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }


    protected function render() {

        $settings = $this->get_settings_for_display();

        ?>

        <div class="bdea-table-card">
            <?php if ( ! empty( $settings['table_title'] ) ) : ?>
                <div class="bdea-table-card-title"><?php echo esc_html( $settings['table_title'] ); ?></div>
            <?php endif; ?>

            <div class="bdea-table-body">
                <?php foreach ( $settings['table_rows'] as $index => $row ) : ?>
                    <div class="bdea-table-row">
                        <div class="bdea-table-label"><?php echo esc_html( $row['row_label'] ); ?></div>
                        <div class="bdea-table-value"><?php echo esc_html( $row['row_value'] ); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <?php
    }
}