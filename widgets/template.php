<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class ELEMENTSKEY_Template_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'elementskey_template';
    }

    public function get_title() {
        return 'Template';
    }

    public function get_icon() {
        return 'eicon-document-file';
    }

    public function get_categories() {
        return [ 'elementskey-elements' ];
    }

    public function get_style_depends() {
        return [ 'elementskey-content-style' ];
    }

    protected function get_templates() {
        static $templates = null;

        if ( null !== $templates ) {
            return $templates;
        }

        $templates = [];

        $args = [
            'post_type'      => 'elementor_library',
            'post_status'    => 'publish',
            'posts_per_page' => 100,
            'orderby'        => 'title',
            'order'          => 'ASC',
        ];

        $items = get_posts( $args );

        foreach ( $items as $item ) {
            $type = get_post_meta( $item->ID, '_elementor_template_type', true );
            $templates[ $item->ID ] = $item->post_title . ( $type ? ' (' . $type . ')' : '' );
        }

        return $templates;
    }

    protected function register_controls() {

        $this->start_controls_section(
            'elementskey_template_section',
            [
                'label' => __( 'Template', 'elementskey' ),
            ]
        );

        $this->add_control(
            'template_id',
            [
                'label' => __( 'Select Template', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $this->get_templates(),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_template_style_section',
            [
                'label' => __( 'Style', 'elementskey' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $template_id = ! empty( $settings['template_id'] ) ? absint( $settings['template_id'] ) : 0;

        if ( ! $template_id || 'publish' !== get_post_status( $template_id ) ) {
            ?>
            <div class="elementskey-loop-grid-empty"><?php esc_html_e( 'Select a template to display.', 'elementskey' ); ?></div>
            <?php
            return;
        }

        $content = \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $template_id, true );
        ?>
        <div class="elementskey-template-widget">
            <?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Elementor template content is rendered HTML. ?>
        </div>
        <?php
    }
}
