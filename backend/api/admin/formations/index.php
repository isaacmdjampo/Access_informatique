<?php
/**
 * GET    /api/admin/formations        — liste toutes les formations
 * GET    /api/admin/formations?id=2   — détail d'une formation
 * POST   /api/admin/formations        — créer une formation
 * PUT    /api/admin/formations?id=2   — modifier une formation
 * DELETE /api/admin/formations?id=2   — supprimer une formation
 * ---------------------------------------------------------------
 * Endpoint protégé : JWT requis.
 * Skills, modules, bénéfices et résultats sont remplacés en totalité
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
    error_log('[API/admin/formations] PDO : ' . $e->getMessage());
    error_response('Erreur interne du serveur.', 500);
}

// ============================================================

function handle_get(): never
{
    $db = get_db();
    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

    if ($id > 0) {
        $stmt = $db->prepare('SELECT * FROM formations WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $f = $stmt->fetch();
        if (!$f) {
            error_response('Formation introuvable.', 404);
        }
        $f['skills']   = fetch_col('formation_skills',   $id, 'skill');
        $f['modules']  = fetch_rel('formation_modules',  $id, ['title','description','duration','sort_order']);
        $f['benefits'] = fetch_col('formation_benefits', $id, 'text');
        $f['outcomes'] = fetch_col('formation_outcomes', $id, 'text');
        json_response($f);
    } else {
        $rows = $db->query(
            'SELECT id, slug, title, category, duration, level, price,
                    is_active, sort_order, created_at, updated_at
               FROM formations
              ORDER BY sort_order ASC, id ASC'
        )->fetchAll();
        json_response($rows);
    }
}

function handle_post(): never
{
    $data = get_json_body();
    validate_formation($data);

    $db = get_db();
    $db->beginTransaction();

    $stmt = $db->prepare(
        'INSERT INTO formations
           (slug, title, category, duration, level, price, description, image_url, is_active, sort_order)
         VALUES
           (:slug, :title, :category, :duration, :level, :price, :desc, :img, :active, :order)'
    );
    $stmt->execute(build_params($data));
    $id = (int) $db->lastInsertId();

    replace_related($db, $id, $data);
    $db->commit();

    json_response(['success' => true, 'message' => 'Formation créée.', 'id' => $id], 201);
}

function handle_put(): never
{
    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    if ($id <= 0) {
        error_response('Paramètre "id" manquant ou invalide.', 422);
    }

    $data = get_json_body();
    validate_formation($data);

    $db     = get_db();
    $exists = $db->prepare('SELECT id FROM formations WHERE id = ?');
    $exists->execute([$id]);
    if (!$exists->fetch()) {
        error_response('Formation introuvable.', 404);
    }

    $db->beginTransaction();

    $params      = build_params($data);
    $params[':id'] = $id;

    $stmt = $db->prepare(
        'UPDATE formations SET
           slug = :slug, title = :title, category = :category,
           duration = :duration, level = :level, price = :price,
           description = :desc, image_url = :img,
           is_active = :active, sort_order = :order
         WHERE id = :id'
    );
    $stmt->execute($params);

    replace_related($db, $id, $data);
    $db->commit();

    json_response(['success' => true, 'message' => 'Formation mise à jour.', 'id' => $id]);
}

function handle_patch(): never
{
    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    if ($id <= 0) error_response('Paramètre "id" manquant.', 422);

    $data      = get_json_body();
    $is_active = isset($data['is_active']) ? (int)(bool)$data['is_active'] : null;
    if ($is_active === null) error_response('Champ "is_active" requis.', 422);

    $db   = get_db();
    $stmt = $db->prepare('UPDATE formations SET is_active = ? WHERE id = ?');
    $stmt->execute([$is_active, $id]);

    if ($stmt->rowCount() === 0) error_response('Formation introuvable.', 404);

    json_response(['success' => true, 'is_active' => $is_active]);
}

function handle_delete(): never
{
    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    if ($id <= 0) {
        error_response('Paramètre "id" manquant ou invalide.', 422);
    }

    $db = get_db();
    $exists = $db->prepare('SELECT id FROM formations WHERE id = ?');
    $exists->execute([$id]);
    if (!$exists->fetch()) {
        error_response('Formation introuvable.', 404);
    }

    $db->prepare('DELETE FROM formations WHERE id = ?')->execute([$id]);

    json_response(['success' => true, 'message' => 'Formation supprimée.']);
}

// ============================================================
// Helpers

function build_params(array $d): array
{
    return [
        ':slug'     => $d['slug'],
        ':title'    => $d['title'],
        ':category' => $d['category'],
        ':duration' => $d['duration'] ?? '',
        ':level'    => $d['level']    ?? '',
        ':price'    => $d['price'],
        ':desc'     => $d['description'],
        ':img'      => $d['image_url'] ?? '',
        ':active'   => isset($d['is_active'])  ? (int) $d['is_active']  : 1,
        ':order'    => isset($d['sort_order']) ? (int) $d['sort_order'] : 0,
    ];
}

function replace_related(PDO $db, int $fid, array $d): void
{
    foreach (['formation_skills','formation_modules','formation_benefits','formation_outcomes'] as $t) {
        $db->prepare("DELETE FROM $t WHERE formation_id = ?")->execute([$fid]);
    }

    if (!empty($d['skills']) && is_array($d['skills'])) {
        $s = $db->prepare('INSERT INTO formation_skills (formation_id, skill, sort_order) VALUES (?,?,?)');
        foreach ($d['skills'] as $i => $sk) {
            $s->execute([$fid, substr(trim((string)$sk),0,80), $i+1]);
        }
    }

    if (!empty($d['modules']) && is_array($d['modules'])) {
        $s = $db->prepare('INSERT INTO formation_modules (formation_id, title, description, duration, sort_order) VALUES (?,?,?,?,?)');
        foreach ($d['modules'] as $i => $m) {
            $s->execute([
                $fid,
                substr(trim((string)($m['title']       ?? '')),0,150),
                trim((string)($m['description'] ?? '')),
                substr(trim((string)($m['duration']    ?? '')),0,50),
                $i+1,
            ]);
        }
    }

    if (!empty($d['benefits']) && is_array($d['benefits'])) {
        $s = $db->prepare('INSERT INTO formation_benefits (formation_id, text, sort_order) VALUES (?,?,?)');
        foreach ($d['benefits'] as $i => $t) {
            $s->execute([$fid, substr(trim((string)$t),0,255), $i+1]);
        }
    }

    if (!empty($d['outcomes']) && is_array($d['outcomes'])) {
        $s = $db->prepare('INSERT INTO formation_outcomes (formation_id, text, sort_order) VALUES (?,?,?)');
        foreach ($d['outcomes'] as $i => $t) {
            $s->execute([$fid, substr(trim((string)$t),0,255), $i+1]);
        }
    }
}

function fetch_col(string $table, int $fid, string $col): array
{
    $db   = get_db();
    $stmt = $db->prepare("SELECT `$col` FROM $table WHERE formation_id = ? ORDER BY sort_order ASC");
    $stmt->execute([$fid]);
    return array_column($stmt->fetchAll(), $col);
}

function fetch_rel(string $table, int $fid, array $cols): array
{
    $db      = get_db();
    $col_str = implode(', ', array_map(fn($c) => "`$c`", $cols));
    $stmt    = $db->prepare("SELECT $col_str FROM $table WHERE formation_id = ? ORDER BY sort_order ASC");
    $stmt->execute([$fid]);
    return $stmt->fetchAll();
}

function validate_formation(array $d): void
{
    $required = ['slug','title','category','price','description'];
    $missing  = array_filter($required, fn($f) => empty($d[$f]));
    if (!empty($missing)) {
        error_response('Champs obligatoires manquants : ' . implode(', ', $missing), 422);
    }
    if (!preg_match('/^[a-z0-9\-]+$/', (string)$d['slug'])) {
        error_response('Slug invalide : lettres minuscules, chiffres et tirets uniquement.', 422);
    }
}
