<?php
/**
 * Test de connexion à la base PlanetHoster
 */

// Essayer différents hosts possibles
$hosts_to_test = [
    'localhost',
    '127.0.0.1',
    'fgpmrfqp.mysql.db', // Hôte MySQL typique PlanetHoster
    'sql.hostinger.com',
    'sql.classicplanethosting.com',
];

$db_name = 'fgpmrfqp_kira';
$db_user = 'fgpmrfqp_kira';
$db_pass = 'Accessinform@tique2026';

echo "=== TEST DE CONNEXION À DIFFÉRENTS HOSTS ===\n\n";

foreach ($hosts_to_test as $host) {
    echo "Testing host: $host ... ";
    try {
        $pdo = new PDO(
            "mysql:host=$host;dbname=$db_name;charset=utf8mb4",
            $db_user,
            $db_pass,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );

        echo "✓ SUCCESS!\n";

        // Test si la table admins existe
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM admins");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "  → Admins in DB: {$result['count']}\n";

        // List admins
        $stmt = $pdo->query("SELECT id, email, password_hash FROM admins");
        $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($admins as $admin) {
            $hash_preview = substr($admin['password_hash'], 0, 20) . "...";
            echo "     - {$admin['email']}: $hash_preview\n";
        }

        echo "\n✓ This is the correct host!\n";
        break;

    } catch (PDOException $e) {
        $error = $e->getMessage();
        if (strpos($error, 'Unknown host') !== false || strpos($error, 'host unreachable') !== false) {
            echo "✗ Host unreachable\n";
        } else if (strpos($error, 'Access denied') !== false) {
            echo "✗ Access denied (wrong credentials?)\n";
        } else {
            echo "✗ Error: " . substr($error, 0, 50) . "...\n";
        }
    }
}
