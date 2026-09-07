<?php
/**
 * Create a test user in WordPress for testing ElementsKey widgets in Elementor.
 * Usage: tools\php.cmd tools\create-test-user.php
 */
$wp_root = 'C:\Users\india\Local Sites\plugin-test\app\public';
require_once $wp_root . '/wp-load.php';

$username = 'qa_tester';
$password = 'TestPassword123!';
$email    = 'qa_tester@plugin-test.local';
$role     = 'administrator';

$user = get_user_by( 'login', $username );

if ( ! $user ) {
    $user_id = wp_create_user( $username, $password, $email );
    if ( is_wp_error( $user_id ) ) {
        echo "[FAIL] Could not create user: " . $user_id->get_error_message() . PHP_EOL;
        exit( 1 );
    }
    $user = get_user_by( 'id', $user_id );
    $user->set_role( $role );
    echo "[CREATED] User created successfully!" . PHP_EOL;
} else {
    wp_set_password( $password, $user->ID );
    $user->set_role( $role );
    echo "[EXISTS] User password and role updated!" . PHP_EOL;
}

echo "Username: " . $username . PHP_EOL;
echo "Password: " . $password . PHP_EOL;
echo "Role: "     . $role . PHP_EOL;
echo "Email: "    . $email . PHP_EOL;
echo "Login URL: " . wp_login_url() . PHP_EOL;
