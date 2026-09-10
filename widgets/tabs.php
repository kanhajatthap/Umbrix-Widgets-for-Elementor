<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class ELEMENTSKEY_Tabs_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'elementskey_tabs';
    }

    public function get_title() {
        return 'Tabs';
    }

    public function get_icon() {
        return 'eicon-tabs';
    }

    public function get_categories() {
        return [ 'elementskey-elements' ];
    }

    public function get_style_depends() {
        return [ 'elementskey-content-style' ];
    }

    public function get_script_depends() {
        return [ 'elementskey-content-script' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'elementskey_tabs_section',
            [
                'label' => __( 'Tabs', 'elementskey' ),
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'tab_title',
            [
                'label' => __( 'Title', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Tab Title', 'elementskey' ),
            ]
        );

        $repeater->add_control(
            'tab_content',
            [
                'label' => __( 'Content', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::WYSIWYG,
                'default' => 'Tab content goes here.',
            ]
        );

        $repeater->add_control(
            'tab_icon',
            [
                'label' => __( 'Icon', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [],
            ]
        );

        $this->add_control(
            'tabs',
            [
                'label' => __( 'Tabs', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [ 'tab_title' => __( 'Features', 'elementskey' ), 'tab_content' => 'Everything you need to get started quickly and scale as you grow.' ],
                    [ 'tab_title' => __( 'Pricing', 'elementskey' ), 'tab_content' => 'Simple, transparent pricing with no hidden fees.' ],
                    [ 'tab_title' => __( 'Support', 'elementskey' ), 'tab_content' => 'Our team is available 24/7 to help you succeed.' ],
                ],
                'title_field' => '{{{ tab_title }}}',
            ]
        );

        $this->add_responsive_control(
            'tab_position',
            [
                'label' => __( 'Position', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'top',
                'options' => [
                    'top' => __( 'Top', 'elementskey' ),
                    'left' => __( 'Left', 'elementskey' ),
                    'right' => __( 'Right', 'elementskey' ),
                ],
            ]
        );

        $this->add_responsive_control(
            'tabs_align',
            [
                'label' => __( 'Tabs Alignment', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [ 'title' => __( 'Left', 'elementskey' ), 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => __( 'Center', 'elementskey' ), 'icon' => 'eicon-text-align-center' ],
                    'right' => [ 'title' => __( 'Right', 'elementskey' ), 'icon' => 'eicon-text-align-right' ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-tabs-nav' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_tabs_nav_style',
            [
                'label' => __( 'Tab Navigation', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'tab_typography',
                'selector' => '{{WRAPPER}} .elementskey-tab-title',
            ]
        );

        $this->add_control(
            'tab_color',
            [
                'label' => __( 'Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#6b7280',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-tab-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tab_active_color',
            [
                'label' => __( 'Active Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-tab-item.is-active .elementskey-tab-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'tab_active_typography',
                'selector' => '{{WRAPPER}} .elementskey-tab-item.is-active .elementskey-tab-title',
                'label' => __( 'Active Typography', 'elementskey' ),
            ]
        );

        $this->add_control(
            'tab_bg',
            [
                'label' => __( 'Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-tab-title' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tab_active_bg',
            [
                'label' => __( 'Active Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#f5f7ff',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-tab-item.is-active .elementskey-tab-title' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tab_hover_color',
            [
                'label' => __( 'Hover Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-tab-item:hover .elementskey-tab-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tab_hover_bg',
            [
                'label' => __( 'Hover Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-tab-item:hover .elementskey-tab-title' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'tab_padding',
            [
                'label' => __( 'Padding', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-tab-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'tab_gap',
            [
                'label' => __( 'Gap Between Tabs', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 40 ] ],
                'default' => [ 'size' => 6, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-tabs-nav' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_tabs_content_style',
            [
                'label' => __( 'Content', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'content_typography',
                'selector' => '{{WRAPPER}} .elementskey-tab-pane',
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label' => __( 'Text Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4b5563',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-tab-pane' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'content_bg',
            [
                'label' => __( 'Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-tab-panes' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'content_border',
                'selector' => '{{WRAPPER}} .elementskey-tab-panes',
            ]
        );

        $this->add_responsive_control(
            'content_radius',
            [
                'label' => __( 'Border Radius', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
                'default' => [ 'size' => 10, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-tab-panes' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => __( 'Padding', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-tab-panes' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( empty( $settings['tabs'] ) ) {
            return;
        }

        $valid_positions = [ 'top', 'left', 'right' ];
        $pos_desktop = ! empty( $settings['tab_position'] ) ? $settings['tab_position'] : 'top';
        $pos_tablet  = ! empty( $settings['tab_position_tablet'] ) ? $settings['tab_position_tablet'] : $pos_desktop;
        $pos_mobile  = ! empty( $settings['tab_position_mobile'] ) ? $settings['tab_position_mobile'] : $pos_tablet;

        if ( ! in_array( $pos_desktop, $valid_positions, true ) ) { $pos_desktop = 'top'; }
        if ( ! in_array( $pos_tablet, $valid_positions, true ) ) { $pos_tablet = $pos_desktop; }
        if ( ! in_array( $pos_mobile, $valid_positions, true ) ) { $pos_mobile = $pos_tablet; }

        ?>
        <div class="elementskey-tabs-widget elementskey-tabs-pos-<?php echo esc_attr( $pos_desktop ); ?>"
             data-pos-desk="<?php echo esc_attr( $pos_desktop ); ?>"
             data-pos-tablet="<?php echo esc_attr( $pos_tablet ); ?>"
             data-pos-mobile="<?php echo esc_attr( $pos_mobile ); ?>">
            <div class="elementskey-tabs-nav" role="tablist">
                <?php foreach ( $settings['tabs'] as $index => $tab ) : ?>
                    <?php
                    $is_active = ( 0 === $index );
                    $tab_id = 'elementskey-tab-' . $this->get_id() . '-' . $index;
                    $icon = ! empty( $tab['tab_icon']['value'] ) ? $tab['tab_icon'] : '';
                    ?>
                    <button type="button"
                            class="elementskey-tab-item<?php echo $is_active ? ' is-active' : ''; ?>"
                            id="<?php echo esc_attr( $tab_id ); ?>-tab"
                            role="tab"
                            aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>"
                            aria-controls="<?php echo esc_attr( $tab_id ); ?>-panel"
                            data-tab="<?php echo esc_attr( $index ); ?>">
                        <span class="elementskey-tab-title">
                            <?php if ( $icon ) : ?>
                                <span class="elementskey-tab-icon"><?php \Elementor\Icons_Manager::render_icon( $icon, [ 'aria-hidden' => 'true' ] ); ?></span>
                            <?php endif; ?>
                            <?php echo esc_html( $tab['tab_title'] ); ?>
                        </span>
                    </button>
                <?php endforeach; ?>
            </div>

            <div class="elementskey-tab-panes">
                <?php foreach ( $settings['tabs'] as $index => $tab ) : ?>
                    <?php
                    $is_active = ( 0 === $index );
                    $tab_id = 'elementskey-tab-' . $this->get_id() . '-' . $index;
                    ?>
                    <div class="elementskey-tab-pane<?php echo $is_active ? ' is-active' : ''; ?>"
                         id="<?php echo esc_attr( $tab_id ); ?>-panel"
                         role="tabpanel"
                         aria-labelledby="<?php echo esc_attr( $tab_id ); ?>-tab"
                         data-pane="<?php echo esc_attr( $index ); ?>">
                        <?php echo wp_kses_post( $tab['tab_content'] ); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}