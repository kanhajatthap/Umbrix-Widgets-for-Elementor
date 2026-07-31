<?php
/**
 * Plugin Name: ElementStack Elementor Addons
 * Description: Custom widgets and UI modules for Elementor.
 * Version: 1.0.0
 * Author: ElementStack
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Define constants
 */
define( 'BDEA_PATH', plugin_dir_path( __FILE__ ) );
define( 'BDEA_URL', plugin_dir_url( __FILE__ ) );
define( 'BDEA_VERSION', '1.0.0' );

/**
 * PSR-4 autoloader for BDEA classes.
 */
spl_autoload_register( function ( $class ) {
    $prefix = 'BDEA\\';
    $len    = strlen( $prefix );

    if ( strncmp( $prefix, $class, $len ) !== 0 ) {
        return;
    }

    $relative_class = substr( $class, $len );
    $file           = BDEA_PATH . str_replace( '\\', DIRECTORY_SEPARATOR, $relative_class ) . '.php';

    if ( file_exists( $file ) ) {
        require_once $file;
    }
} );

/**
 * Check if Elementor is installed & activated
 */
function bdea_check_elementor_loaded() {
    if ( ! did_action( 'elementor/loaded' ) ) {
        add_action( 'admin_notices', 'bdea_elementor_missing_notice' );
        return false;
    }
    return true;
}

/**
 * Admin Notice if Elementor is missing
 */
function bdea_elementor_missing_notice() {
    ?>
    <div class="notice notice-warning is-dismissible">
        <p><strong>ElementStack Elementor Addons</strong> requires Elementor to be installed and activated.</p>
    </div>
    <?php
}

/**
 * Register custom category
 */
function bdea_register_category( $elements_manager ) {
    $elements_manager->add_category(
        'elementstack-elements',
        [
            'title' => 'ElementStack Elements',
            'icon'  => 'fa fa-plug',
        ]
    );
}
add_action( 'elementor/elements/categories_registered', 'bdea_register_category' );

/**
 * Register Widgets
 */
function bdea_get_default_widget_status() {
    return [
        'progress_bar' => 1,
        'data_table'  => 1,
        'carousel'    => 1,
        'feature_comparison_table' => 1,
        'share_it' => 1,
    ];
}

function bdea_get_widget_status() {
    $status = get_option( 'bdea_widget_status', [] );
    return wp_parse_args( $status, bdea_get_default_widget_status() );
}

function bdea_get_widget_definitions() {
    return [
        'progress_bar' => [
            'label' => 'Progress Bar',
            'class' => 'Custom_Progress_Bar_Widget',
        ],
        'data_table'  => [
            'label' => 'Data Table',
            'class' => 'BDEA_Data_Table_Widget',
        ],
        'carousel'    => [
            'label' => 'Carousel',
            'class' => 'BDEA_Swiper_Carousel_Widget',
        ],
        'feature_comparison_table' => [
            'label' => 'Feature Comparison Table',
            'class' => 'BDEA_Feature_Comparison_Table_Widget',
        ],
        'share_it' => [
            'label' => 'Share It',
            'class' => 'BDEA_Share_It_Widget',
        ],
    ];
}

function bdea_register_widgets( $widgets_manager ) {

    if ( ! bdea_check_elementor_loaded() ) {
        return;
    }

    $widget_definitions = bdea_get_widget_definitions();
    $widget_status      = bdea_get_widget_status();

    // Include widget files.
    require_once( BDEA_PATH . 'widgets/progress-bar.php' );
    require_once( BDEA_PATH . 'widgets/data-table.php' );
    require_once( BDEA_PATH . 'widgets/carousel.php' );
    require_once( BDEA_PATH . 'widgets/feature-comparison-table.php' );
    require_once( BDEA_PATH . 'widgets/share-it.php' );

    foreach ( $widget_definitions as $slug => $widget ) {
        if ( empty( $widget_status[ $slug ] ) ) {
            continue;
        }

        $widget_class = '\\' . $widget['class'];
        if ( class_exists( $widget_class ) ) {
            $widgets_manager->register( new $widget_class() );
        }
    }
}
add_action( 'elementor/widgets/register', 'bdea_register_widgets' );

function bdea_sanitize_widget_status( $input ) {
    $defaults = bdea_get_default_widget_status();
    $output   = [];
    $input    = is_array( $input ) ? $input : [];

    foreach ( $defaults as $key => $value ) {
        $output[ $key ] = ! empty( $input[ $key ] ) ? 1 : 0;
    }

    return $output;
}

function bdea_get_widget_descriptions() {
    return [
        'progress_bar' => 'Animated, customizable progress bars with label and percentage display.',
        'data_table' => 'Label-value rows with a titled card, alternating backgrounds and custom colors.',
        'feature_comparison_table' => 'Two-column feature vs checkmark comparison table with styled header.',
        'carousel' => 'Swiper-powered responsive carousel with slides, arrows and dots.',
        'share_it' => 'Share buttons for the current page with network toggles and copy-link support.',
    ];
}

/**
 * Auto-load modules.
 */
function bdea_load_modules() {
    if ( ! bdea_check_elementor_loaded() ) {
        return;
    }

    require_once BDEA_PATH . 'modules/header-footer/Module.php';
    \BDEA\Modules\HeaderFooter\Module::instance();
}
add_action( 'init', 'bdea_load_modules', 15 );

function bdea_admin_menu() {
    add_menu_page(
        'ElementStack Settings',
        'ElementStack',
        'manage_options',
        'elementstack-settings',
        'bdea_render_admin_page',
        'dashicons-screenoptions',
        60
    );
}
add_action( 'admin_menu', 'bdea_admin_menu' );

function bdea_admin_init() {
    register_setting( 'bdea_options_group', 'bdea_widget_status', 'bdea_sanitize_widget_status' );
    register_setting( 'bdea_options_group', 'bdea_module_status', [ 'BDEA\Modules\HeaderFooter\Module', 'sanitize_module_status' ] );
}
add_action( 'admin_init', 'bdea_admin_init' );

function bdea_enqueue_admin_assets( $hook ) {
    if ( 'toplevel_page_elementstack-settings' !== $hook ) {
        return;
    }

    wp_enqueue_style(
        'bdea-admin-style',
        BDEA_URL . 'assets/css/admin.css',
        [],
        BDEA_VERSION
    );
}
add_action( 'admin_enqueue_scripts', 'bdea_enqueue_admin_assets' );

function bdea_render_admin_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $features = [
        'Enable or disable each widget from one place.',
        'Modern Elementor-native widgets with full style controls.',
        'Responsive and lightweight - zero bloat.',
        'Works seamlessly with any Elementor theme.',
        'Individual padding, margin, font and color controls per widget.',
    ];

    $tips = [
        'Toggle widgets on or off and click Save Settings.',
        'Then open the Elementor editor and refresh the panel.',
        'Only enabled widgets will appear under ElementStack Elements.',
    ];

    $widgets       = bdea_get_widget_definitions();
    $descriptions  = bdea_get_widget_descriptions();
    $widget_status = bdea_get_widget_status();
    $enabled_count = array_sum( $widget_status );
    $total_widgets = count( $widgets );
    $inactive_count = max( 0, $total_widgets - $enabled_count );

    ?>
    <div class="wrap bdea-admin-wrap">
        <h1 class="bdea-admin-page-heading-catch">ElementStack Settings</h1>
        <div class="bdea-admin-shell">
            <div class="bdea-admin-hero">
                <div class="bdea-admin-hero-left">
                    <div class="bdea-admin-hero-icon dashicons dashicons-screenoptions" aria-hidden="true"></div>
                    <div>
                        <div class="bdea-admin-hero-title">ElementStack Addons</div>
                        <p>Manage and configure your custom Elementor widgets</p>
                    </div>
                </div>
                <span class="bdea-admin-version">v<?php echo esc_html( BDEA_VERSION ); ?></span>
            </div>

            <?php if ( isset( $_GET['settings-updated'] ) && 'true' === $_GET['settings-updated'] ) : ?>
                <div class="notice notice-success is-dismissible bdea-admin-notice">
                    <p>Settings saved successfully.</p>
                </div>
            <?php endif; ?>

            <div class="bdea-admin-stats-grid">
                <div class="bdea-admin-stat-card">
                    <div class="bdea-admin-stat-icon bdea-admin-stat-icon-blue dashicons dashicons-grid-view"></div>
                    <div>
                        <strong><?php echo esc_html( $total_widgets ); ?></strong>
                        <span>Total Widgets</span>
                    </div>
                </div>

                <div class="bdea-admin-stat-card">
                    <div class="bdea-admin-stat-icon bdea-admin-stat-icon-green dashicons dashicons-yes-alt"></div>
                    <div>
                        <strong><?php echo esc_html( $enabled_count ); ?></strong>
                        <span>Active</span>
                    </div>
                </div>

                <div class="bdea-admin-stat-card">
                    <div class="bdea-admin-stat-icon bdea-admin-stat-icon-orange dashicons dashicons-dismiss"></div>
                    <div>
                        <strong><?php echo esc_html( $inactive_count ); ?></strong>
                        <span>Inactive</span>
                    </div>
                </div>
            </div>

            <div class="bdea-admin-content-grid">
                <div class="bdea-admin-panel bdea-admin-panel-widgets">
                    <h2 class="bdea-admin-panel-title">Widget Manager</h2>

                    <form method="post" action="options.php">
                        <?php settings_fields( 'bdea_options_group' ); ?>

                        <div class="bdea-admin-widget-list">
                            <?php foreach ( $widgets as $slug => $widget ) : ?>
                                <?php
                                $is_active = ! empty( $widget_status[ $slug ] );
                                $description = isset( $descriptions[ $slug ] ) ? $descriptions[ $slug ] : '';
                                ?>
                                <div class="bdea-admin-widget-card">
                                    <div class="bdea-admin-widget-icon dashicons dashicons-grid-view" aria-hidden="true"></div>
                                    <div class="bdea-admin-widget-details">
                                        <h3><?php echo esc_html( $widget['label'] ); ?></h3>
                                        <p><?php echo esc_html( $description ); ?></p>
                                    </div>

                                    <div class="bdea-admin-widget-toggle-wrap">
                                        <label class="bdea-switch" for="bdea_widget_status_<?php echo esc_attr( $slug ); ?>">
                                            <input
                                                type="checkbox"
                                                id="bdea_widget_status_<?php echo esc_attr( $slug ); ?>"
                                                name="bdea_widget_status[<?php echo esc_attr( $slug ); ?>]"
                                                value="1"
                                                <?php checked( $is_active ); ?>
                                            />
                                            <span class="bdea-slider"></span>
                                        </label>
                                        <span class="bdea-admin-widget-state"><?php echo $is_active ? 'Active' : 'Inactive'; ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <button type="submit" class="button button-primary bdea-admin-save-btn" style="margin-top: 20px;">Save Settings</button>
                    </form>
                </div>

                <?php
                $module_status = get_option( 'bdea_module_status', [] );
                $hf_enabled    = ! empty( $module_status['header_footer'] );
                ?>
                <div class="bdea-admin-panel" style="margin-top: 24px;">
                    <h2 class="bdea-admin-panel-title">Modules</h2>
                    <form method="post" action="options.php" style="margin:0;">
                        <?php settings_fields( 'bdea_options_group' ); ?>
                        <div class="bdea-admin-widget-list">
                            <div class="bdea-admin-widget-card">
                                <div class="bdea-admin-widget-icon dashicons dashicons-editor-kitchensink" aria-hidden="true"></div>
                                <div class="bdea-admin-widget-details">
                                    <h3>Header & Footer Builder</h3>
                                    <p>Create and manage custom header and footer templates with Elementor.</p>
                                </div>
                                <div class="bdea-admin-widget-toggle-wrap">
                                    <label class="bdea-switch" for="bdea_module_status_header_footer">
                                        <input
                                            type="checkbox"
                                            id="bdea_module_status_header_footer"
                                            name="bdea_module_status[header_footer]"
                                            value="1"
                                            <?php checked( $hf_enabled ); ?>
                                        />
                                        <span class="bdea-slider"></span>
                                    </label>
                                    <span class="bdea-admin-widget-state"><?php echo $hf_enabled ? 'Active' : 'Inactive'; ?></span>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="button button-primary bdea-admin-save-btn" style="margin-top:14px;">Save Settings</button>
                    </form>
                </div>

                <div class="bdea-admin-sidebar">
                    <div class="bdea-admin-panel">
                        <h2 class="bdea-admin-panel-title">Plugin Features</h2>
                        <ul class="bdea-admin-list">
                            <?php foreach ( $features as $feature ) : ?>
                                <li><?php echo esc_html( $feature ); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <div class="bdea-admin-panel bdea-admin-panel-tips">
                        <h2 class="bdea-admin-panel-title">Quick Tips</h2>
                        <ul class="bdea-admin-list">
                            <?php foreach ( $tips as $tip ) : ?>
                                <li><?php echo esc_html( $tip ); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Register frontend assets so Elementor editor and frontend can use the same handles.
 */
function bdea_register_assets() {
    wp_register_style(
        'swiper',
        'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
        [],
        '11.1.4'
    );

    wp_register_style(
        'bdea-style',
        BDEA_URL . 'assets/css/style.css',
        [],
        '1.0.0'
    );

    wp_register_style(
        'bdea-share-it-style',
        BDEA_URL . 'assets/css/share-it.css',
        [],
        '1.0.0'
    );

    wp_register_script(
        'swiper',
        'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
        [],
        '11.1.4',
        true
    );

    wp_register_script(
        'bdea-script',
        BDEA_URL . 'assets/js/script.js',
        [ 'jquery' ],
        '1.0.0',
        true
    );

    wp_register_script(
        'bdea-carousel-script',
        BDEA_URL . 'assets/js/carousel.js',
        [ 'swiper' ],
        '1.0.0',
        true
    );

    wp_register_script(
        'bdea-share-it-script',
        BDEA_URL . 'assets/js/share-it.js',
        [],
        '1.0.0',
        true
    );
}
add_action( 'init', 'bdea_register_assets' );

function bdea_enqueue_editor_assets() {
    wp_enqueue_script(
        'bdea-editor-badge',
        BDEA_URL . 'assets/js/editor.js',
        ['jquery'],
        '1.0.0',
        true
    );

    wp_enqueue_style(
        'bdea-editor-style',
        BDEA_URL . 'assets/css/editor.css',
        [],
        '1.0.0'
    );
}
add_action( 'elementor/editor/after_enqueue_scripts', 'bdea_enqueue_editor_assets' );