<?php
require 'db.php';
if (!isLoggedIn()) { http_response_code(401); echo json_encode(['error' => 'Niet ingelogd']); exit; }

$uid  = (int)$_SESSION['user_id'];
$user = $pdo->prepare("SELECT id, name, username, email, avatar_url, role, created_at FROM users WHERE id = :id");
$user->execute([':id' => $uid]);
$u    = $user->fetch();

$reviews = $pdo->prepare(
    "SELECT r.*, t.name AS toilet_name FROM reviews r
     JOIN toilets t ON r.toilet_id = t.id
     WHERE r.user_id = :uid ORDER BY r.created_at DESC"
);
$reviews->execute([':uid' => $uid]);

echo json_encode(['user' => $u, 'reviews' => $reviews->fetchAll()]);
