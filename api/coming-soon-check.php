<?php
require_once __DIR__ . '/load-env.php';

header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');

// CORS: alleen eigen domein
$appDomain = rtrim(getenv('APP_DOMAIN') ?: '', '/');
$allowed   = array_filter([$appDomain, 'http://localhost:4321', 'http://localhost', 'http://127.0.0.1']);
$origin    = $_SERVER['HTTP_ORIGIN'] ?? '';
if (in_array($origin, $allowed, true)) {
    header('Access-Control-Allow-Origin: ' . $origin);
}
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false]);
    exit;
}

$raw     = file_get_contents('php://input');
$data    = json_decode($raw, true);
$entered = trim($data['password'] ?? '');

if ($entered === '') {
    echo json_encode(['success' => false]);
    exit;
}

// Wachtwoord uit DB ophalen (bcrypt hash opgeslagen in site_settings)
$host = getenv('DB_HOST') ?: 'localhost';
$db   = getenv('DB_NAME') ?: '';
$user = getenv('DB_USER') ?: '';
$pass = getenv('DB_PASS') ?: '';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8mb4",
        $user, $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );
    $stmt = $pdo->prepare("SELECT setting_value FROM site_settings WHERE setting_key = 'coming_soon_password' LIMIT 1");
    $stmt->execute();
    $row = $stmt->fetch();
} catch (Exception $e) {
    http_response_code(503);
    echo json_encode(['success' => false, 'error' => 'Service tijdelijk niet beschikbaar']);
    exit;
}

if (!$row || !$row['setting_value']) {
    echo json_encode(['success' => false]);
    exit;
}

// Vergelijk met bcrypt hash (sla de hash op via password_hash() in de DB)
echo json_encode(['success' => password_verify($entered, $row['setting_value'])]);
