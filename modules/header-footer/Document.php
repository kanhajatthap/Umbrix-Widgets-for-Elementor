<?php
namespace BDEA\Modules\HeaderFooter;

defined( 'ABSPATH' ) || exit;

class Document extends \BDEA\Framework\Elementor\ThemeBuilderDocument {

    public function get_name() {
        return 'bdea-hf-document';
    }

    public static function get_title() {
        return __( 'Header/Footer Template', 'bdea' );
    }

    protected static function get_cpt() {
        return 'bdea_header_footer';
    }
}
