<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Testimonial_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_testimonial';
    }

    public function get_title() {
        return 'Testimonial';
    }

    public function get_icon() {
        return 'eicon-testimonial';
    }

    public function get_categories() {
        return [ 'elementstack-elements' ];
    }

    public function get_style_depends() {
        return [ 'bdea-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'bdea_testimonial_section',
            [
                'label' => 'Testimonial',
            ]
        );

        $this->add_control(
            'testimonial_quote',
            [
                'label' => 'Testimonial',
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => 'This product completely changed how we work. Setup was easy and the support team is fantastic.',
                'rows' => 5,
            ]
        );

        $this->add_control(
            'testimonial_author',
            [
                'label' => 'Author',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'John Smith',
            ]
        );

        $this->add_control(
            'testimonial_role',
            [
                'label' => 'Role / Company',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'CEO, Example Inc.',
            ]
        );

        $this->add_control(
            'testimonial_avatar',
            [
                'label' => 'Avatar',
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [],
            ]
        );

        $this->add_control(
            'testimonial_rating',
            [
                'label' => 'Rating',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '5',
                'options' => [
                    'none' => 'None',
                    '1' => '1 Star',
                    '2' => '2 Stars',
                    '3' => '3 Stars',
                    '4' => '4 Stars',
                    '5' => '5 Stars',
                ],
            ]
        );

        $this->add_responsive_control(
            'testimonial_align',
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
                    '{{WRAPPER}} .bdea-testimonial-widget' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_testimonial_card_style',
            [
                'label' => 'Card',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'card_bg',
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
            'card_radius',
            [
                'label' => 'Border Radius',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
                'default' => [ 'size' => 12, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-testimonial-card' => 'border-radius: {{SIZE}}{{UNIT}};',
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
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-testimonial-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_testimonial_content_style',
            [
                'label' => 'Content',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'quote_typography',
                'selector' => '{{WRAPPER}} .bdea-testimonial-quote',
            ]
        );

        $this->add_control(
            'quote_color',
            [
                'label' => 'Quote Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-testimonial-quote' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'quote_mark_color',
            [
                'label' => 'Quote Mark Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .bdea-testimonial-mark' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'author_typography',
                'selector' => '{{WRAPPER}} .bdea-testimonial-author',
            ]
        );

        $this->add_control(
            'author_color',
            [
                'label' => 'Author Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-testimonial-author' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'role_typography',
                'selector' => '{{WRAPPER}} .bdea-testimonial-role',
            ]
        );

        $this->add_control(
            'role_color',
            [
                'label' => 'Role Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-testimonial-role' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $quote  = ! empty( $settings['testimonial_quote'] ) ? $settings['testimonial_quote'] : '';
        $author = ! empty( $settings['testimonial_author'] ) ? $settings['testimonial_author'] : '';
        $role   = ! empty( $settings['testimonial_role'] ) ? $settings['testimonial_role'] : '';
        $rating = ! empty( $settings['testimonial_rating'] ) ? $settings['testimonial_rating'] : 'none';
        $avatar = ! empty( $settings['testimonial_avatar']['url'] ) ? $settings['testimonial_avatar']['url'] : '';
        ?>
        <div class="bdea-testimonial-widget">
            <div class="bdea-testimonial-card">
                <span class="bdea-testimonial-mark">&#8220;</span>

                <?php if ( 'none' !== $rating ) : ?>
                    <div class="bdea-testimonial-rating" aria-label="<?php echo esc_attr( $rating ); ?> star rating">
                        <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                            <span class="bdea-testimonial-star<?php echo $i <= (int) $rating ? ' is-filled' : ''; ?>">&#9733;</span>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>

                <?php if ( $quote ) : ?>
                    <p class="bdea-testimonial-quote"><?php echo esc_html( $quote ); ?></p>
                <?php endif; ?>

                <div class="bdea-testimonial-author-row">
                    <?php if ( $avatar ) : ?>
                        <img class="bdea-testimonial-avatar" src="<?php echo esc_url( $avatar ); ?>" alt="<?php echo esc_attr( $author ); ?>" loading="lazy" />
                    <?php endif; ?>
                    <div class="bdea-testimonial-author-meta">
                        <?php if ( $author ) : ?>
                            <div class="bdea-testimonial-author"><?php echo esc_html( $author ); ?></div>
                        <?php endif; ?>
                        <?php if ( $role ) : ?>
                            <div class="bdea-testimonial-role"><?php echo esc_html( $role ); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}