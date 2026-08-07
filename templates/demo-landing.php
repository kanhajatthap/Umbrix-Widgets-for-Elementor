<?php
/**
 * Template Name: BDEA Widget Demo Landing
 * Description: Wrapper for Elementor widget demo page
 */
if ( ! defined( 'ABSPATH' ) ) exit;

// Let Elementor and the default theme handle everything
get_header();

echo '<div id="bdea-widget-demo">';
the_content();
echo '</div>';

get_footer();
