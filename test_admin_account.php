<?php
/**
 * test_admin_account.php
 * Teste l'existence du compte admin et crée un si nécessaire
 */

require_once __DIR__ . '/backend/includes/config.php';
require_once __DIR__ . '/backend/includes/db.php';

echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║    Diagnostic du compte administrateur                    ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

$db = get_db();

// Vérifier les comptes admin existants
echo "1️⃣  COMPTES ADMINISTRATEUR EN BASE DE DONNÉES\n";
echo "───────────────────────────────────────\n";

try {
    $stmt = $db->query("SELECT id, name, email, role, created_at FROM admins");
    $admins = $stmt->fetchAll();
    
    if (empty($admins)) {
        echo "❌ Aucun compte admin trouvé en base de données\n";
    } else {
        echo "✅ Compte(s) admin trouvé(s):\n";
        foreach ($admins as $admin) {
            echo "  - ID: {$admin['id']}\n";
            echo "    Nom: {$admin['name']}\n";
            echo "    Email: {$admin['email']}\n";
            echo "    Rôle: {$admin['role']}\n";
            echo "    Créé le: {$admin['created_at']}\n\n";
        }
    }
} catch (Exception $e) {
    echo "❌ Erreur lors de la lecture des admins: " . $e->getMessage() . "\n";
}

echo "\n";

// Tester le login avec les identifiants par défaut
echo "2️⃣  TEST DE LOGIN\n";
echo "───────────────────────────────────────\n";

$test_email = 'admin@accessinformatique.com';
$test_password = 'Admin@Access2024!';

echo "Testant avec:\n";
echo "  Email: $test_email\n";
echo "  Mot de passe: *" . str_repeat("*", strlen($test_password) - 2) . "*\n\n";

$stmt = $db->prepare("SELECT id, email, password_hash FROM admins WHERE email = ? LIMIT 1");
$stmt->execute([$test_email]);
$admin_record = $stmt->fetch();

if (!$admin_record) {
    echo "❌ Aucun admin avec cet email\n\n";
    echo "💡 SOLUTION: Créer un compte admin\n";
    echo "───────────────────────────────────────\n";
    echo "Voulez-vous créer le compte admin par défaut? (y/n): ";
    $input = trim(fgets(STDIN));
    
    if (strtolower($input) === 'y') {
        $name = 'Admin';
        $email = 'admin@accessinformatique.com';
        $password = 'Admin@Access2024!';
        $password_hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        
        try {
            $stmt = $db->prepare(
                "INSERT INTO admins (name, email, password_hash, role, created_at, updated_at)
                 VALUES (?, ?, ?, 'admin', NOW(), NOW())"
            );
            $stmt->execute([$name, $email, $password_hash]);
            $id = $db->lastInsertId();
            
            echo "\n✅ Compte admin créé avec succès!\n";
            echo "  ID: $id\n";
            echo "  Email: $email\n";
            echo "  Mot de passe: *" . str_repeat("*", strlen($password) - 2) . "*\n";
            echo "\nVous pouvez maintenant vous connecter avec ces identifiants.\n";
        } catch (Exception $e) {
            echo "❌ Erreur lors de la création: " . $e->getMessage() . "\n";
        }
    } else {
        echo "Création annulée.\n";
    }
} else {
    // Vérifier le mot de passe
    if (password_verify($test_password, $admin_record['password_hash'])) {
        echo "✅ Le mot de passe est correct!\n";
        echo "  Hash en base: " . substr($admin_record['password_hash'], 0, 20) . "...\n";
    } else {
        echo "❌ Le mot de passe est INCORRECT\n";
        echo "  Hash en base: " . substr($admin_record['password_hash'], 0, 20) . "...\n";
        echo "\n💡 SOLUTION: Réinitialiser le mot de passe\n";
        echo "───────────────────────────────────────\n";
        
        $new_password = 'Admin@Access2024!';
        $new_hash = password_hash($new_password, PASSWORD_BCRYPT, ['cost' => 12]);
        
        echo "Voulez-vous réinitialiser le mot de passe? (y/n): ";
        $input = trim(fgets(STDIN));
        
        if (strtolower($input) === 'y') {
            try {
                $stmt = $db->prepare("UPDATE admins SET password_hash = ? WHERE id = ?");
                $stmt->execute([$new_hash, $admin_record['id']]);
                
                echo "\n✅ Mot de passe réinitialisé!\n";
                echo "  Nouvel email: admin@accessinformatique.com\n";
                echo "  Nouveau mot de passe: " . $new_password . "\n";
            } catch (Exception $e) {
                echo "❌ Erreur lors de la mise à jour: " . $e->getMessage() . "\n";
            }
        }
    }
}

echo "\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║ Test terminé                                               ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n";
?>
