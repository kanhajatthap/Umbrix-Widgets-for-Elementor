<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class ELEMENTSKEY_Spacer_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'elementskey_spacer';
    }

    public function get_title() {
        return 'Spacer';
    }

    public function get_icon() {
        return 'eicon-spacer';
    }

    public function get_categories() {
        return [ 'elementskey-elements' ];
    }

    public function get_style_depends() {
        return [ 'elementskey-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'elementskey_spacer_section',
            [
                'label' => __( 'Spacer', 'elementskey' ),
            ]
        );

        $this->add_responsive_control(
            'spacer_height',
            [
                'label' => __( 'Height', 'elementskey' ),
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
                    '{{WRAPPER}} .elementskey-spacer' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'show_mobile_helper',
            [
                'label' => __( 'Show helper text in editor only', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_spacer_style_section',
            [
                'label' => __( 'Style', 'elementskey' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $is_editor = \Elementor\Plugin::$instance->editor->is_edit_mode();
        ?>
        <div class="elementskey-spacer">
            <?php if ( $is_editor && 'yes' === $settings['show_mobile_helper'] ) : ?>
                <span class="elementskey-spacer-helper">&#8596;</span>
            <?php endif; ?>
        </div>
        <?php
    }
}