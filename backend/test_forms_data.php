<?php
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'access_informatique');
define('DB_USER', 'root');
define('DB_PASS', '');

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    $stmt = $pdo->query('SELECT COUNT(*) as count FROM contact_submissions');
    $contact_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    $stmt = $pdo->query('SELECT COUNT(*) as count FROM inscription_submissions');
    $inscription_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    echo "contact_submissions: $contact_count rows\n";
    echo "inscription_submissions: $inscription_count rows\n";
    
    echo "\n=== Latest Contact Message ===\n";
    $stmt = $pdo->query('SELECT name, email, subject, created_at FROM contact_submissions ORDER BY id DESC LIMIT 1');
    $msg = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($msg) {
        echo "Name: {$msg['name']}\n";
        echo "Email: {$msg['email']}\n";
        echo "Subject: {$msg['subject']}\n";
        echo "Created: {$msg['created_at']}\n";
    }
    
    echo "\n=== Latest Inscription ===\n";
    $stmt = $pdo->query('SELECT prenom, nom, email, formation_requested, created_at FROM inscription_submissions ORDER BY id DESC LIMIT 1');
    $ins = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($ins) {
        echo "Name: {$ins['prenom']} {$ins['nom']}\n";
        echo "Email: {$ins['email']}\n";
        echo "Formation: {$ins['formation_requested']}\n";
        echo "Created: {$ins['created_at']}\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
