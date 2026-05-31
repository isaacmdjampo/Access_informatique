<?php
/**
 * GET /api/partners
 * ---------------------------------------------------------------
 * Retourne la liste des partenaires / références actifs.
 * Utilisé par la page d'accueil et la page À propos pour afficher
 * le carrousel de logos.
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
    $db   = get_db();
    $stmt = $db->query(
        'SELECT id, name, logo_url
           FROM partners
          WHERE is_active = 1
          ORDER BY sort_order ASC, id ASC'
    );
    $partners = $stmt->fetchAll();

    json_response($partners);

} catch (PDOException $e) {
    error_log('[API/partners] PDO : ' . $e->getMessage());
    error_response('Erreur interne du serveur.', 500);
}
