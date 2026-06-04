<?php
/**
 * GET /api/admin/leads/contact
 * GET /api/admin/leads/contact?id=5       — détail d'un message
 * PUT /api/admin/leads/contact?id=5       — changer le statut (new/read/replied)
 * DELETE /api/admin/leads/contact?id=5   — supprimer un message
 * ---------------------------------------------------------------
 * Endpoint protégé : JWT requis.
 * Pagination : ?page=1&per_page=20
 * ---------------------------------------------------------------
 */

declare(strict_types=1);

require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/../../../includes/db.php';
require_once __DIR__ . '/../../../includes/Response.php';
require_once __DIR__ . '/../../../includes/Auth.php';

cors_headers();

$method = $_SERVER['REQUEST_METHOD'];

if (!in_array($method, ['GET', 'PUT', 'DELETE'], true)) {
    error_response('Méthode non autorisée.', 405);
}

require_auth();

try {
    $db = get_db();
    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

    switch ($method) {

        // ---- Liste paginée ou détail ----
        case 'GET':
            if ($id > 0) {
                // Détail d'un message
                $stmt = $db->prepare('SELECT * FROM contact_submissions WHERE id = ? LIMIT 1');
                $stmt->execute([$id]);
                $row = $stmt->fetch();
                if (!$row) {
                    error_response('Message introuvable.', 404);
                }
                // Marquer automatiquement comme "lu" à l'ouverture
                if ($row['status'] === 'new') {
                    $db->prepare('UPDATE contact_submissions SET status = "read" WHERE id = ?')
                       ->execute([$id]);
                    $row['status'] = 'read';
                }
                json_response($row);
            }

            // Liste paginée
            $page     = max(1, (int) ($_GET['page']     ?? 1));
            $per_page = min(100, max(1, (int) ($_GET['per_page'] ?? 20)));
            $offset   = ($page - 1) * $per_page;
            $status   = $_GET['status'] ?? null;

            $where  = '';
            $params = [];
            if ($status !== null && in_array($status, ['new','read','replied'], true)) {
                $where    = 'WHERE status = ?';
                $params[] = $status;
            }

            $total = $db->prepare("SELECT COUNT(*) FROM contact_submissions $where");
            $total->execute($params);
            $total_count = (int) $total->fetchColumn();

            $params[] = $per_page;
            $params[] = $offset;
            $stmt = $db->prepare(
                "SELECT id, name, email, phone, subject, status, created_at
                   FROM contact_submissions
                   $where
                  ORDER BY created_at DESC
                  LIMIT ? OFFSET ?"
            );
            $stmt->execute($params);
            $rows = $stmt->fetchAll();

            json_response([
                'data'       => $rows,
                'total'      => $total_count,
                'page'       => $page,
                'per_page'   => $per_page,
                'last_page'  => (int) ceil($total_count / $per_page),
            ]);

        // ---- Mise à jour du statut ----
        case 'PUT':
            if ($id <= 0) {
                error_response('Paramètre "id" manquant.', 422);
            }
            $data   = get_json_body();
            $status = trim($data['status'] ?? '');

            if (!in_array($status, ['new','read','replied'], true)) {
                error_response('Statut invalide. Valeurs acceptées : new, read, replied.', 422);
            }

            $exists = $db->prepare('SELECT id FROM contact_submissions WHERE id = ?');
            $exists->execute([$id]);
            if (!$exists->fetch()) {
                error_response('Message introuvable.', 404);
            }

            $db->prepare('UPDATE contact_submissions SET status = ? WHERE id = ?')
               ->execute([$status, $id]);

            json_response(['success' => true, 'message' => 'Statut mis à jour.']);

        // ---- Suppression ----
        case 'DELETE':
            if ($id <= 0) {
                error_response('Paramètre "id" manquant.', 422);
            }

            $exists = $db->prepare('SELECT id FROM contact_submissions WHERE id = ?');
            $exists->execute([$id]);
            if (!$exists->fetch()) {
                error_response('Message introuvable.', 404);
            }

            $db->prepare('DELETE FROM contact_submissions WHERE id = ?')->execute([$id]);
            json_response(['success' => true, 'message' => 'Message supprimé.']);
    }
} catch (PDOException $e) {
    error_log('[API/admin/leads/contact] PDO : ' . $e->getMessage());
    error_response('Erreur interne du serveur.', 500);
}
