<?php
/**
 * test_admin_login_api.php
 * Teste la connexion admin via l'API
 */

require_once __DIR__ . '/backend/includes/config.php';
require_once __DIR__ . '/backend/includes/db.php';
require_once __DIR__ . '/backend/includes/Auth.php';

echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║    Test de connexion Admin via l'API                      ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

// Identifiants de test
$test_email = 'admin@accessinformatique.com';
$test_password = 'Admin@Access2024!';

echo "Test de connexion:\n";
echo "  Email: $test_email\n";
echo "  Mot de passe: " . str_repeat("*", strlen($test_password)) . "\n\n";

// Simuler une requête de login
$db = get_db();

// 1. Chercher l'admin
echo "1️⃣  Recherche de l'admin en base de données...\n";
$stmt = $db->prepare(
    'SELECT id, name, email, role, password_hash
       FROM admins
      WHERE email = ?
      LIMIT 1'
);
$stmt->execute([$test_email]);
$admin = $stmt->fetch();

if (!$admin) {
    echo "❌ Admin non trouvé\n";
    exit(1);
} else {
    echo "✅ Admin trouvé:\n";
    echo "   ID: {$admin['id']}\n";
    echo "   Nom: {$admin['name']}\n";
    echo "   Email: {$admin['email']}\n";
    echo "   Rôle: {$admin['role']}\n\n";
}

// 2. Vérifier le mot de passe
echo "2️⃣  Vérification du mot de passe...\n";
if (!password_verify($test_password, $admin['password_hash'])) {
    echo "❌ Mot de passe incorrect\n";
    exit(1);
} else {
    echo "✅ Mot de passe correct\n\n";
}

// 3. Générer le token JWT
echo "3️⃣  Génération du token JWT...\n";
try {
    $token = generate_token((int) $admin['id'], $admin['email']);
    echo "✅ Token généré:\n";
    echo "   " . substr($token, 0, 50) . "...\n\n";
} catch (Exception $e) {
    echo "❌ Erreur lors de la génération du token: " . $e->getMessage() . "\n";
    exit(1);
}

// 4. Afficher le token généré
echo "4️⃣  Token JWT généré avec succès\n";
echo "   Le token sera stocké côté client et utilisé pour les requêtes authentifiées\n\n";

echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║ ✅ CONNEXION ADMIN FONCTIONNELLE                           ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

echo "Vous pouvez maintenant vous connecter au dashboard admin:\n";
echo "  URL: http://localhost:5173/admin/login\n";
echo "  Email: $test_email\n";
echo "  Mot de passe: " . str_repeat("*", strlen($test_password)) . "\n";
?>
