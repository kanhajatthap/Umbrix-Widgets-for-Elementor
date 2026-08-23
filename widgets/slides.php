<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class ELEMENTSKEY_Slides_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'elementskey_slides';
    }

    public function get_title() {
        return 'Slides';
    }

    public function get_icon() {
        return 'eicon-slides';
    }

    public function get_categories() {
        return [ 'elementskey-elements' ];
    }

    public function get_style_depends() {
        return [ 'elementskey-content-style' ];
    }

    public function get_script_depends() {
        return [ 'swiper', 'elementskey-content-script' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'elementskey_slides_section',
            [
                'label' => __( 'Slides', 'elementskey' ),
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'slide_bg',
            [
                'label' => __( 'Background Image', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
            ]
        );

        $repeater->add_control(
            'slide_title',
            [
                'label' => __( 'Title', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Slide Title', 'elementskey' ),
            ]
        );

        $repeater->add_control(
            'slide_subtitle',
            [
                'label' => __( 'Subtitle', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Slide subtitle text here.',
            ]
        );

        $repeater->add_control(
            'slide_btn_text',
            [
                'label' => __( 'Button Text', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Learn More', 'elementskey' ),
            ]
        );

        $repeater->add_control(
            'slide_btn_url',
            [
                'label' => __( 'Button Link', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://example.com',
            ]
        );

        $this->add_control(
            'slides',
            [
                'label' => __( 'Slides', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [ 'slide_title' => __( 'Slide One', 'elementskey' ), 'slide_subtitle' => 'Welcome to our first slide.' ],
                    [ 'slide_title' => __( 'Slide Two', 'elementskey' ), 'slide_subtitle' => 'Here is the second slide.' ],
                ],
                'title_field' => '{{{ slide_title }}}',
            ]
        );

        $this->add_control(
            'slides_autoplay',
            [
                'label' => __( 'Autoplay', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'slides_dots',
            [
                'label' => __( 'Show Dots', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'slides_arrows',
            [
                'label' => __( 'Show Arrows', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_slides_height_section',
            [
                'label' => __( 'Height', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'slides_height',
            [
                'label' => __( 'Slide Height', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'vh' ],
                'range' => [ 'px' => [ 'min' => 200, 'max' => 900 ], 'vh' => [ 'min' => 30, 'max' => 100 ] ],
                'default' => [ 'size' => 500, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-slides .elementskey-slide' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'slides_overlay',
            [
                'label' => __( 'Overlay Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'rgba(15, 23, 42, 0.45)',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-slide-bg::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_slides_title_style',
            [
                'label' => __( 'Title', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'slide_title_typography',
                'selector' => '{{WRAPPER}} .elementskey-slide-title',
            ]
        );

        $this->add_control(
            'slide_title_color',
            [
                'label' => __( 'Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-slide-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_slides_subtitle_style',
            [
                'label' => __( 'Subtitle', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'slide_subtitle_typography',
                'selector' => '{{WRAPPER}} .elementskey-slide-subtitle',
            ]
        );

        $this->add_control(
            'slide_subtitle_color',
            [
                'label' => __( 'Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#e5e7eb',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-slide-subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_slides_navigation_style',
            [
                'label' => __( 'Navigation', 'elementskey' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'slides_arrow_size',
            [
                'label'      => __( 'Arrow Size', 'elementskey' ),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 24, 'max' => 120 ] ],
                'selectors'  => [
                    '{{WRAPPER}} .elementskey-slides .swiper-button-prev, {{WRAPPER}} .elementskey-slides .swiper-button-next' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'slides_arrow_icon_size',
            [
                'label'      => __( 'Arrow Icon Size', 'elementskey' ),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 10, 'max' => 50 ] ],
                'selectors'  => [
                    '{{WRAPPER}} .elementskey-slides .elementskey-custom-arrow .elementskey-arrow-icon, {{WRAPPER}} .elementskey-slides .elementskey-custom-arrow .elementskey-arrow-icon i, {{WRAPPER}} .elementskey-slides .elementskey-custom-arrow .elementskey-arrow-icon svg' => 'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'slides_prev_arrow_icon',
            [
                'label'   => __( 'Previous Arrow Icon', 'elementskey' ),
                'type'    => \Elementor\Controls_Manager::ICONS,
                'default' => [ 'value' => 'fas fa-chevron-left', 'library' => 'fa-solid' ],
            ]
        );

        $this->add_control(
            'slides_next_arrow_icon',
            [
                'label'   => __( 'Next Arrow Icon', 'elementskey' ),
                'type'    => \Elementor\Controls_Manager::ICONS,
                'default' => [ 'value' => 'fas fa-chevron-right', 'library' => 'fa-solid' ],
            ]
        );

        $this->add_control(
            'slides_arrow_color',
            [
                'label'     => __( 'Arrow Color', 'elementskey' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-slides .swiper-button-prev, {{WRAPPER}} .elementskey-slides .swiper-button-next' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'slides_arrow_background',
            [
                'label'     => __( 'Arrow Background', 'elementskey' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => 'rgba(0,0,0,0.4)',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-slides .swiper-button-prev, {{WRAPPER}} .elementskey-slides .swiper-button-next' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'slides_arrow_border',
                'selector' => '{{WRAPPER}} .elementskey-slides .elementskey-custom-arrow',
            ]
        );

        $this->add_responsive_control(
            'slides_arrow_radius',
            [
                'label'      => __( 'Arrow Border Radius', 'elementskey' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .elementskey-slides .elementskey-custom-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'slides_arrow_border_color',
            [
                'label'     => __( 'Arrow Border Color', 'elementskey' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-slides .elementskey-custom-arrow' => 'border-style: solid; border-width: 1px; border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'slides_arrow_offset',
            [
                'label'      => __( 'Arrow Offset', 'elementskey' ),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
                'selectors'  => [
                    '{{WRAPPER}} .elementskey-slides .swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementskey-slides .swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'slides_dots_color',
            [
                'label'     => __( 'Dots Color', 'elementskey' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#d9d9d9',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-slides .swiper-pagination-bullet' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'slides_dots_active_color',
            [
                'label'     => __( 'Active Dot Color', 'elementskey' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-slides .swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( empty( $settings['slides'] ) ) {
            return;
        }

        $previous_icon = ! empty( $settings['slides_prev_arrow_icon'] ) ? $settings['slides_prev_arrow_icon'] : [ 'value' => 'fas fa-chevron-left', 'library' => 'fa-solid' ];
        $next_icon     = ! empty( $settings['slides_next_arrow_icon'] ) ? $settings['slides_next_arrow_icon'] : [ 'value' => 'fas fa-chevron-right', 'library' => 'fa-solid' ];

        $slider_data = [
            'autoplay' => ( 'yes' === $settings['slides_autoplay'] ),
            'dots'     => ( 'yes' === $settings['slides_dots'] ),
            'arrows'   => ( 'yes' === $settings['slides_arrows'] ),
        ];
        ?>
        <div class="elementskey-slides-widget">
            <div class="elementskey-slides swiper" data-settings='<?php echo esc_attr( wp_json_encode( $slider_data ) ); ?>'>
                <div class="swiper-wrapper">
                    <?php foreach ( $settings['slides'] as $slide ) : ?>
                        <div class="swiper-slide elementskey-slide">
                            <?php if ( ! empty( $slide['slide_bg']['url'] ) ) : ?>
                                <div class="elementskey-slide-bg" style="background-image:url('<?php echo esc_url( $slide['slide_bg']['url'] ); ?>');"></div>
                            <?php endif; ?>
                            <div class="elementskey-slide-content">
                                <?php if ( ! empty( $slide['slide_title'] ) ) : ?>
                                    <h2 class="elementskey-slide-title"><?php echo esc_html( $slide['slide_title'] ); ?></h2>
                                <?php endif; ?>
                                <?php if ( ! empty( $slide['slide_subtitle'] ) ) : ?>
                                    <p class="elementskey-slide-subtitle"><?php echo esc_html( $slide['slide_subtitle'] ); ?></p>
                                <?php endif; ?>
                                <?php if ( ! empty( $slide['slide_btn_text'] ) ) : ?>
                                    <?php $btn_url = ! empty( $slide['slide_btn_url']['url'] ) ? $slide['slide_btn_url']['url'] : '#'; ?>
                                    <a class="elementskey-slide-btn" href="<?php echo esc_url( $btn_url ); ?>"><?php echo esc_html( $slide['slide_btn_text'] ); ?></a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if ( $slider_data['arrows'] ) : ?>
                    <button type="button" class="swiper-button-prev elementskey-custom-arrow" aria-label="Previous Slide"><span class="elementskey-arrow-icon"><?php \Elementor\Icons_Manager::render_icon( $previous_icon, [ 'aria-hidden' => 'true' ] ); ?></span></button>
                    <button type="button" class="swiper-button-next elementskey-custom-arrow" aria-label="Next Slide"><span class="elementskey-arrow-icon"><?php \Elementor\Icons_Manager::render_icon( $next_icon, [ 'aria-hidden' => 'true' ] ); ?></span></button>
                <?php endif; ?>
                <?php if ( $slider_data['dots'] ) : ?>
                    <div class="swiper-pagination"></div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}
