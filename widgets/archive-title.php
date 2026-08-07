<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Archive_Title_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_archive_title';
    }

    public function get_title() {
        return 'Archive Title';
    }

    public function get_icon() {
        return 'eicon-page-title';
    }

    public function get_categories() {
        return [ 'elementstack-elements' ];
    }

    public function get_style_depends() {
        return [ 'bdea-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'bdea_archive_title_section',
            [
                'label' => 'Archive Title',
            ]
        );

        $this->add_control(
            'archive_title_tag',
            [
                'label' => 'HTML Tag',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'h1',
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'p' => 'P',
                    'div' => 'DIV',
                ],
            ]
        );

        $this->add_control(
            'archive_title_fallback',
            [
                'label' => 'Fallback Text (non-archive pages)',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Archives',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_archive_title_style',
            [
                'label' => 'Title',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'archive_title_typography',
                'selector' => '{{WRAPPER}} .bdea-archive-title',
            ]
        );

        $this->add_control(
            'archive_title_color',
            [
                'label' => 'Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .bdea-archive-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'title_shadow',
                'selector' => '{{WRAPPER}} .bdea-archive-title',
            ]
        );

        $this->add_responsive_control(
            'archive_title_spacing',
            [
                'label' => 'Spacing',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-archive-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'archive_title_align',
            [
                'label' => 'Alignment',
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [ 'title' => 'Left', 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => 'Center', 'icon' => 'eicon-text-align-center' ],
                    'right' => [ 'title' => 'Right', 'icon' => 'eicon-text-align-right' ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .bdea-archive-title' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $tag = ! empty( $settings['archive_title_tag'] ) ? $settings['archive_title_tag'] : 'h1';
        if ( ! in_array( $tag, [ 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'div' ], true ) ) {
            $tag = 'h1';
        }

        $title = is_archive() ? get_the_archive_title() : ( ! empty( $settings['archive_title_fallback'] ) ? $settings['archive_title_fallback'] : 'Archives' );
        ?>
        <<?php echo esc_attr( $tag ); ?> class="bdea-archive-title">
            <?php echo esc_html( $title ); ?>
        </<?php echo esc_attr( $tag ); ?>>
        <?php
    }
}