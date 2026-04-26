<?php
require '../db.php';

if (!isModerator()) {
    http_response_code(403);
    echo json_encode(['error' => 'Geen toegang']);
    exit;
}

$stmt = $pdo->query(
    "SELECT r.*, t.name AS toilet_name, u.username
     FROM reviews r
     JOIN toilets t ON r.toilet_id = t.id
     LEFT JOIN users u ON r.user_id = u.id
     WHERE r.status = 'pending'
     ORDER BY r.created_at ASC"
);
echo json_encode($stmt->fetchAll());
