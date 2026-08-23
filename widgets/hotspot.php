<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class ELEMENTSKEY_Hotspot_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'elementskey_hotspot';
    }

    public function get_title() {
        return 'Hotspot';
    }

    public function get_icon() {
        return 'eicon-hotspot';
    }

    public function get_categories() {
        return [ 'elementskey-elements' ];
    }

    public function get_style_depends() {
        return [ 'elementskey-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'elementskey_hotspot_section',
            [
                'label' => __( 'Hotspot', 'elementskey' ),
            ]
        );

        $this->add_control(
            'hotspot_image',
            [
                'label' => __( 'Image', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'hotspot_left',
            [
                'label' => __( 'Horizontal Position', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'range' => [ '%' => [ 'min' => 0, 'max' => 100 ] ],
                'default' => [ 'size' => 50, 'unit' => '%' ],
            ]
        );

        $repeater->add_control(
            'hotspot_top',
            [
                'label' => __( 'Vertical Position', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'range' => [ '%' => [ 'min' => 0, 'max' => 100 ] ],
                'default' => [ 'size' => 50, 'unit' => '%' ],
            ]
        );

        $repeater->add_control(
            'hotspot_label',
            [
                'label' => __( 'Label', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Hotspot', 'elementskey' ),
            ]
        );

        $repeater->add_control(
            'hotspot_description',
            [
                'label' => __( 'Description', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'rows' => 4,
            ]
        );

        $this->add_control(
            'hotspot_markers',
            [
                'label' => __( 'Hotspots', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [ 'hotspot_label' => __( 'Hotspot 1', 'elementskey' ), 'hotspot_left' => [ 'size' => 30, 'unit' => '%' ], 'hotspot_top' => [ 'size' => 40, 'unit' => '%' ], 'hotspot_description' => 'This is the first hotspot description.' ],
                ],
                'title_field' => '{{{ hotspot_label }}}',
            ]
        );

        $this->end_controls_section();

        // Marker Style
        $this->start_controls_section(
            'elementskey_hotspot_dot_style',
            [
                'label' => __( 'Marker', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'hotspot_dot_color',
            [
                'label' => __( 'Dot Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-hotspot-dot' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'hotspot_dot_size',
            [
                'label' => __( 'Dot Size', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 10, 'max' => 60 ] ],
                'default' => [ 'size' => 18, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-hotspot-dot' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'pulse_color',
            [
                'label' => __( 'Pulse Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-hotspot-dot::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'dot_border_radius',
            [
                'label' => __( 'Border Radius', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'range' => [ '%' => [ 'min' => 0, 'max' => 50 ] ],
                'default' => [ 'size' => 50, 'unit' => '%' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-hotspot-dot' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Label Style
        $this->start_controls_section(
            'elementskey_hotspot_label_style',
            [
                'label' => __( 'Label', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'label_typo',
                'selector' => '{{WRAPPER}} .elementskey-hotspot-label',
            ]
        );

        $this->add_control(
            'label_color',
            [
                'label' => __( 'Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-hotspot-label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'label_bg',
            [
                'label' => __( 'Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-hotspot-label' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'label_padding',
            [
                'label' => __( 'Padding', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-hotspot-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'label_radius',
            [
                'label' => __( 'Border Radius', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-hotspot-label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Tooltip Style
        $this->start_controls_section(
            'elementskey_hotspot_tooltip_style',
            [
                'label' => __( 'Tooltip', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'hotspot_tooltip_typography',
                'selector' => '{{WRAPPER}} .elementskey-hotspot-tooltip',
            ]
        );

        $this->add_control(
            'hotspot_tooltip_bg',
            [
                'label' => __( 'Background Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-hotspot-tooltip' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'hotspot_tooltip_color',
            [
                'label' => __( 'Text Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#f7f7f7',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-hotspot-tooltip' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'tooltip_padding',
            [
                'label' => __( 'Padding', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-hotspot-tooltip' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'tooltip_radius',
            [
                'label' => __( 'Border Radius', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-hotspot-tooltip' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'tooltip_shadow',
                'selector' => '{{WRAPPER}} .elementskey-hotspot-tooltip',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $image   = isset( $settings['hotspot_image'] ) ? $settings['hotspot_image'] : [];
        $markers = ! empty( $settings['hotspot_markers'] ) ? $settings['hotspot_markers'] : [];
        ?>
        <div class="elementskey-hotspot">
            <?php if ( ! empty( $image['url'] ) ) : ?>
                <img class="elementskey-hotspot-image" src="<?php echo esc_url( $image['url'] ); ?>" alt="">
            <?php endif; ?>
            <?php foreach ( $markers as $marker ) : ?>
                <?php
                $left        = isset( $marker['hotspot_left']['size'] ) ? (float) $marker['hotspot_left']['size'] : 50;
                $top         = isset( $marker['hotspot_top']['size'] ) ? (float) $marker['hotspot_top']['size'] : 50;
                $label       = ! empty( $marker['hotspot_label'] ) ? $marker['hotspot_label'] : '';
                $description = ! empty( $marker['hotspot_description'] ) ? $marker['hotspot_description'] : '';
                ?>
                <span class="elementskey-hotspot-dot" style="left: <?php echo esc_attr( $left ); ?>%; top: <?php echo esc_attr( $top ); ?>%;">
                    <?php if ( $label ) : ?>
                        <span class="elementskey-hotspot-label"><?php echo esc_html( $label ); ?></span>
                    <?php endif; ?>
                    <?php if ( $description ) : ?>
                        <div class="elementskey-hotspot-tooltip"><?php echo wp_kses_post( $description ); ?></div>
                    <?php endif; ?>
                </span>
            <?php endforeach; ?>
        </div>
        <?php
    }
}