<?php
if ( ! defined( 'ABSPATH' ) ) {
    // WP CLI eval-file loads WordPress, so ABSPATH should already be defined when this runs.
}

do_action( 'admin_menu' );

echo 'submenu_exists=' . ( isset( $GLOBALS['submenu']['elementskey-settings'] ) ? 'yes' : 'no' ) . PHP_EOL;
if ( isset( $GLOBALS['submenu']['elementskey-settings'] ) ) {
    foreach ( $GLOBALS['submenu']['elementskey-settings'] as $item ) {
        echo 'item=' . ( isset( $item[2] ) ? $item[2] : 'none' ) . PHP_EOL;
    }
}
