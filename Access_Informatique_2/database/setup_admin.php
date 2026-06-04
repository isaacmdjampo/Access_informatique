<?php
/**
 * setup_admin.php
 * ---------------------------------------------------------------
 * Script à exécuter UNE SEULE FOIS en ligne de commande pour
 * créer le compte administrateur initial avec un hash bcrypt réel.
 *
 * UTILISATION :
 *   php database/setup_admin.php
 *
 * SUPPRIMER CE FICHIER après utilisation (contient les identifiants).
 * ---------------------------------------------------------------
 */

// ---- Configuration de la connexion ----
// Ajustez ces valeurs selon votre environnement local.
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'access_informatique');
define('DB_USER', 'root');
define('DB_PASS', '');

// ---- Identifiants du premier admin ----
$admin_name  = 'Administrateur';
$admin_email = 'admin@accessinformatique.com';
$admin_pass  = 'AccessInform@tique2026'; // Changez ce mot de passe en production

// ---------------------------------------------------------------
// Connexion PDO
// ---------------------------------------------------------------
try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die('[ERREUR] Connexion impossible : ' . $e->getMessage() . PHP_EOL);
}

// ---------------------------------------------------------------
// Génération du hash bcrypt
// ---------------------------------------------------------------
$hash = password_hash($admin_pass, PASSWORD_BCRYPT, ['cost' => 12]);

// ---------------------------------------------------------------
// Insertion (ou mise à jour si l'email existe déjà)
// ---------------------------------------------------------------
$stmt = $pdo->prepare(
    'INSERT INTO admins (name, email, password_hash)
     VALUES (:name, :email, :hash)
     ON DUPLICATE KEY UPDATE
       name          = VALUES(name),
       password_hash = VALUES(password_hash),
       updated_at    = CURRENT_TIMESTAMP'
);

$stmt->execute([
    ':name'  => $admin_name,
    ':email' => $admin_email,
    ':hash'  => $hash,
]);

echo '[OK] Compte admin créé / mis à jour.' . PHP_EOL;
echo '     Email    : ' . $admin_email . PHP_EOL;
echo '     Password : ' . $admin_pass  . PHP_EOL;
echo PHP_EOL;
echo '[ATTENTION] Supprimez ce fichier immédiatement après exécution !' . PHP_EOL;
 