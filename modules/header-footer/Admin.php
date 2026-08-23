<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */
namespace ElementsKey\Modules\HeaderFooter;

use ElementsKey\Framework\Cache\Cache;
use ElementsKey\Framework\Conditions\ConditionManager;

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
        add_action( 'wp_ajax_elementskey_hf_create_template', [ $this, 'ajax_create_template' ] );
        add_action( 'wp_ajax_elementskey_hf_update_conditions', [ $this, 'ajax_update_conditions' ] );
        add_action( 'wp_ajax_elementskey_hf_get_template_conditions', [ $this, 'ajax_get_template_conditions' ] );
        add_action( 'wp_ajax_elementskey_hf_duplicate_template', [ $this, 'ajax_duplicate_template' ] );
        add_action( 'wp_ajax_elementskey_hf_bulk_action', [ $this, 'ajax_bulk_action' ] );
        add_action( 'wp_ajax_elementskey_hf_reorder_templates', [ $this, 'ajax_reorder_templates' ] );
        add_action( 'wp_ajax_elementskey_hf_export_template', [ $this, 'ajax_export_template' ] );
        add_action( 'wp_ajax_elementskey_hf_import_template', [ $this, 'ajax_import_template' ] );
        add_action( 'wp_ajax_elementskey_hf_get_posts', [ $this, 'ajax_get_posts' ] );
        add_action( 'admin_post_elementskey_hf_restore', [ $this, 'handle_restore' ] );
        add_filter( 'post_row_actions', [ $this, 'add_row_actions' ], 10, 2 );
        add_filter( 'bulk_actions-edit-elementskey_header_footer', [ $this, 'add_bulk_actions' ] );
    }

    public function hide_notices() {
        $screen = get_current_screen();
        if ( $screen && false !== strpos( $screen->id, 'elementskey-hf' ) ) {
            remove_all_actions( 'admin_notices' );
            remove_all_actions( 'all_admin_notices' );
            echo '<style>.notice,.updated,.error,.update-nag{display:none!important}</style>';
        }
    }

    public function register_admin_menu() {
        add_submenu_page(
            'elementskey-settings',
            __( 'Theme Builder', 'elementskey' ),
            __( 'Theme Builder', 'elementskey' ),
            'manage_options',
            'elementskey-hf-builder',
            [ $this, 'render_admin_page' ]
        );
    }

    public function render_admin_page() {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Filter links are plain navigation, no nonce needed.
        $status_view = isset( $_GET['elementskey_status'] ) ? sanitize_key( $_GET['elementskey_status'] ) : 'all';
        if ( ! in_array( $status_view, [ 'all', 'published', 'trash' ], true ) ) {
            $status_view = 'all';
        }

        $statuses    = [
            'all'       => [ 'publish', 'draft' ],
            'published' => [ 'publish' ],
            'trash'     => [ 'trash' ],
        ];

        $type_labels = [
            'header'       => __( 'Header', 'elementskey' ),
            'footer'       => __( 'Footer', 'elementskey' ),
            'single'       => __( 'Single Post', 'elementskey' ),
            'archive'      => __( 'Archive', 'elementskey' ),
            '404'          => __( '404 Page', 'elementskey' ),
            'announcement' => __( 'Announcement', 'elementskey' ),
            'bottom_bar'   => __( 'Bottom Bar', 'elementskey' ),
            'loop'         => __( 'Loop Item', 'elementskey' ),
            'section'      => __( 'Section', 'elementskey' ),
        ];

        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Filter links are plain navigation, no nonce needed.
        $type_view = isset( $_GET['elementskey_type'] ) ? sanitize_key( $_GET['elementskey_type'] ) : '';
        if ( ! isset( $type_labels[ $type_view ] ) ) {
            $type_view = '';
        }

        $type_counts = [];
        foreach ( array_keys( $type_labels ) as $type_key ) {
            $type_counts[ $type_key ] = count( $this->get_templates( $type_key, [ 'publish', 'draft' ] ) );
        }

        $templates   = $this->get_templates( $type_view, $statuses[ $status_view ] );
        $all_pages   = get_pages();
        $hf_counts   = wp_count_posts( 'elementskey_header_footer' );
        $library_templates = array_merge(
            $this->get_templates( 'loop', [ 'publish', 'draft', 'trash' ] ),
            $this->get_templates( 'section', [ 'publish', 'draft', 'trash' ] )
        );
        $publish_count = isset( $hf_counts->publish ) ? (int) $hf_counts->publish : 0;
        $draft_count   = isset( $hf_counts->draft ) ? (int) $hf_counts->draft : 0;
        $trash_count   = isset( $hf_counts->trash ) ? (int) $hf_counts->trash : 0;
        $all_count     = $publish_count + $draft_count + count( array_filter( $library_templates, function( $p ) { return 'trash' !== $p->post_status; } ) );
        $pub_count     = $publish_count + count( array_filter( $library_templates, function( $p ) { return 'publish' === $p->post_status; } ) );
        $trash_count  += count( array_filter( $library_templates, function( $p ) { return 'trash' === $p->post_status; } ) );
        $page_url    = admin_url( 'admin.php?page=elementskey-hf-builder' );
        $base_url    = $type_view ? add_query_arg( 'elementskey_type', $type_view, $page_url ) : $page_url;

        // translators: %s: Template type name (Header, Footer, etc.).
        $create_label = $type_view ? sprintf( __( 'Add New %s', 'elementskey' ), $type_labels[ $type_view ] ) : __( 'Add New Template', 'elementskey' );
        $create_type  = $type_view ? $type_view : 'header';
        ?>
        <div class="wrap elementskey-hf-wrap">
            <h1 class="wp-heading-inline"><?php esc_html_e( 'Theme Builder', 'elementskey' ); ?></h1>

            <a href="#" class="page-title-action elementskey-hf-create-btn" data-type="<?php echo esc_attr( $create_type ); ?>"><?php echo esc_html( $create_label ); ?></a>
            <a href="#" class="page-title-action elementskey-hf-import-btn"><?php esc_html_e( 'Import', 'elementskey' ); ?></a>

            <hr class="wp-header-end">

            <nav class="nav-tab-wrapper elementskey-hf-type-tabs">
                <a href="<?php echo esc_url( remove_query_arg( 'elementskey_type', $page_url ) ); ?>" class="nav-tab <?php echo '' === $type_view ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'All', 'elementskey' ); ?> <span class="count">(<?php echo esc_html( $all_count ); ?>)</span></a>
                <?php foreach ( $type_labels as $type_key => $type_label ) : ?>
                    <a href="<?php echo esc_url( add_query_arg( 'elementskey_type', $type_key, $page_url ) ); ?>" class="nav-tab <?php echo $type_key === $type_view ? 'nav-tab-active' : ''; ?>"><?php echo esc_html( $type_label ); ?> <span class="count">(<?php echo esc_html( $type_counts[ $type_key ] ); ?>)</span></a>
                <?php endforeach; ?>
            </nav>

            <ul class="subsubsub">
                <li class="all"><a href="<?php echo esc_url( $base_url ); ?>" class="<?php echo 'all' === $status_view ? 'current' : ''; ?>"><?php esc_html_e( 'All', 'elementskey' ); ?> <span class="count">(<?php echo esc_html( $all_count ); ?>)</span></a> |</li>
                <li class="published"><a href="<?php echo esc_url( add_query_arg( 'elementskey_status', 'published', $base_url ) ); ?>" class="<?php echo 'published' === $status_view ? 'current' : ''; ?>"><?php esc_html_e( 'Published', 'elementskey' ); ?> <span class="count">(<?php echo esc_html( $pub_count ); ?>)</span></a> |</li>
                <li class="trash"><a href="<?php echo esc_url( add_query_arg( 'elementskey_status', 'trash', $base_url ) ); ?>" class="<?php echo 'trash' === $status_view ? 'current' : ''; ?>"><?php esc_html_e( 'Trash', 'elementskey' ); ?> <span class="count">(<?php echo esc_html( $trash_count ); ?>)</span></a></li>
            </ul>

            <p class="elementskey-hf-list-sub"><?php
                // translators: %s: Number of templates.
                echo esc_html( sprintf( _n( '%s template found', '%s templates found', count( $templates ), 'elementskey' ), number_format_i18n( count( $templates ) ) ) );
            ?></p>

            <div class="elementskey-hf-bulk-bar">
                <select id="elementskey-hf-bulk-action">
                    <option value=""><?php esc_html_e( 'Bulk Actions', 'elementskey' ); ?></option>
                    <?php if ( 'trash' === $status_view ) : ?>
                        <option value="restore"><?php esc_html_e( 'Restore', 'elementskey' ); ?></option>
                        <option value="delete"><?php esc_html_e( 'Delete Permanently', 'elementskey' ); ?></option>
                    <?php else : ?>
                        <option value="trash"><?php esc_html_e( 'Trash', 'elementskey' ); ?></option>
                        <option value="activate"><?php esc_html_e( 'Activate', 'elementskey' ); ?></option>
                        <option value="deactivate"><?php esc_html_e( 'Deactivate', 'elementskey' ); ?></option>
                    <?php endif; ?>
                </select>
                <button type="button" class="button" id="elementskey-hf-bulk-apply" data-nonce="<?php echo esc_attr( wp_create_nonce( 'elementskey_hf_bulk' ) ); ?>"><?php esc_html_e( 'Apply', 'elementskey' ); ?></button>
            </div>

            <table class="wp-list-table widefat fixed striped table-view-list elementskey-hf-table">
                <thead>
                    <tr>
                        <th id="cb" scope="col" class="manage-column check-column"><input type="checkbox" id="elementskey-hf-select-all" /></th>
                        <th scope="col" class="manage-column column-title column-primary"><?php esc_html_e( 'Title', 'elementskey' ); ?></th>
                        <th scope="col" class="manage-column"><?php esc_html_e( 'Type', 'elementskey' ); ?></th>
                        <th scope="col" class="manage-column"><?php esc_html_e( 'Status', 'elementskey' ); ?></th>
                        <th scope="col" class="manage-column"><?php esc_html_e( 'Display Conditions', 'elementskey' ); ?></th>
                        <th scope="col" class="manage-column"><?php esc_html_e( 'Date', 'elementskey' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ( empty( $templates ) ) : ?>
                        <tr><td colspan="6"><?php
                            // translators: %1$s: Template type name, %2$s: Create template button label.
                            echo esc_html( sprintf( __( 'No %1$stemplates found. Click "%2$s" to create one.', 'elementskey' ), $type_view ? strtolower( $type_labels[ $type_view ] ) . ' ' : '', $create_label ) );
                        ?></td></tr>
                    <?php else : ?>
                        <?php foreach ( $templates as $post ) : ?>
                            <?php
                            $post_id    = $post->ID;
                            $is_loop    = 'elementor_library' === $post->post_type;
                            $type       = $is_loop
                                ? get_post_meta( $post_id, '_elementor_template_type', true )
                                : get_post_meta( $post_id, '_elementskey_hf_template_type', true );
                            $conditions = $is_loop ? [] : get_post_meta( $post_id, '_elementskey_hf_conditions', true );
                            $is_trash   = 'trash' === $post->post_status;
                            $is_active  = 'publish' === $post->post_status;
                            $cond_label = $this->get_conditions_label( $conditions );
                            $edit_url   = add_query_arg(
                                [ 'action' => 'elementor', 'post' => $post_id ],
                                admin_url( 'post.php' )
                            );
                            $restore_url = wp_nonce_url(
                                add_query_arg( [ 'action' => 'elementskey_hf_restore', 'id' => $post_id ], admin_url( 'admin-post.php' ) ),
                                'elementskey_hf_restore_' . $post_id
                            );
                            ?>
                            <tr data-id="<?php echo esc_attr( $post_id ); ?>" class="<?php echo $is_trash ? 'elementskey-hf-trash-row' : ''; ?>">
                                <th scope="row" class="check-column"><input type="checkbox" class="elementskey-hf-cb" value="<?php echo esc_attr( $post_id ); ?>" /></th>
                                <td class="column-title column-primary">
                                    <div class="elementskey-hf-type-chip-mobile">
                                        <?php $this->render_type_badge( $type ); ?>
                                    </div>
                                    <strong><a class="row-title" href="<?php echo esc_url( $edit_url ); ?>"><?php echo esc_html( $post->post_title ); ?></a></strong>
                                    <?php if ( $is_trash ) : ?>
                                        <div class="row-actions">
                                            <span class="restore"><a href="<?php echo esc_url( $restore_url ); ?>"><?php esc_html_e( 'Restore', 'elementskey' ); ?></a> | </span>
                                            <span class="trash"><a href="<?php echo esc_url( get_delete_post_link( $post_id ) ); ?>" class="elementskey-hf-trash"><?php esc_html_e( 'Delete Permanently', 'elementskey' ); ?></a></span>
                                        </div>
                                    <?php else : ?>
                                        <div class="row-actions">
                                            <span class="edit"><a href="<?php echo esc_url( $edit_url ); ?>"><?php esc_html_e( 'Edit with Elementor', 'elementskey' ); ?></a> | </span>
                                            <span class="elementskey-hf-dup"><a href="#" class="elementskey-hf-duplicate-btn" data-id="<?php echo esc_attr( $post_id ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'elementskey_hf_duplicate' ) ); ?>"><?php esc_html_e( 'Duplicate', 'elementskey' ); ?></a> | </span>
                                            <span class="elementskey-hf-export"><a href="#" class="elementskey-hf-export-btn" data-id="<?php echo esc_attr( $post_id ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'elementskey_hf_export' ) ); ?>"><?php esc_html_e( 'Export', 'elementskey' ); ?></a> | </span>
                                            <span class="trash"><a href="<?php echo esc_url( get_delete_post_link( $post_id ) ); ?>" class="elementskey-hf-trash"><?php esc_html_e( 'Trash', 'elementskey' ); ?></a></span>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td><?php $this->render_type_badge( $type ); ?></td>
                                <td>
                                    <?php if ( $is_trash ) : ?>
                                        <span class="elementskey-hf-badge elementskey-hf-badge-trash"><?php esc_html_e( 'Trashed', 'elementskey' ); ?></span>
                                    <?php elseif ( $is_active ) : ?>
                                        <span class="elementskey-hf-badge elementskey-hf-badge-active"><?php esc_html_e( 'Active', 'elementskey' ); ?></span>
                                    <?php else : ?>
                                        <span class="elementskey-hf-badge elementskey-hf-badge-inactive"><?php esc_html_e( 'Inactive', 'elementskey' ); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="elementskey-hf-cond-cell">
                                        <?php if ( ! empty( $conditions ) ) : ?>
                                            <div class="elementskey-hf-cond-chips">
                                                <?php foreach ( (array) $conditions as $cond ) : ?>
                                                    <?php
                                                    $cond_id = $cond['condition'] ?? '';
                                                    $all     = $this->condition_manager->get_conditions();
                                                    $label   = isset( $all[ $cond_id ]['label'] ) ? $all[ $cond_id ]['label'] : $cond_id;
                                                    $is_exclude = 'exclude' === ( $cond['type'] ?? '' );
                                                    ?>
                                                    <span class="elementskey-hf-cond-chip <?php echo $is_exclude ? 'elementskey-hf-cond-chip-exclude' : ''; ?>">
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
                                            <span class="elementskey-hf-cond-empty"><?php esc_html_e( 'All Website', 'elementskey' ); ?></span>
                                        <?php endif; ?>
                                        <?php if ( ! $is_trash ) : ?>
                                            <button type="button" class="elementskey-hf-edit-cond" data-id="<?php echo esc_attr( $post_id ); ?>">
                                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M10 1.5l2.5 2.5L4.5 12H2v-2.5L10 1.5z"/></svg>
                                                <?php esc_html_e( 'Edit', 'elementskey' ); ?>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="elementskey-hf-date-cell">
                                    <span class="elementskey-hf-date"><?php echo esc_html( get_the_date( 'M j, Y', $post ) ); ?></span>
                                    <span class="elementskey-hf-time"><?php echo esc_html( get_the_time( 'g:i a', $post ) ); ?></span>
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
        <div id="elementskey-hf-import-modal" class="elementskey-hf-modal-overlay" style="display:none;">
            <div class="elementskey-hf-modal">
                <div class="elementskey-hf-modal-header">
                    <div class="elementskey-hf-modal-header-icon dashicons dashicons-upload"></div>
                    <div>
                        <h2><?php esc_html_e( 'Import Template', 'elementskey' ); ?></h2>
                        <p class="elementskey-hf-modal-subtitle"><?php esc_html_e( 'Upload a previously exported .json file', 'elementskey' ); ?></p>
                    </div>
                    <button type="button" class="elementskey-hf-modal-close">&times;</button>
                </div>
                <div class="elementskey-hf-modal-body">
                    <form id="elementskey-hf-import-form">
                        <div class="elementskey-hf-field">
                            <label><span class="dashicons dashicons-upload"></span> <?php esc_html_e( 'Select JSON File', 'elementskey' ); ?></label>
                            <div class="elementskey-hf-dropzone" id="elementskey-hf-dropzone">
                                <span class="dashicons dashicons-cloud-upload"></span>
                                <p><strong><?php esc_html_e( 'Drop your .json file here', 'elementskey' ); ?></strong> <?php esc_html_e( 'or click to browse', 'elementskey' ); ?></p>
                                <span class="elementskey-hf-dropzone-help"><?php esc_html_e( 'File exported from the Export button', 'elementskey' ); ?></span>
                                <span class="elementskey-hf-dropzone-file"></span>
                                <input type="file" name="import_file" accept=".json" required />
                            </div>
                        </div>
                    </form>
                </div>
                <div class="elementskey-hf-modal-footer">
                    <button type="button" class="elementskey-hf-btn-cancel" data-close-modal><?php esc_html_e( 'Cancel', 'elementskey' ); ?></button>
                    <button type="button" class="elementskey-hf-btn-primary elementskey-hf-import-submit" data-nonce="<?php echo esc_attr( wp_create_nonce( 'elementskey_hf_import' ) ); ?>">
                        <?php esc_html_e( 'Import Template', 'elementskey' ); ?>
                    </button>
                </div>
            </div>
        </div>
        <?php
    }

    private function render_create_modal( $pages ) {
        ?>
        <div id="elementskey-hf-create-modal" class="elementskey-hf-modal-overlay" style="display:none;">
            <div class="elementskey-hf-modal">
                <div class="elementskey-hf-modal-header">
                    <div class="elementskey-hf-modal-header-icon dashicons dashicons-editor-kitchensink"></div>
                    <div>
                        <h2><?php esc_html_e( 'Create', 'elementskey' ); ?> <span class="elementskey-hf-modal-type-label"><?php esc_html_e( 'Header', 'elementskey' ); ?></span> <?php esc_html_e( 'Template', 'elementskey' ); ?></h2>
                        <p class="elementskey-hf-modal-subtitle"><?php esc_html_e( 'Set up a new template with display rules', 'elementskey' ); ?></p>
                    </div>
                    <button type="button" class="elementskey-hf-modal-close">&times;</button>
                </div>
                <div class="elementskey-hf-modal-body">
                    <form id="elementskey-hf-create-form">
                        <input type="hidden" name="type" value="" />

                        <div class="elementskey-hf-field">
                            <label><span class="dashicons dashicons-edit"></span> <?php esc_html_e( 'Template Name', 'elementskey' ); ?></label>
                            <input type="text" name="name" placeholder="<?php esc_attr_e( 'e.g. Main Header, Footer v2', 'elementskey' ); ?>" required />
                        </div>

                        <div class="elementskey-hf-field">
                            <label><span class="dashicons dashicons-layout"></span> <?php esc_html_e( 'Template Type', 'elementskey' ); ?></label>
                            <select name="template_type" id="elementskey-hf-create-type">
                                <option value="header"><?php esc_html_e( 'Header', 'elementskey' ); ?></option>
                                <option value="footer"><?php esc_html_e( 'Footer', 'elementskey' ); ?></option>
                                <option value="single"><?php esc_html_e( 'Single Post Template', 'elementskey' ); ?></option>
                                <option value="archive"><?php esc_html_e( 'Archive (Category / Tag / Loop)', 'elementskey' ); ?></option>
                                <option value="404"><?php esc_html_e( '404 Page', 'elementskey' ); ?></option>
                                <option value="announcement"><?php esc_html_e( 'Announcement Bar', 'elementskey' ); ?></option>
                                <option value="bottom_bar"><?php esc_html_e( 'Bottom Bar', 'elementskey' ); ?></option>
                                <option value="loop"><?php esc_html_e( 'Loop Item Template', 'elementskey' ); ?></option>
                                <option value="section"><?php esc_html_e( 'Section Template', 'elementskey' ); ?></option>
                            </select>
                        </div>

                        <div class="elementskey-hf-field-row">
                            <div class="elementskey-hf-field">
                                <label><span class="dashicons dashicons-layout"></span> <?php esc_html_e( 'Display Condition', 'elementskey' ); ?></label>
                                                                <select name="condition">
                                    <option value="entire_site"><?php esc_html_e( 'Entire Website', 'elementskey' ); ?></option>
                                    <option value="front_page"><?php esc_html_e( 'Front Page', 'elementskey' ); ?></option>
                                    <option value="home_page"><?php esc_html_e( 'Home / Blog Page', 'elementskey' ); ?></option>
                                    <option value="custom_url"><?php esc_html_e( 'Custom URL', 'elementskey' ); ?></option>
                                    <option value="singular"><?php esc_html_e( 'All Singular', 'elementskey' ); ?></option>
                                    <option value="singular:post_type:post"><?php esc_html_e( 'All Blog Posts', 'elementskey' ); ?></option>
                                    <option value="singular:post_type:page"><?php esc_html_e( 'All Pages', 'elementskey' ); ?></option>
                                    <option value="archive"><?php esc_html_e( 'All Archives', 'elementskey' ); ?></option>
                                    <?php
                                    $elementskey_archive_condition_taxonomies = get_taxonomies( [ 'public' => true ], 'objects' );
                                    foreach ( $elementskey_archive_condition_taxonomies as $elementskey_tax ) :
                                        if ( in_array( $elementskey_tax->name, [ 'elementor_library', 'elementskey_header_footer', 'nav_menu', 'link_category' ], true ) ) {
                                            continue;
                                        }
                                        ?>
                                        <option value="archive:taxonomy:<?php echo esc_attr( $elementskey_tax->name ); ?>"><?php
                                            // translators: %s: Taxonomy name.
                                            echo esc_html( sprintf( __( 'All %s Archives', 'elementskey' ), $elementskey_tax->labels->name ) );
                                        ?></option>
                                        <?php
                                        $elementskey_terms = get_terms( [ 'taxonomy' => $elementskey_tax->name, 'hide_empty' => false, 'number' => 50 ] );
                                        if ( ! is_wp_error( $elementskey_terms ) ) {
                                            foreach ( $elementskey_terms as $elementskey_term ) {
                                                ?>
                                                <option value="archive:taxonomy:<?php echo esc_attr( $elementskey_tax->name ); ?>:term:<?php echo esc_attr( $elementskey_term->slug ); ?>"><?php echo esc_html( $elementskey_tax->labels->name ); ?>: <?php echo esc_html( $elementskey_term->name ); ?></option>
                                                <?php
                                            }
                                        }
                                    endforeach;
                                    ?>
                                    <option value="search"><?php esc_html_e( 'Search Results', 'elementskey' ); ?></option>
                                    <option value="404"><?php esc_html_e( '404 Page', 'elementskey' ); ?></option>
                                </select>
                            </div>

                            <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                            <div class="elementskey-hf-field">
                                <label><span class="dashicons dashicons-cart"></span> <?php esc_html_e( 'WooCommerce', 'elementskey' ); ?></label>
                                <select name="woo">
                                    <option value=""><?php esc_html_e( 'None', 'elementskey' ); ?></option>
                                    <option value="woocommerce:shop"><?php esc_html_e( 'Shop Page', 'elementskey' ); ?></option>
                                    <option value="woocommerce:product"><?php esc_html_e( 'Product Page', 'elementskey' ); ?></option>
                                    <option value="woocommerce:cart"><?php esc_html_e( 'Cart Page', 'elementskey' ); ?></option>
                                    <option value="woocommerce:checkout"><?php esc_html_e( 'Checkout Page', 'elementskey' ); ?></option>
                                    <option value="woocommerce:account"><?php esc_html_e( 'My Account Page', 'elementskey' ); ?></option>
                                    <option value="woocommerce:product_archive"><?php esc_html_e( 'Product Archive', 'elementskey' ); ?></option>
                                </select>
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="elementskey-hf-field-row">
                            <div class="elementskey-hf-field">
                                <label><span class="dashicons dashicons-plus"></span> <?php esc_html_e( 'Include Pages', 'elementskey' ); ?></label>
                                <select name="include_pages[]" class="elementskey-hf-page-select" multiple>
                                    <?php foreach ( $pages as $page ) : ?>
                                        <option value="<?php echo esc_attr( $page->ID ); ?>"><?php echo esc_html( $page->post_title ); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="elementskey-hf-select-hint"><?php esc_html_e( 'Hold Ctrl to select multiple', 'elementskey' ); ?></span>
                                <button type="button" class="elementskey-hf-clear-select" data-target="include_pages"><?php esc_html_e( 'Clear', 'elementskey' ); ?></button>
                            </div>

                            <div class="elementskey-hf-field">
                                <label><span class="dashicons dashicons-dismiss"></span> <?php esc_html_e( 'Exclude Pages', 'elementskey' ); ?></label>
                                <select name="exclude_pages[]" class="elementskey-hf-page-select" multiple>
                                    <?php foreach ( $pages as $page ) : ?>
                                        <option value="<?php echo esc_attr( $page->ID ); ?>"><?php echo esc_html( $page->post_title ); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="elementskey-hf-select-hint"><?php esc_html_e( 'Hold Ctrl to select multiple', 'elementskey' ); ?></span>
                                <button type="button" class="elementskey-hf-clear-select" data-target="exclude_pages"><?php esc_html_e( 'Clear', 'elementskey' ); ?></button>
                            </div>
                        </div>

                        <div class="elementskey-hf-field-row elementskey-hf-field-checkboxes">
                            <div class="elementskey-hf-field elementskey-hf-field-inline">
                                <label class="elementskey-hf-checkbox-label">
                                    <input type="checkbox" name="disable_theme" value="yes" />
                                    <span class="elementskey-hf-checkbox-ui"></span>
                                    <span class="elementskey-hf-checkbox-content">
                                        <strong><?php esc_html_e( 'Disable Theme Header', 'elementskey' ); ?></strong>
                                        <span class="elementskey-hf-field-desc"><?php esc_html_e( 'Replace theme header with Elementor', 'elementskey' ); ?></span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="elementskey-hf-modal-footer">
                    <button type="button" class="elementskey-hf-btn-cancel" data-close-modal><?php esc_html_e( 'Cancel', 'elementskey' ); ?></button>
                    <button type="button" class="elementskey-hf-btn-primary elementskey-hf-create-submit" data-nonce="<?php echo esc_attr( wp_create_nonce( 'elementskey_hf_create' ) ); ?>">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M8 3v10M3 8h10"/></svg>
                        <?php esc_html_e( 'Create & Edit', 'elementskey' ); ?>
                    </button>
                </div>
            </div>
        </div>
        <?php
    }

    private function render_conditions_modal() {
        ?>
        <div id="elementskey-hf-conditions-modal" class="elementskey-hf-modal-overlay" style="display:none;">
            <div class="elementskey-hf-modal">
                <div class="elementskey-hf-modal-header">
                    <div class="elementskey-hf-modal-header-icon dashicons dashicons-filter"></div>
                    <div>
                        <h2><?php esc_html_e( 'Edit Display Conditions', 'elementskey' ); ?></h2>
                        <p class="elementskey-hf-modal-subtitle"><?php esc_html_e( 'Control where this template appears', 'elementskey' ); ?></p>
                    </div>
                    <button type="button" class="elementskey-hf-modal-close">&times;</button>
                </div>
                <div class="elementskey-hf-modal-body">
                    <form id="elementskey-hf-conditions-form">
                        <input type="hidden" name="template_id" value="" />
                        <div class="elementskey-hf-cond-search">
                            <span class="dashicons dashicons-search"></span>
                            <input type="text" id="elementskey-hf-cond-search" placeholder="<?php esc_attr_e( 'Search conditions...', 'elementskey' ); ?>" />
                            <button type="button" class="elementskey-hf-cond-search-clear" title="<?php esc_attr_e( 'Clear', 'elementskey' ); ?>">&times;</button>
                        </div>
                        <div class="elementskey-hf-conditions-list"></div>
                        <p>
                            <button type="button" class="button elementskey-hf-add-condition-row">+ <?php esc_html_e( 'Add Condition', 'elementskey' ); ?></button>
                        </p>
                        <hr style="margin:16px 0;border:none;border-top:1px solid #e2e4e7;">
                        <p style="margin:0 0 8px;font-weight:600;color:#1e1e1e;"><?php esc_html_e( 'Template Settings', 'elementskey' ); ?></p>
                        <p class="elementskey-hf-field-desc" style="margin:0 0 12px;">
                            <?php esc_html_e( 'Sticky, transparent, schedule and other behavior settings are managed inside the Elementor editor (Settings panel).', 'elementskey' ); ?>
                        </p>
                        <div class="elementskey-hf-field-row elementskey-hf-field-checkboxes elementskey-hf-conditions-settings">
                            <div class="elementskey-hf-field elementskey-hf-field-inline">
                                <label class="elementskey-hf-checkbox-label">
                                    <input type="checkbox" name="disable_theme" value="yes" />
                                    <span class="elementskey-hf-checkbox-ui"></span>
                                    <span class="elementskey-hf-checkbox-content">
                                        <strong><?php esc_html_e( 'Disable Theme Header', 'elementskey' ); ?></strong>
                                        <span class="elementskey-hf-field-desc"><?php esc_html_e( 'Hide theme header with CSS', 'elementskey' ); ?></span>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <p style="margin:12px 0 8px;font-weight:600;color:#1e1e1e;"><?php esc_html_e( 'Device Visibility', 'elementskey' ); ?></p>
                        <div class="elementskey-hf-device-toggles">
                            <label class="elementskey-hf-device-toggle">
                                <input type="checkbox" name="device_desktop" value="yes" checked />
                                <span class="elementskey-hf-device-btn">
                                    <span class="dashicons dashicons-desktop"></span>
                                    <span class="elementskey-hf-device-label"><?php esc_html_e( 'Desktop', 'elementskey' ); ?></span>
                                </span>
                            </label>
                            <label class="elementskey-hf-device-toggle">
                                <input type="checkbox" name="device_tablet" value="yes" checked />
                                <span class="elementskey-hf-device-btn">
                                    <span class="dashicons dashicons-tablet"></span>
                                    <span class="elementskey-hf-device-label"><?php esc_html_e( 'Tablet', 'elementskey' ); ?></span>
                                </span>
                            </label>
                            <label class="elementskey-hf-device-toggle">
                                <input type="checkbox" name="device_mobile" value="yes" checked />
                                <span class="elementskey-hf-device-btn">
                                    <span class="dashicons dashicons-smartphone"></span>
                                    <span class="elementskey-hf-device-label"><?php esc_html_e( 'Mobile', 'elementskey' ); ?></span>
                                </span>
                            </label>
                        </div>
                    </form>
                </div>
                <div class="elementskey-hf-modal-footer">
                    <button type="button" class="elementskey-hf-btn-cancel" data-close-modal><?php esc_html_e( 'Cancel', 'elementskey' ); ?></button>
                    <button type="button" class="elementskey-hf-btn-primary elementskey-hf-conditions-save" data-nonce="<?php echo esc_attr( wp_create_nonce( 'elementskey_hf_conditions' ) ); ?>">
                        <?php esc_html_e( 'Save Conditions', 'elementskey' ); ?>
                    </button>
                </div>
            </div>
        </div>
        <?php
    }

    public function enqueue_admin_assets( $hook ) {
        if ( false === strpos( $hook, 'elementskey-hf' ) ) {
            return;
        }

        $css_file = __DIR__ . '/assets/css/admin.css';
        $css_ver  = file_exists( $css_file ) ? filemtime( $css_file ) : ELEMENTSKEY_VERSION;
        wp_enqueue_style(
            'elementskey-hf-admin-style',
            plugin_dir_url( __FILE__ ) . 'assets/css/admin.css',
            [],
            $css_ver
        );

        $js_file = __DIR__ . '/assets/js/admin.js';
        $js_ver  = file_exists( $js_file ) ? filemtime( $js_file ) : ELEMENTSKEY_VERSION;
        wp_enqueue_script(
            'elementskey-hf-admin-script',
            plugin_dir_url( __FILE__ ) . 'assets/js/admin.js',
            [ 'jquery', 'jquery-ui-sortable' ],
            $js_ver,
            true
        );

        wp_localize_script( 'elementskey-hf-admin-script', 'elementskeyHFData', [
            'ajax_url'     => admin_url( 'admin-ajax.php' ),
            'conditions'   => $this->condition_manager->get_conditions_grouped(),
            'reorder_nonce' => wp_create_nonce( 'elementskey_hf_reorder' ),
            'strings'   => [
                'include' => __( 'Include', 'elementskey' ),
                'exclude' => __( 'Exclude', 'elementskey' ), // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude -- UI string, not a query argument.
            ],
        ] );
    }

    public function ajax_create_template() {
        check_ajax_referer( 'elementskey_hf_create', 'nonce' );

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

        if ( empty( $name ) || ! in_array( $type, [ 'header', 'footer', 'single', 'archive', '404', 'announcement', 'bottom_bar', 'loop', 'section' ], true ) ) {
            wp_send_json_error( [ 'message' => __( 'Name and type are required.', 'elementskey' ) ] );
        }

        // Loop templates use elementor_library post type (like Elementor Pro)
        if ( in_array( $type, [ 'loop', 'section' ], true ) ) {
            $post_id = wp_insert_post( [
                'post_title'  => $name,
                'post_type'   => 'elementor_library',
                'post_status' => 'publish',
            ] );

if ( is_wp_error( $post_id ) ) {
            wp_send_json_error( [ 'message' => __( 'Failed to create template.', 'elementskey' ) ] );
        }

            $container_id = substr( md5( 'elementskey-' . $type . '-' . $post_id . '-1' ), 0, 7 );
            update_post_meta( $post_id, '_elementor_template_type', 'section' === $type ? 'section' : 'loop-item' );
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
                'message' => __( 'Template created.', 'elementskey' ),
                'edit_url' => add_query_arg(
                    [ 'action' => 'elementor', 'post' => $post_id ],
                    admin_url( 'post.php' )
                ),
            ] );
        }

        $post_id = wp_insert_post( [
            'post_title'  => $name,
            'post_type'   => 'elementskey_header_footer',
            'post_status' => 'publish',
        ] );

        if ( is_wp_error( $post_id ) ) {
            wp_send_json_error( [ 'message' => __( 'Failed to create template.', 'elementskey' ) ] );
        }

        update_post_meta( $post_id, '_elementskey_hf_template_type', $type );

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

        update_post_meta( $post_id, '_elementskey_hf_conditions', $conditions );

        if ( $disable_theme ) {
            update_post_meta( $post_id, '_elementskey_hf_disable_theme', 'yes' );
        }

        $this->cache->flush_all();

        $edit_url = add_query_arg(
            [ 'action' => 'elementor', 'post' => $post_id ],
            admin_url( 'post.php' )
        );

        wp_send_json_success( [ 'edit_url' => $edit_url ] );
    }

    public function ajax_get_template_conditions() {
        check_ajax_referer( 'elementskey_hf_conditions', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( -1 );
        }

        $post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;

        if ( ! $post_id ) {
            wp_send_json_error( [ 'message' => __( 'Invalid post ID.', 'elementskey' ) ] );
        }

        $conditions       = get_post_meta( $post_id, '_elementskey_hf_conditions', true );
        $disable_theme    = get_post_meta( $post_id, '_elementskey_hf_disable_theme', true );
        $type             = get_post_meta( $post_id, '_elementskey_hf_template_type', true );
        $device_vis       = get_post_meta( $post_id, '_elementskey_hf_device_visibility', true );

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
        check_ajax_referer( 'elementskey_hf_conditions', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( -1 );
        }

        $post_id    = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
        $conditions = isset( $_POST['conditions'] ) ? (array) wp_unslash( $_POST['conditions'] ) : []; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Each value sanitized in the loop below.

        if ( ! $post_id ) {
            wp_send_json_error( [ 'message' => __( 'Invalid post ID.', 'elementskey' ) ] );
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

        update_post_meta( $post_id, '_elementskey_hf_conditions', $sanitized );
        $disable_theme = isset( $_POST['disable_theme'] ) ? sanitize_key( $_POST['disable_theme'] ) : '';
        update_post_meta( $post_id, '_elementskey_hf_disable_theme', 'yes' === $disable_theme ? 'yes' : '' );

        $device_vis = [
            'desktop' => isset( $_POST['device_desktop'] ) ? sanitize_key( $_POST['device_desktop'] ) : '',
            'tablet'  => isset( $_POST['device_tablet'] ) ? sanitize_key( $_POST['device_tablet'] ) : '',
            'mobile'  => isset( $_POST['device_mobile'] ) ? sanitize_key( $_POST['device_mobile'] ) : '',
        ];
        $device_vis = array_map( static function ( $value ) {
            return 'yes' === $value ? 'yes' : '';
        }, $device_vis );
        update_post_meta( $post_id, '_elementskey_hf_device_visibility', $device_vis );

        $this->cache->flush_all();

        wp_send_json_success( [ 'message' => __( 'Conditions saved.', 'elementskey' ) ] );
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
        check_ajax_referer( 'elementskey_hf_duplicate', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( -1 );
        }

        $post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;

        if ( ! $post_id ) {
            wp_send_json_error( [ 'message' => __( 'Invalid post ID.', 'elementskey' ) ] );
        }

        $post = get_post( $post_id );

        if ( ! $post || ! in_array( $post->post_type, [ 'elementskey_header_footer', 'elementor_library' ], true ) ) {
            wp_send_json_error( [ 'message' => __( 'Template not found.', 'elementskey' ) ] );
        }

        $new_id = wp_insert_post( [
            'post_title'  => $post->post_title . __( ' (Copy)', 'elementskey' ),
            'post_type'   => $post->post_type,
            'post_status' => 'publish',
        ] );

        if ( is_wp_error( $new_id ) ) {
            wp_send_json_error( [ 'message' => __( 'Failed to duplicate template.', 'elementskey' ) ] );
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
        check_ajax_referer( 'elementskey_hf_bulk', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( -1 );
        }

        $action    = isset( $_POST['doaction'] ) ? sanitize_key( $_POST['doaction'] ) : '';
        $post_ids  = isset( $_POST['post_ids'] ) ? array_map( 'intval', $_POST['post_ids'] ) : [];

        if ( empty( $post_ids ) || ! in_array( $action, [ 'trash', 'activate', 'deactivate', 'restore', 'delete' ], true ) ) {
            wp_send_json_error( [ 'message' => __( 'Invalid request.', 'elementskey' ) ] );
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
        wp_send_json_success( [ 'message' => sprintf( _n( '%s template updated.', '%s templates updated.', count( $post_ids ), 'elementskey' ), number_format_i18n( count( $post_ids ) ) ) ] );
    }

    public function handle_restore() {
        $id = isset( $_GET['id'] ) ? absint( $_GET['id'] ) : 0;

        if ( ! $id || ! current_user_can( 'manage_options' ) ) {
            wp_die( -1 );
        }

        check_admin_referer( 'elementskey_hf_restore_' . $id );

        wp_untrash_post( $id );
        $this->cache->flush_all();

        wp_safe_redirect( wp_get_referer() ? wp_get_referer() : admin_url( 'admin.php?page=elementskey-hf-builder' ) );
        exit;
    }

    public function ajax_get_posts() {
        check_ajax_referer( 'elementskey_hf_conditions', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( -1 );
        }

        $post_type = isset( $_POST['post_type'] ) ? sanitize_key( $_POST['post_type'] ) : '';
        $search    = isset( $_POST['search'] ) ? sanitize_text_field( wp_unslash( $_POST['search'] ) ) : '';

        if ( ! post_type_exists( $post_type ) ) {
            wp_send_json_error( [ 'message' => __( 'Invalid post type.', 'elementskey' ) ] );
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
        check_ajax_referer( 'elementskey_hf_export', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( -1 );
        }

        $post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;

        if ( ! $post_id ) {
            wp_send_json_error( [ 'message' => __( 'Invalid post ID.', 'elementskey' ) ] );
        }

        $post = get_post( $post_id );

        if ( ! $post || ! in_array( $post->post_type, [ 'elementskey_header_footer', 'elementor_library' ], true ) ) {
            wp_send_json_error( [ 'message' => __( 'Template not found.', 'elementskey' ) ] );
        }

        $is_loop = 'elementor_library' === $post->post_type;
        $meta_keys = $is_loop ? [] : [
            '_elementskey_hf_template_type',
            '_elementskey_hf_conditions',
            '_elementskey_hf_priority',
            '_elementskey_hf_sticky',
            '_elementskey_hf_scroll_animation',
            '_elementskey_hf_disable_theme',
            '_elementskey_hf_device_visibility',
            '_elementskey_hf_transparent',
            '_elementskey_hf_sticky_shrink',
            '_elementskey_hf_sticky_hide_scroll',
            '_elementskey_hf_logo_switcher',
            '_elementskey_hf_sticky_offset',
            '_elementskey_hf_dismissible',
            '_elementskey_hf_cookie_days',
            '_elementskey_hf_schedule_enabled',
            '_elementskey_hf_schedule_start',
            '_elementskey_hf_schedule_end',
        ];

        $meta = [];

        foreach ( $meta_keys as $key ) {
            $meta[ $key ] = get_post_meta( $post_id, $key, true );
        }

        $elementor_data = get_post_meta( $post_id, '_elementor_data', true );
        $elementor_css  = get_post_meta( $post_id, '_elementor_css', true );
        $template_type  = get_post_meta( $post_id, '_elementor_template_type', true );

        $export = [
            'version'                 => ELEMENTSKEY_VERSION,
            'title'                   => $post->post_title,
            'type'                    => $post->post_type,
            'meta'                    => $meta,
            'elementor_data'          => $elementor_data,
            'elementor_css'           => $elementor_css,
            'elementor_page_settings' => get_post_meta( $post_id, '_elementor_page_settings', true ),
            'elementor_template_type' => $template_type ?: ( $is_loop ? 'loop-item' : 'elementskey-hf-document' ),
        ];

        wp_send_json_success( [ 'export' => $export ] );
    }

    public function ajax_import_template() {
        check_ajax_referer( 'elementskey_hf_import', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( -1 );
        }

        if ( ! isset( $_FILES['import_file'], $_FILES['import_file']['error'] ) || UPLOAD_ERR_OK !== $_FILES['import_file']['error'] ) {
            wp_send_json_error( [ 'message' => __( 'File upload failed.', 'elementskey' ) ] );
        }

        $file_name = isset( $_FILES['import_file']['name'] ) ? sanitize_file_name( wp_unslash( $_FILES['import_file']['name'] ) ) : '';

        if ( 'json' !== strtolower( pathinfo( $file_name, PATHINFO_EXTENSION ) ) ) {
            wp_send_json_error( [ 'message' => __( 'Only .json template files can be imported.', 'elementskey' ) ] );
        }

        $file_size = isset( $_FILES['import_file']['size'] ) ? absint( $_FILES['import_file']['size'] ) : 0;

        if ( $file_size > 2 * MB_IN_BYTES ) {
            wp_send_json_error( [ 'message' => __( 'The import file is too large. Maximum size is 2 MB.', 'elementskey' ) ] );
        }

        $tmp_name = isset( $_FILES['import_file']['tmp_name'] ) ? $_FILES['import_file']['tmp_name'] : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Server-managed temp path, not user input.

        if ( ! $tmp_name || ! is_uploaded_file( $tmp_name ) ) {
            wp_send_json_error( [ 'message' => __( 'File upload failed.', 'elementskey' ) ] );
        }

        $content = file_get_contents( $tmp_name ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Server-managed temp path, not user input.
        $data    = json_decode( $content, true );

        if ( ! $data || empty( $data['title'] ) ) {
            wp_send_json_error( [ 'message' => __( 'Invalid or corrupt import file.', 'elementskey' ) ] );
        }

        // Determine post type from export data
        $post_type = isset( $data['type'] ) && 'elementor_library' === $data['type']
            ? 'elementor_library'
            : 'elementskey_header_footer';

        $post_id = wp_insert_post( [
            'post_title'  => sanitize_text_field( $data['title'] ),
            'post_type'   => $post_type,
            'post_status' => 'publish',
        ] );

        if ( is_wp_error( $post_id ) ) {
            wp_send_json_error( [ 'message' => __( 'Failed to create template.', 'elementskey' ) ] );
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

        add_post_type_support( 'elementskey_header_footer', 'elementor' );

        $this->cache->flush_all();

        wp_send_json_success( [ 'edit_url' => add_query_arg(
            [ 'action' => 'elementor', 'post' => $post_id ],
            admin_url( 'post.php' )
        ) ] );
    }

    public function ajax_reorder_templates() {
        check_ajax_referer( 'elementskey_hf_reorder', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( -1 );
        }

        $order = isset( $_POST['order'] ) ? (array) wp_unslash( $_POST['order'] ) : []; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- IDs validated with intval() in the loop below.

        if ( empty( $order ) ) {
            wp_send_json_error( [ 'message' => __( 'No order data.', 'elementskey' ) ] );
        }

        foreach ( $order as $index => $post_id ) {
            wp_update_post( [
                'ID'         => intval( $post_id ),
                'menu_order' => intval( $index ),
            ] );
        }

        $this->cache->flush_all();

        wp_send_json_success( [ 'message' => __( 'Order saved.', 'elementskey' ) ] );
    }

    private function get_templates( $type = '', $statuses = null ) {
        $statuses = $statuses ?: [ 'publish', 'draft' ];

        // Loop templates use elementor_library post type (like Elementor Pro)
        if ( in_array( $type, [ 'loop', 'section' ], true ) ) {
            $args = [
                'post_type'      => 'elementor_library',
                'post_status'    => $statuses,
                'posts_per_page' => -1,
                'orderby'        => 'title date',
                'order'          => 'ASC',
                'meta_query'     => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query -- Required to list only Elementor loop templates.
                    [
                        'key'     => '_elementor_template_type',
                        'value'   => 'section' === $type ? [ 'section' ] : [ 'loop-item', 'loop' ],
                        'compare' => 'IN',
                    ],
                ],
            ];
            return get_posts( $args );
        }

        $args = [
            'post_type'      => 'elementskey_header_footer',
            'post_status'    => $statuses,
            'posts_per_page' => -1,
            'orderby'        => 'menu_order date',
            'order'          => 'ASC',
        ];
        if ( $type ) {
            $args['meta_key']   = '_elementskey_hf_template_type'; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- Template type lookup is the primary filter.
            $args['meta_value'] = $type; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value -- Template type lookup is the primary filter.
        }
        return get_posts( $args );
    }

    private function render_type_badge( $type ) {
        if ( 'header' === $type ) :
            ?><span class="elementskey-hf-badge elementskey-hf-badge-header">&#8593; <?php esc_html_e( 'Header', 'elementskey' ); ?></span><?php
        elseif ( 'footer' === $type ) :
            ?><span class="elementskey-hf-badge elementskey-hf-badge-footer">&#8595; <?php esc_html_e( 'Footer', 'elementskey' ); ?></span><?php
        elseif ( 'announcement' === $type ) :
            ?><span class="elementskey-hf-badge elementskey-hf-badge-announcement">&#9888; <?php esc_html_e( 'Announcement', 'elementskey' ); ?></span><?php
        elseif ( 'bottom_bar' === $type ) :
            ?><span class="elementskey-hf-badge elementskey-hf-badge-bottom-bar">&#9660; <?php esc_html_e( 'Bottom Bar', 'elementskey' ); ?></span><?php
        elseif ( 'single' === $type ) :
            ?><span class="elementskey-hf-badge elementskey-hf-badge-single">&#128196; <?php esc_html_e( 'Single Post', 'elementskey' ); ?></span><?php
        elseif ( 'archive' === $type ) :
            ?><span class="elementskey-hf-badge elementskey-hf-badge-archive">&#128230; <?php esc_html_e( 'Archive', 'elementskey' ); ?></span><?php
        elseif ( '404' === $type ) :
            ?><span class="elementskey-hf-badge elementskey-hf-badge-404">&#9888; <?php esc_html_e( '404 Page', 'elementskey' ); ?></span><?php
        elseif ( 'loop' === $type || 'loop-item' === $type ) :
            ?><span class="elementskey-hf-badge elementskey-hf-badge-loop">&#128260; <?php esc_html_e( 'Loop', 'elementskey' ); ?></span><?php
        elseif ( 'section' === $type ) :
            ?><span class="elementskey-hf-badge elementskey-hf-badge-section">&#9638; <?php esc_html_e( 'Section', 'elementskey' ); ?></span><?php
        else :
            ?><em><?php esc_html_e( 'None', 'elementskey' ); ?></em><?php
        endif;
    }

    private function get_conditions_label( $conditions ) {
        if ( empty( $conditions ) || ! is_array( $conditions ) ) {
            return __( 'All Website', 'elementskey' );
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
        if ( 'elementskey_header_footer' !== $post->post_type ) {
            return $actions;
        }

        $new_actions = [];
        $post_type_object = get_post_type_object( 'elementskey_header_footer' );

        if ( current_user_can( 'edit_post', $post->ID ) ) {
            $new_actions['edit_with_elementor'] = sprintf(
                '<a href="%s">%s</a>',
                esc_url( add_query_arg( [ 'action' => 'elementor' ], admin_url( 'post.php?post=' . $post->ID ) ) ),
                esc_html__( 'Edit with Elementor', 'elementskey' )
            );
        }

        if ( current_user_can( 'delete_post', $post->ID ) ) {
            $new_actions['trash'] = sprintf(
                '<a href="%s" class="submitdelete">%s</a>',
                get_delete_post_link( $post->ID ),
                esc_html__( 'Trash', 'elementskey' )
            );
        }

        return $new_actions;
    }

    public function add_bulk_actions( $actions ) {
        unset( $actions['edit'] );
        $actions['trash']       = __( 'Move to Trash', 'elementskey' );
        $actions['activate']    = __( 'Activate', 'elementskey' );
        $actions['deactivate']  = __( 'Deactivate', 'elementskey' );
        return $actions;
    }
}
