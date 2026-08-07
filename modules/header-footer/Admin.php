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
        add_action( 'wp_ajax_bdea_hf_get_posts', [ $this, 'ajax_get_posts' ] );
        add_action( 'admin_post_bdea_hf_restore', [ $this, 'handle_restore' ] );
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
            'Theme Builder',
            'Theme Builder',
            'manage_options',
            'bdea-hf-builder',
            [ $this, 'render_admin_page' ]
        );
    }

    public function render_admin_page() {
        $status_view = isset( $_GET['bdea_status'] ) ? sanitize_key( $_GET['bdea_status'] ) : 'all';
        if ( ! in_array( $status_view, [ 'all', 'published', 'trash' ], true ) ) {
            $status_view = 'all';
        }

        $statuses    = [
            'all'       => [ 'publish', 'draft' ],
            'published' => [ 'publish' ],
            'trash'     => [ 'trash' ],
        ];

        $type_labels = [
            'header'       => 'Header',
            'footer'       => 'Footer',
            'single'       => 'Single Post',
            'archive'      => 'Archive',
            '404'          => '404 Page',
            'announcement' => 'Announcement',
            'bottom_bar'   => 'Bottom Bar',
            'loop'         => 'Loop Item',
        ];

        $type_view = isset( $_GET['bdea_type'] ) ? sanitize_key( $_GET['bdea_type'] ) : '';
        if ( ! isset( $type_labels[ $type_view ] ) ) {
            $type_view = '';
        }

        $type_counts = [];
        foreach ( array_keys( $type_labels ) as $type_key ) {
            $type_counts[ $type_key ] = count( $this->get_templates( $type_key, [ 'publish', 'draft' ] ) );
        }

        $templates   = $this->get_templates( $type_view, $statuses[ $status_view ] );
        $all_pages   = get_pages();
        $hf_counts   = wp_count_posts( 'bdea_header_footer' );
        $loop_templates = $this->get_templates( 'loop', [ 'publish', 'draft' ] );
        $all_count   = (int) $hf_counts->publish + (int) $hf_counts->draft + count( $loop_templates );
        $pub_count   = (int) $hf_counts->publish + count( array_filter( $loop_templates, function( $p ) { return 'publish' === $p->post_status; } ) );
        $trash_count = (int) $hf_counts->trash;
        $page_url    = admin_url( 'admin.php?page=bdea-hf-builder' );
        $base_url    = $type_view ? add_query_arg( 'bdea_type', $type_view, $page_url ) : $page_url;

        $create_label = $type_view ? 'Add New ' . $type_labels[ $type_view ] : 'Add New Template';
        $create_type  = $type_view ? $type_view : 'header';
        ?>
        <div class="wrap bdea-hf-wrap">
            <h1 class="wp-heading-inline">Theme Builder</h1>

            <a href="#" class="page-title-action bdea-hf-create-btn" data-type="<?php echo esc_attr( $create_type ); ?>"><?php echo esc_html( $create_label ); ?></a>
            <a href="#" class="page-title-action bdea-hf-import-btn">Import</a>

            <hr class="wp-header-end">

            <nav class="nav-tab-wrapper bdea-hf-type-tabs">
                <a href="<?php echo esc_url( remove_query_arg( 'bdea_type', $page_url ) ); ?>" class="nav-tab <?php echo '' === $type_view ? 'nav-tab-active' : ''; ?>">All <span class="count">(<?php echo esc_html( $all_count ); ?>)</span></a>
                <?php foreach ( $type_labels as $type_key => $type_label ) : ?>
                    <a href="<?php echo esc_url( add_query_arg( 'bdea_type', $type_key, $page_url ) ); ?>" class="nav-tab <?php echo $type_key === $type_view ? 'nav-tab-active' : ''; ?>"><?php echo esc_html( $type_label ); ?> <span class="count">(<?php echo esc_html( $type_counts[ $type_key ] ); ?>)</span></a>
                <?php endforeach; ?>
            </nav>

            <ul class="subsubsub">
                <li class="all"><a href="<?php echo esc_url( $base_url ); ?>" class="<?php echo 'all' === $status_view ? 'current' : ''; ?>">All <span class="count">(<?php echo esc_html( $all_count ); ?>)</span></a> |</li>
                <li class="published"><a href="<?php echo esc_url( add_query_arg( 'bdea_status', 'published', $base_url ) ); ?>" class="<?php echo 'published' === $status_view ? 'current' : ''; ?>">Published <span class="count">(<?php echo esc_html( $pub_count ); ?>)</span></a> |</li>
                <li class="trash"><a href="<?php echo esc_url( add_query_arg( 'bdea_status', 'trash', $base_url ) ); ?>" class="<?php echo 'trash' === $status_view ? 'current' : ''; ?>">Trash <span class="count">(<?php echo esc_html( $trash_count ); ?>)</span></a></li>
            </ul>

            <p class="bdea-hf-list-sub"><?php echo count( $templates ); ?> template<?php echo count( $templates ) !== 1 ? 's' : ''; ?> found</p>

            <div class="bdea-hf-bulk-bar">
                <select id="bdea-hf-bulk-action">
                    <option value="">Bulk Actions</option>
                    <?php if ( 'trash' === $status_view ) : ?>
                        <option value="restore">Restore</option>
                        <option value="delete">Delete Permanently</option>
                    <?php else : ?>
                        <option value="trash">Trash</option>
                        <option value="activate">Activate</option>
                        <option value="deactivate">Deactivate</option>
                    <?php endif; ?>
                </select>
                <button type="button" class="button" id="bdea-hf-bulk-apply" data-nonce="<?php echo esc_attr( wp_create_nonce( 'bdea_hf_bulk' ) ); ?>">Apply</button>
            </div>

            <table class="wp-list-table widefat fixed striped table-view-list bdea-hf-table">
                <thead>
                    <tr>
                        <th id="cb" scope="col" class="manage-column check-column"><input type="checkbox" id="bdea-hf-select-all" /></th>
                        <th scope="col" class="manage-column column-title column-primary">Title</th>
                        <th scope="col" class="manage-column">Type</th>
                        <th scope="col" class="manage-column">Status</th>
                        <th scope="col" class="manage-column">Display Conditions</th>
                        <th scope="col" class="manage-column">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ( empty( $templates ) ) : ?>
                        <tr><td colspan="6">No <?php echo esc_html( $type_view ? strtolower( $type_labels[ $type_view ] ) . ' ' : '' ); ?>templates found. Click "<?php echo esc_html( $create_label ); ?>" to create one.</td></tr>
                    <?php else : ?>
                        <?php foreach ( $templates as $post ) : ?>
                            <?php
                            $post_id    = $post->ID;
                            $is_loop    = 'elementor_library' === $post->post_type;
                            $type       = $is_loop
                                ? get_post_meta( $post_id, '_elementor_template_type', true )
                                : get_post_meta( $post_id, '_bdea_hf_template_type', true );
                            $conditions = $is_loop ? [] : get_post_meta( $post_id, '_bdea_hf_conditions', true );
                            $is_trash   = 'trash' === $post->post_status;
                            $is_active  = 'publish' === $post->post_status;
                            $cond_label = $this->get_conditions_label( $conditions );
                            $edit_url   = add_query_arg(
                                [ 'action' => 'elementor', 'post' => $post_id ],
                                admin_url( 'post.php' )
                            );
                            $restore_url = wp_nonce_url(
                                add_query_arg( [ 'action' => 'bdea_hf_restore', 'id' => $post_id ], admin_url( 'admin-post.php' ) ),
                                'bdea_hf_restore_' . $post_id
                            );
                            ?>
                            <tr data-id="<?php echo esc_attr( $post_id ); ?>" class="<?php echo $is_trash ? 'bdea-hf-trash-row' : ''; ?>">
                                <th scope="row" class="check-column"><input type="checkbox" class="bdea-hf-cb" value="<?php echo esc_attr( $post_id ); ?>" /></th>
                                <td class="column-title column-primary">
                                    <div class="bdea-hf-type-chip-mobile">
                                        <?php $this->render_type_badge( $type ); ?>
                                    </div>
                                    <strong><a class="row-title" href="<?php echo esc_url( $edit_url ); ?>"><?php echo esc_html( $post->post_title ); ?></a></strong>
                                    <?php if ( $is_trash ) : ?>
                                        <div class="row-actions">
                                            <span class="restore"><a href="<?php echo esc_url( $restore_url ); ?>">Restore</a> | </span>
                                            <span class="trash"><a href="<?php echo esc_url( get_delete_post_link( $post_id ) ); ?>" class="bdea-hf-trash">Delete Permanently</a></span>
                                        </div>
                                    <?php else : ?>
                                        <div class="row-actions">
                                            <span class="edit"><a href="<?php echo esc_url( $edit_url ); ?>">Edit with Elementor</a> | </span>
                                            <span class="bdea-hf-dup"><a href="#" class="bdea-hf-duplicate-btn" data-id="<?php echo esc_attr( $post_id ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'bdea_hf_duplicate' ) ); ?>">Duplicate</a> | </span>
                                            <span class="bdea-hf-export"><a href="#" class="bdea-hf-export-btn" data-id="<?php echo esc_attr( $post_id ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'bdea_hf_export' ) ); ?>">Export</a> | </span>
                                            <span class="trash"><a href="<?php echo esc_url( get_delete_post_link( $post_id ) ); ?>" class="bdea-hf-trash">Trash</a></span>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td><?php $this->render_type_badge( $type ); ?></td>
                                <td>
                                    <?php if ( $is_trash ) : ?>
                                        <span class="bdea-hf-badge bdea-hf-badge-trash">Trashed</span>
                                    <?php elseif ( $is_active ) : ?>
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
                                        <?php if ( ! $is_trash ) : ?>
                                            <button type="button" class="bdea-hf-edit-cond" data-id="<?php echo esc_attr( $post_id ); ?>">
                                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M10 1.5l2.5 2.5L4.5 12H2v-2.5L10 1.5z"/></svg>
                                                Edit
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="bdea-hf-date-cell">
                                    <span class="bdea-hf-date"><?php echo esc_html( get_the_date( 'M j, Y', $post ) ); ?></span>
                                    <span class="bdea-hf-time"><?php echo esc_html( get_the_time( 'g:i a', $post ) ); ?></span>
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
                            <label><span class="dashicons dashicons-upload"></span> Select JSON File</label>
                            <div class="bdea-hf-dropzone" id="bdea-hf-dropzone">
                                <span class="dashicons dashicons-cloud-upload"></span>
                                <p><strong>Drop your .json file here</strong> or click to browse</p>
                                <span class="bdea-hf-dropzone-help">File exported from the Export button</span>
                                <span class="bdea-hf-dropzone-file"></span>
                                <input type="file" name="import_file" accept=".json" required />
                            </div>
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

                        <div class="bdea-hf-field">
                            <label><span class="dashicons dashicons-layout"></span> Template Type</label>
                            <select name="template_type" id="bdea-hf-create-type">
                                <option value="header">Header</option>
                                <option value="footer">Footer</option>
                                <option value="single">Single Post Template</option>
                                <option value="archive">Archive (Category / Tag / Loop)</option>
                                <option value="404">404 Page</option>
                                <option value="announcement">Announcement Bar</option>
                                <option value="bottom_bar">Bottom Bar</option>
                                <option value="loop">Loop Item Template</option>
                            </select>
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
                                    <?php
                                    $bdea_archive_condition_taxonomies = get_taxonomies( [ 'public' => true ], 'objects' );
                                    foreach ( $bdea_archive_condition_taxonomies as $bdea_tax ) :
                                        if ( in_array( $bdea_tax->name, [ 'elementor_library', 'bdea_header_footer', 'nav_menu', 'link_category' ], true ) ) {
                                            continue;
                                        }
                                        ?>
                                        <option value="archive:taxonomy:<?php echo esc_attr( $bdea_tax->name ); ?>">All <?php echo esc_html( $bdea_tax->labels->name ); ?> Archives</option>
                                        <?php
                                        $bdea_terms = get_terms( [ 'taxonomy' => $bdea_tax->name, 'hide_empty' => false, 'number' => 50 ] );
                                        if ( ! is_wp_error( $bdea_terms ) ) {
                                            foreach ( $bdea_terms as $bdea_term ) {
                                                ?>
                                                <option value="archive:taxonomy:<?php echo esc_attr( $bdea_tax->name ); ?>:term:<?php echo esc_attr( $bdea_term->slug ); ?>"><?php echo esc_html( $bdea_tax->labels->name ); ?>: <?php echo esc_html( $bdea_term->name ); ?></option>
                                                <?php
                                            }
                                        }
                                    endforeach;
                                    ?>
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
                                    <input type="checkbox" name="disable_theme" value="yes" />
                                    <span class="bdea-hf-checkbox-ui"></span>
                                    <span class="bdea-hf-checkbox-content">
                                        <strong>Disable Theme Header</strong>
                                        <span class="bdea-hf-field-desc">Replace theme header with Elementor</span>
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
                        <div class="bdea-hf-cond-search">
                            <span class="dashicons dashicons-search"></span>
                            <input type="text" id="bdea-hf-cond-search" placeholder="<?php esc_attr_e( 'Search conditions...', 'bdea' ); ?>" />
                            <button type="button" class="bdea-hf-cond-search-clear" title="<?php esc_attr_e( 'Clear', 'bdea' ); ?>">&times;</button>
                        </div>
                        <div class="bdea-hf-conditions-list"></div>
                        <p>
                            <button type="button" class="button bdea-hf-add-condition-row">+ Add Condition</button>
                        </p>
                        <hr style="margin:16px 0;border:none;border-top:1px solid #e2e4e7;">
                        <p style="margin:0 0 8px;font-weight:600;color:#1e1e1e;">Template Settings</p>
                        <p class="bdea-hf-field-desc" style="margin:0 0 12px;">
                            <?php esc_html_e( 'Sticky, transparent, schedule and other behavior settings are managed inside the Elementor editor (Settings panel).', 'bdea' ); ?>
                        </p>
                        <div class="bdea-hf-field-row bdea-hf-field-checkboxes bdea-hf-conditions-settings">
                            <div class="bdea-hf-field bdea-hf-field-inline">
                                <label class="bdea-hf-checkbox-label">
                                    <input type="checkbox" name="disable_theme" value="yes" />
                                    <span class="bdea-hf-checkbox-ui"></span>
                                    <span class="bdea-hf-checkbox-content">
                                        <strong>Disable Theme Header</strong>
                                        <span class="bdea-hf-field-desc">Hide theme header with CSS</span>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <p style="margin:12px 0 8px;font-weight:600;color:#1e1e1e;">Device Visibility</p>
                        <div class="bdea-hf-device-toggles">
                            <label class="bdea-hf-device-toggle">
                                <input type="checkbox" name="device_desktop" value="yes" checked />
                                <span class="bdea-hf-device-btn">
                                    <span class="dashicons dashicons-desktop"></span>
                                    <span class="bdea-hf-device-label">Desktop</span>
                                </span>
                            </label>
                            <label class="bdea-hf-device-toggle">
                                <input type="checkbox" name="device_tablet" value="yes" checked />
                                <span class="bdea-hf-device-btn">
                                    <span class="dashicons dashicons-tablet"></span>
                                    <span class="bdea-hf-device-label">Tablet</span>
                                </span>
                            </label>
                            <label class="bdea-hf-device-toggle">
                                <input type="checkbox" name="device_mobile" value="yes" checked />
                                <span class="bdea-hf-device-btn">
                                    <span class="dashicons dashicons-smartphone"></span>
                                    <span class="bdea-hf-device-label">Mobile</span>
                                </span>
                            </label>
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
        $type        = isset( $_POST['template_type'] ) ? sanitize_key( $_POST['template_type'] ) : ( isset( $_POST['type'] ) ? sanitize_key( $_POST['type'] ) : '' );
        $condition   = isset( $_POST['condition'] ) ? sanitize_text_field( $_POST['condition'] ) : '';
        $woo         = isset( $_POST['woo'] ) ? sanitize_text_field( $_POST['woo'] ) : '';
        $include_pgs = isset( $_POST['include_pages'] ) ? array_map( 'intval', $_POST['include_pages'] ) : [];
        $exclude_pgs = isset( $_POST['exclude_pages'] ) ? array_map( 'intval', $_POST['exclude_pages'] ) : [];
        $disable_theme = ! empty( $_POST['disable_theme'] );

        if ( empty( $name ) || ! in_array( $type, [ 'header', 'footer', 'single', 'archive', '404', 'announcement', 'bottom_bar', 'loop' ], true ) ) {
            wp_send_json_error( [ 'message' => 'Name and type are required.' ] );
        }

        // Loop templates use elementor_library post type (like Elementor Pro)
        if ( 'loop' === $type ) {
            $post_id = wp_insert_post( [
                'post_title'  => $name,
                'post_type'   => 'elementor_library',
                'post_status' => 'publish',
            ] );

            if ( is_wp_error( $post_id ) ) {
                wp_send_json_error( [ 'message' => 'Failed to create template.' ] );
            }

            $container_id = substr( md5( 'bdea-loop-' . $post_id . '-1' ), 0, 7 );
            update_post_meta( $post_id, '_elementor_template_type', 'loop-item' );
            update_post_meta( $post_id, '_elementor_edit_mode', 'builder' );
            update_post_meta( $post_id, '_elementor_version', \Elementor\Plugin::$instance->version );
            update_post_meta( $post_id, '_elementor_data', wp_json_encode( [
                [
                    'id'       => $container_id,
                    'elType'   => 'container',
                    'settings' => [],
                    'elements' => [],
                    'isInner'  => false,
                ],
            ] ) );

            $this->cache->flush_all();

            wp_send_json_success( [
                'message' => 'Template created.',
                'edit_url' => add_query_arg(
                    [ 'action' => 'elementor', 'post' => $post_id ],
                    admin_url( 'post.php' )
                ),
            ] );
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

        if ( $disable_theme ) {
            update_post_meta( $post_id, '_bdea_hf_disable_theme', 'yes' );
        }

        $this->cache->flush_all();

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
        update_post_meta( $post_id, '_bdea_hf_disable_theme', ! empty( $_POST['disable_theme'] ) ? 'yes' : '' );

        $device_vis = [
            'desktop' => ! empty( $_POST['device_desktop'] ) ? 'yes' : '',
            'tablet'  => ! empty( $_POST['device_tablet'] ) ? 'yes' : '',
            'mobile'  => ! empty( $_POST['device_mobile'] ) ? 'yes' : '',
        ];
        update_post_meta( $post_id, '_bdea_hf_device_visibility', $device_vis );

        $this->cache->flush_all();

        wp_send_json_success( [ 'message' => 'Conditions saved.' ] );
    }

    private function update_schedule_meta( $post_id, $key, $value ) {
        if ( empty( $value ) ) {
            delete_post_meta( $post_id, $key );
            return;
        }

        $timestamp = strtotime( sanitize_text_field( $value ) );

        if ( false !== $timestamp ) {
            update_post_meta( $post_id, $key, $timestamp );
        }
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

        if ( ! $post || ! in_array( $post->post_type, [ 'bdea_header_footer', 'elementor_library' ], true ) ) {
            wp_send_json_error( [ 'message' => 'Template not found.' ] );
        }

        $new_id = wp_insert_post( [
            'post_title'  => $post->post_title . ' (Copy)',
            'post_type'   => $post->post_type,
            'post_status' => 'publish',
        ] );

        if ( is_wp_error( $new_id ) ) {
            wp_send_json_error( [ 'message' => 'Failed to duplicate template.' ] );
        }

        // Copy all post meta
        $meta = get_post_meta( $post_id );
        if ( is_array( $meta ) ) {
            foreach ( $meta as $key => $values ) {
                if ( '' !== $values[0] ) {
                    update_post_meta( $new_id, $key, maybe_unserialize( $values[0] ) );
                }
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

        if ( empty( $post_ids ) || ! in_array( $action, [ 'trash', 'activate', 'deactivate', 'restore', 'delete' ], true ) ) {
            wp_send_json_error( [ 'message' => 'Invalid request.' ] );
        }

        foreach ( $post_ids as $id ) {
            if ( 'trash' === $action ) {
                wp_trash_post( $id );
            } elseif ( 'activate' === $action ) {
                wp_publish_post( $id );
            } elseif ( 'deactivate' === $action ) {
                wp_update_post( [ 'ID' => $id, 'post_status' => 'draft' ] );
            } elseif ( 'restore' === $action ) {
                wp_untrash_post( $id );
            } elseif ( 'delete' === $action ) {
                wp_delete_post( $id, true );
            }
        }

        $this->cache->flush_all();

        wp_send_json_success( [ 'message' => count( $post_ids ) . ' template(s) updated.' ] );
    }

    public function handle_restore() {
        $id = isset( $_GET['id'] ) ? absint( $_GET['id'] ) : 0;

        if ( ! $id || ! current_user_can( 'manage_options' ) || ! wp_verify_nonce( $_GET['_wpnonce'] ?? '', 'bdea_hf_restore_' . $id ) ) {
            wp_die( -1 );
        }

        wp_untrash_post( $id );
        $this->cache->flush_all();

        wp_safe_redirect( wp_get_referer() ? wp_get_referer() : admin_url( 'admin.php?page=bdea-hf-builder' ) );
        exit;
    }

    public function ajax_get_posts() {
        check_ajax_referer( 'bdea_hf_conditions', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( -1 );
        }

        $post_type = isset( $_POST['post_type'] ) ? sanitize_key( $_POST['post_type'] ) : '';
        $search    = isset( $_POST['search'] ) ? sanitize_text_field( $_POST['search'] ) : '';

        if ( ! post_type_exists( $post_type ) ) {
            wp_send_json_error( [ 'message' => 'Invalid post type.' ] );
        }

        $args = [
            'post_type'              => $post_type,
            'post_status'            => 'publish',
            'posts_per_page'         => 100,
            'orderby'                => 'title',
            'order'                  => 'ASC',
            'no_found_rows'          => true,
            'fields'                 => 'ids',
            'update_post_term_cache' => false,
            'update_post_meta_cache' => false,
        ];

        if ( $search ) {
            $args['s'] = $search;
        }

        $items = [];

        foreach ( get_posts( $args ) as $id ) {
            $items[] = [
                'id'    => (int) $id,
                'title' => get_the_title( $id ),
            ];
        }

        wp_send_json_success( [ 'items' => $items ] );
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

        if ( ! $post || ! in_array( $post->post_type, [ 'bdea_header_footer', 'elementor_library' ], true ) ) {
            wp_send_json_error( [ 'message' => 'Template not found.' ] );
        }

        $is_loop = 'elementor_library' === $post->post_type;
        $meta_keys = $is_loop ? [] : [
            '_bdea_hf_template_type',
            '_bdea_hf_conditions',
            '_bdea_hf_priority',
            '_bdea_hf_sticky',
            '_bdea_hf_scroll_animation',
            '_bdea_hf_disable_theme',
            '_bdea_hf_device_visibility',
            '_bdea_hf_transparent',
            '_bdea_hf_sticky_shrink',
            '_bdea_hf_sticky_hide_scroll',
            '_bdea_hf_logo_switcher',
            '_bdea_hf_sticky_offset',
            '_bdea_hf_dismissible',
            '_bdea_hf_cookie_days',
            '_bdea_hf_schedule_enabled',
            '_bdea_hf_schedule_start',
            '_bdea_hf_schedule_end',
        ];

        $meta = [];

        foreach ( $meta_keys as $key ) {
            $meta[ $key ] = get_post_meta( $post_id, $key, true );
        }

        $elementor_data = get_post_meta( $post_id, '_elementor_data', true );
        $elementor_css  = get_post_meta( $post_id, '_elementor_css', true );
        $template_type  = get_post_meta( $post_id, '_elementor_template_type', true );

        $export = [
            'version'                 => BDEA_VERSION,
            'title'                   => $post->post_title,
            'type'                    => $post->post_type,
            'meta'                    => $meta,
            'elementor_data'          => $elementor_data,
            'elementor_css'           => $elementor_css,
            'elementor_page_settings' => get_post_meta( $post_id, '_elementor_page_settings', true ),
            'elementor_template_type' => $template_type ?: ( $is_loop ? 'loop-item' : 'bdea-hf-document' ),
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

        // Determine post type from export data
        $post_type = isset( $data['type'] ) && 'elementor_library' === $data['type']
            ? 'elementor_library'
            : 'bdea_header_footer';

        $post_id = wp_insert_post( [
            'post_title'  => sanitize_text_field( $data['title'] ),
            'post_type'   => $post_type,
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

        if ( isset( $data['elementor_page_settings'] ) && is_array( $data['elementor_page_settings'] ) ) {
            update_post_meta( $post_id, '_elementor_page_settings', wp_slash( $data['elementor_page_settings'] ) );
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

    private function get_templates( $type = '', $statuses = null ) {
        $statuses = $statuses ?: [ 'publish', 'draft' ];

        // Loop templates use elementor_library post type (like Elementor Pro)
        if ( 'loop' === $type ) {
            $args = [
                'post_type'      => 'elementor_library',
                'post_status'    => $statuses,
                'posts_per_page' => -1,
                'orderby'        => 'title date',
                'order'          => 'ASC',
                'meta_query'     => [
                    [
                        'key'     => '_elementor_template_type',
                        'value'   => [ 'loop-item', 'loop' ],
                        'compare' => 'IN',
                    ],
                ],
            ];
            return get_posts( $args );
        }

        $args = [
            'post_type'      => 'bdea_header_footer',
            'post_status'    => $statuses,
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

    private function render_type_badge( $type ) {
        if ( 'header' === $type ) :
            ?><span class="bdea-hf-badge bdea-hf-badge-header">&#8593; Header</span><?php
        elseif ( 'footer' === $type ) :
            ?><span class="bdea-hf-badge bdea-hf-badge-footer">&#8595; Footer</span><?php
        elseif ( 'announcement' === $type ) :
            ?><span class="bdea-hf-badge bdea-hf-badge-announcement">&#9888; Announcement</span><?php
        elseif ( 'bottom_bar' === $type ) :
            ?><span class="bdea-hf-badge bdea-hf-badge-bottom-bar">&#9660; Bottom Bar</span><?php
        elseif ( 'single' === $type ) :
            ?><span class="bdea-hf-badge bdea-hf-badge-single">&#128196; Single Post</span><?php
        elseif ( 'archive' === $type ) :
            ?><span class="bdea-hf-badge bdea-hf-badge-archive">&#128230; Archive</span><?php
        elseif ( '404' === $type ) :
            ?><span class="bdea-hf-badge bdea-hf-badge-404">&#9888; 404 Page</span><?php
        elseif ( 'loop' === $type || 'loop-item' === $type ) :
            ?><span class="bdea-hf-badge bdea-hf-badge-loop">&#128260; Loop</span><?php
        else :
            ?><em>None</em><?php
        endif;
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
