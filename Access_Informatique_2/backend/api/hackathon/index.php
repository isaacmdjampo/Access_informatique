<?php
/**
 * GET /api/hackathon
 * ---------------------------------------------------------------
 * Retourne toutes les données dynamiques de la page Hackathon :
 *  - Thèmes (hackathon_themes)
 *  - Programme jour par jour (hackathon_timeline)
 *  - Récompenses (hackathon_rewards)
 *  - Stats (depuis la table contents, page = hackathon)
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

try {
    $db = get_db();

    // ---- Thèmes ----
    $stmt = $db->query(
        'SELECT icon, title, description
           FROM hackathon_themes
          WHERE is_active = 1
          ORDER BY sort_order ASC'
    );
    $themes = $stmt->fetchAll();

    // ---- Programme (timeline) ----
    $stmt = $db->query(
        'SELECT day_label AS day, title, description
           FROM hackathon_timeline
          ORDER BY sort_order ASC'
    );
    $timeline = $stmt->fetchAll();

    // ---- Récompenses ----
    $stmt = $db->query(
        'SELECT emoji, title, amount,
                is_featured AS featured
           FROM hackathon_rewards
          ORDER BY sort_order ASC'
    );
    $rewards = $stmt->fetchAll();

    // Convertir is_featured en booléen
    foreach ($rewards as &$r) {
        $r['featured'] = (bool) $r['featured'];
    }
    unset($r);

    // ---- Stats (depuis contents) ----
    $stmt = $db->prepare(
        'SELECT key_name, value
           FROM contents
          WHERE page = ?
          ORDER BY id ASC'
    );
    $stmt->execute(['hackathon']);
    $rows  = $stmt->fetchAll();
    $texts = [];
    foreach ($rows as $row) {
        $texts[$row['key_name']] = $row['value'];
    }

    json_response([
        'themes'   => $themes,
        'timeline' => $timeline,
        'rewards'  => $rewards,
        'texts'    => $texts,
    ]);

} catch (PDOException $e) {
    error_log('[API/hackathon] PDO : ' . $e->getMessage());
    error_response('Erreur interne du serveur.', 500);
}
