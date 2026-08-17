<?php
/**
 * ElementStack Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */
/**
 * ElementStack Addons for Elementor - Uninstall.
 *
 * Removes plugin options when the plugin is deleted from the admin.
 *
 * @package ElementStack_Elementor_Addons
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

delete_option( 'bdea_widget_status' );
delete_option( 'bdea_module_status' );
