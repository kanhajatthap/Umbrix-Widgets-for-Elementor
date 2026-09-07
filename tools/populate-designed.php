<?php
/**
 * Populates 2 pages with all 73 widgets — each with 3 DISTINCT visual designs.
 * Page 82 – widgets-content (36 content widgets)
 * Page 84 – widgets-media (37 media/social/form/theme widgets)
 * Usage: tools\wp.cmd eval-file tools/populate-designed.php
 */
if ( ! defined( 'ABSPATH' ) ) { require_once dirname( __DIR__, 4 ) . '/wp-load.php'; }
if ( ! did_action( 'elementor/loaded' ) ) { die( 'Elementor not loaded.' ); }

function _rid() { return substr( md5( uniqid( mt_rand(), true ) ), 0, 7 ); }
function _w( $type, $s = [] ) { return [ 'id' => _rid(), 'elType' => 'widget', 'widgetType' => $type, 'settings' => $s, 'elements' => [], 'isInner' => false ]; }
function _h( $text, $tag = 'h2' ) { return _w( 'heading', [ 'title' => $text, 'header_size' => $tag, 'align' => 'left', 'title_color' => '#111827' ] ); }
function _col( $widgets, $size = 100 ) { $els = is_array( $widgets[0] ?? null ) ? $widgets : [ $widgets ]; return [ 'id' => _rid(), 'elType' => 'column', 'settings' => [ '_column_size' => $size ], 'elements' => $els, 'isInner' => false ]; }
function _sec( $cols, $s = [] ) { return [ 'id' => _rid(), 'elType' => 'section', 'settings' => array_merge( [ 'layout' => 'boxed', 'content_width' => [ 'unit' => 'px', 'size' => 1140 ], 'gap' => 'extended' ], $s ), 'elements' => $cols, 'isInner' => false ]; }
function _cols( $cols_of_w, $s = [], $sizes = [] ) { $cs = []; foreach ( $cols_of_w as $i => $cw ) { $cs[] = _col( $cw, $sizes[ $i ] ?? (int) ( 100 / count( $cols_of_w ) ) ); } return _sec( $cs, $s ); }
function _show( $name, $cols, $band = 0 ) { $bg = ( $band % 2 ) ? '#f8fafc' : '#ffffff'; $hdr = _cols( [ [ _h( $name ) ] ], [ 'background_background' => 'classic', 'background_color' => $bg, 'padding' => [ 'unit' => 'px', 'top' => 50, 'right' => 20, 'bottom' => 10, 'left' => 20, 'isLinked' => false ] ] ); $body = _cols( $cols, [ 'background_background' => 'classic', 'background_color' => $bg, 'padding' => [ 'unit' => 'px', 'top' => 20, 'right' => 20, 'bottom' => 50, 'left' => 20, 'isLinked' => false ] ] ); return [ $hdr, $body ]; }
function _typo( $size, $weight = '400' ) { return [ 'unit' => 'px', 'size' => $size, 'size_mobile' => $size, 'weight' => $weight ]; }
function _dim( $v ) { return [ 'unit' => 'px', 'top' => $v, 'right' => $v, 'bottom' => $v, 'left' => $v, 'isLinked' => true ]; }
function _dim4( $t, $r, $b, $l ) { return [ 'unit' => 'px', 'top' => $t, 'right' => $r, 'bottom' => $b, 'left' => $l, 'isLinked' => false ]; }
function _rad( $v ) { return [ 'unit' => 'px', 'top' => $v, 'right' => $v, 'bottom' => $v, 'left' => $v, 'isLinked' => true ]; }
function _bdr( $w, $c, $s = 'solid' ) { return [ 'border' => [ 'width' => [ 'size' => $w ], 'color' => $c, 'style' => $s ] ]; }
function _shd( $c, $v = 4, $b = 20 ) { return [ 'enable' => 'yes', 'color' => $c, 'horizontal' => 0, 'vertical' => $v, 'blur' => $b, 'spread' => 0 ]; }

function _save( $id, $els, $title ) {
    $json = wp_json_encode( $els, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
    if ( json_last_error() !== JSON_ERROR_NONE ) { echo "JSON ERROR: " . json_last_error_msg() . PHP_EOL; return false; }
    global $wpdb;
    $wpdb->delete( $wpdb->postmeta, [ 'post_id' => $id, 'meta_key' => '_elementor_data' ], [ '%d', '%s' ] );
    $wpdb->insert( $wpdb->postmeta, [ 'post_id' => $id, 'meta_key' => '_elementor_data', 'meta_value' => $json ], [ '%d', '%s', '%s' ] );
    $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->postmeta} WHERE post_id=%d AND meta_key IN ('_elementor_edit_mode','_elementor_version','_wp_page_template','_elementor_css','_elementor_page_assets')", $id ) );
    $wpdb->query( $wpdb->prepare( "INSERT INTO {$wpdb->postmeta} (post_id, meta_key, meta_value) VALUES (%d, '_elementor_edit_mode', 'builder')", $id ) );
    $wpdb->query( $wpdb->prepare( "INSERT INTO {$wpdb->postmeta} (post_id, meta_key, meta_value) VALUES (%d, '_elementor_version', %s)", $id, ELEMENTOR_VERSION ) );
    $wpdb->query( $wpdb->prepare( "INSERT INTO {$wpdb->postmeta} (post_id, meta_key, meta_value) VALUES (%d, '_wp_page_template', '')", $id ) );
    if ( class_exists( '\Elementor\Plugin' ) ) { \Elementor\Plugin::$instance->files_manager->clear_cache(); }
    echo "Page $id ($title): " . count( $els ) . " sections, " . strlen( $json ) . " bytes\n";
    return true;
}

/* ═══════════════════════════════════════════════
   PAGE 1: widgets-content (ID 82) — 36 widgets
   ═══════════════════════════════════════════════ */
$b = 0; $e = [];

$e[] = _cols( [ [ _h( 'Content Widgets - 3 Designs Each', 'h1' ), _w( 'text-editor', [ 'editor' => '<p style="color:#6b7280">Every widget styled 3 different ways using Elementor controls only.</p>', 'align' => 'center' ] ) ] ], [ 'background_background' => 'gradient', 'background_color' => '#0f172a', 'background_color_b' => '#4f46e5', 'padding' => _dim4( 80, 20, 80, 20 ) ] );

// BUTTON
array_push( $e, ..._show( 'Button', [
    [ _w( 'elementskey_button', [ 'button_text' => 'Get Started', 'button_bg' => '#4361ee', 'button_color' => '#fff', 'button_icon' => ['value'=>'fas fa-arrow-right','library'=>'fa-solid'], 'button_icon_align' => 'right', 'button_radius' => _rad(30), 'button_shadow' => _shd('#4361ee80'), 'button_padding' => _dim4(14,32,14,32), 'button_hover_bg' => '#3451de', 'button_hover_translate' => 'lift', 'button_align' => 'center', 'button_typography' => _typo(16,'600') ] ) ],
    [ _w( 'elementskey_button', [ 'button_text' => 'Learn More', 'button_bg' => 'transparent', 'button_color' => '#10b981', 'button_icon' => ['value'=>'fas fa-chevron-right','library'=>'fa-solid'], 'button_icon_align' => 'right', 'button_radius' => _rad(0), 'button_border' => _bdr(2,'#10b981'), 'button_padding' => _dim4(12,28,12,28), 'button_hover_bg' => '#10b981', 'button_hover_color' => '#fff', 'button_hover_translate' => 'grow', 'button_align' => 'center', 'button_typography' => _typo(15,'700') ] ) ],
    [ _w( 'elementskey_button', [ 'button_text' => 'Contact Us', 'button_bg' => '#f43f5e', 'button_color' => '#fff', 'button_icon' => ['value'=>'fas fa-envelope','library'=>'fa-solid'], 'button_icon_align' => 'left', 'button_radius' => _rad(50), 'button_shadow' => _shd('#f43f5e60',6,25), 'button_padding' => _dim4(16,40,16,40), 'button_hover_bg' => '#e11d48', 'button_hover_translate' => 'lift', 'button_align' => 'center', 'button_typography' => _typo(17,'600') ] ) ],
], $b++ ) );

// ICON
array_push( $e, ..._show( 'Icon', [
    [ _w( 'elementskey_icon', [ 'selected_icon' => ['value'=>'fas fa-star','library'=>'fa-solid'], 'icon_color' => '#f7b500', 'icon_bg' => '#fef3c7', 'icon_size' => ['size'=>40,'unit'=>'px'], 'icon_padding' => _dim(18), 'icon_radius' => ['size'=>50,'unit'=>'%'], 'icon_align' => 'center' ] ) ],
    [ _w( 'elementskey_icon', [ 'selected_icon' => ['value'=>'fas fa-heart','library'=>'fa-solid'], 'icon_color' => '#ef4444', 'icon_bg' => '#fee2e2', 'icon_size' => ['size'=>34,'unit'=>'px'], 'icon_padding' => _dim(14), 'icon_radius' => ['size'=>8,'unit'=>'px'], 'icon_align' => 'center' ] ) ],
    [ _w( 'elementskey_icon', [ 'selected_icon' => ['value'=>'fas fa-bolt','library'=>'fa-solid'], 'icon_color' => '#7c3aed', 'icon_size' => ['size'=>28,'unit'=>'px'], 'icon_rotate' => ['size'=>-15,'unit'=>'deg'], 'icon_align' => 'center' ] ) ],
], $b++ ) );

// ICON BOX
array_push( $e, ..._show( 'Icon Box', [
    [ _w( 'elementskey_icon_box', [ 'selected_icon' => ['value'=>'fas fa-rocket','library'=>'fa-solid'], 'title' => 'Fast Performance', 'description' => 'Optimized for speed.', 'align' => 'center', 'position' => 'top', 'card_bg' => '#eff6ff', 'icon_color' => '#2563eb', 'icon_bg' => '#dbeafe', 'icon_size' => ['size'=>32,'unit'=>'px'], 'icon_padding' => _dim(16), 'icon_radius' => ['size'=>12,'unit'=>'px'], 'title_color' => '#1e40af', 'desc_color' => '#3b82f6' ] ) ],
    [ _w( 'elementskey_icon_box', [ 'selected_icon' => ['value'=>'fas fa-shield-alt','library'=>'fa-solid'], 'title' => 'Secure', 'description' => 'Built with security.', 'align' => 'left', 'position' => 'left', 'card_bg' => '#ecfdf5', 'icon_color' => '#059669', 'icon_bg' => '#d1fae5', 'icon_size' => ['size'=>28,'unit'=>'px'], 'icon_padding' => _dim(14), 'icon_radius' => ['size'=>50,'unit'=>'%'], 'title_color' => '#065f46', 'desc_color' => '#10b981' ] ) ],
    [ _w( 'elementskey_icon_box', [ 'selected_icon' => ['value'=>'fas fa-paint-brush','library'=>'fa-solid'], 'title' => 'Customizable', 'description' => 'Full styling controls.', 'align' => 'center', 'position' => 'top', 'card_bg' => '#f5f3ff', 'icon_color' => '#7c3aed', 'icon_bg' => 'transparent', 'icon_size' => ['size'=>36,'unit'=>'px'], 'title_color' => '#5b21b6', 'desc_color' => '#8b5cf6' ] ) ],
], $b++ ) );

// IMAGE BOX
array_push( $e, ..._show( 'Image Box', [
    [ _w( 'elementskey_image_box', [ 'image' => ['url'=>'https://picsum.photos/seed/ibox1/400/200'], 'title' => 'Creative Solutions', 'description' => 'Innovative designs.', 'card_bg' => '#eff6ff', 'title_color' => '#1e40af', 'desc_color' => '#3b82f6', 'image_radius' => ['size'=>12,'unit'=>'px'], 'image_shadow' => _shd('#2563eb20') ] ) ],
    [ _w( 'elementskey_image_box', [ 'image' => ['url'=>'https://picsum.photos/seed/ibox2/400/200'], 'title' => 'Expert Team', 'description' => 'Professional experience.', 'card_bg' => '#ecfdf5', 'title_color' => '#065f46', 'desc_color' => '#10b981', 'image_radius' => ['size'=>50,'unit'=>'%'] ] ) ],
    [ _w( 'elementskey_image_box', [ 'image' => ['url'=>'https://picsum.photos/seed/ibox3/400/200'], 'title' => '24/7 Support', 'description' => 'Always here to help.', 'card_bg' => '#fff1f2', 'title_color' => '#9f1239', 'desc_color' => '#f43f5e', 'image_radius' => ['size'=>16,'unit'=>'px'], 'image_shadow' => _shd('#f43f5e20',6,20) ] ) ],
], $b++ ) );

// COUNTER
array_push( $e, ..._show( 'Counter', [
    [ _w( 'elementskey_counter', [ 'counter_number' => 2500, 'counter_suffix' => '+', 'counter_title' => 'Happy Customers', 'number_color' => '#4361ee', 'title_color' => '#6b7280', 'counter_bg' => '#fff', 'counter_border_radius' => _dim(16), 'counter_shadow' => _shd('#00000015'), 'counter_padding' => _dim(30), 'number_typography' => _typo(48,'800'), 'title_typography' => _typo(14,'400') ] ) ],
    [ _w( 'elementskey_counter', [ 'counter_number' => 150, 'counter_suffix' => '+', 'counter_title' => 'Projects Done', 'number_color' => '#fff', 'title_color' => '#d1fae5', 'prefix_suffix_color' => '#d1fae5', 'counter_bg' => '#059669', 'counter_border_radius' => _dim(12), 'counter_padding' => _dim(30), 'number_typography' => _typo(48,'800'), 'title_typography' => _typo(14,'400') ] ) ],
    [ _w( 'elementskey_counter', [ 'counter_number' => 99, 'counter_suffix' => '%', 'counter_title' => 'Satisfaction', 'number_color' => '#f43f5e', 'title_color' => '#9ca3af', 'counter_bg' => '#fff1f2', 'counter_border' => _bdr(2,'#fecdd3'), 'counter_border_radius' => _dim(20), 'counter_padding' => _dim(30), 'number_typography' => _typo(48,'800'), 'title_typography' => _typo(14,'500') ] ) ],
], $b++ ) );

// ACCORDION
array_push( $e, ..._show( 'Accordion', [
    [ _w( 'elementskey_accordion', [ 'accordion_items' => [ ['acc_title'=>'What is ElementsKey?','acc_content'=>'A powerful Elementor addon with 73 widgets.','acc_active'=>'yes'], ['acc_title'=>'Do I need Elementor Pro?','acc_content'=>'No! It works with the free version.'], ['acc_title'=>'Can I use on client sites?','acc_content'=>'Yes, unlimited sites.'] ], 'show_icon' => 'yes', 'header_color' => '#1e40af', 'header_bg' => '#eff6ff', 'header_active_bg' => '#3b82f6', 'header_radius' => ['size'=>8,'unit'=>'px'], 'content_color' => '#374151' ] ) ],
    [ _w( 'elementskey_accordion', [ 'accordion_items' => [ ['acc_title'=>'How to install?','acc_content'=>'Upload and activate.','acc_active'=>'yes'], ['acc_title'=>'Translation ready?','acc_content'=>'Yes, text domain: elementskey.'], ['acc_title'=>'Any theme?','acc_content'=>'Yes, all themes.'] ], 'header_color' => '#065f46', 'header_bg' => '#ecfdf5', 'header_active_bg' => '#10b981', 'header_radius' => ['size'=>0,'unit'=>'px'], 'content_color' => '#4b5563' ] ) ],
    [ _w( 'elementskey_accordion', [ 'accordion_items' => [ ['acc_title'=>'Performance','acc_content'=>'Lightweight and fast.','acc_active'=>'yes'], ['acc_title'=>'Updates','acc_content'=>'Regular updates.'], ['acc_title'=>'Support','acc_content'=>'GitHub issues.'] ], 'header_color' => '#9f1239', 'header_bg' => '#fff1f2', 'header_active_bg' => '#f43f5e', 'header_radius' => ['size'=>16,'unit'=>'px'], 'content_color' => '#6b7280' ] ) ],
], $b++ ) );

// TABS
array_push( $e, ..._show( 'Tabs', [
    [ _w( 'elementskey_tabs', [ 'tabs' => [ ['tab_title'=>'Overview','tab_content'=>'Lightweight addon with 73 widgets.','tab_icon'=>['value'=>'fas fa-info-circle','library'=>'fa-solid']], ['tab_title'=>'Features','tab_content'=>'Loop Grid, Theme Builder, Widget Manager.','tab_icon'=>['value'=>'fas fa-cog','library'=>'fa-solid']], ['tab_title'=>'Reviews','tab_content'=>'Rated 5 stars!','tab_icon'=>['value'=>'fas fa-star','library'=>'fa-solid']] ], 'tab_color' => '#9ca3af', 'tab_active_color' => '#4361ee', 'tab_bg' => '#f9fafb', 'tab_active_bg' => '#fff', 'content_color' => '#374151', 'content_radius' => ['size'=>12,'unit'=>'px'] ] ) ],
    [ _w( 'elementskey_tabs', [ 'tabs' => [ ['tab_title'=>'Design','tab_content'=>'Beautiful modern designs.','tab_icon'=>['value'=>'fas fa-paint-brush','library'=>'fa-solid']], ['tab_title'=>'Speed','tab_content'=>'Zero bloat.','tab_icon'=>['value'=>'fas fa-bolt','library'=>'fa-solid']], ['tab_title'=>'Support','tab_content'=>'GitHub support.','tab_icon'=>['value'=>'fas fa-headset','library'=>'fa-solid']] ], 'tab_position' => 'left', 'tab_color' => '#6b7280', 'tab_active_color' => '#059669', 'tab_bg' => '#f3f4f6', 'tab_active_bg' => '#ecfdf5', 'content_color' => '#4b5563' ] ) ],
    [ _w( 'elementskey_tabs', [ 'tabs' => [ ['tab_title'=>'Free','tab_content'=>'Works with Elementor free.','tab_icon'=>['value'=>'fas fa-check-circle','library'=>'fa-solid']], ['tab_title'=>'Pro','tab_content'=>'No Pro needed.','tab_icon'=>['value'=>'fas fa-gem','library'=>'fa-solid']] ], 'tab_color' => '#9ca3af', 'tab_active_color' => '#f43f5e', 'tab_bg' => '#fef2f2', 'tab_active_bg' => '#fff', 'content_color' => '#374151', 'content_radius' => ['size'=>8,'unit'=>'px'] ] ) ],
], $b++ ) );

// TESTIMONIAL
array_push( $e, ..._show( 'Testimonial', [
    [ _w( 'elementskey_testimonial', [ 'testimonial_quote' => 'This plugin transformed how we build pages.', 'testimonial_author' => 'Sarah Johnson', 'testimonial_role' => 'CEO, TechCorp', 'testimonial_rating' => '5', 'card_bg' => '#fff', 'card_radius' => ['size'=>16,'unit'=>'px'], 'card_shadow' => _shd('#00000010'), 'card_padding' => _dim(30), 'quote_color' => '#374151', 'quote_mark_color' => '#4361ee', 'author_color' => '#111827', 'role_color' => '#6b7280' ] ) ],
    [ _w( 'elementskey_testimonial', [ 'testimonial_quote' => 'Best Elementor addon. Highly recommended!', 'testimonial_author' => 'Mike Rodriguez', 'testimonial_role' => 'Developer', 'testimonial_rating' => '4', 'card_bg' => '#3b82f6', 'card_radius' => ['size'=>12,'unit'=>'px'], 'card_padding' => _dim(30), 'quote_color' => '#fff', 'quote_mark_color' => '#93c5fd', 'author_color' => '#fff', 'role_color' => '#bfdbfe' ] ) ],
    [ _w( 'elementskey_testimonial', [ 'testimonial_quote' => 'Clean code, great performance.', 'testimonial_author' => 'Anna Kim', 'testimonial_role' => 'Designer', 'testimonial_rating' => '5', 'card_bg' => '#fff1f2', 'card_radius' => ['size'=>20,'unit'=>'px'], 'card_border' => _bdr(1,'#fecdd3'), 'card_padding' => _dim(30), 'quote_color' => '#881337', 'quote_mark_color' => '#fb7185', 'author_color' => '#9f1239', 'role_color' => '#fda4af' ] ) ],
], $b++ ) );

// BLOCKQUOTE
array_push( $e, ..._show( 'Blockquote', [
    [ _w( 'elementskey_blockquote', [ 'blockquote_content' => 'Design is not just what it looks like. Design is how it works.', 'blockquote_author' => 'Steve Jobs', 'quote_color' => '#1e40af', 'author_color' => '#6b7280', 'quote_typography' => _typo(20,'600') ] ) ],
    [ _w( 'elementskey_blockquote', [ 'blockquote_content' => 'Simplicity is the ultimate sophistication.', 'blockquote_author' => 'Leonardo da Vinci', 'quote_color' => '#065f46', 'author_color' => '#059669', 'quote_typography' => _typo(22,'700') ] ) ],
    [ _w( 'elementskey_blockquote', [ 'blockquote_content' => 'Code is like humor. When you explain it, it is bad.', 'blockquote_author' => 'Cory House', 'quote_color' => '#9f1239', 'author_color' => '#f43f5e', 'quote_typography' => _typo(18,'500') ] ) ],
], $b++ ) );

// CALL TO ACTION
array_push( $e, ..._show( 'Call to Action', [
    [ _w( 'elementskey_call_to_action', [ 'cta_title' => 'Ready to Get Started?', 'cta_description' => 'Join 10,000+ happy customers.', 'cta_button_text' => 'Get Started Free', 'cta_overlay' => '#4361ee20', 'cta_title_color' => '#1e40af', 'cta_desc_color' => '#3b82f6', 'cta_btn_bg' => '#4361ee', 'btn_text_color' => '#fff', 'cta_border_radius' => _rad(12), 'cta_shadow' => _shd('#4361ee20') ] ) ],
    [ _w( 'elementskey_call_to_action', [ 'cta_title' => 'Build Something Amazing', 'cta_description' => '73 widgets at your fingertips.', 'cta_button_text' => 'Explore Widgets', 'cta_overlay' => '#10b98120', 'cta_title_color' => '#065f46', 'cta_desc_color' => '#10b981', 'cta_btn_bg' => '#10b981', 'btn_text_color' => '#fff', 'cta_border_radius' => _rad(0) ] ) ],
    [ _w( 'elementskey_call_to_action', [ 'cta_title' => 'Need Help?', 'cta_description' => 'Our support team is ready.', 'cta_button_text' => 'Contact Support', 'cta_overlay' => '#f43f5e20', 'cta_title_color' => '#9f1239', 'cta_desc_color' => '#f43f5e', 'cta_btn_bg' => '#f43f5e', 'btn_text_color' => '#fff', 'cta_border_radius' => _rad(24), 'cta_shadow' => _shd('#f43f5e20',6,25) ] ) ],
], $b++ ) );

// FLIP BOX
array_push( $e, ..._show( 'Flip Box', [
    [ _w( 'elementskey_flip_box', [ 'front_title' => 'Hover Me!', 'front_description' => 'Click to see the back', 'back_title' => 'Surprise!', 'back_description' => 'Back content here.', 'flip_icon' => ['value'=>'fas fa-sync-alt','library'=>'fa-solid'], 'flip_front_bg' => '#4361ee', 'flip_back_bg' => '#1e40af', 'flip_front_color' => '#fff', 'flip_back_color' => '#fff', 'front_icon_color' => '#fff', 'front_icon_size' => ['size'=>40,'unit'=>'px'], 'flip_border_radius' => _rad(16), 'btn_bg' => '#fff', 'btn_color' => '#1e40af' ] ) ],
    [ _w( 'elementskey_flip_box', [ 'front_title' => 'Features', 'front_description' => 'See what we offer', 'back_title' => '73 Widgets', 'back_description' => 'All included.', 'flip_icon' => ['value'=>'fas fa-cog','library'=>'fa-solid'], 'flip_front_bg' => '#10b981', 'flip_back_bg' => '#065f46', 'flip_front_color' => '#fff', 'flip_back_color' => '#fff', 'front_icon_color' => '#fff', 'front_icon_size' => ['size'=>36,'unit'=>'px'], 'flip_border_radius' => _rad(0), 'btn_bg' => '#fff', 'btn_color' => '#065f46' ] ) ],
    [ _w( 'elementskey_flip_box', [ 'front_title' => 'About Us', 'front_description' => 'Learn more', 'back_title' => 'Our Story', 'back_description' => '12 years experience.', 'flip_icon' => ['value'=>'fas fa-info-circle','library'=>'fa-solid'], 'flip_front_bg' => '#f43f5e', 'flip_back_bg' => '#9f1239', 'flip_front_color' => '#fff', 'flip_back_color' => '#fff', 'front_icon_color' => '#fff', 'front_icon_size' => ['size'=>44,'unit'=>'px'], 'flip_border_radius' => _rad(24), 'btn_bg' => '#fff', 'btn_color' => '#9f1239' ] ) ],
], $b++ ) );

// PRICE TABLE
array_push( $e, ..._show( 'Price Table', [
    [ _w( 'elementskey_price_table', [ 'pt_plan_name' => 'Starter', 'pt_price' => '$9', 'pt_period' => '/mo', 'pt_features' => [ ['pt_feature_text'=>'5 Projects','pt_feature_included'=>'yes'], ['pt_feature_text'=>'10GB Storage','pt_feature_included'=>'yes'] ], 'pt_button_text' => 'Choose Plan', 'pt_header_bg' => '#eff6ff', 'pt_header_color' => '#1e40af', 'price_color' => '#4361ee', 'feature_color' => '#4b5563', 'feature_included_color' => '#2563eb', 'pt_btn_bg' => '#4361ee', 'btn_text_color' => '#fff', 'card_border_radius' => _rad(12) ] ) ],
    [ _w( 'elementskey_price_table', [ 'pt_plan_name' => 'Professional', 'pt_price' => '$29', 'pt_period' => '/mo', 'pt_highlighted' => 'yes', 'pt_features' => [ ['pt_feature_text'=>'Unlimited Projects','pt_feature_included'=>'yes'], ['pt_feature_text'=>'Priority Support','pt_feature_included'=>'yes'] ], 'pt_button_text' => 'Choose Plan', 'pt_header_bg' => '#10b981', 'pt_header_color' => '#fff', 'price_color' => '#fff', 'feature_color' => '#374151', 'feature_included_color' => '#10b981', 'pt_btn_bg' => '#10b981', 'btn_text_color' => '#fff', 'pt_featured_border' => '#10b981', 'card_border_radius' => _rad(16), 'card_shadow' => _shd('#10b98130',8,30) ] ) ],
    [ _w( 'elementskey_price_table', [ 'pt_plan_name' => 'Enterprise', 'pt_price' => '$99', 'pt_period' => '/mo', 'pt_features' => [ ['pt_feature_text'=>'Everything in Pro','pt_feature_included'=>'yes'] ], 'pt_button_text' => 'Contact Sales', 'pt_header_bg' => '#fff1f2', 'pt_header_color' => '#9f1239', 'price_color' => '#f43f5e', 'feature_color' => '#4b5563', 'feature_included_color' => '#f43f5e', 'pt_btn_bg' => '#f43f5e', 'btn_text_color' => '#fff', 'card_border_radius' => _rad(8), 'card_border' => _bdr(2,'#fecdd3') ] ) ],
], $b++ ) );

// PRICE LIST
array_push( $e, ..._show( 'Price List', [
    [ _w( 'elementskey_price_list', [ 'price_list_items' => [ ['pl_title'=>'Web Design','pl_price'=>'$1,200','pl_description'=>'Custom website'], ['pl_title'=>'SEO','pl_price'=>'$500','pl_description'=>'Full SEO'], ['pl_title'=>'Maintenance','pl_price'=>'$99/mo','pl_description'=>'Updates'] ], 'pl_title_color' => '#1e40af', 'pl_price_color' => '#4361ee', 'pl_desc_color' => '#6b7280', 'line_color' => '#dbeafe', 'item_padding' => _dim(16), 'item_border_radius' => _rad(8) ] ) ],
    [ _w( 'elementskey_price_list', [ 'price_list_items' => [ ['pl_title'=>'Logo Design','pl_price'=>'$299','pl_description'=>'Brand identity'], ['pl_title'=>'Business Card','pl_price'=>'$49','pl_description'=>'Premium cards'] ], 'pl_title_color' => '#065f46', 'pl_price_color' => '#10b981', 'pl_desc_color' => '#6b7280', 'line_color' => '#d1fae5', 'item_padding' => _dim(20) ] ) ],
    [ _w( 'elementskey_price_list', [ 'price_list_items' => [ ['pl_title'=>'Monthly','pl_price'=>'$199/mo','pl_description'=>'All inclusive'], ['pl_title'=>'Annual','pl_price'=>'$1,999/yr','pl_description'=>'Save 2 months','pl_featured'=>'yes'] ], 'pl_title_color' => '#9f1239', 'pl_price_color' => '#f43f5e', 'pl_desc_color' => '#6b7280', 'line_color' => '#fecdd3', 'item_padding' => _dim(14), 'item_border' => _bdr(1,'#fecdd3'), 'item_border_radius' => _rad(12) ] ) ],
], $b++ ) );

// DATA TABLE
array_push( $e, ..._show( 'Data Table', [
    [ _w( 'elementskey_data_table', [ 'table_title' => 'Widget Comparison', 'table_rows' => [ ['row_label'=>'Widgets','row_value'=>'73'], ['row_label'=>'Categories','row_value'=>'4'], ['row_label'=>'Performance','row_value'=>'Lightweight'] ], 'table_bg' => '#fff', 'table_border' => '#e5e7eb', 'table_radius' => ['size'=>12,'unit'=>'px'], 'title_color' => '#1e40af', 'label_color' => '#374151', 'value_color' => '#4361ee', 'row_bg' => '#f9fafb', 'row_alt_bg' => '#fff', 'row_border_color' => '#e5e7eb' ] ) ],
    [ _w( 'elementskey_data_table', [ 'table_title' => 'Plan Features', 'table_rows' => [ ['row_label'=>'Price','row_value'=>'Free'], ['row_label'=>'Support','row_value'=>'GitHub'] ], 'table_bg' => '#ecfdf5', 'table_border' => '#d1fae5', 'table_radius' => ['size'=>0,'unit'=>'px'], 'title_color' => '#065f46', 'label_color' => '#374151', 'value_color' => '#10b981', 'row_bg' => '#f0fdf4', 'row_alt_bg' => '#ecfdf5' ] ) ],
    [ _w( 'elementskey_data_table', [ 'table_title' => 'System Info', 'table_rows' => [ ['row_label'=>'PHP','row_value'=>'7.4+'], ['row_label'=>'WordPress','row_value'=>'6.0+'] ], 'table_bg' => '#fff1f2', 'table_border' => '#fecdd3', 'table_radius' => ['size'=>16,'unit'=>'px'], 'title_color' => '#9f1239', 'label_color' => '#374151', 'value_color' => '#f43f5e', 'row_bg' => '#fef2f2', 'row_alt_bg' => '#fff1f2' ] ) ],
], $b++ ) );

// FEATURE COMPARISON TABLE
array_push( $e, ..._show( 'Feature Comparison Table', [
    [ _w( 'elementskey_feature_comparison_table', [ 'table_title' => 'ElementsKey vs Others', 'rows' => [ ['feature_text'=>'73 Widgets','value_type'=>'icon','value_icon'=>'fas fa-check'], ['feature_text'=>'Theme Builder','value_type'=>'icon','value_icon'=>'fas fa-check'], ['feature_text'=>'Loop Grid','value_type'=>'icon','value_icon'=>'fas fa-check'] ], 'table_background' => '#fff', 'table_border_color' => '#e5e7eb', 'table_border_radius' => ['size'=>12,'unit'=>'px'], 'header_background' => '#4361ee', 'header_text_color' => '#fff', 'row_background' => '#f9fafb', 'row_alt_background' => '#eff6ff', 'feature_text_color' => '#374151', 'value_text_color' => '#10b981', 'value_icon_color' => '#10b981' ] ) ],
    [ _w( 'elementskey_feature_comparison_table', [ 'table_title' => 'Free vs Pro', 'rows' => [ ['feature_text'=>'All 73 Widgets','value_type'=>'icon','value_icon'=>'fas fa-check'], ['feature_text'=>'Header/Footer','value_type'=>'icon','value_icon'=>'fas fa-check'] ], 'table_background' => '#fff', 'table_border_color' => '#d1fae5', 'table_border_radius' => ['size'=>0,'unit'=>'px'], 'header_background' => '#10b981', 'header_text_color' => '#fff', 'row_background' => '#f0fdf4', 'row_alt_background' => '#ecfdf5', 'feature_text_color' => '#374151', 'value_text_color' => '#059669', 'value_icon_color' => '#059669' ] ) ],
    [ _w( 'elementskey_feature_comparison_table', [ 'table_title' => 'Compatibility', 'rows' => [ ['feature_text'=>'Any Theme','value_type'=>'icon','value_icon'=>'fas fa-check'], ['feature_text'=>'Elementor Free','value_type'=>'icon','value_icon'=>'fas fa-check'] ], 'table_background' => '#fff', 'table_border_color' => '#fecdd3', 'table_border_radius' => ['size'=>16,'unit'=>'px'], 'header_background' => '#f43f5e', 'header_text_color' => '#fff', 'row_background' => '#fef2f2', 'row_alt_background' => '#fff1f2', 'feature_text_color' => '#374151', 'value_text_color' => '#e11d48', 'value_icon_color' => '#e11d48' ] ) ],
], $b++ ) );

// ICON LIST
array_push( $e, ..._show( 'Icon List', [
    [ _w( 'elementskey_icon_list', [ 'icon_list' => [ ['list_text'=>'Easy to install','list_icon'=>'fas fa-check-circle'], ['list_text'=>'73 widgets','list_icon'=>'fas fa-check-circle'], ['list_text'=>'Any theme','list_icon'=>'fas fa-check-circle'] ], 'icon_color' => '#2563eb', 'icon_bg' => '#dbeafe', 'icon_size' => ['size'=>20,'unit'=>'px'], 'text_color' => '#374151', 'item_padding' => _dim(12), 'item_border_radius' => _rad(8) ] ) ],
    [ _w( 'elementskey_icon_list', [ 'icon_list' => [ ['list_text'=>'Lightweight','list_icon'=>'fas fa-bolt'], ['list_text'=>'Responsive','list_icon'=>'fas fa-mobile-alt'], ['list_text'=>'SEO friendly','list_icon'=>'fas fa-search'] ], 'icon_color' => '#059669', 'icon_bg' => '#d1fae5', 'icon_size' => ['size'=>22,'unit'=>'px'], 'text_color' => '#374151', 'item_padding' => _dim(14) ] ) ],
    [ _w( 'elementskey_icon_list', [ 'icon_list' => [ ['list_text'=>'24/7 support','list_icon'=>'fas fa-headset'], ['list_text'=>'Regular updates','list_icon'=>'fas fa-sync'] ], 'icon_color' => '#e11d48', 'icon_bg' => '#ffe4e6', 'icon_size' => ['size'=>18,'unit'=>'px'], 'text_color' => '#374151', 'item_padding' => _dim(10), 'item_border' => _bdr(1,'#fecdd3'), 'item_border_radius' => _rad(12) ] ) ],
], $b++ ) );

// PROGRESS BAR
array_push( $e, ..._show( 'Progress Bar', [
    [ _w( 'elementskey_progress_bar', [ 'bars' => [ ['title'=>'HTML/CSS','percentage'=>['size'=>90]] ], 'bar_color' => '#4361ee', 'bg_color' => '#e5e7eb', 'title_color' => '#1e40af', 'percentage_color' => '#4361ee', 'bar_radius' => _rad(8), 'height' => ['size'=>12,'unit'=>'px'] ] ) ],
    [ _w( 'elementskey_progress_bar', [ 'bars' => [ ['title'=>'JavaScript','percentage'=>['size'=>75]] ], 'bar_color' => '#10b981', 'bg_color' => '#d1fae5', 'title_color' => '#065f46', 'percentage_color' => '#10b981', 'bar_radius' => _rad(0), 'height' => ['size'=>16,'unit'=>'px'] ] ) ],
    [ _w( 'elementskey_progress_bar', [ 'bars' => [ ['title'=>'PHP','percentage'=>['size'=>60]] ], 'bar_color' => '#f43f5e', 'bg_color' => '#ffe4e6', 'title_color' => '#9f1239', 'percentage_color' => '#f43f5e', 'bar_radius' => _rad(20), 'height' => ['size'=>10,'unit'=>'px'] ] ) ],
], $b++ ) );

// PROGRESS TRACKER
array_push( $e, ..._show( 'Progress Tracker', [
    [ _w( 'elementskey_progress_tracker', [ 'tracker_title' => 'Order Progress', 'progress_fill_color' => '#4361ee', 'progress_text_color' => '#1e40af', 'progress_height' => ['size'=>20,'unit'=>'px'] ] ) ],
    [ _w( 'elementskey_progress_tracker', [ 'tracker_title' => 'Course Progress', 'progress_fill_color' => '#10b981', 'progress_text_color' => '#065f46', 'progress_height' => ['size'=>14,'unit'=>'px'] ] ) ],
    [ _w( 'elementskey_progress_tracker', [ 'tracker_title' => 'Project Status', 'progress_fill_color' => '#f43f5e', 'progress_text_color' => '#9f1239', 'progress_height' => ['size'=>8,'unit'=>'px'] ] ) ],
], $b++ ) );

// STAR RATING
array_push( $e, ..._show( 'Star Rating', [
    [ _w( 'elementskey_star_rating', [ 'rating_value' => ['size'=>5], 'rating_scale' => '5', 'show_number' => 'yes', 'star_color' => '#f7b500', 'empty_star_color' => '#e5e7eb', 'number_color' => '#f7b500', 'star_size' => ['size'=>28,'unit'=>'px'] ] ) ],
    [ _w( 'elementskey_star_rating', [ 'rating_value' => ['size'=>4.5], 'rating_scale' => '5', 'show_number' => 'yes', 'star_color' => '#10b981', 'empty_star_color' => '#d1fae5', 'number_color' => '#10b981', 'star_size' => ['size'=>32,'unit'=>'px'] ] ) ],
    [ _w( 'elementskey_star_rating', [ 'rating_value' => ['size'=>3], 'rating_scale' => '5', 'show_number' => 'yes', 'star_color' => '#f43f5e', 'empty_star_color' => '#ffe4e6', 'number_color' => '#f43f5e', 'star_size' => ['size'=>24,'unit'=>'px'] ] ) ],
], $b++ ) );

// ANIMATED HEADLINE
array_push( $e, ..._show( 'Animated Headline', [
    [ _w( 'elementskey_animated_headline', [ 'animated_headline' => 'We build', 'headline_style' => 'rotating', 'rotating_text' => ['websites','apps','stores','brands'], 'before_color' => '#111827', 'words_color' => '#4361ee', 'words_highlight_color' => '#dbeafe', 'words_padding' => _dim4(2,8,2,8), 'words_radius' => _rad(6) ] ) ],
    [ _w( 'elementskey_animated_headline', [ 'animated_headline' => 'Learn', 'headline_style' => 'rotating', 'rotating_text' => ['HTML','CSS','JavaScript','PHP'], 'before_color' => '#111827', 'words_color' => '#10b981', 'words_highlight_color' => '#d1fae5', 'words_padding' => _dim4(4,12,4,12), 'words_radius' => _rad(0) ] ) ],
    [ _w( 'elementskey_animated_headline', [ 'animated_headline' => 'Create', 'headline_style' => 'rotating', 'rotating_text' => ['designs','experiences','solutions'], 'before_color' => '#111827', 'words_color' => '#f43f5e', 'words_highlight_color' => '#ffe4e6', 'words_padding' => _dim4(2,10,2,10), 'words_radius' => _rad(50) ] ) ],
], $b++ ) );

// COUNTDOWN
array_push( $e, ..._show( 'Countdown', [
    [ _w( 'elementskey_countdown', [ 'due_date' => gmdate('Y-m-d\TH:i:s', strtotime('+7 days')), 'show_labels' => 'yes', 'box_bg' => '#eff6ff', 'box_border_radius' => _dim(12), 'box_padding' => _dim(20), 'box_gap' => ['size'=>10,'unit'=>'px'], 'countdown_number_color' => '#1e40af', 'countdown_label_color' => '#6b7280', 'box_shadow' => _shd('#4361ee15',2,10) ] ) ],
    [ _w( 'elementskey_countdown', [ 'due_date' => gmdate('Y-m-d\TH:i:s', strtotime('+30 days')), 'show_labels' => 'yes', 'box_bg' => '#ecfdf5', 'box_border_radius' => _dim(0), 'box_padding' => _dim(16), 'box_gap' => ['size'=>4,'unit'=>'px'], 'countdown_number_color' => '#065f46', 'countdown_label_color' => '#6b7280', 'box_border' => _bdr(1,'#d1fae5') ] ) ],
    [ _w( 'elementskey_countdown', [ 'due_date' => gmdate('Y-m-d\TH:i:s', strtotime('+90 days')), 'show_labels' => 'yes', 'box_bg' => '#fff1f2', 'box_border_radius' => _dim(20), 'box_padding' => _dim(24), 'box_gap' => ['size'=>12,'unit'=>'px'], 'countdown_number_color' => '#9f1239', 'countdown_label_color' => '#6b7280', 'box_shadow' => _shd('#f43f5e20',4,15) ] ) ],
], $b++ ) );

// CODE HIGHLIGHT
array_push( $e, ..._show( 'Code Highlight', [
    [ _w( 'elementskey_code_highlight', [ 'code_language' => 'javascript', 'show_copy_btn' => 'yes', 'code_content' => "const greeting = \"Hello World\";\nconsole.log(greeting);", 'code_bg_color' => '#0f172a', 'code_text_color' => '#e2e8f0', 'code_radius' => _rad(12), 'code_padding' => _dim(20), 'copy_bg' => '#4361ee', 'copy_color' => '#fff' ] ) ],
    [ _w( 'elementskey_code_highlight', [ 'code_language' => 'php', 'show_copy_btn' => 'yes', 'code_content' => "<?php\necho \"Hello\";\n?>", 'code_bg_color' => '#ecfdf5', 'code_text_color' => '#065f46', 'code_radius' => _rad(0), 'code_padding' => _dim(16), 'copy_bg' => '#10b981', 'copy_color' => '#fff' ] ) ],
    [ _w( 'elementskey_code_highlight', [ 'code_language' => 'css', 'show_copy_btn' => 'yes', 'code_content' => ".btn {\n  background: #f43f5e;\n  color: white;\n}", 'code_bg_color' => '#1e1e2e', 'code_text_color' => '#f5c2e7', 'code_radius' => _rad(16), 'code_padding' => _dim(24), 'code_border' => _bdr(1,'#f43f5e40'), 'copy_bg' => '#f43f5e', 'copy_color' => '#fff' ] ) ],
], $b++ ) );

// TABLE OF CONTENT
array_push( $e, ..._show( 'Table of Content', [
    [ _w( 'elementskey_table_of_content', [ 'toc_title' => 'Table of Contents', 'toc_title_color' => '#1e40af', 'toc_item_color' => '#4361ee', 'toc_item_hover_color' => '#2563eb', 'toc_wrapper_bg' => '#eff6ff', 'toc_wrapper_padding' => _dim(20), 'toc_wrapper_border_radius' => _rad(12), 'toc_item_padding' => _dim4(8,12,8,12) ] ) ],
    [ _w( 'elementskey_table_of_content', [ 'toc_title' => 'On This Page', 'toc_title_color' => '#065f46', 'toc_item_color' => '#10b981', 'toc_item_hover_color' => '#059669', 'toc_wrapper_bg' => '#ecfdf5', 'toc_wrapper_padding' => _dim(16), 'toc_wrapper_border_radius' => _rad(0), 'toc_item_padding' => _dim4(10,16,10,16) ] ) ],
    [ _w( 'elementskey_table_of_content', [ 'toc_title' => 'Navigation', 'toc_title_color' => '#9f1239', 'toc_item_color' => '#f43f5e', 'toc_item_hover_color' => '#e11d48', 'toc_wrapper_bg' => '#fff1f2', 'toc_wrapper_padding' => _dim(24), 'toc_wrapper_border_radius' => _rad(20), 'toc_wrapper_border' => _bdr(1,'#fecdd3'), 'toc_item_padding' => _dim4(6,10,6,10), 'toc_item_border_radius' => _rad(8) ] ) ],
], $b++ ) );

// REVIEWS
array_push( $e, ..._show( 'Reviews', [
    [ _w( 'elementskey_reviews', [ 'reviews' => [ ['rv_name'=>'Mike R.','rv_role'=>'Developer','rv_comment'=>'Love this addon!','rv_rating'=>5], ['rv_name'=>'Anna K.','rv_role'=>'Designer','rv_comment'=>'Great widgets.','rv_rating'=>4] ], 'rv_card_bg' => '#fff', 'card_border_radius' => _rad(12), 'card_padding' => _dim(24), 'rv_text_color' => '#374151', 'rv_name_color' => '#1e40af', 'role_color' => '#6b7280', 'rv_star_color' => '#f7b500', 'card_shadow' => _shd('#00000010',2,10) ] ) ],
    [ _w( 'elementskey_reviews', [ 'reviews' => [ ['rv_name'=>'John D.','rv_role'=>'Owner','rv_comment'=>'Best addon.','rv_rating'=>5], ['rv_name'=>'Lisa M.','rv_role'=>'Marketer','rv_comment'=>'Easy and fast.','rv_rating'=>5] ], 'rv_card_bg' => '#ecfdf5', 'card_border_radius' => _rad(0), 'card_padding' => _dim(20), 'rv_text_color' => '#374151', 'rv_name_color' => '#065f46', 'role_color' => '#059669', 'rv_star_color' => '#10b981' ] ) ],
    [ _w( 'elementskey_reviews', [ 'reviews' => [ ['rv_name'=>'Tom B.','rv_role'=>'Freelancer','rv_comment'=>'Saves hours.','rv_rating'=>5] ], 'rv_card_bg' => '#fff1f2', 'card_border_radius' => _rad(16), 'card_padding' => _dim(28), 'rv_text_color' => '#374151', 'rv_name_color' => '#9f1239', 'role_color' => '#f43f5e', 'rv_star_color' => '#f43f5e', 'card_border' => _bdr(1,'#fecdd3') ] ) ],
], $b++ ) );

// TESTIMONIAL CAROUSEL
array_push( $e, ..._show( 'Testimonial Carousel', [
    [ _w( 'elementskey_testimonial_carousel', [ 'testimonials' => [ ['tc_name'=>'Sarah','tc_role'=>'CEO','tc_content'=>'Transformed our workflow.','tc_rating'=>5], ['tc_name'=>'Mike','tc_role'=>'Dev','tc_content'=>'Best addon.','tc_rating'=>4], ['tc_name'=>'Anna','tc_role'=>'Designer','tc_content'=>'Beautiful widgets.','tc_rating'=>5] ], 'tc_card_bg' => '#fff', 'card_border_radius' => _rad(12), 'card_padding' => _dim(24), 'tc_text_color' => '#374151', 'tc_name_color' => '#1e40af', 'tc_role_color' => '#6b7280', 'tc_star_color' => '#f7b500', 'card_shadow' => _shd('#00000010',2,10) ] ) ],
    [ _w( 'elementskey_testimonial_carousel', [ 'testimonials' => [ ['tc_name'=>'John','tc_role'=>'Director','tc_content'=>'Highly recommended.','tc_rating'=>5], ['tc_name'=>'Emily','tc_role'=>'Manager','tc_content'=>'Excellent support.','tc_rating'=>5] ], 'tc_card_bg' => '#3b82f6', 'card_border_radius' => _rad(8), 'card_padding' => _dim(24), 'tc_text_color' => '#fff', 'tc_name_color' => '#fff', 'tc_role_color' => '#bfdbfe', 'tc_star_color' => '#fbbf24' ] ) ],
    [ _w( 'elementskey_testimonial_carousel', [ 'testimonials' => [ ['tc_name'=>'Chris','tc_role'=>'Founder','tc_content'=>'Game changer.','tc_rating'=>5] ], 'tc_card_bg' => '#fff1f2', 'card_border_radius' => _rad(20), 'card_padding' => _dim(28), 'tc_text_color' => '#374151', 'tc_name_color' => '#9f1239', 'tc_role_color' => '#f43f5e', 'tc_star_color' => '#f43f5e', 'card_border' => _bdr(1,'#fecdd3') ] ) ],
], $b++ ) );

// HOTSPOT
array_push( $e, ..._show( 'Hotspot', [
    [ _w( 'elementskey_hotspot', [ 'hotspot_image' => ['url'=>'https://picsum.photos/seed/h1/600/400'], 'hotspot_markers' => [ ['hotspot_left'=>30,'hotspot_top'=>40,'hotspot_label'=>'A','hotspot_description'=>'Cloud sync.'], ['hotspot_left'=>70,'hotspot_top'=>60,'hotspot_label'=>'B','hotspot_description'=>'Analytics.'] ], 'hotspot_dot_color' => '#4361ee', 'hotspot_dot_size' => ['size'=>20,'unit'=>'px'], 'pulse_color' => '#4361ee40', 'label_color' => '#fff', 'label_bg' => '#4361ee', 'hotspot_tooltip_bg' => '#1e293b', 'hotspot_tooltip_color' => '#fff' ] ) ],
    [ _w( 'elementskey_hotspot', [ 'hotspot_image' => ['url'=>'https://picsum.photos/seed/h2/600/400'], 'hotspot_markers' => [ ['hotspot_left'=>50,'hotspot_top'=>30,'hotspot_label'=>'Click','hotspot_description'=>'Action.'] ], 'hotspot_dot_color' => '#10b981', 'hotspot_dot_size' => ['size'=>16,'unit'=>'px'], 'pulse_color' => '#10b98140', 'label_color' => '#fff', 'label_bg' => '#10b981', 'hotspot_tooltip_bg' => '#065f46', 'hotspot_tooltip_color' => '#fff' ] ) ],
    [ _w( 'elementskey_hotspot', [ 'hotspot_image' => ['url'=>'https://picsum.photos/seed/h3/600/400'], 'hotspot_markers' => [ ['hotspot_left'=>20,'hotspot_top'=>50,'hotspot_label'=>'Info','hotspot_description'=>'Details.'], ['hotspot_left'=>80,'hotspot_top'=>30,'hotspot_label'=>'Go','hotspot_description'=>'Start.'] ], 'hotspot_dot_color' => '#f43f5e', 'hotspot_dot_size' => ['size'=>24,'unit'=>'px'], 'pulse_color' => '#f43f5e40', 'label_color' => '#fff', 'label_bg' => '#f43f5e', 'hotspot_tooltip_bg' => '#9f1239', 'hotspot_tooltip_color' => '#fff' ] ) ],
], $b++ ) );

// BASIC GALLERY
array_push( $e, ..._show( 'Basic Gallery', [
    [ _w( 'elementskey_basic_gallery', [ 'galleries' => [ ['gallery_image'=>['url'=>'https://picsum.photos/seed/g1/400/300'],'gallery_caption'=>'Mountain'], ['gallery_image'=>['url'=>'https://picsum.photos/seed/g2/400/300'],'gallery_caption'=>'Ocean'], ['gallery_image'=>['url'=>'https://picsum.photos/seed/g3/400/300'],'gallery_caption'=>'City'] ], 'columns' => 3, 'image_radius' => ['size'=>12,'unit'=>'px'], 'image_shadow' => _shd('#00000020'), 'caption_color' => '#1e40af' ] ) ],
    [ _w( 'elementskey_basic_gallery', [ 'galleries' => [ ['gallery_image'=>['url'=>'https://picsum.photos/seed/g4/400/300']], ['gallery_image'=>['url'=>'https://picsum.photos/seed/g5/400/300']], ['gallery_image'=>['url'=>'https://picsum.photos/seed/g6/400/300']] ], 'columns' => 3, 'image_radius' => ['size'=>50,'unit'=>'%'], 'caption_color' => '#065f46' ] ) ],
    [ _w( 'elementskey_basic_gallery', [ 'galleries' => [ ['gallery_image'=>['url'=>'https://picsum.photos/seed/g7/400/300']], ['gallery_image'=>['url'=>'https://picsum.photos/seed/g8/400/300']] ], 'columns' => 2, 'image_radius' => ['size'=>0,'unit'=>'px'], 'image_height' => ['size'=>300,'unit'=>'px'], 'caption_color' => '#9f1239' ] ) ],
], $b++ ) );

// IMAGE CAROUSEL
array_push( $e, ..._show( 'Image Carousel', [
    [ _w( 'elementskey_image_carousel', [ 'carousel_images' => [ ['carousel_image'=>['url'=>'https://picsum.photos/seed/ic1/600/400']], ['carousel_image'=>['url'=>'https://picsum.photos/seed/ic2/600/400']], ['carousel_image'=>['url'=>'https://picsum.photos/seed/ic3/600/400']] ], 'slides_per_view' => 2, 'image_radius' => ['size'=>12,'unit'=>'px'], 'arrow_color' => '#fff', 'arrow_bg' => '#4361ee', 'dots_color' => '#cbd5e1', 'dots_active_color' => '#4361ee' ] ) ],
    [ _w( 'elementskey_image_carousel', [ 'carousel_images' => [ ['carousel_image'=>['url'=>'https://picsum.photos/seed/ic4/600/400']], ['carousel_image'=>['url'=>'https://picsum.photos/seed/ic5/600/400']], ['carousel_image'=>['url'=>'https://picsum.photos/seed/ic6/600/400']] ], 'slides_per_view' => 3, 'image_radius' => ['size'=>50,'unit'=>'%'], 'arrow_color' => '#fff', 'arrow_bg' => '#10b981', 'dots_color' => '#d1fae5', 'dots_active_color' => '#10b981' ] ) ],
    [ _w( 'elementskey_image_carousel', [ 'carousel_images' => [ ['carousel_image'=>['url'=>'https://picsum.photos/seed/ic7/600/400']], ['carousel_image'=>['url'=>'https://picsum.photos/seed/ic8/600/400']] ], 'slides_per_view' => 2, 'image_radius' => ['size'=>16,'unit'=>'px'], 'arrow_color' => '#fff', 'arrow_bg' => '#f43f5e', 'dots_color' => '#fecdd3', 'dots_active_color' => '#f43f5e' ] ) ],
], $b++ ) );

// MEDIA CAROUSEL
array_push( $e, ..._show( 'Media Carousel', [
    [ _w( 'elementskey_media_carousel', [ 'media_items' => [ ['mc_image'=>['url'=>'https://picsum.photos/seed/m1/600/400'],'mc_title'=>'One'], ['mc_image'=>['url'=>'https://picsum.photos/seed/m2/600/400'],'mc_title'=>'Two'], ['mc_image'=>['url'=>'https://picsum.photos/seed/m3/600/400'],'mc_title'=>'Three'] ], 'mc_radius' => ['size'=>12,'unit'=>'px'], 'mc_caption_color' => '#fff', 'mc_arrow_color' => '#fff', 'mc_arrow_background' => '#4361ee', 'mc_dots_color' => '#cbd5e1', 'mc_dots_active_color' => '#4361ee' ] ) ],
    [ _w( 'elementskey_media_carousel', [ 'media_items' => [ ['mc_image'=>['url'=>'https://picsum.photos/seed/m4/600/400'],'mc_title'=>'A'], ['mc_image'=>['url'=>'https://picsum.photos/seed/m5/600/400'],'mc_title'=>'B'] ], 'mc_radius' => ['size'=>0,'unit'=>'px'], 'mc_caption_color' => '#fff', 'mc_arrow_color' => '#fff', 'mc_arrow_background' => '#10b981', 'mc_dots_color' => '#d1fae5', 'mc_dots_active_color' => '#10b981' ] ) ],
    [ _w( 'elementskey_media_carousel', [ 'media_items' => [ ['mc_image'=>['url'=>'https://picsum.photos/seed/m6/600/400']], ['mc_image'=>['url'=>'https://picsum.photos/seed/m7/600/400']], ['mc_image'=>['url'=>'https://picsum.photos/seed/m8/600/400']] ], 'mc_radius' => ['size'=>20,'unit'=>'px'], 'mc_caption_color' => '#fff', 'mc_arrow_color' => '#fff', 'mc_arrow_background' => '#f43f5e', 'mc_dots_color' => '#fecdd3', 'mc_dots_active_color' => '#f43f5e' ] ) ],
], $b++ ) );

// CAROUSEL (SWIPER)
array_push( $e, ..._show( 'Carousel', [
    [ _w( 'elementskey_swiper_carousel', [ 'container_bg' => '#eff6ff', 'slide_bg' => '#fff', 'slide_radius' => ['size'=>12,'unit'=>'px'], 'slide_padding' => _dim(24), 'title_color' => '#1e40af', 'description_color' => '#374151', 'button_bg_color' => '#4361ee', 'button_text_color' => '#fff', 'arrow_color' => '#4361ee', 'arrow_bg' => '#fff', 'dots_color' => '#cbd5e1', 'dots_active_color' => '#4361ee' ] ) ],
    [ _w( 'elementskey_swiper_carousel', [ 'container_bg' => '#ecfdf5', 'slide_bg' => '#fff', 'slide_radius' => ['size'=>0,'unit'=>'px'], 'slide_padding' => _dim(20), 'title_color' => '#065f46', 'description_color' => '#374151', 'button_bg_color' => '#10b981', 'button_text_color' => '#fff', 'arrow_color' => '#10b981', 'arrow_bg' => '#fff', 'dots_color' => '#d1fae5', 'dots_active_color' => '#10b981' ] ) ],
    [ _w( 'elementskey_swiper_carousel', [ 'container_bg' => '#fff1f2', 'slide_bg' => '#fff', 'slide_radius' => ['size'=>20,'unit'=>'px'], 'slide_padding' => _dim(28), 'title_color' => '#9f1239', 'description_color' => '#374151', 'button_bg_color' => '#f43f5e', 'button_text_color' => '#fff', 'arrow_color' => '#f43f5e', 'arrow_bg' => '#fff', 'dots_color' => '#fecdd3', 'dots_active_color' => '#f43f5e' ] ) ],
], $b++ ) );

// SLIDES
array_push( $e, ..._show( 'Slides', [
    [ _w( 'elementskey_slides', [ 'slides' => [ ['slide_title'=>'Welcome','slide_subtitle'=>'73 widgets','slide_btn_text'=>'Explore'] ], 'slides_height' => ['size'=>400,'unit'=>'px'], 'slides_overlay' => '#1e40af80', 'slide_title_color' => '#fff', 'slide_subtitle_color' => '#bfdbfe', 'slides_arrow_color' => '#fff', 'slides_arrow_background' => '#4361ee' ] ) ],
    [ _w( 'elementskey_slides', [ 'slides' => [ ['slide_title'=>'Build Pages','slide_subtitle'=>'With Elementor','slide_btn_text'=>'Start'] ], 'slides_height' => ['size'=>350,'unit'=>'px'], 'slides_overlay' => '#065f4680', 'slide_title_color' => '#fff', 'slide_subtitle_color' => '#d1fae5', 'slides_arrow_color' => '#fff', 'slides_arrow_background' => '#10b981' ] ) ],
    [ _w( 'elementskey_slides', [ 'slides' => [ ['slide_title'=>'Lightweight','slide_subtitle'=>'Zero bloat','slide_btn_text'=>'Learn'] ], 'slides_height' => ['size'=>450,'unit'=>'px'], 'slides_overlay' => '#9f123980', 'slide_title_color' => '#fff', 'slide_subtitle_color' => '#fecdd3', 'slides_arrow_color' => '#fff', 'slides_arrow_background' => '#f43f5e' ] ) ],
], $b++ ) );

// OFF-CANVAS
array_push( $e, ..._show( 'Off-Canvas', [
    [ _w( 'elementskey_off_canvas', [ 'offcanvas_title' => 'Menu', 'trigger_text' => 'Open Menu', 'trigger_icon' => ['value'=>'fas fa-bars','library'=>'fa-solid'], 'trigger_color' => '#fff', 'trigger_bg' => '#4361ee', 'trigger_radius' => ['size'=>8,'unit'=>'px'], 'trigger_padding' => _dim4(12,24,12,24), 'panel_bg' => '#fff', 'overlay_color' => '#00000080', 'panel_title_color' => '#1e40af', 'panel_content_color' => '#374151' ] ) ],
    [ _w( 'elementskey_off_canvas', [ 'offcanvas_title' => 'Cart', 'trigger_text' => 'View Cart', 'trigger_icon' => ['value'=>'fas fa-shopping-cart','library'=>'fa-solid'], 'trigger_color' => '#fff', 'trigger_bg' => '#10b981', 'trigger_radius' => ['size'=>50,'unit'=>'%'], 'trigger_padding' => _dim4(14,28,14,28), 'panel_bg' => '#ecfdf5', 'overlay_color' => '#00000060', 'panel_title_color' => '#065f46', 'panel_content_color' => '#374151' ] ) ],
    [ _w( 'elementskey_off_canvas', [ 'offcanvas_title' => 'Settings', 'trigger_text' => 'Open Settings', 'trigger_icon' => ['value'=>'fas fa-cog','library'=>'fa-solid'], 'trigger_color' => '#fff', 'trigger_bg' => '#f43f5e', 'trigger_radius' => ['size'=>12,'unit'=>'px'], 'trigger_padding' => _dim4(10,20,10,20), 'panel_bg' => '#fff1f2', 'overlay_color' => '#00000070', 'panel_title_color' => '#9f1239', 'panel_content_color' => '#374151' ] ) ],
], $b++ ) );

// LINK IN BIO
array_push( $e, ..._show( 'Link in Bio', [
    [ _w( 'elementskey_link_in_bio', [ 'bio_name' => '@brand', 'bio_text' => 'Creator', 'bio_links' => [ ['bio_link_label'=>'Website','bio_link_url'=>['url'=>'#'],'bio_link_icon'=>['value'=>'fas fa-globe','library'=>'fa-solid']], ['bio_link_label'=>'Blog','bio_link_url'=>['url'=>'#'],'bio_link_icon'=>['value'=>'fas fa-pen','library'=>'fa-solid']] ], 'bio_page_bg' => '#eff6ff', 'bio_name_color' => '#1e40af', 'bio_text_color' => '#6b7280', 'bio_link_color' => '#4361ee' ] ) ],
    [ _w( 'elementskey_link_in_bio', [ 'bio_name' => '@dev', 'bio_text' => 'Developer', 'bio_links' => [ ['bio_link_label'=>'GitHub','bio_link_url'=>['url'=>'#'],'bio_link_icon'=>['value'=>'fab fa-github','library'=>'fa-brands']], ['bio_link_label'=>'LinkedIn','bio_link_url'=>['url'=>'#'],'bio_link_icon'=>['value'=>'fab fa-linkedin','library'=>'fa-brands']] ], 'bio_page_bg' => '#ecfdf5', 'bio_name_color' => '#065f46', 'bio_text_color' => '#6b7280', 'bio_link_color' => '#10b981' ] ) ],
    [ _w( 'elementskey_link_in_bio', [ 'bio_name' => '@agency', 'bio_text' => 'Agency', 'bio_links' => [ ['bio_link_label'=>'Portfolio','bio_link_url'=>['url'=>'#'],'bio_link_icon'=>['value'=>'fas fa-briefcase','library'=>'fa-solid']], ['bio_link_label'=>'Contact','bio_link_url'=>['url'=>'#'],'bio_link_icon'=>['value'=>'fas fa-envelope','library'=>'fa-solid']] ], 'bio_page_bg' => '#fff1f2', 'bio_name_color' => '#9f1239', 'bio_text_color' => '#6b7280', 'bio_link_color' => '#f43f5e' ] ) ],
], $b++ ) );

// SHARE IT
array_push( $e, ..._show( 'Share It', [
    [ _w( 'elementskey_share_it', [ 'elementskey_share_it_icon_color' => '#fff', 'elementskey_share_it_background_color' => '#4361ee', 'elementskey_share_it_hover_color' => '#fff', 'elementskey_share_it_hover_background' => '#3451de', 'elementskey_share_it_border_radius' => ['size'=>8,'unit'=>'px'], 'elementskey_share_it_icon_size' => ['size'=>20,'unit'=>'px'], 'elementskey_share_it_padding' => _dim(12) ] ) ],
    [ _w( 'elementskey_share_it', [ 'elementskey_share_it_icon_color' => '#fff', 'elementskey_share_it_background_color' => '#10b981', 'elementskey_share_it_hover_color' => '#fff', 'elementskey_share_it_hover_background' => '#059669', 'elementskey_share_it_border_radius' => ['size'=>50,'unit'=>'%'], 'elementskey_share_it_icon_size' => ['size'=>24,'unit'=>'px'], 'elementskey_share_it_padding' => _dim(14) ] ) ],
    [ _w( 'elementskey_share_it', [ 'elementskey_share_it_icon_color' => '#f43f5e', 'elementskey_share_it_background_color' => '#fff1f2', 'elementskey_share_it_hover_color' => '#fff', 'elementskey_share_it_hover_background' => '#f43f5e', 'elementskey_share_it_border_radius' => ['size'=>12,'unit'=>'px'], 'elementskey_share_it_icon_size' => ['size'=>18,'unit'=>'px'], 'elementskey_share_it_padding' => _dim(10) ] ) ],
], $b++ ) );

// GOOGLE MAPS
array_push( $e, ..._show( 'Google Maps', [
    [ _w( 'elementskey_google_maps', [ 'map_address' => 'New York, USA', 'map_zoom' => 12, 'map_radius' => ['size'=>12,'unit'=>'px'] ] ) ],
    [ _w( 'elementskey_google_maps', [ 'map_address' => 'London, UK', 'map_zoom' => 12, 'map_radius' => ['size'=>0,'unit'=>'px'], 'map_filter' => 'grayscale' ] ) ],
    [ _w( 'elementskey_google_maps', [ 'map_address' => 'Tokyo, Japan', 'map_zoom' => 12, 'map_radius' => ['size'=>24,'unit'=>'px'] ] ) ],
], $b++ ) );

_save( 82, $e, 'Widgets - Content' );

// ═══════════════════════════════════════════════
// PAGE 2: widgets-media (ID 84) — 37 widgets
// ═══════════════════════════════════════════════
$b = 0; $e = [];

$e[] = _cols( [ [ _h( 'Media, Social, Forms & Theme Widgets', 'h1' ), _w( 'text-editor', [ 'editor' => '<p style="color:#6b7280">Media, social, forms, and theme widgets with 3 variations each.</p>', 'align' => 'center' ] ) ] ], [ 'background_background' => 'gradient', 'background_color' => '#0f172a', 'background_color_b' => '#7c3aed', 'padding' => _dim4( 80, 20, 80, 20 ) ] );

// VIDEO
array_push( $e, ..._show( 'Video', [
    [ _w( 'elementskey_video', [ 'video_type' => 'youtube', 'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'video_radius' => _rad(12), 'video_shadow' => _shd('#4361ee20') ] ) ],
    [ _w( 'elementskey_video', [ 'video_type' => 'youtube', 'youtube_url' => 'https://www.youtube.com/watch?v=8aGhZQkoFbQ', 'video_radius' => _rad(0) ] ) ],
    [ _w( 'elementskey_video', [ 'video_type' => 'youtube', 'youtube_url' => 'https://www.youtube.com/watch?v=kXYiU_JCYtU', 'video_radius' => _rad(20), 'video_shadow' => _shd('#f43f5e20',8,30) ] ) ],
], $b++ ) );

// VIDEO PLAYLIST
array_push( $e, ..._show( 'Video Playlist', [
    [ _w( 'elementskey_video_playlist', [ 'playlist' => [ ['vp_title'=>'Intro','vp_source'=>'youtube','vp_url'=>['url'=>'https://www.youtube.com/watch?v=dQw4w9WgXcQ']], ['vp_title'=>'Getting Started','vp_source'=>'youtube','vp_url'=>['url'=>'https://www.youtube.com/watch?v=8aGhZQkoFbQ']], ['vp_title'=>'Advanced','vp_source'=>'youtube','vp_url'=>['url'=>'https://www.youtube.com/watch?v=kXYiU_JCYtU']] ], 'vp_item_color' => '#374151', 'vp_item_active_bg' => '#4361ee', 'vp_item_active_color' => '#fff', 'vp_item_padding' => _dim(12), 'vp_item_border_radius' => _rad(8), 'vp_player_border_radius' => _rad(12) ] ) ],
    [ _w( 'elementskey_video_playlist', [ 'playlist' => [ ['vp_title'=>'Tutorial 1','vp_source'=>'youtube','vp_url'=>['url'=>'https://www.youtube.com/watch?v=dQw4w9WgXcQ']], ['vp_title'=>'Tutorial 2','vp_source'=>'youtube','vp_url'=>['url'=>'https://www.youtube.com/watch?v=8aGhZQkoFbQ']] ], 'vp_item_color' => '#374151', 'vp_item_active_bg' => '#10b981', 'vp_item_active_color' => '#fff', 'vp_item_padding' => _dim(14), 'vp_item_border_radius' => _rad(0), 'vp_player_border_radius' => _rad(0) ] ) ],
    [ _w( 'elementskey_video_playlist', [ 'playlist' => [ ['vp_title'=>'Demo','vp_source'=>'youtube','vp_url'=>['url'=>'https://www.youtube.com/watch?v=dQw4w9WgXcQ']] ], 'vp_item_color' => '#374151', 'vp_item_active_bg' => '#f43f5e', 'vp_item_active_color' => '#fff', 'vp_item_padding' => _dim(16), 'vp_item_border_radius' => _rad(16), 'vp_player_border_radius' => _rad(20) ] ) ],
], $b++ ) );

// LOTTIE (no style controls)
array_push( $e, ..._show( 'Lottie', [
    [ _w( 'elementskey_lottie', [ 'lottie_url' => ['url'=>'https://assets2.lottiefiles.com/packages/lf20_usmfx6bp.json'] ] ) ],
    [ _w( 'elementskey_lottie', [ 'lottie_url' => ['url'=>'https://assets2.lottiefiles.com/packages/lf20_usmfx6bp.json'] ] ) ],
    [ _w( 'elementskey_lottie', [ 'lottie_url' => ['url'=>'https://assets2.lottiefiles.com/packages/lf20_usmfx6bp.json'] ] ) ],
], $b++ ) );

// SOUNDCLOUD
array_push( $e, ..._show( 'SoundCloud', [
    [ _w( 'elementskey_soundcloud', [ 'soundcloud_url' => 'https://soundcloud.com/skrillex/scary-monsters-and-nice-sprites', 'sc_align' => 'center', 'sc_radius' => ['size'=>12,'unit'=>'px'] ] ) ],
    [ _w( 'elementskey_soundcloud', [ 'soundcloud_url' => 'https://soundcloud.com/skrillex/scary-monsters-and-nice-sprites', 'sc_align' => 'left', 'sc_radius' => ['size'=>0,'unit'=>'px'] ] ) ],
    [ _w( 'elementskey_soundcloud', [ 'soundcloud_url' => 'https://soundcloud.com/skrillex/scary-monsters-and-nice-sprites', 'sc_align' => 'center', 'sc_radius' => ['size'=>50,'unit'=>'px'] ] ) ],
], $b++ ) );

// FACEBOOK EMBED
array_push( $e, ..._show( 'Facebook Embed', [
    [ _w( 'elementskey_facebook_embed', [ 'url' => 'https://www.facebook.com/facebook/videos/10153231379906729/', 'wrap_border_radius' => _rad(12) ] ) ],
    [ _w( 'elementskey_facebook_embed', [ 'url' => 'https://www.facebook.com/facebook/videos/10153231379906729/', 'wrap_border_radius' => _rad(0) ] ) ],
    [ _w( 'elementskey_facebook_embed', [ 'url' => 'https://www.facebook.com/facebook/videos/10153231379906729/', 'wrap_border_radius' => _rad(20), 'wrap_border' => _bdr(2,'#1877f2') ] ) ],
], $b++ ) );

// FACEBOOK PAGE
array_push( $e, ..._show( 'Facebook Page', [
    [ _w( 'elementskey_facebook_page', [ 'url' => 'https://www.facebook.com/facebook', 'wrap_border_radius' => _rad(12) ] ) ],
    [ _w( 'elementskey_facebook_page', [ 'url' => 'https://www.facebook.com/facebook', 'wrap_border_radius' => _rad(0) ] ) ],
    [ _w( 'elementskey_facebook_page', [ 'url' => 'https://www.facebook.com/facebook', 'wrap_border_radius' => _rad(16), 'wrap_border' => _bdr(1,'#e5e7eb') ] ) ],
], $b++ ) );

// SOCIAL ICONS
array_push( $e, ..._show( 'Social Icons', [
    [ _w( 'elementskey_social_icons', [ 'social_icons' => [ ['social_type'=>'facebook','social_link'=>['url'=>'#']], ['social_type'=>'twitter','social_link'=>['url'=>'#']], ['social_type'=>'instagram','social_link'=>['url'=>'#']], ['social_type'=>'linkedin','social_link'=>['url'=>'#']], ['social_type'=>'youtube','social_link'=>['url'=>'#']] ], 'icon_size' => ['size'=>20,'unit'=>'px'], 'icon_color' => '#fff', 'icon_bg' => '#4361ee', 'icon_radius' => ['size'=>8,'unit'=>'px'], 'icon_padding' => _dim(12), 'icon_gap' => ['size'=>8,'unit'=>'px'] ] ) ],
    [ _w( 'elementskey_social_icons', [ 'social_icons' => [ ['social_type'=>'facebook','social_link'=>['url'=>'#']], ['social_type'=>'twitter','social_link'=>['url'=>'#']], ['social_type'=>'instagram','social_link'=>['url'=>'#']] ], 'icon_size' => ['size'=>24,'unit'=>'px'], 'icon_color' => '#10b981', 'icon_bg' => '#ecfdf5', 'icon_radius' => ['size'=>50,'unit'=>'%'], 'icon_padding' => _dim(14), 'icon_gap' => ['size'=>12,'unit'=>'px'] ] ) ],
    [ _w( 'elementskey_social_icons', [ 'social_icons' => [ ['social_type'=>'linkedin','social_link'=>['url'=>'#']], ['social_type'=>'youtube','social_link'=>['url'=>'#']], ['social_type'=>'github','social_link'=>['url'=>'#']] ], 'icon_size' => ['size'=>18,'unit'=>'px'], 'icon_color' => '#f43f5e', 'icon_bg' => '#fff1f2', 'icon_radius' => ['size'=>12,'unit'=>'px'], 'icon_padding' => _dim(10), 'icon_gap' => ['size'=>6,'unit'=>'px'] ] ) ],
], $b++ ) );

// FORM
array_push( $e, ..._show( 'Form', [
    [ _w( 'elementskey_form', [ 'form_fields' => [ ['field_type'=>'text','field_label'=>'Name','field_name'=>'name','field_required'=>'yes'], ['field_type'=>'email','field_label'=>'Email','field_name'=>'email','field_required'=>'yes'], ['field_type'=>'textarea','field_label'=>'Message','field_name'=>'message'] ], 'submit_button_text' => 'Send Message', 'label_color' => '#1e40af', 'input_text_color' => '#111827', 'input_bg_color' => '#fff', 'input_border_color' => '#e5e7eb', 'input_border_radius' => _rad(8), 'input_padding' => _dim4(12,16,12,16), 'button_bg_color' => '#4361ee', 'button_color' => '#fff', 'button_radius' => _rad(8), 'button_padding' => _dim4(12,32,12,32) ] ) ],
    [ _w( 'elementskey_form', [ 'form_fields' => [ ['field_type'=>'text','field_label'=>'Full Name','field_name'=>'fullname','field_required'=>'yes'], ['field_type'=>'email','field_label'=>'Email','field_name'=>'email','field_required'=>'yes'], ['field_type'=>'tel','field_label'=>'Phone','field_name'=>'phone'], ['field_type'=>'textarea','field_label'=>'Details','field_name'=>'details'] ], 'submit_button_text' => 'Submit', 'label_color' => '#065f46', 'input_text_color' => '#111827', 'input_bg_color' => '#f0fdf4', 'input_border_color' => '#d1fae5', 'input_border_radius' => _rad(0), 'input_padding' => _dim4(14,18,14,18), 'button_bg_color' => '#10b981', 'button_color' => '#fff', 'button_radius' => _rad(0), 'button_padding' => _dim4(14,36,14,36) ] ) ],
    [ _w( 'elementskey_form', [ 'form_fields' => [ ['field_type'=>'email','field_label'=>'Email','field_name'=>'email','field_required'=>'yes'] ], 'submit_button_text' => 'Subscribe', 'label_color' => '#9f1239', 'input_text_color' => '#111827', 'input_bg_color' => '#fff', 'input_border_color' => '#fecdd3', 'input_border_radius' => _rad(16), 'input_padding' => _dim4(16,20,16,20), 'button_bg_color' => '#f43f5e', 'button_color' => '#fff', 'button_radius' => _rad(16), 'button_padding' => _dim4(16,40,16,40) ] ) ],
], $b++ ) );

// LOGIN
array_push( $e, ..._show( 'Login', [
    [ _w( 'elementskey_login', [ 'login_title' => 'Welcome Back', 'button_label' => 'Log In', 'login_title_color' => '#1e40af', 'input_text_color' => '#111827', 'input_bg_color' => '#fff', 'input_border_color' => '#e5e7eb', 'input_border_radius' => _rad(8), 'input_padding' => _dim4(12,16,12,16), 'btn_text_color' => '#fff', 'btn_bg_color' => '#4361ee', 'btn_border_radius' => _rad(8), 'btn_padding' => _dim4(12,32,12,32), 'link_color' => '#4361ee' ] ) ],
    [ _w( 'elementskey_login', [ 'login_title' => 'Member Login', 'button_label' => 'Sign In', 'login_title_color' => '#065f46', 'input_text_color' => '#111827', 'input_bg_color' => '#f0fdf4', 'input_border_color' => '#d1fae5', 'input_border_radius' => _rad(0), 'input_padding' => _dim4(14,18,14,18), 'btn_text_color' => '#fff', 'btn_bg_color' => '#10b981', 'btn_border_radius' => _rad(0), 'btn_padding' => _dim4(14,36,14,36), 'link_color' => '#10b981' ] ) ],
    [ _w( 'elementskey_login', [ 'login_title' => 'Access Account', 'button_label' => 'Login', 'login_title_color' => '#9f1239', 'input_text_color' => '#111827', 'input_bg_color' => '#fff', 'input_border_color' => '#fecdd3', 'input_border_radius' => _rad(12), 'input_padding' => _dim4(16,20,16,20), 'btn_text_color' => '#fff', 'btn_bg_color' => '#f43f5e', 'btn_border_radius' => _rad(12), 'btn_padding' => _dim4(16,40,16,40), 'link_color' => '#f43f5e' ] ) ],
], $b++ ) );

// SHORTCODE (no style controls)
array_push( $e, ..._show( 'Shortcode', [
    [ _w( 'elementskey_shortcode', [ 'shortcode_content' => '[gallery ids="1,2,3"]' ] ) ],
    [ _w( 'elementskey_shortcode', [ 'shortcode_content' => '[gallery ids="1,2,3"]' ] ) ],
    [ _w( 'elementskey_shortcode', [ 'shortcode_content' => '[gallery ids="1,2,3"]' ] ) ],
], $b++ ) );

// FACEBOOK BUTTON
array_push( $e, ..._show( 'Facebook Button', [
    [ _w( 'elementskey_facebook_button', [ 'fb_button_layout' => 'standard', 'fb_button_action' => 'like', 'fb_button_padding' => _dim4(8,16,8,16), 'fb_button_border_radius' => _rad(6) ] ) ],
    [ _w( 'elementskey_facebook_button', [ 'fb_button_layout' => 'standard', 'fb_button_action' => 'recommend', 'fb_button_padding' => _dim4(10,20,10,20), 'fb_button_border_radius' => _rad(0) ] ) ],
    [ _w( 'elementskey_facebook_button', [ 'fb_button_layout' => 'button_count', 'fb_button_action' => 'like', 'fb_button_padding' => _dim4(6,12,6,12), 'fb_button_border_radius' => _rad(12) ] ) ],
], $b++ ) );

// FACEBOOK COMMENTS
array_push( $e, ..._show( 'Facebook Comments', [
    [ _w( 'elementskey_facebook_comments', [ 'url' => 'https://www.facebook.com/facebook', 'wrap_border_radius' => _rad(12), 'wrap_padding' => _dim(8) ] ) ],
    [ _w( 'elementskey_facebook_comments', [ 'url' => 'https://www.facebook.com/facebook', 'wrap_border_radius' => _rad(0) ] ) ],
    [ _w( 'elementskey_facebook_comments', [ 'url' => 'https://www.facebook.com/facebook', 'wrap_border_radius' => _rad(16), 'wrap_border' => _bdr(1,'#e5e7eb') ] ) ],
], $b++ ) );

// SEARCH
array_push( $e, ..._show( 'Search', [
    [ _w( 'elementskey_search', [ 'input_text_color' => '#111827', 'input_bg_color' => '#fff', 'input_border_color' => '#e5e7eb', 'input_border_radius' => _rad(8), 'input_padding' => _dim4(12,16,12,16), 'search_btn_bg' => '#4361ee', 'search_btn_color' => '#fff', 'btn_border_radius' => _rad(8), 'btn_padding' => _dim4(12,24,12,24) ] ) ],
    [ _w( 'elementskey_search', [ 'input_text_color' => '#111827', 'input_bg_color' => '#f0fdf4', 'input_border_color' => '#d1fae5', 'input_border_radius' => _rad(0), 'input_padding' => _dim4(14,18,14,18), 'search_btn_bg' => '#10b981', 'search_btn_color' => '#fff', 'btn_border_radius' => _rad(0), 'btn_padding' => _dim4(14,28,14,28) ] ) ],
    [ _w( 'elementskey_search', [ 'input_text_color' => '#111827', 'input_bg_color' => '#fff', 'input_border_color' => '#fecdd3', 'input_border_radius' => _rad(20), 'input_padding' => _dim4(16,24,16,24), 'search_btn_bg' => '#f43f5e', 'search_btn_color' => '#fff', 'btn_border_radius' => _rad(20), 'btn_padding' => _dim4(16,32,16,32) ] ) ],
], $b++ ) );

// TAXONOMY FILTER
array_push( $e, ..._show( 'Taxonomy Filter', [
    [ _w( 'elementskey_taxonomy_filter', [ 'filter_taxonomy' => 'category', 'filter_all_label' => 'All', 'filter_color' => '#374151', 'filter_active_bg' => '#4361ee', 'filter_active_color' => '#fff', 'filter_hover_bg' => '#eff6ff', 'filter_hover_color' => '#4361ee', 'filter_padding' => _dim4(8,16,8,16), 'filter_border_radius' => _rad(8) ] ) ],
    [ _w( 'elementskey_taxonomy_filter', [ 'filter_taxonomy' => 'category', 'filter_all_label' => 'All Posts', 'filter_color' => '#374151', 'filter_active_bg' => '#10b981', 'filter_active_color' => '#fff', 'filter_hover_bg' => '#ecfdf5', 'filter_hover_color' => '#10b981', 'filter_padding' => _dim4(10,20,10,20), 'filter_border_radius' => _rad(0) ] ) ],
    [ _w( 'elementskey_taxonomy_filter', [ 'filter_taxonomy' => 'post_tag', 'filter_all_label' => 'All Tags', 'filter_color' => '#374151', 'filter_active_bg' => '#f43f5e', 'filter_active_color' => '#fff', 'filter_hover_bg' => '#fff1f2', 'filter_hover_color' => '#f43f5e', 'filter_padding' => _dim4(6,12,6,12), 'filter_border_radius' => _rad(16) ] ) ],
], $b++ ) );

// TEMPLATE (no style controls)
array_push( $e, ..._show( 'Template', [
    [ _w( 'elementskey_template', [] ) ],
    [ _w( 'elementskey_template', [] ) ],
    [ _w( 'elementskey_template', [] ) ],
], $b++ ) );

// MENU
array_push( $e, ..._show( 'Menu', [
    [ _w( 'elementskey_menu', [ 'menu_link_color' => '#1e40af', 'menu_hover_color' => '#4361ee', 'menu_typography' => _typo(16,'500') ] ) ],
    [ _w( 'elementskey_menu', [ 'menu_link_color' => '#065f46', 'menu_hover_color' => '#10b981', 'menu_typography' => _typo(15,'600') ] ) ],
    [ _w( 'elementskey_menu', [ 'menu_link_color' => '#9f1239', 'menu_hover_color' => '#f43f5e', 'menu_typography' => _typo(17,'700') ] ) ],
], $b++ ) );

// WP MENU
array_push( $e, ..._show( 'WordPress Menu', [
    [ _w( 'elementskey_wp_menu', [ 'wp_menu_color' => '#1e40af', 'wp_menu_hover_color' => '#4361ee', 'wp_menu_typography' => _typo(16,'500') ] ) ],
    [ _w( 'elementskey_wp_menu', [ 'wp_menu_color' => '#065f46', 'wp_menu_hover_color' => '#10b981', 'wp_menu_typography' => _typo(15,'600') ] ) ],
    [ _w( 'elementskey_wp_menu', [ 'wp_menu_color' => '#9f1239', 'wp_menu_hover_color' => '#f43f5e', 'wp_menu_typography' => _typo(17,'700') ] ) ],
], $b++ ) );

// SITEMAP
array_push( $e, ..._show( 'Sitemap', [
    [ _w( 'elementskey_sitemap', [ 'sitemap_link_color' => '#4361ee', 'sitemap_title_color' => '#1e40af', 'count_color' => '#6b7280', 'item_border_color' => '#e5e7eb' ] ) ],
    [ _w( 'elementskey_sitemap', [ 'sitemap_link_color' => '#10b981', 'sitemap_title_color' => '#065f46', 'count_color' => '#6b7280', 'item_border_color' => '#d1fae5' ] ) ],
    [ _w( 'elementskey_sitemap', [ 'sitemap_link_color' => '#f43f5e', 'sitemap_title_color' => '#9f1239', 'count_color' => '#6b7280', 'item_border_color' => '#fecdd3' ] ) ],
], $b++ ) );

// SITE LOGO
array_push( $e, ..._show( 'Site Logo', [
    [ _w( 'elementskey_site_logo', [ 'logo_border_radius' => _rad(8), 'logo_padding' => _dim(8) ] ) ],
    [ _w( 'elementskey_site_logo', [ 'logo_border_radius' => _rad(50), 'logo_padding' => _dim(12) ] ) ],
    [ _w( 'elementskey_site_logo', [ 'logo_border_radius' => _rad(0), 'logo_padding' => _dim(4) ] ) ],
], $b++ ) );

// SITE TITLE
array_push( $e, ..._show( 'Site Title', [
    [ _w( 'elementskey_site_title', [ 'site_title_color' => '#1e40af', 'site_title_align' => 'left', 'site_title_typography' => _typo(28,'700') ] ) ],
    [ _w( 'elementskey_site_title', [ 'site_title_color' => '#065f46', 'site_title_align' => 'center', 'site_title_typography' => _typo(32,'800') ] ) ],
    [ _w( 'elementskey_site_title', [ 'site_title_color' => '#9f1239', 'site_title_align' => 'right', 'site_title_typography' => _typo(24,'600') ] ) ],
], $b++ ) );

// PAGE TITLE
array_push( $e, ..._show( 'Page Title', [
    [ _w( 'elementskey_page_title', [ 'page_title_color' => '#1e40af', 'page_title_align' => 'left', 'page_title_typography' => _typo(36,'700') ] ) ],
    [ _w( 'elementskey_page_title', [ 'page_title_color' => '#065f46', 'page_title_align' => 'center', 'page_title_typography' => _typo(40,'800') ] ) ],
    [ _w( 'elementskey_page_title', [ 'page_title_color' => '#9f1239', 'page_title_align' => 'right', 'page_title_typography' => _typo(30,'600') ] ) ],
], $b++ ) );

// POST TITLE
array_push( $e, ..._show( 'Post Title', [
    [ _w( 'elementskey_post_title', [ 'title_color' => '#1e40af', 'title_hover_color' => '#4361ee', 'title_align' => 'left', 'title_typography' => _typo(28,'700') ] ) ],
    [ _w( 'elementskey_post_title', [ 'title_color' => '#065f46', 'title_hover_color' => '#10b981', 'title_align' => 'center', 'title_typography' => _typo(32,'800') ] ) ],
    [ _w( 'elementskey_post_title', [ 'title_color' => '#9f1239', 'title_hover_color' => '#f43f5e', 'title_align' => 'right', 'title_typography' => _typo(24,'600') ] ) ],
], $b++ ) );

// POST EXCERPT
array_push( $e, ..._show( 'Post Excerpt', [
    [ _w( 'elementskey_post_excerpt', [ 'excerpt_length' => 20, 'excerpt_color' => '#374151', 'excerpt_align' => 'left', 'readmore_color' => '#4361ee', 'readmore_hover_color' => '#2563eb', 'excerpt_typography' => _typo(16,'400') ] ) ],
    [ _w( 'elementskey_post_excerpt', [ 'excerpt_length' => 30, 'excerpt_color' => '#374151', 'excerpt_align' => 'center', 'readmore_color' => '#10b981', 'readmore_hover_color' => '#059669', 'excerpt_typography' => _typo(15,'400') ] ) ],
    [ _w( 'elementskey_post_excerpt', [ 'excerpt_length' => 10, 'excerpt_color' => '#374151', 'excerpt_align' => 'right', 'readmore_color' => '#f43f5e', 'readmore_hover_color' => '#e11d48', 'excerpt_typography' => _typo(17,'500') ] ) ],
], $b++ ) );

// FEATURED IMAGE
array_push( $e, ..._show( 'Featured Image', [
    [ _w( 'elementskey_featured_image', [ 'image_radius' => _rad(12), 'image_shadow' => _shd('#4361ee20') ] ) ],
    [ _w( 'elementskey_featured_image', [ 'image_radius' => _rad(50) ] ) ],
    [ _w( 'elementskey_featured_image', [ 'image_radius' => _rad(0), 'image_border' => _bdr(3,'#f43f5e'), 'image_shadow' => _shd('#f43f5e20',8,25) ] ) ],
], $b++ ) );

// POST CONTENT
array_push( $e, ..._show( 'Post Content', [
    [ _w( 'elementskey_post_content', [ 'content_color' => '#374151', 'content_align' => 'left', 'link_color' => '#4361ee', 'link_hover_color' => '#2563eb' ] ) ],
    [ _w( 'elementskey_post_content', [ 'content_color' => '#374151', 'content_align' => 'center', 'link_color' => '#10b981', 'link_hover_color' => '#059669' ] ) ],
    [ _w( 'elementskey_post_content', [ 'content_color' => '#374151', 'content_align' => 'right', 'link_color' => '#f43f5e', 'link_hover_color' => '#e11d48' ] ) ],
], $b++ ) );

// AUTHOR BOX
array_push( $e, ..._show( 'Author Box', [
    [ _w( 'elementskey_author_box', [ 'box_bg' => '#eff6ff', 'box_radius' => _rad(12), 'box_padding' => _dim(24), 'name_color' => '#1e40af', 'description_color' => '#374151', 'website_link_color' => '#4361ee' ] ) ],
    [ _w( 'elementskey_author_box', [ 'box_bg' => '#ecfdf5', 'box_radius' => _rad(0), 'box_padding' => _dim(20), 'name_color' => '#065f46', 'description_color' => '#374151', 'website_link_color' => '#10b981' ] ) ],
    [ _w( 'elementskey_author_box', [ 'box_bg' => '#fff1f2', 'box_radius' => _rad(20), 'box_padding' => _dim(28), 'name_color' => '#9f1239', 'description_color' => '#374151', 'website_link_color' => '#f43f5e' ] ) ],
], $b++ ) );

// POST COMMENTS
array_push( $e, ..._show( 'Post Comments', [
    [ _w( 'elementskey_post_comments', [ 'title_color' => '#1e40af', 'comment_text_color' => '#374151', 'comment_bg' => '#f8fafc', 'comment_border_color' => '#e5e7eb', 'form_input_bg' => '#fff', 'form_btn_bg' => '#4361ee', 'form_btn_color' => '#fff' ] ) ],
    [ _w( 'elementskey_post_comments', [ 'title_color' => '#065f46', 'comment_text_color' => '#374151', 'comment_bg' => '#ecfdf5', 'comment_border_color' => '#d1fae5', 'form_input_bg' => '#f0fdf4', 'form_btn_bg' => '#10b981', 'form_btn_color' => '#fff' ] ) ],
    [ _w( 'elementskey_post_comments', [ 'title_color' => '#9f1239', 'comment_text_color' => '#374151', 'comment_bg' => '#fff1f2', 'comment_border_color' => '#fecdd3', 'form_input_bg' => '#fff', 'form_btn_bg' => '#f43f5e', 'form_btn_color' => '#fff' ] ) ],
], $b++ ) );

// POST NAVIGATION
array_push( $e, ..._show( 'Post Navigation', [
    [ _w( 'elementskey_post_navigation', [ 'nav_color' => '#4361ee', 'nav_hover_color' => '#2563eb', 'label_color' => '#6b7280', 'arrow_color' => '#4361ee', 'nav_align' => 'left', 'box_padding' => _dim(16), 'box_border' => _bdr(1,'#e5e7eb') ] ) ],
    [ _w( 'elementskey_post_navigation', [ 'nav_color' => '#10b981', 'nav_hover_color' => '#059669', 'label_color' => '#6b7280', 'arrow_color' => '#10b981', 'nav_align' => 'center', 'box_padding' => _dim(20) ] ) ],
    [ _w( 'elementskey_post_navigation', [ 'nav_color' => '#f43f5e', 'nav_hover_color' => '#e11d48', 'label_color' => '#6b7280', 'arrow_color' => '#f43f5e', 'nav_align' => 'right', 'box_padding' => _dim(12), 'box_border' => _bdr(2,'#fecdd3') ] ) ],
], $b++ ) );

// POST INFO
array_push( $e, ..._show( 'Post Info', [
    [ _w( 'elementskey_post_info', [ 'post_info_items' => [ ['selected_icon'=>['value'=>'fas fa-calendar','library'=>'fa-solid'],'type'=>'post_date'], ['selected_icon'=>['value'=>'fas fa-user','library'=>'fa-solid'],'type'=>'author'] ], 'info_color' => '#374151', 'info_link_color' => '#4361ee', 'sep_color' => '#9ca3af' ] ) ],
    [ _w( 'elementskey_post_info', [ 'post_info_items' => [ ['selected_icon'=>['value'=>'fas fa-folder','library'=>'fa-solid'],'type'=>'post_terms'], ['selected_icon'=>['value'=>'fas fa-comments','library'=>'fa-solid'],'type'=>'comments'] ], 'info_color' => '#374151', 'info_link_color' => '#10b981', 'sep_color' => '#9ca3af' ] ) ],
    [ _w( 'elementskey_post_info', [ 'post_info_items' => [ ['selected_icon'=>['value'=>'fas fa-clock','library'=>'fa-solid'],'type'=>'post_date'] ], 'info_color' => '#374151', 'info_link_color' => '#f43f5e', 'sep_color' => '#9ca3af' ] ) ],
], $b++ ) );

// BREADCRUMBS
array_push( $e, ..._show( 'Breadcrumbs', [
    [ _w( 'elementskey_breadcrumbs', [ 'breadcrumb_color' => '#4361ee', 'breadcrumb_hover_color' => '#2563eb', 'breadcrumb_current_color' => '#1e40af', 'breadcrumb_sep_color' => '#9ca3af' ] ) ],
    [ _w( 'elementskey_breadcrumbs', [ 'breadcrumb_color' => '#10b981', 'breadcrumb_hover_color' => '#059669', 'breadcrumb_current_color' => '#065f46', 'breadcrumb_sep_color' => '#9ca3af' ] ) ],
    [ _w( 'elementskey_breadcrumbs', [ 'breadcrumb_color' => '#f43f5e', 'breadcrumb_hover_color' => '#e11d48', 'breadcrumb_current_color' => '#9f1239', 'breadcrumb_sep_color' => '#9ca3af' ] ) ],
], $b++ ) );

// ARCHIVE TITLE
array_push( $e, ..._show( 'Archive Title', [
    [ _w( 'elementskey_archive_title', [ 'archive_title_color' => '#1e40af', 'archive_title_align' => 'left', 'archive_title_typography' => _typo(32,'700') ] ) ],
    [ _w( 'elementskey_archive_title', [ 'archive_title_color' => '#065f46', 'archive_title_align' => 'center', 'archive_title_typography' => _typo(36,'800') ] ) ],
    [ _w( 'elementskey_archive_title', [ 'archive_title_color' => '#9f1239', 'archive_title_align' => 'right', 'archive_title_typography' => _typo(28,'600') ] ) ],
], $b++ ) );

// ARCHIVE POSTS
array_push( $e, ..._show( 'Archive Posts', [
    [ _w( 'elementskey_archive_posts', [ 'posts_per_page' => 3, 'columns' => 3, 'card_bg' => '#fff', 'card_radius' => ['size'=>12,'unit'=>'px'], 'title_color' => '#1e40af', 'excerpt_color' => '#374151', 'pagination_bg' => '#eff6ff', 'pagination_color' => '#4361ee', 'pagination_active_bg' => '#4361ee', 'pagination_active_color' => '#fff' ] ) ],
    [ _w( 'elementskey_archive_posts', [ 'posts_per_page' => 2, 'columns' => 2, 'card_bg' => '#ecfdf5', 'card_radius' => ['size'=>0,'unit'=>'px'], 'title_color' => '#065f46', 'excerpt_color' => '#374151', 'pagination_bg' => '#d1fae5', 'pagination_color' => '#059669', 'pagination_active_bg' => '#10b981', 'pagination_active_color' => '#fff' ] ) ],
    [ _w( 'elementskey_archive_posts', [ 'posts_per_page' => 4, 'columns' => 4, 'card_bg' => '#fff1f2', 'card_radius' => ['size'=>16,'unit'=>'px'], 'title_color' => '#9f1239', 'excerpt_color' => '#374151', 'pagination_bg' => '#ffe4e6', 'pagination_color' => '#e11d48', 'pagination_active_bg' => '#f43f5e', 'pagination_active_color' => '#fff' ] ) ],
], $b++ ) );

// POSTS
array_push( $e, ..._show( 'Posts', [
    [ _w( 'elementskey_posts', [ 'posts_per_page' => 3, 'columns' => 3, 'card_bg' => '#fff', 'card_radius' => ['size'=>12,'unit'=>'px'], 'card_padding' => _dim(16), 'title_color' => '#1e40af', 'title_hover_color' => '#4361ee', 'excerpt_color' => '#374151', 'meta_color' => '#6b7280', 'readmore_color' => '#4361ee', 'card_shadow' => _shd('#00000010',2,10) ] ) ],
    [ _w( 'elementskey_posts', [ 'posts_per_page' => 2, 'columns' => 2, 'card_bg' => '#ecfdf5', 'card_radius' => ['size'=>0,'unit'=>'px'], 'card_padding' => _dim(16), 'title_color' => '#065f46', 'title_hover_color' => '#10b981', 'excerpt_color' => '#374151', 'meta_color' => '#6b7280', 'readmore_color' => '#10b981' ] ) ],
    [ _w( 'elementskey_posts', [ 'posts_per_page' => 4, 'columns' => 4, 'card_bg' => '#fff1f2', 'card_radius' => ['size'=>16,'unit'=>'px'], 'card_padding' => _dim(20), 'title_color' => '#9f1239', 'title_hover_color' => '#f43f5e', 'excerpt_color' => '#374151', 'meta_color' => '#6b7280', 'readmore_color' => '#f43f5e', 'card_border' => _bdr(1,'#fecdd3') ] ) ],
], $b++ ) );

// PORTFOLIO
array_push( $e, ..._show( 'Portfolio', [
    [ _w( 'elementskey_portfolio', [ 'filter_btn_color' => '#374151', 'filter_btn_bg' => '#eff6ff', 'filter_btn_active_color' => '#fff', 'filter_btn_active_bg' => '#4361ee', 'filter_btn_radius' => ['size'=>8,'unit'=>'px'], 'card_bg' => '#fff', 'card_radius' => ['size'=>12,'unit'=>'px'], 'title_color' => '#1e40af', 'excerpt_color' => '#374151' ] ) ],
    [ _w( 'elementskey_portfolio', [ 'filter_btn_color' => '#374151', 'filter_btn_bg' => '#ecfdf5', 'filter_btn_active_color' => '#fff', 'filter_btn_active_bg' => '#10b981', 'filter_btn_radius' => ['size'=>0,'unit'=>'px'], 'card_bg' => '#fff', 'card_radius' => ['size'=>0,'unit'=>'px'], 'title_color' => '#065f46', 'excerpt_color' => '#374151' ] ) ],
    [ _w( 'elementskey_portfolio', [ 'filter_btn_color' => '#374151', 'filter_btn_bg' => '#fff1f2', 'filter_btn_active_color' => '#fff', 'filter_btn_active_bg' => '#f43f5e', 'filter_btn_radius' => ['size'=>20,'unit'=>'px'], 'card_bg' => '#fff', 'card_radius' => ['size'=>16,'unit'=>'px'], 'title_color' => '#9f1239', 'excerpt_color' => '#374151', 'card_shadow' => _shd('#f43f5e15',4,15) ] ) ],
], $b++ ) );

// LOOP GRID
array_push( $e, ..._show( 'Loop Grid', [
    [ _w( 'elementskey_loop_grid', [ 'card_bg' => '#fff', 'card_radius' => ['size'=>12,'unit'=>'px'], 'card_padding' => _dim(16), 'title_color' => '#1e40af', 'excerpt_color' => '#374151', 'meta_color' => '#6b7280', 'button_color' => '#fff', 'button_bg' => '#4361ee', 'pagination_color' => '#4361ee', 'pagination_active_bg' => '#4361ee', 'pagination_active_color' => '#fff' ] ) ],
    [ _w( 'elementskey_loop_grid', [ 'card_bg' => '#ecfdf5', 'card_radius' => ['size'=>0,'unit'=>'px'], 'card_padding' => _dim(16), 'title_color' => '#065f46', 'excerpt_color' => '#374151', 'meta_color' => '#6b7280', 'button_color' => '#fff', 'button_bg' => '#10b981', 'pagination_color' => '#059669', 'pagination_active_bg' => '#10b981', 'pagination_active_color' => '#fff' ] ) ],
    [ _w( 'elementskey_loop_grid', [ 'card_bg' => '#fff1f2', 'card_radius' => ['size'=>16,'unit'=>'px'], 'card_padding' => _dim(20), 'title_color' => '#9f1239', 'excerpt_color' => '#374151', 'meta_color' => '#6b7280', 'button_color' => '#fff', 'button_bg' => '#f43f5e', 'pagination_color' => '#e11d48', 'pagination_active_bg' => '#f43f5e', 'pagination_active_color' => '#fff' ] ) ],
], $b++ ) );

// LOOP CAROUSEL
array_push( $e, ..._show( 'Loop Carousel', [
    [ _w( 'elementskey_loop_carousel', [ 'card_bg' => '#fff', 'card_radius' => ['size'=>12,'unit'=>'px'], 'card_padding' => _dim(16), 'title_color' => '#1e40af', 'excerpt_color' => '#374151', 'meta_color' => '#6b7280', 'arrow_color' => '#4361ee', 'arrow_bg' => '#eff6ff', 'dots_color' => '#cbd5e1', 'dots_active_color' => '#4361ee' ] ) ],
    [ _w( 'elementskey_loop_carousel', [ 'card_bg' => '#ecfdf5', 'card_radius' => ['size'=>0,'unit'=>'px'], 'card_padding' => _dim(16), 'title_color' => '#065f46', 'excerpt_color' => '#374151', 'meta_color' => '#6b7280', 'arrow_color' => '#10b981', 'arrow_bg' => '#d1fae5', 'dots_color' => '#d1fae5', 'dots_active_color' => '#10b981' ] ) ],
    [ _w( 'elementskey_loop_carousel', [ 'card_bg' => '#fff1f2', 'card_radius' => ['size'=>16,'unit'=>'px'], 'card_padding' => _dim(20), 'title_color' => '#9f1239', 'excerpt_color' => '#374151', 'meta_color' => '#6b7280', 'arrow_color' => '#f43f5e', 'arrow_bg' => '#ffe4e6', 'dots_color' => '#fecdd3', 'dots_active_color' => '#f43f5e' ] ) ],
], $b++ ) );

_save( 84, $e, 'Widgets - Media' );

echo "\nDone! Both pages updated with styled designs.\n";
