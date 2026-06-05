<?php
/**
 * Test script to verify admin password hash
 */

// Connexion locale pour le développement
define('DB_HOST', 'localhost');
define('DB_NAME', 'access_informatique');
define('DB_USER', 'root');
define('DB_PASS', '');

$password_to_test = 'AccessInform@tique2026';

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // Get all admins
    $stmt = $pdo->query("SELECT id, name, email, password_hash FROM admins");
    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "=== ADMINS EN BASE ===\n";
    if (empty($admins)) {
        echo "❌ AUCUN ADMIN!\n";
        exit(1);
    }

    foreach ($admins as $admin) {
        echo "\n--- Admin: {$admin['email']} ---\n";
        echo "ID: {$admin['id']}\n";
        echo "Name: {$admin['name']}\n";
        echo "Hash: {$admin['password_hash']}\n";
        echo "Hash length: " . strlen($admin['password_hash']) . " chars\n";

        // Test avec le mot de passe
        if (password_verify($password_to_test, $admin['password_hash'])) {
            echo "✓ Password '{$password_to_test}' matches!\n";
        } else {
            echo "❌ Password '{$password_to_test}' does NOT match!\n";

            // Generate a new hash for testing
            echo "\n--- GENERATING NEW HASH ---\n";
            $new_hash = password_hash($password_to_test, PASSWORD_BCRYPT, ['cost' => 10]);
            echo "New hash: $new_hash\n";
            echo "New hash length: " . strlen($new_hash) . " chars\n";

            // Test if the new hash would work
            if (password_verify($password_to_test, $new_hash)) {
                echo "✓ New hash works with the password!\n";
                echo "\nRun this SQL to update:\n";
                echo "UPDATE admins SET password_hash = '$new_hash' WHERE id = {$admin['id']};\n";
            }
        }
    }

} catch (PDOException $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n";
    exit(1);
}
