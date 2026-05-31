<?php
/**
 * POST /api/admin/upload
 * ---------------------------------------------------------------
 * Upload d'une image depuis le dashboard admin.
 *
 * Form-data attendu :
 *   image  = fichier (jpg, jpeg, png, webp, gif — max 5 Mo)
 *   type   = "solution" | "partner" | "formation"  (optionnel, défaut: "solution")
 *
 * Réponse succès :
 *   { "success": true, "url": "http://.../uploads/solutions/abc123.jpg" }
 *
 * Sécurité :
 *  - Vérification du MIME réel (finfo), pas juste l'extension
 *  - Nom de fichier régénéré (UUID-like), jamais le nom original
 *  - PHP interdit dans le dossier uploads/ via .htaccess
 * ---------------------------------------------------------------
 */

declare(strict_types=1);

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/Response.php';
require_once __DIR__ . '/../../includes/Auth.php';

cors_headers();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    error_response('Méthode non autorisée.', 405);
}

require_auth();

// ---- Validation du champ fichier ----
if (empty($_FILES['image']) || $_FILES['image']['error'] === UPLOAD_ERR_NO_FILE) {
    error_response('Aucun fichier reçu. Champ attendu : "image".', 422);
}

if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    error_response('Erreur lors de l\'envoi du fichier (code : ' . $_FILES['image']['error'] . ').', 422);
}

// ---- Type MIME réel (via finfo — plus fiable que l'extension) ----
$allowed_mimes = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/webp' => 'webp',
    'image/gif'  => 'gif',
];

$finfo     = new finfo(FILEINFO_MIME_TYPE);
$mime_type = $finfo->file($_FILES['image']['tmp_name']);

if (!isset($allowed_mimes[$mime_type])) {
    error_response('Type de fichier non autorisé. Formats acceptés : JPG, PNG, WEBP, GIF.', 422);
}

// ---- Taille maximale : 5 Mo ----
$max_size = 5 * 1024 * 1024; // 5 097 152 octets
if ($_FILES['image']['size'] > $max_size) {
    error_response('Fichier trop volumineux. Taille maximale : 5 Mo.', 422);
}

// ---- Sous-dossier selon le type ----
$type           = strtolower(trim($_POST['type'] ?? 'solution'));
$allowed_types  = ['solution', 'partner', 'formation'];
if (!in_array($type, $allowed_types, true)) {
    $type = 'solution';
}
$sub_folder = $type . 's'; // solution → solutions, partner → partners

// ---- Création du dossier si inexistant ----
$target_dir = UPLOAD_DIR . '/' . $sub_folder;
if (!is_dir($target_dir) && !mkdir($target_dir, 0755, true)) {
    error_log('[API/admin/upload] Impossible de créer : ' . $target_dir);
    error_response('Erreur interne : impossible de créer le dossier d\'upload.', 500);
}

// ---- Nom de fichier unique (jamais le nom original) ----
$extension = $allowed_mimes[$mime_type];
$filename  = bin2hex(random_bytes(16)) . '.' . $extension; // ex: a3f9c7...ab12.jpg
$target    = $target_dir . '/' . $filename;

// ---- Déplacer le fichier temporaire ----
if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
    error_log('[API/admin/upload] move_uploaded_file échoué vers : ' . $target);
    error_response('Erreur lors de la sauvegarde du fichier.', 500);
}

// ---- URL publique de l'image ----
$public_url = UPLOAD_URL . '/' . $sub_folder . '/' . $filename;

json_response([
    'success'  => true,
    'url'      => $public_url,
    'filename' => $filename,
]);
