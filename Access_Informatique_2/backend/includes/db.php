<?php
/**
 * db.php
 * ---------------------------------------------------------------
 * Fournit une connexion PDO MySQL unique (singleton) réutilisée
 * pour tout le cycle de vie d'une requête HTTP.
 *
 * Usage : $pdo = get_db();
 * ---------------------------------------------------------------
 */

declare(strict_types=1);

/**
 * Retourne l'instance PDO. La crée lors du premier appel,
 * puis la réutilise (static).
 *
 * @throws PDOException Si la connexion échoue
 */
function get_db(): PDO
{
    static $pdo = null;

    if ($pdo !== null) {
        return $pdo;
    }

    $dsn = sprintf(
        'mysql:host=%s;dbname=%s;charset=utf8mb4',
        DB_HOST,
        DB_NAME
    );

    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        // Lever des exceptions PHP sur toute erreur SQL
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        // Retourner les résultats en tableaux associatifs par défaut
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        // Désactiver l'émulation des requêtes préparées
        // (force les vraies requêtes préparées côté MySQL)
        PDO::ATTR_EMULATE_PREPARES   => false,
        // Persistance des connexions désactivée (plus sûr en production)
        PDO::ATTR_PERSISTENT         => false,
    ]);

    return $pdo;
}
