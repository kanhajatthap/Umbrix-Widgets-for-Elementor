<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class ELEMENTSKEY_Archive_Posts_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'elementskey_archive_posts';
    }

    public function get_title() {
        return 'Archive Posts';
    }

    public function get_icon() {
        return 'eicon-archive-posts';
    }

    public function get_categories() {
        return [ 'elementskey-elements' ];
    }

    public function get_style_depends() {
        return [ 'elementskey-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'elementskey_archive_query_section',
            [
                'label' => __( 'Query', 'elementskey' ),
            ]
        );

        $post_types = [ '' => __( 'Auto (Archive / Main Query)', 'elementskey' ) ] + elementskey_widget_post_types();

        $this->add_control(
            'post_type',
            [
                'label' => __( 'Post Type', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => $post_types,
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label' => __( 'Posts Per Page', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 9,
                'min' => 1,
                'max' => 100,
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label' => __( 'Order By', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'date',
                'options' => [
                    'date' => __( 'Date', 'elementskey' ),
                    'modified' => __( 'Modified Date', 'elementskey' ),
                    'title' => __( 'Title', 'elementskey' ),
                    'menu_order' => __( 'Menu Order', 'elementskey' ),
                    'rand' => __( 'Random', 'elementskey' ),
                    'comment_count' => __( 'Comment Count', 'elementskey' ),
                ],
            ]
        );

        $this->add_control(
            'order',
            [
                'label' => __( 'Order', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'DESC',
                'options' => [
                    'ASC' => __( 'Ascending', 'elementskey' ),
                    'DESC' => __( 'Descending', 'elementskey' ),
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_archive_layout_section',
            [
                'label' => __( 'Layout', 'elementskey' ),
            ]
        );

        $this->add_control(
            'columns',
            [
                'label' => __( 'Columns', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 2,
                'min' => 1,
                'max' => 4,
            ]
        );

        $this->add_control(
            'show_thumbnail',
            [
                'label' => __( 'Show Thumbnail', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'thumbnail_size',
            [
                'label' => __( 'Thumbnail Size', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'thumbnail' => __( 'Thumbnail', 'elementskey' ),
                    'medium' => __( 'Medium', 'elementskey' ),
                    'large' => __( 'Large', 'elementskey' ),
                    'medium_large' => __( 'Medium Large', 'elementskey' ),
                    'full' => __( 'Full', 'elementskey' ),
                ],
                'default' => 'medium_large',
                'condition' => [ 'show_thumbnail' => 'yes' ],
            ]
        );

        $this->add_control(
            'show_date',
            [
                'label' => __( 'Show Date', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_excerpt',
            [
                'label' => __( 'Show Excerpt', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'excerpt_length',
            [
                'label' => __( 'Excerpt Length (words)', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 18,
                'min' => 3,
                'max' => 120,
                'condition' => [ 'show_excerpt' => 'yes' ],
            ]
        );

        $this->add_control(
            'enable_pagination',
            [
                'label' => __( 'Enable Pagination', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'no',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_archive_style_section',
            [
                'label' => __( 'Cards', 'elementskey' ),
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
                    '{{WRAPPER}} .elementskey-archive-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_radius',
            [
                'label' => __( 'Border Radius', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
                'default' => [ 'size' => 8, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-archive-card' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .elementskey-archive-title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Title Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-archive-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'excerpt_typography',
                'selector' => '{{WRAPPER}} .elementskey-archive-excerpt',
            ]
        );

        $this->add_control(
            'excerpt_color',
            [
                'label' => __( 'Excerpt Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4b5563',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-archive-excerpt' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_archive_pagination_style_section',
            [
                'label' => __( 'Pagination', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'pagination_bg',
            [
                'label' => __( 'Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#f3f4f6',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-archive-pagination .page-numbers' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'pagination_color',
            [
                'label' => __( 'Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-archive-pagination .page-numbers' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'pagination_active_bg',
            [
                'label' => __( 'Active Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-archive-pagination .page-numbers.current' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'pagination_active_color',
            [
                'label' => __( 'Active Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-archive-pagination .page-numbers.current' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $post_type = ! empty( $settings['post_type'] ) ? $settings['post_type'] : '';

        $is_main = empty( $post_type );

        if ( $is_main ) {
            global $wp_query;

            $query_args       = $wp_query->query_vars;
            $query_args['paged'] = max( 1, get_query_var( 'paged' ) );
            $query            = new \WP_Query( $query_args );
        } else {
            $query = new \WP_Query( elementskey_widget_query_args( $settings ) );
        }

        if ( ! $query->have_posts() ) {
            echo '<div class="elementskey-loop-grid-empty">No posts found.</div>';
            return;
        }

        $columns = ! empty( $settings['columns'] ) ? (int) $settings['columns'] : 2;
        $columns = max( 1, min( 4, $columns ) );

        $excerpt_length = ! empty( $settings['excerpt_length'] ) ? absint( $settings['excerpt_length'] ) : 18;
        $thumbnail_size = ! empty( $settings['thumbnail_size'] ) ? $settings['thumbnail_size'] : 'medium_large';
        ?>
        <div class="elementskey-archive-posts">
            <div class="elementskey-archive-grid" style="--elementskey-cols: <?php echo esc_attr( $columns ); ?>;">
                <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                    <?php $post_id = get_the_ID(); ?>
                    <article class="elementskey-archive-card">
                        <?php if ( 'yes' === $settings['show_thumbnail'] && has_post_thumbnail( $post_id ) ) : ?>
                            <a class="elementskey-archive-thumb" href="<?php the_permalink(); ?>">
                                <?php echo get_the_post_thumbnail( $post_id, $thumbnail_size ); ?>
                            </a>
                        <?php endif; ?>

                        <div class="elementskey-archive-body">
                            <h3 class="elementskey-archive-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>

                            <?php if ( 'yes' === $settings['show_date'] ) : ?>
                                <div class="elementskey-archive-meta"><?php echo esc_html( get_the_date() ); ?></div>
                            <?php endif; ?>

                            <?php if ( 'yes' === $settings['show_excerpt'] ) : ?>
                                <p class="elementskey-archive-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), $excerpt_length ) ); ?></p>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <?php if ( 'yes' === $settings['enable_pagination'] ) : ?>
                <div class="elementskey-archive-pagination">
                    <?php
                    if ( $query->max_num_pages > 1 ) {
                        $big   = 999999999;
                        $paged = max( 1, get_query_var( 'paged' ) );
                        echo wp_kses_post( paginate_links( [
                            'base'    => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                            'format'  => '?paged=%#%',
                            'current' => $paged,
                            'total'   => $query->max_num_pages,
                        ] ) );
                    }
                    ?>
                </div>
            <?php endif; ?>
        </div>
        <?php

        wp_reset_postdata();
    }
}