<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class ELEMENTSKEY_Progress_Tracker_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'elementskey_progress_tracker';
    }

    public function get_title() {
        return 'Progress Tracker';
    }

    public function get_icon() {
        return 'eicon-skill-bar';
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
            'elementskey_progress_tracker_section',
            [
                'label' => __( 'Progress Tracker', 'elementskey' ),
            ]
        );

        $this->add_control(
            'progress_description',
            [
                'label' => __( 'Description', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Event Progress', 'elementskey' ),
            ]
        );

        $this->add_control(
            'progress_percentage',
            [
                'label' => __( 'Percentage', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 75,
                'min' => 0,
                'max' => 100,
                'step' => 1,
            ]
        );

        $this->add_control(
            'progress_show_percentage',
            [
                'label' => __( 'Show Percentage', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_progress_tracker_style',
            [
                'label' => __( 'Style', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'progress_fill_color',
            [
                'label' => __( 'Fill Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-progress-tracker-fill' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'progress_text_color',
            [
                'label' => __( 'Text Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-progress-tracker-description' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementskey-progress-tracker-percentage' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'progress_height',
            [
                'label' => __( 'Height', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 4, 'max' => 50 ] ],
                'default' => [ 'size' => 8, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-progress-tracker-bar' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $description      = ! empty( $settings['progress_description'] ) ? $settings['progress_description'] : '';
        $percentage       = (int) $settings['progress_percentage'];
        $show_percentage  = ( 'yes' === $settings['progress_show_percentage'] );
        ?>
        <div class="elementskey-progress-tracker" data-width="<?php echo esc_attr( $percentage ); ?>">
            <?php if ( $description ) : ?>
                <div class="elementskey-progress-tracker-description"><?php echo esc_html( $description ); ?></div>
            <?php endif; ?>
            <div class="elementskey-progress-tracker-bar">
                <div class="elementskey-progress-tracker-fill"></div>
            </div>
            <?php if ( $show_percentage ) : ?>
                <span class="elementskey-progress-tracker-percentage"><?php echo esc_html( $percentage ); ?>%</span>
            <?php endif; ?>
        </div>
        <?php
    }
}