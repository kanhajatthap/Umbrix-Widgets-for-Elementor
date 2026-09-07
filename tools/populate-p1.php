<?php
if ( ! defined( 'ABSPATH' ) ) { require_once dirname( __DIR__, 4 ) . '/wp-load.php'; }
if ( ! did_action( 'elementor/loaded' ) ) { die( 'Elementor not loaded.' ); }

function _rid() { return substr( md5( uniqid( mt_rand(), true ) ), 0, 7 ); }
function _w( $type, $s = [] ) {
    return [ 'id' => _rid(), 'elType' => 'widget', 'widgetType' => $type, 'settings' => $s, 'elements' => [], 'isInner' => false ];
}
function _h( $text, $tag = 'h2' ) {
    return _w( 'heading', [ 'title' => $text, 'header_size' => $tag, 'align' => 'left', 'title_color' => '#111827' ] );
}
function _col( $widgets, $size = 100 ) {
    $els = is_array( $widgets[0] ?? null ) ? $widgets : [ $widgets ];
    return [ 'id' => _rid(), 'elType' => 'column', 'settings' => [ '_column_size' => $size ], 'elements' => $els, 'isInner' => false ];
}
function _sec( $cols, $s = [] ) {
    return [ 'id' => _rid(), 'elType' => 'section', 'settings' => array_merge( [ 'layout' => 'boxed', 'content_width' => [ 'unit' => 'px', 'size' => 1140 ], 'gap' => 'extended' ], $s ), 'elements' => $cols, 'isInner' => false ];
}
function _cols( $cols_of_w, $s = [], $sizes = [] ) {
    $cs = [];
    foreach ( $cols_of_w as $i => $cw ) {
        $cs[] = _col( $cw, $sizes[ $i ] ?? (int) ( 100 / count( $cols_of_w ) ) );
    }
    return _sec( $cs, $s );
}
function _show( $name, $cols, $band = 0 ) {
    $bg = ( $band % 2 ) ? '#f8fafc' : '#ffffff';
    $hdr = _cols( [ [ _h( $name ) ] ], [
        'background_background' => 'classic', 'background_color' => $bg,
        'padding' => [ 'unit' => 'px', 'top' => 50, 'right' => 20, 'bottom' => 10, 'left' => 20, 'isLinked' => false ],
    ] );
    $body = _cols( $cols, [
        'background_background' => 'classic', 'background_color' => $bg,
        'padding' => [ 'unit' => 'px', 'top' => 20, 'right' => 20, 'bottom' => 50, 'left' => 20, 'isLinked' => false ],
    ] );
    return [ $hdr, $body ];
}
function _typo( $size, $weight = '400', $family = '' ) {
    $t = [ 'unit' => 'px', 'size' => $size, 'size_mobile' => $size, 'weight' => $weight ];
    if ( $family ) $t['font_family'] = $family;
    return $t;
}
function _dim( $v ) { return [ 'unit' => 'px', 'top' => $v, 'right' => $v, 'bottom' => $v, 'left' => $v, 'isLinked' => true ]; }
function _dim4( $t, $r, $b, $l ) { return [ 'unit' => 'px', 'top' => $t, 'right' => $r, 'bottom' => $b, 'left' => $l, 'isLinked' => false ]; }
