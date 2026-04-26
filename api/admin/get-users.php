<?php
require '../db.php';

if (!isAdmin()) { http_response_code(403); echo json_encode(['error' => 'Geen toegang']); exit; }

$stmt = $pdo->query(
    "SELECT id, name, username, email, role, avatar_url, is_blocked, approved_review_count, created_at FROM users ORDER BY id"
);
echo json_encode(['users' => $stmt->fetchAll()]);
