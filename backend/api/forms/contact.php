<?php
/**
 * POST /api/forms/contact
 * ---------------------------------------------------------------
 * Traite le formulaire "Nous contacter".
 *
 * Flux :
 *  1. Validation et sanitisation des champs
 *  2. Insertion en base (contact_submissions)
 *  3. Email de notification à l'admin
 *  4. Email de confirmation au visiteur
 *  5. Réponse JSON au frontend
 * ---------------------------------------------------------------
 */

declare(strict_types=1);

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/Response.php';
require_once __DIR__ . '/../../includes/Mailer.php';

cors_headers();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    error_response('Méthode non autorisée.', 405);
}

// ---- Lecture du corps JSON ----
$body = get_json_body();

// ---- Validation des champs obligatoires ----
$errors = [];

$name    = trim($body['name']    ?? '');
$email   = trim($body['email']   ?? '');
$phone   = trim($body['phone']   ?? '');
$subject = trim($body['subject'] ?? '');
$message = trim($body['message'] ?? '');

if ($name === '') {
    $errors['name'] = 'Le nom est obligatoire.';
}
if ($email === '') {
    $errors['email'] = 'L\'adresse email est obligatoire.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'L\'adresse email n\'est pas valide.';
}
if ($message === '') {
    $errors['message'] = 'Le message est obligatoire.';
}

// Longueurs maximales
if (strlen($name) > 150) {
    $errors['name'] = 'Le nom ne doit pas dépasser 150 caractères.';
}
if (strlen($subject) > 255) {
    $errors['subject'] = 'Le sujet ne doit pas dépasser 255 caractères.';
}
if (strlen($message) > 5000) {
    $errors['message'] = 'Le message ne doit pas dépasser 5000 caractères.';
}

if (!empty($errors)) {
    json_response(['success' => false, 'errors' => $errors], 422);
}

// ---- Récupération de l'IP (avec support reverse proxy) ----
$ip = $_SERVER['HTTP_X_FORWARDED_FOR']
    ?? $_SERVER['REMOTE_ADDR']
    ?? null;
if ($ip) {
    // Prendre la première IP si liste (X-Forwarded-For peut en contenir plusieurs)
    $ip = trim(explode(',', $ip)[0]);
    $ip = filter_var($ip, FILTER_VALIDATE_IP) ? $ip : null;
}

try {
    $db = get_db();

    // ---- Insertion en base ----
    $stmt = $db->prepare(
        'INSERT INTO contact_submissions
           (name, email, phone, subject, message, ip_address, status)
         VALUES
           (:name, :email, :phone, :subject, :message, :ip, "new")'
    );
    $stmt->execute([
        ':name'    => $name,
        ':email'   => $email,
        ':phone'   => $phone,
        ':subject' => $subject,
        ':message' => $message,
        ':ip'      => $ip,
    ]);

    // ---- Email de notification à l'admin ----
    $date         = date('d/m/Y à H:i');
    $name_safe    = htmlspecialchars($name,    ENT_QUOTES, 'UTF-8');
    $email_safe   = htmlspecialchars($email,   ENT_QUOTES, 'UTF-8');
    $phone_safe   = htmlspecialchars($phone,   ENT_QUOTES, 'UTF-8');
    $subject_safe = htmlspecialchars($subject, ENT_QUOTES, 'UTF-8');
    $message_safe = nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8'));

    $admin_html = Mailer::template(
        'Nouveau message de contact',
        <<<HTML
        <p>Un nouveau message a été reçu via le formulaire de contact du site.</p>
        <div class="info-block">
          <div class="info-row">
            <span class="info-label">Nom</span>
            <span class="info-value">{$name_safe}</span>
          </div>
          <div class="info-row">
            <span class="info-label">Email</span>
            <span class="info-value">{$email_safe}</span>
          </div>
          <div class="info-row">
            <span class="info-label">Téléphone</span>
            <span class="info-value">{$phone_safe}</span>
          </div>
          <div class="info-row">
            <span class="info-label">Sujet</span>
            <span class="info-value">{$subject_safe}</span>
          </div>
          <div class="info-row">
            <span class="info-label">Date</span>
            <span class="info-value">{$date}</span>
          </div>
        </div>
        <p><strong>Message :</strong></p>
        <div class="message-block">{$message_safe}</div>
        <p style="margin-top:20px; color:#6b7280; font-size:13px;">
          Répondre directement à : <a href="mailto:{$email_safe}">{$email_safe}</a>
        </p>
        HTML
    );

    Mailer::notifyAdmin("Nouveau message de contact — {$name}", $admin_html);

    // ---- Email de confirmation au visiteur ----
    $user_html = Mailer::template(
        'Votre message a bien été reçu',
        <<<HTML
        <p>Bonjour <strong>{$name_safe}</strong>,</p>
        <p style="margin: 16px 0;">
          Nous avons bien reçu votre message et notre équipe vous répondra dans les plus
          brefs délais, généralement sous 24 heures.
        </p>
        <div class="message-block">
          <strong>Votre message :</strong><br><br>
          {$message_safe}
        </div>
        <p style="margin-top: 20px;">
          En attendant, n'hésitez pas à nous appeler directement au
          <strong>(+225) 01 01 57 30 54</strong>.
        </p>
        HTML
    );

    Mailer::send($email, $name, 'Votre message a bien été reçu — Access Informatique', $user_html);

    json_response([
        'success' => true,
        'message' => 'Votre message a bien été envoyé. Nous vous répondrons dans les 24h.',
    ]);

} catch (PDOException $e) {
    error_log('[API/forms/contact] PDO : ' . $e->getMessage());
    error_response('Erreur interne du serveur. Veuillez réessayer.', 500);
}
