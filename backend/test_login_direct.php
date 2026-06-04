<?php
/**
 * Test direct du login — appel simple et enregistrement
 */

define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'access_informatique');
define('DB_USER', 'root');
define('DB_PASS', '');

// LOG FILE
$log_file = __DIR__ . '/login_test.log';

function log_msg($msg) {
    global $log_file;
    file_put_contents($log_file, date('Y-m-d H:i:s') . ' - ' . $msg . "\n", FILE_APPEND);
}

log_msg('=== START LOGIN TEST ===');

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    log_msg('✓ DB Connected');

    // Test 1: Check admin exists
    $stmt = $pdo->prepare('SELECT id, name, email, password_hash FROM admins WHERE email = ?');
    $stmt->execute(['admin@accessinformatique.com']);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$admin) {
        log_msg('❌ Admin NOT FOUND in DB!');
        die('Admin not found');
    }

    log_msg('✓ Admin found: ' . $admin['email']);
    log_msg('  Hash length: ' . strlen($admin['password_hash']));

    // Test 2: Verify password
    $test_pass = 'Admin@Access2024!';
    if (password_verify($test_pass, $admin['password_hash'])) {
        log_msg('✓ Password verification SUCCESS');
        log_msg('Admin ID: ' . $admin['id']);
        log_msg('Admin Name: ' . $admin['name']);
    } else {
        log_msg('❌ Password verification FAILED!');
        log_msg('Hash: ' . $admin['password_hash']);
        die('Password incorrect');
    }

    log_msg('=== ALL TESTS PASSED ===');
    echo "OK - Check $log_file for details\n";

} catch (Exception $e) {
    log_msg('❌ Error: ' . $e->getMessage());
    die($e->getMessage());
}
