<?php
/**
 * Plugin Name: ElementStack Elementor Addons
 * Description: Custom widgets and UI modules for Elementor.
 * Version: 1.1.0
 * Author: ElementStack
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Define constants
 */
define( 'BDEA_PATH', plugin_dir_path( __FILE__ ) );
define( 'BDEA_URL', plugin_dir_url( __FILE__ ) );
define( 'BDEA_VERSION', '1.1.0' );

/**
 * PSR-4 autoloader for BDEA classes.
 */
spl_autoload_register( function ( $class ) {
    $prefix = 'BDEA\\';
    $len    = strlen( $prefix );

    if ( strncmp( $prefix, $class, $len ) !== 0 ) {
        return;
    }

    $relative_class = substr( $class, $len );
    $file           = BDEA_PATH . str_replace( '\\', DIRECTORY_SEPARATOR, $relative_class ) . '.php';

    if ( file_exists( $file ) ) {
        require_once $file;
    }
} );

/**
 * Check if Elementor is installed & activated
 */
function bdea_check_elementor_loaded() {
    if ( ! did_action( 'elementor/loaded' ) ) {
        add_action( 'admin_notices', 'bdea_elementor_missing_notice' );
        return false;
    }
    return true;
}

/**
 * Admin Notice if Elementor is missing
 */
function bdea_elementor_missing_notice() {
    ?>
    <div class="notice notice-warning is-dismissible">
        <p><strong>ElementStack Elementor Addons</strong> requires Elementor to be installed and activated.</p>
    </div>
    <?php
}

/**
 * Register custom category
 */
function bdea_register_category( $elements_manager ) {
    $elements_manager->add_category(
        'elementstack-elements',
        [
            'title' => 'ElementStack Elements',
            'icon'  => 'fa fa-plug',
        ]
    );
}
add_action( 'elementor/elements/categories_registered', 'bdea_register_category' );

/**
 * Register Widgets
 */
function bdea_get_default_widget_status() {
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

function bdea_get_widget_status() {
    $status = get_option( 'bdea_widget_status', [] );
    return wp_parse_args( $status, bdea_get_default_widget_status() );
}

function bdea_get_widget_definitions() {
    return [
        'progress_bar' => [
            'label' => 'Progress Bar',
            'class' => 'Custom_Progress_Bar_Widget',
        ],
        'data_table'  => [
            'label' => 'Data Table',
            'class' => 'BDEA_Data_Table_Widget',
        ],
        'carousel'    => [
            'label' => 'Carousel',
            'class' => 'BDEA_Swiper_Carousel_Widget',
        ],
        'feature_comparison_table' => [
            'label' => 'Feature Comparison Table',
            'class' => 'BDEA_Feature_Comparison_Table_Widget',
        ],
        'share_it' => [
            'label' => 'Share It',
            'class' => 'BDEA_Share_It_Widget',
        ],
        'loop_grid' => [
            'label' => 'Loop Grid',
            'class' => 'BDEA_Loop_Grid_Widget',
        ],
        'loop_carousel' => [
            'label' => 'Loop Carousel',
            'class' => 'BDEA_Loop_Carousel_Widget',
        ],
        'off_canvas' => [
            'label' => 'Off-Canvas',
            'class' => 'BDEA_Off_Canvas_Widget',
        ],
        'posts' => [
            'label' => 'Posts',
            'class' => 'BDEA_Posts_Widget',
        ],
        'portfolio' => [
            'label' => 'Portfolio',
            'class' => 'BDEA_Portfolio_Widget',
        ],
        'video' => [
            'label' => 'Video',
            'class' => 'BDEA_Video_Widget',
        ],
        'button' => [
            'label' => 'Button',
            'class' => 'BDEA_Button_Widget',
        ],
        'star_rating' => [
            'label' => 'Star Rating',
            'class' => 'BDEA_Star_Rating_Widget',
        ],
        'divider' => [
            'label' => 'Divider',
            'class' => 'BDEA_Divider_Widget',
        ],
        'google_maps' => [
            'label' => 'Google Maps',
            'class' => 'BDEA_Google_Maps_Widget',
        ],
        'icon' => [
            'label' => 'Icon',
            'class' => 'BDEA_Icon_Widget',
        ],
        'icon_box' => [
            'label' => 'Icon Box',
            'class' => 'BDEA_Icon_Box_Widget',
        ],
        'image_box' => [
            'label' => 'Image Box',
            'class' => 'BDEA_Image_Box_Widget',
        ],
        'basic_gallery' => [
            'label' => 'Basic Gallery',
            'class' => 'BDEA_Basic_Gallery_Widget',
        ],
        'image_carousel' => [
            'label' => 'Image Carousel',
            'class' => 'BDEA_Image_Carousel_Widget',
        ],
        'icon_list' => [
            'label' => 'Icon List',
            'class' => 'BDEA_Icon_List_Widget',
        ],
        'counter' => [
            'label' => 'Counter',
            'class' => 'BDEA_Counter_Widget',
        ],
        'spacer' => [
            'label' => 'Spacer',
            'class' => 'BDEA_Spacer_Widget',
        ],
        'testimonial' => [
            'label' => 'Testimonial',
            'class' => 'BDEA_Testimonial_Widget',
        ],
        'tabs' => [
            'label' => 'Tabs',
            'class' => 'BDEA_Tabs_Widget',
        ],
        'accordion' => [
            'label' => 'Accordion',
            'class' => 'BDEA_Accordion_Widget',
        ],
        'social_icons' => [
            'label' => 'Social Icons',
            'class' => 'BDEA_Social_Icons_Widget',
        ],
        'soundcloud' => [
            'label' => 'SoundCloud',
            'class' => 'BDEA_Sound_Cloud_Widget',
        ],
        'shortcode' => [
            'label' => 'Shortcode',
            'class' => 'BDEA_Shortcode_Widget',
        ],
        'form' => [
            'label' => 'Form',
            'class' => 'BDEA_Form_Widget',
        ],
        'login' => [
            'label' => 'Login',
            'class' => 'BDEA_Login_Widget',
        ],
        'slides' => [
            'label' => 'Slides',
            'class' => 'BDEA_Slides_Widget',
        ],
        'animated_headline' => [
            'label' => 'Animated Headline',
            'class' => 'BDEA_Animated_Headline_Widget',
        ],
        'hotspot' => [
            'label' => 'Hotspot',
            'class' => 'BDEA_Hotspot_Widget',
        ],
        'price_list' => [
            'label' => 'Price List',
            'class' => 'BDEA_Price_List_Widget',
        ],
        'price_table' => [
            'label' => 'Price Table',
            'class' => 'BDEA_Price_Table_Widget',
        ],
        'flip_box' => [
            'label' => 'Flip Box',
            'class' => 'BDEA_Flip_Box_Widget',
        ],
        'call_to_action' => [
            'label' => 'Call to Action',
            'class' => 'BDEA_Call_To_Action_Widget',
        ],
        'media_carousel' => [
            'label' => 'Media Carousel',
            'class' => 'BDEA_Media_Carousel_Widget',
        ],
        'testimonial_carousel' => [
            'label' => 'Testimonial Carousel',
            'class' => 'BDEA_Testimonial_Carousel_Widget',
        ],
        'reviews' => [
            'label' => 'Reviews',
            'class' => 'BDEA_Reviews_Widget',
        ],
        'table_of_content' => [
            'label' => 'Table Of Content',
            'class' => 'BDEA_Table_Of_Content_Widget',
        ],
        'countdown' => [
            'label' => 'Countdown',
            'class' => 'BDEA_Countdown_Widget',
        ],
        'blockquote' => [
            'label' => 'Blockquote',
            'class' => 'BDEA_Blockquote_Widget',
        ],
        'facebook_button' => [
            'label' => 'Facebook Button',
            'class' => 'BDEA_Facebook_Button_Widget',
        ],
        'facebook_comments' => [
            'label' => 'Facebook Comments',
            'class' => 'BDEA_Facebook_Comments_Widget',
        ],
        'facebook_embed' => [
            'label' => 'Facebook Embed',
            'class' => 'BDEA_Facebook_Embed_Widget',
        ],
        'facebook_page' => [
            'label' => 'Facebook Page',
            'class' => 'BDEA_Facebook_Page_Widget',
        ],
        'template' => [
            'label' => 'Template',
            'class' => 'BDEA_Template_Widget',
        ],
        'lottie_widget' => [
            'label' => 'Lottie',
            'class' => 'BDEA_Lottie_Widget',
        ],
        'code_highlight' => [
            'label' => 'Code Highlight',
            'class' => 'BDEA_Code_Highlight_Widget',
        ],
        'video_playlist' => [
            'label' => 'Video Playlist',
            'class' => 'BDEA_Video_Playlist_Widget',
        ],
        'progress_tracker' => [
            'label' => 'Progress Tracker',
            'class' => 'BDEA_Progress_Tracker_Widget',
        ],
        'menu_widget' => [
            'label' => 'Menu',
            'class' => 'BDEA_Menu_Widget',
        ],
        'taxonomy_filter' => [
            'label' => 'Taxonomy Filter',
            'class' => 'BDEA_Taxonomy_Filter_Widget',
        ],
        'link_in_bio' => [
            'label' => 'Link in Bio',
            'class' => 'BDEA_Link_In_Bio_Widget',
        ],
        'site_logo' => [
            'label' => 'Site Logo',
            'class' => 'BDEA_Site_Logo_Widget',
        ],
        'site_title' => [
            'label' => 'Site Title',
            'class' => 'BDEA_Site_Title_Widget',
        ],
        'page_title' => [
            'label' => 'Page Title',
            'class' => 'BDEA_Page_Title_Widget',
        ],
        'wp_menu' => [
            'label' => 'WordPress Menu',
            'class' => 'BDEA_Wp_Menu_Widget',
        ],
        'sitemap' => [
            'label' => 'Sitemap',
            'class' => 'BDEA_Sitemap_Widget',
        ],
        'post_title' => [
            'label' => 'Post Title',
            'class' => 'BDEA_Post_Title_Widget',
        ],
        'post_excerpt' => [
            'label' => 'Post Excerpt',
            'class' => 'BDEA_Post_Excerpt_Widget',
        ],
        'featured_image' => [
            'label' => 'Featured Image',
            'class' => 'BDEA_Featured_Image_Widget',
        ],
        'post_content' => [
            'label' => 'Post Content',
            'class' => 'BDEA_Post_Content_Widget',
        ],
        'author_box' => [
            'label' => 'Author Box',
            'class' => 'BDEA_Author_Box_Widget',
        ],
        'post_comments' => [
            'label' => 'Post Comments',
            'class' => 'BDEA_Post_Comments_Widget',
        ],
        'post_navigation' => [
            'label' => 'Post Navigation',
            'class' => 'BDEA_Post_Navigation_Widget',
        ],
        'post_info' => [
            'label' => 'Post Info',
            'class' => 'BDEA_Post_Info_Widget',
        ],
        'search' => [
            'label' => 'Search',
            'class' => 'BDEA_Search_Widget',
        ],
        'breadcrumbs' => [
            'label' => 'Breadcrumbs',
            'class' => 'BDEA_Breadcrumbs_Widget',
        ],
        'archive_title' => [
            'label' => 'Archive Title',
            'class' => 'BDEA_Archive_Title_Widget',
        ],
        'archive_posts' => [
            'label' => 'Archive Posts',
            'class' => 'BDEA_Archive_Posts_Widget',
        ],
    ];
}

function bdea_register_widgets( $widgets_manager ) {

    if ( ! bdea_check_elementor_loaded() ) {
        return;
    }

    $widget_definitions = bdea_get_widget_definitions();
    $widget_status      = bdea_get_widget_status();

    // Include widget files.
    require_once( BDEA_PATH . 'widgets/progress-bar.php' );
    require_once( BDEA_PATH . 'widgets/data-table.php' );
    require_once( BDEA_PATH . 'widgets/carousel.php' );
    require_once( BDEA_PATH . 'widgets/feature-comparison-table.php' );
    require_once( BDEA_PATH . 'widgets/share-it.php' );
    require_once( BDEA_PATH . 'widgets/helpers.php' );
    require_once( BDEA_PATH . 'widgets/loop-grid.php' );
    require_once( BDEA_PATH . 'widgets/loop-carousel.php' );
    require_once( BDEA_PATH . 'widgets/off-canvas.php' );
    require_once( BDEA_PATH . 'widgets/posts.php' );
    require_once( BDEA_PATH . 'widgets/portfolio.php' );
    require_once( BDEA_PATH . 'widgets/video.php' );
    require_once( BDEA_PATH . 'widgets/button.php' );
    require_once( BDEA_PATH . 'widgets/star-rating.php' );
    require_once( BDEA_PATH . 'widgets/divider.php' );
    require_once( BDEA_PATH . 'widgets/google-maps.php' );
    require_once( BDEA_PATH . 'widgets/icon.php' );
    require_once( BDEA_PATH . 'widgets/icon-box.php' );
    require_once( BDEA_PATH . 'widgets/image-box.php' );
    require_once( BDEA_PATH . 'widgets/basic-gallery.php' );
    require_once( BDEA_PATH . 'widgets/image-carousel.php' );
    require_once( BDEA_PATH . 'widgets/icon-list.php' );
    require_once( BDEA_PATH . 'widgets/counter.php' );
    require_once( BDEA_PATH . 'widgets/spacer.php' );
    require_once( BDEA_PATH . 'widgets/testimonial.php' );
    require_once( BDEA_PATH . 'widgets/tabs.php' );
    require_once( BDEA_PATH . 'widgets/accordion.php' );
    require_once( BDEA_PATH . 'widgets/social-icons.php' );
    require_once( BDEA_PATH . 'widgets/soundcloud.php' );
    require_once( BDEA_PATH . 'widgets/shortcode.php' );
    require_once( BDEA_PATH . 'widgets/form.php' );
    require_once( BDEA_PATH . 'widgets/login.php' );
    require_once( BDEA_PATH . 'widgets/slides.php' );
    require_once( BDEA_PATH . 'widgets/animated-headline.php' );
    require_once( BDEA_PATH . 'widgets/hotspot.php' );
    require_once( BDEA_PATH . 'widgets/price-list.php' );
    require_once( BDEA_PATH . 'widgets/price-table.php' );
    require_once( BDEA_PATH . 'widgets/flip-box.php' );
    require_once( BDEA_PATH . 'widgets/call-to-action.php' );
    require_once( BDEA_PATH . 'widgets/media-carousel.php' );
    require_once( BDEA_PATH . 'widgets/testimonial-carousel.php' );
    require_once( BDEA_PATH . 'widgets/reviews.php' );
    require_once( BDEA_PATH . 'widgets/table-of-content.php' );
    require_once( BDEA_PATH . 'widgets/countdown.php' );
    require_once( BDEA_PATH . 'widgets/blockquote.php' );
    require_once( BDEA_PATH . 'widgets/facebook-button.php' );
    require_once( BDEA_PATH . 'widgets/facebook-comments.php' );
    require_once( BDEA_PATH . 'widgets/facebook-embed.php' );
    require_once( BDEA_PATH . 'widgets/facebook-page.php' );
    require_once( BDEA_PATH . 'widgets/template.php' );
    require_once( BDEA_PATH . 'widgets/lottie-widget.php' );
    require_once( BDEA_PATH . 'widgets/code-highlight.php' );
    require_once( BDEA_PATH . 'widgets/video-playlist.php' );
    require_once( BDEA_PATH . 'widgets/progress-tracker.php' );
    require_once( BDEA_PATH . 'widgets/menu.php' );
    require_once( BDEA_PATH . 'widgets/taxonomy-filter.php' );
    require_once( BDEA_PATH . 'widgets/link-in-bio.php' );
    require_once( BDEA_PATH . 'widgets/site-logo.php' );
    require_once( BDEA_PATH . 'widgets/site-title.php' );
    require_once( BDEA_PATH . 'widgets/page-title.php' );
    require_once( BDEA_PATH . 'widgets/wp-menu.php' );
    require_once( BDEA_PATH . 'widgets/sitemap.php' );
    require_once( BDEA_PATH . 'widgets/post-title.php' );
    require_once( BDEA_PATH . 'widgets/post-excerpt.php' );
    require_once( BDEA_PATH . 'widgets/featured-image.php' );
    require_once( BDEA_PATH . 'widgets/post-content.php' );
    require_once( BDEA_PATH . 'widgets/author-box.php' );
    require_once( BDEA_PATH . 'widgets/post-comments.php' );
    require_once( BDEA_PATH . 'widgets/post-navigation.php' );
    require_once( BDEA_PATH . 'widgets/post-info.php' );
    require_once( BDEA_PATH . 'widgets/search.php' );
    require_once( BDEA_PATH . 'widgets/breadcrumbs.php' );
    require_once( BDEA_PATH . 'widgets/archive-title.php' );
    require_once( BDEA_PATH . 'widgets/archive-posts.php' );

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
add_action( 'elementor/widgets/register', 'bdea_register_widgets' );

function bdea_sanitize_widget_status( $input ) {
    $defaults = bdea_get_default_widget_status();

    if ( ! is_array( $input ) || empty( $input ) ) {
        $current = get_option( 'bdea_widget_status', $defaults );
        return wp_parse_args( is_array( $current ) ? $current : [], $defaults );
    }

    $output = [];

    foreach ( $defaults as $key => $value ) {
        $output[ $key ] = ! empty( $input[ $key ] ) ? 1 : 0;
    }

    return $output;
}

function bdea_get_widget_descriptions() {
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
function bdea_load_modules() {
    if ( ! bdea_check_elementor_loaded() ) {
        return;
    }

    require_once BDEA_PATH . 'modules/header-footer/Module.php';
    \BDEA\Modules\HeaderFooter\Module::instance();

    require_once BDEA_PATH . 'Framework/WidgetConditions.php';
    \BDEA\Framework\WidgetConditions::instance();
}
add_action( 'init', 'bdea_load_modules', 15 );

/**
 * Handle BDEA Form widget submissions via admin-post.php.
 *
 * Registered outside the widget loader so it also runs on admin-post.php
 * requests, where Elementor never fires elementor/widgets/register.
 */
function bdea_handle_form_submit() {
    $nonce = isset( $_POST['bdea_form_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['bdea_form_nonce'] ) ) : '';

    if ( ! wp_verify_nonce( $nonce, 'bdea_form_submit' ) ) {
        wp_send_json_error( [ 'message' => 'Invalid form submission. Please refresh the page and try again.' ], 400 );
    }

    $form_id = isset( $_POST['bdea_form_id'] ) ? sanitize_text_field( wp_unslash( $_POST['bdea_form_id'] ) ) : '';

    $email_to    = isset( $_POST['bdea_email_to'] ) ? sanitize_email( wp_unslash( $_POST['bdea_email_to'] ) ) : get_option( 'admin_email' );
    $email_subject = isset( $_POST['bdea_email_subject'] ) ? sanitize_text_field( wp_unslash( $_POST['bdea_email_subject'] ) ) : 'New Form Submission';

    $body_lines = [];
    foreach ( $_POST as $key => $value ) {
        if ( 0 !== strpos( $key, 'bdea_field_' ) ) {
            continue;
        }

        $label = str_replace( 'bdea_field_', '', $key );
        $label = ucwords( str_replace( [ '_', '-' ], ' ', $label ) );

        if ( is_array( $value ) ) {
            $value = implode( ', ', array_map( 'sanitize_text_field', wp_unslash( $value ) ) );
        } else {
            $value = sanitize_textarea_field( wp_unslash( $value ) );
        }

        $body_lines[] = $label . ': ' . $value;
    }

    if ( empty( $body_lines ) ) {
        wp_send_json_error( [ 'message' => 'The form does not contain any fields.' ], 400 );
    }

    $body = implode( "\n\n", $body_lines ) . "\n\n---\nSubmitted via ElementStack Form (ID: " . $form_id . ")\n";

    $sent = wp_mail(
        $email_to,
        $email_subject,
        $body,
        [ 'Content-Type: text/plain; charset=UTF-8' ]
    );

    if ( $sent ) {
        wp_send_json_success( [ 'message' => 'success' ] );
    }

    wp_send_json_error( [ 'message' => 'The message could not be sent. Please try again.' ], 500 );
}
add_action( 'admin_post_nopriv_bdea_form_submit', 'bdea_handle_form_submit' );
add_action( 'admin_post_bdea_form_submit', 'bdea_handle_form_submit' );

/**
 * Handle Loop Grid "Load on Demand" / "Infinite Scroll" via admin-ajax.php.
 *
 * Registered outside the widget loader because admin-ajax requests never fire
 * elementor/widgets/register.
 */
function bdea_handle_loop_load() {
    check_ajax_referer( 'bdea_loop_load', 'nonce' );

    require_once BDEA_PATH . 'widgets/helpers.php';

    $page = isset( $_POST['page'] ) ? max( 1, absint( wp_unslash( $_POST['page'] ) ) ) : 1;

    $settings = [];
    if ( isset( $_POST['settings'] ) ) {
        $settings = json_decode( wp_unslash( $_POST['settings'] ), true );
        if ( ! is_array( $settings ) ) {
            $settings = [];
        }
    }

    if ( ! empty( $settings['exclude_ids'] ) ) {
        $settings['exclude_ids'] = array_map( 'trim', explode( ',', (string) $settings['exclude_ids'] ) );
    }

    $query_args = bdea_widget_query_args( $settings );

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
    bdea_render_loop_items( $query, $settings );
    $html = ob_get_clean();

    wp_send_json_success( [ 'html' => $html, 'has_more' => $page < (int) $query->max_num_pages ] );
}
add_action( 'wp_ajax_bdea_loop_load', 'bdea_handle_loop_load' );
add_action( 'wp_ajax_nopriv_bdea_loop_load', 'bdea_handle_loop_load' );

/**
 * Create a new loop template from the Loop Grid / Loop Carousel widget (editor only).
 *
 * Follows Elementor Pro's flow: the template is always created with the
 * dedicated "loop-item" type, so it appears in the loop template dropdowns
 * and the library under its own type — never as header/footer/page.
 * Elementor core falls back to the "page" document when Pro is inactive,
 * so the template stays editable either way.
 */
function bdea_create_loop_template() {
    check_ajax_referer( 'bdea_editor', 'nonce' );

    if ( ! current_user_can( 'edit_posts' ) ) {
        wp_send_json_error( [ 'message' => 'You do not have permission to create templates.' ], 403 );
    }

    $type = 'loop-item';

    $post_id = wp_insert_post( [
        'post_type'    => 'elementor_library',
        'post_status'  => 'publish',
        'post_title'   => 'Loop Template ' . gmdate( 'Y-m-d H:i' ),
    ] );

    if ( is_wp_error( $post_id ) ) {
        wp_send_json_error( [ 'message' => $post_id->get_error_message() ], 500 );
    }

    $container_id = substr( md5( 'bdea-loop-' . $post_id . '-1' ), 0, 7 );

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
add_action( 'wp_ajax_bdea_create_loop_template', 'bdea_create_loop_template' );

function bdea_admin_menu() {
    add_menu_page(
        'ElementStack Settings',
        'ElementStack',
        'manage_options',
        'elementstack-settings',
        'bdea_render_admin_page',
        'dashicons-screenoptions',
        60
    );
}
add_action( 'admin_menu', 'bdea_admin_menu' );

function bdea_admin_init() {
    register_setting( 'bdea_options_group', 'bdea_widget_status', 'bdea_sanitize_widget_status' );
    register_setting( 'bdea_options_group', 'bdea_module_status', [ 'BDEA\Modules\HeaderFooter\Module', 'sanitize_module_status' ] );
}
add_action( 'admin_init', 'bdea_admin_init' );

function bdea_enqueue_admin_assets( $hook ) {
    if ( 'toplevel_page_elementstack-settings' !== $hook ) {
        return;
    }

    wp_enqueue_style(
        'bdea-admin-font',
        'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap',
        [],
        BDEA_VERSION
    );

    wp_enqueue_style(
        'bdea-admin-style',
        BDEA_URL . 'assets/css/admin.css',
        [ 'bdea-admin-font' ],
        BDEA_VERSION
    );
}
add_action( 'admin_enqueue_scripts', 'bdea_enqueue_admin_assets' );

function bdea_render_admin_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $features = [
        'Enable or disable each widget from one place.',
        'Modern Elementor-native widgets with full style controls.',
        'Responsive and lightweight - zero bloat.',
        'Works seamlessly with any Elementor theme.',
        'Individual padding, margin, font and color controls per widget.',
    ];

    $tips = [
        'Toggle widgets on or off and click Save Settings.',
        'Then open the Elementor editor and refresh the panel.',
        'Only enabled widgets will appear under ElementStack Elements.',
    ];

    $widgets       = bdea_get_widget_definitions();
    $descriptions  = bdea_get_widget_descriptions();
    $widget_status = bdea_get_widget_status();
    $enabled_count = array_sum( $widget_status );
    $total_widgets = count( $widgets );
    $inactive_count = max( 0, $total_widgets - $enabled_count );

    ?>
    <div class="wrap bdea-admin-wrap">
        <h1 class="bdea-admin-page-heading-catch">ElementStack Settings</h1>
        <div class="bdea-admin-shell">
            <div class="bdea-admin-hero">
                <div class="bdea-admin-hero-left">
                    <div class="bdea-admin-hero-icon dashicons dashicons-screenoptions" aria-hidden="true"></div>
                    <div>
                        <div class="bdea-admin-hero-title">ElementStack Addons</div>
                        <p>Manage and configure your custom Elementor widgets</p>
                    </div>
                </div>
                <span class="bdea-admin-version">v<?php echo esc_html( BDEA_VERSION ); ?></span>
            </div>

            <?php if ( isset( $_GET['settings-updated'] ) && 'true' === $_GET['settings-updated'] ) : ?>
                <div class="notice notice-success is-dismissible bdea-admin-notice">
                    <p>Settings saved successfully.</p>
                </div>
            <?php endif; ?>

            <div class="bdea-admin-stats-grid">
                <div class="bdea-admin-stat-card">
                    <div class="bdea-admin-stat-icon bdea-admin-stat-icon-blue dashicons dashicons-grid-view"></div>
                    <div>
                        <strong><?php echo esc_html( $total_widgets ); ?></strong>
                        <span>Total Widgets</span>
                    </div>
                </div>

                <div class="bdea-admin-stat-card">
                    <div class="bdea-admin-stat-icon bdea-admin-stat-icon-green dashicons dashicons-yes-alt"></div>
                    <div>
                        <strong><?php echo esc_html( $enabled_count ); ?></strong>
                        <span>Active</span>
                    </div>
                </div>

                <div class="bdea-admin-stat-card">
                    <div class="bdea-admin-stat-icon bdea-admin-stat-icon-orange dashicons dashicons-dismiss"></div>
                    <div>
                        <strong><?php echo esc_html( $inactive_count ); ?></strong>
                        <span>Inactive</span>
                    </div>
                </div>
            </div>

            <div class="bdea-admin-content-grid">
                <form method="post" action="options.php" class="bdea-admin-main-form">
                    <?php settings_fields( 'bdea_options_group' ); ?>

                    <input type="hidden" name="bdea_widget_status" value="" />
                    <input type="hidden" name="bdea_module_status" value="" />

                    <div class="bdea-admin-panel bdea-admin-panel-widgets">
                        <h2 class="bdea-admin-panel-title">Widget Manager</h2>

                        <div class="bdea-admin-widget-list">
                            <?php foreach ( $widgets as $slug => $widget ) : ?>
                                <?php
                                $is_active = ! empty( $widget_status[ $slug ] );
                                $description = isset( $descriptions[ $slug ] ) ? $descriptions[ $slug ] : '';
                                ?>
                                <div class="bdea-admin-widget-card">
                                    <div class="bdea-admin-widget-card-head">
                                        <div class="bdea-admin-widget-icon dashicons dashicons-grid-view" aria-hidden="true"></div>
                                        <div class="bdea-admin-widget-details">
                                            <h3><?php echo esc_html( $widget['label'] ); ?></h3>
                                            <p><?php echo esc_html( $description ); ?></p>
                                        </div>
                                    </div>

                                    <div class="bdea-admin-widget-toggle-wrap">
                                        <span class="bdea-admin-widget-state<?php echo $is_active ? ' is-on' : ' is-off'; ?>"><?php echo $is_active ? 'Active' : 'Inactive'; ?></span>
                                        <label class="bdea-switch" for="bdea_widget_status_<?php echo esc_attr( $slug ); ?>">
                                            <input
                                                type="checkbox"
                                                id="bdea_widget_status_<?php echo esc_attr( $slug ); ?>"
                                                name="bdea_widget_status[<?php echo esc_attr( $slug ); ?>]"
                                                value="1"
                                                <?php checked( $is_active ); ?>
                                            />
                                            <span class="bdea-slider"></span>
                                        </label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <?php
                    $module_status = get_option( 'bdea_module_status', [] );
                    $hf_enabled    = ! empty( $module_status['header_footer'] );
                    ?>
                    <div class="bdea-admin-panel bdea-admin-panel-modules">
                        <h2 class="bdea-admin-panel-title">Modules</h2>
                        <div class="bdea-admin-widget-list">
                            <div class="bdea-admin-widget-card">
                                <div class="bdea-admin-widget-card-head">
                                    <div class="bdea-admin-widget-icon dashicons dashicons-editor-kitchensink" aria-hidden="true"></div>
                                    <div class="bdea-admin-widget-details">
                                        <h3>Theme Builder</h3>
                                        <p>Create and manage custom header, footer, and theme templates with Elementor.</p>
                                    </div>
                                </div>
                                <div class="bdea-admin-widget-toggle-wrap">
                                    <span class="bdea-admin-widget-state<?php echo $hf_enabled ? ' is-on' : ' is-off'; ?>"><?php echo $hf_enabled ? 'Active' : 'Inactive'; ?></span>
                                    <label class="bdea-switch" for="bdea_module_status_header_footer">
                                        <input
                                            type="checkbox"
                                            id="bdea_module_status_header_footer"
                                            name="bdea_module_status[header_footer]"
                                            value="1"
                                            <?php checked( $hf_enabled ); ?>
                                        />
                                        <span class="bdea-slider"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bdea-admin-form-actions">
                        <button type="submit" class="button button-primary bdea-admin-save-btn">Save Settings</button>
                        <span class="bdea-admin-form-hint">Saves widget and module changes together</span>
                    </div>
                </form>

                <div class="bdea-admin-sidebar">
                    <div class="bdea-admin-panel">
                        <h2 class="bdea-admin-panel-title">Plugin Features</h2>
                        <ul class="bdea-admin-list">
                            <?php foreach ( $features as $feature ) : ?>
                                <li><?php echo esc_html( $feature ); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <div class="bdea-admin-panel bdea-admin-panel-tips">
                        <h2 class="bdea-admin-panel-title">Quick Tips</h2>
                        <ul class="bdea-admin-list">
                            <?php foreach ( $tips as $tip ) : ?>
                                <li><?php echo esc_html( $tip ); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="bdea-admin-footer">
                <span class="bdea-admin-footer-brand">ElementStack Addons</span>
                <span class="bdea-admin-footer-note"><?php echo esc_html( $total_widgets ); ?> widgets &middot; <?php echo esc_html( $enabled_count ); ?> active &middot; Crafted for Elementor</span>
            </div>
        </div>
    </div>
    <script>
    ( function () {
        var switches = document.querySelectorAll( '.bdea-admin-wrap .bdea-switch input' );
        switches.forEach( function ( input ) {
            input.addEventListener( 'change', function () {
                var card = input.closest( '.bdea-admin-widget-card' );
                if ( ! card ) {
                    return;
                }
                var state = card.querySelector( '.bdea-admin-widget-state' );
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
function bdea_register_assets() {
    wp_register_style(
        'bdea-style',
        BDEA_URL . 'assets/css/style.css',
        [],
        '1.0.0'
    );

    wp_register_style(
        'bdea-share-it-style',
        BDEA_URL . 'assets/css/share-it.css',
        [],
        '1.0.0'
    );

    wp_register_style(
        'bdea-content-style',
        BDEA_URL . 'assets/css/content-widgets.css',
        [],
        '1.0.0'
    );

    wp_register_script(
        'bdea-script',
        BDEA_URL . 'assets/js/script.js',
        [ 'jquery' ],
        '1.0.0',
        true
    );

    wp_register_script(
        'bdea-carousel-script',
        BDEA_URL . 'assets/js/carousel.js',
        [ 'swiper' ],
        '1.0.0',
        true
    );

    wp_register_script(
        'bdea-share-it-script',
        BDEA_URL . 'assets/js/share-it.js',
        [],
        '1.0.0',
        true
    );

    wp_register_script(
        'bdea-content-script',
        BDEA_URL . 'assets/js/content-widgets.js',
        [],
        '1.0.0',
        true
    );
}
add_action( 'init', 'bdea_register_assets' );

function bdea_enqueue_editor_assets() {
    wp_enqueue_script(
        'bdea-editor-badge',
        BDEA_URL . 'assets/js/editor.js',
        ['jquery'],
        '1.4.0',
        true
    );

    $widget_labels = [];
    foreach ( bdea_get_widget_definitions() as $widget ) {
        $widget_labels[] = $widget['label'];
    }

    wp_localize_script( 'bdea-editor-badge', 'bdeaEsWidgetLabels', $widget_labels );

    wp_enqueue_style(
        'bdea-editor-style',
        BDEA_URL . 'assets/css/editor.css',
        [],
        '1.0.0'
    );
}
add_action( 'elementor/editor/after_enqueue_scripts', 'bdea_enqueue_editor_assets' );

/**
 * Register Demo Landing page template
 */
function bdea_register_demo_template( $templates ) {
    $templates['templates/demo-landing.php'] = 'BDEA Widget Demo Landing';
    return $templates;
}
add_filter( 'page_templates', 'bdea_register_demo_template' );

function bdea_demo_template_include( $template ) {
    if ( is_page_template( 'templates/demo-landing.php' ) ) {
        $new_template = BDEA_PATH . 'templates/demo-landing.php';
        if ( file_exists( $new_template ) ) {
            return $new_template;
        }
    }
    return $template;
}
add_filter( 'template_include', 'bdea_demo_template_include' );