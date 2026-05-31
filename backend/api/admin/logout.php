<?php
/**
 * POST /api/admin/logout
 * ---------------------------------------------------------------
 * Déconnexion de l'administrateur.
 *
 * Avec JWT, la déconnexion est gérée côté client (suppression du
 * token du localStorage). Ce endpoint est fourni pour que le
 * frontend ait un point de terminaison propre et pour permettre
 * une éventuelle liste noire de tokens à l'avenir.
 *
 * Exige un token valide pour éviter des requêtes non autorisées.
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

// Vérifier que le token est valide avant d'accepter le logout
require_auth();

// Avec JWT stateless, la "déconnexion" réelle se fait côté client
// en supprimant le token du stockage local.
// Ce endpoint confirme juste que la requête a été traitée.
json_response([
    'success' => true,
    'message' => 'Déconnexion réussie.',
]);
