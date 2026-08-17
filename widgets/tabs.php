<?php
/**
 * ElementStack Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Tabs_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_tabs';
    }

    public function get_title() {
        return 'Tabs';
    }

    public function get_icon() {
        return 'eicon-tabs';
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
            'bdea_tabs_section',
            [
                'label' => __( 'Tabs', 'elementstack-elementor-addons' ),
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'tab_title',
            [
                'label' => __( 'Title', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Tab Title', 'elementstack-elementor-addons' ),
            ]
        );

        $repeater->add_control(
            'tab_content',
            [
                'label' => __( 'Content', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::WYSIWYG,
                'default' => 'Tab content goes here.',
            ]
        );

        $repeater->add_control(
            'tab_icon',
            [
                'label' => __( 'Icon', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [],
            ]
        );

        $this->add_control(
            'tabs',
            [
                'label' => __( 'Tabs', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [ 'tab_title' => __( 'Features', 'elementstack-elementor-addons' ), 'tab_content' => 'Everything you need to get started quickly and scale as you grow.' ],
                    [ 'tab_title' => __( 'Pricing', 'elementstack-elementor-addons' ), 'tab_content' => 'Simple, transparent pricing with no hidden fees.' ],
                    [ 'tab_title' => __( 'Support', 'elementstack-elementor-addons' ), 'tab_content' => 'Our team is available 24/7 to help you succeed.' ],
                ],
                'title_field' => '{{{ tab_title }}}',
            ]
        );

        $this->add_control(
            'tab_position',
            [
                'label' => __( 'Position', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'top',
                'options' => [
                    'top' => __( 'Top', 'elementstack-elementor-addons' ),
                    'left' => __( 'Left', 'elementstack-elementor-addons' ),
                ],
            ]
        );

        $this->add_responsive_control(
            'tabs_align',
            [
                'label' => __( 'Tabs Alignment', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [ 'title' => __( 'Left', 'elementstack-elementor-addons' ), 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => __( 'Center', 'elementstack-elementor-addons' ), 'icon' => 'eicon-text-align-center' ],
                    'right' => [ 'title' => __( 'Right', 'elementstack-elementor-addons' ), 'icon' => 'eicon-text-align-right' ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .bdea-tabs-nav' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_tabs_nav_style',
            [
                'label' => __( 'Tab Navigation', 'elementstack-elementor-addons' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'tab_typography',
                'selector' => '{{WRAPPER}} .bdea-tab-title',
            ]
        );

        $this->add_control(
            'tab_color',
            [
                'label' => __( 'Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#6b7280',
                'selectors' => [
                    '{{WRAPPER}} .bdea-tab-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tab_active_color',
            [
                'label' => __( 'Active Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .bdea-tab-item.is-active .bdea-tab-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tab_bg',
            [
                'label' => __( 'Background', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-tab-item' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tab_active_bg',
            [
                'label' => __( 'Active Background', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#f5f7ff',
                'selectors' => [
                    '{{WRAPPER}} .bdea-tab-item.is-active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'tab_padding',
            [
                'label' => __( 'Padding', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-tab-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'tab_gap',
            [
                'label' => __( 'Gap Between Tabs', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 40 ] ],
                'default' => [ 'size' => 6, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-tabs-nav' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_tabs_content_style',
            [
                'label' => __( 'Content', 'elementstack-elementor-addons' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'content_typography',
                'selector' => '{{WRAPPER}} .bdea-tab-pane',
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label' => __( 'Text Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4b5563',
                'selectors' => [
                    '{{WRAPPER}} .bdea-tab-pane' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'content_bg',
            [
                'label' => __( 'Background', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .bdea-tab-panes' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'content_border',
                'selector' => '{{WRAPPER}} .bdea-tab-panes',
            ]
        );

        $this->add_responsive_control(
            'content_radius',
            [
                'label' => __( 'Border Radius', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
                'default' => [ 'size' => 10, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-tab-panes' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => __( 'Padding', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-tab-panes' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

        $position = ! empty( $settings['tab_position'] ) ? $settings['tab_position'] : 'top';
        $widget_class = 'bdea-tabs-widget bdea-tabs-pos-' . $position;
        ?>
        <div class="<?php echo esc_attr( $widget_class ); ?>">
            <div class="bdea-tabs-nav" role="tablist">
                <?php foreach ( $settings['tabs'] as $index => $tab ) : ?>
                    <?php
                    $is_active = ( 0 === $index );
                    $tab_id = 'bdea-tab-' . $this->get_id() . '-' . $index;
                    $icon = ! empty( $tab['tab_icon']['value'] ) ? $tab['tab_icon']['value'] : '';
                    ?>
                    <button type="button"
                            class="bdea-tab-item<?php echo $is_active ? ' is-active' : ''; ?>"
                            id="<?php echo esc_attr( $tab_id ); ?>-tab"
                            role="tab"
                            aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>"
                            aria-controls="<?php echo esc_attr( $tab_id ); ?>-panel"
                            data-tab="<?php echo esc_attr( $index ); ?>">
                        <span class="bdea-tab-title">
                            <?php if ( $icon ) : ?>
                                <span class="bdea-tab-icon"><i class="<?php echo esc_attr( $icon ); ?>"></i></span>
                            <?php endif; ?>
                            <?php echo esc_html( $tab['tab_title'] ); ?>
                        </span>
                    </button>
                <?php endforeach; ?>
            </div>

            <div class="bdea-tab-panes">
                <?php foreach ( $settings['tabs'] as $index => $tab ) : ?>
                    <?php
                    $is_active = ( 0 === $index );
                    $tab_id = 'bdea-tab-' . $this->get_id() . '-' . $index;
                    ?>
                    <div class="bdea-tab-pane<?php echo $is_active ? ' is-active' : ''; ?>"
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