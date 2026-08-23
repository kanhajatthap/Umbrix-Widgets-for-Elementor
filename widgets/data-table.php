<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class ELEMENTSKEY_Data_Table_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'elementskey_data_table';
    }

    public function get_title() {
        return 'Data Table';
    }

    public function get_icon() {
        return 'eicon-table';
    }

    public function get_categories() {
        return [ 'elementskey-elements' ];
    }

    public function get_style_depends() {
        return [ 'elementskey-style' ];
    }

    protected function register_controls() {

        // ================= CONTENT =================
        $this->start_controls_section(
            'content_section',
            [
                'label' => __( 'Data Table', 'elementskey' ),
            ]
        );

        $this->add_control(
            'table_title',
            [
                'label' => __( 'Table Title', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'ALL INVESTORS', 'elementskey' ),
            ]
        );

        $row_repeater = new \Elementor\Repeater();

        $row_repeater->add_control(
            'row_label',
            [
                'label' => __( 'Label', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Buy from Other Investors', 'elementskey' ),
            ]
        );

        $row_repeater->add_control(
            'row_value',
            [
                'label' => __( 'Value', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '12.94%',
            ]
        );

        $this->add_control(
            'table_rows',
            [
                'label' => __( 'Rows', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $row_repeater->get_controls(),
                'default' => [
                    [ 'row_label' => __( 'Buy from Other Investors', 'elementskey' ), 'row_value' => '12.94%' ],
                    [ 'row_label' => __( 'Sell to Other Investors', 'elementskey' ), 'row_value' => '39%' ],
                    [ 'row_label' => __( 'Sell to Traditional Buyers', 'elementskey' ), 'row_value' => '62%' ],
                    [ 'row_label' => __( 'Buy/Sell Ratio', 'elementskey' ), 'row_value' => '3.6x' ],
                ],
                'title_field' => '{{{ row_label }}}',
            ]
        );

        $this->end_controls_section();

        // ================= STYLE =================
        $this->start_controls_section(
            'style_general_section',
            [
                'label' => __( 'General', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'table_bg',
            [
                'label' => __( 'Table Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-table-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'table_border',
            [
                'label' => __( 'Table Border Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#d8e0ed',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-table-card' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'table_radius',
            [
                'label' => __( 'Border Radius', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 100 ],
                ],
                'default' => [
                    'size' => 24,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-table-card' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'table_content_padding',
            [
                'label' => __( 'Content Padding', 'elementskey' ),
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
                    '{{WRAPPER}} .elementskey-table-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_title_section',
            [
                'label' => __( 'Title', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => __( 'Title Typography', 'elementskey' ),
                'selector' => '{{WRAPPER}} .elementskey-table-card-title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Title Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#0d234a',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-table-card-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_underline_color',
            [
                'label' => __( 'Title Underline Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#0d234a',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-table-card-title::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_underline_height',
            [
                'label' => __( 'Title Underline Height', 'elementskey' ),
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
                    '{{WRAPPER}} .elementskey-table-card-title::after' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'title_underline_width',
            [
                'label' => __( 'Title Underline Width', 'elementskey' ),
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
                    '{{WRAPPER}} .elementskey-table-card-title::after' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_row_section',
            [
                'label' => __( 'Row', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'row_bg',
            [
                'label' => __( 'Row Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-table-row' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'row_alt_bg',
            [
                'label' => __( 'Row Highlight Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#f7fbff',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-table-row:nth-child(even)' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'row_border_color',
            [
                'label' => __( 'Row Border Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#e5ecf7',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-table-row' => 'border-bottom-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'row_border_width',
            [
                'label' => __( 'Row Border Width', 'elementskey' ),
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
                    '{{WRAPPER}} .elementskey-table-row' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'row_padding',
            [
                'label' => __( 'Row Padding', 'elementskey' ),
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
                    '{{WRAPPER}} .elementskey-table-row' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_column_section',
            [
                'label' => __( 'Column', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'label_typography',
                'label' => __( 'Label Typography', 'elementskey' ),
                'selector' => '{{WRAPPER}} .elementskey-table-label',
            ]
        );

        $this->add_control(
            'label_color',
            [
                'label' => __( 'Label Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1a2d51',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-table-label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'value_typography',
                'label' => __( 'Value Typography', 'elementskey' ),
                'selector' => '{{WRAPPER}} .elementskey-table-value',
            ]
        );

        $this->add_control(
            'value_color',
            [
                'label' => __( 'Value Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#0f3a80',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-table-value' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }


    protected function render() {

        $settings = $this->get_settings_for_display();

        ?>

        <div class="elementskey-table-card">
            <?php if ( ! empty( $settings['table_title'] ) ) : ?>
                <div class="elementskey-table-card-title"><?php echo esc_html( $settings['table_title'] ); ?></div>
            <?php endif; ?>

            <div class="elementskey-table-body">
                <?php foreach ( $settings['table_rows'] as $index => $row ) : ?>
                    <div class="elementskey-table-row">
                        <div class="elementskey-table-label"><?php echo esc_html( $row['row_label'] ); ?></div>
                        <div class="elementskey-table-value"><?php echo esc_html( $row['row_value'] ); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <?php
    }
}