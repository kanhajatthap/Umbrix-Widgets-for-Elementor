<?php
/**
 * Shared helpers for ElementStack content widgets (Loop Grid, Loop Carousel, Posts, Portfolio).
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Build WP_Query args from widget settings.
 *
 * @param array $settings Widget settings.
 * @return array Query args.
 */
function bdea_widget_query_args( $settings ) {
    $args = [
        'post_type'           => ! empty( $settings['post_type'] ) ? $settings['post_type'] : 'post',
        'post_status'         => 'publish',
        'posts_per_page'      => ! empty( $settings['posts_per_page'] ) ? absint( $settings['posts_per_page'] ) : 6,
        'orderby'             => ! empty( $settings['orderby'] ) ? $settings['orderby'] : 'date',
        'order'               => ! empty( $settings['order'] ) ? $settings['order'] : 'DESC',
        'ignore_sticky_posts' => true,
        'no_found_rows'       => ( empty( $settings['pagination_type'] ) || 'none' === $settings['pagination_type'] )
            && ( empty( $settings['enable_pagination'] ) || 'yes' !== $settings['enable_pagination'] ),
    ];

    if ( ! empty( $settings['exclude_ids'] ) ) {
        $args['post__not_in'] = array_map( 'intval', (array) $settings['exclude_ids'] );
    }

    if ( ! empty( $settings['offset'] ) ) {
        $args['offset'] = absint( $settings['offset'] );
    }

    $tax_query = [];

    if ( ! empty( $settings['include_cats'] ) && is_array( $settings['include_cats'] ) ) {
        $cats = array_filter( array_map( 'intval', $settings['include_cats'] ) );
        if ( $cats ) {
            $tax_query[] = [
                'taxonomy' => 'category',
                'field'    => 'term_id',
                'terms'    => $cats,
            ];
        }
    }

    if ( ! empty( $settings['include_tags'] ) && is_array( $settings['include_tags'] ) ) {
        $tags = array_filter( array_map( 'intval', $settings['include_tags'] ) );
        if ( $tags ) {
            $tax_query[] = [
                'taxonomy' => 'post_tag',
                'field'    => 'term_id',
                'terms'    => $tags,
            ];
        }
    }

    if ( ! empty( $settings['exclude_cats'] ) && is_array( $settings['exclude_cats'] ) ) {
        $excats = array_filter( array_map( 'intval', $settings['exclude_cats'] ) );
        if ( $excats ) {
            $tax_query[] = [
                'taxonomy' => 'category',
                'field'    => 'term_id',
                'terms'    => $excats,
                'operator' => 'NOT IN',
            ];
        }
    }

    if ( count( $tax_query ) > 1 ) {
        $tax_query['relation'] = 'AND';
    }

    if ( $tax_query ) {
        $args['tax_query'] = $tax_query;
    }

    return $args;
}

/**
 * Get a list of public post types (excluding attachments, theme builder types).
 *
 * @return array slug => label
 */
function bdea_widget_post_types() {
    $types = get_post_types( [ 'public' => true ], 'objects' );
    $out   = [];

    foreach ( $types as $type ) {
        if ( in_array( $type->name, [ 'attachment', 'bdea_header_footer', 'elementor_library' ], true ) ) {
            continue;
        }
        $out[ $type->name ] = $type->labels->singular_name;
    }

    return $out;
}

/**
 * Get a list of terms for a taxonomy (for selects).
 *
 * @param string $taxonomy Taxonomy name.
 * @return array term_id => name
 */
function bdea_widget_terms_list( $taxonomy ) {
    $terms = get_terms( [
        'taxonomy'   => $taxonomy,
        'hide_empty' => false,
        'number'     => 200,
    ] );

    $out = [];

    if ( is_wp_error( $terms ) ) {
        return $out;
    }

    foreach ( $terms as $term ) {
        $out[ $term->term_id ] = $term->name;
    }

    return $out;
}

/**
 * Get loop templates for the Loop Grid / Loop Carousel dropdown.
 *
 * Like Elementor Pro, only "loop" type templates are listed — headers,
 * footers, pages, popups etc. never appear here. A loop template is
 * reusable across every loop widget (grid, carousel).
 *
 * Queries elementor_library posts with _elementor_template_type = loop-item|loop
 *
 * @return array template_id => title
 */
function bdea_loop_template_options() {
    $templates = [];

    // Loop templates use elementor_library post type (like Elementor Pro)
    $elementor_items = get_posts( [
        'post_type'      => 'elementor_library',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'title',
        'order'          => 'ASC',
        'meta_query'     => [
            [
                'key'     => '_elementor_template_type',
                'value'   => [ 'loop-item', 'loop' ],
                'compare' => 'IN',
            ],
        ],
    ] );

    foreach ( $elementor_items as $item ) {
        $templates[ $item->ID ] = $item->post_title;
    }

    asort( $templates );

    return $templates;
}

/**
 * Get a list of public taxonomies (for portfolio filter selects).
 *
 * @return array slug => label
 */
function bdea_widget_taxonomies() {
    $taxonomies = get_taxonomies( [ 'public' => true ], 'objects' );
    $out        = [];

    foreach ( $taxonomies as $taxonomy ) {
        $out[ $taxonomy->name ] = $taxonomy->labels->singular_name;
    }

    return $out;
}

/**
 * Render loop items (loop template or built-in card) for Loop Grid / Loop Carousel.
 *
 * Shared between the widget render() and the bdea_loop_load AJAX handler so both
 * produce identical markup.
 *
 * @param \WP_Query $query    The post query to loop over.
 * @param array     $settings Widget settings.
 */
function bdea_render_loop_items( $query, $settings ) {
    $template_id = ! empty( $settings['loop_template'] ) ? absint( $settings['loop_template'] ) : 0;
    $frontend    = \Elementor\Plugin::$instance->frontend ?? null;

    if (
        $template_id && 'publish' === get_post_status( $template_id )
        && $frontend && method_exists( $frontend, 'get_builder_content_for_display' )
    ) {
        while ( $query->have_posts() ) : $query->the_post();
            echo $frontend->get_builder_content_for_display( $template_id, false ); // phpcs:ignore WordPress.Security.EscapeOutput
        endwhile;

        wp_reset_postdata();
        return;
    }

    $excerpt_length = ! empty( $settings['excerpt_length'] ) ? absint( $settings['excerpt_length'] ) : 18;

    while ( $query->have_posts() ) : $query->the_post();
        $post_id = get_the_ID();
        ?>
        <article class="bdea-loop-card">
            <?php if ( ! empty( $settings['show_thumbnail'] ) && 'yes' === $settings['show_thumbnail'] && has_post_thumbnail( $post_id ) ) : ?>
                <a class="bdea-loop-thumb" href="<?php the_permalink(); ?>">
                    <?php echo get_the_post_thumbnail( $post_id, 'medium_large' ); ?>
                </a>
            <?php endif; ?>

            <div class="bdea-loop-body">
                <?php if ( ! empty( $settings['show_meta'] ) && 'yes' === $settings['show_meta'] ) : ?>
                    <div class="bdea-loop-meta">
                        <span class="bdea-loop-meta-date"><?php echo esc_html( get_the_date() ); ?></span>
                        <?php $cats = get_the_category(); ?>
                        <?php if ( $cats ) : ?>
                            <span class="bdea-loop-meta-sep">&middot;</span>
                            <span class="bdea-loop-meta-cat"><?php echo esc_html( $cats[0]->name ); ?></span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if ( ! empty( $settings['show_title'] ) && 'yes' === $settings['show_title'] ) : ?>
                    <?php $title_tag = in_array( $settings['title_tag'], [ 'h2', 'h3', 'h4', 'h5', 'div' ], true ) ? $settings['title_tag'] : 'h3'; ?>
                    <<?php echo esc_attr( $title_tag ); ?> class="bdea-loop-title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </<?php echo esc_attr( $title_tag ); ?>>
                <?php endif; ?>

                <?php if ( ! empty( $settings['show_excerpt'] ) && 'yes' === $settings['show_excerpt'] ) : ?>
                    <p class="bdea-loop-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), $excerpt_length ) ); ?></p>
                <?php endif; ?>

                <?php if ( ! empty( $settings['show_read_more'] ) && 'yes' === $settings['show_read_more'] ) : ?>
                    <a class="bdea-loop-more" href="<?php the_permalink(); ?>">
                        <?php echo esc_html( ! empty( $settings['read_more_text'] ) ? $settings['read_more_text'] : 'Read More' ); ?>
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                <?php endif; ?>
            </div>
        </article>
        <?php
    endwhile;

    wp_reset_postdata();
}

/**
 * Print the Facebook SDK once per request, even when multiple FB widgets are on the same page.
 *
 * @param string $version SDK version to load.
 */
function bdea_maybe_print_fb_sdk( $version = 'v18.0' ) {
    static $scheduled = false;

    if ( $scheduled ) {
        return;
    }

    $scheduled = true;

    add_action( 'wp_footer', function () use ( $version ) {
        ?>
        <div id="fb-root"></div>
        <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&amp;version=<?php echo esc_attr( $version ); ?>"></script>
        <?php
    }, 99 );
}