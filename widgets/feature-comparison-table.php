<?php
/**
 * ElementStack Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Feature_Comparison_Table_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_feature_comparison_table';
    }

    public function get_title() {
        return 'Feature Comparison Table';
    }

    public function get_icon() {
        return 'eicon-table-of-contents';
    }

    public function get_categories() {
        return [ 'elementstack-elements' ];
    }

    public function get_style_depends() {
        return [ 'bdea-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => __( 'Feature Comparison', 'elementstack-elementor-addons' ),
            ]
        );

        $this->add_control(
            'left_heading',
            [
                'label' => __( 'Left Column Heading', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
            ]
        );

        $this->add_control(
            'right_heading',
            [
                'label' => __( 'Right Column Heading', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
            ]
        );

        $row_repeater = new \Elementor\Repeater();

        $row_repeater->add_control(
            'feature_text',
            [
                'label' => __( 'Feature Text', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
            ]
        );

        $row_repeater->add_control(
            'value_type',
            [
                'label' => __( 'Value Type', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'text',
                'options' => [
                    'text' => __( 'Text', 'elementstack-elementor-addons' ),
                    'icon' => __( 'Icon', 'elementstack-elementor-addons' ),
                ],
            ]
        );

        $row_repeater->add_control(
            'value_text',
            [
                'label' => __( 'Value Text', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'condition' => [
                    'value_type' => 'text',
                ],
            ]
        );

        $row_repeater->add_control(
            'value_icon',
            [
                'label' => __( 'Value Icon', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'condition' => [
                    'value_type' => 'icon',
                ],
            ]
        );

        $this->add_control(
            'rows',
            [
                'label' => __( 'Rows', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $row_repeater->get_controls(),
                'default' => [],
                'title_field' => '{{{ feature_text }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_table_section',
            [
                'label' => __( 'Table', 'elementstack-elementor-addons' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'table_background',
            [
                'label' => __( 'Background', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#c4d3df',
                'selectors' => [
                    '{{WRAPPER}} .bdea-feature-comparison-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'table_border_color',
            [
                'label' => __( 'Border Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#c4d3df',
                'selectors' => [
                    '{{WRAPPER}} .bdea-feature-comparison-card' => 'border-style: solid !important; border-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'table_border_width',
            [
                'label' => __( 'Border Width', 'elementstack-elementor-addons' ),
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
                    '{{WRAPPER}} .bdea-feature-comparison-card' => 'border-style: solid !important; border-width: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_control(
            'table_border_radius',
            [
                'label' => __( 'Border Radius', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 80 ],
                ],
                'default' => [
                    'size' => 12,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-feature-comparison-card' => 'border-radius: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_control(
            'table_overflow_hidden',
            [
                'label' => __( 'Clip Inner Corners', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
                'selectors_dictionary' => [
                    'yes' => 'hidden',
                    '' => 'visible',
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-feature-comparison-card' => 'overflow: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'table_padding',
            [
                'label' => __( 'Outer Padding', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-feature-comparison-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_header_section',
            [
                'label' => __( 'Header', 'elementstack-elementor-addons' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'header_background',
            [
                'label' => __( 'Header Background', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#253979',
                'selectors' => [
                    '{{WRAPPER}} .bdea-feature-comparison-table thead th' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'header_padding',
            [
                'label' => __( 'Header Padding', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'top' => 16,
                    'right' => 18,
                    'bottom' => 16,
                    'left' => 18,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-feature-comparison-table thead th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'header_typography',
                'selector' => '{{WRAPPER}} .bdea-feature-comparison-heading',
            ]
        );

        $this->add_control(
            'header_text_color',
            [
                'label' => __( 'Header Text Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .bdea-feature-comparison-heading' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'header_alignment',
            [
                'label' => __( 'Header Alignment', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'default' => 'left',
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'elementstack-elementor-addons' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'elementstack-elementor-addons' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'elementstack-elementor-addons' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-feature-comparison-heading' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_rows_section',
            [
                'label' => __( 'Rows', 'elementstack-elementor-addons' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'row_background',
            [
                'label' => __( 'Row Background', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#c4d3df',
                'selectors' => [
                    '{{WRAPPER}} .bdea-feature-comparison-row' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'row_alt_background',
            [
                'label' => __( 'Alternate Row Background', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#d3dde7',
                'selectors' => [
                    '{{WRAPPER}} .bdea-feature-comparison-row:nth-child(even)' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'row_border_color',
            [
                'label' => __( 'Row Border Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#c1ccd9',
                'selectors' => [
                    '{{WRAPPER}} .bdea-feature-comparison-row td' => 'border-top-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'row_border_width',
            [
                'label' => __( 'Row Border Width', 'elementstack-elementor-addons' ),
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
                    '{{WRAPPER}} .bdea-feature-comparison-row td' => 'border-top-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'row_padding',
            [
                'label' => __( 'Row Cell Padding', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'top' => 14,
                    'right' => 18,
                    'bottom' => 14,
                    'left' => 18,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-feature-comparison-row td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_feature_column_section',
            [
                'label' => __( 'Feature Column', 'elementstack-elementor-addons' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'feature_typography',
                'selector' => '{{WRAPPER}} .bdea-feature-comparison-feature',
            ]
        );

        $this->add_control(
            'feature_text_color',
            [
                'label' => __( 'Feature Text Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1c2f4b',
                'selectors' => [
                    '{{WRAPPER}} .bdea-feature-comparison-feature' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'feature_alignment',
            [
                'label' => __( 'Feature Alignment', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'default' => 'left',
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'elementstack-elementor-addons' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'elementstack-elementor-addons' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'elementstack-elementor-addons' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-feature-comparison-feature' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_value_column_section',
            [
                'label' => __( 'Value Column', 'elementstack-elementor-addons' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'value_typography',
                'selector' => '{{WRAPPER}} .bdea-feature-comparison-value',
            ]
        );

        $this->add_control(
            'value_text_color',
            [
                'label' => __( 'Value Text Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#3cad60',
                'selectors' => [
                    '{{WRAPPER}} .bdea-feature-comparison-value' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'value_icon_color',
            [
                'label' => __( 'Icon Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#3cad60',
                'selectors' => [
                    '{{WRAPPER}} .bdea-feature-comparison-value .elementor-icon' => 'color: {{VALUE}} !important;',
                    '{{WRAPPER}} .bdea-feature-comparison-value i' => 'color: {{VALUE}} !important;',
                    '{{WRAPPER}} .bdea-feature-comparison-value .elementor-icon svg' => 'fill: {{VALUE}} !important; stroke: {{VALUE}} !important;',
                    '{{WRAPPER}} .bdea-feature-comparison-value svg' => 'fill: {{VALUE}} !important; stroke: {{VALUE}} !important;',
                    '{{WRAPPER}} .bdea-feature-comparison-value svg *' => 'fill: {{VALUE}} !important; stroke: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'value_alignment',
            [
                'label' => __( 'Value Alignment', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'elementstack-elementor-addons' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'elementstack-elementor-addons' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'elementstack-elementor-addons' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-feature-comparison-value' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'value_icon_size',
            [
                'label' => __( 'Icon Size', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [ 'min' => 8, 'max' => 80 ],
                ],
                'default' => [
                    'size' => 18,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-feature-comparison-value .elementor-icon' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important; font-size: {{SIZE}}{{UNIT}} !important; line-height: 1 !important;',
                    '{{WRAPPER}} .bdea-feature-comparison-value .elementor-icon i' => 'font-size: {{SIZE}}{{UNIT}} !important;',
                    '{{WRAPPER}} .bdea-feature-comparison-value .elementor-icon svg' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important;',
                    '{{WRAPPER}} .bdea-feature-comparison-value i' => 'font-size: {{SIZE}}{{UNIT}} !important; line-height: 1 !important;',
                    '{{WRAPPER}} .bdea-feature-comparison-value svg' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important;',
                    '{{WRAPPER}} .bdea-feature-comparison-value .e-font-icon-svg' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $left_heading  = isset( $settings['left_heading'] ) ? trim( $settings['left_heading'] ) : '';
        $right_heading = isset( $settings['right_heading'] ) ? trim( $settings['right_heading'] ) : '';
        $rows          = ! empty( $settings['rows'] ) ? $settings['rows'] : [];
        ?>

        <div class="bdea-feature-comparison-card">
            <table class="bdea-feature-comparison-table" role="table">
                <thead>
                    <tr>
                        <th scope="col" class="bdea-feature-comparison-heading bdea-feature-comparison-heading-left">
                            <?php echo '' !== $left_heading ? esc_html( $left_heading ) : '&nbsp;'; ?>
                        </th>
                        <th scope="col" class="bdea-feature-comparison-heading bdea-feature-comparison-heading-right">
                            <?php echo '' !== $right_heading ? esc_html( $right_heading ) : '&nbsp;'; ?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ( $rows as $row ) : ?>
                        <?php
                        $feature_text = isset( $row['feature_text'] ) ? trim( $row['feature_text'] ) : '';
                        $value_text   = isset( $row['value_text'] ) ? trim( $row['value_text'] ) : '';
                        $value_type   = isset( $row['value_type'] ) ? $row['value_type'] : 'text';
                        ?>
                        <tr class="bdea-feature-comparison-row">
                            <td class="bdea-feature-comparison-feature">
                                <?php echo '' !== $feature_text ? esc_html( $feature_text ) : '&nbsp;'; ?>
                            </td>
                            <td class="bdea-feature-comparison-value">
                                <?php if ( 'icon' === $value_type && ! empty( $row['value_icon']['value'] ) ) : ?>
                                    <?php \Elementor\Icons_Manager::render_icon( $row['value_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                                <?php else : ?>
                                    <?php echo '' !== $value_text ? esc_html( $value_text ) : '&nbsp;'; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php
    }
}
