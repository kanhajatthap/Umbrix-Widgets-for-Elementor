<?php
namespace BDEA\Framework\Conditions;

defined( 'ABSPATH' ) || exit;

class ConditionManager {

    private $conditions = [];

    public function __construct() {
        $this->register_defaults();
    }

    private function register_defaults() {
        $defaults = [
            'entire_site' => [
                'label' => __( 'Entire Website', 'bdea' ),
                'group' => __( 'General', 'bdea' ),
            ],
            'front_page'  => [
                'label' => __( 'Front Page', 'bdea' ),
                'group' => __( 'General', 'bdea' ),
            ],
            'home_page'   => [
                'label' => __( 'Home / Blog Page', 'bdea' ),
                'group' => __( 'General', 'bdea' ),
            ],
            'custom_url'  => [
                'label' => __( 'Custom URL', 'bdea' ),
                'group' => __( 'General', 'bdea' ),
            ],
            'singular'    => [
                'label' => __( 'All Singular', 'bdea' ),
                'group' => __( 'Singular', 'bdea' ),
            ],
            'singular:post_type:post' => [
                'label' => __( 'All Posts', 'bdea' ),
                'group' => __( 'Singular', 'bdea' ),
            ],
            'singular:post_type:page' => [
                'label' => __( 'All Pages', 'bdea' ),
                'group' => __( 'Singular', 'bdea' ),
            ],
            'archive'     => [
                'label' => __( 'All Archives', 'bdea' ),
                'group' => __( 'Archives', 'bdea' ),
            ],
            'search'      => [
                'label' => __( 'Search Results', 'bdea' ),
                'group' => __( 'Archives', 'bdea' ),
            ],
            '404'         => [
                'label' => __( '404 Page', 'bdea' ),
                'group' => __( 'General', 'bdea' ),
            ],
        ];

        foreach ( $defaults as $id => $args ) {
            $this->register( $id, $args['label'], $args['group'] );
        }

        $post_types = get_post_types( [ 'public' => true ], 'objects' );

        foreach ( $post_types as $pt ) {
            if ( in_array( $pt->name, [ 'bdea_header_footer', 'elementor_library', 'attachment' ], true ) ) {
                continue;
            }

            $this->register(
                'singular:post_type:' . $pt->name,
                sprintf( __( 'Singular: %s', 'bdea' ), $pt->label ),
                __( 'Singular', 'bdea' )
            );
        }

        $this->register_user_role_conditions();
        $this->register_language_conditions();

        if ( class_exists( 'WooCommerce' ) ) {
            $this->register_woocommerce_conditions();
        }
    }

    private function register_language_conditions() {
        $has_wpml    = function_exists( 'wpml_current_language' ) || has_filter( 'wpml_active_languages' );
        $has_polylang = function_exists( 'pll_languages_list' );

        if ( ! $has_wpml && ! $has_polylang ) {
            return;
        }

        $languages = [];

        if ( $has_polylang && function_exists( 'pll_languages_list' ) ) {
            foreach ( pll_languages_list( [ 'fields' => 'slug' ] ) as $slug ) {
                $languages[ $slug ] = $slug;
            }
        }

        if ( $has_wpml ) {
            $active = apply_filters( 'wpml_active_languages', [] );

            foreach ( (array) $active as $lang ) {
                if ( isset( $lang['code'] ) ) {
                    $languages[ $lang['code'] ] = $lang['code'];
                }
            }
        }

        foreach ( $languages as $code ) {
            $this->register(
                'language:' . $code,
                sprintf( __( 'Language: %s', 'bdea' ), strtoupper( $code ) ),
                __( 'Language', 'bdea' )
            );
        }
    }

    private function register_user_role_conditions() {
        $this->register( 'user_role:logged_in', __( 'Logged In', 'bdea' ), __( 'User Role', 'bdea' ) );
        $this->register( 'user_role:logged_out', __( 'Logged Out', 'bdea' ), __( 'User Role', 'bdea' ) );

        $roles = wp_roles()->get_names();

        foreach ( $roles as $role => $label ) {
            $this->register(
                'user_role:' . $role,
                sprintf( __( 'Role: %s', 'bdea' ), $label ),
                __( 'User Role', 'bdea' )
            );
        }
    }

    private function register_woocommerce_conditions() {
        $woo = [
            'woocommerce:shop'            => __( 'Shop Page', 'bdea' ),
            'woocommerce:product'         => __( 'Product Page', 'bdea' ),
            'woocommerce:cart'            => __( 'Cart Page', 'bdea' ),
            'woocommerce:checkout'        => __( 'Checkout Page', 'bdea' ),
            'woocommerce:account'         => __( 'My Account Page', 'bdea' ),
            'woocommerce:product_archive' => __( 'Product Archive (Category/Tag)', 'bdea' ),
        ];

        foreach ( $woo as $id => $label ) {
            $this->register( $id, $label, __( 'WooCommerce', 'bdea' ) );
        }
    }

    public function register( $id, $label, $group ) {
        $this->conditions[ $id ] = [
            'id'    => $id,
            'label' => $label,
            'group' => $group,
        ];
    }

    public function get_conditions() {
        return $this->conditions;
    }

    public function get_conditions_grouped() {
        $grouped = [];

        foreach ( $this->conditions as $id => $data ) {
            $grouped[ $data['group'] ][ $id ] = $data['label'];
        }

        return $grouped;
    }

    public function evaluate( $conditions ) {
        if ( empty( $conditions ) || ! is_array( $conditions ) ) {
            return true;
        }

        $includes = [];
        $excludes = [];

        foreach ( $conditions as $cond ) {
            $condition_id = isset( $cond['condition'] ) ? $cond['condition'] : '';
            $type         = isset( $cond['type'] ) ? $cond['type'] : 'include';

            if ( 'exclude' === $type ) {
                $excludes[] = $condition_id;
            } else {
                $includes[] = $condition_id;
            }
        }

        if ( empty( $includes ) ) {
            foreach ( $excludes as $condition_id ) {
                if ( $this->check( $condition_id ) ) {
                    return false;
                }
            }

            return true;
        }

        $matched = false;

        foreach ( $includes as $condition_id ) {
            if ( $this->check( $condition_id ) ) {
                $matched = true;
                break;
            }
        }

        if ( ! $matched ) {
            return false;
        }

        foreach ( $excludes as $condition_id ) {
            if ( $this->check( $condition_id ) ) {
                return false;
            }
        }

        return true;
    }

    public function check( $condition_id ) {
        if ( empty( $condition_id ) ) {
            return false;
        }

        $parts = explode( ':', $condition_id );
        $type  = $parts[0];

        switch ( $type ) {
            case 'entire_site':
                return true;

            case 'front_page':
                return is_front_page();

            case 'home_page':
                return is_home();

            case 'custom_url':
                return $this->check_custom_url( $parts );

            case 'singular':
                return $this->check_singular( $parts );

            case 'archive':
                return $this->check_archive( $parts );

            case 'search':
                return is_search();

            case '404':
                return is_404();

            case 'user_role':
                return $this->check_user_role( $parts );

            case 'language':
                return $this->check_language( $parts );

            case 'woocommerce':
                return $this->check_woocommerce( $parts );
        }

        return false;
    }

    private function check_singular( $parts ) {
        if ( 1 === count( $parts ) ) {
            return is_singular();
        }

        if ( 3 === count( $parts ) && 'post_type' === $parts[1] ) {
            return is_singular( $parts[2] );
        }

        if ( 3 === count( $parts ) && 'post_id' === $parts[1] ) {
            return is_singular() && get_queried_object_id() === (int) $parts[2];
        }

        return false;
    }

    private function check_archive( $parts ) {
        if ( 1 === count( $parts ) ) {
            return is_archive();
        }

        if ( 3 === count( $parts ) && 'post_type' === $parts[1] ) {
            return is_post_type_archive( $parts[2] );
        }

        if ( 5 === count( $parts ) && 'taxonomy' === $parts[1] ) {
            return is_tax( $parts[2], $parts[4] );
        }

        return false;
    }

    private function check_custom_url( $parts ) {
        if ( 1 === count( $parts ) ) {
            $current_path = wp_parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );
            $home_path    = wp_parse_url( home_url( '/' ), PHP_URL_PATH );
            $relative     = $home_path ? substr( $current_path, strlen( $home_path ) ) : ltrim( $current_path, '/' );

            return ! empty( $relative );
        }

        $pattern = isset( $parts[1] ) ? $parts[1] : '';
        if ( empty( $pattern ) ) {
            return false;
        }

        $current_path = wp_parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );
        $home_path    = wp_parse_url( home_url( '/' ), PHP_URL_PATH );
        $relative     = $home_path ? substr( $current_path, strlen( $home_path ) ) : ltrim( $current_path, '/' );

        if ( strpos( $pattern, '*' ) !== false ) {
            $regex = '/^' . str_replace( '\\*', '.*', preg_quote( $pattern, '/' ) ) . '$/i';
            return (bool) preg_match( $regex, $relative );
        }

        return $relative === $pattern;
    }

    private function check_user_role( $parts ) {
        $sub = $parts[1] ?? '';

        if ( 'logged_in' === $sub ) {
            return is_user_logged_in();
        }

        if ( 'logged_out' === $sub ) {
            return ! is_user_logged_in();
        }

        if ( $sub && is_user_logged_in() ) {
            $user = wp_get_current_user();
            return in_array( $sub, (array) $user->roles, true );
        }

        return false;
    }

    private function check_language( $parts ) {
        $code = $parts[1] ?? '';

        if ( empty( $code ) ) {
            return false;
        }

        $current = '';

        if ( has_filter( 'wpml_current_language' ) ) {
            $current = apply_filters( 'wpml_current_language', $current );
        } elseif ( function_exists( 'pll_current_language' ) ) {
            $current = pll_current_language();
        }

        return $current === $code;
    }

    private function check_woocommerce( $parts ) {
        if ( ! function_exists( 'is_shop' ) ) {
            return false;
        }

        if ( 1 === count( $parts ) ) {
            return is_shop() || is_product() || is_cart() || is_checkout() || is_account_page();
        }

        $sub = $parts[1] ?? '';

        switch ( $sub ) {
            case 'shop':
                return is_shop();
            case 'product':
                return is_product();
            case 'cart':
                return is_cart();
            case 'checkout':
                return is_checkout();
            case 'account':
                return is_account_page();
            case 'product_archive':
                return is_product_category() || is_product_tag();
        }

        return false;
    }
}
