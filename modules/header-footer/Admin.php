<?php
/**
 * ElementStack Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */
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
            __( 'Theme Builder', 'elementstack-elementor-addons' ),
            __( 'Theme Builder', 'elementstack-elementor-addons' ),
            'manage_options',
            'bdea-hf-builder',
            [ $this, 'render_admin_page' ]
        );
    }

    public function render_admin_page() {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Filter links are plain navigation, no nonce needed.
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
            'header'       => __( 'Header', 'elementstack-elementor-addons' ),
            'footer'       => __( 'Footer', 'elementstack-elementor-addons' ),
            'single'       => __( 'Single Post', 'elementstack-elementor-addons' ),
            'archive'      => __( 'Archive', 'elementstack-elementor-addons' ),
            '404'          => __( '404 Page', 'elementstack-elementor-addons' ),
            'announcement' => __( 'Announcement', 'elementstack-elementor-addons' ),
            'bottom_bar'   => __( 'Bottom Bar', 'elementstack-elementor-addons' ),
            'loop'         => __( 'Loop Item', 'elementstack-elementor-addons' ),
        ];

        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Filter links are plain navigation, no nonce needed.
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

        // translators: %s: Template type name (Header, Footer, etc.).
        $create_label = $type_view ? sprintf( __( 'Add New %s', 'elementstack-elementor-addons' ), $type_labels[ $type_view ] ) : __( 'Add New Template', 'elementstack-elementor-addons' );
        $create_type  = $type_view ? $type_view : 'header';
        ?>
        <div class="wrap bdea-hf-wrap">
            <h1 class="wp-heading-inline"><?php esc_html_e( 'Theme Builder', 'elementstack-elementor-addons' ); ?></h1>

            <a href="#" class="page-title-action bdea-hf-create-btn" data-type="<?php echo esc_attr( $create_type ); ?>"><?php echo esc_html( $create_label ); ?></a>
            <a href="#" class="page-title-action bdea-hf-import-btn"><?php esc_html_e( 'Import', 'elementstack-elementor-addons' ); ?></a>

            <hr class="wp-header-end">

            <nav class="nav-tab-wrapper bdea-hf-type-tabs">
                <a href="<?php echo esc_url( remove_query_arg( 'bdea_type', $page_url ) ); ?>" class="nav-tab <?php echo '' === $type_view ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'All', 'elementstack-elementor-addons' ); ?> <span class="count">(<?php echo esc_html( $all_count ); ?>)</span></a>
                <?php foreach ( $type_labels as $type_key => $type_label ) : ?>
                    <a href="<?php echo esc_url( add_query_arg( 'bdea_type', $type_key, $page_url ) ); ?>" class="nav-tab <?php echo $type_key === $type_view ? 'nav-tab-active' : ''; ?>"><?php echo esc_html( $type_label ); ?> <span class="count">(<?php echo esc_html( $type_counts[ $type_key ] ); ?>)</span></a>
                <?php endforeach; ?>
            </nav>

            <ul class="subsubsub">
                <li class="all"><a href="<?php echo esc_url( $base_url ); ?>" class="<?php echo 'all' === $status_view ? 'current' : ''; ?>"><?php esc_html_e( 'All', 'elementstack-elementor-addons' ); ?> <span class="count">(<?php echo esc_html( $all_count ); ?>)</span></a> |</li>
                <li class="published"><a href="<?php echo esc_url( add_query_arg( 'bdea_status', 'published', $base_url ) ); ?>" class="<?php echo 'published' === $status_view ? 'current' : ''; ?>"><?php esc_html_e( 'Published', 'elementstack-elementor-addons' ); ?> <span class="count">(<?php echo esc_html( $pub_count ); ?>)</span></a> |</li>
                <li class="trash"><a href="<?php echo esc_url( add_query_arg( 'bdea_status', 'trash', $base_url ) ); ?>" class="<?php echo 'trash' === $status_view ? 'current' : ''; ?>"><?php esc_html_e( 'Trash', 'elementstack-elementor-addons' ); ?> <span class="count">(<?php echo esc_html( $trash_count ); ?>)</span></a></li>
            </ul>

            <p class="bdea-hf-list-sub"><?php
                // translators: %s: Number of templates.
                echo esc_html( sprintf( _n( '%s template found', '%s templates found', count( $templates ), 'elementstack-elementor-addons' ), number_format_i18n( count( $templates ) ) ) );
            ?></p>

            <div class="bdea-hf-bulk-bar">
                <select id="bdea-hf-bulk-action">
                    <option value=""><?php esc_html_e( 'Bulk Actions', 'elementstack-elementor-addons' ); ?></option>
                    <?php if ( 'trash' === $status_view ) : ?>
                        <option value="restore"><?php esc_html_e( 'Restore', 'elementstack-elementor-addons' ); ?></option>
                        <option value="delete"><?php esc_html_e( 'Delete Permanently', 'elementstack-elementor-addons' ); ?></option>
                    <?php else : ?>
                        <option value="trash"><?php esc_html_e( 'Trash', 'elementstack-elementor-addons' ); ?></option>
                        <option value="activate"><?php esc_html_e( 'Activate', 'elementstack-elementor-addons' ); ?></option>
                        <option value="deactivate"><?php esc_html_e( 'Deactivate', 'elementstack-elementor-addons' ); ?></option>
                    <?php endif; ?>
                </select>
                <button type="button" class="button" id="bdea-hf-bulk-apply" data-nonce="<?php echo esc_attr( wp_create_nonce( 'bdea_hf_bulk' ) ); ?>"><?php esc_html_e( 'Apply', 'elementstack-elementor-addons' ); ?></button>
            </div>

            <table class="wp-list-table widefat fixed striped table-view-list bdea-hf-table">
                <thead>
                    <tr>
                        <th id="cb" scope="col" class="manage-column check-column"><input type="checkbox" id="bdea-hf-select-all" /></th>
                        <th scope="col" class="manage-column column-title column-primary"><?php esc_html_e( 'Title', 'elementstack-elementor-addons' ); ?></th>
                        <th scope="col" class="manage-column"><?php esc_html_e( 'Type', 'elementstack-elementor-addons' ); ?></th>
                        <th scope="col" class="manage-column"><?php esc_html_e( 'Status', 'elementstack-elementor-addons' ); ?></th>
                        <th scope="col" class="manage-column"><?php esc_html_e( 'Display Conditions', 'elementstack-elementor-addons' ); ?></th>
                        <th scope="col" class="manage-column"><?php esc_html_e( 'Date', 'elementstack-elementor-addons' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ( empty( $templates ) ) : ?>
                        <tr><td colspan="6"><?php
                            // translators: %1$s: Template type name, %2$s: Create template button label.
                            echo esc_html( sprintf( __( 'No %1$stemplates found. Click "%2$s" to create one.', 'elementstack-elementor-addons' ), $type_view ? strtolower( $type_labels[ $type_view ] ) . ' ' : '', $create_label ) );
                        ?></td></tr>
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
                                            <span class="restore"><a href="<?php echo esc_url( $restore_url ); ?>"><?php esc_html_e( 'Restore', 'elementstack-elementor-addons' ); ?></a> | </span>
                                            <span class="trash"><a href="<?php echo esc_url( get_delete_post_link( $post_id ) ); ?>" class="bdea-hf-trash"><?php esc_html_e( 'Delete Permanently', 'elementstack-elementor-addons' ); ?></a></span>
                                        </div>
                                    <?php else : ?>
                                        <div class="row-actions">
                                            <span class="edit"><a href="<?php echo esc_url( $edit_url ); ?>"><?php esc_html_e( 'Edit with Elementor', 'elementstack-elementor-addons' ); ?></a> | </span>
                                            <span class="bdea-hf-dup"><a href="#" class="bdea-hf-duplicate-btn" data-id="<?php echo esc_attr( $post_id ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'bdea_hf_duplicate' ) ); ?>"><?php esc_html_e( 'Duplicate', 'elementstack-elementor-addons' ); ?></a> | </span>
                                            <span class="bdea-hf-export"><a href="#" class="bdea-hf-export-btn" data-id="<?php echo esc_attr( $post_id ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'bdea_hf_export' ) ); ?>"><?php esc_html_e( 'Export', 'elementstack-elementor-addons' ); ?></a> | </span>
                                            <span class="trash"><a href="<?php echo esc_url( get_delete_post_link( $post_id ) ); ?>" class="bdea-hf-trash"><?php esc_html_e( 'Trash', 'elementstack-elementor-addons' ); ?></a></span>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td><?php $this->render_type_badge( $type ); ?></td>
                                <td>
                                    <?php if ( $is_trash ) : ?>
                                        <span class="bdea-hf-badge bdea-hf-badge-trash"><?php esc_html_e( 'Trashed', 'elementstack-elementor-addons' ); ?></span>
                                    <?php elseif ( $is_active ) : ?>
                                        <span class="bdea-hf-badge bdea-hf-badge-active"><?php esc_html_e( 'Active', 'elementstack-elementor-addons' ); ?></span>
                                    <?php else : ?>
                                        <span class="bdea-hf-badge bdea-hf-badge-inactive"><?php esc_html_e( 'Inactive', 'elementstack-elementor-addons' ); ?></span>
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
                                            <span class="bdea-hf-cond-empty"><?php esc_html_e( 'All Website', 'elementstack-elementor-addons' ); ?></span>
                                        <?php endif; ?>
                                        <?php if ( ! $is_trash ) : ?>
                                            <button type="button" class="bdea-hf-edit-cond" data-id="<?php echo esc_attr( $post_id ); ?>">
                                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M10 1.5l2.5 2.5L4.5 12H2v-2.5L10 1.5z"/></svg>
                                                <?php esc_html_e( 'Edit', 'elementstack-elementor-addons' ); ?>
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
                        <h2><?php esc_html_e( 'Import Template', 'elementstack-elementor-addons' ); ?></h2>
                        <p class="bdea-hf-modal-subtitle"><?php esc_html_e( 'Upload a previously exported .json file', 'elementstack-elementor-addons' ); ?></p>
                    </div>
                    <button type="button" class="bdea-hf-modal-close">&times;</button>
                </div>
                <div class="bdea-hf-modal-body">
                    <form id="bdea-hf-import-form">
                        <div class="bdea-hf-field">
                            <label><span class="dashicons dashicons-upload"></span> <?php esc_html_e( 'Select JSON File', 'elementstack-elementor-addons' ); ?></label>
                            <div class="bdea-hf-dropzone" id="bdea-hf-dropzone">
                                <span class="dashicons dashicons-cloud-upload"></span>
                                <p><strong><?php esc_html_e( 'Drop your .json file here', 'elementstack-elementor-addons' ); ?></strong> <?php esc_html_e( 'or click to browse', 'elementstack-elementor-addons' ); ?></p>
                                <span class="bdea-hf-dropzone-help"><?php esc_html_e( 'File exported from the Export button', 'elementstack-elementor-addons' ); ?></span>
                                <span class="bdea-hf-dropzone-file"></span>
                                <input type="file" name="import_file" accept=".json" required />
                            </div>
                        </div>
                    </form>
                </div>
                <div class="bdea-hf-modal-footer">
                    <button type="button" class="bdea-hf-btn-cancel" data-close-modal><?php esc_html_e( 'Cancel', 'elementstack-elementor-addons' ); ?></button>
                    <button type="button" class="bdea-hf-btn-primary bdea-hf-import-submit" data-nonce="<?php echo esc_attr( wp_create_nonce( 'bdea_hf_import' ) ); ?>">
                        <?php esc_html_e( 'Import Template', 'elementstack-elementor-addons' ); ?>
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
                        <h2><?php esc_html_e( 'Create', 'elementstack-elementor-addons' ); ?> <span class="bdea-hf-modal-type-label"><?php esc_html_e( 'Header', 'elementstack-elementor-addons' ); ?></span> <?php esc_html_e( 'Template', 'elementstack-elementor-addons' ); ?></h2>
                        <p class="bdea-hf-modal-subtitle"><?php esc_html_e( 'Set up a new template with display rules', 'elementstack-elementor-addons' ); ?></p>
                    </div>
                    <button type="button" class="bdea-hf-modal-close">&times;</button>
                </div>
                <div class="bdea-hf-modal-body">
                    <form id="bdea-hf-create-form">
                        <input type="hidden" name="type" value="" />

                        <div class="bdea-hf-field">
                            <label><span class="dashicons dashicons-edit"></span> <?php esc_html_e( 'Template Name', 'elementstack-elementor-addons' ); ?></label>
                            <input type="text" name="name" placeholder="<?php esc_attr_e( 'e.g. Main Header, Footer v2', 'elementstack-elementor-addons' ); ?>" required />
                        </div>

                        <div class="bdea-hf-field">
                            <label><span class="dashicons dashicons-layout"></span> <?php esc_html_e( 'Template Type', 'elementstack-elementor-addons' ); ?></label>
                            <select name="template_type" id="bdea-hf-create-type">
                                <option value="header"><?php esc_html_e( 'Header', 'elementstack-elementor-addons' ); ?></option>
                                <option value="footer"><?php esc_html_e( 'Footer', 'elementstack-elementor-addons' ); ?></option>
                                <option value="single"><?php esc_html_e( 'Single Post Template', 'elementstack-elementor-addons' ); ?></option>
                                <option value="archive"><?php esc_html_e( 'Archive (Category / Tag / Loop)', 'elementstack-elementor-addons' ); ?></option>
                                <option value="404"><?php esc_html_e( '404 Page', 'elementstack-elementor-addons' ); ?></option>
                                <option value="announcement"><?php esc_html_e( 'Announcement Bar', 'elementstack-elementor-addons' ); ?></option>
                                <option value="bottom_bar"><?php esc_html_e( 'Bottom Bar', 'elementstack-elementor-addons' ); ?></option>
                                <option value="loop"><?php esc_html_e( 'Loop Item Template', 'elementstack-elementor-addons' ); ?></option>
                            </select>
                        </div>

                        <div class="bdea-hf-field-row">
                            <div class="bdea-hf-field">
                                <label><span class="dashicons dashicons-layout"></span> <?php esc_html_e( 'Display Condition', 'elementstack-elementor-addons' ); ?></label>
                                                                <select name="condition">
                                    <option value="entire_site"><?php esc_html_e( 'Entire Website', 'elementstack-elementor-addons' ); ?></option>
                                    <option value="front_page"><?php esc_html_e( 'Front Page', 'elementstack-elementor-addons' ); ?></option>
                                    <option value="home_page"><?php esc_html_e( 'Home / Blog Page', 'elementstack-elementor-addons' ); ?></option>
                                    <option value="custom_url"><?php esc_html_e( 'Custom URL', 'elementstack-elementor-addons' ); ?></option>
                                    <option value="singular"><?php esc_html_e( 'All Singular', 'elementstack-elementor-addons' ); ?></option>
                                    <option value="singular:post_type:post"><?php esc_html_e( 'All Blog Posts', 'elementstack-elementor-addons' ); ?></option>
                                    <option value="singular:post_type:page"><?php esc_html_e( 'All Pages', 'elementstack-elementor-addons' ); ?></option>
                                    <option value="archive"><?php esc_html_e( 'All Archives', 'elementstack-elementor-addons' ); ?></option>
                                    <?php
                                    $bdea_archive_condition_taxonomies = get_taxonomies( [ 'public' => true ], 'objects' );
                                    foreach ( $bdea_archive_condition_taxonomies as $bdea_tax ) :
                                        if ( in_array( $bdea_tax->name, [ 'elementor_library', 'bdea_header_footer', 'nav_menu', 'link_category' ], true ) ) {
                                            continue;
                                        }
                                        ?>
                                        <option value="archive:taxonomy:<?php echo esc_attr( $bdea_tax->name ); ?>"><?php
                                            // translators: %s: Taxonomy name.
                                            echo esc_html( sprintf( __( 'All %s Archives', 'elementstack-elementor-addons' ), $bdea_tax->labels->name ) );
                                        ?></option>
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
                                    <option value="search"><?php esc_html_e( 'Search Results', 'elementstack-elementor-addons' ); ?></option>
                                    <option value="404"><?php esc_html_e( '404 Page', 'elementstack-elementor-addons' ); ?></option>
                                </select>
                            </div>

                            <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                            <div class="bdea-hf-field">
                                <label><span class="dashicons dashicons-cart"></span> <?php esc_html_e( 'WooCommerce', 'elementstack-elementor-addons' ); ?></label>
                                <select name="woo">
                                    <option value=""><?php esc_html_e( 'None', 'elementstack-elementor-addons' ); ?></option>
                                    <option value="woocommerce:shop"><?php esc_html_e( 'Shop Page', 'elementstack-elementor-addons' ); ?></option>
                                    <option value="woocommerce:product"><?php esc_html_e( 'Product Page', 'elementstack-elementor-addons' ); ?></option>
                                    <option value="woocommerce:cart"><?php esc_html_e( 'Cart Page', 'elementstack-elementor-addons' ); ?></option>
                                    <option value="woocommerce:checkout"><?php esc_html_e( 'Checkout Page', 'elementstack-elementor-addons' ); ?></option>
                                    <option value="woocommerce:account"><?php esc_html_e( 'My Account Page', 'elementstack-elementor-addons' ); ?></option>
                                    <option value="woocommerce:product_archive"><?php esc_html_e( 'Product Archive', 'elementstack-elementor-addons' ); ?></option>
                                </select>
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="bdea-hf-field-row">
                            <div class="bdea-hf-field">
                                <label><span class="dashicons dashicons-plus"></span> <?php esc_html_e( 'Include Pages', 'elementstack-elementor-addons' ); ?></label>
                                <select name="include_pages[]" class="bdea-hf-page-select" multiple>
                                    <?php foreach ( $pages as $page ) : ?>
                                        <option value="<?php echo esc_attr( $page->ID ); ?>"><?php echo esc_html( $page->post_title ); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="bdea-hf-select-hint"><?php esc_html_e( 'Hold Ctrl to select multiple', 'elementstack-elementor-addons' ); ?></span>
                                <button type="button" class="bdea-hf-clear-select" data-target="include_pages"><?php esc_html_e( 'Clear', 'elementstack-elementor-addons' ); ?></button>
                            </div>

                            <div class="bdea-hf-field">
                                <label><span class="dashicons dashicons-dismiss"></span> <?php esc_html_e( 'Exclude Pages', 'elementstack-elementor-addons' ); ?></label>
                                <select name="exclude_pages[]" class="bdea-hf-page-select" multiple>
                                    <?php foreach ( $pages as $page ) : ?>
                                        <option value="<?php echo esc_attr( $page->ID ); ?>"><?php echo esc_html( $page->post_title ); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="bdea-hf-select-hint"><?php esc_html_e( 'Hold Ctrl to select multiple', 'elementstack-elementor-addons' ); ?></span>
                                <button type="button" class="bdea-hf-clear-select" data-target="exclude_pages"><?php esc_html_e( 'Clear', 'elementstack-elementor-addons' ); ?></button>
                            </div>
                        </div>

                        <div class="bdea-hf-field-row bdea-hf-field-checkboxes">
                            <div class="bdea-hf-field bdea-hf-field-inline">
                                <label class="bdea-hf-checkbox-label">
                                    <input type="checkbox" name="disable_theme" value="yes" />
                                    <span class="bdea-hf-checkbox-ui"></span>
                                    <span class="bdea-hf-checkbox-content">
                                        <strong><?php esc_html_e( 'Disable Theme Header', 'elementstack-elementor-addons' ); ?></strong>
                                        <span class="bdea-hf-field-desc"><?php esc_html_e( 'Replace theme header with Elementor', 'elementstack-elementor-addons' ); ?></span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="bdea-hf-modal-footer">
                    <button type="button" class="bdea-hf-btn-cancel" data-close-modal><?php esc_html_e( 'Cancel', 'elementstack-elementor-addons' ); ?></button>
                    <button type="button" class="bdea-hf-btn-primary bdea-hf-create-submit" data-nonce="<?php echo esc_attr( wp_create_nonce( 'bdea_hf_create' ) ); ?>">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M8 3v10M3 8h10"/></svg>
                        <?php esc_html_e( 'Create & Edit', 'elementstack-elementor-addons' ); ?>
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
                        <h2><?php esc_html_e( 'Edit Display Conditions', 'elementstack-elementor-addons' ); ?></h2>
                        <p class="bdea-hf-modal-subtitle"><?php esc_html_e( 'Control where this template appears', 'elementstack-elementor-addons' ); ?></p>
                    </div>
                    <button type="button" class="bdea-hf-modal-close">&times;</button>
                </div>
                <div class="bdea-hf-modal-body">
                    <form id="bdea-hf-conditions-form">
                        <input type="hidden" name="template_id" value="" />
                        <div class="bdea-hf-cond-search">
                            <span class="dashicons dashicons-search"></span>
                            <input type="text" id="bdea-hf-cond-search" placeholder="<?php esc_attr_e( 'Search conditions...', 'elementstack-elementor-addons' ); ?>" />
                            <button type="button" class="bdea-hf-cond-search-clear" title="<?php esc_attr_e( 'Clear', 'elementstack-elementor-addons' ); ?>">&times;</button>
                        </div>
                        <div class="bdea-hf-conditions-list"></div>
                        <p>
                            <button type="button" class="button bdea-hf-add-condition-row">+ <?php esc_html_e( 'Add Condition', 'elementstack-elementor-addons' ); ?></button>
                        </p>
                        <hr style="margin:16px 0;border:none;border-top:1px solid #e2e4e7;">
                        <p style="margin:0 0 8px;font-weight:600;color:#1e1e1e;"><?php esc_html_e( 'Template Settings', 'elementstack-elementor-addons' ); ?></p>
                        <p class="bdea-hf-field-desc" style="margin:0 0 12px;">
                            <?php esc_html_e( 'Sticky, transparent, schedule and other behavior settings are managed inside the Elementor editor (Settings panel).', 'elementstack-elementor-addons' ); ?>
                        </p>
                        <div class="bdea-hf-field-row bdea-hf-field-checkboxes bdea-hf-conditions-settings">
                            <div class="bdea-hf-field bdea-hf-field-inline">
                                <label class="bdea-hf-checkbox-label">
                                    <input type="checkbox" name="disable_theme" value="yes" />
                                    <span class="bdea-hf-checkbox-ui"></span>
                                    <span class="bdea-hf-checkbox-content">
                                        <strong><?php esc_html_e( 'Disable Theme Header', 'elementstack-elementor-addons' ); ?></strong>
                                        <span class="bdea-hf-field-desc"><?php esc_html_e( 'Hide theme header with CSS', 'elementstack-elementor-addons' ); ?></span>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <p style="margin:12px 0 8px;font-weight:600;color:#1e1e1e;"><?php esc_html_e( 'Device Visibility', 'elementstack-elementor-addons' ); ?></p>
                        <div class="bdea-hf-device-toggles">
                            <label class="bdea-hf-device-toggle">
                                <input type="checkbox" name="device_desktop" value="yes" checked />
                                <span class="bdea-hf-device-btn">
                                    <span class="dashicons dashicons-desktop"></span>
                                    <span class="bdea-hf-device-label"><?php esc_html_e( 'Desktop', 'elementstack-elementor-addons' ); ?></span>
                                </span>
                            </label>
                            <label class="bdea-hf-device-toggle">
                                <input type="checkbox" name="device_tablet" value="yes" checked />
                                <span class="bdea-hf-device-btn">
                                    <span class="dashicons dashicons-tablet"></span>
                                    <span class="bdea-hf-device-label"><?php esc_html_e( 'Tablet', 'elementstack-elementor-addons' ); ?></span>
                                </span>
                            </label>
                            <label class="bdea-hf-device-toggle">
                                <input type="checkbox" name="device_mobile" value="yes" checked />
                                <span class="bdea-hf-device-btn">
                                    <span class="dashicons dashicons-smartphone"></span>
                                    <span class="bdea-hf-device-label"><?php esc_html_e( 'Mobile', 'elementstack-elementor-addons' ); ?></span>
                                </span>
                            </label>
                        </div>
                    </form>
                </div>
                <div class="bdea-hf-modal-footer">
                    <button type="button" class="bdea-hf-btn-cancel" data-close-modal><?php esc_html_e( 'Cancel', 'elementstack-elementor-addons' ); ?></button>
                    <button type="button" class="bdea-hf-btn-primary bdea-hf-conditions-save" data-nonce="<?php echo esc_attr( wp_create_nonce( 'bdea_hf_conditions' ) ); ?>">
                        <?php esc_html_e( 'Save Conditions', 'elementstack-elementor-addons' ); ?>
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
                'include' => __( 'Include', 'elementstack-elementor-addons' ),
                'exclude' => __( 'Exclude', 'elementstack-elementor-addons' ), // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude -- UI string, not a query argument.
            ],
        ] );
    }

    public function ajax_create_template() {
        check_ajax_referer( 'bdea_hf_create', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( -1 );
        }

        $name        = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
        $type        = isset( $_POST['template_type'] ) ? sanitize_key( $_POST['template_type'] ) : ( isset( $_POST['type'] ) ? sanitize_key( $_POST['type'] ) : '' );
        $condition   = isset( $_POST['condition'] ) ? sanitize_text_field( wp_unslash( $_POST['condition'] ) ) : '';
        $woo         = isset( $_POST['woo'] ) ? sanitize_text_field( wp_unslash( $_POST['woo'] ) ) : '';
        $include_pgs = isset( $_POST['include_pages'] ) ? array_map( 'intval', (array) wp_unslash( $_POST['include_pages'] ) ) : [];
        $exclude_pgs = isset( $_POST['exclude_pages'] ) ? array_map( 'intval', (array) wp_unslash( $_POST['exclude_pages'] ) ) : [];
        $disable_theme = ! empty( $_POST['disable_theme'] );

        if ( empty( $name ) || ! in_array( $type, [ 'header', 'footer', 'single', 'archive', '404', 'announcement', 'bottom_bar', 'loop' ], true ) ) {
            wp_send_json_error( [ 'message' => __( 'Name and type are required.', 'elementstack-elementor-addons' ) ] );
        }

        // Loop templates use elementor_library post type (like Elementor Pro)
        if ( 'loop' === $type ) {
            $post_id = wp_insert_post( [
                'post_title'  => $name,
                'post_type'   => 'elementor_library',
                'post_status' => 'publish',
            ] );

if ( is_wp_error( $post_id ) ) {
            wp_send_json_error( [ 'message' => __( 'Failed to create template.', 'elementstack-elementor-addons' ) ] );
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
                'message' => __( 'Template created.', 'elementstack-elementor-addons' ),
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
            wp_send_json_error( [ 'message' => __( 'Failed to create template.', 'elementstack-elementor-addons' ) ] );
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
            wp_send_json_error( [ 'message' => __( 'Invalid post ID.', 'elementstack-elementor-addons' ) ] );
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
        $conditions = isset( $_POST['conditions'] ) ? (array) wp_unslash( $_POST['conditions'] ) : []; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Each value sanitized in the loop below.

        if ( ! $post_id ) {
            wp_send_json_error( [ 'message' => __( 'Invalid post ID.', 'elementstack-elementor-addons' ) ] );
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
        $disable_theme = isset( $_POST['disable_theme'] ) ? sanitize_key( $_POST['disable_theme'] ) : '';
        update_post_meta( $post_id, '_bdea_hf_disable_theme', 'yes' === $disable_theme ? 'yes' : '' );

        $device_vis = [
            'desktop' => isset( $_POST['device_desktop'] ) ? sanitize_key( $_POST['device_desktop'] ) : '',
            'tablet'  => isset( $_POST['device_tablet'] ) ? sanitize_key( $_POST['device_tablet'] ) : '',
            'mobile'  => isset( $_POST['device_mobile'] ) ? sanitize_key( $_POST['device_mobile'] ) : '',
        ];
        $device_vis = array_map( static function ( $value ) {
            return 'yes' === $value ? 'yes' : '';
        }, $device_vis );
        update_post_meta( $post_id, '_bdea_hf_device_visibility', $device_vis );

        $this->cache->flush_all();

        wp_send_json_success( [ 'message' => __( 'Conditions saved.', 'elementstack-elementor-addons' ) ] );
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
            wp_send_json_error( [ 'message' => __( 'Invalid post ID.', 'elementstack-elementor-addons' ) ] );
        }

        $post = get_post( $post_id );

        if ( ! $post || ! in_array( $post->post_type, [ 'bdea_header_footer', 'elementor_library' ], true ) ) {
            wp_send_json_error( [ 'message' => __( 'Template not found.', 'elementstack-elementor-addons' ) ] );
        }

        $new_id = wp_insert_post( [
            'post_title'  => $post->post_title . __( ' (Copy)', 'elementstack-elementor-addons' ),
            'post_type'   => $post->post_type,
            'post_status' => 'publish',
        ] );

        if ( is_wp_error( $new_id ) ) {
            wp_send_json_error( [ 'message' => __( 'Failed to duplicate template.', 'elementstack-elementor-addons' ) ] );
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
            wp_send_json_error( [ 'message' => __( 'Invalid request.', 'elementstack-elementor-addons' ) ] );
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

        // translators: %s: Number of templates.
        wp_send_json_success( [ 'message' => sprintf( _n( '%s template updated.', '%s templates updated.', count( $post_ids ), 'elementstack-elementor-addons' ), number_format_i18n( count( $post_ids ) ) ) ] );
    }

    public function handle_restore() {
        $id = isset( $_GET['id'] ) ? absint( $_GET['id'] ) : 0;

        if ( ! $id || ! current_user_can( 'manage_options' ) ) {
            wp_die( -1 );
        }

        check_admin_referer( 'bdea_hf_restore_' . $id );

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
        $search    = isset( $_POST['search'] ) ? sanitize_text_field( wp_unslash( $_POST['search'] ) ) : '';

        if ( ! post_type_exists( $post_type ) ) {
            wp_send_json_error( [ 'message' => __( 'Invalid post type.', 'elementstack-elementor-addons' ) ] );
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
            wp_send_json_error( [ 'message' => __( 'Invalid post ID.', 'elementstack-elementor-addons' ) ] );
        }

        $post = get_post( $post_id );

        if ( ! $post || ! in_array( $post->post_type, [ 'bdea_header_footer', 'elementor_library' ], true ) ) {
            wp_send_json_error( [ 'message' => __( 'Template not found.', 'elementstack-elementor-addons' ) ] );
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

        if ( ! isset( $_FILES['import_file'], $_FILES['import_file']['error'] ) || UPLOAD_ERR_OK !== $_FILES['import_file']['error'] ) {
            wp_send_json_error( [ 'message' => __( 'File upload failed.', 'elementstack-elementor-addons' ) ] );
        }

        $file_name = isset( $_FILES['import_file']['name'] ) ? sanitize_file_name( wp_unslash( $_FILES['import_file']['name'] ) ) : '';

        if ( 'json' !== strtolower( pathinfo( $file_name, PATHINFO_EXTENSION ) ) ) {
            wp_send_json_error( [ 'message' => __( 'Only .json template files can be imported.', 'elementstack-elementor-addons' ) ] );
        }

        $file_size = isset( $_FILES['import_file']['size'] ) ? absint( $_FILES['import_file']['size'] ) : 0;

        if ( $file_size > 2 * MB_IN_BYTES ) {
            wp_send_json_error( [ 'message' => __( 'The import file is too large. Maximum size is 2 MB.', 'elementstack-elementor-addons' ) ] );
        }

        $tmp_name = isset( $_FILES['import_file']['tmp_name'] ) ? $_FILES['import_file']['tmp_name'] : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Server-managed temp path, not user input.

        if ( ! $tmp_name || ! is_uploaded_file( $tmp_name ) ) {
            wp_send_json_error( [ 'message' => __( 'File upload failed.', 'elementstack-elementor-addons' ) ] );
        }

        $content = file_get_contents( $tmp_name ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Server-managed temp path, not user input.
        $data    = json_decode( $content, true );

        if ( ! $data || empty( $data['title'] ) ) {
            wp_send_json_error( [ 'message' => __( 'Invalid or corrupt import file.', 'elementstack-elementor-addons' ) ] );
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
            wp_send_json_error( [ 'message' => __( 'Failed to create template.', 'elementstack-elementor-addons' ) ] );
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

        $order = isset( $_POST['order'] ) ? (array) wp_unslash( $_POST['order'] ) : []; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- IDs validated with intval() in the loop below.

        if ( empty( $order ) ) {
            wp_send_json_error( [ 'message' => __( 'No order data.', 'elementstack-elementor-addons' ) ] );
        }

        foreach ( $order as $index => $post_id ) {
            wp_update_post( [
                'ID'         => intval( $post_id ),
                'menu_order' => intval( $index ),
            ] );
        }

        $this->cache->flush_all();

        wp_send_json_success( [ 'message' => __( 'Order saved.', 'elementstack-elementor-addons' ) ] );
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
                'meta_query'     => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query -- Required to list only Elementor loop templates.
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
            $args['meta_key']   = '_bdea_hf_template_type'; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- Template type lookup is the primary filter.
            $args['meta_value'] = $type; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value -- Template type lookup is the primary filter.
        }
        return get_posts( $args );
    }

    private function render_type_badge( $type ) {
        if ( 'header' === $type ) :
            ?><span class="bdea-hf-badge bdea-hf-badge-header">&#8593; <?php esc_html_e( 'Header', 'elementstack-elementor-addons' ); ?></span><?php
        elseif ( 'footer' === $type ) :
            ?><span class="bdea-hf-badge bdea-hf-badge-footer">&#8595; <?php esc_html_e( 'Footer', 'elementstack-elementor-addons' ); ?></span><?php
        elseif ( 'announcement' === $type ) :
            ?><span class="bdea-hf-badge bdea-hf-badge-announcement">&#9888; <?php esc_html_e( 'Announcement', 'elementstack-elementor-addons' ); ?></span><?php
        elseif ( 'bottom_bar' === $type ) :
            ?><span class="bdea-hf-badge bdea-hf-badge-bottom-bar">&#9660; <?php esc_html_e( 'Bottom Bar', 'elementstack-elementor-addons' ); ?></span><?php
        elseif ( 'single' === $type ) :
            ?><span class="bdea-hf-badge bdea-hf-badge-single">&#128196; <?php esc_html_e( 'Single Post', 'elementstack-elementor-addons' ); ?></span><?php
        elseif ( 'archive' === $type ) :
            ?><span class="bdea-hf-badge bdea-hf-badge-archive">&#128230; <?php esc_html_e( 'Archive', 'elementstack-elementor-addons' ); ?></span><?php
        elseif ( '404' === $type ) :
            ?><span class="bdea-hf-badge bdea-hf-badge-404">&#9888; <?php esc_html_e( '404 Page', 'elementstack-elementor-addons' ); ?></span><?php
        elseif ( 'loop' === $type || 'loop-item' === $type ) :
            ?><span class="bdea-hf-badge bdea-hf-badge-loop">&#128260; <?php esc_html_e( 'Loop', 'elementstack-elementor-addons' ); ?></span><?php
        else :
            ?><em><?php esc_html_e( 'None', 'elementstack-elementor-addons' ); ?></em><?php
        endif;
    }

    private function get_conditions_label( $conditions ) {
        if ( empty( $conditions ) || ! is_array( $conditions ) ) {
            return __( 'All Website', 'elementstack-elementor-addons' );
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
                esc_html__( 'Edit with Elementor', 'elementstack-elementor-addons' )
            );
        }

        if ( current_user_can( 'delete_post', $post->ID ) ) {
            $new_actions['trash'] = sprintf(
                '<a href="%s" class="submitdelete">%s</a>',
                get_delete_post_link( $post->ID ),
                esc_html__( 'Trash', 'elementstack-elementor-addons' )
            );
        }

        return $new_actions;
    }

    public function add_bulk_actions( $actions ) {
        unset( $actions['edit'] );
        $actions['trash']       = __( 'Move to Trash', 'elementstack-elementor-addons' );
        $actions['activate']    = __( 'Activate', 'elementstack-elementor-addons' );
        $actions['deactivate']  = __( 'Deactivate', 'elementstack-elementor-addons' );
        return $actions;
    }
}
