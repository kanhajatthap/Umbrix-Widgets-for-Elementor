<?php
/**
 * ElementStack Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Price_Table_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_price_table';
    }

    public function get_title() {
        return 'Price Table';
    }

    public function get_icon() {
        return 'eicon-price-table';
    }

    public function get_categories() {
        return [ 'elementstack-elements' ];
    }

    public function get_style_depends() {
        return [ 'bdea-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'bdea_price_table_section',
            [
                'label' => __( 'Price Table', 'elementstack-elementor-addons' ),
            ]
        );

        $this->add_control(
            'pt_title',
            [
                'label' => __( 'Title', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Basic', 'elementstack-elementor-addons' ),
            ]
        );

        $this->add_control(
            'pt_subtitle',
            [
                'label' => __( 'Subtitle', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'For individuals', 'elementstack-elementor-addons' ),
            ]
        );

        $this->add_control(
            'pt_price',
            [
                'label' => __( 'Price', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '$19',
            ]
        );

        $this->add_control(
            'pt_period',
            [
                'label' => __( 'Period', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '/month',
            ]
        );

        $this->add_control(
            'pt_featured',
            [
                'label' => __( 'Featured', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $this->add_control(
            'pt_btn_text',
            [
                'label' => __( 'Button Text', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Get Started', 'elementstack-elementor-addons' ),
            ]
        );

        $this->add_control(
            'pt_btn_url',
            [
                'label' => __( 'Button Link', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://example.com',
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'pt_feature_text',
            [
                'label' => __( 'Feature', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Feature item', 'elementstack-elementor-addons' ),
            ]
        );

        $repeater->add_control(
            'pt_feature_included',
            [
                'label' => __( 'Included', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'pt_features',
            [
                'label' => __( 'Features', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [ 'pt_feature_text' => __( 'Feature one', 'elementstack-elementor-addons' ) ],
                    [ 'pt_feature_text' => __( 'Feature two', 'elementstack-elementor-addons' ) ],
                    [ 'pt_feature_text' => __( 'Feature three', 'elementstack-elementor-addons' ), 'pt_feature_included' => '' ],
                ],
                'title_field' => '{{{ pt_feature_text }}}',
            ]
        );

        $this->end_controls_section();

        // General Style
        $this->start_controls_section(
            'bdea_pt_general_style',
            [
                'label' => __( 'General', 'elementstack-elementor-addons' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'card_border',
                'selector' => '{{WRAPPER}} .bdea-price-table',
            ]
        );

        $this->add_responsive_control(
            'card_border_radius',
            [
                'label' => __( 'Border Radius', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-price-table' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'card_shadow',
                'selector' => '{{WRAPPER}} .bdea-price-table',
            ]
        );

        $this->add_control(
            'pt_featured_border',
            [
                'label' => __( 'Featured Border Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#f59e0b',
                'selectors' => [
                    '{{WRAPPER}} .bdea-price-table.is-featured' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Header Style
        $this->start_controls_section(
            'bdea_pt_header_style',
            [
                'label' => __( 'Header', 'elementstack-elementor-addons' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'pt_header_bg',
            [
                'label' => __( 'Background', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .bdea-price-table-header' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'header_padding',
            [
                'label' => __( 'Padding', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 30,
                    'right' => 20,
                    'bottom' => 30,
                    'left' => 20,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-price-table-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'pt_header_color',
            [
                'label' => __( 'Text Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .bdea-price-table-header' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .bdea-price-table-title' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .bdea-price-table-subtitle' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .bdea-price-table-price' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .bdea-price-table-period' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typo',
                'selector' => '{{WRAPPER}} .bdea-price-table-title',
            ]
        );

        $this->add_control(
            'price_color',
            [
                'label' => __( 'Price Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-price-table-price' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Features Style
        $this->start_controls_section(
            'bdea_pt_features_style',
            [
                'label' => __( 'Features', 'elementstack-elementor-addons' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'features_padding',
            [
                'label' => __( 'Padding', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 20,
                    'right' => 20,
                    'bottom' => 20,
                    'left' => 20,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-price-table-features' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'feature_typo',
                'selector' => '{{WRAPPER}} .bdea-price-table-features li',
            ]
        );

        $this->add_control(
            'feature_color',
            [
                'label' => __( 'Text Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4b5563',
                'selectors' => [
                    '{{WRAPPER}} .bdea-price-table-features li' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'feature_included_color',
            [
                'label' => __( 'Included Check Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#10b981',
                'selectors' => [
                    '{{WRAPPER}} .bdea-price-table-features li.is-included .bdea-price-table-check' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'feature_excluded_color',
            [
                'label' => __( 'Excluded X Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ef4444',
                'selectors' => [
                    '{{WRAPPER}} .bdea-price-table-features li.is-excluded .bdea-price-table-check' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'feature_spacing',
            [
                'label' => __( 'Item Spacing', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 30 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-price-table-features li' => 'padding: {{SIZE}}{{UNIT}} 0;',
                ],
            ]
        );

        $this->end_controls_section();

        // Button Style
        $this->start_controls_section(
            'bdea_pt_btn_style',
            [
                'label' => __( 'Button', 'elementstack-elementor-addons' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'pt_btn_bg',
            [
                'label' => __( 'Background', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .bdea-price-table-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_text_color',
            [
                'label' => __( 'Text Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .bdea-price-table-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_hover_bg',
            [
                'label' => __( 'Hover Background', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-price-table-btn:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_hover_text',
            [
                'label' => __( 'Hover Text Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-price-table-btn:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'btn_border_radius',
            [
                'label' => __( 'Border Radius', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 6,
                    'right' => 6,
                    'bottom' => 6,
                    'left' => 6,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-price-table-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'btn_typo',
                'selector' => '{{WRAPPER}} .bdea-price-table-btn',
            ]
        );

        $this->add_responsive_control(
            'btn_padding',
            [
                'label' => __( 'Padding', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 12,
                    'right' => 24,
                    'bottom' => 12,
                    'left' => 24,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-price-table-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $featured = ( 'yes' === $settings['pt_featured'] );
        ?>
        <div class="bdea-price-table-widget">
            <div class="bdea-price-table<?php echo $featured ? ' is-featured' : ''; ?>">
                <div class="bdea-price-table-header">
                    <h3 class="bdea-price-table-title"><?php echo esc_html( $settings['pt_title'] ); ?></h3>
                    <?php if ( ! empty( $settings['pt_subtitle'] ) ) : ?>
                        <div class="bdea-price-table-subtitle"><?php echo esc_html( $settings['pt_subtitle'] ); ?></div>
                    <?php endif; ?>
                    <div class="bdea-price-table-price-wrap">
                        <span class="bdea-price-table-price"><?php echo esc_html( $settings['pt_price'] ); ?></span>
                        <?php if ( ! empty( $settings['pt_period'] ) ) : ?>
                            <span class="bdea-price-table-period"><?php echo esc_html( $settings['pt_period'] ); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <ul class="bdea-price-table-features">
                    <?php if ( ! empty( $settings['pt_features'] ) ) : ?>
                        <?php foreach ( $settings['pt_features'] as $feature ) : ?>
                            <li class="<?php echo 'yes' === $feature['pt_feature_included'] ? 'is-included' : 'is-excluded'; ?>">
                                <span class="bdea-price-table-check" aria-hidden="true">
                                    <?php echo 'yes' === $feature['pt_feature_included'] ? '&#10003;' : '&#10007;'; ?>
                                </span>
                                <?php echo esc_html( $feature['pt_feature_text'] ); ?>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
                <?php if ( ! empty( $settings['pt_btn_text'] ) ) : ?>
                    <div class="bdea-price-table-footer">
                        <a class="bdea-price-table-btn" href="<?php echo esc_url( ! empty( $settings['pt_btn_url']['url'] ) ? $settings['pt_btn_url']['url'] : '#' ); ?>">
                            <?php echo esc_html( $settings['pt_btn_text'] ); ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}