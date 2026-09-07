<?php
/**
 * Static audit: i18n, PHP version compat, escaping, license headers, name consistency.
 * Usage: tools\php.cmd tools\qa-audit.php
 */

$root  = realpath( __DIR__ . '/..' );
$td    = 'elementskey';
$skip  = [ realpath( __DIR__ ) . DIRECTORY_SEPARATOR, realpath( __DIR__ . '/../vendor' ) . DIRECTORY_SEPARATOR ];
$it    = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $root, FilesystemIterator::SKIP_DOTS ) );
$files = [];
foreach ( $it as $file ) {
    if ( $file->getExtension() !== 'php' ) {
        continue;
    }
    $p = $file->getPathname();
    foreach ( $skip as $s ) {
        if ( strpos( $p, $s ) === 0 ) {
            continue 2;
        }
    }
    $files[] = $p;
}
sort( $files );

$issues = [];

// ---- Translation function name => regex capturing the domain string ----
$translators = [
    '__', '_e', 'esc_html__', 'esc_html_e', 'esc_attr__', 'esc_attr_e',
    '_x', '_ex', 'esc_html_x', 'esc_attr_x', '_n', '_nx', 'esc_attr_n', 'esc_html_n',
];
$tx_re = implode( '|', array_map( 'preg_quote', $translators ) );

foreach ( $files as $f ) {
    $rel  = str_replace( $root . DIRECTORY_SEPARATOR, '', $f );
    $code = file_get_contents( $f );

    // 1. Translation calls missing text domain (first arg literal, only 1-2 args)
    if ( preg_match_all( '/(?<!\w)(?:' . $tx_re . ')\(\s*[\'"][^\'"]+[\'"](?:\s*,\s*[\'"][^\'"]+[\'"])?\s*\)/', $code, $m ) ) {
        foreach ( $m[0] as $call ) {
            $domains = [];
            preg_match_all( '/,\s*[\'"]([^\'"]+)[\'"]\s*\)/', $call, $dm );
            $domains = $dm[1] ?: [];
            $is_singular_plural = (bool) preg_match( '/(?:_n|_nx)\(\s*[\'"][^\'"]+[\'"],\s*[\'"][^\'"]+[\'"],\s*[\'"][^\'"]+[\'"]/', $call );
            if ( $is_singular_plural ) {
                continue;
            }
            if ( ! $domains ) {
                $line = 1 + substr_count( substr( $code, 0, strpos( $code, $call ) ), "\n" );
                $issues[] = sprintf( '%s:%d MISSING text domain: %s', $rel, $line, substr( $call, 0, 90 ) );
            } else {
                foreach ( $domains as $d ) {
                    if ( $d !== $td ) {
                        $line = 1 + substr_count( substr( $code, 0, strpos( $code, $call ) ), "\n" );
                        $issues[] = sprintf( '%s:%d WRONG text domain "%s": %s', $rel, $line, $d, substr( $call, 0, 90 ) );
                    }
                }
            }
        }
    }

    // 2. PHP 8-only syntax (plugin Requires PHP 7.4)
    $p8 = [];
    if ( preg_match_all( '/\bstr_contains\s*\(/', $code, $m ) ) { $p8[] = 'str_contains (PHP 8)'; }
    if ( preg_match_all( '/\bstr_starts_with\s*\(/', $code, $m ) ) { $p8[] = 'str_starts_with (PHP 8)'; }
    if ( preg_match_all( '/\bstr_ends_with\s*\(/', $code, $m ) ) { $p8[] = 'str_ends_with (PHP 8)'; }
    if ( preg_match_all( '/\bmatch\s*\(/', $code, $m ) ) { $p8[] = 'match expression (PHP 8)'; }
    if ( preg_match_all( '/\?->/', $code, $m ) ) { $p8[] = 'nullsafe ?-> (PHP 8)'; }
    if ( preg_match_all( '/\breadonly\s/', $code, $m ) ) { $p8[] = 'readonly (PHP 8.1)'; }
    if ( preg_match_all( '/\benum\s+\w+/', $code, $m ) ) { $p8[] = 'enum (PHP 8.1)'; }
    if ( preg_match_all( '/\)\s*:\s*never\b/', $code, $m ) ) { $p8[] = 'never return type (PHP 8.1)'; }
    if ( preg_match_all( '/#\[\w+\(/', $code, $m ) ) { $p8[] = 'attributes (PHP 8)'; }
    if ( preg_match_all( '/\bfn\s*\(/', $code, $m ) ) { $p8[] = 'arrow fn (PHP 7.4 OK, review)'; }
    if ( $p8 ) {
        $issues[] = sprintf( '%s PHP-8 syntax: %s', $rel, implode( ', ', array_unique( $p8 ) ) );
    }

    // 3. Escaping: direct echo of variables (common XSS/escaping miss)
    if ( preg_match_all( "/echo\s+\$[\w\[\]\"'<>-]+/", $code, $m ) ) {
        foreach ( $m[0] as $hit ) {
            if ( ! preg_match( '/(?:esc_html|esc_attr|esc_url|wp_kses|esc_textarea|json_encode)/', $hit ) ) {
                $line = 1 + substr_count( substr( $code, 0, strpos( $code, $hit ) ), "\n" );
                $issues[] = sprintf( '%s:%d unescaped echo: %s', $rel, $line, substr( $hit, 0, 70 ) );
            }
        }
    }

    // 4. License header present?
    if ( stripos( $code, 'GPL' ) === false ) {
        $issues[] = sprintf( '%s no GPL/license header', $rel );
    }

    // 5. String literal echo: only flag text-like strings (skip HTML/CSS/JS markup)
    if ( preg_match_all( '/echo\s+[\'"]([^\'"\n]{4,80})[\'"](?!\s*(?:===|!==|==|!=|\|\||&&|\)))/', $code, $m ) ) {
        foreach ( $m[1] as $txt ) {
            if ( preg_match( '/[<>{}:\/.]/', $txt ) ) {
                continue;
            }
            $pos  = strpos( $code, 'echo ' . var_export( $txt, true ) );
            if ( $pos === false ) {
                $pos = strpos( $code, $txt );
            }
            $line = 1 + substr_count( substr( $code, 0, $pos ), "\n" );
            $issues[] = sprintf( '%s:%d untranslated literal echo: %s', $rel, $line, $txt );
        }
    }

    // 6. Mojibake / encoding corruption
    if ( preg_match_all( '/[\xC3][\x80-\xBF]|[\xE2][\x80-\xBF]|[\xC2][\x80-\xBF]/', $code, $m ) && ! preg_match( '/[\x{0080}-\x{FFFF}]/u', $code ) ) {
        $issues[] = sprintf( '%s possible mojibake (broken UTF-8): %s', $rel, $m[0][0] );
    }
}

echo 'Scanned ' . count( $files ) . ' PHP files.' . PHP_EOL;
if ( ! $issues ) {
    echo 'AUDIT CLEAN.' . PHP_EOL;
    exit( 0 );
}
echo PHP_EOL . implode( PHP_EOL, $issues ) . PHP_EOL;
echo PHP_EOL . 'Total: ' . count( $issues ) . ' findings.' . PHP_EOL;
