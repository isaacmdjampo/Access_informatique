<?php
/**
 * RateLimit.php
 * ---------------------------------------------------------------
 * Limitation du nombre de requêtes par IP (rate limiting).
 *
 * Utilise des fichiers temporaires pour stocker les compteurs —
 * solution simple et sans dépendance externe (pas de Redis).
 *
 * Usage :
 *   rate_limit('login_' . $ip, 5, 300);
 *   → bloque si plus de 5 tentatives en 5 minutes depuis cette IP
 * ---------------------------------------------------------------
 */

declare(strict_types=1);

/**
 * Vérifie et incrémente le compteur de requêtes pour une clé donnée.
 * Appelle error_response(429) et stoppe l'exécution si la limite est atteinte.
 *
 * @param string $key            Identifiant unique (ex: "login_192.168.1.1")
 * @param int    $max_attempts   Nombre maximum de tentatives autorisées
 * @param int    $window_seconds Fenêtre temporelle en secondes
 */
function rate_limit(string $key, int $max_attempts = 10, int $window_seconds = 60): void
{
    $tmp_file = sys_get_temp_dir() . '/rl_' . md5($key) . '.json';
    $now      = time();
    $attempts = [];

    // Lire les tentatives précédentes
    if (file_exists($tmp_file)) {
        $raw      = file_get_contents($tmp_file);
        $attempts = is_string($raw) ? (json_decode($raw, true) ?? []) : [];
    }

    // Garder uniquement les tentatives dans la fenêtre temporelle
    $attempts = array_values(
        array_filter($attempts, fn(int $t): bool => $t > ($now - $window_seconds))
    );

    // Vérifier la limite avant d'ajouter la tentative courante
    if (count($attempts) >= $max_attempts) {
        $retry_after = $window_seconds;

        // En-tête standard pour informer le client
        header("Retry-After: $retry_after");
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(429);
        echo json_encode([
            'success' => false,
            'error'   => "Trop de tentatives. Réessayez dans $retry_after secondes.",
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Enregistrer la tentative courante
    $attempts[] = $now;
    file_put_contents($tmp_file, json_encode($attempts), LOCK_EX);
}

/**
 * Réinitialise le compteur pour une clé (ex: après connexion réussie).
 *
 * @param string $key Identifiant unique à effacer
 */
function rate_limit_reset(string $key): void
{
    $tmp_file = sys_get_temp_dir() . '/rl_' . md5($key) . '.json';
    if (file_exists($tmp_file)) {
        unlink($tmp_file);
    }
}
