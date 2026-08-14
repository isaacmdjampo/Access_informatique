<?php
/**
 * GET  /api/admin/contents          — liste tous les contenus groupés par page
 * GET  /api/admin/contents?page=home — contenus d'une page
 * PUT  /api/admin/contents?id=5     — modifier la valeur d'un contenu
 * ---------------------------------------------------------------
 * Endpoint protégé : JWT requis.
 * ---------------------------------------------------------------
 */

declare(strict_types=1);

require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/../../../includes/db.php';
require_once __DIR__ . '/../../../includes/Response.php';
require_once __DIR__ . '/../../../includes/Auth.php';
require_once __DIR__ . '/../../../includes/RoleCheck.php';

cors_headers();

$method = $_SERVER['REQUEST_METHOD'];

$payload = require_auth();
$current_admin_id = (int) ($payload['sub'] ?? 0);
require_role('admin', $current_admin_id);

if (!in_array($method, ['GET', 'PUT'], true)) {
    error_response('Méthode non autorisée.', 405);
}

try {
    match ($method) {
        'GET' => handle_get(),
        'PUT' => handle_put(),
    };
} catch (PDOException $e) {
    error_log('[API/admin/contents] PDO : ' . $e->getMessage());
    error_response('Erreur interne du serveur.', 500);
}

// ============================================================

function handle_get(): never
{
    $db   = get_db();
    $page = isset($_GET['page']) ? trim($_GET['page']) : null;

    if ($page !== null && $page !== '') {
        $stmt = $db->prepare(
            'SELECT id, page, key_name, label, value, updated_at
               FROM contents
              WHERE page = ?
              ORDER BY id ASC'
        );
        $stmt->execute([$page]);
        json_response($stmt->fetchAll());
    } else {
        $stmt = $db->query(
            'SELECT id, page, key_name, label, value, updated_at
               FROM contents
              ORDER BY page ASC, id ASC'
        );
        $rows   = $stmt->fetchAll();
        $result = [];
        foreach ($rows as $row) {
            $result[$row['page']][] = $row;
        }
        json_response($result);
    }
}

function handle_put(): never
{
    global $current_admin_id;
    $id   = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    $body = get_json_body();

    if ($id <= 0) {
        error_response('Paramètre "id" manquant ou invalide.', 422);
    }

    $value = $body['value'] ?? null;
    if ($value === null) {
        error_response('Champ "value" requis.', 422);
    }
    if (strlen((string) $value) > 65535) {
        error_response('Valeur trop longue.', 422);
    }

    $db = get_db();

    // Vérifier que le contenu existe
    $stmt = $db->prepare('SELECT page, key_name, value FROM contents WHERE id = ?');
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    if (!$row) {
        error_response('Contenu introuvable.', 404);
    }

    $stmt = $db->prepare('UPDATE contents SET value = ? WHERE id = ?');
    $stmt->execute([(string) $value, $id]);

    log_audit($current_admin_id, 'update', 'content', $id, [
        'page' => $row['page'],
        'key_name' => $row['key_name'],
        'old' => $row['value'],
        'new' => (string) $value,
    ]);

    json_response([
        'success' => true,
        'message' => 'Contenu mis à jour avec succès.',
        'id'      => $id,
    ]);
}
