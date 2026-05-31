<?php
/**
 * GET /api/solutions
 * GET /api/solutions?slug=solumed
 * ---------------------------------------------------------------
 * Retourne la liste des solutions actives ou le détail d'une solution.
 *
 * GET /api/solutions
 *   → tableau de solutions avec tags (pour la liste / filtres)
 *
 * GET /api/solutions?slug=solumed
 *   → objet complet avec modules, advantages, stats, tags (page détail)
 * ---------------------------------------------------------------
 */

declare(strict_types=1);

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/Response.php';

cors_headers();

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    error_response('Méthode non autorisée.', 405);
}

$slug = isset($_GET['slug']) ? trim($_GET['slug']) : null;

try {
    if ($slug !== null && $slug !== '') {
        get_solution_by_slug($slug);
    } else {
        get_all_solutions();
    }
} catch (PDOException $e) {
    error_log('[API/solutions] PDO : ' . $e->getMessage());
    error_response('Erreur interne du serveur.', 500);
}

// ============================================================

/**
 * Retourne la liste de toutes les solutions actives.
 * Inclut les tags pour l'affichage des badges sur les cartes.
 */
function get_all_solutions(): never
{
    $db = get_db();

    // Récupérer toutes les solutions actives
    $stmt = $db->query(
        'SELECT id, slug, name, tagline, category,
                accent_color, accent_color_light,
                hero_image, price, short_description,
                sort_order
           FROM solutions
          WHERE is_active = 1
          ORDER BY sort_order ASC, id ASC'
    );
    $solutions = $stmt->fetchAll();

    if (empty($solutions)) {
        json_response([]);
    }

    // Récupérer les IDs pour charger les tags en une seule requête
    $ids          = array_column($solutions, 'id');
    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    $stmt = $db->prepare(
        "SELECT solution_id, tag
           FROM solution_tags
          WHERE solution_id IN ($placeholders)
          ORDER BY sort_order ASC"
    );
    $stmt->execute($ids);
    $all_tags = $stmt->fetchAll();

    // Indexer les tags par solution_id
    $tags_map = [];
    foreach ($all_tags as $row) {
        $tags_map[(int) $row['solution_id']][] = $row['tag'];
    }

    // Fusionner les tags dans chaque solution
    foreach ($solutions as &$solution) {
        $id = (int) $solution['id'];
        $solution['tags'] = $tags_map[$id] ?? [];
    }
    unset($solution);

    json_response($solutions);
}

/**
 * Retourne le détail complet d'une solution par son slug.
 * Inclut modules, avantages, stats et tags.
 */
function get_solution_by_slug(string $slug): never
{
    $db = get_db();

    // Requête principale
    $stmt = $db->prepare(
        'SELECT id, slug, name, tagline, category,
                accent_color, accent_color_light,
                hero_image, price, short_description, full_description,
                brochure_url, demo_url,
                stat1_value, stat1_label,
                stat2_value, stat2_label,
                stat3_value, stat3_label
           FROM solutions
          WHERE slug = ? AND is_active = 1
          LIMIT 1'
    );
    $stmt->execute([$slug]);
    $solution = $stmt->fetch();

    if (!$solution) {
        error_response('Solution introuvable.', 404);
    }

    $id = (int) $solution['id'];

    // ---- Stats (reformatées en tableau pour le frontend) ----
    $solution['stats'] = [];
    for ($i = 1; $i <= 3; $i++) {
        $val = $solution["stat{$i}_value"] ?? null;
        $lbl = $solution["stat{$i}_label"] ?? null;
        if ($val !== null) {
            $solution['stats'][] = ['value' => $val, 'label' => $lbl];
        }
        // Retirer les colonnes brutes de la réponse
        unset($solution["stat{$i}_value"], $solution["stat{$i}_label"]);
    }

    // ---- Modules ----
    $stmt = $db->prepare(
        'SELECT title, description
           FROM solution_modules
          WHERE solution_id = ?
          ORDER BY sort_order ASC'
    );
    $stmt->execute([$id]);
    $solution['modules'] = $stmt->fetchAll();

    // ---- Avantages ----
    $stmt = $db->prepare(
        'SELECT text
           FROM solution_advantages
          WHERE solution_id = ?
          ORDER BY sort_order ASC'
    );
    $stmt->execute([$id]);
    $solution['advantages'] = array_column($stmt->fetchAll(), 'text');

    // ---- Tags ----
    $stmt = $db->prepare(
        'SELECT tag
           FROM solution_tags
          WHERE solution_id = ?
          ORDER BY sort_order ASC'
    );
    $stmt->execute([$id]);
    $solution['tags'] = array_column($stmt->fetchAll(), 'tag');

    json_response($solution);
}
