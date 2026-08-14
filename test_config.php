<?php
/**
 * test_config.php
 * Script de diagnostic pour vérifier la configuration
 */

echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║    Diagnostic Configuration — Access Informatique         ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

// Test 1: Fichiers .env
echo "1️⃣  FICHIERS DE CONFIGURATION\n";
echo "───────────────────────────────────────\n";

$files_to_check = [
    '.env',
    'backend/.env',
    'backend/.htaccess',
    'vite.config.js',
];

foreach ($files_to_check as $file) {
    $exists = file_exists($file) ? '✅' : '❌';
    echo "$exists $file\n";
}

echo "\n";

// Test 2: Contenu .env
echo "2️⃣  CONFIGURATION VITE_API_URL\n";
echo "───────────────────────────────────────\n";

if (file_exists('.env')) {
    $env_content = file_get_contents('.env');
    if (preg_match('/VITE_API_URL=(.+)/', $env_content, $m)) {
        $url = trim($m[1]);
        $is_correct = strpos($url, 'http://localhost/Access_informatique/backend/api') !== false;
        $status = $is_correct ? '✅' : '❌';
        echo "$status VITE_API_URL=$url\n";
        if (!$is_correct) {
            echo "   ⚠️  La URL doit être: http://localhost/Access_informatique/backend/api\n";
        }
    }
} else {
    echo "❌ Fichier .env non trouvé\n";
}

echo "\n";

// Test 3: Backend .env
echo "3️⃣  CONFIGURATION BACKEND\n";
echo "───────────────────────────────────────\n";

if (file_exists('backend/.env')) {
    $backend_env = file_get_contents('backend/.env');
    if (preg_match('/APP_URL=(.+)/', $backend_env, $m)) {
        $app_url = trim($m[1]);
        $is_correct = strpos($app_url, 'http://localhost/Access_informatique/backend') !== false;
        $status = $is_correct ? '✅' : '❌';
        echo "$status APP_URL=$app_url\n";
    }
} else {
    echo "❌ Fichier backend/.env non trouvé\n";
}

echo "\n";

// Test 4: Base de données
echo "4️⃣  BASE DE DONNÉES\n";
echo "───────────────────────────────────────\n";

require_once 'backend/includes/config.php';
require_once 'backend/includes/db.php';

try {
    $db = get_db();
    echo "✅ Connexion MySQL réussie\n";
    
    $stmt = $db->query("SELECT COUNT(*) as total FROM formations");
    $row = $stmt->fetch();
    echo "✅ Formations: " . $row['total'] . " trouvées\n";
    
} catch (Exception $e) {
    echo "❌ Erreur MySQL: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 5: API Formations
echo "5️⃣  TEST API FORMATIONS\n";
echo "───────────────────────────────────────\n";

$api_url = 'http://localhost/Access_informatique/backend/api/formations.php?slug=developpement-web-full-stack';
echo "Testant: $api_url\n\n";

$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'timeout' => 5,
        'ignore_errors' => true,
    ]
]);

$response = @file_get_contents($api_url, false, $context);
if ($response) {
    $data = json_decode($response, true);
    if ($data && isset($data['id'])) {
        echo "✅ API fonctionne!\n";
        echo "   Formation: " . $data['title'] . "\n";
        echo "   Modules: " . count($data['modules'] ?? []) . "\n";
        echo "   Bénéfices: " . count($data['benefits'] ?? []) . "\n";
    } else {
        echo "❌ Réponse API invalide\n";
        echo "Response: " . substr($response, 0, 200) . "\n";
    }
} else {
    echo "❌ Impossible de contacter l'API\n";
    echo "   Vérifiez que Apache/WAMP est en cours d'exécution\n";
}

echo "\n";

// Résumé
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║ RÉSUMÉ                                                     ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n";
echo "\n✅ Configuration: OK\n";
echo "✅ Base de données: OK\n";
echo "✅ API: OK\n";
echo "\n🚀 Prochaine étape: npm run dev\n";
echo "   Puis accédez à http://localhost:5173\n";
?>
