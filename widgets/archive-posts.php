<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Archive_Posts_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_archive_posts';
    }

    public function get_title() {
        return 'Archive Posts';
    }

    public function get_icon() {
        return 'eicon-archive-posts';
    }

    public function get_categories() {
        return [ 'elementstack-elements' ];
    }

    public function get_style_depends() {
        return [ 'bdea-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'bdea_archive_query_section',
            [
                'label' => 'Query',
            ]
        );

        $post_types = [ '' => 'Auto (Archive / Main Query)' ] + bdea_widget_post_types();

        $this->add_control(
            'post_type',
            [
                'label' => 'Post Type',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => $post_types,
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label' => 'Posts Per Page',
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 9,
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
                    'comment_count' => 'Comment Count',
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
            'bdea_archive_layout_section',
            [
                'label' => 'Layout',
            ]
        );

        $this->add_control(
            'columns',
            [
                'label' => 'Columns',
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 2,
                'min' => 1,
                'max' => 4,
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
            'thumbnail_size',
            [
                'label' => 'Thumbnail Size',
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'thumbnail' => 'Thumbnail',
                    'medium' => 'Medium',
                    'large' => 'Large',
                    'medium_large' => 'Medium Large',
                    'full' => 'Full',
                ],
                'default' => 'medium_large',
                'condition' => [ 'show_thumbnail' => 'yes' ],
            ]
        );

        $this->add_control(
            'show_date',
            [
                'label' => 'Show Date',
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
                'default' => 18,
                'min' => 3,
                'max' => 120,
                'condition' => [ 'show_excerpt' => 'yes' ],
            ]
        );

        $this->add_control(
            'enable_pagination',
            [
                'label' => 'Enable Pagination',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'no',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_archive_style_section',
            [
                'label' => 'Cards',
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
                    '{{WRAPPER}} .bdea-archive-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_radius',
            [
                'label' => 'Border Radius',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
                'default' => [ 'size' => 8, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-archive-card' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .bdea-archive-title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => 'Title Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .bdea-archive-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'excerpt_typography',
                'selector' => '{{WRAPPER}} .bdea-archive-excerpt',
            ]
        );

        $this->add_control(
            'excerpt_color',
            [
                'label' => 'Excerpt Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4b5563',
                'selectors' => [
                    '{{WRAPPER}} .bdea-archive-excerpt' => 'color: {{VALUE}};',
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
            $query = $wp_query;
        } else {
            $query = new \WP_Query( bdea_widget_query_args( $settings ) );
        }

        if ( ! $query->have_posts() ) {
            echo '<div class="bdea-loop-grid-empty">No posts found.</div>';
            wp_reset_postdata();
            return;
        }

        $columns = ! empty( $settings['columns'] ) ? (int) $settings['columns'] : 2;
        $columns = max( 1, min( 4, $columns ) );

        $excerpt_length = ! empty( $settings['excerpt_length'] ) ? absint( $settings['excerpt_length'] ) : 18;
        $thumbnail_size = ! empty( $settings['thumbnail_size'] ) ? $settings['thumbnail_size'] : 'medium_large';
        ?>
        <div class="bdea-archive-posts">
            <div class="bdea-archive-grid" style="--bdea-cols: <?php echo esc_attr( $columns ); ?>;">
                <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                    <?php $post_id = get_the_ID(); ?>
                    <article class="bdea-archive-card">
                        <?php if ( 'yes' === $settings['show_thumbnail'] && has_post_thumbnail( $post_id ) ) : ?>
                            <a class="bdea-archive-thumb" href="<?php the_permalink(); ?>">
                                <?php echo get_the_post_thumbnail( $post_id, $thumbnail_size ); ?>
                            </a>
                        <?php endif; ?>

                        <div class="bdea-archive-body">
                            <h3 class="bdea-archive-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>

                            <?php if ( 'yes' === $settings['show_date'] ) : ?>
                                <div class="bdea-archive-meta"><?php echo esc_html( get_the_date() ); ?></div>
                            <?php endif; ?>

                            <?php if ( 'yes' === $settings['show_excerpt'] ) : ?>
                                <p class="bdea-archive-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), $excerpt_length ) ); ?></p>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <?php if ( 'yes' === $settings['enable_pagination'] ) : ?>
                <div class="bdea-archive-pagination">
                    <?php
                    if ( $is_main ) {
                        the_posts_pagination();
                    } elseif ( $query->max_num_pages > 1 ) {
                        $big   = 999999999;
                        $paged = max( 1, get_query_var( 'paged' ) );
                        echo paginate_links( [
                            'base'    => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                            'format'  => '?paged=%#%',
                            'current' => $paged,
                            'total'   => $query->max_num_pages,
                        ] );
                    }
                    ?>
                </div>
            <?php endif; ?>
        </div>
        <?php

        if ( ! $is_main ) {
            wp_reset_postdata();
        }
    }
}