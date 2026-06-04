<?php
/**
 * GET /api/admin/admins          — Lister tous les admins
 * POST /api/admin/admins         — Créer un nouvel admin
 * PUT /api/admin/admins?id=X     — Mettre à jour un admin
 * DELETE /api/admin/admins?id=X  — Supprimer un admin
 * ---------------------------------------------------------------
 * Endpoint protégé : JWT + vérification de rôle.
 * Seul un superadmin peut tout faire.
 * Un admin peut créer des éditeurs mais pas modifier les rôles.
 * ---------------------------------------------------------------
 */

declare(strict_types=1);

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/Response.php';
require_once __DIR__ . '/../../includes/Auth.php';
require_once __DIR__ . '/../../includes/RoleCheck.php';

cors_headers();

$method = $_SERVER['REQUEST_METHOD'];

if (!in_array($method, ['GET', 'POST', 'PUT', 'DELETE'], true)) {
    error_response('Méthode non autorisée.', 405);
}

// ---- Authentification requise ----
$payload = require_auth();

try {
    $db = get_db();
    $current_admin_id = (int) ($payload['sub'] ?? 0);
    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

    switch ($method) {

        // ---- Lister tous les admins ----
        case 'GET':
            // Tous les admins authentifiés peuvent voir la liste
            $stmt = $db->query(
                'SELECT id, name, email, role, created_at, created_by, updated_by, updated_at
                   FROM admins
                  ORDER BY role DESC, name ASC'
            );
            $admins = $stmt->fetchAll();

            json_response([
                'data' => $admins,
                'total' => count($admins),
            ]);

        // ---- Créer un nouvel admin ----
        case 'POST':
            require_role('admin', $current_admin_id);

            $body = get_json_body();
            $name = trim($body['name'] ?? '');
            $email = trim($body['email'] ?? '');
            $password = trim($body['password'] ?? '');
            $role = trim($body['role'] ?? 'editor');

            $errors = [];

            if ($name === '') {
                $errors['name'] = 'Le nom est obligatoire.';
            } elseif (strlen($name) > 100) {
                $errors['name'] = 'Le nom ne doit pas dépasser 100 caractères.';
            }

            if ($email === '') {
                $errors['email'] = 'L\'email est obligatoire.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'L\'email n\'est pas valide.';
            }

            if ($password === '') {
                $errors['password'] = 'Le mot de passe est obligatoire.';
            } elseif (strlen($password) < 8) {
                $errors['password'] = 'Le mot de passe doit contenir au moins 8 caractères.';
            }

            // Un admin ne peut créer que des éditeurs
            if ($role !== 'editor') {
                $current_role = $db->prepare('SELECT role FROM admins WHERE id = ?');
                $current_role->execute([$current_admin_id]);
                $admin_data = $current_role->fetch();

                if ($admin_data['role'] !== 'superadmin') {
                    $errors['role'] = 'Seul un superadmin peut créer des admins.';
                } else if (!in_array($role, ['superadmin', 'admin', 'editor'], true)) {
                    $errors['role'] = 'Rôle invalide.';
                }
            }

            if (!empty($errors)) {
                json_response(['success' => false, 'errors' => $errors], 422);
            }

            // Vérifier que l'email n'existe pas
            $check = $db->prepare('SELECT id FROM admins WHERE email = ?');
            $check->execute([$email]);
            if ($check->fetch()) {
                error_response('Cet email existe déjà.', 409);
            }

            // Créer le nouvel admin
            $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
            $stmt = $db->prepare(
                'INSERT INTO admins (name, email, password_hash, role, created_by)
                 VALUES (?, ?, ?, ?, ?)'
            );
            $stmt->execute([$name, $email, $hash, $role, $current_admin_id]);
            $new_id = (int) $db->lastInsertId();

            log_audit($current_admin_id, 'create', 'admin', $new_id, [
                'name' => $name,
                'email' => $email,
                'role' => $role,
            ]);

            json_response([
                'success' => true,
                'message' => "Admin '{$name}' créé avec succès.",
                'admin' => [
                    'id' => $new_id,
                    'name' => $name,
                    'email' => $email,
                    'role' => $role,
                ],
            ], 201);

        // ---- Mettre à jour un admin ----
        case 'PUT':
            require_role('superadmin', $current_admin_id);

            if ($id <= 0) {
                error_response('ID admin manquant.', 422);
            }

            // Vérifier que l'admin existe
            $check = $db->prepare('SELECT * FROM admins WHERE id = ?');
            $check->execute([$id]);
            $admin = $check->fetch();

            if (!$admin) {
                error_response('Admin introuvable.', 404);
            }

            // Un superadmin ne peut pas se supprimer lui-même
            if ($id === $current_admin_id) {
                error_response('Vous ne pouvez pas modifier votre propre compte.', 403);
            }

            $body = get_json_body();
            $role = trim($body['role'] ?? '');

            if (!in_array($role, ['superadmin', 'admin', 'editor'], true)) {
                error_response('Rôle invalide.', 422);
            }

            // Enregistrer le changement
            if ($role !== $admin['role']) {
                $db->prepare('UPDATE admins SET role = ?, updated_by = ? WHERE id = ?')
                   ->execute([$role, $current_admin_id, $id]);

                log_audit($current_admin_id, 'update', 'admin', $id, [
                    'field' => 'role',
                    'old' => $admin['role'],
                    'new' => $role,
                ]);
            }

            json_response([
                'success' => true,
                'message' => 'Rôle mis à jour.',
            ]);

        // ---- Supprimer un admin ----
        case 'DELETE':
            require_role('superadmin', $current_admin_id);

            if ($id <= 0) {
                error_response('ID admin manquant.', 422);
            }

            // Vérifier que l'admin existe
            $check = $db->prepare('SELECT * FROM admins WHERE id = ?');
            $check->execute([$id]);
            $admin = $check->fetch();

            if (!$admin) {
                error_response('Admin introuvable.', 404);
            }

            // Un superadmin ne peut pas se supprimer lui-même
            if ($id === $current_admin_id) {
                error_response('Vous ne pouvez pas supprimer votre propre compte.', 403);
            }

            // Supprimer l'admin
            $db->prepare('DELETE FROM admins WHERE id = ?')->execute([$id]);

            log_audit($current_admin_id, 'delete', 'admin', $id, [
                'name' => $admin['name'],
                'email' => $admin['email'],
                'role' => $admin['role'],
            ]);

            json_response([
                'success' => true,
                'message' => 'Admin supprimé.',
            ]);
    }
} catch (PDOException $e) {
    error_log('[API/admin/admins] PDO: ' . $e->getMessage());
    error_response('Erreur interne du serveur.', 500);
}
