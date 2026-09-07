<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class ELEMENTSKEY_Testimonial_Carousel_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'elementskey_testimonial_carousel';
    }

    public function get_title() {
        return 'Testimonial Carousel';
    }

    public function get_icon() {
        return 'eicon-testimonial-carousel';
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
            'elementskey_testimonial_carousel_section',
            [
                'label' => __( 'Testimonials', 'elementskey' ),
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'tc_avatar',
            [
                'label' => __( 'Avatar', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
            ]
        );

        $repeater->add_control(
            'tc_name',
            [
                'label' => __( 'Name', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'John Doe', 'elementskey' ),
            ]
        );

        $repeater->add_control(
            'tc_role',
            [
                'label' => __( 'Role', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'CEO', 'elementskey' ),
            ]
        );

        $repeater->add_control(
            'tc_content',
            [
                'label' => __( 'Testimonial', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => 'Amazing service, highly recommended!',
            ]
        );

        $repeater->add_control(
            'tc_rating',
            [
                'label' => __( 'Rating', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 5,
                'min' => 1,
                'max' => 5,
                'step' => 1,
            ]
        );

        $this->add_control(
            'testimonials',
            [
                'label' => __( 'Testimonials', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [ 'tc_name' => __( 'Sarah Johnson', 'elementskey' ), 'tc_role' => __( 'Designer', 'elementskey' ) ],
                    [ 'tc_name' => __( 'Mike Chen', 'elementskey' ), 'tc_role' => __( 'Developer', 'elementskey' ) ],
                    [ 'tc_name' => __( 'Emma Wilson', 'elementskey' ), 'tc_role' => __( 'Manager', 'elementskey' ) ],
                ],
                'title_field' => '{{{ tc_name }}}',
            ]
        );

        $this->add_control(
            'tc_slides_view',
            [
                'label' => __( 'Slides to Show', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 2,
                'min' => 1,
                'max' => 4,
            ]
        );

        $this->add_control(
            'tc_autoplay',
            [
                'label' => __( 'Autoplay', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'tc_dots',
            [
                'label' => __( 'Show Dots', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'tc_arrows',
            [
                'label' => __( 'Show Arrows', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        // Card Style
        $this->start_controls_section(
            'elementskey_tc_card_style',
            [
                'label' => __( 'Card', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'tc_card_bg',
            [
                'label' => __( 'Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-testimonial-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'card_border',
                'selector' => '{{WRAPPER}} .elementskey-testimonial-card',
            ]
        );

        $this->add_responsive_control(
            'card_border_radius',
            [
                'label' => __( 'Border Radius', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-testimonial-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'card_shadow',
                'selector' => '{{WRAPPER}} .elementskey-testimonial-card',
            ]
        );

        $this->add_responsive_control(
            'card_padding',
            [
                'label' => __( 'Padding', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 30,
                    'right' => 30,
                    'bottom' => 30,
                    'left' => 30,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-testimonial-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_gap',
            [
                'label' => __( 'Slide Gap', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-wrapper' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Avatar Style
        $this->start_controls_section(
            'elementskey_tc_avatar_style',
            [
                'label' => __( 'Avatar', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'avatar_size',
            [
                'label' => __( 'Size', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 16, 'max' => 120 ] ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-testimonial-author img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'avatar_radius',
            [
                'label' => __( 'Border Radius', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-testimonial-author img' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'avatar_border',
                'selector' => '{{WRAPPER}} .elementskey-testimonial-author img',
            ]
        );

        $this->add_responsive_control(
            'avatar_spacing',
            [
                'label' => __( 'Spacing', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 30 ] ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-testimonial-author img' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Typography
        $this->start_controls_section(
            'elementskey_tc_typo',
            [
                'label' => __( 'Typography', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'tc_text_color',
            [
                'label' => __( 'Text Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4b5563',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-testimonial-content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'content_typo',
                'selector' => '{{WRAPPER}} .elementskey-testimonial-content',
            ]
        );

        $this->add_control(
            'tc_name_color',
            [
                'label' => __( 'Name Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-testimonial-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'name_typo',
                'selector' => '{{WRAPPER}} .elementskey-testimonial-name',
            ]
        );

        $this->add_control(
            'tc_role_color',
            [
                'label' => __( 'Role Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-testimonial-role' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Stars
        $this->start_controls_section(
            'elementskey_tc_stars_style',
            [
                'label' => __( 'Stars', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'tc_star_color',
            [
                'label' => __( 'Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#f59e0b',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-testimonial-stars' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'star_size',
            [
                'label' => __( 'Size', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 10, 'max' => 40 ] ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-testimonial-stars' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'star_spacing',
            [
                'label' => __( 'Spacing', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 10 ] ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-testimonial-stars' => 'letter-spacing: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Navigation Style
        $this->start_controls_section(
            'elementskey_tc_nav_style',
            [
                'label' => __( 'Navigation', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'arrow_color',
            [
                'label' => __( 'Arrow Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swiper-button-prev, {{WRAPPER}} .swiper-button-next' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_background',
            [
                'label'     => __( 'Arrow Background', 'elementskey' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-custom-arrow' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'arrow_border',
                'selector' => '{{WRAPPER}} .elementskey-custom-arrow',
            ]
        );

        $this->add_responsive_control(
            'arrow_radius',
            [
                'label'      => __( 'Arrow Border Radius', 'elementskey' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .elementskey-custom-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_border_color',
            [
                'label'     => __( 'Arrow Border Color', 'elementskey' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-custom-arrow' => 'border-style: solid; border-width: 1px; border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrow_size',
            [
                'label'      => __( 'Arrow Size', 'elementskey' ),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 20, 'max' => 120 ] ],
                'default'    => [
                    'size' => 40,
                    'unit' => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .swiper-button-prev, {{WRAPPER}} .swiper-button-next, {{WRAPPER}} .elementskey-custom-arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrow_icon_size',
            [
                'label'      => __( 'Arrow Icon Size', 'elementskey' ),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [ 'px' => [ 'min' => 10, 'max' => 60 ] ],
                'default'    => [
                    'size' => 20,
                    'unit' => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .elementskey-custom-arrow .elementskey-arrow-icon, {{WRAPPER}} .elementskey-custom-arrow .elementskey-arrow-icon i, {{WRAPPER}} .elementskey-custom-arrow .elementskey-arrow-icon svg' => 'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'prev_arrow_icon',
            [
                'label'   => __( 'Previous Arrow Icon', 'elementskey' ),
                'type'    => \Elementor\Controls_Manager::ICONS,
                'default' => [ 'value' => 'fas fa-chevron-left', 'library' => 'fa-solid' ],
            ]
        );

        $this->add_control(
            'next_arrow_icon',
            [
                'label'   => __( 'Next Arrow Icon', 'elementskey' ),
                'type'    => \Elementor\Controls_Manager::ICONS,
                'default' => [ 'value' => 'fas fa-chevron-right', 'library' => 'fa-solid' ],
            ]
        );

        $this->add_control(
            'dot_color',
            [
                'label' => __( 'Dot Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'dot_active_color',
            [
                'label' => __( 'Active Dot Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet-active' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( empty( $settings['testimonials'] ) ) {
            return;
        }

        $previous_icon = ! empty( $settings['prev_arrow_icon'] ) ? $settings['prev_arrow_icon'] : [ 'value' => 'fas fa-chevron-left', 'library' => 'fa-solid' ];
        $next_icon     = ! empty( $settings['next_arrow_icon'] ) ? $settings['next_arrow_icon'] : [ 'value' => 'fas fa-chevron-right', 'library' => 'fa-solid' ];

        $slider_data = [
            'autoplay' => ( 'yes' === $settings['tc_autoplay'] ),
            'dots'     => ( 'yes' === $settings['tc_dots'] ),
            'arrows'   => ( 'yes' === $settings['tc_arrows'] ),
            'slides'   => max( 1, (int) $settings['tc_slides_view'] ),
            'slidesTablet' => 1,
            'slidesMobile' => 1,
        ];
        ?>
        <div class="elementskey-testimonial-carousel-widget">
            <div class="elementskey-testimonial-carousel swiper" data-settings='<?php echo esc_attr( wp_json_encode( $slider_data ) ); ?>'>
                <div class="swiper-wrapper">
                    <?php foreach ( $settings['testimonials'] as $item ) : ?>
                        <div class="swiper-slide">
                            <div class="elementskey-testimonial-card">
                                <div class="elementskey-testimonial-stars">
                                    <?php
                                    $rating = max( 1, min( 5, (int) $item['tc_rating'] ) );
                                    for ( $i = 1; $i <= 5; $i++ ) {
                                        echo $i <= $rating ? '&#9733;' : '&#9734;';
                                    }
                                    ?>
                                </div>
                                <p class="elementskey-testimonial-content"><?php echo esc_html( $item['tc_content'] ); ?></p>
                                <div class="elementskey-testimonial-author">
                                    <?php if ( ! empty( $item['tc_avatar']['url'] ) ) : ?>
                                        <img src="<?php echo esc_url( $item['tc_avatar']['url'] ); ?>" alt="<?php echo esc_attr( $item['tc_name'] ); ?>" loading="lazy" />
                                    <?php endif; ?>
                                    <div>
                                        <div class="elementskey-testimonial-name"><?php echo esc_html( $item['tc_name'] ); ?></div>
                                        <?php if ( ! empty( $item['tc_role'] ) ) : ?>
                                            <div class="elementskey-testimonial-role"><?php echo esc_html( $item['tc_role'] ); ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
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