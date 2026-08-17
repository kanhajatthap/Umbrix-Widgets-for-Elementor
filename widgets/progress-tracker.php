<?php
/**
 * ElementStack Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Progress_Tracker_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_progress_tracker';
    }

    public function get_title() {
        return 'Progress Tracker';
    }

    public function get_icon() {
        return 'eicon-skill-bar';
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
            'bdea_progress_tracker_section',
            [
                'label' => __( 'Progress Tracker', 'elementstack-elementor-addons' ),
            ]
        );

        $this->add_control(
            'progress_description',
            [
                'label' => __( 'Description', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Event Progress', 'elementstack-elementor-addons' ),
            ]
        );

        $this->add_control(
            'progress_percentage',
            [
                'label' => __( 'Percentage', 'elementstack-elementor-addons' ),
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
                'label' => __( 'Show Percentage', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_progress_tracker_style',
            [
                'label' => __( 'Style', 'elementstack-elementor-addons' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'progress_fill_color',
            [
                'label' => __( 'Fill Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .bdea-progress-tracker-fill' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'progress_text_color',
            [
                'label' => __( 'Text Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .bdea-progress-tracker-description' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .bdea-progress-tracker-percentage' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'progress_height',
            [
                'label' => __( 'Height', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 4, 'max' => 50 ] ],
                'default' => [ 'size' => 8, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-progress-tracker-bar' => 'height: {{SIZE}}{{UNIT}};',
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
        <div class="bdea-progress-tracker" data-width="<?php echo esc_attr( $percentage ); ?>">
            <?php if ( $description ) : ?>
                <div class="bdea-progress-tracker-description"><?php echo esc_html( $description ); ?></div>
            <?php endif; ?>
            <div class="bdea-progress-tracker-bar">
                <div class="bdea-progress-tracker-fill"></div>
            </div>
            <?php if ( $show_percentage ) : ?>
                <span class="bdea-progress-tracker-percentage"><?php echo esc_html( $percentage ); ?>%</span>
            <?php endif; ?>
        </div>
        <?php
    }
}