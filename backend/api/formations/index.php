<?php
/**
 * GET /api/formations
 * GET /api/formations?slug=developpement-web-full-stack
 * ---------------------------------------------------------------
 * Retourne la liste des formations actives ou le détail d'une formation.
 *
 * GET /api/formations
 *   → tableau de formations avec skills (pour la liste)
 *
 * GET /api/formations?slug=developpement-web-full-stack
 *   → objet complet avec modules, bénéfices, résultats, skills (page détail)
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
        get_formation_by_slug($slug);
    } else {
        get_all_formations();
    }
} catch (PDOException $e) {
    error_log('[API/formations] PDO : ' . $e->getMessage());
    error_response('Erreur interne du serveur.', 500);
}

// ============================================================

/**
 * Liste de toutes les formations actives avec leurs compétences.
 */
function get_all_formations(): never
{
    $db = get_db();

    $stmt = $db->query(
        'SELECT id, slug, title, category, duration, level,
                price, description, image_url, sort_order
           FROM formations
          WHERE is_active = 1
          ORDER BY sort_order ASC, id ASC'
    );
    $formations = $stmt->fetchAll();

    if (empty($formations)) {
        json_response([]);
    }

    // Charger les skills de toutes les formations en une requête
    $ids          = array_column($formations, 'id');
    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    $stmt = $db->prepare(
        "SELECT formation_id, skill
           FROM formation_skills
          WHERE formation_id IN ($placeholders)
          ORDER BY sort_order ASC"
    );
    $stmt->execute($ids);
    $all_skills = $stmt->fetchAll();

    $skills_map = [];
    foreach ($all_skills as $row) {
        $skills_map[(int) $row['formation_id']][] = $row['skill'];
    }

    foreach ($formations as &$formation) {
        $formation['skills'] = $skills_map[(int) $formation['id']] ?? [];
    }
    unset($formation);

    json_response($formations);
}

/**
 * Détail complet d'une formation par son slug.
 */
function get_formation_by_slug(string $slug): never
{
    $db = get_db();

    $stmt = $db->prepare(
        'SELECT id, slug, title, category, duration, level,
                price, description, image_url
           FROM formations
          WHERE slug = ? AND is_active = 1
          LIMIT 1'
    );
    $stmt->execute([$slug]);
    $formation = $stmt->fetch();

    if (!$formation) {
        error_response('Formation introuvable.', 404);
    }

    $id = (int) $formation['id'];

    // ---- Skills ----
    $stmt = $db->prepare(
        'SELECT skill FROM formation_skills
          WHERE formation_id = ?
          ORDER BY sort_order ASC'
    );
    $stmt->execute([$id]);
    $formation['skills'] = array_column($stmt->fetchAll(), 'skill');

    // ---- Modules ----
    $stmt = $db->prepare(
        'SELECT title, description, duration
           FROM formation_modules
          WHERE formation_id = ?
          ORDER BY sort_order ASC'
    );
    $stmt->execute([$id]);
    $formation['modules'] = $stmt->fetchAll();

    // ---- Bénéfices ----
    $stmt = $db->prepare(
        'SELECT text FROM formation_benefits
          WHERE formation_id = ?
          ORDER BY sort_order ASC'
    );
    $stmt->execute([$id]);
    $formation['benefits'] = array_column($stmt->fetchAll(), 'text');

    // ---- Résultats attendus ----
    $stmt = $db->prepare(
        'SELECT text FROM formation_outcomes
          WHERE formation_id = ?
          ORDER BY sort_order ASC'
    );
    $stmt->execute([$id]);
    $formation['outcomes'] = array_column($stmt->fetchAll(), 'text');

    json_response($formation);
}
