<?php
/**
 * Syntax lint all PHP files in the plugin (except tools/).
 * Usage: tools\php.cmd tools\lint.php
 */
$root   = realpath( __DIR__ . '/..' );
$skip   = [ realpath( __DIR__ ) . DIRECTORY_SEPARATOR ];
$it     = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator( $root, FilesystemIterator::SKIP_DOTS )
);
$fail   = 0;
$count  = 0;
$errors = [];

foreach ( $it as $file ) {
    if ( $file->getExtension() !== 'php' ) {
        continue;
    }
    $path = $file->getPathname();
    foreach ( $skip as $dir ) {
        if ( strpos( $path, $dir ) === 0 ) {
            continue 2;
        }
    }
    $count++;
    $cmd  = escapeshellarg( PHP_BINARY ) . ' -n -l ' . escapeshellarg( $path ) . ' 2>&1';
    $out  = [];
    $code = 0;
    exec( $cmd, $out, $code );
    if ( $code !== 0 ) {
        $fail++;
        $errors[] = implode( PHP_EOL, $out );
    }
}

echo "Checked {$count} PHP files." . PHP_EOL;
if ( $fail ) {
    echo PHP_EOL . implode( PHP_EOL . PHP_EOL, $errors ) . PHP_EOL;
    echo "FAILED: {$fail} file(s) with syntax errors." . PHP_EOL;
    exit( 1 );
}
echo "All files pass syntax check." . PHP_EOL;
exit( 0 );
