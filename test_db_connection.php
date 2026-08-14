<?php
/**
 * test_db_connection.php
 * Teste la connexion à la base de données MySQL
 */

// Charger la config et la connexion
require_once __DIR__ . '/backend/includes/config.php';
require_once __DIR__ . '/backend/includes/db.php';

echo "=== Test de connexion MySQL ===\n\n";

echo "Configuration:\n";
echo "  DB_HOST: " . DB_HOST . "\n";
echo "  DB_NAME: " . DB_NAME . "\n";
echo "  DB_USER: " . DB_USER . "\n";
echo "  DB_PASS: " . (DB_PASS ? '***' : '(vide)') . "\n\n";

try {
    $db = get_db();
    echo "✅ Connexion réussie!\n\n";

    // Vérifier les tables
    echo "Vérification des tables:\n";
    $stmt = $db->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($tables)) {
        echo "  ❌ Aucune table trouvée. Le schéma n'a pas été importé.\n";
    } else {
        echo "  Tables trouvées:\n";
        foreach ($tables as $table) {
            echo "    - $table\n";
        }
    }

    // Vérifier les formations
    echo "\nVérification des formations:\n";
    $stmt = $db->query("SELECT COUNT(*) as total FROM formations");
    $row = $stmt->fetch();
    echo "  Formations: " . $row['total'] . "\n";

    if ($row['total'] > 0) {
        $stmt = $db->query("SELECT id, slug, title FROM formations LIMIT 3");
        $formations = $stmt->fetchAll();
        foreach ($formations as $f) {
            echo "    - [ID " . $f['id'] . "] " . $f['title'] . " (" . $f['slug'] . ")\n";
        }
    }

    // Vérifier les modules
    echo "\nVérification des modules de formation:\n";
    $stmt = $db->query("SELECT COUNT(*) as total FROM formation_modules");
    $row = $stmt->fetch();
    echo "  Modules: " . $row['total'] . "\n";

} catch (PDOException $e) {
    echo "❌ Erreur de connexion:\n";
    echo "  " . $e->getMessage() . "\n\n";
    echo "Assurez-vous que:\n";
    echo "  1. MySQL/WAMP est en cours d'exécution\n";
    echo "  2. La base de données '" . DB_NAME . "' existe\n";
    echo "  3. Les identifiants sont corrects\n";
}
?>
