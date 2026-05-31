<?php
/**
 * debug_login.php — SCRIPT DE DIAGNOSTIC TEMPORAIRE
 * Ouvrir dans le navigateur : http://localhost/Access_Informatique/database/debug_login.php
 * SUPPRIMER CE FICHIER après utilisation.
 */

$DB_HOST = '127.0.0.1';
$DB_NAME = 'access_informatique';
$DB_USER = 'root';
$DB_PASS = '';

$test_password = 'Admin@Access2024!';

echo '<pre>';

// 1. Connexion base de données
try {
    $pdo = new PDO(
        "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4",
        $DB_USER, $DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    echo "✅ Connexion MySQL OK\n\n";
} catch (PDOException $e) {
    die("❌ Connexion impossible : " . $e->getMessage());
}

// 2. Lire l'admin en base
$stmt = $pdo->query("SELECT id, name, email, password_hash FROM admins LIMIT 5");
$admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($admins)) {
    die("❌ Table 'admins' vide — setup_admin.php n'a pas fonctionné.");
}

echo "✅ " . count($admins) . " admin(s) trouvé(s) en base :\n\n";

foreach ($admins as $admin) {
    echo "  ID    : " . $admin['id'] . "\n";
    echo "  Nom   : " . $admin['name'] . "\n";
    echo "  Email : " . $admin['email'] . "\n";
    echo "  Hash  : " . substr($admin['password_hash'], 0, 7) . "... (" . strlen($admin['password_hash']) . " caractères)\n";

    // 3. Vérification du mot de passe
    $ok = password_verify($test_password, $admin['password_hash']);
    echo "  Test mot de passe '" . $test_password . "' : " . ($ok ? "✅ CORRECT" : "❌ INCORRECT") . "\n\n";
}

// 4. Vérifier la version PHP
echo "PHP : " . PHP_VERSION . "\n";
echo "password_hash algo : " . PASSWORD_BCRYPT . " (BCRYPT)\n";

echo '</pre>';
