<?php
/**
 * Script pour créer un admin
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';

$email = 'admin@accessinformatique.com';
$name = 'Administrateur';
$password = 'AccessInform@tique2026';

// Générer le hash bcrypt
$password_hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);

echo "=== CRÉATION D'UN ADMIN ===\n\n";
echo "Email: $email\n";
echo "Nom: $name\n";
echo "Mot de passe: $password\n";
echo "Hash: $password_hash\n";
echo "Hash length: " . strlen($password_hash) . " chars\n\n";

try {
    $db = get_db();

    // Supprimer les admins existants
    echo "Suppression des admins existants...\n";
    $db->exec("DELETE FROM admins");

    // Insérer le nouvel admin
    echo "Insertion du nouvel admin...\n";
    $stmt = $db->prepare(
        'INSERT INTO admins (name, email, password_hash, created_at)
         VALUES (?, ?, ?, NOW())'
    );
    $stmt->execute([$name, $email, $password_hash]);

    echo "✓ Admin créé avec succès!\n\n";

    // Vérifier qu'il a été créé
    $stmt = $db->prepare('SELECT id, name, email FROM admins WHERE email = ?');
    $stmt->execute([$email]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin) {
        echo "Admin créé:\n";
        echo "  ID: {$admin['id']}\n";
        echo "  Name: {$admin['name']}\n";
        echo "  Email: {$admin['email']}\n";
        echo "\n✓ Prêt à se connecter!\n";
    }

} catch (PDOException $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    exit(1);
}
