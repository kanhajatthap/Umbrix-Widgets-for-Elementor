<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */
namespace ElementsKey\Framework\Elementor;

defined( 'ABSPATH' ) || exit;

abstract class ThemeBuilderDocument extends \Elementor\Core\DocumentTypes\PageBase {

    public static function get_properties() {
        $properties = parent::get_properties();

        $properties['admin_tab_group']          = 'theme';
        $properties['support_kit']              = false;
        $properties['show_in_library']          = true;
        $properties['support_wp_page']          = false;
        $properties['support_wp_page_templates'] = false;
        $properties['cpt']                      = [ static::get_cpt() ];

        return $properties;
    }

    abstract protected static function get_cpt();

    public function get_container_classes() {
        return 'elementskey-theme-builder';
    }

    public function print_content() {
        if ( \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
            echo '<style>.elementor-editor-active .elementskey-theme-builder { margin:0!important;padding:0!important; }</style>';
        }

        parent::print_content();
    }
}
