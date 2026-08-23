<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class ELEMENTSKEY_Price_List_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'elementskey_price_list';
    }

    public function get_title() {
        return 'Price List';
    }

    public function get_icon() {
        return 'eicon-t-price';
    }

    public function get_categories() {
        return [ 'elementskey-elements' ];
    }

    public function get_style_depends() {
        return [ 'elementskey-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'elementskey_price_list_section',
            [
                'label' => __( 'Price List', 'elementskey' ),
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'pl_title',
            [
                'label' => __( 'Title', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Item Name', 'elementskey' ),
            ]
        );

        $repeater->add_control(
            'pl_price',
            [
                'label' => __( 'Price', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '$10',
            ]
        );

        $repeater->add_control(
            'pl_badge',
            [
                'label' => __( 'Badge', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __( 'Popular', 'elementskey' ),
            ]
        );

        $repeater->add_control(
            'pl_description',
            [
                'label' => __( 'Description', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => 'Item description goes here.',
            ]
        );

        $repeater->add_control(
            'pl_featured',
            [
                'label' => __( 'Featured', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $this->add_control(
            'price_list_items',
            [
                'label' => __( 'Items', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [ 'pl_title' => __( 'Classic Burger', 'elementskey' ), 'pl_price' => '$9' ],
                    [ 'pl_title' => __( 'Cheese Burger', 'elementskey' ), 'pl_price' => '$11', 'pl_badge' => __( 'Popular', 'elementskey' ) ],
                    [ 'pl_title' => __( 'Veggie Burger', 'elementskey' ), 'pl_price' => '$8' ],
                ],
                'title_field' => '{{{ pl_title }}}',
            ]
        );

        $this->end_controls_section();

        // General Style
        $this->start_controls_section(
            'elementskey_pl_general_style',
            [
                'label' => __( 'General', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'pl_align',
            [
                'label' => __( 'Alignment', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [ 'title' => __( 'Left', 'elementskey' ), 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => __( 'Center', 'elementskey' ), 'icon' => 'eicon-text-align-center' ],
                    'right' => [ 'title' => __( 'Right', 'elementskey' ), 'icon' => 'eicon-text-align-right' ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-price-list-widget' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'item_border',
                'selector' => '{{WRAPPER}} .elementskey-price-list-item',
            ]
        );

        $this->add_responsive_control(
            'item_border_radius',
            [
                'label' => __( 'Border Radius', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-price-list-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_spacing',
            [
                'label' => __( 'Item Spacing', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-price-list-item + .elementskey-price-list-item' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_padding',
            [
                'label' => __( 'Padding', 'elementskey' ),
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
                    '{{WRAPPER}} .elementskey-price-list-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Typography
        $this->start_controls_section(
            'elementskey_pl_typo',
            [
                'label' => __( 'Typography', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typo',
                'selector' => '{{WRAPPER}} .elementskey-price-list-title',
            ]
        );

        $this->add_control(
            'pl_title_color',
            [
                'label' => __( 'Title Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-price-list-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'price_typo',
                'selector' => '{{WRAPPER}} .elementskey-price-list-price',
            ]
        );

        $this->add_control(
            'pl_price_color',
            [
                'label' => __( 'Price Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-price-list-price' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'desc_typo',
                'selector' => '{{WRAPPER}} .elementskey-price-list-desc',
            ]
        );

        $this->add_control(
            'pl_desc_color',
            [
                'label' => __( 'Description Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4b5563',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-price-list-desc' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Line Style
        $this->start_controls_section(
            'elementskey_pl_line_style',
            [
                'label' => __( 'Divider Line', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'line_color',
            [
                'label' => __( 'Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#e5e7eb',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-price-list-line' => 'border-bottom-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'line_style',
            [
                'label' => __( 'Style', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'dotted',
                'options' => [
                    'solid' => __( 'Solid', 'elementskey' ),
                    'dashed' => __( 'Dashed', 'elementskey' ),
                    'dotted' => __( 'Dotted', 'elementskey' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-price-list-line' => 'border-bottom-style: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Badge Style
        $this->start_controls_section(
            'elementskey_pl_badge_style',
            [
                'label' => __( 'Badge', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'pl_badge_bg',
            [
                'label' => __( 'Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-price-list-badge' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'badge_color',
            [
                'label' => __( 'Text Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-price-list-badge' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'badge_typo',
                'selector' => '{{WRAPPER}} .elementskey-price-list-badge',
            ]
        );

        $this->add_responsive_control(
            'badge_radius',
            [
                'label' => __( 'Border Radius', 'elementskey' ),
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
                    '{{WRAPPER}} .elementskey-price-list-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'badge_padding',
            [
                'label' => __( 'Padding', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-price-list-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Featured Style
        $this->start_controls_section(
            'elementskey_pl_featured_style',
            [
                'label' => __( 'Featured Item', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'featured_bg',
            [
                'label' => __( 'Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-price-list-item.is-featured' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'featured_radius',
            [
                'label' => __( 'Border Radius', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-price-list-item.is-featured' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
        <div class="elementskey-price-list-widget">
            <?php foreach ( $settings['price_list_items'] as $item ) : ?>
                <div class="elementskey-price-list-item<?php echo 'yes' === $item['pl_featured'] ? ' is-featured' : ''; ?>">
                    <?php if ( ! empty( $item['pl_badge'] ) ) : ?>
                        <span class="elementskey-price-list-badge"><?php echo esc_html( $item['pl_badge'] ); ?></span>
                    <?php endif; ?>
                    <div class="elementskey-price-list-head">
                        <span class="elementskey-price-list-title"><?php echo esc_html( $item['pl_title'] ); ?></span>
                        <span class="elementskey-price-list-line" aria-hidden="true"></span>
                        <span class="elementskey-price-list-price"><?php echo esc_html( $item['pl_price'] ); ?></span>
                    </div>
                    <?php if ( ! empty( $item['pl_description'] ) ) : ?>
                        <p class="elementskey-price-list-desc"><?php echo esc_html( $item['pl_description'] ); ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }
}