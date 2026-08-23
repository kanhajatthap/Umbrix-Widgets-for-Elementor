<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */
namespace ElementsKey\Framework\Cache;

defined( 'ABSPATH' ) || exit;

class Cache {

    private $prefix = 'elementskey_';
    private $default_expiry = 43200;

    public function get( $key ) {
        return get_transient( $this->prefix . $key );
    }

    public function set( $key, $data, $expiry = null ) {
        set_transient( $this->prefix . $key, $data, $expiry ?? $this->default_expiry );
    }

    public function delete( $key ) {
        delete_transient( $this->prefix . $key );
    }

    public function flush_all() {
        global $wpdb;
        $wpdb->query( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Intentional bulk transient cleanup; delete operations have no cache layer.
            $wpdb->prepare(
                "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
                '_transient_' . $this->prefix . '%'
            )
        );
    }
}
