<?php
/**
 * Mailer.php
 * ---------------------------------------------------------------
 * Wrapper centralisé pour les notifications email.
 * Utilise la fonction mail() native PHP (pas de dépendances).
 * ---------------------------------------------------------------
 */

declare(strict_types=1);

class Mailer
{
    /**
     * Envoie un email HTML.
     *
     * @param string $to_email  Adresse email du destinataire
     * @param string $to_name   Nom du destinataire
     * @param string $subject   Objet de l'email
     * @param string $html_body Corps HTML
     * @return bool             true si envoyé avec succès
     */
    public static function send(
        string $to_email,
        string $to_name,
        string $subject,
        string $html_body
    ): bool {
        // Valider l'email
        if (!filter_var($to_email, FILTER_VALIDATE_EMAIL)) {
            error_log("[Mailer] Email invalide : $to_email");
            return false;
        }

        // Destinataire au format "Nom <email>"
        $to = "{$to_name} <{$to_email}>";

        // Entêtes
        $headers = [
            "MIME-Version: 1.0",
            "Content-Type: text/html; charset=UTF-8",
            "From: noreply@accessinformatique.com",
            "Reply-To: info@accessinformatique.com",
            "X-Mailer: PHP/" . phpversion(),
        ];

        // Envoyer l'email
        $result = mail($to, $subject, $html_body, implode("\r\n", $headers));

        if (!$result) {
            error_log("[Mailer] Échec d'envoi à {$to_email}");
            return false;
        }

        error_log("[Mailer] Email envoyé à {$to_email}");
        return true;
    }

    /**
     * Envoie une notification à l'admin.
     *
     * @param string $subject   Objet de l'email
     * @param string $html_body Corps HTML
     * @return bool             true si envoyé avec succès
     */
    public static function notifyAdmin(string $subject, string $html_body): bool
    {
        $admin_email = defined('MAIL_ADMIN') ? MAIL_ADMIN : 'admin@accessinformatique.com';
        return self::send($admin_email, 'Administrateur', $subject, $html_body);
    }

    /**
     * Génère un template HTML cohérent pour les emails.
     *
     * @param string $title Titre du message
     * @param string $body  Contenu HTML du message
     * @return string       HTML complet du template
     */
    public static function template(string $title, string $body): string
    {
        return <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$title}</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; background: #f9fafb; }
        .content { background: white; border-radius: 8px; padding: 30px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .header { border-bottom: 3px solid #166030; margin-bottom: 20px; padding-bottom: 15px; }
        .header h1 { margin: 0; color: #166030; font-size: 24px; }
        .info-block { background: #f3f4f6; border-left: 4px solid #166030; padding: 15px; margin: 15px 0; border-radius: 4px; }
        .info-row { display: flex; margin: 10px 0; }
        .info-label { font-weight: 600; min-width: 120px; color: #166030; }
        .info-value { flex: 1; }
        .message-block { background: #f0fdf4; border: 1px solid #d1fae5; padding: 15px; border-radius: 6px; margin: 15px 0; }
        .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; font-size: 12px; color: #6b7280; text-align: center; }
        a { color: #166030; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <div class="content">
            <div class="header">
                <h1>{$title}</h1>
            </div>
            {$body}
            <div class="footer">
                <p>© Access Informatique. Tous droits réservés.</p>
            </div>
        </div>
    </div>
</body>
</html>
HTML;
    }
}
