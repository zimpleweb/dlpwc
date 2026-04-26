<?php
require __DIR__ . '/../db.php';
if (!isAdmin()) { http_response_code(403); echo json_encode(['error' => 'Geen toegang']); exit; }
verifyCsrf();

$data = json_decode(file_get_contents('php://input'), true);
$id   = (int)($data['id'] ?? 0);
if (!$id) { http_response_code(400); echo json_encode(['error' => 'Ongeldig ID']); exit; }

$pdo->prepare("DELETE FROM reviews WHERE id = :id")->execute([':id' => $id]);
echo json_encode(['success' => true]);
