<?php
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
    
    // Vérifier la structure de la table
    echo "=== Table Structure ===\n";
    $stmt = $pdo->query("DESCRIBE admins");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "{$row['Field']}: {$row['Type']} {$row['Null']} {$row['Default']}\n";
    }
    
    echo "\n=== Admin Data ===\n";
    $stmt = $pdo->query("SELECT id, name, email, role FROM admins");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "[$row[id]] $row[name] ($row[email]) — Role: '$row[role]'\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
