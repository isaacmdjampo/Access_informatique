<?php
/**
 * Mailer.php
 * ---------------------------------------------------------------
 * Wrapper centralisé autour de PHPMailer.
 * Toutes les notifications email du site passent par cette classe.
 *
 * Prérequis : exécuter "composer install" dans backend/
 * ---------------------------------------------------------------
 */

declare(strict_types=1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

// Chargement de l'autoloader Composer
require_once dirname(__DIR__) . '/vendor/autoload.php';

class Mailer
{
    /**
     * Envoie un email HTML via SMTP.
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
        $mail = new PHPMailer(true);

        try {
            // ---- Configuration SMTP ----
            $mail->isSMTP();
            $mail->Host       = MAIL_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = MAIL_USERNAME;
            $mail->Password   = MAIL_PASSWORD;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = MAIL_PORT;
            $mail->CharSet    = 'UTF-8';

            // ---- Expéditeur ----
            $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
            $mail->addReplyTo(MAIL_FROM, MAIL_FROM_NAME);

            // ---- Destinataire ----
            $mail->addAddress($to_email, $to_name);

            // ---- Contenu ----
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $html_body;
            $mail->AltBody = strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", $html_body));

            $mail->send();
            return true;

        } catch (PHPMailerException $e) {
            error_log('[Mailer] Erreur envoi vers ' . $to_email . ' : ' . $mail->ErrorInfo);
            return false;
        }
    }

    /**
     * Envoie une notification email à l'administrateur du site.
     * Utilisé pour tous les formulaires du site.
     */
    public static function notifyAdmin(string $subject, string $html_body): bool
    {
        return self::send(MAIL_ADMIN, MAIL_FROM_NAME, $subject, $html_body);
    }

    /**
     * Retourne un template HTML complet pour les emails transactionnels.
     *
     * @param string $title   Titre de l'email (affiché en vert)
     * @param string $content Corps HTML interne
     */
    public static function template(string $title, string $content): string
    {
        $year = date('Y');
        return <<<HTML
        <!DOCTYPE html>
        <html lang="fr">
        <head>
          <meta charset="UTF-8">
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <title>{$title}</title>
          <style>
            * { box-sizing: border-box; margin: 0; padding: 0; }
            body {
              font-family: 'Segoe UI', Arial, sans-serif;
              background-color: #f4f4f4;
              color: #333;
              line-height: 1.6;
            }
            .wrapper { max-width: 620px; margin: 32px auto; }
            .container {
              background: #ffffff;
              border-radius: 10px;
              overflow: hidden;
              box-shadow: 0 2px 12px rgba(0,0,0,.10);
            }
            .header {
              background: linear-gradient(135deg, #16a34a, #15803d);
              padding: 28px 36px;
            }
            .header h1 {
              color: #ffffff;
              font-size: 22px;
              font-weight: 700;
              margin-bottom: 4px;
            }
            .header p {
              color: rgba(255,255,255,.8);
              font-size: 13px;
            }
            .body { padding: 36px; }
            .body h2 {
              color: #16a34a;
              font-size: 18px;
              margin-bottom: 20px;
              padding-bottom: 12px;
              border-bottom: 2px solid #f0fdf4;
            }
            .info-block {
              background: #f9fafb;
              border-radius: 8px;
              padding: 20px 24px;
              margin: 16px 0;
            }
            .info-row {
              display: flex;
              padding: 8px 0;
              border-bottom: 1px solid #e5e7eb;
            }
            .info-row:last-child { border-bottom: none; }
            .info-label {
              font-weight: 600;
              color: #6b7280;
              min-width: 160px;
              font-size: 13px;
              text-transform: uppercase;
              letter-spacing: .4px;
            }
            .info-value { color: #111827; font-size: 14px; }
            .message-block {
              background: #f0fdf4;
              border-left: 4px solid #16a34a;
              border-radius: 0 8px 8px 0;
              padding: 16px 20px;
              margin: 16px 0;
              font-size: 14px;
              color: #374151;
            }
            .cta-btn {
              display: inline-block;
              background: #16a34a;
              color: #fff;
              padding: 12px 28px;
              border-radius: 6px;
              text-decoration: none;
              font-weight: 600;
              font-size: 14px;
              margin: 20px 0;
            }
            .footer {
              background: #f9fafb;
              padding: 20px 36px;
              border-top: 1px solid #e5e7eb;
              text-align: center;
              font-size: 12px;
              color: #9ca3af;
            }
            .footer a { color: #6b7280; text-decoration: none; }
          </style>
        </head>
        <body>
          <div class="wrapper">
            <div class="container">
              <div class="header">
                <h1>Access Informatique</h1>
                <p>Éditeur de solutions de gestion sur mesure</p>
              </div>
              <div class="body">
                <h2>{$title}</h2>
                {$content}
              </div>
              <div class="footer">
                &copy; {$year} Access Informatique &mdash;
                Yopougon Sable, Andokoi, Abidjan, Côte d'Ivoire<br>
                <a href="mailto:info@accessinformatique.com">info@accessinformatique.com</a> &nbsp;|&nbsp;
                (+225) 01 01 57 30 54
              </div>
            </div>
          </div>
        </body>
        </html>
        HTML;
    }
}
