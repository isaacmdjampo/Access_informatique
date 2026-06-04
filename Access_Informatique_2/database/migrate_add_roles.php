<?php
/**
 * Migration : Ajouter les colonnes de rôles et d'audit aux admins
 *
 * Utilisation : php database/migrate_add_roles.php
 *
 * Ajoute :
 *  - role (superadmin, admin, editor)
 *  - created_by (ID de l'admin qui a créé ce compte)
 *  - updated_by (ID du dernier admin à modifier ce compte)
 *  - action_log (JSON des dernières actions)
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

    echo "[OK] Connexion établie\n";

    // ---- Vérifier si la colonne 'role' existe déjà ----
    $stmt = $pdo->query("SHOW COLUMNS FROM admins LIKE 'role'");
    if ($stmt->rowCount() === 0) {
        echo "[*] Ajout colonne 'role'...\n";
        $pdo->exec("
            ALTER TABLE admins
            ADD COLUMN role VARCHAR(20) NOT NULL DEFAULT 'admin'
            COMMENT 'superadmin, admin, ou editor'
            AFTER password_hash
        ");
        echo "[OK] Colonne 'role' ajoutée\n";
    } else {
        echo "[SKIP] Colonne 'role' existe déjà\n";
    }

    // ---- Vérifier si la colonne 'created_by' existe ----
    $stmt = $pdo->query("SHOW COLUMNS FROM admins LIKE 'created_by'");
    if ($stmt->rowCount() === 0) {
        echo "[*] Ajout colonne 'created_by'...\n";
        $pdo->exec("
            ALTER TABLE admins
            ADD COLUMN created_by INT UNSIGNED DEFAULT NULL
            COMMENT 'ID du créateur'
            AFTER role
        ");
        echo "[OK] Colonne 'created_by' ajoutée\n";
    } else {
        echo "[SKIP] Colonne 'created_by' existe déjà\n";
    }

    // ---- Vérifier si la colonne 'updated_by' existe ----
    $stmt = $pdo->query("SHOW COLUMNS FROM admins LIKE 'updated_by'");
    if ($stmt->rowCount() === 0) {
        echo "[*] Ajout colonne 'updated_by'...\n";
        $pdo->exec("
            ALTER TABLE admins
            ADD COLUMN updated_by INT UNSIGNED DEFAULT NULL
            COMMENT 'ID du dernier modificateur'
            AFTER created_by
        ");
        echo "[OK] Colonne 'updated_by' ajoutée\n";
    } else {
        echo "[SKIP] Colonne 'updated_by' existe déjà\n";
    }

    // ---- Créer table d'audit ----
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS admin_audit_log (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            admin_id INT UNSIGNED NOT NULL COMMENT 'Admin qui a fait l''action',
            action VARCHAR(50) NOT NULL COMMENT 'create, update, delete, etc.',
            target_type VARCHAR(50) NOT NULL COMMENT 'admin, solution, formation, etc.',
            target_id INT UNSIGNED COMMENT 'ID de ce qui a été modifié',
            changes JSON COMMENT 'Avant/après (old, new)',
            ip_address VARCHAR(45) DEFAULT NULL,
            user_agent VARCHAR(500) DEFAULT NULL,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY idx_admin_id (admin_id),
            KEY idx_created_at (created_at),
            KEY idx_action (action),
            CONSTRAINT fk_audit_admin FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "[OK] Table 'admin_audit_log' vérifiée\n";

    // ---- Mettre à jour le premier admin au rôle 'superadmin' ----
    echo "[*] Mise à jour du premier admin au rôle 'superadmin'...\n";
    $pdo->exec("UPDATE admins SET role = 'superadmin' ORDER BY id ASC LIMIT 1");
    echo "[OK] Migration complète !\n";

} catch (PDOException $e) {
    die('[ERREUR] ' . $e->getMessage() . PHP_EOL);
}
