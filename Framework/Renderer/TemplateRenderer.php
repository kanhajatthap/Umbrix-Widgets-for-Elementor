<?php
/**
 * ElementStack Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */
namespace BDEA\Framework\Renderer;

use BDEA\Framework\Conditions\ConditionManager;
use BDEA\Framework\Cache\Cache;

defined( 'ABSPATH' ) || exit;

class TemplateRenderer {

    private $condition_manager;
    private $cache;

    private $rendered     = [];
    private $match_cache  = [];

    public function __construct( ConditionManager $condition_manager, Cache $cache ) {
        $this->condition_manager = $condition_manager;
        $this->cache             = $cache;
    }

    public function get_matching_template_id( $type ) {
        if ( isset( $this->match_cache[ $type ] ) ) {
            return $this->match_cache[ $type ];
        }

        $template_ids  = $this->get_templates_by_type( $type );
        $matched_id    = 0;
        $best_priority = PHP_INT_MAX;

        foreach ( $template_ids as $id ) {
            $conditions = get_post_meta( $id, '_bdea_hf_conditions', true );
            $priority   = (int) get_post_meta( $id, '_bdea_hf_priority', true );

            if ( $this->condition_manager->evaluate( $conditions ) ) {
                if ( $priority < $best_priority ) {
                    $matched_id    = $id;
                    $best_priority = $priority;
                }
            }
        }

        $this->match_cache[ $type ] = $matched_id;
        return $matched_id;
    }

    public function render( $post_id ) {
        if ( ! $post_id || in_array( $post_id, $this->rendered, true ) ) {
            return;
        }

        $post = get_post( $post_id );
        if ( ! $post || 'publish' !== $post->post_status ) {
            return;
        }

        $this->rendered[] = $post_id;

        if ( \Elementor\Plugin::$instance->documents->get( $post_id ) ) {
            $content = \Elementor\Plugin::$instance->frontend->get_builder_content( $post_id, true );

            if ( $content ) {
                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Elementor builder content is escaped by Elementor.
                echo $content;
            }
        } else {
            if ( isset( $post->post_content ) ) {
                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped, WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- the_content filter escapes content.
                echo apply_filters( 'the_content', $post->post_content );
            }
        }
    }

    public function has_rendered( $post_id ) {
        return in_array( $post_id, $this->rendered, true );
    }

    public function clear_rendered() {
        $this->rendered = [];
    }

    private function get_templates_by_type( $type ) {
        $cache_key = 'hf_templates_' . $type;
        $templates = $this->cache->get( $cache_key );

        if ( false !== $templates ) {
            return $templates;
        }

        $templates = get_posts(
            [
                'post_type'              => 'bdea_header_footer',
                'post_status'            => 'publish',
                'posts_per_page'         => 50,
                'meta_key'               => '_bdea_hf_template_type', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- Template type lookup is the primary filter.
                'meta_value'             => $type, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value -- Template type lookup is the primary filter.
                'orderby'                => 'menu_order',
                'order'                  => 'ASC',
                'no_found_rows'          => true,
                'fields'                 => 'ids',
                'update_post_term_cache' => false,
                'update_post_meta_cache' => false,
            ]
        );

        $this->cache->set( $cache_key, $templates );
        return $templates;
    }
}
