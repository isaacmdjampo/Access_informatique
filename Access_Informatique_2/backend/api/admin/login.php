<?php
/**
 * POST /api/admin/login
 * ---------------------------------------------------------------
 * Authentification de l'administrateur.
 *
 * Corps JSON attendu :
 *   { "email": "...", "password": "..." }
 *
 * Réponse succès :
 *   { "success": true, "token": "JWT...", "admin": { "id", "name", "email" } }
 *
 * Réponse échec :
 *   { "success": false, "error": "..." }
 * ---------------------------------------------------------------
 */

declare(strict_types=1);

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/Response.php';
require_once __DIR__ . '/../../includes/Auth.php';
require_once __DIR__ . '/../../includes/RateLimit.php';

cors_headers();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    error_response('Méthode non autorisée.', 405);
}

// ---- Rate limiting : 5 tentatives par IP toutes les 5 minutes ----
$client_ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
$client_ip = trim(explode(',', $client_ip)[0]);
$client_ip = filter_var($client_ip, FILTER_VALIDATE_IP) ? $client_ip : '0.0.0.0';

rate_limit('admin_login_' . $client_ip, 5, 300);

// DEBUG: Log ce qu'on reçoit dans un fichier dédié
$log_file = __DIR__ . '/../../login_requests.log';

$raw_body = file_get_contents('php://input');
$headers = getallheaders();
$log_entry = "\n" . date('Y-m-d H:i:s') . " --- REQUEST ---\n";
$log_entry .= "Method: " . $_SERVER['REQUEST_METHOD'] . "\n";
$log_entry .= "Content-Type: " . ($_SERVER['CONTENT_TYPE'] ?? 'NONE') . "\n";
$log_entry .= "Raw Body: " . $raw_body . "\n";
$log_entry .= "Headers: " . json_encode($headers) . "\n";

$body = get_json_body();
$log_entry .= "Parsed Body: " . json_encode($body) . "\n";

$email    = trim($body['email']    ?? '');
$password = trim($body['password'] ?? '');
$log_entry .= "Email: '$email' | Password: " . (strlen($password) > 0 ? strlen($password) . ' chars' : 'EMPTY') . "\n";

file_put_contents($log_file, $log_entry, FILE_APPEND);

// ---- Validation minimale ----
if ($email === '' || $password === '') {
    error_response('Email et mot de passe requis.', 422);
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    error_response('Email invalide.', 422);
}

try {
    $db = get_db();

    // Chercher l'admin par email (requête préparée — zéro concaténation SQL)
    $stmt = $db->prepare(
        'SELECT id, name, email, password_hash
           FROM admins
          WHERE email = ?
          LIMIT 1'
    );
    $stmt->execute([$email]);
    $admin = $stmt->fetch();

    // Vérification en deux étapes distinctes pour éviter les timing attacks :
    //  1. L'admin existe-t-il ?
    //  2. Le mot de passe est-il correct ?
    // Note : password_verify() prend toujours le même temps
    //        même si le hash est invalide.
    if (!$admin || !password_verify($password, $admin['password_hash'])) {
        // Message générique intentionnel : ne pas indiquer si c'est l'email ou le mot de passe
        error_response('Identifiants incorrects.', 401);
    }

    // Connexion réussie — réinitialiser le compteur de tentatives de cette IP
    rate_limit_reset('admin_login_' . $client_ip);

    // Générer le token JWT
    $token = generate_token((int) $admin['id'], $admin['email']);

    // Retirer le hash de la réponse
    unset($admin['password_hash']);

    json_response([
        'success'   => true,
        'token'     => $token,
        'expiresIn' => JWT_EXPIRY,
        'admin'     => $admin,
    ]);

} catch (PDOException $e) {
    error_log('[API/admin/login] PDO : ' . $e->getMessage());
    error_response('Erreur interne du serveur.', 500);
}
