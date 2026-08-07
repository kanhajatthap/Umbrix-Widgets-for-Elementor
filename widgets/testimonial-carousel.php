<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Testimonial_Carousel_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_testimonial_carousel';
    }

    public function get_title() {
        return 'Testimonial Carousel';
    }

    public function get_icon() {
        return 'eicon-testimonial-carousel';
    }

    public function get_categories() {
        return [ 'elementstack-elements' ];
    }

    public function get_style_depends() {
        return [ 'bdea-content-style' ];
    }

    public function get_script_depends() {
        return [ 'swiper', 'bdea-content-script' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'bdea_testimonial_carousel_section',
            [
                'label' => 'Testimonials',
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'tc_avatar',
            [
                'label' => 'Avatar',
                'type' => \Elementor\Controls_Manager::MEDIA,
            ]
        );

        $repeater->add_control(
            'tc_name',
            [
                'label' => 'Name',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'John Doe',
            ]
        );

        $repeater->add_control(
            'tc_role',
            [
                'label' => 'Role',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'CEO',
            ]
        );

        $repeater->add_control(
            'tc_content',
            [
                'label' => 'Testimonial',
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => 'Amazing service, highly recommended!',
            ]
        );

        $repeater->add_control(
            'tc_rating',
            [
                'label' => 'Rating',
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
                'label' => 'Testimonials',
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [ 'tc_name' => 'Sarah Johnson', 'tc_role' => 'Designer' ],
                    [ 'tc_name' => 'Mike Chen', 'tc_role' => 'Developer' ],
                    [ 'tc_name' => 'Emma Wilson', 'tc_role' => 'Manager' ],
                ],
                'title_field' => '{{{ tc_name }}}',
            ]
        );

        $this->add_control(
            'tc_slides_view',
            [
                'label' => 'Slides to Show',
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 2,
                'min' => 1,
                'max' => 4,
            ]
        );

        $this->add_control(
            'tc_autoplay',
            [
                'label' => 'Autoplay',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'tc_dots',
            [
                'label' => 'Show Dots',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'tc_arrows',
            [
                'label' => 'Show Arrows',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        // Card Style
        $this->start_controls_section(
            'bdea_tc_card_style',
            [
                'label' => 'Card',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'tc_card_bg',
            [
                'label' => 'Background',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .bdea-testimonial-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'card_border',
                'selector' => '{{WRAPPER}} .bdea-testimonial-card',
            ]
        );

        $this->add_responsive_control(
            'card_border_radius',
            [
                'label' => 'Border Radius',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-testimonial-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'card_shadow',
                'selector' => '{{WRAPPER}} .bdea-testimonial-card',
            ]
        );

        $this->add_responsive_control(
            'card_padding',
            [
                'label' => 'Padding',
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
                    '{{WRAPPER}} .bdea-testimonial-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_gap',
            [
                'label' => 'Slide Gap',
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
            'bdea_tc_avatar_style',
            [
                'label' => 'Avatar',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'avatar_size',
            [
                'label' => 'Size',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 16, 'max' => 120 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-testimonial-author img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'avatar_radius',
            [
                'label' => 'Border Radius',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-testimonial-author img' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'avatar_border',
                'selector' => '{{WRAPPER}} .bdea-testimonial-author img',
            ]
        );

        $this->add_responsive_control(
            'avatar_spacing',
            [
                'label' => 'Spacing',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 30 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-testimonial-author img' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Typography
        $this->start_controls_section(
            'bdea_tc_typo',
            [
                'label' => 'Typography',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'tc_text_color',
            [
                'label' => 'Text Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4b5563',
                'selectors' => [
                    '{{WRAPPER}} .bdea-testimonial-content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'content_typo',
                'selector' => '{{WRAPPER}} .bdea-testimonial-content',
            ]
        );

        $this->add_control(
            'tc_name_color',
            [
                'label' => 'Name Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .bdea-testimonial-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'name_typo',
                'selector' => '{{WRAPPER}} .bdea-testimonial-name',
            ]
        );

        $this->add_control(
            'tc_role_color',
            [
                'label' => 'Role Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-testimonial-role' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Stars
        $this->start_controls_section(
            'bdea_tc_stars_style',
            [
                'label' => 'Stars',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'tc_star_color',
            [
                'label' => 'Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#f59e0b',
                'selectors' => [
                    '{{WRAPPER}} .bdea-testimonial-stars' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'star_size',
            [
                'label' => 'Size',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 10, 'max' => 40 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-testimonial-stars' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'star_spacing',
            [
                'label' => 'Spacing',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 10 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-testimonial-stars' => 'letter-spacing: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Navigation Style
        $this->start_controls_section(
            'bdea_tc_nav_style',
            [
                'label' => 'Navigation',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'arrow_color',
            [
                'label' => 'Arrow Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swiper-button-prev, {{WRAPPER}} .swiper-button-next' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrow_size',
            [
                'label' => 'Arrow Size',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 10, 'max' => 40 ] ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-button-prev, {{WRAPPER}} .swiper-button-next' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'dot_color',
            [
                'label' => 'Dot Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'dot_active_color',
            [
                'label' => 'Active Dot Color',
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

        $slider_data = [
            'autoplay' => ( 'yes' === $settings['tc_autoplay'] ),
            'dots'     => ( 'yes' === $settings['tc_dots'] ),
            'arrows'   => ( 'yes' === $settings['tc_arrows'] ),
            'slides'   => max( 1, (int) $settings['tc_slides_view'] ),
            'slidesTablet' => 1,
            'slidesMobile' => 1,
        ];
        ?>
        <div class="bdea-testimonial-carousel-widget">
            <div class="bdea-testimonial-carousel swiper" data-settings='<?php echo esc_attr( wp_json_encode( $slider_data ) ); ?>'>
                <div class="swiper-wrapper">
                    <?php foreach ( $settings['testimonials'] as $item ) : ?>
                        <div class="swiper-slide">
                            <div class="bdea-testimonial-card">
                                <div class="bdea-testimonial-stars">
                                    <?php
                                    $rating = max( 1, min( 5, (int) $item['tc_rating'] ) );
                                    for ( $i = 1; $i <= 5; $i++ ) {
                                        echo $i <= $rating ? '&#9733;' : '&#9734;';
                                    }
                                    ?>
                                </div>
                                <p class="bdea-testimonial-content"><?php echo esc_html( $item['tc_content'] ); ?></p>
                                <div class="bdea-testimonial-author">
                                    <?php if ( ! empty( $item['tc_avatar']['url'] ) ) : ?>
                                        <img src="<?php echo esc_url( $item['tc_avatar']['url'] ); ?>" alt="<?php echo esc_attr( $item['tc_name'] ); ?>" loading="lazy" />
                                    <?php endif; ?>
                                    <div>
                                        <div class="bdea-testimonial-name"><?php echo esc_html( $item['tc_name'] ); ?></div>
                                        <?php if ( ! empty( $item['tc_role'] ) ) : ?>
                                            <div class="bdea-testimonial-role"><?php echo esc_html( $item['tc_role'] ); ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if ( $slider_data['arrows'] ) : ?>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                <?php endif; ?>
                <?php if ( $slider_data['dots'] ) : ?>
                    <div class="swiper-pagination"></div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}