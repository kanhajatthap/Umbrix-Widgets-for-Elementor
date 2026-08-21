<?php
/**
 * ElementKey Lite
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Star_Rating_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_star_rating';
    }

    public function get_title() {
        return 'Star Rating';
    }

    public function get_icon() {
        return 'eicon-rating';
    }

    public function get_categories() {
        return [ 'elementkey-lite-elements' ];
    }

    public function get_style_depends() {
        return [ 'bdea-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'bdea_rating_content_section',
            [
                'label' => __( 'Rating', 'elementkey-lite' ),
            ]
        );

        $this->add_control(
            'rating_value',
            [
                'label' => __( 'Rating', 'elementkey-lite' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ '' ],
                'range' => [
                    '' => [ 'min' => 0, 'max' => 5, 'step' => 0.1 ],
                ],
                'default' => [ 'size' => 4.5 ],
            ]
        );

        $this->add_control(
            'rating_scale',
            [
                'label' => __( 'Scale', 'elementkey-lite' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '5',
                'options' => [
                    '5' => '0 ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Å“ 5',
                    '10' => '0 ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Å“ 10',
                ],
            ]
        );

        $this->add_control(
            'show_number',
            [
                'label' => __( 'Show Rating Number', 'elementkey-lite' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'rating_label',
            [
                'label' => __( 'Label (optional)', 'elementkey-lite' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'e.g. 5.0 out of 5',
            ]
        );

        $this->add_responsive_control(
            'rating_align',
            [
                'label' => __( 'Alignment', 'elementkey-lite' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [ 'title' => __( 'Left', 'elementkey-lite' ), 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => __( 'Center', 'elementkey-lite' ), 'icon' => 'eicon-text-align-center' ],
                    'right' => [ 'title' => __( 'Right', 'elementkey-lite' ), 'icon' => 'eicon-text-align-right' ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .bdea-star-rating' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_rating_style_section',
            [
                'label' => __( 'Style', 'elementkey-lite' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'star_color',
            [
                'label' => __( 'Star Color', 'elementkey-lite' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#f7b500',
                'selectors' => [
                    '{{WRAPPER}} .bdea-star-rating .bdea-star-filled' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .bdea-star-rating .bdea-star-half-fill' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'empty_star_color',
            [
                'label' => __( 'Empty Star Color', 'elementkey-lite' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#d9d9d9',
                'selectors' => [
                    '{{WRAPPER}} .bdea-star-rating .bdea-star-empty' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'star_size',
            [
                'label' => __( 'Star Size', 'elementkey-lite' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range' => [ 'px' => [ 'min' => 10, 'max' => 80 ], 'em' => [ 'min' => 0.5, 'max' => 5 ] ],
                'default' => [ 'size' => 22, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-star-rating .bdea-star-icons .bdea-star' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'star_gap',
            [
                'label' => __( 'Star Spacing', 'elementkey-lite' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 30 ] ],
                'default' => [ 'size' => 4, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-star-rating .bdea-star-icons .bdea-star' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'number_typography',
                'selector' => '{{WRAPPER}} .bdea-star-rating .bdea-rating-number',
            ]
        );

        $this->add_control(
            'number_color',
            [
                'label' => __( 'Number Color', 'elementkey-lite' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#555555',
                'selectors' => [
                    '{{WRAPPER}} .bdea-star-rating .bdea-rating-number' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'label_typography',
                'selector' => '{{WRAPPER}} .bdea-star-rating .bdea-rating-label',
            ]
        );

        $this->add_control(
            'label_color',
            [
                'label' => __( 'Label Color', 'elementkey-lite' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#555555',
                'selectors' => [
                    '{{WRAPPER}} .bdea-star-rating .bdea-rating-label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'rating_padding',
            [
                'label' => __( 'Padding', 'elementkey-lite' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-star-rating' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $scale = ! empty( $settings['rating_scale'] ) ? (int) $settings['rating_scale'] : 5;
        $value = ! empty( $settings['rating_value']['size'] ) ? (float) $settings['rating_value']['size'] : 0;

        $star_count = 5;
        if ( 10 === $scale ) {
            $star_count = 10;
        }

        $normalized = ( $value / $scale ) * $star_count;
        $full_stars = (int) floor( $normalized );
        $fraction   = $normalized - $full_stars;

        $show_number = ( 'yes' === $settings['show_number'] );
        $label       = ! empty( $settings['rating_label'] ) ? $settings['rating_label'] : '';
        ?>
        <div class="bdea-star-rating" role="img" aria-label="<?php echo esc_attr( sprintf( 'Rated %s out of %d', number_format( $value, 1 ), $star_count ) ); ?>">
            <div class="bdea-star-rating-row">
                <span class="bdea-star-icons">
                    <?php for ( $i = 0; $i < $star_count; $i++ ) : ?>
                        <?php if ( $i < $full_stars ) : ?>
                            <span class="bdea-star bdea-star-filled">&#9733;</span>
                        <?php elseif ( $i === $full_stars && $fraction >= 0.25 ) : ?>
                            <span class="bdea-star bdea-star-half" aria-hidden="true">
                                <span class="bdea-star-half-bg">&#9733;</span>
                                <span class="bdea-star-half-fill">&#9733;</span>
                            </span>
                        <?php else : ?>
                            <span class="bdea-star bdea-star-empty">&#9733;</span>
                        <?php endif; ?>
                    <?php endfor; ?>
                </span>

                <?php if ( $show_number ) : ?>
                    <span class="bdea-rating-number"><?php echo esc_html( number_format( $value, 1 ) ); ?></span>
                <?php endif; ?>

                <?php if ( $label ) : ?>
                    <span class="bdea-rating-label"><?php echo esc_html( $label ); ?></span>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}
