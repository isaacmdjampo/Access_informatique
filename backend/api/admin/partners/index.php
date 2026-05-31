<?php
/**
 * GET    /api/admin/partners       — liste tous les partenaires
 * POST   /api/admin/partners       — ajouter un partenaire
 * PUT    /api/admin/partners?id=2  — modifier un partenaire
 * DELETE /api/admin/partners?id=2  — supprimer un partenaire
 * ---------------------------------------------------------------
 * Endpoint protégé : JWT requis.
 * ---------------------------------------------------------------
 */

declare(strict_types=1);

require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/../../../includes/db.php';
require_once __DIR__ . '/../../../includes/Response.php';
require_once __DIR__ . '/../../../includes/Auth.php';

cors_headers();

$method = $_SERVER['REQUEST_METHOD'];

if (!in_array($method, ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'], true)) {
    error_response('Méthode non autorisée.', 405);
}

require_auth();

try {
    $db = get_db();

    switch ($method) {
        // ---- Liste ----
        case 'GET':
            $rows = $db->query(
                'SELECT id, name, logo_url, is_active, sort_order
                   FROM partners
                  ORDER BY sort_order ASC, id ASC'
            )->fetchAll();
            json_response($rows);

        // ---- Création ----
        case 'POST':
            $data = get_json_body();
            $name = trim($data['name'] ?? '');
            $logo = trim($data['logo_url'] ?? '');

            if ($name === '' || $logo === '') {
                error_response('Champs "name" et "logo_url" obligatoires.', 422);
            }

            $stmt = $db->prepare(
                'INSERT INTO partners (name, logo_url, is_active, sort_order)
                 VALUES (?, ?, ?, ?)'
            );
            $stmt->execute([
                substr($name, 0, 150),
                substr($logo, 0, 500),
                isset($data['is_active']) ? (int) $data['is_active'] : 1,
                isset($data['sort_order']) ? (int) $data['sort_order'] : 0,
            ]);
            json_response([
                'success' => true,
                'message' => 'Partenaire ajouté.',
                'id'      => (int) $db->lastInsertId(),
            ], 201);

        // ---- Modification ----
        case 'PUT':
            $id   = isset($_GET['id']) ? (int) $_GET['id'] : 0;
            $data = get_json_body();

            if ($id <= 0) {
                error_response('Paramètre "id" manquant.', 422);
            }

            $exists = $db->prepare('SELECT id FROM partners WHERE id = ?');
            $exists->execute([$id]);
            if (!$exists->fetch()) {
                error_response('Partenaire introuvable.', 404);
            }

            $name = trim($data['name']     ?? '');
            $logo = trim($data['logo_url'] ?? '');

            if ($name === '' || $logo === '') {
                error_response('Champs "name" et "logo_url" obligatoires.', 422);
            }

            $db->prepare(
                'UPDATE partners SET name=?, logo_url=?, is_active=?, sort_order=? WHERE id=?'
            )->execute([
                substr($name, 0, 150),
                substr($logo, 0, 500),
                isset($data['is_active'])  ? (int) $data['is_active']  : 1,
                isset($data['sort_order']) ? (int) $data['sort_order'] : 0,
                $id,
            ]);
            json_response(['success' => true, 'message' => 'Partenaire mis à jour.']);

        // ---- Toggle actif/inactif ----
        case 'PATCH':
            $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
            if ($id <= 0) error_response('Paramètre "id" manquant.', 422);
            $d         = get_json_body();
            $is_active = isset($d['is_active']) ? (int)(bool)$d['is_active'] : null;
            if ($is_active === null) error_response('Champ "is_active" requis.', 422);
            $s = $db->prepare('UPDATE partners SET is_active = ? WHERE id = ?');
            $s->execute([$is_active, $id]);
            if ($s->rowCount() === 0) error_response('Partenaire introuvable.', 404);
            json_response(['success' => true, 'is_active' => $is_active]);

        // ---- Suppression ----
        case 'DELETE':
            $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

            if ($id <= 0) {
                error_response('Paramètre "id" manquant.', 422);
            }

            $exists = $db->prepare('SELECT id FROM partners WHERE id = ?');
            $exists->execute([$id]);
            if (!$exists->fetch()) {
                error_response('Partenaire introuvable.', 404);
            }

            $db->prepare('DELETE FROM partners WHERE id = ?')->execute([$id]);
            json_response(['success' => true, 'message' => 'Partenaire supprimé.']);
    }
} catch (PDOException $e) {
    error_log('[API/admin/partners] PDO : ' . $e->getMessage());
    error_response('Erreur interne du serveur.', 500);
}
