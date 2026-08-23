<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class ELEMENTSKEY_Archive_Title_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'elementskey_archive_title';
    }

    public function get_title() {
        return 'Archive Title';
    }

    public function get_icon() {
        return 'eicon-page-title';
    }

    public function get_categories() {
        return [ 'elementskey-elements' ];
    }

    public function get_style_depends() {
        return [ 'elementskey-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'elementskey_archive_title_section',
            [
                'label' => __( 'Archive Title', 'elementskey' ),
            ]
        );

        $this->add_control(
            'archive_title_tag',
            [
                'label' => __( 'HTML Tag', 'elementskey' ),
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
                'label' => __( 'Fallback Text (non-archive pages)', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Archives', 'elementskey' ),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_archive_title_style',
            [
                'label' => __( 'Title', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'archive_title_typography',
                'selector' => '{{WRAPPER}} .elementskey-archive-title',
            ]
        );

        $this->add_control(
            'archive_title_color',
            [
                'label' => __( 'Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-archive-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'title_shadow',
                'selector' => '{{WRAPPER}} .elementskey-archive-title',
            ]
        );

        $this->add_responsive_control(
            'archive_title_spacing',
            [
                'label' => __( 'Spacing', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-archive-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'archive_title_align',
            [
                'label' => __( 'Alignment', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [ 'title' => __( 'Left', 'elementskey' ), 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => __( 'Center', 'elementskey' ), 'icon' => 'eicon-text-align-center' ],
                    'right' => [ 'title' => __( 'Right', 'elementskey' ), 'icon' => 'eicon-text-align-right' ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-archive-title' => 'text-align: {{VALUE}};',
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
        <<?php echo esc_attr( $tag ); ?> class="elementskey-archive-title">
            <?php echo esc_html( $title ); ?>
        </<?php echo esc_attr( $tag ); ?>>
        <?php
    }
}