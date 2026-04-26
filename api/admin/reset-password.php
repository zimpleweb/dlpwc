<?php
require '../db.php';
if (!isAdmin()) { http_response_code(403); echo json_encode(['error' => 'Geen toegang']); exit; }

$data   = json_decode(file_get_contents('php://input'), true);
$userId = (int)($data['user_id'] ?? 0);
$newPw  = trim($data['password'] ?? '');

if (!$userId) { http_response_code(400); echo json_encode(['error' => 'Ongeldig user_id']); exit; }
if (strlen($newPw) < 8) { http_response_code(400); echo json_encode(['error' => 'Wachtwoord minimaal 8 tekens']); exit; }

$hash = password_hash($newPw, PASSWORD_BCRYPT, ['cost' => 12]);
$pdo->prepare("UPDATE users SET password_hash = :h WHERE id = :id")
    ->execute([':h' => $hash, ':id' => $userId]);

echo json_encode(['success' => true]);
