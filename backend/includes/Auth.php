<?php
/**
 * Auth.php
 * ---------------------------------------------------------------
 * Implémentation JWT HS256 sans dépendance externe.
 *
 * Fournit :
 *  - JWT::encode()      — génère un token signé
 *  - JWT::decode()      — vérifie et décode un token
 *  - generate_token()   — crée un token pour un admin
 *  - require_auth()     — middleware : bloque avec 401 si non authentifié
 * ---------------------------------------------------------------
 */

declare(strict_types=1);

class JWT
{
    /**
     * Encode en base64 URL-safe sans padding (RFC 4648 §5).
     */
    private static function b64encode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * Décode depuis base64 URL-safe (ré-ajoute le padding si nécessaire).
     */
    private static function b64decode(string $data): string
    {
        $padding = strlen($data) % 4;
        if ($padding) {
            $data .= str_repeat('=', 4 - $padding);
        }
        return base64_decode(strtr($data, '-_', '+/'));
    }

    /**
     * Génère un token JWT signé HS256.
     *
     * @param array<string, mixed> $payload Données du token (sans mot de passe !)
     * @return string Le token JWT (header.payload.signature)
     */
    public static function encode(array $payload): string
    {
        $header = self::b64encode(
            json_encode(['alg' => 'HS256', 'typ' => 'JWT'], JSON_THROW_ON_ERROR)
        );
        $claims = self::b64encode(
            json_encode($payload, JSON_THROW_ON_ERROR)
        );
        $signature = self::b64encode(
            hash_hmac('sha256', "$header.$claims", JWT_SECRET, true)
        );

        return "$header.$claims.$signature";
    }

    /**
     * Vérifie la signature et l'expiration d'un token JWT.
     *
     * @param string $token Token JWT brut
     * @return array<string, mixed>|null Payload si valide, null sinon
     */
    public static function decode(string $token): ?array
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return null;
        }

        [$header, $claims, $signature] = $parts;

        // Recalculer la signature attendue et comparer
        // hash_equals() est à temps constant : protège contre les timing attacks
        $expected = self::b64encode(
            hash_hmac('sha256', "$header.$claims", JWT_SECRET, true)
        );
        if (!hash_equals($expected, $signature)) {
            return null;
        }

        // Décoder le payload
        $payload = json_decode(self::b64decode($claims), true);
        if (!is_array($payload)) {
            return null;
        }

        // Vérifier l'expiration
        if (!isset($payload['exp']) || $payload['exp'] < time()) {
            return null;
        }

        return $payload;
    }
}

/**
 * Génère un token JWT pour un administrateur authentifié.
 *
 * @param int    $admin_id ID de l'admin en base
 * @param string $email    Email de l'admin
 */
function generate_token(int $admin_id, string $email): string
{
    return JWT::encode([
        'sub'   => $admin_id,
        'email' => $email,
        'iat'   => time(),
        'exp'   => time() + JWT_EXPIRY,
    ]);
}

/**
 * Extrait et vérifie le token depuis l'en-tête Authorization.
 *
 * Format attendu : "Authorization: Bearer <token>"
 *
 * @return array<string, mixed>|null Payload ou null si absent/invalide
 */
function get_token_payload(): ?array
{
    // STRATÉGIE PRINCIPALE : header X-Admin-Token
    // Apache/mod_fcgid bloque Authorization mais laisse passer les headers personnalisés.
    $token = $_SERVER['HTTP_X_ADMIN_TOKEN'] ?? '';

    // Fallbacks pour Authorization: Bearer (si un jour on change de config Apache)
    if (empty($token)) {
        $auth = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';
        if (empty($auth) && function_exists('getallheaders')) {
            $h    = getallheaders();
            $auth = $h['Authorization'] ?? $h['authorization'] ?? '';
        }
        if (str_starts_with($auth, 'Bearer ')) {
            $token = trim(substr($auth, 7));
        }
    }

    if (empty($token)) {
        return null;
    }

    return JWT::decode($token);
}

/**
 * Middleware d'authentification.
 * À appeler au début de chaque endpoint admin protégé.
 *
 * Envoie une réponse 401 et stoppe l'exécution si non authentifié.
 *
 * @return array<string, mixed> Le payload JWT si authentifié
 */
function require_auth(): array
{
    $payload = get_token_payload();

    if ($payload === null) {
        // Pas d'include Response.php possible ici car on peut pas être sûr
        // qu'il est déjà chargé — on l'utilise directement
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(401);
        echo json_encode([
            'success' => false,
            'error'   => 'Non autorisé — token manquant ou expiré.',
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    return $payload;
}
