<?php
require '../db.php';
if (!isAdmin()) { http_response_code(403); echo json_encode(['error' => 'Geen toegang']); exit; }

// Geblokkeerde gebruikers
$users = $pdo->query(
    "SELECT u.id, u.username, u.email, u.name, u.blocked_until,
            r.id AS review_id, r.comment, r.created_at, t.name AS toilet_name
     FROM users u
     LEFT JOIN reviews r ON r.user_id = u.id AND r.status = 'blocked'
     LEFT JOIN toilets t ON t.id = r.toilet_id
     WHERE u.is_blocked = 1
     ORDER BY u.id DESC"
)->fetchAll();

// Geblokkeerde gasten
$guests = $pdo->query(
    "SELECT gb.id, gb.ip, gb.fingerprint, gb.blocked_until,
            r.id AS review_id, r.comment, r.guest_name, r.created_at,
            t.name AS toilet_name
     FROM guest_blocks gb
     JOIN reviews r ON r.id = gb.review_id
     JOIN toilets t ON t.id = gb.toilet_id
     WHERE gb.blocked_until IS NULL OR gb.blocked_until > NOW()
     ORDER BY gb.id DESC"
)->fetchAll();

$blocked = [];

foreach ($users as $u) {
    $blocked[] = [
        'id'           => $u['id'],
        'type'         => 'user',
        'name'         => $u['name'] ?? $u['username'] ?? 'Onbekend',
        'ip'           => null,
        'blocked_until'=> $u['blocked_until'],
        'review_id'    => $u['review_id'],
        'comment'      => $u['comment'],
        'toilet_name'  => $u['toilet_name'],
    ];
}

foreach ($guests as $g) {
    $blocked[] = [
        'id'           => $g['id'],
        'type'         => 'guest',
        'name'         => $g['guest_name'] ?? null,
        'ip'           => $g['ip'],
        'blocked_until'=> $g['blocked_until'],
        'review_id'    => $g['review_id'],
        'comment'      => $g['comment'],
        'toilet_name'  => $g['toilet_name'],
    ];
}

echo json_encode(['blocked' => $blocked]);
