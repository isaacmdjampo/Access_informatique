<?php
/**
 * GET /api/admin/stats
 * ---------------------------------------------------------------
 * Statistiques clés pour le tableau de bord admin.
 *
 * Retourne :
 *  - Nombre de solutions actives
 *  - Nombre de formations actives
 *  - Nombre de messages de contact reçus (total / non lus)
 *  - Nombre d'inscriptions reçues (total / non traitées)
 *  - Les 5 derniers messages de contact
 *  - Les 5 dernières inscriptions
 * ---------------------------------------------------------------
 */

declare(strict_types=1);

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/Response.php';
require_once __DIR__ . '/../../includes/Auth.php';

cors_headers();

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    error_response('Méthode non autorisée.', 405);
}

require_auth();

try {
    $db = get_db();

    // ---- Compteurs ----
    $solutions_count = $db->query(
        'SELECT COUNT(*) FROM solutions WHERE is_active = 1'
    )->fetchColumn();

    $formations_count = $db->query(
        'SELECT COUNT(*) FROM formations WHERE is_active = 1'
    )->fetchColumn();

    $contacts_total = $db->query(
        'SELECT COUNT(*) FROM contact_submissions'
    )->fetchColumn();

    $contacts_new = $db->query(
        'SELECT COUNT(*) FROM contact_submissions WHERE status = "new"'
    )->fetchColumn();

    $inscriptions_total = $db->query(
        'SELECT COUNT(*) FROM inscription_submissions'
    )->fetchColumn();

    $inscriptions_new = $db->query(
        'SELECT COUNT(*) FROM inscription_submissions WHERE status = "new"'
    )->fetchColumn();

    // ---- Derniers messages de contact ----
    $last_contacts = $db->query(
        'SELECT id, name, email, subject, status, created_at
           FROM contact_submissions
          ORDER BY created_at DESC
          LIMIT 5'
    )->fetchAll();

    // ---- Dernières inscriptions ----
    $last_inscriptions = $db->query(
        'SELECT id, prenom, nom, email, formation_requested, status, created_at
           FROM inscription_submissions
          ORDER BY created_at DESC
          LIMIT 5'
    )->fetchAll();

    json_response([
        'counters' => [
            'solutions'         => (int) $solutions_count,
            'formations'        => (int) $formations_count,
            'contacts_total'    => (int) $contacts_total,
            'contacts_new'      => (int) $contacts_new,
            'inscriptions_total'=> (int) $inscriptions_total,
            'inscriptions_new'  => (int) $inscriptions_new,
        ],
        'last_contacts'     => $last_contacts,
        'last_inscriptions' => $last_inscriptions,
    ]);

} catch (PDOException $e) {
    error_log('[API/admin/stats] PDO : ' . $e->getMessage());
    error_response('Erreur interne du serveur.', 500);
}
