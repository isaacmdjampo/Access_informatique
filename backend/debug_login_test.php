<?php
/**
 * Simule exactement ce que fait le endpoint /api/admin/login
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

    // Tester les deux noms de table possibles
    echo "=== TEST 1: FROM admins ===\n";
    $stmt = $pdo->prepare(
        'SELECT id, name, email, password_hash
           FROM admins
          WHERE email = ?
          LIMIT 1'
    );
    $stmt->execute(['admin@accessinformatique.com']);
    $admin = $stmt->fetch();

    if ($admin) {
        echo "✓ Trouvé dans 'admins'\n";
        echo "Email: " . $admin['email'] . "\n";
        if (password_verify('Admin@Access2024!', $admin['password_hash'])) {
            echo "✓ Password OK\n";
        } else {
            echo "❌ Password FAIL\n";
        }
    } else {
        echo "❌ Pas trouvé dans 'admins'\n";
    }

    echo "\n=== TEST 2: FROM users_admins ===\n";
    try {
        $stmt = $pdo->prepare(
            'SELECT id, email, password FROM users_admins
               WHERE email = ?
              LIMIT 1'
        );
        $stmt->execute(['admin@accessinformatique.com']);
        $admin2 = $stmt->fetch();

        if ($admin2) {
            echo "✓ Trouvé dans 'users_admins'\n";
            print_r($admin2);
        } else {
            echo "❌ Pas trouvé dans 'users_admins'\n";
        }
    } catch (Exception $e) {
        echo "❌ Erreur: " . $e->getMessage() . "\n";
    }

    echo "\n=== TOUTES LES TABLES ===\n";
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    foreach ($tables as $table) {
        echo "- $table\n";
    }

} catch (PDOException $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
