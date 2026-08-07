<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Price_List_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_price_list';
    }

    public function get_title() {
        return 'Price List';
    }

    public function get_icon() {
        return 'eicon-t-price';
    }

    public function get_categories() {
        return [ 'elementstack-elements' ];
    }

    public function get_style_depends() {
        return [ 'bdea-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'bdea_price_list_section',
            [
                'label' => 'Price List',
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'pl_title',
            [
                'label' => 'Title',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Item Name',
            ]
        );

        $repeater->add_control(
            'pl_price',
            [
                'label' => 'Price',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '$10',
            ]
        );

        $repeater->add_control(
            'pl_badge',
            [
                'label' => 'Badge',
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'Popular',
            ]
        );

        $repeater->add_control(
            'pl_description',
            [
                'label' => 'Description',
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => 'Item description goes here.',
            ]
        );

        $repeater->add_control(
            'pl_featured',
            [
                'label' => 'Featured',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $this->add_control(
            'price_list_items',
            [
                'label' => 'Items',
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [ 'pl_title' => 'Classic Burger', 'pl_price' => '$9' ],
                    [ 'pl_title' => 'Cheese Burger', 'pl_price' => '$11', 'pl_badge' => 'Popular' ],
                    [ 'pl_title' => 'Veggie Burger', 'pl_price' => '$8' ],
                ],
                'title_field' => '{{{ pl_title }}}',
            ]
        );

        $this->end_controls_section();

        // General Style
        $this->start_controls_section(
            'bdea_pl_general_style',
            [
                'label' => 'General',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'pl_align',
            [
                'label' => 'Alignment',
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [ 'title' => 'Left', 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => 'Center', 'icon' => 'eicon-text-align-center' ],
                    'right' => [ 'title' => 'Right', 'icon' => 'eicon-text-align-right' ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .bdea-price-list-widget' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'item_border',
                'selector' => '{{WRAPPER}} .bdea-price-list-item',
            ]
        );

        $this->add_responsive_control(
            'item_border_radius',
            [
                'label' => 'Border Radius',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-price-list-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_spacing',
            [
                'label' => 'Item Spacing',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-price-list-item + .bdea-price-list-item' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_padding',
            [
                'label' => 'Padding',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 16,
                    'right' => 16,
                    'bottom' => 16,
                    'left' => 16,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-price-list-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Typography
        $this->start_controls_section(
            'bdea_pl_typo',
            [
                'label' => 'Typography',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typo',
                'selector' => '{{WRAPPER}} .bdea-price-list-title',
            ]
        );

        $this->add_control(
            'pl_title_color',
            [
                'label' => 'Title Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .bdea-price-list-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'price_typo',
                'selector' => '{{WRAPPER}} .bdea-price-list-price',
            ]
        );

        $this->add_control(
            'pl_price_color',
            [
                'label' => 'Price Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .bdea-price-list-price' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'desc_typo',
                'selector' => '{{WRAPPER}} .bdea-price-list-desc',
            ]
        );

        $this->add_control(
            'pl_desc_color',
            [
                'label' => 'Description Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4b5563',
                'selectors' => [
                    '{{WRAPPER}} .bdea-price-list-desc' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Line Style
        $this->start_controls_section(
            'bdea_pl_line_style',
            [
                'label' => 'Divider Line',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'line_color',
            [
                'label' => 'Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#e5e7eb',
                'selectors' => [
                    '{{WRAPPER}} .bdea-price-list-line' => 'border-bottom-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'line_style',
            [
                'label' => 'Style',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'dotted',
                'options' => [
                    'solid' => 'Solid',
                    'dashed' => 'Dashed',
                    'dotted' => 'Dotted',
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-price-list-line' => 'border-bottom-style: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Badge Style
        $this->start_controls_section(
            'bdea_pl_badge_style',
            [
                'label' => 'Badge',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'pl_badge_bg',
            [
                'label' => 'Background',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .bdea-price-list-badge' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'badge_color',
            [
                'label' => 'Text Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .bdea-price-list-badge' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'badge_typo',
                'selector' => '{{WRAPPER}} .bdea-price-list-badge',
            ]
        );

        $this->add_responsive_control(
            'badge_radius',
            [
                'label' => 'Border Radius',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 4,
                    'right' => 4,
                    'bottom' => 4,
                    'left' => 4,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-price-list-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'badge_padding',
            [
                'label' => 'Padding',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-price-list-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Featured Style
        $this->start_controls_section(
            'bdea_pl_featured_style',
            [
                'label' => 'Featured Item',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'featured_bg',
            [
                'label' => 'Background',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-price-list-item.is-featured' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'featured_radius',
            [
                'label' => 'Border Radius',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-price-list-item.is-featured' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( empty( $settings['price_list_items'] ) ) {
            return;
        }
        ?>
        <div class="bdea-price-list-widget">
            <?php foreach ( $settings['price_list_items'] as $item ) : ?>
                <div class="bdea-price-list-item<?php echo 'yes' === $item['pl_featured'] ? ' is-featured' : ''; ?>">
                    <?php if ( ! empty( $item['pl_badge'] ) ) : ?>
                        <span class="bdea-price-list-badge"><?php echo esc_html( $item['pl_badge'] ); ?></span>
                    <?php endif; ?>
                    <div class="bdea-price-list-head">
                        <span class="bdea-price-list-title"><?php echo esc_html( $item['pl_title'] ); ?></span>
                        <span class="bdea-price-list-line" aria-hidden="true"></span>
                        <span class="bdea-price-list-price"><?php echo esc_html( $item['pl_price'] ); ?></span>
                    </div>
                    <?php if ( ! empty( $item['pl_description'] ) ) : ?>
                        <p class="bdea-price-list-desc"><?php echo esc_html( $item['pl_description'] ); ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }
}