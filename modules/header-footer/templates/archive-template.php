<?php
/**
 * Archive template override used by the Theme Builder.
 * Keeps the theme chrome (header/footer) and replaces only the archive body (loop).
 *
 * @package BDEA\Modules\HeaderFooter
 */

defined( 'ABSPATH' ) || exit;

get_header();

echo '<div class="bdea-hf-archive">';
do_action( 'bdea_hf_render_archive' );
echo '</div>';

get_footer();