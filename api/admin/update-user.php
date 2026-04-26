<?php
require '../db.php';

if (!isAdmin()) { http_response_code(403); echo json_encode(['error' => 'Geen toegang']); exit; }
verifyCsrf();

$data  = json_decode(file_get_contents('php://input'), true);
$id    = (int)($data['id'] ?? 0);
$role  = $data['role']       ?? null;
$block = isset($data['is_blocked']) ? (int)$data['is_blocked'] : null;

if (!$id) { http_response_code(400); echo json_encode(['error' => 'Geen ID']); exit; }

// Vaste kolommen met conditionele parameters (geen dynamische SQL-concatenatie)
$validRole = ($role && in_array($role, ['user', 'admin', 'moderator'], true)) ? $role : null;
$validBlock = $block !== null ? (int)(bool)$block : null;

if ($validRole === null && $validBlock === null) {
    http_response_code(400); echo json_encode(['error' => 'Niets te updaten']); exit;
}

if ($validRole !== null && $validBlock !== null) {
    $pdo->prepare("UPDATE users SET role = :role, is_blocked = :block WHERE id = :id")
        ->execute([':role' => $validRole, ':block' => $validBlock, ':id' => $id]);
} elseif ($validRole !== null) {
    $pdo->prepare("UPDATE users SET role = :role WHERE id = :id")
        ->execute([':role' => $validRole, ':id' => $id]);
} else {
    $pdo->prepare("UPDATE users SET is_blocked = :block WHERE id = :id")
        ->execute([':block' => $validBlock, ':id' => $id]);
}

echo json_encode(['success' => true]);
