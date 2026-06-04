<?php
/**
 * Response.php
 * ---------------------------------------------------------------
 * Fonctions utilitaires pour :
 *  - Envoyer les en-têtes CORS corrects
 *  - Retourner des réponses JSON (succès ou erreur)
 *  - Lire le corps JSON d'une requête entrante
 * ---------------------------------------------------------------
 */

declare(strict_types=1);

/**
 * Envoie les en-têtes CORS et gère les requêtes OPTIONS (preflight).
 *
 * Seule l'origine définie dans FRONTEND_URL est autorisée,
 * plus localhost:5173 et localhost:3000 en développement.
 */
function cors_headers(): void
{
    $origin = $_SERVER['HTTP_ORIGIN'] ?? '';

    $allowed_origins = array_filter([
        FRONTEND_URL,
        'http://localhost:5173',
        'http://localhost:3000',
        'http://127.0.0.1:5173',
    ]);

    if (in_array($origin, $allowed_origins, true)) {
        header("Access-Control-Allow-Origin: $origin");
    } else {
        // Fallback sur l'URL frontend configurée
        header('Access-Control-Allow-Origin: ' . FRONTEND_URL);
    }

    header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, X-Admin-Token');
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');  // Cache le preflight 24h

    // Répondre immédiatement aux requêtes preflight OPTIONS
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit;
    }
}

/**
 * Envoie une réponse JSON et stoppe l'exécution du script.
 *
 * @param mixed $data   Données à sérialiser en JSON
 * @param int   $status Code HTTP (200 par défaut)
 */
function json_response(mixed $data, int $status = 200): never
{
    header('Content-Type: application/json; charset=utf-8');
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    exit;
}

/**
 * Raccourci pour les réponses d'erreur.
 *
 * @param string $message Message d'erreur lisible
 * @param int    $status  Code HTTP d'erreur (400, 401, 403, 404, 405, 500...)
 */
function error_response(string $message, int $status = 400): never
{
    json_response(['success' => false, 'error' => $message], $status);
}

/**
 * Lit et décode le corps JSON de la requête HTTP entrante.
 *
 * Utilisé pour les requêtes POST/PUT avec Content-Type: application/json.
 * Retourne toujours un tableau (jamais null) pour simplifier le code
 * appelant.
 *
 * @return array<string, mixed>
 */
function get_json_body(): array
{
    $raw = file_get_contents('php://input');
    if (empty($raw)) {
        return [];
    }
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

/**
 * Sanitise une chaîne pour l'affichage HTML.
 * À utiliser sur toute valeur renvoyée dans une réponse JSON
 * destinée à être insérée directement dans du HTML.
 */
function sanitize(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}
