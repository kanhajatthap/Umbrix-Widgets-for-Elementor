<?php
// Test that the Builder admin submenu is registered and the old slug is removed.
$found_builder = false;
$found_hf = false;
if ( isset( $GLOBALS['submenu']['elementskey-settings'] ) ) {
    foreach ( $GLOBALS['submenu']['elementskey-settings'] as $item ) {
        if ( isset( $item[2] ) ) {
            if ( 'admin.php?page=elementskey-builder' === $item[2] ) {
                $found_builder = true;
            }
            if ( 'admin.php?page=elementskey-hf-builder' === $item[2] ) {
                $found_hf = true;
            }
        }
    }
}

echo 'builder_slug=' . ( $found_builder ? 'yes' : 'no' ) . PHP_EOL;
echo 'hf_slug=' . ( $found_hf ? 'yes' : 'no' ) . PHP_EOL;
