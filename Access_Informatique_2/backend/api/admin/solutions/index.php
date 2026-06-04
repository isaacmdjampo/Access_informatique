<?php
/**
 * GET    /api/admin/solutions        — liste toutes les solutions
 * GET    /api/admin/solutions?id=3   — détail d'une solution
 * POST   /api/admin/solutions        — créer une solution
 * PUT    /api/admin/solutions?id=3   — modifier une solution
 * DELETE /api/admin/solutions?id=3   — supprimer une solution
 * ---------------------------------------------------------------
 * Endpoint protégé : JWT requis.
 * Les modules, avantages et tags sont remplacés en totalité
 * à chaque mise à jour (delete + insert).
 * ---------------------------------------------------------------
 */

declare(strict_types=1);

require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/../../../includes/db.php';
require_once __DIR__ . '/../../../includes/Response.php';
require_once __DIR__ . '/../../../includes/Auth.php';

cors_headers();

$method = $_SERVER['REQUEST_METHOD'];

if (!in_array($method, ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'], true)) {
    error_response('Méthode non autorisée.', 405);
}

require_auth();

try {
    match ($method) {
        'GET'    => handle_get(),
        'POST'   => handle_post(),
        'PUT'    => handle_put(),
        'PATCH'  => handle_patch(),
        'DELETE' => handle_delete(),
    };
} catch (PDOException $e) {
    error_log('[API/admin/solutions] PDO : ' . $e->getMessage());
    error_response('Erreur interne du serveur.', 500);
}

// ============================================================

function handle_get(): never
{
    $db = get_db();
    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

    if ($id > 0) {
        // ---- Détail d'une solution ----
        $stmt = $db->prepare('SELECT * FROM solutions WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $sol = $stmt->fetch();
        if (!$sol) {
            error_response('Solution introuvable.', 404);
        }

        $sol['modules']    = fetch_related('solution_modules',    $id, ['title','description','sort_order']);
        $sol['advantages'] = fetch_related('solution_advantages', $id, ['text','sort_order']);
        $sol['tags']       = fetch_related('solution_tags',       $id, ['tag','sort_order']);

        json_response($sol);
    } else {
        // ---- Liste de toutes les solutions ----
        $rows = $db->query(
            'SELECT id, slug, name, tagline, category, price,
                    is_active, sort_order, created_at, updated_at
               FROM solutions
              ORDER BY sort_order ASC, id ASC'
        )->fetchAll();
        json_response($rows);
    }
}

function handle_post(): never
{
    $data = get_json_body();
    validate_solution_data($data);

    $db = get_db();
    $db->beginTransaction();

    $stmt = $db->prepare(
        'INSERT INTO solutions
           (slug, name, tagline, category, accent_color, accent_color_light,
            hero_image, price, short_description, full_description,
            brochure_url, demo_url,
            stat1_value, stat1_label, stat2_value, stat2_label, stat3_value, stat3_label,
            is_active, sort_order)
         VALUES
           (:slug, :name, :tagline, :category, :accent_color, :accent_color_light,
            :hero_image, :price, :short_desc, :full_desc,
            :brochure, :demo,
            :s1v, :s1l, :s2v, :s2l, :s3v, :s3l,
            :active, :order)'
    );
    $stmt->execute(build_solution_params($data));
    $id = (int) $db->lastInsertId();

    replace_related($db, $id, $data);

    $db->commit();

    json_response([
        'success' => true,
        'message' => 'Solution créée avec succès.',
        'id'      => $id,
    ], 201);
}

function handle_put(): never
{
    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    if ($id <= 0) {
        error_response('Paramètre "id" manquant ou invalide.', 422);
    }

    $data = get_json_body();
    validate_solution_data($data);

    $db = get_db();

    $exists = $db->prepare('SELECT id FROM solutions WHERE id = ?');
    $exists->execute([$id]);
    if (!$exists->fetch()) {
        error_response('Solution introuvable.', 404);
    }

    $db->beginTransaction();

    $params     = build_solution_params($data);
    $params[':id'] = $id;

    $stmt = $db->prepare(
        'UPDATE solutions SET
           slug = :slug, name = :name, tagline = :tagline, category = :category,
           accent_color = :accent_color, accent_color_light = :accent_color_light,
           hero_image = :hero_image, price = :price,
           short_description = :short_desc, full_description = :full_desc,
           brochure_url = :brochure, demo_url = :demo,
           stat1_value = :s1v, stat1_label = :s1l,
           stat2_value = :s2v, stat2_label = :s2l,
           stat3_value = :s3v, stat3_label = :s3l,
           is_active = :active, sort_order = :order
         WHERE id = :id'
    );
    $stmt->execute($params);

    replace_related($db, $id, $data);

    $db->commit();

    json_response([
        'success' => true,
        'message' => 'Solution mise à jour avec succès.',
        'id'      => $id,
    ]);
}

function handle_patch(): never
{
    // PATCH /api/admin/solutions?id=3
    // Met à jour uniquement is_active (toggle rapide depuis la liste).
    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    if ($id <= 0) error_response('Paramètre "id" manquant.', 422);

    $data      = get_json_body();
    $is_active = isset($data['is_active']) ? (int)(bool)$data['is_active'] : null;
    if ($is_active === null) error_response('Champ "is_active" requis.', 422);

    $db   = get_db();
    $stmt = $db->prepare('UPDATE solutions SET is_active = ? WHERE id = ?');
    $stmt->execute([$is_active, $id]);

    if ($stmt->rowCount() === 0) error_response('Solution introuvable.', 404);

    json_response(['success' => true, 'is_active' => $is_active]);
}

function handle_delete(): never
{
    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    if ($id <= 0) {
        error_response('Paramètre "id" manquant ou invalide.', 422);
    }

    $db = get_db();

    $exists = $db->prepare('SELECT id FROM solutions WHERE id = ?');
    $exists->execute([$id]);
    if (!$exists->fetch()) {
        error_response('Solution introuvable.', 404);
    }

    // Les tables liées sont supprimées automatiquement (ON DELETE CASCADE)
    $db->prepare('DELETE FROM solutions WHERE id = ?')->execute([$id]);

    json_response([
        'success' => true,
        'message' => 'Solution supprimée avec succès.',
    ]);
}

// ============================================================
// Helpers

/** Construit le tableau de paramètres PDO pour INSERT/UPDATE */
function build_solution_params(array $data): array
{
    return [
        ':slug'              => $data['slug'],
        ':name'              => $data['name'],
        ':tagline'           => $data['tagline'],
        ':category'          => $data['category'],
        ':accent_color'      => $data['accent_color']       ?? '#16a34a',
        ':accent_color_light'=> $data['accent_color_light'] ?? '#dcfce7',
        ':hero_image'        => $data['hero_image']         ?? '',
        ':price'             => $data['price'],
        ':short_desc'        => $data['short_description'],
        ':full_desc'         => $data['full_description'],
        ':brochure'          => $data['brochure_url']       ?? null,
        ':demo'              => $data['demo_url']           ?? null,
        ':s1v'               => $data['stat1_value']        ?? null,
        ':s1l'               => $data['stat1_label']        ?? null,
        ':s2v'               => $data['stat2_value']        ?? null,
        ':s2l'               => $data['stat2_label']        ?? null,
        ':s3v'               => $data['stat3_value']        ?? null,
        ':s3l'               => $data['stat3_label']        ?? null,
        ':active'            => isset($data['is_active']) ? (int) $data['is_active'] : 1,
        ':order'             => isset($data['sort_order']) ? (int) $data['sort_order'] : 0,
    ];
}

/** Remplace les modules, avantages et tags d'une solution */
function replace_related(PDO $db, int $solution_id, array $data): void
{
    // Supprimer les anciens (CASCADE gère aussi, mais ici on le fait explicitement)
    foreach (['solution_modules','solution_advantages','solution_tags'] as $table) {
        $db->prepare("DELETE FROM $table WHERE solution_id = ?")->execute([$solution_id]);
    }

    // Insérer les nouveaux modules
    if (!empty($data['modules']) && is_array($data['modules'])) {
        $stmt = $db->prepare(
            'INSERT INTO solution_modules (solution_id, title, description, sort_order)
             VALUES (?, ?, ?, ?)'
        );
        foreach ($data['modules'] as $i => $mod) {
            $stmt->execute([
                $solution_id,
                substr(trim((string)($mod['title']       ?? '')), 0, 150),
                trim((string)($mod['description'] ?? '')),
                $i + 1,
            ]);
        }
    }

    // Insérer les nouveaux avantages
    if (!empty($data['advantages']) && is_array($data['advantages'])) {
        $stmt = $db->prepare(
            'INSERT INTO solution_advantages (solution_id, text, sort_order)
             VALUES (?, ?, ?)'
        );
        foreach ($data['advantages'] as $i => $text) {
            $stmt->execute([$solution_id, substr(trim((string) $text), 0, 255), $i + 1]);
        }
    }

    // Insérer les nouveaux tags
    if (!empty($data['tags']) && is_array($data['tags'])) {
        $stmt = $db->prepare(
            'INSERT INTO solution_tags (solution_id, tag, sort_order)
             VALUES (?, ?, ?)'
        );
        foreach ($data['tags'] as $i => $tag) {
            $stmt->execute([$solution_id, substr(trim((string) $tag), 0, 80), $i + 1]);
        }
    }
}

/** Récupère les lignes d'une table liée pour une solution */
function fetch_related(string $table, int $solution_id, array $cols): array
{
    $db   = get_db();
    $cols = implode(', ', array_map(fn($c) => "`$c`", $cols));
    $stmt = $db->prepare("SELECT $cols FROM $table WHERE solution_id = ? ORDER BY sort_order ASC");
    $stmt->execute([$solution_id]);
    return $stmt->fetchAll();
}

/** Valide les champs obligatoires d'une solution */
function validate_solution_data(array $data): void
{
    $required = ['slug','name','tagline','category','price','short_description','full_description'];
    $missing  = [];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            $missing[] = $field;
        }
    }
    if (!empty($missing)) {
        error_response('Champs obligatoires manquants : ' . implode(', ', $missing), 422);
    }
    if (!preg_match('/^[a-z0-9\-]+$/', (string)$data['slug'])) {
        error_response('Le slug ne doit contenir que des lettres minuscules, chiffres et tirets.', 422);
    }
}
