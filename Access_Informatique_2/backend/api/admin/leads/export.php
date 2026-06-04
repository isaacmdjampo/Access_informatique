<?php
/**
 * GET /api/admin/leads/export/contact
 * GET /api/admin/leads/export/inscriptions
 * ---------------------------------------------------------------
 * Exporte les leads en fichier CSV téléchargeable.
 * Le paramètre "type" est passé via l'URL (réécriture .htaccess).
 *
 * Endpoint protégé : JWT requis.
 * ---------------------------------------------------------------
 */

declare(strict_types=1);

require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/../../../includes/db.php';
require_once __DIR__ . '/../../../includes/Response.php';
require_once __DIR__ . '/../../../includes/Auth.php';

cors_headers();

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    error_response('Méthode non autorisée.', 405);
}

require_auth();

$type = strtolower(trim($_GET['type'] ?? ''));

if (!in_array($type, ['contact', 'inscriptions'], true)) {
    error_response('Type d\'export invalide. Valeurs acceptées : contact, inscriptions.', 422);
}

try {
    $db = get_db();

    if ($type === 'contact') {
        export_contacts($db);
    } else {
        export_inscriptions($db);
    }

} catch (PDOException $e) {
    error_log('[API/admin/leads/export] PDO : ' . $e->getMessage());
    error_response('Erreur interne du serveur.', 500);
}

// ============================================================

function export_contacts(PDO $db): never
{
    $rows = $db->query(
        'SELECT id, name, email, phone, subject, message, status, created_at
           FROM contact_submissions
          ORDER BY created_at DESC'
    )->fetchAll();

    $headers = ['ID','Nom','Email','Téléphone','Sujet','Message','Statut','Date'];
    $filename = 'contacts_' . date('Ymd_His') . '.csv';

    output_csv($filename, $headers, $rows, function (array $row): array {
        return [
            $row['id'],
            $row['name'],
            $row['email'],
            $row['phone']   ?? '',
            $row['subject'] ?? '',
            // Nettoyer les sauts de ligne dans le message pour ne pas casser le CSV
            str_replace(["\r\n", "\r", "\n"], ' ', $row['message']),
            $row['status'],
            $row['created_at'],
        ];
    });
}

function export_inscriptions(PDO $db): never
{
    $rows = $db->query(
        'SELECT id, prenom, nom, email, telephone, ville,
                formation_requested, format_prefere, niveau, message,
                status, created_at
           FROM inscription_submissions
          ORDER BY created_at DESC'
    )->fetchAll();

    $headers = [
        'ID','Prénom','Nom','Email','Téléphone','Ville',
        'Formation','Format','Niveau','Message','Statut','Date'
    ];
    $filename = 'inscriptions_' . date('Ymd_His') . '.csv';

    output_csv($filename, $headers, $rows, function (array $row): array {
        return [
            $row['id'],
            $row['prenom'],
            $row['nom'],
            $row['email'],
            $row['telephone']         ?? '',
            $row['ville']             ?? '',
            $row['formation_requested'],
            $row['format_prefere'],
            $row['niveau'],
            str_replace(["\r\n", "\r", "\n"], ' ', $row['message'] ?? ''),
            $row['status'],
            $row['created_at'],
        ];
    });
}

/**
 * Génère et envoie un fichier CSV directement au navigateur.
 *
 * @param string   $filename   Nom du fichier téléchargé
 * @param string[] $headers    En-têtes de colonnes
 * @param array[]  $rows       Données brutes
 * @param callable $mapper     Transforme chaque ligne en tableau de valeurs
 */
function output_csv(string $filename, array $headers, array $rows, callable $mapper): never
{
    // En-têtes HTTP pour le téléchargement
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');

    $out = fopen('php://output', 'w');

    // BOM UTF-8 pour que Excel ouvre correctement les accents
    fwrite($out, "\xEF\xBB\xBF");

    // Ligne d'en-têtes
    fputcsv($out, $headers, ';');

    // Lignes de données
    foreach ($rows as $row) {
        fputcsv($out, $mapper($row), ';');
    }

    fclose($out);
    exit;
}
