<?php
/**
 * GET /api/contents
 * GET /api/contents?page=home
 * ---------------------------------------------------------------
 * Retourne les textes statiques du site stockés en base.
 * Utilisé par le frontend Vue.js pour afficher des contenus
 * modifiables depuis le dashboard admin.
 *
 * Exemples :
 *   GET /api/contents?page=home
 *   → { "page": "home", "data": { "hero.title": "...", ... } }
 *
 *   GET /api/contents
 *   → { "home": { "hero.title": "..." }, "about": { ... }, ... }
 * ---------------------------------------------------------------
 */

declare(strict_types=1);

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/Response.php';

cors_headers();

// Cet endpoint n'accepte que GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    error_response('Méthode non autorisée.', 405);
}

try {
    $db   = get_db();
    $page = isset($_GET['page']) ? trim($_GET['page']) : null;

    if ($page !== null && $page !== '') {
        // ---- Contenus d'une seule page ----
        $stmt = $db->prepare(
            'SELECT key_name, value
               FROM contents
              WHERE page = ?
              ORDER BY id ASC'
        );
        $stmt->execute([$page]);
        $rows = $stmt->fetchAll();

        // Transformer le tableau de lignes en objet key => value
        $data = [];
        foreach ($rows as $row) {
            $data[$row['key_name']] = $row['value'];
        }

        json_response(['page' => $page, 'data' => $data]);

    } else {
        // ---- Tous les contenus groupés par page ----
        $stmt = $db->query(
            'SELECT page, key_name, value
               FROM contents
              ORDER BY page ASC, id ASC'
        );
        $rows = $stmt->fetchAll();

        $result = [];
        foreach ($rows as $row) {
            $result[$row['page']][$row['key_name']] = $row['value'];
        }

        json_response($result);
    }

} catch (PDOException $e) {
    error_log('[API/contents] PDO : ' . $e->getMessage());
    error_response('Erreur interne du serveur.', 500);
}
