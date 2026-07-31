<?php
namespace BDEA\Modules\HeaderFooter;

use BDEA\Framework\Cache\Cache;
use BDEA\Framework\Conditions\ConditionManager;

defined( 'ABSPATH' ) || exit;

class Admin {

    private $cache;
    private $condition_manager;

    public function __construct( Cache $cache, ConditionManager $condition_manager ) {
        $this->cache             = $cache;
        $this->condition_manager = $condition_manager;

        add_action( 'admin_menu', [ $this, 'register_admin_menu' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_assets' ] );
        add_action( 'admin_head', [ $this, 'hide_notices' ] );
        add_action( 'wp_ajax_bdea_hf_create_template', [ $this, 'ajax_create_template' ] );
        add_action( 'wp_ajax_bdea_hf_update_conditions', [ $this, 'ajax_update_conditions' ] );
        add_action( 'wp_ajax_bdea_hf_get_template_conditions', [ $this, 'ajax_get_template_conditions' ] );
        add_action( 'wp_ajax_bdea_hf_duplicate_template', [ $this, 'ajax_duplicate_template' ] );
        add_action( 'wp_ajax_bdea_hf_bulk_action', [ $this, 'ajax_bulk_action' ] );
        add_action( 'wp_ajax_bdea_hf_reorder_templates', [ $this, 'ajax_reorder_templates' ] );
        add_action( 'wp_ajax_bdea_hf_export_template', [ $this, 'ajax_export_template' ] );
        add_action( 'wp_ajax_bdea_hf_import_template', [ $this, 'ajax_import_template' ] );
        add_filter( 'post_row_actions', [ $this, 'add_row_actions' ], 10, 2 );
        add_filter( 'bulk_actions-edit-bdea_header_footer', [ $this, 'add_bulk_actions' ] );
    }

    public function hide_notices() {
        $screen = get_current_screen();
        if ( $screen && false !== strpos( $screen->id, 'bdea-hf' ) ) {
            remove_all_actions( 'admin_notices' );
            remove_all_actions( 'all_admin_notices' );
            echo '<style>.notice,.updated,.error,.update-nag{display:none!important}</style>';
        }
    }

    public function register_admin_menu() {
        add_submenu_page(
            'elementstack-settings',
            'Header / Footer Builder',
            'Header / Footer',
            'manage_options',
            'bdea-hf-builder',
            [ $this, 'render_admin_page' ]
        );

        add_submenu_page(
            'elementstack-settings',
            'Settings',
            'Settings',
            'manage_options',
            'bdea-hf-settings',
            [ $this, 'render_settings_page' ]
        );
    }

    public function render_admin_page() {
        $templates = $this->get_templates();
        $all_pages = get_pages();
        ?>
        <div class="wrap bdea-hf-wrap">
            <div class="bdea-hf-page-header">
                <div class="bdea-hf-page-header-left">
                    <div class="bdea-hf-page-header-icon dashicons dashicons-editor-kitchensink"></div>
                    <div>
                        <h1>Header / Footer</h1>
                        <p><?php echo count( $templates ); ?> template<?php echo count( $templates ) !== 1 ? 's' : ''; ?> created</p>
                    </div>
                </div>
                <div class="bdea-hf-page-header-right">
                    <button type="button" class="bdea-hf-create-btn" data-type="header">
                        Add New Header
                    </button>
                    <button type="button" class="bdea-hf-create-btn" data-type="footer" style="margin-left:8px;">
                        Add New Footer
                    </button>
                    <button type="button" class="bdea-hf-create-btn bdea-hf-import-btn" style="margin-left:8px;">
                        Import
                    </button>
                </div>
            </div>

            <div class="bdea-hf-bulk-bar">
                <select id="bdea-hf-bulk-action">
                    <option value="">Bulk Actions</option>
                    <option value="trash">Trash</option>
                    <option value="activate">Activate</option>
                    <option value="deactivate">Deactivate</option>
                </select>
                <button type="button" class="button" id="bdea-hf-bulk-apply" data-nonce="<?php echo esc_attr( wp_create_nonce( 'bdea_hf_bulk' ) ); ?>">Apply</button>
            </div>

            <table class="wp-list-table widefat fixed striped table-view-list bdea-hf-table">
                <thead>
                    <tr>
                        <th width="30"><input type="checkbox" id="bdea-hf-select-all" /></th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Display Conditions</th>
                        <th>Created</th>
                        <th>Updated</th>
                        <th width="220">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ( empty( $templates ) ) : ?>
                        <tr><td colspan="7">No templates found. Click "Add New Header" or "Add New Footer" to create one.</td></tr>
                    <?php else : ?>
                        <?php foreach ( $templates as $post ) : ?>
                            <?php
                            $post_id    = $post->ID;
                            $type       = get_post_meta( $post_id, '_bdea_hf_template_type', true );
                            $conditions = get_post_meta( $post_id, '_bdea_hf_conditions', true );
                            $is_active  = 'publish' === $post->post_status && is_array( $conditions ) && ! empty( $conditions );
                            $cond_label = $this->get_conditions_label( $conditions );
                            $edit_url   = add_query_arg(
                                [ 'action' => 'elementor', 'post' => $post_id ],
                                admin_url( 'post.php' )
                            );
                            ?>
                            <tr data-id="<?php echo esc_attr( $post_id ); ?>">
                                <td><input type="checkbox" class="bdea-hf-cb" value="<?php echo esc_attr( $post_id ); ?>" /></td>
                                <td><strong><?php echo esc_html( $post->post_title ); ?></strong></td>
                                <td>
                                    <?php if ( 'header' === $type ) : ?>
                                        <span class="bdea-hf-badge bdea-hf-badge-header">&#8593; Header</span>
                                    <?php elseif ( 'footer' === $type ) : ?>
                                        <span class="bdea-hf-badge bdea-hf-badge-footer">&#8595; Footer</span>
                                    <?php else : ?>
                                        <em>None</em>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ( $is_active ) : ?>
                                        <span class="bdea-hf-badge bdea-hf-badge-active">Active</span>
                                    <?php else : ?>
                                        <span class="bdea-hf-badge bdea-hf-badge-inactive">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="bdea-hf-cond-cell">
                                        <?php if ( ! empty( $conditions ) ) : ?>
                                            <div class="bdea-hf-cond-chips">
                                                <?php foreach ( (array) $conditions as $cond ) : ?>
                                                    <?php
                                                    $cond_id = $cond['condition'] ?? '';
                                                    $all     = $this->condition_manager->get_conditions();
                                                    $label   = isset( $all[ $cond_id ]['label'] ) ? $all[ $cond_id ]['label'] : $cond_id;
                                                    $is_exclude = 'exclude' === ( $cond['type'] ?? '' );
                                                    ?>
                                                    <span class="bdea-hf-cond-chip <?php echo $is_exclude ? 'bdea-hf-cond-chip-exclude' : ''; ?>">
                                                        <?php if ( $is_exclude ) : ?>
                                                            <svg width="12" height="12" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M3 3l6 6M9 3l-6 6"/></svg>
                                                        <?php else : ?>
                                                            <svg width="12" height="12" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M2.5 6h7M6 2.5v7"/></svg>
                                                        <?php endif; ?>
                                                        <?php echo esc_html( $label ); ?>
                                                    </span>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php else : ?>
                                            <span class="bdea-hf-cond-empty">All Website</span>
                                        <?php endif; ?>
                                        <button type="button" class="bdea-hf-edit-cond" data-id="<?php echo esc_attr( $post_id ); ?>">
                                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M10 1.5l2.5 2.5L4.5 12H2v-2.5L10 1.5z"/></svg>
                                            Edit
                                        </button>
                                    </div>
                                </td>
                                <td class="bdea-hf-date-cell">
                                    <span class="bdea-hf-date"><?php echo esc_html( get_the_date( 'M j, Y', $post ) ); ?></span>
                                    <span class="bdea-hf-time"><?php echo esc_html( get_the_time( 'g:i a', $post ) ); ?></span>
                                </td>
                                <td class="bdea-hf-date-cell">
                                    <span class="bdea-hf-date"><?php echo esc_html( get_the_modified_date( 'M j, Y', $post ) ); ?></span>
                                    <span class="bdea-hf-time"><?php echo esc_html( get_the_modified_time( 'g:i a', $post ) ); ?></span>
                                </td>
                                <td>
                                    <a href="<?php echo esc_url( $edit_url ); ?>" class="button button-small bdea-hf-btn-edit">
                                        Edit with Elementor
                                    </a>
                                    <button type="button" class="button button-small bdea-hf-duplicate-btn" data-id="<?php echo esc_attr( $post_id ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'bdea_hf_duplicate' ) ); ?>">
                                        Duplicate
                                    </button>
                                    <button type="button" class="button button-small bdea-hf-export-btn" data-id="<?php echo esc_attr( $post_id ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'bdea_hf_export' ) ); ?>">
                                        Export
                                    </button>
                                    <a href="<?php echo esc_url( add_query_arg( [ 'elementor-preview' => $post_id ], get_permalink( $post_id ) ) ); ?>" class="button button-small" target="_blank">
                                        Preview
                                    </a>
                                    <a href="<?php echo get_delete_post_link( $post_id ); ?>" class="button button-small bdea-hf-trash" onclick="return confirm('Delete this template?');">
                                        Trash
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php $this->render_create_modal( $all_pages ); ?>
        <?php $this->render_conditions_modal(); ?>
        <?php $this->render_import_modal(); ?>
        <?php
    }

    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1>Header & Footer Builder Settings</h1>
            <form method="post" action="options.php">
                <?php settings_fields( 'bdea_options_group' ); ?>
                <table class="form-table">
                    <tr>
                        <th>Enable Header & Footer Builder</th>
                        <td>
                            <?php
                            $module_status = get_option( 'bdea_module_status', [] );
                            $enabled = ! empty( $module_status['header_footer'] );
                            ?>
                            <label>
                                <input type="checkbox" name="bdea_module_status[header_footer]" value="1" <?php checked( $enabled ); ?> />
                                Active
                            </label>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    private function render_import_modal() {
        ?>
        <div id="bdea-hf-import-modal" class="bdea-hf-modal-overlay" style="display:none;">
            <div class="bdea-hf-modal">
                <div class="bdea-hf-modal-header">
                    <div class="bdea-hf-modal-header-icon dashicons dashicons-upload"></div>
                    <div>
                        <h2>Import Template</h2>
                        <p class="bdea-hf-modal-subtitle">Upload a previously exported .json file</p>
                    </div>
                    <button type="button" class="bdea-hf-modal-close">&times;</button>
                </div>
                <div class="bdea-hf-modal-body">
                    <form id="bdea-hf-import-form">
                        <div class="bdea-hf-field">
                            <label>Select JSON File</label>
                            <input type="file" name="import_file" accept=".json" required />
                            <span class="bdea-hf-field-desc">File exported from the Export button</span>
                        </div>
                    </form>
                </div>
                <div class="bdea-hf-modal-footer">
                    <button type="button" class="bdea-hf-btn-cancel" data-close-modal>Cancel</button>
                    <button type="button" class="bdea-hf-btn-primary bdea-hf-import-submit" data-nonce="<?php echo esc_attr( wp_create_nonce( 'bdea_hf_import' ) ); ?>">
                        Import Template
                    </button>
                </div>
            </div>
        </div>
        <?php
    }

    private function render_create_modal( $pages ) {
        ?>
        <div id="bdea-hf-create-modal" class="bdea-hf-modal-overlay" style="display:none;">
            <div class="bdea-hf-modal">
                <div class="bdea-hf-modal-header">
                    <div class="bdea-hf-modal-header-icon dashicons dashicons-editor-kitchensink"></div>
                    <div>
                        <h2>Create <span class="bdea-hf-modal-type-label">Header</span> Template</h2>
                        <p class="bdea-hf-modal-subtitle">Set up a new template with display rules</p>
                    </div>
                    <button type="button" class="bdea-hf-modal-close">&times;</button>
                </div>
                <div class="bdea-hf-modal-body">
                    <form id="bdea-hf-create-form">
                        <input type="hidden" name="type" value="" />

                        <div class="bdea-hf-field">
                            <label><span class="dashicons dashicons-edit"></span> Template Name</label>
                            <input type="text" name="name" placeholder="e.g. Main Header, Footer v2" required />
                        </div>

                        <div class="bdea-hf-field-row">
                            <div class="bdea-hf-field">
                                <label><span class="dashicons dashicons-layout"></span> Display Condition</label>
                                <select name="condition">
                                    <option value="entire_site">Entire Website</option>
                                    <option value="front_page">Front Page</option>
                                    <option value="home_page">Home / Blog Page</option>
                                    <option value="custom_url">Custom URL</option>
                                    <option value="singular">All Singular</option>
                                    <option value="singular:post_type:post">All Blog Posts</option>
                                    <option value="singular:post_type:page">All Pages</option>
                                    <option value="archive">All Archives</option>
                                    <option value="search">Search Results</option>
                                    <option value="404">404 Page</option>
                                </select>
                            </div>

                            <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                            <div class="bdea-hf-field">
                                <label><span class="dashicons dashicons-cart"></span> WooCommerce</label>
                                <select name="woo">
                                    <option value="">— None —</option>
                                    <option value="woocommerce:shop">Shop Page</option>
                                    <option value="woocommerce:product">Product Page</option>
                                    <option value="woocommerce:cart">Cart Page</option>
                                    <option value="woocommerce:checkout">Checkout Page</option>
                                    <option value="woocommerce:account">My Account Page</option>
                                    <option value="woocommerce:product_archive">Product Archive</option>
                                </select>
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="bdea-hf-field-row">
                            <div class="bdea-hf-field">
                                <label><span class="dashicons dashicons-plus"></span> Include Pages</label>
                                <select name="include_pages[]" class="bdea-hf-page-select" multiple>
                                    <?php foreach ( $pages as $page ) : ?>
                                        <option value="<?php echo esc_attr( $page->ID ); ?>"><?php echo esc_html( $page->post_title ); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="bdea-hf-select-hint">Hold Ctrl to select multiple</span>
                                <button type="button" class="bdea-hf-clear-select" data-target="include_pages">Clear</button>
                            </div>

                            <div class="bdea-hf-field">
                                <label><span class="dashicons dashicons-dismiss"></span> Exclude Pages</label>
                                <select name="exclude_pages[]" class="bdea-hf-page-select" multiple>
                                    <?php foreach ( $pages as $page ) : ?>
                                        <option value="<?php echo esc_attr( $page->ID ); ?>"><?php echo esc_html( $page->post_title ); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="bdea-hf-select-hint">Hold Ctrl to select multiple</span>
                                <button type="button" class="bdea-hf-clear-select" data-target="exclude_pages">Clear</button>
                            </div>
                        </div>

                        <div class="bdea-hf-field-row bdea-hf-field-checkboxes">
                            <div class="bdea-hf-field bdea-hf-field-inline">
                                <label class="bdea-hf-checkbox-label">
                                    <input type="checkbox" name="sticky" value="1" />
                                    <span class="bdea-hf-checkbox-ui"></span>
                                    <span class="bdea-hf-checkbox-content">
                                        <strong>Make Header Sticky</strong>
                                        <span class="bdea-hf-field-desc">Header stays fixed at top on scroll</span>
                                    </span>
                                </label>
                            </div>
                            <div class="bdea-hf-field bdea-hf-field-inline">
                                <label class="bdea-hf-checkbox-label">
                                    <input type="checkbox" name="scroll_animation" value="1" />
                                    <span class="bdea-hf-checkbox-ui"></span>
                                    <span class="bdea-hf-checkbox-content">
                                        <strong>Scroll Animation</strong>
                                        <span class="bdea-hf-field-desc">Header hides/shows on scroll</span>
                                    </span>
                                </label>
                            </div>
                            <div class="bdea-hf-field bdea-hf-field-inline">
                                <label class="bdea-hf-checkbox-label">
                                    <input type="checkbox" name="disable_theme" value="yes" />
                                    <span class="bdea-hf-checkbox-ui"></span>
                                    <span class="bdea-hf-checkbox-content">
                                        <strong>Disable Theme Header/Footer</strong>
                                        <span class="bdea-hf-field-desc">Replace theme header/footer with Elementor</span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="bdea-hf-modal-footer">
                    <button type="button" class="bdea-hf-btn-cancel" data-close-modal>Cancel</button>
                    <button type="button" class="bdea-hf-btn-primary bdea-hf-create-submit" data-nonce="<?php echo esc_attr( wp_create_nonce( 'bdea_hf_create' ) ); ?>">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M8 3v10M3 8h10"/></svg>
                        Create &amp; Edit
                    </button>
                </div>
            </div>
        </div>
        <?php
    }

    private function render_conditions_modal() {
        ?>
        <div id="bdea-hf-conditions-modal" class="bdea-hf-modal-overlay" style="display:none;">
            <div class="bdea-hf-modal">
                <div class="bdea-hf-modal-header">
                    <div class="bdea-hf-modal-header-icon dashicons dashicons-filter"></div>
                    <div>
                        <h2>Edit Display Conditions</h2>
                        <p class="bdea-hf-modal-subtitle">Control where this template appears</p>
                    </div>
                    <button type="button" class="bdea-hf-modal-close">&times;</button>
                </div>
                <div class="bdea-hf-modal-body">
                    <form id="bdea-hf-conditions-form">
                        <input type="hidden" name="template_id" value="" />
                        <div class="bdea-hf-conditions-list"></div>
                        <p>
                            <button type="button" class="button bdea-hf-add-condition-row">+ Add Condition</button>
                        </p>
                        <hr style="margin:16px 0;border:none;border-top:1px solid #e2e4e7;">
                        <p style="margin:0 0 8px;font-weight:600;color:#1e1e1e;">Template Settings</p>
                        <div class="bdea-hf-field-row bdea-hf-field-checkboxes bdea-hf-conditions-settings">
                            <div class="bdea-hf-field bdea-hf-field-inline">
                                <label class="bdea-hf-checkbox-label">
                                    <input type="checkbox" name="sticky" value="yes" />
                                    <span class="bdea-hf-checkbox-ui"></span>
                                    <span class="bdea-hf-checkbox-content">
                                        <strong>Make Header Sticky</strong>
                                        <span class="bdea-hf-field-desc">Header stays fixed at top</span>
                                    </span>
                                </label>
                            </div>
                            <div class="bdea-hf-field bdea-hf-field-inline">
                                <label class="bdea-hf-checkbox-label">
                                    <input type="checkbox" name="scroll_animation" value="yes" />
                                    <span class="bdea-hf-checkbox-ui"></span>
                                    <span class="bdea-hf-checkbox-content">
                                        <strong>Scroll Animation</strong>
                                        <span class="bdea-hf-field-desc">Header hides/shows on scroll</span>
                                    </span>
                                </label>
                            </div>
                            <div class="bdea-hf-field bdea-hf-field-inline">
                                <label class="bdea-hf-checkbox-label">
                                    <input type="checkbox" name="disable_theme" value="yes" />
                                    <span class="bdea-hf-checkbox-ui"></span>
                                    <span class="bdea-hf-checkbox-content">
                                        <strong>Disable Theme Header/Footer</strong>
                                        <span class="bdea-hf-field-desc">Hide theme header/footer with CSS</span>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <p style="margin:12px 0 8px;font-weight:600;color:#1e1e1e;">Device Visibility</p>
                        <div class="bdea-hf-field-row bdea-hf-field-checkboxes">
                            <div class="bdea-hf-field bdea-hf-field-inline">
                                <label class="bdea-hf-checkbox-label">
                                    <input type="checkbox" name="device_desktop" value="yes" checked />
                                    <span class="bdea-hf-checkbox-ui"></span>
                                    <span class="bdea-hf-checkbox-content">
                                        <strong>Desktop</strong>
                                        <span class="bdea-hf-field-desc">Show on desktop</span>
                                    </span>
                                </label>
                            </div>
                            <div class="bdea-hf-field bdea-hf-field-inline">
                                <label class="bdea-hf-checkbox-label">
                                    <input type="checkbox" name="device_tablet" value="yes" checked />
                                    <span class="bdea-hf-checkbox-ui"></span>
                                    <span class="bdea-hf-checkbox-content">
                                        <strong>Tablet</strong>
                                        <span class="bdea-hf-field-desc">Show on tablet</span>
                                    </span>
                                </label>
                            </div>
                            <div class="bdea-hf-field bdea-hf-field-inline">
                                <label class="bdea-hf-checkbox-label">
                                    <input type="checkbox" name="device_mobile" value="yes" checked />
                                    <span class="bdea-hf-checkbox-ui"></span>
                                    <span class="bdea-hf-checkbox-content">
                                        <strong>Mobile</strong>
                                        <span class="bdea-hf-field-desc">Show on mobile</span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="bdea-hf-modal-footer">
                    <button type="button" class="bdea-hf-btn-cancel" data-close-modal>Cancel</button>
                    <button type="button" class="bdea-hf-btn-primary bdea-hf-conditions-save" data-nonce="<?php echo esc_attr( wp_create_nonce( 'bdea_hf_conditions' ) ); ?>">
                        Save Conditions
                    </button>
                </div>
            </div>
        </div>
        <?php
    }

    public function enqueue_admin_assets( $hook ) {
        if ( false === strpos( $hook, 'bdea-hf' ) ) {
            return;
        }

        $css_file = __DIR__ . '/assets/css/admin.css';
        $css_ver  = file_exists( $css_file ) ? filemtime( $css_file ) : BDEA_VERSION;
        wp_enqueue_style(
            'bdea-hf-admin-style',
            plugin_dir_url( __FILE__ ) . 'assets/css/admin.css',
            [],
            $css_ver
        );

        $js_file = __DIR__ . '/assets/js/admin.js';
        $js_ver  = file_exists( $js_file ) ? filemtime( $js_file ) : BDEA_VERSION;
        wp_enqueue_script(
            'bdea-hf-admin-script',
            plugin_dir_url( __FILE__ ) . 'assets/js/admin.js',
            [ 'jquery', 'jquery-ui-sortable' ],
            $js_ver,
            true
        );

        wp_localize_script( 'bdea-hf-admin-script', 'bdeaHFData', [
            'ajax_url'     => admin_url( 'admin-ajax.php' ),
            'conditions'   => $this->condition_manager->get_conditions_grouped(),
            'reorder_nonce' => wp_create_nonce( 'bdea_hf_reorder' ),
            'strings'   => [
                'include' => __( 'Include', 'bdea' ),
                'exclude' => __( 'Exclude', 'bdea' ),
            ],
        ] );
    }

    public function ajax_create_template() {
        check_ajax_referer( 'bdea_hf_create', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( -1 );
        }

        $name        = isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';
        $type        = isset( $_POST['type'] ) ? sanitize_key( $_POST['type'] ) : '';
        $condition   = isset( $_POST['condition'] ) ? sanitize_text_field( $_POST['condition'] ) : '';
        $woo         = isset( $_POST['woo'] ) ? sanitize_text_field( $_POST['woo'] ) : '';
        $include_pgs = isset( $_POST['include_pages'] ) ? array_map( 'intval', $_POST['include_pages'] ) : [];
        $exclude_pgs = isset( $_POST['exclude_pages'] ) ? array_map( 'intval', $_POST['exclude_pages'] ) : [];
        $sticky        = ! empty( $_POST['sticky'] );
        $scroll_anim   = ! empty( $_POST['scroll_animation'] );
        $disable_theme = ! empty( $_POST['disable_theme'] );

        if ( empty( $name ) || ! in_array( $type, [ 'header', 'footer' ], true ) ) {
            wp_send_json_error( [ 'message' => 'Name and type are required.' ] );
        }

        $post_id = wp_insert_post( [
            'post_title'  => $name,
            'post_type'   => 'bdea_header_footer',
            'post_status' => 'publish',
        ] );

        if ( is_wp_error( $post_id ) ) {
            wp_send_json_error( [ 'message' => 'Failed to create template.' ] );
        }

        update_post_meta( $post_id, '_bdea_hf_template_type', $type );

        $conditions = [];

        if ( ! empty( $condition ) ) {
            $conditions[] = [ 'type' => 'include', 'condition' => $condition ];
        }

        if ( ! empty( $woo ) ) {
            $conditions[] = [ 'type' => 'include', 'condition' => $woo ];
        }

        foreach ( $include_pgs as $page_id ) {
            $conditions[] = [ 'type' => 'include', 'condition' => 'singular:post_id:' . $page_id ];
        }

        foreach ( $exclude_pgs as $page_id ) {
            $conditions[] = [ 'type' => 'exclude', 'condition' => 'singular:post_id:' . $page_id ];
        }

        update_post_meta( $post_id, '_bdea_hf_conditions', $conditions );

        if ( $sticky ) {
            update_post_meta( $post_id, '_bdea_hf_sticky', 'yes' );
        }

        if ( $scroll_anim ) {
            update_post_meta( $post_id, '_bdea_hf_scroll_animation', 'yes' );
        }

        if ( $disable_theme ) {
            update_post_meta( $post_id, '_bdea_hf_disable_theme', 'yes' );
        }

        $edit_url = add_query_arg(
            [ 'action' => 'elementor', 'post' => $post_id ],
            admin_url( 'post.php' )
        );

        wp_send_json_success( [ 'edit_url' => $edit_url ] );
    }

    public function ajax_get_template_conditions() {
        check_ajax_referer( 'bdea_hf_conditions', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( -1 );
        }

        $post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;

        if ( ! $post_id ) {
            wp_send_json_error( [ 'message' => 'Invalid post ID.' ] );
        }

        $conditions       = get_post_meta( $post_id, '_bdea_hf_conditions', true );
        $disable_theme    = get_post_meta( $post_id, '_bdea_hf_disable_theme', true );
        $sticky           = get_post_meta( $post_id, '_bdea_hf_sticky', true );
        $scroll_animation = get_post_meta( $post_id, '_bdea_hf_scroll_animation', true );
        $type             = get_post_meta( $post_id, '_bdea_hf_template_type', true );
        $device_vis       = get_post_meta( $post_id, '_bdea_hf_device_visibility', true );

        if ( ! is_array( $conditions ) ) {
            $conditions = [];
        }

        if ( ! is_array( $device_vis ) ) {
            $device_vis = [ 'desktop' => 'yes', 'tablet' => 'yes', 'mobile' => 'yes' ];
        }

        wp_send_json_success( [
            'conditions'        => $conditions,
            'disable_theme'     => $disable_theme,
            'sticky'            => $sticky,
            'scroll_animation'  => $scroll_animation,
            'type'              => $type,
            'device_visibility' => $device_vis,
        ] );
    }

    public function ajax_update_conditions() {
        check_ajax_referer( 'bdea_hf_conditions', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( -1 );
        }

        $post_id    = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
        $conditions = isset( $_POST['conditions'] ) ? $_POST['conditions'] : [];

        if ( ! $post_id ) {
            wp_send_json_error( [ 'message' => 'Invalid post ID.' ] );
        }

        $sanitized = [];

        foreach ( $conditions as $cond ) {
            $type      = isset( $cond['type'] ) ? sanitize_key( $cond['type'] ) : 'include';
            $condition = isset( $cond['condition'] ) ? sanitize_text_field( $cond['condition'] ) : '';

            if ( ! empty( $condition ) ) {
                $sanitized[] = [
                    'type'      => $type,
                    'condition' => $condition,
                ];
            }
        }

        update_post_meta( $post_id, '_bdea_hf_conditions', $sanitized );
        update_post_meta( $post_id, '_bdea_hf_disable_theme', isset( $_POST['disable_theme'] ) ? 'yes' : '' );
        update_post_meta( $post_id, '_bdea_hf_sticky', isset( $_POST['sticky'] ) ? 'yes' : '' );
        update_post_meta( $post_id, '_bdea_hf_scroll_animation', isset( $_POST['scroll_animation'] ) ? 'yes' : '' );

        $device_vis = [
            'desktop' => isset( $_POST['device_desktop'] ) ? 'yes' : '',
            'tablet'  => isset( $_POST['device_tablet'] ) ? 'yes' : '',
            'mobile'  => isset( $_POST['device_mobile'] ) ? 'yes' : '',
        ];
        update_post_meta( $post_id, '_bdea_hf_device_visibility', $device_vis );

        $this->cache->flush_all();

        wp_send_json_success( [ 'message' => 'Conditions saved.' ] );
    }

    public function ajax_duplicate_template() {
        check_ajax_referer( 'bdea_hf_duplicate', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( -1 );
        }

        $post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;

        if ( ! $post_id ) {
            wp_send_json_error( [ 'message' => 'Invalid post ID.' ] );
        }

        $post = get_post( $post_id );

        if ( ! $post || 'bdea_header_footer' !== $post->post_type ) {
            wp_send_json_error( [ 'message' => 'Template not found.' ] );
        }

        $new_id = wp_insert_post( [
            'post_title'  => $post->post_title . ' (Copy)',
            'post_type'   => 'bdea_header_footer',
            'post_status' => 'publish',
        ] );

        if ( is_wp_error( $new_id ) ) {
            wp_send_json_error( [ 'message' => 'Failed to duplicate template.' ] );
        }

        $meta_keys = [
            '_bdea_hf_template_type',
            '_bdea_hf_conditions',
            '_bdea_hf_priority',
            '_bdea_hf_sticky',
            '_bdea_hf_scroll_animation',
            '_bdea_hf_disable_theme',
            '_bdea_hf_device_visibility',
        ];

        foreach ( $meta_keys as $key ) {
            $val = get_post_meta( $post_id, $key, true );
            if ( '' !== $val ) {
                update_post_meta( $new_id, $key, $val );
            }
        }

        $this->cache->flush_all();

        wp_send_json_success( [ 'edit_url' => add_query_arg(
            [ 'action' => 'elementor', 'post' => $new_id ],
            admin_url( 'post.php' )
        ) ] );
    }

    public function ajax_bulk_action() {
        check_ajax_referer( 'bdea_hf_bulk', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( -1 );
        }

        $action    = isset( $_POST['doaction'] ) ? sanitize_key( $_POST['doaction'] ) : '';
        $post_ids  = isset( $_POST['post_ids'] ) ? array_map( 'intval', $_POST['post_ids'] ) : [];

        if ( empty( $post_ids ) || ! in_array( $action, [ 'trash', 'activate', 'deactivate' ], true ) ) {
            wp_send_json_error( [ 'message' => 'Invalid request.' ] );
        }

        foreach ( $post_ids as $id ) {
            if ( 'trash' === $action ) {
                wp_trash_post( $id );
            } elseif ( 'activate' === $action ) {
                wp_publish_post( $id );
            } elseif ( 'deactivate' === $action ) {
                wp_update_post( [ 'ID' => $id, 'post_status' => 'draft' ] );
            }
        }

        $this->cache->flush_all();

        wp_send_json_success( [ 'message' => count( $post_ids ) . ' template(s) updated.' ] );
    }

    public function ajax_export_template() {
        check_ajax_referer( 'bdea_hf_export', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( -1 );
        }

        $post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;

        if ( ! $post_id ) {
            wp_send_json_error( [ 'message' => 'Invalid post ID.' ] );
        }

        $post = get_post( $post_id );

        if ( ! $post || 'bdea_header_footer' !== $post->post_type ) {
            wp_send_json_error( [ 'message' => 'Template not found.' ] );
        }

        $meta_keys = [
            '_bdea_hf_template_type',
            '_bdea_hf_conditions',
            '_bdea_hf_priority',
            '_bdea_hf_sticky',
            '_bdea_hf_scroll_animation',
            '_bdea_hf_disable_theme',
            '_bdea_hf_device_visibility',
        ];

        $meta = [];

        foreach ( $meta_keys as $key ) {
            $meta[ $key ] = get_post_meta( $post_id, $key, true );
        }

        $elementor_data = get_post_meta( $post_id, '_elementor_data', true );
        $elementor_css  = get_post_meta( $post_id, '_elementor_css', true );
        $template_type  = get_post_meta( $post_id, '_elementor_template_type', true );

        $export = [
            'version'              => BDEA_VERSION,
            'title'                => $post->post_title,
            'type'                 => 'bdea_header_footer',
            'meta'                 => $meta,
            'elementor_data'       => $elementor_data,
            'elementor_css'        => $elementor_css,
            'elementor_template_type' => $template_type ?: 'bdea-hf-document',
        ];

        wp_send_json_success( [ 'export' => $export ] );
    }

    public function ajax_import_template() {
        check_ajax_referer( 'bdea_hf_import', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( -1 );
        }

        if ( ! isset( $_FILES['import_file'] ) || UPLOAD_ERR_OK !== $_FILES['import_file']['error'] ) {
            wp_send_json_error( [ 'message' => 'File upload failed.' ] );
        }

        $content = file_get_contents( $_FILES['import_file']['tmp_name'] );
        $data    = json_decode( $content, true );

        if ( ! $data || empty( $data['title'] ) ) {
            wp_send_json_error( [ 'message' => 'Invalid or corrupt import file.' ] );
        }

        $post_id = wp_insert_post( [
            'post_title'  => sanitize_text_field( $data['title'] ),
            'post_type'   => 'bdea_header_footer',
            'post_status' => 'publish',
        ] );

        if ( is_wp_error( $post_id ) ) {
            wp_send_json_error( [ 'message' => 'Failed to create template.' ] );
        }

        if ( ! empty( $data['meta'] ) ) {
            foreach ( $data['meta'] as $key => $value ) {
                if ( '' !== $value ) {
                    update_post_meta( $post_id, $key, $value );
                }
            }
        }

        if ( isset( $data['elementor_data'] ) ) {
            $elementor_data = $data['elementor_data'];
            if ( is_array( $elementor_data ) || is_object( $elementor_data ) ) {
                $elementor_data = wp_slash( wp_json_encode( $elementor_data ) );
            }
            update_post_meta( $post_id, '_elementor_data', $elementor_data );
        }

        if ( isset( $data['elementor_css'] ) ) {
            update_post_meta( $post_id, '_elementor_css', $data['elementor_css'] );
        }

        if ( isset( $data['elementor_template_type'] ) ) {
            update_post_meta( $post_id, '_elementor_template_type', $data['elementor_template_type'] );
        }

        add_post_type_support( 'bdea_header_footer', 'elementor' );

        $this->cache->flush_all();

        wp_send_json_success( [ 'edit_url' => add_query_arg(
            [ 'action' => 'elementor', 'post' => $post_id ],
            admin_url( 'post.php' )
        ) ] );
    }

    public function ajax_reorder_templates() {
        check_ajax_referer( 'bdea_hf_reorder', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( -1 );
        }

        $order = isset( $_POST['order'] ) ? $_POST['order'] : [];

        if ( empty( $order ) ) {
            wp_send_json_error( [ 'message' => 'No order data.' ] );
        }

        foreach ( $order as $index => $post_id ) {
            wp_update_post( [
                'ID'         => intval( $post_id ),
                'menu_order' => intval( $index ),
            ] );
        }

        $this->cache->flush_all();

        wp_send_json_success( [ 'message' => 'Order saved.' ] );
    }

    private function get_templates( $type = '' ) {
        $args = [
            'post_type'      => 'bdea_header_footer',
            'post_status'    => [ 'publish', 'draft' ],
            'posts_per_page' => -1,
            'orderby'        => 'menu_order date',
            'order'          => 'ASC',
        ];
        if ( $type ) {
            $args['meta_key']   = '_bdea_hf_template_type';
            $args['meta_value'] = $type;
        }
        return get_posts( $args );
    }

    private function get_conditions_label( $conditions ) {
        if ( empty( $conditions ) || ! is_array( $conditions ) ) {
            return 'All Website';
        }

        $labels = [];

        foreach ( $conditions as $cond ) {
            $condition_id = $cond['condition'] ?? '';
            $all          = $this->condition_manager->get_conditions();
            $label        = isset( $all[ $condition_id ]['label'] ) ? $all[ $condition_id ]['label'] : $condition_id;
            $prefix       = ( 'exclude' === $cond['type'] ) ? '!' : '';
            $labels[]     = $prefix . $label;
        }

        return implode( ', ', $labels );
    }

    public function add_row_actions( $actions, $post ) {
        if ( 'bdea_header_footer' !== $post->post_type ) {
            return $actions;
        }

        $new_actions = [];
        $post_type_object = get_post_type_object( 'bdea_header_footer' );

        if ( current_user_can( 'edit_post', $post->ID ) ) {
            $new_actions['edit_with_elementor'] = sprintf(
                '<a href="%s">%s</a>',
                esc_url( add_query_arg( [ 'action' => 'elementor' ], admin_url( 'post.php?post=' . $post->ID ) ) ),
                esc_html__( 'Edit with Elementor', 'bdea' )
            );
        }

        if ( current_user_can( 'delete_post', $post->ID ) ) {
            $new_actions['trash'] = sprintf(
                '<a href="%s" class="submitdelete">%s</a>',
                get_delete_post_link( $post->ID ),
                esc_html__( 'Trash', 'bdea' )
            );
        }

        return $new_actions;
    }

    public function add_bulk_actions( $actions ) {
        unset( $actions['edit'] );
        $actions['trash']       = __( 'Move to Trash', 'bdea' );
        $actions['activate']    = __( 'Activate', 'bdea' );
        $actions['deactivate']  = __( 'Deactivate', 'bdea' );
        return $actions;
    }
}
