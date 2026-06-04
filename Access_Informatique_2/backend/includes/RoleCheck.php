<?php
/**
 * RoleCheck.php
 * ---------------------------------------------------------------
 * Middleware pour vérifier les rôles et permissions.
 * Utilisé dans les endpoints protégés pour contrôler l'accès
 * selon le rôle de l'admin.
 * ---------------------------------------------------------------
 */

declare(strict_types=1);

/**
 * Rôles disponibles et leurs permissions
 */
const ROLES = [
    'superadmin' => 'Administrateur Suprême',
    'admin' => 'Administrateur',
    'editor' => 'Éditeur de contenu',
];

const ROLE_HIERARCHY = [
    'superadmin' => 3,  // Peut tout faire
    'admin' => 2,       // Peut gérer le contenu mais pas les admins
    'editor' => 1,      // Peut éditer le contenu uniquement
];

/**
 * Vérifie que l'admin connecté a au moins le rôle requis.
 *
 * @param string $required_role Le rôle requis (superadmin, admin, editor)
 * @param int $admin_id L'ID de l'admin (depuis le token JWT)
 * @return bool true si l'admin a le rôle requis
 */
function check_role(string $required_role, int $admin_id): bool
{
    if (!isset(ROLE_HIERARCHY[$required_role])) {
        return false;
    }

    try {
        $db = get_db();
        $stmt = $db->prepare('SELECT role FROM admins WHERE id = ? LIMIT 1');
        $stmt->execute([$admin_id]);
        $row = $stmt->fetch();

        if (!$row) {
            return false;
        }

        $admin_level = ROLE_HIERARCHY[$row['role']] ?? 0;
        $required_level = ROLE_HIERARCHY[$required_role];

        return $admin_level >= $required_level;
    } catch (PDOException $e) {
        error_log('[RoleCheck] PDO: ' . $e->getMessage());
        return false;
    }
}

/**
 * Exige que l'admin connecté ait au minimum le rôle spécifié.
 * Sinon, retourne une erreur 403.
 *
 * @param string $required_role Le rôle requis
 * @param int $admin_id L'ID de l'admin (depuis le token JWT)
 */
function require_role(string $required_role, int $admin_id): void
{
    if (!check_role($required_role, $admin_id)) {
        error_response(
            "Accès refusé. Rôle requis: {$required_role}",
            403
        );
    }
}

/**
 * Enregistre une action dans le log d'audit.
 *
 * @param int $admin_id ID de l'admin qui a fait l'action
 * @param string $action Type d'action (create, update, delete, etc.)
 * @param string $target_type Type de la cible (admin, solution, etc.)
 * @param int|null $target_id ID de la cible modifiée
 * @param array $changes Tableau ['old' => ..., 'new' => ...]
 */
function log_audit(
    int $admin_id,
    string $action,
    string $target_type,
    ?int $target_id,
    array $changes = []
): void {
    try {
        $db = get_db();
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $ip = trim(explode(',', $ip)[0]);
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';

        $stmt = $db->prepare(
            'INSERT INTO admin_audit_log
             (admin_id, action, target_type, target_id, changes, ip_address, user_agent)
             VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $admin_id,
            $action,
            $target_type,
            $target_id,
            !empty($changes) ? json_encode($changes) : null,
            $ip,
            substr($user_agent, 0, 500),
        ]);
    } catch (PDOException $e) {
        error_log('[log_audit] PDO: ' . $e->getMessage());
    }
}
