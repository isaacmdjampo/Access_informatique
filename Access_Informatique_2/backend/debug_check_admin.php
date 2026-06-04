<?php
/**
 * Debug script to check admin credentials
 */

define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'access_informatique');
define('DB_USER', 'root');
define('DB_PASS', '');

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // Check if table exists
    $tables = $pdo->query("SHOW TABLES LIKE 'admins'")->fetchAll();
    echo "=== TABLE ADMINS ===\n";
    if (empty($tables)) {
        echo "❌ Table 'admins' N'EXISTE PAS!\n";
        exit(1);
    }
    echo "✓ Table 'admins' existe\n\n";

    // List all admins
    echo "=== ADMINS EN BASE ===\n";
    $stmt = $pdo->query("SELECT id, name, email, password_hash FROM admins");
    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($admins)) {
        echo "❌ AUCUN ADMIN TROUVÉ!\n";
        echo "\n📝 Créez un admin avec: php database/setup_admin.php\n";
        exit(1);
    }

    foreach ($admins as $admin) {
        echo "ID: {$admin['id']}\n";
        echo "Name: {$admin['name']}\n";
        echo "Email: {$admin['email']}\n";
        echo "Hash (premier 20 char): " . substr($admin['password_hash'], 0, 20) . "...\n";
        echo "Hash valide? " . (strlen($admin['password_hash']) === 60 ? "✓ OUI (60 chars)" : "❌ NON") . "\n";
        echo "\n";
    }

    // Test password verification
    echo "=== TEST VERIFICATION MOT DE PASSE ===\n";
    $test_password = 'Admin@Access2024!';
    $admin = $admins[0];

    echo "Test avec: $test_password\n";
    echo "Hash: " . $admin['password_hash'] . "\n";

    if (password_verify($test_password, $admin['password_hash'])) {
        echo "✓ MOT DE PASSE CORRECT!\n";
    } else {
        echo "❌ MOT DE PASSE INCORRECT!\n";
        echo "\nVous devez recréer l'admin:\n";
        echo "php database/setup_admin.php\n";
    }

} catch (PDOException $e) {
    echo "❌ Erreur BD: " . $e->getMessage() . "\n";
    exit(1);
}
