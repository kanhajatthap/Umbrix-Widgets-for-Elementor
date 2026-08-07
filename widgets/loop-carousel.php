<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Loop_Carousel_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_loop_carousel';
    }

    public function get_title() {
        return 'Loop Carousel';
    }

    public function get_icon() {
        return 'eicon-slider-push';
    }

    public function get_categories() {
        return [ 'elementstack-elements' ];
    }

    public function get_style_depends() {
        return [ 'swiper', 'bdea-content-style' ];
    }

    public function get_script_depends() {
        return [ 'swiper', 'bdea-carousel-script' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'bdea_loop_template_section',
            [
                'label' => 'Loop Template',
            ]
        );

        $this->add_control(
            'loop_template',
            [
                'label' => 'Select Loop Template',
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => bdea_loop_template_options(),
                'description' => 'Select an Elementor template to render each loop item. Leave empty to use the built-in card below.',
            ]
        );

        $this->add_control(
            'post_type',
            [
                'label' => 'Post Type',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'post',
                'options' => bdea_widget_post_types(),
                'description' => 'Choose the post type this loop should query.',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_query_section',
            [
                'label' => 'Query',
            ]
        );

        $this->add_control(
            'include_cats',
            [
                'label' => 'Categories',
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => bdea_widget_terms_list( 'category' ),
                'description' => 'Leave empty for all categories.',
            ]
        );

        $this->add_control(
            'include_tags',
            [
                'label' => 'Tags',
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => bdea_widget_terms_list( 'post_tag' ),
                'description' => 'Leave empty for all tags.',
            ]
        );

        $this->add_control(
            'exclude_ids',
            [
                'label' => 'Exclude Posts (IDs, comma separated)',
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => '12, 45, 89',
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label' => 'Number of Posts',
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 8,
                'min' => 1,
                'max' => 100,
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label' => 'Order By',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'date',
                'options' => [
                    'date' => 'Date',
                    'modified' => 'Modified Date',
                    'title' => 'Title',
                    'menu_order' => 'Menu Order',
                    'rand' => 'Random',
                ],
            ]
        );

        $this->add_control(
            'order',
            [
                'label' => 'Order',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'DESC',
                'options' => [
                    'ASC' => 'Ascending',
                    'DESC' => 'Descending',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_behavior_section',
            [
                'label' => 'Behavior',
            ]
        );

        $this->add_responsive_control(
            'slides_per_view',
            [
                'label' => 'Slides Per View',
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 6,
                'default' => 3,
                'tablet_default' => 2,
                'mobile_default' => 1,
            ]
        );

        $this->add_responsive_control(
            'space_between',
            [
                'label' => 'Space Between (px)',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 80 ] ],
                'default' => [ 'size' => 24, 'unit' => 'px' ],
            ]
        );

        $this->add_control(
            'show_arrows',
            [
                'label' => 'Show Arrows',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_dots',
            [
                'label' => 'Show Dots',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label' => 'Autoplay',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'autoplay_delay',
            [
                'label' => 'Autoplay Speed (ms)',
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 3500,
                'min' => 500,
                'max' => 15000,
                'step' => 100,
                'condition' => [ 'autoplay' => 'yes' ],
            ]
        );

        $this->add_control(
            'pause_on_hover',
            [
                'label' => 'Pause on Hover',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [ 'autoplay' => 'yes' ],
            ]
        );

        $this->add_control(
            'loop',
            [
                'label' => 'Loop',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'transition_speed',
            [
                'label' => 'Transition Speed (ms)',
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 650,
                'min' => 100,
                'max' => 5000,
                'step' => 50,
            ]
        );

        $this->add_control(
            'equal_height',
            [
                'label' => 'Equal Height Cards',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_card_content_section',
            [
                'label' => 'Card Content',
            ]
        );

        $this->add_control(
            'show_thumbnail',
            [
                'label' => 'Show Thumbnail',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_title',
            [
                'label' => 'Show Title',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_excerpt',
            [
                'label' => 'Show Excerpt',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'excerpt_length',
            [
                'label' => 'Excerpt Length (words)',
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 14,
                'min' => 3,
                'max' => 120,
                'condition' => [ 'show_excerpt' => 'yes' ],
            ]
        );

        $this->add_control(
            'show_meta',
            [
                'label' => 'Show Meta (date)',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_card_style_section',
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
                'selectors' => [
                    '{{WRAPPER}} .bdea-loop-carousel-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'card_border',
                'selector' => '{{WRAPPER}} .bdea-loop-carousel-card',
            ]
        );

        $this->add_responsive_control(
            'card_radius',
            [
                'label' => 'Border Radius',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-loop-carousel-card' => 'border-radius: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .bdea-loop-carousel-thumb img' => 'border-radius: {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} 0 0;',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'card_shadow',
                'selector' => '{{WRAPPER}} .bdea-loop-carousel-card',
            ]
        );

        $this->add_responsive_control(
            'card_padding',
            [
                'label' => 'Padding',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-loop-carousel-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_image_style_section',
            [
                'label' => 'Thumbnail',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_height',
            [
                'label' => 'Height',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 80, 'max' => 700 ] ],
                'default' => [ 'size' => 200, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-loop-carousel-thumb img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_typography_section',
            [
                'label' => 'Typography',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .bdea-loop-carousel-title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => 'Title Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-loop-carousel-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'excerpt_typography',
                'selector' => '{{WRAPPER}} .bdea-loop-carousel-excerpt',
            ]
        );

        $this->add_control(
            'excerpt_color',
            [
                'label' => 'Excerpt Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-loop-carousel-excerpt' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'meta_typography',
                'selector' => '{{WRAPPER}} .bdea-loop-carousel-meta',
            ]
        );

        $this->add_control(
            'meta_color',
            [
                'label' => 'Meta Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-loop-carousel-meta' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_navigation_section',
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
                    '{{WRAPPER}} .bdea-swiper-button-prev, {{WRAPPER}} .bdea-swiper-button-next' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_bg',
            [
                'label' => 'Arrow Background',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-swiper-button-prev, {{WRAPPER}} .bdea-swiper-button-next' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'dots_color',
            [
                'label' => 'Dots Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-swiper-pagination .swiper-pagination-bullet' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'dots_active_color',
            [
                'label' => 'Active Dot Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-swiper-pagination .swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( ! empty( $settings['exclude_ids'] ) ) {
            $settings['exclude_ids'] = array_map( 'trim', explode( ',', $settings['exclude_ids'] ) );
        }

        $query = new \WP_Query( bdea_widget_query_args( $settings ) );

        if ( ! $query->have_posts() ) {
            echo '<div class="bdea-loop-grid-empty">No posts found.</div>';
            return;
        }

        $template_id = ! empty( $settings['loop_template'] ) ? absint( $settings['loop_template'] ) : 0;

        if ( $template_id && 'publish' === get_post_status( $template_id ) ) {
            $frontend = \Elementor\Plugin::$instance->frontend;

            $space_desktop = ! empty( $settings['space_between']['size'] ) ? (int) $settings['space_between']['size'] : 24;
            $space_tablet  = ! empty( $settings['space_between_tablet']['size'] ) ? (int) $settings['space_between_tablet']['size'] : $space_desktop;
            $space_mobile  = ! empty( $settings['space_between_mobile']['size'] ) ? (int) $settings['space_between_mobile']['size'] : $space_tablet;

            $slides_desktop = ! empty( $settings['slides_per_view'] ) ? (int) $settings['slides_per_view'] : 3;
            $slides_tablet  = ! empty( $settings['slides_per_view_tablet'] ) ? (int) $settings['slides_per_view_tablet'] : 2;
            $slides_mobile  = ! empty( $settings['slides_per_view_mobile'] ) ? (int) $settings['slides_per_view_mobile'] : 1;

            $settings_data = [
                'showArrows' => ( 'yes' === $settings['show_arrows'] ),
                'showDots' => ( 'yes' === $settings['show_dots'] ),
                'autoplay' => ( 'yes' === $settings['autoplay'] ),
                'autoplayDelay' => ! empty( $settings['autoplay_delay'] ) ? (int) $settings['autoplay_delay'] : 3500,
                'pauseOnHover' => ( 'yes' === $settings['pause_on_hover'] ),
                'loop' => ( 'yes' === $settings['loop'] ),
                'speed' => ! empty( $settings['transition_speed'] ) ? (int) $settings['transition_speed'] : 650,
                'equalHeight' => ( 'yes' === $settings['equal_height'] ),
                'slidesDesktop' => $slides_desktop,
                'slidesTablet' => $slides_tablet,
                'slidesMobile' => $slides_mobile,
                'spaceDesktop' => $space_desktop,
                'spaceTablet' => $space_tablet,
                'spaceMobile' => $space_mobile,
            ];
            ?>
            <div class="bdea-carousel-widget bdea-loop-carousel-widget" data-settings='<?php echo esc_attr( wp_json_encode( $settings_data ) ); ?>'>
                <div class="swiper bdea-swiper">
                    <div class="swiper-wrapper">
                        <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                            <div class="swiper-slide">
                                <?php echo $frontend->get_builder_content_for_display( $template_id, false ); ?>
                            </div>
                        <?php endwhile; ?>
                    </div>

                    <?php if ( 'yes' === $settings['show_arrows'] ) : ?>
                        <button type="button" class="bdea-swiper-button-prev" aria-label="Previous Slide">&#10094;</button>
                        <button type="button" class="bdea-swiper-button-next" aria-label="Next Slide">&#10095;</button>
                    <?php endif; ?>
                </div>

                <?php if ( 'yes' === $settings['show_dots'] ) : ?>
                    <div class="swiper-pagination bdea-swiper-pagination"></div>
                <?php endif; ?>
            </div>
            <?php
            wp_reset_postdata();
            return;
        }

        $space_desktop = ! empty( $settings['space_between']['size'] ) ? (int) $settings['space_between']['size'] : 24;
        $space_tablet  = ! empty( $settings['space_between_tablet']['size'] ) ? (int) $settings['space_between_tablet']['size'] : $space_desktop;
        $space_mobile  = ! empty( $settings['space_between_mobile']['size'] ) ? (int) $settings['space_between_mobile']['size'] : $space_tablet;

        $slides_desktop = ! empty( $settings['slides_per_view'] ) ? (int) $settings['slides_per_view'] : 3;
        $slides_tablet  = ! empty( $settings['slides_per_view_tablet'] ) ? (int) $settings['slides_per_view_tablet'] : 2;
        $slides_mobile  = ! empty( $settings['slides_per_view_mobile'] ) ? (int) $settings['slides_per_view_mobile'] : 1;

        $settings_data = [
            'showArrows' => ( 'yes' === $settings['show_arrows'] ),
            'showDots' => ( 'yes' === $settings['show_dots'] ),
            'autoplay' => ( 'yes' === $settings['autoplay'] ),
            'autoplayDelay' => ! empty( $settings['autoplay_delay'] ) ? (int) $settings['autoplay_delay'] : 3500,
            'pauseOnHover' => ( 'yes' === $settings['pause_on_hover'] ),
            'loop' => ( 'yes' === $settings['loop'] ),
            'speed' => ! empty( $settings['transition_speed'] ) ? (int) $settings['transition_speed'] : 650,
            'equalHeight' => ( 'yes' === $settings['equal_height'] ),
            'slidesDesktop' => $slides_desktop,
            'slidesTablet' => $slides_tablet,
            'slidesMobile' => $slides_mobile,
            'spaceDesktop' => $space_desktop,
            'spaceTablet' => $space_tablet,
            'spaceMobile' => $space_mobile,
        ];

        $excerpt_length = ! empty( $settings['excerpt_length'] ) ? absint( $settings['excerpt_length'] ) : 14;
        ?>
        <div class="bdea-carousel-widget bdea-loop-carousel-widget" data-settings='<?php echo esc_attr( wp_json_encode( $settings_data ) ); ?>'>
            <div class="swiper bdea-swiper">
                <div class="swiper-wrapper">
                    <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                        <?php $post_id = get_the_ID(); ?>
                        <div class="swiper-slide">
                            <article class="bdea-loop-carousel-card">
                                <?php if ( 'yes' === $settings['show_thumbnail'] && has_post_thumbnail( $post_id ) ) : ?>
                                    <a class="bdea-loop-carousel-thumb" href="<?php the_permalink(); ?>">
                                        <?php echo get_the_post_thumbnail( $post_id, 'medium_large' ); ?>
                                    </a>
                                <?php endif; ?>

                                <div class="bdea-loop-carousel-body">
                                    <?php if ( 'yes' === $settings['show_meta'] ) : ?>
                                        <div class="bdea-loop-carousel-meta"><?php echo esc_html( get_the_date() ); ?></div>
                                    <?php endif; ?>

                                    <?php if ( 'yes' === $settings['show_title'] ) : ?>
                                        <h3 class="bdea-loop-carousel-title">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </h3>
                                    <?php endif; ?>

                                    <?php if ( 'yes' === $settings['show_excerpt'] ) : ?>
                                        <p class="bdea-loop-carousel-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), $excerpt_length ) ); ?></p>
                                    <?php endif; ?>
                                </div>
                            </article>
                        </div>
                    <?php endwhile; ?>
                </div>

                <?php if ( 'yes' === $settings['show_arrows'] ) : ?>
                    <button type="button" class="bdea-swiper-button-prev" aria-label="Previous Slide">&#10094;</button>
                    <button type="button" class="bdea-swiper-button-next" aria-label="Next Slide">&#10095;</button>
                <?php endif; ?>
            </div>

            <?php if ( 'yes' === $settings['show_dots'] ) : ?>
                <div class="swiper-pagination bdea-swiper-pagination"></div>
            <?php endif; ?>
        </div>
        <?php

        wp_reset_postdata();
    }
}
