<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class ELEMENTSKEY_Animated_Headline_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'elementskey_animated_headline';
    }

    public function get_title() {
        return 'Animated Headline';
    }

    public function get_icon() {
        return 'eicon-animated-headline';
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
            'elementskey_animated_headline_section',
            [
                'label' => __( 'Animated Headline', 'elementskey' ),
            ]
        );

        $this->add_control(
            'headline_before',
            [
                'label' => __( 'Before Text', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'We',
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'word',
            [
                'label' => __( 'Word', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Design', 'elementskey' ),
            ]
        );

        $this->add_control(
            'headline_words',
            [
                'label' => __( 'Rotating Words', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [ 'word' => __( 'Design', 'elementskey' ) ],
                    [ 'word' => __( 'Create', 'elementskey' ) ],
                    [ 'word' => __( 'Inspire', 'elementskey' ) ],
                ],
                'title_field' => '{{{ word }}}',
            ]
        );

        $this->add_control(
            'headline_after',
            [
                'label' => __( 'After Text', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Beautiful Things', 'elementskey' ),
            ]
        );

        $this->add_control(
            'headline_speed',
            [
                'label' => __( 'Rotation Speed (ms)', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 2500,
                'min' => 500,
                'max' => 10000,
                'step' => 100,
            ]
        );

        $this->add_control(
            'headline_effect',
            [
                'label' => __( 'Animation Effect', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => __( 'Default', 'elementskey' ),
                    'blur'    => __( 'Blur', 'elementskey' ),
                    'flip'    => __( 'Flip', 'elementskey' ),
                    'slide'   => __( 'Slide', 'elementskey' ),
                    'pop'     => __( 'Pop', 'elementskey' ),
                ],
            ]
        );

        $this->end_controls_section();

        // Alignment
        $this->start_controls_section(
            'elementskey_headline_general_style',
            [
                'label' => __( 'General', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'headline_align',
            [
                'label' => __( 'Alignment', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [ 'title' => __( 'Left', 'elementskey' ), 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => __( 'Center', 'elementskey' ), 'icon' => 'eicon-text-align-center' ],
                    'right' => [ 'title' => __( 'Right', 'elementskey' ), 'icon' => 'eicon-text-align-right' ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-animated-headline' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'headline_spacing',
            [
                'label' => __( 'Spacing', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-animated-headline' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_headline_before_style',
            [
                'label' => __( 'Before Text', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'before_typography',
                'selector' => '{{WRAPPER}} .elementskey-headline-before',
            ]
        );

        $this->add_control(
            'before_color',
            [
                'label' => __( 'Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-headline-before' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_headline_words_style',
            [
                'label' => __( 'Rotating Words', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'words_typography',
                'selector' => '{{WRAPPER}} .elementskey-headline-words',
            ]
        );

        $this->add_control(
            'words_color',
            [
                'label' => __( 'Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-headline-words' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'words_highlight_color',
            [
                'label' => __( 'Highlight Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-headline-words' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'words_padding',
            [
                'label' => __( 'Highlight Padding', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-headline-words' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'words_radius',
            [
                'label' => __( 'Highlight Border Radius', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-headline-words' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_headline_after_style',
            [
                'label' => __( 'After Text', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'after_typography',
                'selector' => '{{WRAPPER}} .elementskey-headline-after',
            ]
        );

        $this->add_control(
            'after_color',
            [
                'label' => __( 'Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-headline-after' => 'color: {{VALUE}};',
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
        <div class="elementskey-animated-headline">
            <?php if ( $before ) : ?>
                <span class="elementskey-headline-before"><?php echo esc_html( $before ); ?></span>
            <?php endif; ?>
            <span class="elementskey-headline-words" data-speed="<?php echo esc_attr( $speed ); ?>" data-effect="<?php echo esc_attr( $settings['headline_effect'] ); ?>">
                <?php foreach ( $words as $index => $word ) : ?>
                    <span class="elementskey-headline-word<?php echo 0 === $index ? ' is-active' : ''; ?>"><?php echo esc_html( $word['word'] ); ?></span>
                <?php endforeach; ?>
            </span>
            <?php if ( $after ) : ?>
                <span class="elementskey-headline-after"><?php echo esc_html( $after ); ?></span>
            <?php endif; ?>
        </div>
        <?php
    }
}