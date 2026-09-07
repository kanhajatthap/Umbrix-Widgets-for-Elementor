<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */
/**
 * Archive template override used by the Theme Builder.
 * Keeps the theme chrome (header/footer) and replaces only the archive body (loop).
 *
 * @package ElementsKey\Modules\HeaderFooter
 */

defined( 'ABSPATH' ) || exit;

get_header();

echo '<div class="elementskey-hf-archive">';
do_action( 'elementskey_hf_render_archive' );
echo '</div>';

get_footer();