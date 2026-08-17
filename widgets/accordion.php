<?php
/**
 * ElementStack Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Accordion_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_accordion';
    }

    public function get_title() {
        return 'Accordion';
    }

    public function get_icon() {
        return 'eicon-accordion';
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
            'bdea_accordion_section',
            [
                'label' => __( 'Accordion', 'elementstack-elementor-addons' ),
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'acc_title',
            [
                'label' => __( 'Title', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Accordion Title', 'elementstack-elementor-addons' ),
            ]
        );

        $repeater->add_control(
            'acc_content',
            [
                'label' => __( 'Content', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::WYSIWYG,
                'default' => 'Accordion content goes here.',
            ]
        );

        $repeater->add_control(
            'acc_active',
            [
                'label' => __( 'Open by Default', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $this->add_control(
            'accordion_items',
            [
                'label' => __( 'Items', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [ 'acc_title' => __( 'What is your return policy?', 'elementstack-elementor-addons' ), 'acc_content' => __( 'You can return any item within 30 days for a full refund.', 'elementstack-elementor-addons' ), 'acc_active' => 'yes' ],
                    [ 'acc_title' => __( 'How fast is shipping?', 'elementstack-elementor-addons' ), 'acc_content' => __( 'Orders ship within 24 hours and arrive in 2 to 5 business days.', 'elementstack-elementor-addons' ) ],
                    [ 'acc_title' => __( 'Do you offer support?', 'elementstack-elementor-addons' ), 'acc_content' => __( 'Yes, our team is available around the clock via chat and email.', 'elementstack-elementor-addons' ) ],
                ],
                'title_field' => '{{{ acc_title }}}',
            ]
        );

        $this->add_control(
            'show_icon',
            [
                'label' => __( 'Show Icon', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_accordion_header_style',
            [
                'label' => __( 'Header', 'elementstack-elementor-addons' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'header_typography',
                'selector' => '{{WRAPPER}} .bdea-accordion-title',
            ]
        );

        $this->add_control(
            'header_color',
            [
                'label' => __( 'Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .bdea-accordion-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'header_bg',
            [
                'label' => __( 'Background', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#f5f5f5',
                'selectors' => [
                    '{{WRAPPER}} .bdea-accordion-header' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'header_active_bg',
            [
                'label' => __( 'Active Background', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#eef1ff',
                'selectors' => [
                    '{{WRAPPER}} .bdea-accordion-item.is-active .bdea-accordion-header' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'header_padding',
            [
                'label' => __( 'Padding', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-accordion-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'header_radius',
            [
                'label' => __( 'Radius', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 40 ] ],
                'default' => [ 'size' => 6, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-accordion-header' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_accordion_content_style',
            [
                'label' => __( 'Content', 'elementstack-elementor-addons' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'content_typography',
                'selector' => '{{WRAPPER}} .bdea-accordion-content',
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label' => __( 'Text Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4b5563',
                'selectors' => [
                    '{{WRAPPER}} .bdea-accordion-content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'content_bg',
            [
                'label' => __( 'Background', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-accordion-content' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => __( 'Padding', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-accordion-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( empty( $settings['accordion_items'] ) ) {
            return;
        }

        $show_icon = ( 'yes' === $settings['show_icon'] );
        ?>
        <div class="bdea-accordion-widget">
            <?php foreach ( $settings['accordion_items'] as $index => $item ) : ?>
                <?php
                $is_active = ( 'yes' === $item['acc_active'] );
                $item_id = 'bdea-acc-' . $this->get_id() . '-' . $index;
                ?>
                <div class="bdea-accordion-item<?php echo $is_active ? ' is-active' : ''; ?>">
                    <div class="bdea-accordion-header" role="button" tabindex="0"
                         aria-expanded="<?php echo $is_active ? 'true' : 'false'; ?>"
                         aria-controls="<?php echo esc_attr( $item_id ); ?>">
                        <span class="bdea-accordion-title">
                            <?php if ( $show_icon ) : ?>
                                <span class="bdea-accordion-icon bdea-accordion-icon-close" aria-hidden="true">+</span>
                                <span class="bdea-accordion-icon bdea-accordion-icon-open" aria-hidden="true">&#8722;</span>
                            <?php endif; ?>
                            <?php echo esc_html( $item['acc_title'] ); ?>
                        </span>
                    </div>
                    <div class="bdea-accordion-body" id="<?php echo esc_attr( $item_id ); ?>">
                        <div class="bdea-accordion-content">
                            <?php echo wp_kses_post( $item['acc_content'] ); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }
}