<?php
header('Content-Type: application/json');

require '../db.php';

if (empty($_SESSION['user_id']) || empty($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Geen toegang']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$id   = (int)($data['id']         ?? 0);
$lang = trim($data['review_lang'] ?? 'en');

if (!$id || !in_array($lang, ['en', 'nl', 'fr'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Ongeldige invoer']);
    exit;
}

$stmt = $pdo->prepare("UPDATE reviews SET review_lang = :lang WHERE id = :id");
$stmt->execute([':lang' => $lang, ':id' => $id]);

echo json_encode(['success' => true]);
