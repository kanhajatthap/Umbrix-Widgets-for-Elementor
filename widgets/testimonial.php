<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class ELEMENTSKEY_Testimonial_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'elementskey_testimonial';
    }

    public function get_title() {
        return 'Testimonial';
    }

    public function get_icon() {
        return 'eicon-testimonial';
    }

    public function get_categories() {
        return [ 'elementskey-elements' ];
    }

    public function get_style_depends() {
        return [ 'elementskey-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'elementskey_testimonial_section',
            [
                'label' => __( 'Testimonial', 'elementskey' ),
            ]
        );

        $this->add_control(
            'testimonial_quote',
            [
                'label' => __( 'Testimonial', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => 'This product completely changed how we work. Setup was easy and the support team is fantastic.',
                'rows' => 5,
            ]
        );

        $this->add_control(
            'testimonial_author',
            [
                'label' => __( 'Author', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'John Smith', 'elementskey' ),
            ]
        );

        $this->add_control(
            'testimonial_role',
            [
                'label' => __( 'Role / Company', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'CEO, Example Inc.',
            ]
        );

        $this->add_control(
            'testimonial_avatar',
            [
                'label' => __( 'Avatar', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [],
            ]
        );

        $this->add_control(
            'testimonial_rating',
            [
                'label' => __( 'Rating', 'elementskey' ),
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
                'label' => __( 'Alignment', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [ 'title' => __( 'Left', 'elementskey' ), 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => __( 'Center', 'elementskey' ), 'icon' => 'eicon-text-align-center' ],
                    'right' => [ 'title' => __( 'Right', 'elementskey' ), 'icon' => 'eicon-text-align-right' ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-testimonial-widget' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_testimonial_card_style',
            [
                'label' => __( 'Card', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'card_bg',
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
            'card_radius',
            [
                'label' => __( 'Border Radius', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
                'default' => [ 'size' => 12, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-testimonial-card' => 'border-radius: {{SIZE}}{{UNIT}};',
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
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-testimonial-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_testimonial_content_style',
            [
                'label' => __( 'Content', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'quote_typography',
                'selector' => '{{WRAPPER}} .elementskey-testimonial-quote',
            ]
        );

        $this->add_control(
            'quote_color',
            [
                'label' => __( 'Quote Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-testimonial-quote' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'quote_mark_color',
            [
                'label' => __( 'Quote Mark Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-testimonial-mark' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'author_typography',
                'selector' => '{{WRAPPER}} .elementskey-testimonial-author',
            ]
        );

        $this->add_control(
            'author_color',
            [
                'label' => __( 'Author Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-testimonial-author' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'role_typography',
                'selector' => '{{WRAPPER}} .elementskey-testimonial-role',
            ]
        );

        $this->add_control(
            'role_color',
            [
                'label' => __( 'Role Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-testimonial-role' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'star_color',
            [
                'label' => __( 'Star Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#f59e0b',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-testimonial-star.is-filled' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'star_empty_color',
            [
                'label' => __( 'Empty Star Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-testimonial-star:not(.is-filled)' => 'color: {{VALUE}};',
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
        <div class="elementskey-testimonial-widget">
            <div class="elementskey-testimonial-card">
                <span class="elementskey-testimonial-mark">&#8220;</span>

                <?php if ( 'none' !== $rating ) : ?>
                    <div class="elementskey-testimonial-rating" aria-label="<?php echo esc_attr( $rating ); ?> star rating">
                        <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                            <span class="elementskey-testimonial-star<?php echo $i <= (int) $rating ? ' is-filled' : ''; ?>">&#9733;</span>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>

                <?php if ( $quote ) : ?>
                    <p class="elementskey-testimonial-quote"><?php echo esc_html( $quote ); ?></p>
                <?php endif; ?>

                <div class="elementskey-testimonial-author-row">
                    <?php if ( $avatar ) : ?>
                        <img class="elementskey-testimonial-avatar" src="<?php echo esc_url( $avatar ); ?>" alt="<?php echo esc_attr( $author ); ?>" loading="lazy" />
                    <?php endif; ?>
                    <div class="elementskey-testimonial-author-meta">
                        <?php if ( $author ) : ?>
                            <div class="elementskey-testimonial-author"><?php echo esc_html( $author ); ?></div>
                        <?php endif; ?>
                        <?php if ( $role ) : ?>
                            <div class="elementskey-testimonial-role"><?php echo esc_html( $role ); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}