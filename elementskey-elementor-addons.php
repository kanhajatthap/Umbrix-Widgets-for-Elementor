<?php
/**
 * Plugin Name: ElementsKey Addons for Elementor
 * Plugin URI: https://github.com/kanhajatthap/ElementsKey-Elementor-Addons
 * Description: Supercharge Elementor with 73 lightweight widgets and a Theme Builder for custom headers, footers, and archives.
 * Version: 1.1.0
 * Author: Kanha Jatthap
 * Author URI: https://github.com/kanhajatthap
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Requires Plugins: elementor
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: elementskey
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Define constants
 */
define( 'ELEMENTSKEY_PATH', plugin_dir_path( __FILE__ ) );
define( 'ELEMENTSKEY_URL', plugin_dir_url( __FILE__ ) );
define( 'ELEMENTSKEY_VERSION', '1.1.0' );

/**
 * PSR-4 autoloader for ElementsKey classes.
 */
spl_autoload_register( function ( $class ) {
    $prefix = 'ElementsKey\\';
    $len    = strlen( $prefix );

    if ( strncmp( $prefix, $class, $len ) !== 0 ) {
        return;
    }

    $relative_class = substr( $class, $len );
    $file           = ELEMENTSKEY_PATH . str_replace( '\\', DIRECTORY_SEPARATOR, $relative_class ) . '.php';

    if ( file_exists( $file ) ) {
        require_once $file;
    }
} );

/**
 * Check if Elementor is installed & activated
 */
function elementskey_check_elementor_loaded() {
    if ( ! did_action( 'elementor/loaded' ) ) {
        add_action( 'admin_notices', 'elementskey_elementor_missing_notice' );
        return false;
    }
    return true;
}

/**
 * Admin Notice if Elementor is missing
 */
function elementskey_elementor_missing_notice() {
    ?>
    <div class="notice notice-warning is-dismissible">
        <p><strong>ElementsKey Addons for Elementor</strong> <?php esc_html_e( 'requires Elementor to be installed and activated.', 'elementskey' ); ?></p>
    </div>
    <?php
}

/**
 * Register custom category
 */
function elementskey_register_category( $elements_manager ) {
    $elements_manager->add_category(
        'elementskey-elements',
        [
            'title' => 'ElementsKey Elements',
            'icon'  => 'fa fa-plug',
        ]
    );
}
add_action( 'elementor/elements/categories_registered', 'elementskey_register_category' );

/**
 * Add common Elementor-style presentation controls to every ElementsKey widget.
 * Widget-specific controls remain responsible for styling their inner elements.
 */
function elementskey_register_common_widget_style_controls( $element, $section_id, $args ) {
    if ( ! $element instanceof \Elementor\Widget_Base || empty( $args['tab'] ) || \Elementor\Controls_Manager::TAB_STYLE !== $args['tab'] ) {
        return;
    }

    static $added = [];
    $element_id = spl_object_id( $element );

    if ( isset( $added[ $element_id ] ) ) {
        return;
    }

    if ( 0 !== strpos( $element->get_name(), 'elementskey_' ) ) {
        return;
    }

    $added[ $element_id ] = true;

    $element->start_controls_section(
        'elementskey_common_style',
        [
            'label' => __( 'Widget Container', 'elementskey' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ]
    );

    $element->add_control(
        'elementskey_container_background',
        [
            'label'     => __( 'Background Color', 'elementskey' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}}' => 'background-color: {{VALUE}};',
            ],
        ]
    );

    $element->add_control(
        'elementskey_container_text_color',
        [
            'label'     => __( 'Text Color', 'elementskey' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}}' => 'color: {{VALUE}};',
            ],
        ]
    );

    $element->add_group_control(
        \Elementor\Group_Control_Border::get_type(),
        [
            'name'     => 'elementskey_container_border',
            'selector' => '{{WRAPPER}}',
        ]
    );

    $element->add_responsive_control(
        'elementskey_container_border_radius',
        [
            'label'      => __( 'Border Radius', 'elementskey' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%', 'em' ],
            'selectors'  => [
                '{{WRAPPER}}' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]
    );

    $element->add_group_control(
        \Elementor\Group_Control_Box_Shadow::get_type(),
        [
            'name'     => 'elementskey_container_box_shadow',
            'selector' => '{{WRAPPER}}',
        ]
    );

    $element->add_responsive_control(
        'elementskey_container_padding',
        [
            'label'      => __( 'Padding', 'elementskey' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%', 'em' ],
            'selectors'  => [
                '{{WRAPPER}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]
    );

    $element->end_controls_section();
}
add_action( 'elementor/element/after_section_end', 'elementskey_register_common_widget_style_controls', 10, 3 );

/**
 * Register Widgets
 */
function elementskey_get_default_widget_status() {
    return [
        'progress_bar' => 1,
        'data_table'  => 1,
        'carousel'    => 1,
        'feature_comparison_table' => 1,
        'share_it' => 1,
        'loop_grid' => 1,
        'loop_carousel' => 1,
        'off_canvas' => 1,
        'posts' => 1,
        'portfolio' => 1,
        'video' => 1,
        'button' => 1,
        'star_rating' => 1,
        'divider' => 1,
        'google_maps' => 1,
        'icon' => 1,
        'icon_box' => 1,
        'image_box' => 1,
        'basic_gallery' => 1,
        'image_carousel' => 1,
        'icon_list' => 1,
        'counter' => 1,
        'spacer' => 1,
        'testimonial' => 1,
        'tabs' => 1,
        'accordion' => 1,
        'social_icons' => 1,
        'soundcloud' => 1,
        'shortcode' => 1,
        'form' => 1,
        'login' => 1,
        'slides' => 1,
        'animated_headline' => 1,
        'hotspot' => 1,
        'price_list' => 1,
        'price_table' => 1,
        'flip_box' => 1,
        'call_to_action' => 1,
        'media_carousel' => 1,
        'testimonial_carousel' => 1,
        'reviews' => 1,
        'table_of_content' => 1,
        'countdown' => 1,
        'blockquote' => 1,
        'facebook_button' => 1,
        'facebook_comments' => 1,
        'facebook_embed' => 1,
        'facebook_page' => 1,
        'template' => 1,
        'lottie_widget' => 1,
        'code_highlight' => 1,
        'video_playlist' => 1,
        'progress_tracker' => 1,
        'menu_widget' => 1,
        'taxonomy_filter' => 1,
        'link_in_bio' => 1,
        'site_logo' => 1,
        'site_title' => 1,
        'page_title' => 1,
        'wp_menu' => 1,
        'sitemap' => 1,
        'post_title' => 1,
        'post_excerpt' => 1,
        'featured_image' => 1,
        'post_content' => 1,
        'author_box' => 1,
        'post_comments' => 1,
        'post_navigation' => 1,
        'post_info' => 1,
        'search' => 1,
        'breadcrumbs' => 1,
        'archive_title' => 1,
        'archive_posts' => 1,
    ];
}

function elementskey_get_widget_status() {
    $status = get_option( 'elementskey_widget_status', [] );
    return wp_parse_args( $status, elementskey_get_default_widget_status() );
}

function elementskey_get_widget_definitions() {
    return [
        'progress_bar' => [
            'label' => 'Progress Bar',
            'class' => 'ELEMENTSKEY_Progress_Bar_Widget',
        ],
        'data_table'  => [
            'label' => 'Data Table',
            'class' => 'ELEMENTSKEY_Data_Table_Widget',
        ],
        'carousel'    => [
            'label' => 'Carousel',
            'class' => 'ELEMENTSKEY_Swiper_Carousel_Widget',
        ],
        'feature_comparison_table' => [
            'label' => 'Feature Comparison Table',
            'class' => 'ELEMENTSKEY_Feature_Comparison_Table_Widget',
        ],
        'share_it' => [
            'label' => 'Share It',
            'class' => 'ELEMENTSKEY_Share_It_Widget',
        ],
        'loop_grid' => [
            'label' => 'Loop Grid',
            'class' => 'ELEMENTSKEY_Loop_Grid_Widget',
        ],
        'loop_carousel' => [
            'label' => 'Loop Carousel',
            'class' => 'ELEMENTSKEY_Loop_Carousel_Widget',
        ],
        'off_canvas' => [
            'label' => 'Off-Canvas',
            'class' => 'ELEMENTSKEY_Off_Canvas_Widget',
        ],
        'posts' => [
            'label' => 'Posts',
            'class' => 'ELEMENTSKEY_Posts_Widget',
        ],
        'portfolio' => [
            'label' => 'Portfolio',
            'class' => 'ELEMENTSKEY_Portfolio_Widget',
        ],
        'video' => [
            'label' => 'Video',
            'class' => 'ELEMENTSKEY_Video_Widget',
        ],
        'button' => [
            'label' => 'Button',
            'class' => 'ELEMENTSKEY_Button_Widget',
        ],
        'star_rating' => [
            'label' => 'Star Rating',
            'class' => 'ELEMENTSKEY_Star_Rating_Widget',
        ],
        'divider' => [
            'label' => 'Divider',
            'class' => 'ELEMENTSKEY_Divider_Widget',
        ],
        'google_maps' => [
            'label' => 'Google Maps',
            'class' => 'ELEMENTSKEY_Google_Maps_Widget',
        ],
        'icon' => [
            'label' => 'Icon',
            'class' => 'ELEMENTSKEY_Icon_Widget',
        ],
        'icon_box' => [
            'label' => 'Icon Box',
            'class' => 'ELEMENTSKEY_Icon_Box_Widget',
        ],
        'image_box' => [
            'label' => 'Image Box',
            'class' => 'ELEMENTSKEY_Image_Box_Widget',
        ],
        'basic_gallery' => [
            'label' => 'Basic Gallery',
            'class' => 'ELEMENTSKEY_Basic_Gallery_Widget',
        ],
        'image_carousel' => [
            'label' => 'Image Carousel',
            'class' => 'ELEMENTSKEY_Image_Carousel_Widget',
        ],
        'icon_list' => [
            'label' => 'Icon List',
            'class' => 'ELEMENTSKEY_Icon_List_Widget',
        ],
        'counter' => [
            'label' => 'Counter',
            'class' => 'ELEMENTSKEY_Counter_Widget',
        ],
        'spacer' => [
            'label' => 'Spacer',
            'class' => 'ELEMENTSKEY_Spacer_Widget',
        ],
        'testimonial' => [
            'label' => 'Testimonial',
            'class' => 'ELEMENTSKEY_Testimonial_Widget',
        ],
        'tabs' => [
            'label' => 'Tabs',
            'class' => 'ELEMENTSKEY_Tabs_Widget',
        ],
        'accordion' => [
            'label' => 'Accordion',
            'class' => 'ELEMENTSKEY_Accordion_Widget',
        ],
        'social_icons' => [
            'label' => 'Social Icons',
            'class' => 'ELEMENTSKEY_Social_Icons_Widget',
        ],
        'soundcloud' => [
            'label' => 'SoundCloud',
            'class' => 'ELEMENTSKEY_Sound_Cloud_Widget',
        ],
        'shortcode' => [
            'label' => 'Shortcode',
            'class' => 'ELEMENTSKEY_Shortcode_Widget',
        ],
        'form' => [
            'label' => 'Form',
            'class' => 'ELEMENTSKEY_Form_Widget',
        ],
        'login' => [
            'label' => 'Login',
            'class' => 'ELEMENTSKEY_Login_Widget',
        ],
        'slides' => [
            'label' => 'Slides',
            'class' => 'ELEMENTSKEY_Slides_Widget',
        ],
        'animated_headline' => [
            'label' => 'Animated Headline',
            'class' => 'ELEMENTSKEY_Animated_Headline_Widget',
        ],
        'hotspot' => [
            'label' => 'Hotspot',
            'class' => 'ELEMENTSKEY_Hotspot_Widget',
        ],
        'price_list' => [
            'label' => 'Price List',
            'class' => 'ELEMENTSKEY_Price_List_Widget',
        ],
        'price_table' => [
            'label' => 'Price Table',
            'class' => 'ELEMENTSKEY_Price_Table_Widget',
        ],
        'flip_box' => [
            'label' => 'Flip Box',
            'class' => 'ELEMENTSKEY_Flip_Box_Widget',
        ],
        'call_to_action' => [
            'label' => 'Call to Action',
            'class' => 'ELEMENTSKEY_Call_To_Action_Widget',
        ],
        'media_carousel' => [
            'label' => 'Media Carousel',
            'class' => 'ELEMENTSKEY_Media_Carousel_Widget',
        ],
        'testimonial_carousel' => [
            'label' => 'Testimonial Carousel',
            'class' => 'ELEMENTSKEY_Testimonial_Carousel_Widget',
        ],
        'reviews' => [
            'label' => 'Reviews',
            'class' => 'ELEMENTSKEY_Reviews_Widget',
        ],
        'table_of_content' => [
            'label' => 'Table Of Content',
            'class' => 'ELEMENTSKEY_Table_Of_Content_Widget',
        ],
        'countdown' => [
            'label' => 'Countdown',
            'class' => 'ELEMENTSKEY_Countdown_Widget',
        ],
        'blockquote' => [
            'label' => 'Blockquote',
            'class' => 'ELEMENTSKEY_Blockquote_Widget',
        ],
        'facebook_button' => [
            'label' => 'Facebook Button',
            'class' => 'ELEMENTSKEY_Facebook_Button_Widget',
        ],
        'facebook_comments' => [
            'label' => 'Facebook Comments',
            'class' => 'ELEMENTSKEY_Facebook_Comments_Widget',
        ],
        'facebook_embed' => [
            'label' => 'Facebook Embed',
            'class' => 'ELEMENTSKEY_Facebook_Embed_Widget',
        ],
        'facebook_page' => [
            'label' => 'Facebook Page',
            'class' => 'ELEMENTSKEY_Facebook_Page_Widget',
        ],
        'template' => [
            'label' => 'Template',
            'class' => 'ELEMENTSKEY_Template_Widget',
        ],
        'lottie_widget' => [
            'label' => 'Lottie',
            'class' => 'ELEMENTSKEY_Lottie_Widget',
        ],
        'code_highlight' => [
            'label' => 'Code Highlight',
            'class' => 'ELEMENTSKEY_Code_Highlight_Widget',
        ],
        'video_playlist' => [
            'label' => 'Video Playlist',
            'class' => 'ELEMENTSKEY_Video_Playlist_Widget',
        ],
        'progress_tracker' => [
            'label' => 'Progress Tracker',
            'class' => 'ELEMENTSKEY_Progress_Tracker_Widget',
        ],
        'menu_widget' => [
            'label' => 'Menu',
            'class' => 'ELEMENTSKEY_Menu_Widget',
        ],
        'taxonomy_filter' => [
            'label' => 'Taxonomy Filter',
            'class' => 'ELEMENTSKEY_Taxonomy_Filter_Widget',
        ],
        'link_in_bio' => [
            'label' => 'Link in Bio',
            'class' => 'ELEMENTSKEY_Link_In_Bio_Widget',
        ],
        'site_logo' => [
            'label' => 'Site Logo',
            'class' => 'ELEMENTSKEY_Site_Logo_Widget',
        ],
        'site_title' => [
            'label' => 'Site Title',
            'class' => 'ELEMENTSKEY_Site_Title_Widget',
        ],
        'page_title' => [
            'label' => 'Page Title',
            'class' => 'ELEMENTSKEY_Page_Title_Widget',
        ],
        'wp_menu' => [
            'label' => 'WordPress Menu',
            'class' => 'ELEMENTSKEY_Wp_Menu_Widget',
        ],
        'sitemap' => [
            'label' => 'Sitemap',
            'class' => 'ELEMENTSKEY_Sitemap_Widget',
        ],
        'post_title' => [
            'label' => 'Post Title',
            'class' => 'ELEMENTSKEY_Post_Title_Widget',
        ],
        'post_excerpt' => [
            'label' => 'Post Excerpt',
            'class' => 'ELEMENTSKEY_Post_Excerpt_Widget',
        ],
        'featured_image' => [
            'label' => 'Featured Image',
            'class' => 'ELEMENTSKEY_Featured_Image_Widget',
        ],
        'post_content' => [
            'label' => 'Post Content',
            'class' => 'ELEMENTSKEY_Post_Content_Widget',
        ],
        'author_box' => [
            'label' => 'Author Box',
            'class' => 'ELEMENTSKEY_Author_Box_Widget',
        ],
        'post_comments' => [
            'label' => 'Post Comments',
            'class' => 'ELEMENTSKEY_Post_Comments_Widget',
        ],
        'post_navigation' => [
            'label' => 'Post Navigation',
            'class' => 'ELEMENTSKEY_Post_Navigation_Widget',
        ],
        'post_info' => [
            'label' => 'Post Info',
            'class' => 'ELEMENTSKEY_Post_Info_Widget',
        ],
        'search' => [
            'label' => 'Search',
            'class' => 'ELEMENTSKEY_Search_Widget',
        ],
        'breadcrumbs' => [
            'label' => 'Breadcrumbs',
            'class' => 'ELEMENTSKEY_Breadcrumbs_Widget',
        ],
        'archive_title' => [
            'label' => 'Archive Title',
            'class' => 'ELEMENTSKEY_Archive_Title_Widget',
        ],
        'archive_posts' => [
            'label' => 'Archive Posts',
            'class' => 'ELEMENTSKEY_Archive_Posts_Widget',
        ],
    ];
}

function elementskey_register_widgets( $widgets_manager ) {

    if ( ! elementskey_check_elementor_loaded() ) {
        return;
    }

    $widget_definitions = elementskey_get_widget_definitions();
    $widget_status      = elementskey_get_widget_status();

    // Include widget files.
    require_once( ELEMENTSKEY_PATH . 'widgets/progress-bar.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/data-table.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/carousel.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/feature-comparison-table.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/share-it.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/helpers.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/loop-grid.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/loop-carousel.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/off-canvas.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/posts.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/portfolio.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/video.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/button.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/star-rating.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/divider.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/google-maps.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/icon.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/icon-box.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/image-box.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/basic-gallery.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/image-carousel.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/icon-list.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/counter.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/spacer.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/testimonial.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/tabs.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/accordion.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/social-icons.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/soundcloud.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/shortcode.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/form.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/login.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/slides.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/animated-headline.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/hotspot.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/price-list.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/price-table.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/flip-box.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/call-to-action.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/media-carousel.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/testimonial-carousel.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/reviews.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/table-of-content.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/countdown.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/blockquote.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/facebook-button.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/facebook-comments.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/facebook-embed.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/facebook-page.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/template.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/lottie-widget.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/code-highlight.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/video-playlist.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/progress-tracker.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/menu.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/taxonomy-filter.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/link-in-bio.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/site-logo.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/site-title.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/page-title.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/wp-menu.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/sitemap.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/post-title.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/post-excerpt.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/featured-image.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/post-content.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/author-box.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/post-comments.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/post-navigation.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/post-info.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/search.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/breadcrumbs.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/archive-title.php' );
    require_once( ELEMENTSKEY_PATH . 'widgets/archive-posts.php' );

    foreach ( $widget_definitions as $slug => $widget ) {
        if ( empty( $widget_status[ $slug ] ) ) {
            continue;
        }

        $widget_class = '\\' . $widget['class'];
        if ( class_exists( $widget_class ) ) {
            $widgets_manager->register( new $widget_class() );
        }
    }
}
add_action( 'elementor/widgets/register', 'elementskey_register_widgets' );

function elementskey_sanitize_widget_status( $input ) {
    $defaults = elementskey_get_default_widget_status();
    $output   = [];

    foreach ( $defaults as $key => $value ) {
        $output[ $key ] = ( is_array( $input ) && ! empty( $input[ $key ] ) ) ? 1 : 0;
    }

    return $output;
}

function elementskey_get_widget_descriptions() {
    return [
        'progress_bar' => 'Animated, customizable progress bars with label and percentage display.',
        'data_table' => 'Label-value rows with a titled card, alternating backgrounds and custom colors.',
        'feature_comparison_table' => 'Two-column feature vs checkmark comparison table with styled header.',
        'carousel' => 'Swiper-powered responsive carousel with slides, arrows and dots.',
        'share_it' => 'Share buttons for the current page with network toggles and copy-link support.',
        'loop_grid' => 'Responsive post grid with full query controls (post type, categories, tags, order).',
        'loop_carousel' => 'Swiper-powered carousel that pulls posts dynamically from any post type.',
        'off_canvas' => 'Slide-in panel with toggle button, overlay, close controls and template support.',
        'posts' => 'Classic posts listing with thumbnail, meta, excerpt and pagination.',
        'portfolio' => 'Filterable portfolio grid with category filter buttons.',
        'video' => 'Embed YouTube, Vimeo or self-hosted videos with responsive player.',
        'button' => 'Stylable button/CTA with icon, link options and full hover states.',
        'star_rating' => 'Display ratings with adjustable value, size and colors.',
        'divider' => 'Decorative divider with style, width, alignment and color controls.',
        'google_maps' => 'Embed Google Maps by address with zoom, height and width controls.',
        'icon' => 'Single icon with link, alignment, size, color and background controls.',
        'icon_box' => 'Icon with title, description and link in top or left layouts.',
        'image_box' => 'Image with title, description, link and full image style controls.',
        'basic_gallery' => 'Responsive image gallery grid with captions, links and column controls.',
        'image_carousel' => 'Swiper-powered image carousel with captions, links, arrows and dots.',
        'icon_list' => 'Bullet list with custom icons, links, alignment and styling.',
        'counter' => 'Animated number counter with prefix, suffix, duration and title.',
        'spacer' => 'Responsive vertical spacing helper with px or vh heights.',
        'testimonial' => 'Quote card with author, role, avatar, rating and full style controls.',
        'tabs' => 'Tabbed content with titles, WYSIWYG panes and top or left layouts.',
        'accordion' => 'Collapsible content items with WYSIWYG panes and full styling.',
        'social_icons' => 'Social network icons with links, colors, sizes and hover states.',
        'soundcloud' => 'Embed SoundCloud tracks with visual player and autoplay options.',
        'shortcode' => 'Render any WordPress shortcode inside Elementor.',
        'form' => 'Custom contact form with configurable fields and submit button.',
        'login' => 'Login form for logged-out users with logout option for logged-in users.',
        'slides' => 'Full-width Swiper slides with background image, content and buttons.',
        'animated_headline' => 'Headline with rotating words, animation speed and styling.',
        'hotspot' => 'Image with positioned tooltip hotspots and descriptions.',
        'price_list' => 'List of priced items with badges, descriptions and featured state.',
        'price_table' => 'Pricing table with features, price, period and CTA button.',
        'flip_box' => '3D flip card with front/back content, icon and button.',
        'call_to_action' => 'Banner CTA with background image, overlay, text and buttons.',
        'media_carousel' => 'Swiper carousel for images with captions, dots and arrows.',
        'testimonial_carousel' => 'Swiper carousel of testimonials with avatars and ratings.',
        'reviews' => 'Responsive review cards grid with stars, avatars and ratings.',
        'table_of_content' => 'Auto-generated table of contents from page headings.',
        'countdown' => 'Animated countdown timer to a target date and time.',
        'blockquote' => 'Styled quote block with author name and role.',
        'facebook_button' => 'Facebook Like/Recommend button with layouts and SDK.',
        'facebook_comments' => 'Facebook comments plugin embedded with SDK.',
        'facebook_embed' => 'Embed a single Facebook post or video.',
        'facebook_page' => 'Embed a Facebook page plugin box.',
        'template' => 'Embed any Elementor template inside another page.',
        'lottie' => 'Render Lottie JSON animations with loop, speed and size controls.',
        'code_highlight' => 'Code block with language, copy button and styling.',
        'video_playlist' => 'Video player with a clickable playlist of YouTube/Vimeo items.',
        'progress_tracker' => 'Animated linear progress bar with label and percentage.',
        'menu' => 'Display a WordPress navigation menu horizontally or vertically.',
        'taxonomy_filter' => 'Term filter buttons or dropdown for any taxonomy.',
        'link_in_bio' => 'Centered avatar, bio and vertical link buttons layout.',
        'site_logo' => 'Show the site custom logo with fallback to site name.',
        'site_title' => 'Show the site title linked to the homepage.',
        'page_title' => 'Display the current page title.',
        'wp_menu' => 'WordPress menu by menu or theme location with styling.',
        'sitemap' => 'List published posts of selected post types grouped by type.',
        'post_title' => 'Display the current post title with link option.',
        'post_excerpt' => 'Display the current post excerpt.',
        'featured_image' => 'Display the current post featured image with link option.',
        'post_content' => 'Render the full content of the current post.',
        'author_box' => 'Author card with avatar, name, bio and website.',
        'post_comments' => 'WordPress comments list and comment form.',
        'post_navigation' => 'Previous/next post navigation links.',
        'post_info' => 'Post meta row: author, date, categories, tags and comments.',
        'search' => 'Search form with styled input and button.',
        'breadcrumbs' => 'Breadcrumb trail with separators for pages and archives.',
        'archive_title' => 'Display the current archive title.',
        'archive_posts' => 'Grid listing of posts with pagination for archive contexts.',
    ];
}

/**
 * Auto-load modules.
 */
function elementskey_load_modules() {
    if ( ! elementskey_check_elementor_loaded() ) {
        return;
    }

    require_once ELEMENTSKEY_PATH . 'modules/builder/Module.php';
    \ElementsKey\Modules\HeaderFooter\Module::instance();

    require_once ELEMENTSKEY_PATH . 'Framework/WidgetConditions.php';
    \ElementsKey\Framework\WidgetConditions::instance();

    require_once ELEMENTSKEY_PATH . 'modules/custom-css/CustomCss.php';
    \ElementsKey\Modules\CustomCss\CustomCss::instance();
}
add_action( 'init', 'elementskey_load_modules', 15 );

/**
 * Handle ElementsKey Form widget submissions via admin-post.php.
 *
 * Registered outside the widget loader so it also runs on admin-post.php
 * requests, where Elementor never fires elementor/widgets/register.
 */
function elementskey_handle_form_submit() {
    $nonce = isset( $_POST['elementskey_form_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['elementskey_form_nonce'] ) ) : '';

    if ( ! wp_verify_nonce( $nonce, 'elementskey_form_submit' ) ) {
        wp_send_json_error( [ 'message' => __( 'Invalid form submission. Please refresh the page and try again.', 'elementskey' ) ], 400 );
    }

    $form_id = isset( $_POST['elementskey_form_id'] ) ? sanitize_text_field( wp_unslash( $_POST['elementskey_form_id'] ) ) : '';

    $email_to      = get_option( 'admin_email' );
    $email_subject = isset( $_POST['elementskey_email_subject'] ) ? sanitize_text_field( wp_unslash( $_POST['elementskey_email_subject'] ) ) : __( 'New Form Submission', 'elementskey' );

    $body_lines = [];
    foreach ( $_POST as $key => $value ) {
        if ( 0 !== strpos( $key, 'elementskey_field_' ) ) {
            continue;
        }

        $label = str_replace( 'elementskey_field_', '', $key );
        $label = ucwords( str_replace( [ '_', '-' ], ' ', $label ) );

        if ( is_array( $value ) ) {
            $value = implode( ', ', array_map( 'sanitize_text_field', wp_unslash( $value ) ) );
        } else {
            $value = sanitize_textarea_field( wp_unslash( $value ) );
        }

        $body_lines[] = $label . ': ' . $value;
    }

    if ( empty( $body_lines ) ) {
        wp_send_json_error( [ 'message' => __( 'The form does not contain any fields.', 'elementskey' ) ], 400 );
    }

    /* translators: %s: Form ID. */
    $body = implode( "\n\n", $body_lines ) . "\n\n---\n" . sprintf( __( 'Submitted via ElementsKey Form (ID: %s)', 'elementskey' ), $form_id ) . "\n";

    $sent = wp_mail(
        $email_to,
        $email_subject,
        $body,
        [ 'Content-Type: text/plain; charset=UTF-8' ]
    );

    if ( $sent ) {
        wp_send_json_success( [ 'message' => 'success' ] );
    }

    wp_send_json_error( [ 'message' => __( 'The message could not be sent. Please try again.', 'elementskey' ) ], 500 );
}
add_action( 'admin_post_nopriv_elementskey_form_submit', 'elementskey_handle_form_submit' );
add_action( 'admin_post_elementskey_form_submit', 'elementskey_handle_form_submit' );

/**
 * Handle Loop Grid "Load on Demand" / "Infinite Scroll" via admin-ajax.php.
 *
 * Registered outside the widget loader because admin-ajax requests never fire
 * elementor/widgets/register.
 */
function elementskey_handle_loop_load() {
    check_ajax_referer( 'elementskey_loop_load', 'nonce' );

    require_once ELEMENTSKEY_PATH . 'widgets/helpers.php';

    $page = isset( $_POST['page'] ) ? max( 1, absint( wp_unslash( $_POST['page'] ) ) ) : 1;

    $settings = [];
    if ( isset( $_POST['settings'] ) ) {
        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- Decoded settings are sanitized per-field by elementskey_widget_query_args().
        $settings = json_decode( wp_unslash( $_POST['settings'] ), true );
        if ( ! is_array( $settings ) ) {
            $settings = [];
        }
    }

    if ( ! empty( $settings['exclude_ids'] ) ) {
        $settings['exclude_ids'] = array_map( 'trim', explode( ',', (string) $settings['exclude_ids'] ) );
    }

    $query_args = elementskey_widget_query_args( $settings );

    if ( ! empty( $query_args['offset'] ) ) {
        $query_args['offset'] = $query_args['offset'] + ( $page - 1 ) * $query_args['posts_per_page'];
    } else {
        $query_args['paged'] = $page;
    }

    $query = new \WP_Query( $query_args );

    if ( ! $query->have_posts() ) {
        wp_send_json_success( [ 'html' => '', 'has_more' => false ] );
    }

    ob_start();
    elementskey_render_loop_items( $query, $settings );
    $html = ob_get_clean();

    wp_send_json_success( [ 'html' => $html, 'has_more' => $page < (int) $query->max_num_pages ] );
}
add_action( 'wp_ajax_elementskey_loop_load', 'elementskey_handle_loop_load' );
add_action( 'wp_ajax_nopriv_elementskey_loop_load', 'elementskey_handle_loop_load' );

/**
 * Create a new loop template from the Loop Grid / Loop Carousel widget (editor only).
 *
 * Follows Elementor Pro's flow: the template is always created with the
 * dedicated "loop-item" type, so it appears in the loop template dropdowns
 * and the library under its own type — never as header/footer/page.
 * Elementor core falls back to the "page" document when Pro is inactive,
 * so the template stays editable either way.
 */
function elementskey_create_loop_template() {
    check_ajax_referer( 'elementskey_editor', 'nonce' );

    if ( ! current_user_can( 'edit_posts' ) ) {
        wp_send_json_error( [ 'message' => __( 'You do not have permission to create templates.', 'elementskey' ) ], 403 );
    }

    $type = 'loop-item';

    $post_id = wp_insert_post( [
        'post_type'    => 'elementor_library',
        'post_status'  => 'publish',
        /* translators: %s: Date and time. */
        'post_title'   => sprintf( __( 'Loop Template %s', 'elementskey' ), gmdate( 'Y-m-d H:i' ) ),
    ] );

    if ( is_wp_error( $post_id ) ) {
        wp_send_json_error( [ 'message' => $post_id->get_error_message() ], 500 );
    }

    $container_id = substr( md5( 'elementskey-loop-' . $post_id . '-1' ), 0, 7 );

    update_post_meta( $post_id, '_elementor_template_type', $type );
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

    wp_send_json_success( [
        'id'       => $post_id,
        'edit_url' => admin_url( 'post.php?post=' . $post_id . '&action=elementor' ),
    ] );
}
add_action( 'wp_ajax_elementskey_create_loop_template', 'elementskey_create_loop_template' );

function elementskey_admin_menu() {
    add_menu_page(
        __( 'ElementsKey Settings', 'elementskey' ),
        __( 'ElementsKey', 'elementskey' ),
        'manage_options',
        'elementskey-settings',
        'elementskey_render_admin_page',
        'dashicons-screenoptions',
        60
    );
}
add_action( 'admin_menu', 'elementskey_admin_menu' );

function elementskey_admin_init() {
    register_setting( 'elementskey_options_group', 'elementskey_widget_status', 'elementskey_sanitize_widget_status' );
    register_setting( 'elementskey_options_group', 'elementskey_module_status', [ 'ElementsKey\Modules\HeaderFooter\Module', 'sanitize_module_status' ] );
}
add_action( 'admin_init', 'elementskey_admin_init' );

function elementskey_enqueue_admin_assets( $hook ) {
    if ( 'toplevel_page_elementskey-settings' !== $hook ) {
        return;
    }

    wp_enqueue_style(
        'elementskey-admin-font',
        'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap',
        [],
        ELEMENTSKEY_VERSION
    );

    wp_enqueue_style(
        'elementskey-admin-style',
        ELEMENTSKEY_URL . 'assets/css/admin.css',
        [ 'elementskey-admin-font' ],
        ELEMENTSKEY_VERSION
    );
}
add_action( 'admin_enqueue_scripts', 'elementskey_enqueue_admin_assets' );

register_activation_hook( __FILE__, 'elementskey_activate_site_setup' );

function elementskey_activate_site_setup() {
    elementskey_ensure_site_pages();
}

function elementskey_get_site_page_data() {
    return [
        'home' => [
            'title' => __( 'Home', 'elementskey' ),
            'slug'  => 'home',
            'type'  => 'home',
        ],
        'about' => [
            'title' => __( 'About', 'elementskey' ),
            'slug'  => 'about',
            'type'  => 'about',
        ],
        'contact' => [
            'title' => __( 'Contact', 'elementskey' ),
            'slug'  => 'contact',
            'type'  => 'contact',
        ],
        'plugin' => [
            'title' => __( 'Plugin', 'elementskey' ),
            'slug'  => 'plugin',
            'type'  => 'plugin',
        ],
    ];
}

function elementskey_get_site_page_id( $slug ) {
    $page = get_page_by_path( $slug );
    if ( $page instanceof WP_Post ) {
        return $page->ID;
    }

    $data = elementskey_get_site_page_data();
    if ( ! empty( $data[ $slug ] ) ) {
        $page = get_page_by_title( $data[ $slug ]['title'] );
        if ( $page instanceof WP_Post ) {
            return $page->ID;
        }
    }

    return 0;
}

function elementskey_ensure_site_pages() {
    $data = elementskey_get_site_page_data();
    $created = [];

    foreach ( $data as $key => $settings ) {
        $existing_id = elementskey_get_site_page_id( $settings['slug'] );

        if ( $existing_id ) {
            $created[ $key ] = $existing_id;
            continue;
        }

        $page_id = wp_insert_post( [
            'post_type'    => 'page',
            'post_status'  => 'publish',
            'post_title'   => $settings['title'],
            'post_name'    => $settings['slug'],
            'post_content' => '[elementskey_site_page type="' . esc_attr( $settings['type'] ) . '"]',
        ] );

        if ( ! is_wp_error( $page_id ) ) {
            $created[ $key ] = $page_id;
        }
    }

    if ( ! empty( $created['home'] ) && 'page' === get_option( 'show_on_front' ) && ! get_option( 'page_on_front' ) ) {
        update_option( 'page_on_front', $created['home'] );
        update_option( 'show_on_front', 'page' );
    }

    if ( ! empty( $created['home'] ) && ! get_option( 'page_on_front' ) ) {
        update_option( 'page_on_front', $created['home'] );
        update_option( 'show_on_front', 'page' );
    }

    return $created;
}

function elementskey_enqueue_site_assets() {
    if ( is_admin() ) {
        return;
    }

    wp_enqueue_style(
        'elementskey-site-font',
        'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap',
        [],
        ELEMENTSKEY_VERSION
    );

    wp_enqueue_style(
        'elementskey-site-style',
        ELEMENTSKEY_URL . 'assets/css/site.css',
        [ 'elementskey-site-font' ],
        ELEMENTSKEY_VERSION
    );
}
add_action( 'wp_enqueue_scripts', 'elementskey_enqueue_site_assets' );

function elementskey_get_site_menu_items() {
    $items = [];
    foreach ( elementskey_get_site_page_data() as $key => $settings ) {
        $page_id = elementskey_get_site_page_id( $settings['slug'] );
        $items[ $key ] = [
            'label' => $settings['title'],
            'url'   => $page_id ? get_permalink( $page_id ) : home_url( '/' . $settings['slug'] . '/' ),
        ];
    }

    return $items;
}

function elementskey_render_site_header() {
    $items = elementskey_get_site_menu_items();
    $nav = '';

    foreach ( $items as $item ) {
        $nav .= '<li><a href="' . esc_url( $item['url'] ) . '">' . esc_html( $item['label'] ) . '</a></li>';
    }

    return sprintf(
        '<header class="elementskey-site-header"><div class="elementskey-site-container"><div class="elementskey-site-brand-wrap"><a class="elementskey-site-brand" href="%1$s">ElementKey Lite</a></div><nav class="elementskey-site-nav"><ul>%2$s</ul></nav><a class="elementskey-site-cta" href="%3$s">Get Started</a></div></header>',
        esc_url( home_url( '/' ) ),
        $nav,
        esc_url( home_url( '/contact/' ) )
    );
}

function elementskey_render_site_footer() {
    $socials = [
        'Facebook' => 'https://facebook.com',
        'X' => 'https://x.com',
        'Instagram' => 'https://instagram.com',
        'LinkedIn' => 'https://linkedin.com',
    ];

    $links = '';
    foreach ( $socials as $label => $url ) {
        $links .= '<a href="' . esc_url( $url ) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr( $label ) . '">' . esc_html( $label ) . '</a>';
    }

    return sprintf(
        '<footer class="elementskey-site-footer"><div class="elementskey-site-container elementskey-site-footer-grid"><div class="elementskey-site-footer-brand"><div class="elementskey-site-brand">ElementKey Lite</div><p>Professional Elementor add-ons, crisp design, and fast front-end workflows built for modern WordPress teams.</p></div><div class="elementskey-site-footer-links"><h4>Explore</h4><ul><li><a href="%1$s">Home</a></li><li><a href="%2$s">About</a></li><li><a href="%3$s">Contact</a></li><li><a href="%4$s">Plugin</a></li></ul></div><div class="elementskey-site-footer-newsletter"><h4>Newsletter</h4><form class="elementskey-site-newsletter" method="post"><input type="email" placeholder="Your email" aria-label="Email address"><button type="submit">Join</button></form></div><div class="elementskey-site-footer-social"><h4>Follow</h4><div class="elementskey-site-social-links">%5$s</div></div></div><div class="elementskey-site-footer-bottom"><span>© %6$s ElementKey Lite</span><span>Built with Elementor styling and plugin-first workflows</span></div></footer>',
        esc_url( home_url( '/home/' ) ),
        esc_url( home_url( '/about/' ) ),
        esc_url( home_url( '/contact/' ) ),
        esc_url( home_url( '/plugin/' ) ),
        $links,
        gmdate( 'Y' )
    );
}

function elementskey_render_site_homepage() {
    return '<main class="elementskey-site-page"><section class="elementskey-site-hero"><div class="elementskey-site-container elementskey-site-hero-grid"><div class="elementskey-site-copy"><span class="elementskey-site-kicker">WordPress growth toolkit</span><h1>Build a faster, cleaner Elementor website with ElementKey Lite.</h1><p>Launch modern pages with compact widgets, header/footer flexibility, and a premium plugin-first experience.</p><div class="elementskey-site-actions"><a class="elementskey-site-button primary" href="'. esc_url( home_url( '/plugin/' ) ) .'">Explore plugin</a><a class="elementskey-site-button secondary" href="'. esc_url( home_url( '/contact/' ) ) .'">Book a demo</a></div><ul class="elementskey-site-metrics"><li><strong>73+</strong><span>Widgets</span></li><li><strong>Fast</strong><span>Load time</span></li><li><strong>Modern</strong><span>Design</span></li></ul></div><div class="elementskey-site-visual"><div class="elementskey-site-card card-main"><span>Launch-ready</span><h3>ElementKey Lite</h3><p>Custom headers, flexible sections, and polished layouts.</p></div><div class="elementskey-site-card card-small"><span>Quick setup</span><strong>1-click starter site</strong></div></div></div></section><section class="elementskey-site-section"><div class="elementskey-site-container"><div class="elementskey-site-section-heading"><span>Why teams choose us</span><h2>Everything your plugin website needs.</h2></div><div class="elementskey-site-grid three"><article class="elementskey-site-feature"><div class="elementskey-site-icon">01</div><h3>Custom layouts</h3><p>Create premium pages with flexible sections, buttons, galleries, and content blocks.</p></article><article class="elementskey-site-feature"><div class="elementskey-site-icon">02</div><h3>Built for Elementor</h3><p>Use a plugin-first workflow that keeps site design smooth and easy to manage.</p></article><article class="elementskey-site-feature"><div class="elementskey-site-icon">03</div><h3>Conversion-ready</h3><p>From CTA blocks to newsletter capture, every section supports real growth goals.</p></article></div></div></section><section class="elementskey-site-section alt"><div class="elementskey-site-container"><div class="elementskey-site-section-heading"><span>Plugin highlights</span><h2>Powerful, lightweight, and ready to publish.</h2></div><div class="elementskey-site-grid four"><div class="elementskey-site-stat"><strong>Header Builder</strong><span>Custom, branded navigation</span></div><div class="elementskey-site-stat"><strong>Footer Builder</strong><span>Social + newsletter + CTA</span></div><div class="elementskey-site-stat"><strong>Responsive Design</strong><span>Looks sharp on mobile</span></div><div class="elementskey-site-stat"><strong>Modern UI</strong><span>Clean cards and gradients</span></div></div></div></section></main>';
}

function elementskey_render_site_about() {
    return '<main class="elementskey-site-page"><section class="elementskey-site-section"><div class="elementskey-site-container elementskey-site-split"><div><span class="elementskey-site-kicker">About ElementKey Lite</span><h1>We design smarter plugin experiences for WordPress creators.</h1><p>ElementKey Lite helps businesses and agencies build premium pages faster, without bloated code or confusing configuration.</p><p>Our mission is simple: provide strong Elementor tooling with clean design, lightweight performance, and a flexible workflow that feels native to WordPress.</p></div><div class="elementskey-site-panel"><h3>What we deliver</h3><ul><li>Modern website structure</li><li>Reusable plugin sections</li><li>Flexible marketing content</li><li>Fast publishing workflow</li></ul></div></div></section></main>';
}

function elementskey_render_site_contact() {
    return '<main class="elementskey-site-page"><section class="elementskey-site-section"><div class="elementskey-site-container elementskey-site-contact-grid"><div><span class="elementskey-site-kicker">Contact</span><h1>Let’s build your next WordPress website.</h1><p>Need a branded landing page, plugin marketing site, or custom Elementor experience? We are ready to help.</p><ul class="elementskey-site-contact-list"><li>Email: hello@elementkeylite.com</li><li>Phone: +92 300 1234567</li><li>Location: Lahore, Pakistan</li></ul></div><div class="elementskey-site-panel form-panel"><h3>Send a message</h3><form class="elementskey-site-contact-form"><input type="text" placeholder="Your name"><input type="email" placeholder="Email address"><textarea placeholder="Your project details"></textarea><button type="submit">Submit</button></form></div></div></section></main>';
}

function elementskey_render_site_plugin() {
    return '<main class="elementskey-site-page"><section class="elementskey-site-section"><div class="elementskey-site-container"><div class="elementskey-site-section-heading"><span>Plugin</span><h1>Everything you need in one WordPress toolkit.</h1></div><div class="elementskey-site-grid three"><article class="elementskey-site-feature"><h3>Elementor widgets</h3><p>Use ready-made blocks for CTA, icons, pricing, galleries, and landing sections.</p></article><article class="elementskey-site-feature"><h3>Header & footer</h3><p>Customize navigation and footer content to match your business identity.</p></article><article class="elementskey-site-feature"><h3>Launch workflow</h3><p>Turn your plugin into a live marketing site with simple, conversion-focused design.</p></article></div></div></section></main>';
}

function elementskey_render_site_page_shortcode( $atts = [] ) {
    $atts = shortcode_atts( [ 'type' => 'home' ], $atts, 'elementskey_site_page' );

    $type = sanitize_key( $atts['type'] );

    switch ( $type ) {
        case 'about':
            $content = elementskey_render_site_about();
            break;
        case 'contact':
            $content = elementskey_render_site_contact();
            break;
        case 'plugin':
            $content = elementskey_render_site_plugin();
            break;
        case 'header':
            return elementskey_render_site_header();
        case 'footer':
            return elementskey_render_site_footer();
        case 'home':
        default:
            $content = elementskey_render_site_homepage();
            break;
    }

    return elementskey_render_site_header() . $content . elementskey_render_site_footer();
}
add_shortcode( 'elementskey_site_page', 'elementskey_render_site_page_shortcode' );
add_shortcode( 'elementskey_site_header', 'elementskey_render_site_header' );
add_shortcode( 'elementskey_site_footer', 'elementskey_render_site_footer' );

function elementskey_render_admin_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $features = [
        __( 'Enable or disable each widget from one place.', 'elementskey' ),
        __( 'Modern Elementor-native widgets with full style controls.', 'elementskey' ),
        __( 'Responsive and lightweight - zero bloat.', 'elementskey' ),
        __( 'Works seamlessly with any Elementor theme.', 'elementskey' ),
        __( 'Individual padding, margin, font and color controls per widget.', 'elementskey' ),
    ];

    $tips = [
        __( 'Toggle widgets on or off and click Save Settings.', 'elementskey' ),
        __( 'Then open the Elementor editor and refresh the panel.', 'elementskey' ),
        __( 'Only enabled widgets will appear under ElementsKey Elements.', 'elementskey' ),
    ];

    $widgets       = elementskey_get_widget_definitions();
    $descriptions  = elementskey_get_widget_descriptions();
    $widget_status = elementskey_get_widget_status();
    $enabled_count = array_sum( $widget_status );
    $total_widgets = count( $widgets );
    $inactive_count = max( 0, $total_widgets - $enabled_count );

    ?>
    <div class="wrap elementskey-admin-wrap">
        <h1 class="elementskey-admin-page-heading-catch"><?php esc_html_e( 'ElementsKey Settings', 'elementskey' ); ?></h1>
        <div class="elementskey-admin-shell">
            <div class="elementskey-admin-hero">
                <div class="elementskey-admin-hero-left">
                    <div class="elementskey-admin-hero-icon dashicons dashicons-screenoptions" aria-hidden="true"></div>
                    <div>
                        <div class="elementskey-admin-hero-title"><?php esc_html_e( 'ElementsKey Addons', 'elementskey' ); ?></div>
                        <p><?php esc_html_e( 'Manage and configure your custom Elementor widgets', 'elementskey' ); ?></p>
                    </div>
                </div>
                <span class="elementskey-admin-version">v<?php echo esc_html( ELEMENTSKEY_VERSION ); ?></span>
            </div>

            <?php
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- settings-updated is a redirect marker set by the Settings API.
            $settings_updated = isset( $_GET['settings-updated'] ) && 'true' === sanitize_text_field( wp_unslash( $_GET['settings-updated'] ) );
            if ( $settings_updated ) : ?>
                <div class="notice notice-success is-dismissible elementskey-admin-notice">
                    <p><?php esc_html_e( 'Settings saved successfully.', 'elementskey' ); ?></p>
                </div>
            <?php endif; ?>

            <div class="elementskey-admin-stats-grid">
                <div class="elementskey-admin-stat-card">
                    <div class="elementskey-admin-stat-icon elementskey-admin-stat-icon-blue dashicons dashicons-grid-view"></div>
                    <div>
                        <strong><?php echo esc_html( $total_widgets ); ?></strong>
                        <span><?php esc_html_e( 'Total Widgets', 'elementskey' ); ?></span>
                    </div>
                </div>

                <div class="elementskey-admin-stat-card">
                    <div class="elementskey-admin-stat-icon elementskey-admin-stat-icon-green dashicons dashicons-yes-alt"></div>
                    <div>
                        <strong><?php echo esc_html( $enabled_count ); ?></strong>
                        <span><?php esc_html_e( 'Active', 'elementskey' ); ?></span>
                    </div>
                </div>

                <div class="elementskey-admin-stat-card">
                    <div class="elementskey-admin-stat-icon elementskey-admin-stat-icon-orange dashicons dashicons-dismiss"></div>
                    <div>
                        <strong><?php echo esc_html( $inactive_count ); ?></strong>
                        <span><?php esc_html_e( 'Inactive', 'elementskey' ); ?></span>
                    </div>
                </div>
            </div>

            <div class="elementskey-admin-content-grid">
                <form method="post" action="options.php" class="elementskey-admin-main-form">
                    <?php settings_fields( 'elementskey_options_group' ); ?>

                    <input type="hidden" name="elementskey_widget_status" value="" />
                    <input type="hidden" name="elementskey_module_status" value="" />

                    <?php
                    $module_status = wp_parse_args( get_option( 'elementskey_module_status', [] ), \ElementsKey\Modules\HeaderFooter\Module::get_default_module_status() );
                    $hf_enabled    = ! empty( $module_status['header_footer'] );
                    ?>
                    <div class="elementskey-admin-panel elementskey-admin-panel-modules">
                        <h2 class="elementskey-admin-panel-title"><?php esc_html_e( 'Modules', 'elementskey' ); ?></h2>
                        <div class="elementskey-admin-widget-list">
                            <div class="elementskey-admin-widget-card">
                                <div class="elementskey-admin-widget-card-head">
                                    <div class="elementskey-admin-widget-icon dashicons dashicons-editor-kitchensink" aria-hidden="true"></div>
                                    <div class="elementskey-admin-widget-details">
                                        <h3><?php esc_html_e( 'Builder', 'elementskey' ); ?></h3>
                                        <p><?php esc_html_e( 'Create and manage custom templates with Elementor.', 'elementskey' ); ?></p>
                                    </div>
                                </div>
                                <div class="elementskey-admin-widget-toggle-wrap">
                                    <span class="elementskey-admin-widget-state<?php echo $hf_enabled ? ' is-on' : ' is-off'; ?>"><?php echo $hf_enabled ? esc_html__( 'Active', 'elementskey' ) : esc_html__( 'Inactive', 'elementskey' ); ?></span>
                                    <label class="elementskey-switch" for="elementskey_module_status_header_footer">
                                        <input
                                            type="checkbox"
                                            id="elementskey_module_status_header_footer"
                                            name="elementskey_module_status[header_footer]"
                                            value="1"
                                            <?php checked( $hf_enabled ); ?>
                                        />
                                        <span class="elementskey-slider"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="elementskey-admin-panel elementskey-admin-panel-widgets">
                        <h2 class="elementskey-admin-panel-title"><?php esc_html_e( 'Widget Manager', 'elementskey' ); ?></h2>

                        <div class="elementskey-admin-widget-list elementskey-admin-widget-list--grid-2">
                            <?php foreach ( $widgets as $slug => $widget ) : ?>
                                <?php
                                $is_active = ! empty( $widget_status[ $slug ] );
                                $description = isset( $descriptions[ $slug ] ) ? $descriptions[ $slug ] : '';
                                ?>
                                <div class="elementskey-admin-widget-card">
                                    <div class="elementskey-admin-widget-card-head">
                                        <div class="elementskey-admin-widget-icon dashicons dashicons-grid-view" aria-hidden="true"></div>
                                        <div class="elementskey-admin-widget-details">
                                            <h3><?php echo esc_html( $widget['label'] ); ?></h3>
                                            <p><?php echo esc_html( $description ); ?></p>
                                        </div>
                                    </div>

                                    <div class="elementskey-admin-widget-toggle-wrap">
                                        <span class="elementskey-admin-widget-state<?php echo $is_active ? ' is-on' : ' is-off'; ?>"><?php echo $is_active ? esc_html__( 'Active', 'elementskey' ) : esc_html__( 'Inactive', 'elementskey' ); ?></span>
                                        <label class="elementskey-switch" for="elementskey_widget_status_<?php echo esc_attr( $slug ); ?>">
                                            <input
                                                type="checkbox"
                                                id="elementskey_widget_status_<?php echo esc_attr( $slug ); ?>"
                                                name="elementskey_widget_status[<?php echo esc_attr( $slug ); ?>]"
                                                value="1"
                                                <?php checked( $is_active ); ?>
                                            />
                                            <span class="elementskey-slider"></span>
                                        </label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="elementskey-admin-form-actions">
                        <button type="submit" class="button button-primary elementskey-admin-save-btn"><?php esc_html_e( 'Save Settings', 'elementskey' ); ?></button>
                        <span class="elementskey-admin-form-hint"><?php esc_html_e( 'Saves widget and module changes together', 'elementskey' ); ?></span>
                    </div>
                </form>

                <div class="elementskey-admin-sidebar">
                    <div class="elementskey-admin-panel">
                        <h2 class="elementskey-admin-panel-title"><?php esc_html_e( 'Plugin Features', 'elementskey' ); ?></h2>
                        <ul class="elementskey-admin-list">
                            <?php foreach ( $features as $feature ) : ?>
                                <li><?php echo esc_html( $feature ); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <div class="elementskey-admin-panel elementskey-admin-panel-tips">
                        <h2 class="elementskey-admin-panel-title"><?php esc_html_e( 'Quick Tips', 'elementskey' ); ?></h2>
                        <ul class="elementskey-admin-list">
                            <?php foreach ( $tips as $tip ) : ?>
                                <li><?php echo esc_html( $tip ); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="elementskey-admin-footer">
                <span class="elementskey-admin-footer-brand"><?php esc_html_e( 'ElementsKey Addons', 'elementskey' ); ?></span>
                <span class="elementskey-admin-footer-note"><?php echo esc_html( $total_widgets ); ?> <?php esc_html_e( 'widgets', 'elementskey' ); ?> &middot; <?php echo esc_html( $enabled_count ); ?> <?php esc_html_e( 'active', 'elementskey' ); ?> &middot; <?php esc_html_e( 'Crafted for Elementor', 'elementskey' ); ?></span>
            </div>
        </div>
    </div>
    <script>
    ( function () {
        var switches = document.querySelectorAll( '.elementskey-admin-wrap .elementskey-switch input' );
        switches.forEach( function ( input ) {
            input.addEventListener( 'change', function () {
                var card = input.closest( '.elementskey-admin-widget-card' );
                if ( ! card ) {
                    return;
                }
                var state = card.querySelector( '.elementskey-admin-widget-state' );
                if ( ! state ) {
                    return;
                }
                var on = input.checked;
                state.textContent = on ? 'Active' : 'Inactive';
                state.classList.toggle( 'is-on', on );
                state.classList.toggle( 'is-off', ! on );
            } );
        } );
    } )();
    </script>
    <?php
}

/**
 * Register frontend assets so Elementor editor and frontend can use the same handles.
 */
function elementskey_register_assets() {
    wp_register_style(
        'elementskey-style',
        ELEMENTSKEY_URL . 'assets/css/style.css',
        [],
        ELEMENTSKEY_VERSION
    );

    wp_register_style(
        'elementskey-share-it-style',
        ELEMENTSKEY_URL . 'assets/css/share-it.css',
        [],
        ELEMENTSKEY_VERSION
    );

    wp_register_style(
        'elementskey-content-style',
        ELEMENTSKEY_URL . 'assets/css/content-widgets.css',
        [],
        ELEMENTSKEY_VERSION
    );

    wp_register_script(
        'elementskey-script',
        ELEMENTSKEY_URL . 'assets/js/script.js',
        [ 'jquery' ],
        ELEMENTSKEY_VERSION,
        true
    );

    wp_register_script(
        'elementskey-carousel-script',
        ELEMENTSKEY_URL . 'assets/js/carousel.js',
        [ 'swiper' ],
        ELEMENTSKEY_VERSION,
        true
    );

    wp_register_script(
        'elementskey-share-it-script',
        ELEMENTSKEY_URL . 'assets/js/share-it.js',
        [],
        ELEMENTSKEY_VERSION,
        true
    );

    wp_register_style(
        'elementskey-fontawesome',
        ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/fontawesome.min.css',
        [],
        '5.15.3'
    );

    wp_register_style(
        'elementskey-fa-brands',
        ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/brands.min.css',
        [ 'elementskey-fontawesome' ],
        '5.15.3'
    );

    wp_register_style(
        'elementskey-fa-solid',
        ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/solid.min.css',
        [ 'elementskey-fontawesome' ],
        '5.15.3'
    );

    wp_register_script(
        'elementskey-content-script',
        ELEMENTSKEY_URL . 'assets/js/content-widgets.js',
        [],
        ELEMENTSKEY_VERSION,
        true
    );
}
add_action( 'init', 'elementskey_register_assets' );

function elementskey_enqueue_editor_assets() {
    wp_enqueue_script(
        'elementskey-editor-badge',
        ELEMENTSKEY_URL . 'assets/js/editor.js',
        ['jquery'],
        ELEMENTSKEY_VERSION,
        true
    );

    $widget_labels = [];
    foreach ( elementskey_get_widget_definitions() as $widget ) {
        $widget_labels[] = $widget['label'];
    }

    wp_localize_script( 'elementskey-editor-badge', 'elementskeyEsWidgetLabels', $widget_labels );

    wp_enqueue_style(
        'elementskey-editor-style',
        ELEMENTSKEY_URL . 'assets/css/editor.css',
        [],
        ELEMENTSKEY_VERSION
    );
}
add_action( 'elementor/editor/after_enqueue_scripts', 'elementskey_enqueue_editor_assets' );