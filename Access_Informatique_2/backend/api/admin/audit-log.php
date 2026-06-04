<?php
/**
 * GET /api/admin/audit-log
 * ---------------------------------------------------------------
 * Endpoint protégé : JWT requis.
 * Seul les superadmin et admin peuvent voir le log d'audit.
 * ---------------------------------------------------------------
 */

declare(strict_types=1);

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/Response.php';
require_once __DIR__ . '/../../includes/Auth.php';
require_once __DIR__ . '/../../includes/RoleCheck.php';

cors_headers();

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    error_response('Méthode non autorisée.', 405);
}

require_auth();
$payload = get_token_payload();
$current_admin_id = (int) $payload['admin_id'];

// Seuls admin et superadmin peuvent voir l'audit
require_role('admin', $current_admin_id);

try {
    $db = get_db();

    $page = max(1, (int) ($_GET['page'] ?? 1));
    $per_page = min(100, max(1, (int) ($_GET['per_page'] ?? 50)));
    $offset = ($page - 1) * $per_page;

    $total = $db->query('SELECT COUNT(*) FROM admin_audit_log')->fetchColumn();

    $stmt = $db->prepare(
        'SELECT
            al.id, al.admin_id, al.action, al.target_type, al.target_id,
            al.changes, al.ip_address, al.created_at,
            a.name as admin_name, a.email as admin_email
         FROM admin_audit_log al
         LEFT JOIN admins a ON al.admin_id = a.id
         ORDER BY al.created_at DESC
         LIMIT ? OFFSET ?'
    );
    $stmt->execute([$per_page, $offset]);
    $logs = $stmt->fetchAll();

    // Parser les JSON et les dates
    foreach ($logs as &$log) {
        if ($log['changes']) {
            $log['changes'] = json_decode($log['changes'], true);
        }
        $log['timestamp'] = strtotime($log['created_at']);
    }

    json_response([
        'data' => $logs,
        'total' => (int) $total,
        'page' => $page,
        'per_page' => $per_page,
        'last_page' => (int) ceil($total / $per_page),
    ]);

} catch (PDOException $e) {
    error_log('[API/admin/audit-log] PDO: ' . $e->getMessage());
    error_response('Erreur interne du serveur.', 500);
}
