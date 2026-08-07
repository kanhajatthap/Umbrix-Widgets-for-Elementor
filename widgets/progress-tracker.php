<?php
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
                'label' => 'Progress Tracker',
            ]
        );

        $this->add_control(
            'progress_description',
            [
                'label' => 'Description',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Event Progress',
            ]
        );

        $this->add_control(
            'progress_percentage',
            [
                'label' => 'Percentage',
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
                'label' => 'Show Percentage',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_progress_tracker_style',
            [
                'label' => 'Style',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'progress_fill_color',
            [
                'label' => 'Fill Color',
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
                'label' => 'Text Color',
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
                'label' => 'Height',
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