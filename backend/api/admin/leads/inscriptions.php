<?php
/**
 * GET    /api/admin/leads/inscriptions         — liste paginée
 * GET    /api/admin/leads/inscriptions?id=3    — détail d'une inscription
 * PUT    /api/admin/leads/inscriptions?id=3    — modifier le statut
 * DELETE /api/admin/leads/inscriptions?id=3    — supprimer
 * ---------------------------------------------------------------
 * Endpoint protégé : JWT requis.
 * Statuts possibles : new | contacted | enrolled | cancelled
 * Filtres URL : ?status=new&formation=...&page=1&per_page=20
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

        case 'GET':
            if ($id > 0) {
                $stmt = $db->prepare('SELECT * FROM inscription_submissions WHERE id = ? LIMIT 1');
                $stmt->execute([$id]);
                $row = $stmt->fetch();
                if (!$row) {
                    error_response('Inscription introuvable.', 404);
                }
                json_response($row);
            }

            // ---- Liste filtrée et paginée ----
            $page      = max(1, (int)($_GET['page']     ?? 1));
            $per_page  = min(100, max(1, (int)($_GET['per_page'] ?? 20)));
            $offset    = ($page - 1) * $per_page;
            $status    = $_GET['status']    ?? null;
            $formation = $_GET['formation'] ?? null;

            $conditions = [];
            $params     = [];

            if ($status !== null && in_array($status, ['new','contacted','enrolled','cancelled'], true)) {
                $conditions[] = 'status = ?';
                $params[]     = $status;
            }
            if ($formation !== null && $formation !== '') {
                $conditions[] = 'formation_requested LIKE ?';
                $params[]     = '%' . $formation . '%';
            }

            $where = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';

            $count_stmt = $db->prepare("SELECT COUNT(*) FROM inscription_submissions $where");
            $count_stmt->execute($params);
            $total = (int) $count_stmt->fetchColumn();

            $params[] = $per_page;
            $params[] = $offset;
            $stmt = $db->prepare(
                "SELECT id, prenom, nom, email, telephone, ville,
                        formation_requested, format_prefere, niveau,
                        status, created_at
                   FROM inscription_submissions
                   $where
                  ORDER BY created_at DESC
                  LIMIT ? OFFSET ?"
            );
            $stmt->execute($params);

            json_response([
                'data'      => $stmt->fetchAll(),
                'total'     => $total,
                'page'      => $page,
                'per_page'  => $per_page,
                'last_page' => (int) ceil($total / $per_page),
            ]);

        case 'PUT':
            if ($id <= 0) {
                error_response('Paramètre "id" manquant.', 422);
            }
            $data   = get_json_body();
            $status = trim($data['status'] ?? '');

            if (!in_array($status, ['new','contacted','enrolled','cancelled'], true)) {
                error_response('Statut invalide. Valeurs : new, contacted, enrolled, cancelled.', 422);
            }

            $exists = $db->prepare('SELECT id FROM inscription_submissions WHERE id = ?');
            $exists->execute([$id]);
            if (!$exists->fetch()) {
                error_response('Inscription introuvable.', 404);
            }

            $db->prepare('UPDATE inscription_submissions SET status = ? WHERE id = ?')
               ->execute([$status, $id]);

            json_response(['success' => true, 'message' => 'Statut mis à jour.']);

        case 'DELETE':
            if ($id <= 0) {
                error_response('Paramètre "id" manquant.', 422);
            }

            $exists = $db->prepare('SELECT id FROM inscription_submissions WHERE id = ?');
            $exists->execute([$id]);
            if (!$exists->fetch()) {
                error_response('Inscription introuvable.', 404);
            }

            $db->prepare('DELETE FROM inscription_submissions WHERE id = ?')->execute([$id]);
            json_response(['success' => true, 'message' => 'Inscription supprimée.']);
    }
} catch (PDOException $e) {
    error_log('[API/admin/leads/inscriptions] PDO : ' . $e->getMessage());
    error_response('Erreur interne du serveur.', 500);
}
