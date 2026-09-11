<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */
namespace ElementsKey\Modules\HeaderFooter;

use ElementsKey\Framework\Conditions\ConditionManager;

defined( 'ABSPATH' ) || exit;

class MetaBox {

    private $condition_manager;

    public function __construct( ConditionManager $condition_manager ) {
        $this->condition_manager = $condition_manager;
        add_action( 'add_meta_boxes', [ $this, 'register' ] );
        add_action( 'save_post', [ $this, 'save' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_assets' ] );
    }

    public function enqueue_assets( $hook ) {
        if ( ! in_array( $hook, [ 'post.php', 'post-new.php' ], true ) ) {
            return;
        }

        if ( 'builder' !== get_post_type() ) {
            return;
        }

        $js_file = plugin_dir_path( __FILE__ ) . 'assets/js/admin.js';

        if ( file_exists( $js_file ) ) {
            wp_enqueue_script(
                'elementskey-hf-admin',
                plugin_dir_url( __FILE__ ) . 'assets/js/admin.js',
                [ 'jquery' ],
                ELEMENTSKEY_VERSION,
                true
            );

            wp_localize_script(
                'elementskey-hf-admin',
                'elementskeyHFData',
                [
                    'conditions'   => $this->condition_manager->get_conditions_grouped(),
                    'nextIndex'    => $this->get_next_condition_index(),
                    'strings'      => [
                        'include' => __( 'Include', 'elementskey' ),
                        'exclude' => __( 'Exclude', 'elementskey' ), // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude -- UI string, not a query argument.
                    ],
                ]
            );
        }
    }

    public function register() {
        add_meta_box(
            'elementskey_hf_template_type',
            __( 'Template Type', 'elementskey' ),
            [ $this, 'render_type_meta_box' ],
            'builder',
            'side',
            'default'
        );

        add_meta_box(
            'elementskey_hf_display_conditions',
            __( 'Display Conditions', 'elementskey' ),
            [ $this, 'render_conditions_meta_box' ],
            'builder',
            'normal',
            'high'
        );

        add_meta_box(
            'elementskey_hf_settings',
            __( 'Template Settings', 'elementskey' ),
            [ $this, 'render_settings_meta_box' ],
            'builder',
            'side',
            'default'
        );
    }

    public function render_type_meta_box( $post ) {
        wp_nonce_field( 'elementskey_hf_meta_box', 'elementskey_hf_meta_box_nonce' );

        $current = get_post_meta( $post->ID, '_elementskey_hf_template_type', true );
        ?>
        <p>
            <select name="elementskey_hf_template_type" id="elementskey-hf-template-type" style="width:100%;">
                <option value=""><?php esc_html_e( 'Select Template Type', 'elementskey' ); ?></option>
                <option value="header" <?php selected( $current, 'header' ); ?>><?php esc_html_e( 'Header', 'elementskey' ); ?></option>
                <option value="footer" <?php selected( $current, 'footer' ); ?>><?php esc_html_e( 'Footer', 'elementskey' ); ?></option>
                <option value="single" <?php selected( $current, 'single' ); ?>><?php esc_html_e( 'Single Post Template', 'elementskey' ); ?></option>
                <option value="archive" <?php selected( $current, 'archive' ); ?>><?php esc_html_e( 'Archive (Category / Tag / Loop)', 'elementskey' ); ?></option>
                <option value="404" <?php selected( $current, '404' ); ?>><?php esc_html_e( '404 Page', 'elementskey' ); ?></option>
                <option value="announcement" <?php selected( $current, 'announcement' ); ?>><?php esc_html_e( 'Announcement Bar', 'elementskey' ); ?></option>
                <option value="bottom_bar" <?php selected( $current, 'bottom_bar' ); ?>><?php esc_html_e( 'Bottom Bar', 'elementskey' ); ?></option>
                <option value="section" <?php selected( $current, 'section' ); ?>><?php esc_html_e( 'Section Template', 'elementskey' ); ?></option>
            </select>
        </p>
        <?php
    }

    public function render_conditions_meta_box( $post ) {
        $saved = get_post_meta( $post->ID, '_elementskey_hf_conditions', true );

        if ( ! is_array( $saved ) ) {
            $saved = [];
        }
        ?>
        <div class="elementskey-hf-conditions-wrap">
            <p><?php esc_html_e( 'Choose where this template should appear:', 'elementskey' ); ?></p>
            <table class="widefat elementskey-hf-conditions-table" id="elementskey-hf-conditions-table">
                <thead>
                    <tr>
                        <th style="width:80px;"><?php esc_html_e( 'Type', 'elementskey' ); ?></th>
                        <th><?php esc_html_e( 'Condition', 'elementskey' ); ?></th>
                        <th style="width:60px;"><?php esc_html_e( 'Actions', 'elementskey' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ( $saved as $index => $cond ) : ?>
                        <tr>
                            <td>
                                <select name="elementskey_hf_conditions[<?php echo esc_attr( $index ); ?>][type]" style="width:100%;">
                                    <option value="include" <?php selected( $cond['type'], 'include' ); ?>><?php esc_html_e( 'Include', 'elementskey' ); ?></option>
                                    <option value="exclude" <?php selected( $cond['type'], 'exclude' ); ?>><?php esc_html_e( 'Exclude', 'elementskey' ); ?></option>
                                </select>
                            </td>
                            <td>
                                <select name="elementskey_hf_conditions[<?php echo esc_attr( $index ); ?>][condition]" style="width:100%;">
                                    <?php
                                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Options are built from escaped term names.
                                    echo $this->render_condition_options( $cond['condition'] ?? '' );
                                    ?>
                                </select>
                            </td>
                            <td>
                                <button type="button" class="button elementskey-hf-remove-condition" style="background:#dc3232;color:#fff;border-color:#dc3232;cursor:pointer;"><?php esc_html_e( 'x', 'elementskey' ); ?></button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <p>
                <button type="button" class="button" id="elementskey-hf-add-condition"><?php esc_html_e( 'Add Condition', 'elementskey' ); ?></button>
            </p>
        </div>
        <?php
    }

    public function render_settings_meta_box( $post ) {
        $priority = get_post_meta( $post->ID, '_elementskey_hf_priority', true );

        if ( '' === $priority ) {
            $priority = 10;
        }

        $type = get_post_meta( $post->ID, '_elementskey_hf_template_type', true );
        ?>
        <p class="description">
            <?php esc_html_e( 'Sticky, transparent, logo switcher and schedule settings are managed inside the Elementor editor (Settings panel).', 'elementskey' ); ?>
        </p>
        <?php if ( in_array( $type, [ 'header', 'footer' ], true ) ) : ?>
        <p>
            <label>
                <input type="checkbox" name="elementskey_hf_disable_theme" value="yes" <?php checked( get_post_meta( $post->ID, '_elementskey_hf_disable_theme', true ), 'yes' ); ?> />
                <?php esc_html_e( 'Disable default theme header/footer', 'elementskey' ); ?>
            </label>
        </p>
        <?php endif; ?>
        <p>
            <label for="elementskey-hf-priority">
                <?php esc_html_e( 'Priority (lower value = higher priority):', 'elementskey' ); ?>
            </label>
            <input type="number" name="elementskey_hf_priority" id="elementskey-hf-priority"
                   value="<?php echo esc_attr( $priority ); ?>" min="0" max="999" style="width:100%;" />
        </p>
        <p class="description">
            <?php esc_html_e( 'Templates with lower priority numbers are checked first.', 'elementskey' ); ?>
        </p>
        <?php
    }

    public function save( $post_id ) {
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if ( ! isset( $_POST['elementskey_hf_meta_box_nonce'] )
            || ! wp_verify_nonce( wp_unslash( $_POST['elementskey_hf_meta_box_nonce'] ), 'elementskey_hf_meta_box' ) // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Nonce comparison does not require sanitization.
        ) {
            return;
        }

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        if ( 'builder' !== get_post_type( $post_id ) ) {
            return;
        }

        if ( isset( $_POST['elementskey_hf_template_type'] ) ) {
            $type = sanitize_key( $_POST['elementskey_hf_template_type'] );

            if ( in_array( $type, [ 'header', 'footer', 'single', 'archive', '404', 'announcement', 'bottom_bar', 'section' ], true ) ) {
                update_post_meta( $post_id, '_elementskey_hf_template_type', $type );
            } else {
                delete_post_meta( $post_id, '_elementskey_hf_template_type' );
            }
        }

        if ( isset( $_POST['elementskey_hf_conditions'] ) && is_array( $_POST['elementskey_hf_conditions'] ) ) {
            $conditions = [];

            foreach ( wp_unslash( $_POST['elementskey_hf_conditions'] ) as $cond ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Each value sanitized in the loop below.
                $type      = isset( $cond['type'] ) ? sanitize_key( $cond['type'] ) : 'include';
                $condition = isset( $cond['condition'] ) ? sanitize_text_field( $cond['condition'] ) : '';

                if ( $condition ) {
                    $conditions[] = [
                        'type'      => $type,
                        'condition' => $condition,
                    ];
                }
            }

            update_post_meta( $post_id, '_elementskey_hf_conditions', $conditions );
        }

        if ( isset( $_POST['elementskey_hf_priority'] ) ) {
            update_post_meta( $post_id, '_elementskey_hf_priority', absint( $_POST['elementskey_hf_priority'] ) );
        }

        $disable_theme = isset( $_POST['elementskey_hf_disable_theme'] ) ? sanitize_key( $_POST['elementskey_hf_disable_theme'] ) : '';
        $this->save_yes_no_meta( $post_id, '_elementskey_hf_disable_theme', $disable_theme );
    }

    private function save_yes_no_meta( $post_id, $key, $value ) {
        if ( 'yes' === $value ) {
            update_post_meta( $post_id, $key, 'yes' );
        } else {
            delete_post_meta( $post_id, $key );
        }
    }

    private function render_condition_options( $selected ) {
        $grouped = $this->condition_manager->get_conditions_grouped();
        $html    = '';

        foreach ( $grouped as $group => $conditions ) {
            $html .= '<optgroup label="' . esc_attr( $group ) . '">';

            foreach ( $conditions as $id => $label ) {
                $html .= '<option value="' . esc_attr( $id ) . '" ' . selected( $selected, $id, false ) . '>'
                        . esc_html( $label ) . '</option>';
            }

            $html .= '</optgroup>';
        }

        return $html;
    }

    private function get_next_condition_index() {
        global $post;

        if ( ! $post || 'builder' !== $post->post_type ) {
            return 0;
        }

        $saved = get_post_meta( $post->ID, '_elementskey_hf_conditions', true );

        if ( is_array( $saved ) ) {
            return count( $saved );
        }

        return 0;
    }
}
