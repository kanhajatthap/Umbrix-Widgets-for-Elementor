<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class ELEMENTSKEY_Accordion_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'elementskey_accordion';
    }

    public function get_title() {
        return 'Accordion';
    }

    public function get_icon() {
        return 'eicon-accordion';
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
            'elementskey_accordion_section',
            [
                'label' => __( 'Accordion', 'elementskey' ),
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'acc_title',
            [
                'label' => __( 'Title', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Accordion Title', 'elementskey' ),
            ]
        );

        $repeater->add_control(
            'acc_content',
            [
                'label' => __( 'Content', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::WYSIWYG,
                'default' => 'Accordion content goes here.',
            ]
        );

        $repeater->add_control(
            'acc_active',
            [
                'label' => __( 'Open by Default', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $this->add_control(
            'accordion_items',
            [
                'label' => __( 'Items', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [ 'acc_title' => __( 'What is your return policy?', 'elementskey' ), 'acc_content' => __( 'You can return any item within 30 days for a full refund.', 'elementskey' ), 'acc_active' => 'yes' ],
                    [ 'acc_title' => __( 'How fast is shipping?', 'elementskey' ), 'acc_content' => __( 'Orders ship within 24 hours and arrive in 2 to 5 business days.', 'elementskey' ) ],
                    [ 'acc_title' => __( 'Do you offer support?', 'elementskey' ), 'acc_content' => __( 'Yes, our team is available around the clock via chat and email.', 'elementskey' ) ],
                ],
                'title_field' => '{{{ acc_title }}}',
            ]
        );

        $this->add_control(
            'show_icon',
            [
                'label' => __( 'Show Icon', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_accordion_header_style',
            [
                'label' => __( 'Header', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'header_typography',
                'selector' => '{{WRAPPER}} .elementskey-accordion-title',
            ]
        );

        $this->add_control(
            'header_color',
            [
                'label' => __( 'Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-accordion-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'header_bg',
            [
                'label' => __( 'Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#f5f5f5',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-accordion-header' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'header_active_bg',
            [
                'label' => __( 'Active Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#eef1ff',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-accordion-item.is-active .elementskey-accordion-header' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'header_padding',
            [
                'label' => __( 'Padding', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-accordion-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'header_radius',
            [
                'label' => __( 'Radius', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 40 ] ],
                'default' => [ 'size' => 6, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-accordion-header' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_accordion_content_style',
            [
                'label' => __( 'Content', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'content_typography',
                'selector' => '{{WRAPPER}} .elementskey-accordion-content',
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label' => __( 'Text Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4b5563',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-accordion-content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'content_bg',
            [
                'label' => __( 'Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-accordion-content' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => __( 'Padding', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-accordion-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
        <div class="elementskey-accordion-widget">
            <?php foreach ( $settings['accordion_items'] as $index => $item ) : ?>
                <?php
                $is_active = ( 'yes' === $item['acc_active'] );
                $item_id = 'elementskey-acc-' . $this->get_id() . '-' . $index;
                ?>
                <div class="elementskey-accordion-item<?php echo $is_active ? ' is-active' : ''; ?>">
                    <div class="elementskey-accordion-header" role="button" tabindex="0"
                         aria-expanded="<?php echo $is_active ? 'true' : 'false'; ?>"
                         aria-controls="<?php echo esc_attr( $item_id ); ?>">
                        <span class="elementskey-accordion-title">
                            <?php if ( $show_icon ) : ?>
                                <span class="elementskey-accordion-icon elementskey-accordion-icon-close" aria-hidden="true">+</span>
                                <span class="elementskey-accordion-icon elementskey-accordion-icon-open" aria-hidden="true">&#8722;</span>
                            <?php endif; ?>
                            <?php echo esc_html( $item['acc_title'] ); ?>
                        </span>
                    </div>
                    <div class="elementskey-accordion-body" id="<?php echo esc_attr( $item_id ); ?>">
                        <div class="elementskey-accordion-content">
                            <?php echo wp_kses_post( $item['acc_content'] ); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }
}