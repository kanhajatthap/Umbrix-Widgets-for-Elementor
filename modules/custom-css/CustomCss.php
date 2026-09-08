<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */
namespace ElementsKey\Modules\CustomCss;

defined( 'ABSPATH' ) || exit;

/**
 * Adds Custom CSS controls at every level:
 * - Page / Document settings (Advanced tab)
 * - Section (Advanced tab)
 * - Container (Advanced tab)
 * - Widget (Advanced tab)
 *
 * CSS is injected via inline <style> tags on the frontend, scoped to each
 * element's unique selector.
 */
class CustomCss {

    const PAGE_META_KEY = 'elementskey_custom_css';

    private static $instance = null;

    /** Collect CSS during render so we can output once. */
    private $collected_css = [];

    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // Page-level controls.
        add_action( 'elementor/documents/register_controls', [ $this, 'register_page_controls' ] );
        add_action( 'elementor/document/after_save', [ $this, 'save_page_meta' ], 10, 2 );

        // Section / Container / Widget controls.
        add_action( 'elementor/element/after_section_end', [ $this, 'register_element_controls' ], 10, 3 );

        // Frontend output – Sections.
        add_action( 'elementor/frontend/section/after_render', [ $this, 'render_element_css' ] );

        // Frontend output – Containers.
        add_action( 'elementor/frontend/container/after_render', [ $this, 'render_element_css' ] );

        // Frontend output – Widgets.
        add_filter( 'elementor/widget/render_content', [ $this, 'render_widget_css' ], 10, 2 );

        // Frontend output – Page-level CSS.
        add_action( 'wp_head', [ $this, 'output_page_css' ], 999 );
    }

    /* ------------------------------------------------------------------ */
    /*  PAGE-LEVEL CONTROLS                                               */
    /* ------------------------------------------------------------------ */

    public function register_page_controls( $document ) {
        if ( ! $document instanceof \Elementor\Core\Base\Document ) {
            return;
        }

        $document->start_controls_section(
            'elementskey_custom_css_page',
            [
                'label' => __( 'Custom CSS', 'elementskey' ) . ' <span style="background:#9b0dff;color:#fff;padding:2px 8px;border-radius:3px;font-size:10px;font-weight:700;vertical-align:middle;margin-left:6px;">ElementsKey</span>',
                'tab'   => \Elementor\Controls_Manager::TAB_ADVANCED,
            ]
        );

        $document->add_control(
            'elementskey_custom_css_page_info',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw'  => '<div style="padding:10px 12px;background:#f0edff;border-radius:4px;margin-bottom:4px;color:#5a3e8a;font-size:12px;line-height:1.6;">' . esc_html__( 'Add custom CSS for this page. It will only apply to this page.', 'elementskey' ) . '</div>',
            ]
        );

        $document->add_control(
            'elementskey_custom_css',
            [
                'label'       => __( 'Your CSS', 'elementskey' ),
                'type'        => \Elementor\Controls_Manager::CODE,
                'language'    => 'css',
                'placeholder' => '/* ' . __( 'Your custom CSS', 'elementskey' ) . ' */',
            ]
        );

        $document->end_controls_section();
    }

    /* ------------------------------------------------------------------ */
    /*  SECTION / CONTAINER / WIDGET CONTROLS                             */
    /* ------------------------------------------------------------------ */

    /**
     * Hook: elementor/element/after_section_end
     *
     * Fires after every section in the editor panel. We piggyback on the
     * last Advanced sub-section (_section_responsive) to inject our
     * Custom CSS section right after it.
     */
    public function register_element_controls( $element, $section_id, $args ) {
        if ( '_section_responsive' !== $section_id ) {
            return;
        }

        $el_type = $element->get_type(); // section | container | widget

        if ( ! in_array( $el_type, [ 'section', 'container', 'widget' ], true ) ) {
            return;
        }

        $id = $element->get_id();

        $element->start_controls_section(
            'elementskey_custom_css_' . $id,
            [
                'label' => __( 'Custom CSS', 'elementskey' ) . ' <span style="background:#9b0dff;color:#fff;padding:2px 8px;border-radius:3px;font-size:10px;font-weight:700;vertical-align:middle;margin-left:6px;">ElementsKey</span>',
                'tab'   => \Elementor\Controls_Manager::TAB_ADVANCED,
            ]
        );

        $element->add_control(
            'elementskey_css_info_' . $id,
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw'  => '<div style="padding:10px 12px;background:#f0edff;border-radius:4px;margin-bottom:4px;color:#5a3e8a;font-size:12px;line-height:1.6;">' . esc_html__( 'Add custom CSS for this element.', 'elementskey' ) . '</div>',
            ]
        );

        $element->add_control(
            'elementskey_custom_css',
            [
                'label'       => __( 'Your CSS', 'elementskey' ),
                'type'        => \Elementor\Controls_Manager::CODE,
                'language'    => 'css',
                'placeholder' => '/* ' . __( 'Your custom CSS', 'elementskey' ) . ' */',
            ]
        );

        $element->end_controls_section();
    }

    /* ------------------------------------------------------------------ */
    /*  SAVE – Page-level                                                 */
    /* ------------------------------------------------------------------ */

    public function save_page_meta( $document, $data ) {
        if ( ! $document ) {
            return;
        }

        $post_id = $document->get_main_id();
        if ( ! $post_id ) {
            return;
        }

        $settings = isset( $data['settings'] ) ? $data['settings'] : [];
        $css      = isset( $settings['elementskey_custom_css'] )
            ? sanitize_textarea_field( wp_unslash( $settings['elementskey_custom_css'] ) )
            : '';

        update_post_meta( $post_id, self::PAGE_META_KEY, $css );
    }

    /* ------------------------------------------------------------------ */
    /*  FRONTEND OUTPUT – Sections & Containers                           */
    /* ------------------------------------------------------------------ */

    /**
     * Fires after a section or container is rendered on the frontend.
     *
     * @param \Elementor\Element_Base $element The rendered element.
     */
    public function render_element_css( $element ) {
        $settings = $element->get_settings_for_display();
        $css      = isset( $settings['elementskey_custom_css'] )
            ? sanitize_textarea_field( wp_unslash( $settings['elementskey_custom_css'] ) )
            : '';

        if ( empty( trim( $css ) ) ) {
            return;
        }

        echo '<style id="elementskey-css-' . esc_attr( $element->get_id() ) . '">';
        echo '/* ElementsKey Custom CSS */' . "\n";
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo wp_strip_all_tags( $css ) . "\n";
        echo '</style>' . "\n";
    }

    /* ------------------------------------------------------------------ */
    /*  FRONTEND OUTPUT – Widgets                                         */
    /* ------------------------------------------------------------------ */

    /**
     * Filter: elementor/widget/render_content
     *
     * Appends an inline <style> after the widget's rendered HTML.
     */
    public function render_widget_css( $content, $widget ) {
        $settings = $widget->get_settings_for_display();
        $css      = isset( $settings['elementskey_custom_css'] )
            ? sanitize_textarea_field( wp_unslash( $settings['elementskey_custom_css'] ) )
            : '';

        if ( empty( trim( $css ) ) ) {
            return $content;
        }

        $style = '<style id="elementskey-css-' . esc_attr( $widget->get_id() ) . '">';
        $style .= '/* ElementsKey Custom CSS */' . "\n";
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        $style .= wp_strip_all_tags( $css ) . "\n";
        $style .= '</style>' . "\n";

        return $content . $style;
    }

    /* ------------------------------------------------------------------ */
    /*  FRONTEND OUTPUT – Page-level CSS                                  */
    /* ------------------------------------------------------------------ */

    public function output_page_css() {
        if ( is_admin() ) {
            return;
        }

        $post_id = get_the_ID();
        if ( ! $post_id ) {
            return;
        }

        $css = get_post_meta( $post_id, self::PAGE_META_KEY, true );
        if ( empty( trim( $css ) ) ) {
            return;
        }

        echo '<style id="elementskey-page-css">' . "\n";
        echo '/* ElementsKey Page Custom CSS - Post #' . esc_attr( $post_id ) . " */\n";
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo wp_strip_all_tags( $css ) . "\n";
        echo '</style>' . "\n";
    }
}
