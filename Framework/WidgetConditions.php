<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */
namespace ElementsKey\Framework;

defined( 'ABSPATH' ) || exit;

use ElementsKey\Framework\Conditions\ConditionManager;

/**
 * Elementor Pro style per-widget display conditions.
 *
 * Adds a "Display Conditions" section to every Elementor widget and
 * hides the widget on the frontend when the configured rules do not match.
 */
class WidgetConditions {

    private $condition_manager;

    private static $instance = null;

    public static function instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct() {
        $this->condition_manager = new ConditionManager();

        add_action( 'elementor/element/common/_section_style/after_section_end', [ $this, 'register_controls' ], 10, 2 );
        add_filter( 'elementor/widget/render_content', [ $this, 'maybe_hide_widget' ], 10, 2 );
    }

    public function register_controls( $element, $args ) {
        static $processed = [];

        if ( ! $element instanceof \Elementor\Widget_Base ) {
            return;
        }

        $name = $element->get_name();

        if ( isset( $processed[ $name ] ) ) {
            return;
        }

        $processed[ $name ] = true;

        $element->start_controls_section(
            'elementskey_widget_conditions_section',
            [
                'label' => 'Display Conditions',
                'tab'   => \Elementor\Controls_Manager::TAB_ADVANCED,
            ]
        );

        $element->add_control(
            'elementskey_widget_conditions_enable',
            [
                'label'        => 'Enable Display Conditions',
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'default'      => '',
                'return_value' => 'yes',
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'condition_type',
            [
                'label'   => 'Type',
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'include',
                'options' => [
                    'include' => 'Include',
                    'exclude' => 'Exclude', // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude -- Control option, not a query argument.
                ],
            ]
        );

        $repeater->add_control(
            'condition',
            [
                'label'   => 'Condition',
                'type'    => \Elementor\Controls_Manager::SELECT2,
                'options' => $this->condition_manager->get_conditions_grouped(),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'custom_url',
            [
                'label'       => 'URL',
                'type'        => \Elementor\Controls_Manager::TEXT,
                'placeholder' => '/about-us or /blog/*',
                'description' => 'Relative URL path. Use * as a wildcard.',
                'condition'   => [
                    'condition' => 'custom_url',
                ],
            ]
        );

        $element->add_control(
            'elementskey_widget_conditions',
            [
                'label'       => 'Conditions',
                'type'        => \Elementor\Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'title_field' => '{{{ condition_type === "exclude" ? "Exclude" : "Include" }}}: {{{ condition }}}',
                'condition'   => [
                    'elementskey_widget_conditions_enable' => 'yes',
                ],
            ]
        );

        $element->end_controls_section();
    }

    public function maybe_hide_widget( $content, $widget ) {
        $settings = $widget->get_settings();

        if ( empty( $settings['elementskey_widget_conditions_enable'] ) || 'yes' !== $settings['elementskey_widget_conditions_enable'] ) {
            return $content;
        }

        $rows = ! empty( $settings['elementskey_widget_conditions'] ) ? $settings['elementskey_widget_conditions'] : [];

        $conditions = [];

        foreach ( $rows as $row ) {
            $type      = isset( $row['condition_type'] ) ? $row['condition_type'] : 'include';
            $condition = isset( $row['condition'] ) ? $row['condition'] : '';

            if ( 'custom_url' === $condition && isset( $row['custom_url'] ) && '' !== trim( $row['custom_url'] ) ) {
                $condition = 'custom_url:' . trim( $row['custom_url'] );
            }

            if ( $condition ) {
                $conditions[] = [
                    'type'      => $type,
                    'condition' => $condition,
                ];
            }
        }

        if ( empty( $conditions ) ) {
            return $content;
        }

        if ( ! $this->condition_manager->evaluate( $conditions ) ) {
            if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                return '<div style="padding:14px;border:1px dashed #c2cbd2;background:#fbf8f1;color:#917c2f;font-size:13px;text-align:center;">'
                        . esc_html__( 'This widget is hidden by its Display Conditions.', 'elementskey' )
                        . '</div>';
            }

            return '';
        }

        return $content;
    }
}
