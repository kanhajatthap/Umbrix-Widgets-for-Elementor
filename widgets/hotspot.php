<?php
/**
 * ElementStack Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Hotspot_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_hotspot';
    }

    public function get_title() {
        return 'Hotspot';
    }

    public function get_icon() {
        return 'eicon-hotspot';
    }

    public function get_categories() {
        return [ 'elementstack-elements' ];
    }

    public function get_style_depends() {
        return [ 'bdea-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'bdea_hotspot_section',
            [
                'label' => __( 'Hotspot', 'elementstack-elementor-addons' ),
            ]
        );

        $this->add_control(
            'hotspot_image',
            [
                'label' => __( 'Image', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'hotspot_left',
            [
                'label' => __( 'Horizontal Position', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'range' => [ '%' => [ 'min' => 0, 'max' => 100 ] ],
                'default' => [ 'size' => 50, 'unit' => '%' ],
            ]
        );

        $repeater->add_control(
            'hotspot_top',
            [
                'label' => __( 'Vertical Position', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'range' => [ '%' => [ 'min' => 0, 'max' => 100 ] ],
                'default' => [ 'size' => 50, 'unit' => '%' ],
            ]
        );

        $repeater->add_control(
            'hotspot_label',
            [
                'label' => __( 'Label', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Hotspot', 'elementstack-elementor-addons' ),
            ]
        );

        $repeater->add_control(
            'hotspot_description',
            [
                'label' => __( 'Description', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'rows' => 4,
            ]
        );

        $this->add_control(
            'hotspot_markers',
            [
                'label' => __( 'Hotspots', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [ 'hotspot_label' => __( 'Hotspot 1', 'elementstack-elementor-addons' ), 'hotspot_left' => [ 'size' => 30, 'unit' => '%' ], 'hotspot_top' => [ 'size' => 40, 'unit' => '%' ], 'hotspot_description' => 'This is the first hotspot description.' ],
                ],
                'title_field' => '{{{ hotspot_label }}}',
            ]
        );

        $this->end_controls_section();

        // Marker Style
        $this->start_controls_section(
            'bdea_hotspot_dot_style',
            [
                'label' => __( 'Marker', 'elementstack-elementor-addons' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'hotspot_dot_color',
            [
                'label' => __( 'Dot Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .bdea-hotspot-dot' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'hotspot_dot_size',
            [
                'label' => __( 'Dot Size', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 10, 'max' => 60 ] ],
                'default' => [ 'size' => 18, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-hotspot-dot' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'pulse_color',
            [
                'label' => __( 'Pulse Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-hotspot-dot::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'dot_border_radius',
            [
                'label' => __( 'Border Radius', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'range' => [ '%' => [ 'min' => 0, 'max' => 50 ] ],
                'default' => [ 'size' => 50, 'unit' => '%' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-hotspot-dot' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Label Style
        $this->start_controls_section(
            'bdea_hotspot_label_style',
            [
                'label' => __( 'Label', 'elementstack-elementor-addons' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'label_typo',
                'selector' => '{{WRAPPER}} .bdea-hotspot-label',
            ]
        );

        $this->add_control(
            'label_color',
            [
                'label' => __( 'Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-hotspot-label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'label_bg',
            [
                'label' => __( 'Background', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-hotspot-label' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'label_padding',
            [
                'label' => __( 'Padding', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-hotspot-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'label_radius',
            [
                'label' => __( 'Border Radius', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-hotspot-label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Tooltip Style
        $this->start_controls_section(
            'bdea_hotspot_tooltip_style',
            [
                'label' => __( 'Tooltip', 'elementstack-elementor-addons' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'hotspot_tooltip_typography',
                'selector' => '{{WRAPPER}} .bdea-hotspot-tooltip',
            ]
        );

        $this->add_control(
            'hotspot_tooltip_bg',
            [
                'label' => __( 'Background Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .bdea-hotspot-tooltip' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'hotspot_tooltip_color',
            [
                'label' => __( 'Text Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#f7f7f7',
                'selectors' => [
                    '{{WRAPPER}} .bdea-hotspot-tooltip' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'tooltip_padding',
            [
                'label' => __( 'Padding', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-hotspot-tooltip' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'tooltip_radius',
            [
                'label' => __( 'Border Radius', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-hotspot-tooltip' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'tooltip_shadow',
                'selector' => '{{WRAPPER}} .bdea-hotspot-tooltip',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $image   = isset( $settings['hotspot_image'] ) ? $settings['hotspot_image'] : [];
        $markers = ! empty( $settings['hotspot_markers'] ) ? $settings['hotspot_markers'] : [];
        ?>
        <div class="bdea-hotspot">
            <?php if ( ! empty( $image['url'] ) ) : ?>
                <img class="bdea-hotspot-image" src="<?php echo esc_url( $image['url'] ); ?>" alt="">
            <?php endif; ?>
            <?php foreach ( $markers as $marker ) : ?>
                <?php
                $left        = isset( $marker['hotspot_left']['size'] ) ? (float) $marker['hotspot_left']['size'] : 50;
                $top         = isset( $marker['hotspot_top']['size'] ) ? (float) $marker['hotspot_top']['size'] : 50;
                $label       = ! empty( $marker['hotspot_label'] ) ? $marker['hotspot_label'] : '';
                $description = ! empty( $marker['hotspot_description'] ) ? $marker['hotspot_description'] : '';
                ?>
                <span class="bdea-hotspot-dot" style="left: <?php echo esc_attr( $left ); ?>%; top: <?php echo esc_attr( $top ); ?>%;">
                    <?php if ( $label ) : ?>
                        <span class="bdea-hotspot-label"><?php echo esc_html( $label ); ?></span>
                    <?php endif; ?>
                    <?php if ( $description ) : ?>
                        <div class="bdea-hotspot-tooltip"><?php echo wp_kses_post( $description ); ?></div>
                    <?php endif; ?>
                </span>
            <?php endforeach; ?>
        </div>
        <?php
    }
}