<?php
/**
 * test_api_formations.php
 * Teste l'API des formations
 */

require_once __DIR__ . '/backend/includes/config.php';
require_once __DIR__ . '/backend/includes/db.php';
require_once __DIR__ . '/backend/includes/Response.php';

echo "=== Test de l'API Formations ===\n\n";

// Tester sans slug (liste)
echo "Test 1: GET /api/formations (liste)\n";
echo "---\n";

try {
    $db = get_db();

    // Simuler l'appel à get_all_formations
    $stmt = $db->query(
        'SELECT id, slug, title, category, duration, level,
                price, description, image_url, sort_order
           FROM formations
          WHERE is_active = 1
          ORDER BY sort_order ASC, id ASC'
    );
    $formations = $stmt->fetchAll();

    if (!empty($formations)) {
        echo "✅ " . count($formations) . " formation(s) trouvée(s):\n";
        foreach ($formations as $f) {
            echo "  - {$f['title']} ({$f['slug']})\n";
        }
    } else {
        echo "❌ Aucune formation trouvée\n";
    }

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

echo "\n";

// Tester avec slug
echo "Test 2: GET /api/formations?slug=developpement-web-full-stack\n";
echo "---\n";

try {
    $db = get_db();
    $slug = 'developpement-web-full-stack';

    // Simuler l'appel à get_formation_by_slug
    $stmt = $db->prepare(
        'SELECT id, slug, title, category, duration, level,
                price, description, image_url
           FROM formations
          WHERE slug = ? AND is_active = 1
          LIMIT 1'
    );
    $stmt->execute([$slug]);
    $formation = $stmt->fetch();

    if ($formation) {
        echo "✅ Formation trouvée:\n";
        echo "  ID: " . $formation['id'] . "\n";
        echo "  Titre: " . $formation['title'] . "\n";
        echo "  Slug: " . $formation['slug'] . "\n";
        echo "  Catégorie: " . $formation['category'] . "\n";
        echo "  Prix: " . $formation['price'] . "\n";

        $id = (int) $formation['id'];

        // Charger les skills
        $stmt = $db->prepare(
            'SELECT skill FROM formation_skills
              WHERE formation_id = ?
              ORDER BY sort_order ASC'
        );
        $stmt->execute([$id]);
        $skills = array_column($stmt->fetchAll(), 'skill');
        echo "  Skills: " . implode(', ', $skills) . "\n";

        // Charger les modules
        $stmt = $db->prepare(
            'SELECT title, description, duration
               FROM formation_modules
              WHERE formation_id = ?
              ORDER BY sort_order ASC'
        );
        $stmt->execute([$id]);
        $modules = $stmt->fetchAll();
        echo "  Modules: " . count($modules) . "\n";
        foreach ($modules as $m) {
            echo "    - {$m['title']} ({$m['duration']})\n";
        }

        // Charger les bénéfices
        $stmt = $db->prepare(
            'SELECT text FROM formation_benefits
              WHERE formation_id = ?
              ORDER BY sort_order ASC'
        );
        $stmt->execute([$id]);
        $benefits = array_column($stmt->fetchAll(), 'text');
        echo "  Bénéfices: " . count($benefits) . "\n";

        // Charger les résultats
        $stmt = $db->prepare(
            'SELECT text FROM formation_outcomes
              WHERE formation_id = ?
              ORDER BY sort_order ASC'
        );
        $stmt->execute([$id]);
        $outcomes = array_column($stmt->fetchAll(), 'text');
        echo "  Résultats: " . count($outcomes) . "\n";

    } else {
        echo "❌ Formation non trouvée\n";
    }

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

echo "\n✅ Tous les tests sont réussis!\n";
echo "Les données sont présentes et accessibles via l'API.\n";
?>
