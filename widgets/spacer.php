<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Spacer_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_spacer';
    }

    public function get_title() {
        return 'Spacer';
    }

    public function get_icon() {
        return 'eicon-spacer';
    }

    public function get_categories() {
        return [ 'elementstack-elements' ];
    }

    public function get_style_depends() {
        return [ 'bdea-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'bdea_spacer_section',
            [
                'label' => 'Spacer',
            ]
        );

        $this->add_responsive_control(
            'spacer_height',
            [
                'label' => 'Height',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'vh' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 600 ],
                    'vh' => [ 'min' => 1, 'max' => 100 ],
                ],
                'default' => [ 'size' => 50, 'unit' => 'px' ],
                'tablet_default' => [ 'size' => 40, 'unit' => 'px' ],
                'mobile_default' => [ 'size' => 30, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-spacer' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'show_mobile_helper',
            [
                'label' => 'Show helper text in editor only',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $is_editor = \Elementor\Plugin::$instance->editor->is_edit_mode();
        ?>
        <div class="bdea-spacer">
            <?php if ( $is_editor && 'yes' === $settings['show_mobile_helper'] ) : ?>
                <span class="bdea-spacer-helper">&#8596;</span>
            <?php endif; ?>
        </div>
        <?php
    }
}