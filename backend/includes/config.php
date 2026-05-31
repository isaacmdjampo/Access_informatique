<?php
/**
 * config.php
 * ---------------------------------------------------------------
 * Charge les variables d'environnement depuis .env et définit
 * toutes les constantes utilisées par le backend.
 *
 * Ce fichier doit être le PREMIER include de chaque endpoint.
 * ---------------------------------------------------------------
 */

declare(strict_types=1);

/**
 * Lit le fichier .env et injecte les variables dans $_ENV / putenv().
 * N'écrase pas les variables déjà définies (utile en production
 * où les variables sont injectées par le serveur).
 */
function load_env(string $path): void
{
    if (!file_exists($path)) {
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);

        // Ignorer les commentaires et lignes vides
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }

        // Séparer au premier signe = seulement
        $pos = strpos($line, '=');
        if ($pos === false) {
            continue;
        }

        $key = trim(substr($line, 0, $pos));
        $val = trim(substr($line, $pos + 1));

        // Retirer les guillemets éventuels autour de la valeur
        if (preg_match('/^"(.*)"$/', $val, $m) || preg_match("/^'(.*)'$/", $val, $m)) {
            $val = $m[1];
        }

        if ($key !== '' && !array_key_exists($key, $_ENV)) {
            putenv("$key=$val");
            $_ENV[$key]    = $val;
            $_SERVER[$key] = $val;
        }
    }
}

// Chemin vers le .env à la racine du backend/
load_env(dirname(__DIR__) . '/.env');

// ---- Application ----
define('APP_ENV', $_ENV['APP_ENV'] ?? 'production');
define('APP_URL', rtrim($_ENV['APP_URL'] ?? 'http://localhost/Access_Informatique/backend', '/'));

// ---- Base de données ----
define('DB_HOST', $_ENV['DB_HOST'] ?? '127.0.0.1');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'access_informatique');
define('DB_USER', $_ENV['DB_USER'] ?? 'root');
define('DB_PASS', $_ENV['DB_PASS'] ?? '');

// ---- JWT (authentification admin) ----
define('JWT_SECRET', $_ENV['JWT_SECRET'] ?? 'changeme_not_secure');
define('JWT_EXPIRY', (int) ($_ENV['JWT_EXPIRY'] ?? 3600));  // en secondes

// ---- Mailer (PHPMailer SMTP) ----
define('MAIL_HOST',      $_ENV['MAIL_HOST']      ?? 'smtp.gmail.com');
define('MAIL_PORT',      (int) ($_ENV['MAIL_PORT'] ?? 587));
define('MAIL_USERNAME',  $_ENV['MAIL_USERNAME']  ?? '');
define('MAIL_PASSWORD',  $_ENV['MAIL_PASSWORD']  ?? '');
define('MAIL_FROM',      $_ENV['MAIL_FROM']      ?? 'noreply@accessinformatique.com');
define('MAIL_FROM_NAME', $_ENV['MAIL_FROM_NAME'] ?? 'Access Informatique');
define('MAIL_ADMIN',     $_ENV['MAIL_ADMIN']     ?? 'admin@accessinformatique.com');

// ---- Uploads ----
// Chemin absolu sur le disque où stocker les fichiers uploadés
define('UPLOAD_DIR', dirname(__DIR__) . '/uploads');
// URL publique correspondante pour afficher les images côté frontend
define('UPLOAD_URL', rtrim($_ENV['UPLOAD_URL'] ?? (APP_URL . '/uploads'), '/'));

// ---- Frontend (origine autorisée pour CORS) ----
define('FRONTEND_URL', rtrim($_ENV['FRONTEND_URL'] ?? 'http://localhost:5173', '/'));

// ---- Affichage des erreurs PHP ----
if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

// Fuseau horaire d'Abidjan (UTC+0, pas de changement d'heure)
date_default_timezone_set('Africa/Abidjan');
