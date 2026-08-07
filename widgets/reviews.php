<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Reviews_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_reviews';
    }

    public function get_title() {
        return 'Reviews';
    }

    public function get_icon() {
        return 'eicon-review';
    }

    public function get_categories() {
        return [ 'elementstack-elements' ];
    }

    public function get_style_depends() {
        return [ 'bdea-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'bdea_reviews_section',
            [
                'label' => 'Reviews',
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'rv_avatar',
            [
                'label' => 'Avatar',
                'type' => \Elementor\Controls_Manager::MEDIA,
            ]
        );

        $repeater->add_control(
            'rv_name',
            [
                'label' => 'Name',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'John Doe',
            ]
        );

        $repeater->add_control(
            'rv_role',
            [
                'label' => 'Role',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Verified Customer',
            ]
        );

        $repeater->add_control(
            'rv_comment',
            [
                'label' => 'Review',
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => 'Great product, would buy again!',
            ]
        );

        $repeater->add_control(
            'rv_rating',
            [
                'label' => 'Rating',
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 5,
                'min' => 1,
                'max' => 5,
                'step' => 1,
            ]
        );

        $repeater->add_control(
            'rv_provider',
            [
                'label' => 'Provider',
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'Google, Trustpilot...',
            ]
        );

        $this->add_control(
            'reviews',
            [
                'label' => 'Reviews',
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [ 'rv_name' => 'Alice Brown', 'rv_comment' => 'Fantastic experience from start to finish.' ],
                    [ 'rv_name' => 'David Miller', 'rv_comment' => 'High quality and great support team.' ],
                    [ 'rv_name' => 'Grace Lee', 'rv_comment' => 'Would recommend to anyone looking.' ],
                ],
                'title_field' => '{{{ rv_name }}}',
            ]
        );

        $this->add_control(
            'rv_columns',
            [
                'label' => 'Columns',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '3',
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ],
            ]
        );

        $this->end_controls_section();

        // Card Style
        $this->start_controls_section(
            'bdea_reviews_card_style',
            [
                'label' => 'Card',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'rv_card_bg',
            [
                'label' => 'Background',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .bdea-review-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'card_border',
                'selector' => '{{WRAPPER}} .bdea-review-card',
            ]
        );

        $this->add_responsive_control(
            'card_border_radius',
            [
                'label' => 'Border Radius',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-review-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'card_shadow',
                'selector' => '{{WRAPPER}} .bdea-review-card',
            ]
        );

        $this->add_responsive_control(
            'card_padding',
            [
                'label' => 'Padding',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 24,
                    'right' => 24,
                    'bottom' => 24,
                    'left' => 24,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-review-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_gap',
            [
                'label' => 'Column Gap',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-reviews-widget' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Avatar Style
        $this->start_controls_section(
            'bdea_reviews_avatar_style',
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
                    '{{WRAPPER}} .bdea-review-author img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .bdea-review-author img' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
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
                    '{{WRAPPER}} .bdea-review-author img' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Typography
        $this->start_controls_section(
            'bdea_reviews_typo',
            [
                'label' => 'Typography',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'text_typo',
                'selector' => '{{WRAPPER}} .bdea-review-comment',
            ]
        );

        $this->add_control(
            'rv_text_color',
            [
                'label' => 'Review Text Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4b5563',
                'selectors' => [
                    '{{WRAPPER}} .bdea-review-comment' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'rv_name_color',
            [
                'label' => 'Name Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .bdea-review-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'name_typo',
                'selector' => '{{WRAPPER}} .bdea-review-name',
            ]
        );

        $this->add_control(
            'role_color',
            [
                'label' => 'Role Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-review-role' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Stars
        $this->start_controls_section(
            'bdea_reviews_stars_style',
            [
                'label' => 'Stars',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'rv_star_color',
            [
                'label' => 'Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#f59e0b',
                'selectors' => [
                    '{{WRAPPER}} .bdea-review-stars' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .bdea-review-stars' => 'font-size: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .bdea-review-stars' => 'letter-spacing: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( empty( $settings['reviews'] ) ) {
            return;
        }

        $cols = (int) $settings['rv_columns'];
        if ( $cols < 1 ) { $cols = 3; }
        ?>
        <div class="bdea-reviews-widget bdea-cols-<?php echo esc_attr( $cols ); ?>">
            <?php foreach ( $settings['reviews'] as $review ) : ?>
                <div class="bdea-review-card">
                    <div class="bdea-review-stars">
                        <?php
                        $rating = max( 1, min( 5, (int) $review['rv_rating'] ) );
                        for ( $i = 1; $i <= 5; $i++ ) {
                            echo $i <= $rating ? '&#9733;' : '&#9734;';
                        }
                        ?>
                    </div>
                    <p class="bdea-review-comment"><?php echo esc_html( $review['rv_comment'] ); ?></p>
                    <div class="bdea-review-author">
                        <?php if ( ! empty( $review['rv_avatar']['url'] ) ) : ?>
                            <img src="<?php echo esc_url( $review['rv_avatar']['url'] ); ?>" alt="<?php echo esc_attr( $review['rv_name'] ); ?>" loading="lazy" />
                        <?php endif; ?>
                        <div>
                            <div class="bdea-review-name"><?php echo esc_html( $review['rv_name'] ); ?></div>
                            <div class="bdea-review-role">
                                <?php echo esc_html( $review['rv_role'] ); ?>
                                <?php if ( ! empty( $review['rv_provider'] ) ) : ?>
                                    <span class="bdea-review-provider">&middot; <?php echo esc_html( $review['rv_provider'] ); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }
}