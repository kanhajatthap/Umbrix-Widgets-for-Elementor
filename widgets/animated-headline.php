<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Animated_Headline_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_animated_headline';
    }

    public function get_title() {
        return 'Animated Headline';
    }

    public function get_icon() {
        return 'eicon-animated-headline';
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
            'bdea_animated_headline_section',
            [
                'label' => 'Animated Headline',
            ]
        );

        $this->add_control(
            'headline_before',
            [
                'label' => 'Before Text',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'We',
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'word',
            [
                'label' => 'Word',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Design',
            ]
        );

        $this->add_control(
            'headline_words',
            [
                'label' => 'Rotating Words',
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [ 'word' => 'Design' ],
                    [ 'word' => 'Create' ],
                    [ 'word' => 'Inspire' ],
                ],
                'title_field' => '{{{ word }}}',
            ]
        );

        $this->add_control(
            'headline_after',
            [
                'label' => 'After Text',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Beautiful Things',
            ]
        );

        $this->add_control(
            'headline_speed',
            [
                'label' => 'Rotation Speed (ms)',
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 2500,
                'min' => 500,
                'max' => 10000,
                'step' => 100,
            ]
        );

        $this->end_controls_section();

        // Alignment
        $this->start_controls_section(
            'bdea_headline_general_style',
            [
                'label' => 'General',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'headline_align',
            [
                'label' => 'Alignment',
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [ 'title' => 'Left', 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => 'Center', 'icon' => 'eicon-text-align-center' ],
                    'right' => [ 'title' => 'Right', 'icon' => 'eicon-text-align-right' ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .bdea-animated-headline' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'headline_spacing',
            [
                'label' => 'Spacing',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-animated-headline' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_headline_before_style',
            [
                'label' => 'Before Text',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'before_typography',
                'selector' => '{{WRAPPER}} .bdea-headline-before',
            ]
        );

        $this->add_control(
            'before_color',
            [
                'label' => 'Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .bdea-headline-before' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_headline_words_style',
            [
                'label' => 'Rotating Words',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'words_typography',
                'selector' => '{{WRAPPER}} .bdea-headline-words',
            ]
        );

        $this->add_control(
            'words_color',
            [
                'label' => 'Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .bdea-headline-words' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'words_highlight_color',
            [
                'label' => 'Highlight Background',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-headline-words' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'words_padding',
            [
                'label' => 'Highlight Padding',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-headline-words' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'words_radius',
            [
                'label' => 'Highlight Border Radius',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-headline-words' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_headline_after_style',
            [
                'label' => 'After Text',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'after_typography',
                'selector' => '{{WRAPPER}} .bdea-headline-after',
            ]
        );

        $this->add_control(
            'after_color',
            [
                'label' => 'Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .bdea-headline-after' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $before = ! empty( $settings['headline_before'] ) ? $settings['headline_before'] : '';
        $after  = ! empty( $settings['headline_after'] ) ? $settings['headline_after'] : '';
        $speed  = ! empty( $settings['headline_speed'] ) ? (int) $settings['headline_speed'] : 2500;
        $words  = ! empty( $settings['headline_words'] ) ? $settings['headline_words'] : [];
        ?>
        <div class="bdea-animated-headline">
            <?php if ( $before ) : ?>
                <span class="bdea-headline-before"><?php echo esc_html( $before ); ?></span>
            <?php endif; ?>
            <span class="bdea-headline-words" data-speed="<?php echo esc_attr( $speed ); ?>">
                <?php foreach ( $words as $index => $word ) : ?>
                    <span class="bdea-headline-word<?php echo 0 === $index ? ' is-active' : ''; ?>"><?php echo esc_html( $word['word'] ); ?></span>
                <?php endforeach; ?>
            </span>
            <?php if ( $after ) : ?>
                <span class="bdea-headline-after"><?php echo esc_html( $after ); ?></span>
            <?php endif; ?>
        </div>
        <?php
    }
}