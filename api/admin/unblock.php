<?php
require '../db.php';
if (!isModerator()) { http_response_code(403); echo json_encode(['error' => 'Geen toegang']); exit; }

$data = json_decode(file_get_contents('php://input'), true);
$type = $data['type'] ?? '';

if ($type === 'user') {
    $userId = (int)($data['user_id'] ?? 0);
    if (!$userId) { http_response_code(400); echo json_encode(['error' => 'Ongeldig user_id']); exit; }
    $pdo->prepare("UPDATE users SET is_blocked = 0, blocked_until = NULL WHERE id = :id")
        ->execute([':id' => $userId]);
    // Zet geblokkeerde reviews van deze user terug op rejected
    $pdo->prepare("UPDATE reviews SET status = 'rejected' WHERE user_id = :uid AND status = 'blocked'")
        ->execute([':uid' => $userId]);

} elseif ($type === 'guest') {
    $blockId = (int)($data['block_id'] ?? 0);
    if (!$blockId) { http_response_code(400); echo json_encode(['error' => 'Ongeldig block_id']); exit; }
    $pdo->prepare("DELETE FROM guest_blocks WHERE id = :id")->execute([':id' => $blockId]);

} else {
    http_response_code(400); echo json_encode(['error' => 'Onbekend type']); exit;
}

echo json_encode(['success' => true]);
