<?php
/**
 * POST /api/forms/inscription
 * ---------------------------------------------------------------
 * Traite le formulaire d'inscription à une formation.
 *
 * Flux :
 *  1. Validation et sanitisation des champs
 *  2. Insertion en base (inscription_submissions)
 *  3. Email de notification à l'admin
 *  4. Email de confirmation au candidat
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

// ---- Extraction et nettoyage des champs ----
$prenom     = trim($body['prenom']     ?? '');
$nom        = trim($body['nom']        ?? '');
$email      = trim($body['email']      ?? '');
$telephone  = trim($body['telephone']  ?? '');
$ville      = trim($body['ville']      ?? '');
$formation  = trim($body['formation']  ?? '');
$format     = trim($body['format']     ?? '');
$niveau     = trim($body['niveau']     ?? '');
$message    = trim($body['message']    ?? '');

// ---- Validation ----
$errors = [];

if ($prenom === '') {
    $errors['prenom'] = 'Le prénom est obligatoire.';
}
if ($nom === '') {
    $errors['nom'] = 'Le nom est obligatoire.';
}
if ($email === '') {
    $errors['email'] = 'L\'adresse email est obligatoire.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'L\'adresse email n\'est pas valide.';
}
if ($formation === '') {
    $errors['formation'] = 'Veuillez choisir une formation.';
}

$formats_valides = ['Présentiel', 'En ligne', 'Hybride'];
if (!in_array($format, $formats_valides, true)) {
    $errors['format'] = 'Le format choisi n\'est pas valide.';
}

$niveaux_valides = ['Débutant', 'Intermédiaire', 'Avancé'];
if (!in_array($niveau, $niveaux_valides, true)) {
    $errors['niveau'] = 'Le niveau choisi n\'est pas valide.';
}

// Longueurs
if (strlen($prenom) > 100)    { $errors['prenom']    = 'Prénom trop long (max 100 car.).'; }
if (strlen($nom) > 100)       { $errors['nom']       = 'Nom trop long (max 100 car.).'; }
if (strlen($ville) > 100)     { $errors['ville']     = 'Ville trop longue (max 100 car.).'; }
if (strlen($formation) > 150) { $errors['formation'] = 'Formation trop longue (max 150 car.).'; }
if (strlen($message) > 3000)  { $errors['message']   = 'Message trop long (max 3000 car.).'; }

if (!empty($errors)) {
    json_response(['success' => false, 'errors' => $errors], 422);
}

// ---- IP ----
$ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? null;
if ($ip) {
    $ip = trim(explode(',', $ip)[0]);
    $ip = filter_var($ip, FILTER_VALIDATE_IP) ? $ip : null;
}

try {
    $db = get_db();

    // ---- Insertion en base ----
    $stmt = $db->prepare(
        'INSERT INTO inscription_submissions
           (prenom, nom, email, telephone, ville,
            formation_requested, format_prefere, niveau,
            message, ip_address, status)
         VALUES
           (:prenom, :nom, :email, :telephone, :ville,
            :formation, :format, :niveau,
            :message, :ip, "new")'
    );
    $stmt->execute([
        ':prenom'    => $prenom,
        ':nom'       => $nom,
        ':email'     => $email,
        ':telephone' => $telephone,
        ':ville'     => $ville,
        ':formation' => $formation,
        ':format'    => $format,
        ':niveau'    => $niveau,
        ':message'   => $message,
        ':ip'        => $ip,
    ]);

    // ---- Préparation des variables HTML (échappées) ----
    $date          = date('d/m/Y à H:i');
    $prenom_s      = htmlspecialchars($prenom,    ENT_QUOTES, 'UTF-8');
    $nom_s         = htmlspecialchars($nom,       ENT_QUOTES, 'UTF-8');
    $email_s       = htmlspecialchars($email,     ENT_QUOTES, 'UTF-8');
    $telephone_s   = htmlspecialchars($telephone, ENT_QUOTES, 'UTF-8');
    $ville_s       = htmlspecialchars($ville,     ENT_QUOTES, 'UTF-8');
    $formation_s   = htmlspecialchars($formation, ENT_QUOTES, 'UTF-8');
    $format_s      = htmlspecialchars($format,    ENT_QUOTES, 'UTF-8');
    $niveau_s      = htmlspecialchars($niveau,    ENT_QUOTES, 'UTF-8');
    $message_s     = nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8'));

    // ---- Email de notification à l'admin ----
    $admin_html = Mailer::template(
        "Nouvelle inscription — {$formation_s}",
        <<<HTML
        <p>Une nouvelle demande d'inscription a été soumise via le site.</p>
        <div class="info-block">
          <div class="info-row">
            <span class="info-label">Prénom</span>
            <span class="info-value">{$prenom_s}</span>
          </div>
          <div class="info-row">
            <span class="info-label">Nom</span>
            <span class="info-value">{$nom_s}</span>
          </div>
          <div class="info-row">
            <span class="info-label">Email</span>
            <span class="info-value">{$email_s}</span>
          </div>
          <div class="info-row">
            <span class="info-label">Téléphone</span>
            <span class="info-value">{$telephone_s}</span>
          </div>
          <div class="info-row">
            <span class="info-label">Ville</span>
            <span class="info-value">{$ville_s}</span>
          </div>
          <div class="info-row">
            <span class="info-label">Formation</span>
            <span class="info-value"><strong>{$formation_s}</strong></span>
          </div>
          <div class="info-row">
            <span class="info-label">Format</span>
            <span class="info-value">{$format_s}</span>
          </div>
          <div class="info-row">
            <span class="info-label">Niveau</span>
            <span class="info-value">{$niveau_s}</span>
          </div>
          <div class="info-row">
            <span class="info-label">Date</span>
            <span class="info-value">{$date}</span>
          </div>
        </div>
        <p><strong>Message du candidat :</strong></p>
        <div class="message-block">{$message_s}</div>
        <p style="margin-top:20px; color:#6b7280; font-size:13px;">
          Contacter le candidat : <a href="mailto:{$email_s}">{$email_s}</a>
        </p>
        HTML
    );

    Mailer::notifyAdmin(
        "Nouvelle inscription — {$formation} — {$prenom} {$nom}",
        $admin_html
    );

    // ---- Email de confirmation au candidat ----
    $user_html = Mailer::template(
        'Votre demande d\'inscription a été reçue',
        <<<HTML
        <p>Bonjour <strong>{$prenom_s}</strong>,</p>
        <p style="margin: 16px 0;">
          Nous avons bien reçu votre demande d'inscription à la formation
          <strong>"{$formation_s}"</strong>.
        </p>
        <div class="info-block">
          <div class="info-row">
            <span class="info-label">Formation</span>
            <span class="info-value"><strong>{$formation_s}</strong></span>
          </div>
          <div class="info-row">
            <span class="info-label">Format souhaité</span>
            <span class="info-value">{$format_s}</span>
          </div>
          <div class="info-row">
            <span class="info-label">Votre niveau</span>
            <span class="info-value">{$niveau_s}</span>
          </div>
        </div>
        <p style="margin: 16px 0;">
          Notre équipe pédagogique vous contactera dans les <strong>24 heures</strong>
          pour finaliser votre inscription et vous communiquer les modalités pratiques.
        </p>
        <p>
          Pour toute question urgente, appelez-nous directement :<br>
          <strong>(+225) 01 01 57 30 54</strong> ou <strong>(+225) 07 07 26 18 58</strong>
        </p>
        HTML
    );

    Mailer::send(
        $email,
        "$prenom $nom",
        "Demande d'inscription reçue — {$formation} — Access Informatique",
        $user_html
    );

    json_response([
        'success' => true,
        'message' => "Merci {$prenom} ! Votre demande a bien été envoyée. Notre équipe vous contactera dans les 24h.",
        'prenom'  => $prenom,
        'email'   => $email,
    ]);

} catch (PDOException $e) {
    error_log('[API/forms/inscription] PDO : ' . $e->getMessage());
    error_response('Erreur interne du serveur. Veuillez réessayer.', 500);
}
