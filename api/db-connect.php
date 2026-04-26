<?php
require_once __DIR__ . '/load-env.php';

$host = getenv('DB_CONNECT_HOST') ?: '127.0.0.1';
$db   = getenv('DB_CONNECT_NAME') ?: '';
$user = getenv('DB_CONNECT_USER') ?: '';
$pass = getenv('DB_CONNECT_PASS') ?: '';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8mb4",
        $user, $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database verbinding mislukt']);
    exit;
}
