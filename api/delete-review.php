<?php
require 'db.php';
if (!isLoggedIn()) { http_response_code(401); echo json_encode(['error' => 'Niet ingelogd']); exit; }
verifyCsrf();

$data = json_decode(file_get_contents('php://input'), true);
$id   = (int)($data['id'] ?? 0);

// Check eigenaar
$stmt = $pdo->prepare("SELECT user_id FROM reviews WHERE id = :id");
$stmt->execute([':id' => $id]);
$rev = $stmt->fetch();

if (!$rev || $rev['user_id'] != $_SESSION['user_id']) {
    http_response_code(403); echo json_encode(['error' => 'Geen toegang']); exit;
}

$pdo->prepare("DELETE FROM reviews WHERE id = :id")->execute([':id' => $id]);
echo json_encode(['success' => true]);
