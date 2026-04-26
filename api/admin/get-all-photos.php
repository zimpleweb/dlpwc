<?php
require __DIR__ . '/../db.php';
if (!isModerator()) { http_response_code(403); echo json_encode(['error' => 'Geen toegang']); exit; }

$result = [];

// ── Redactiefoto's ────────────────────────────────────────────────
$stmt = $pdo->query("SELECT id, name, area, editorial_photo FROM toilets WHERE editorial_photo IS NOT NULL ORDER BY name");
foreach ($stmt->fetchAll() as $t) {
    $result[] = [
        'type'       => 'editorial',
        'toilet_id'  => $t['id'],
        'toilet_name'=> $t['name'],
        'area'       => $t['area'],
        'filename'   => $t['editorial_photo'],
        'url'        => '/uploads/editorial/' . $t['editorial_photo'],
        'review_id'  => null,
        'uploader'   => 'Redactie',
    ];
}

// ── Review foto's ─────────────────────────────────────────────────
$stmt = $pdo->query(
    "SELECT r.id AS review_id, r.images_json, r.created_at, r.status,
            t.id AS toilet_id, t.name AS toilet_name, t.area,
            u.username, r.guest_name
     FROM reviews r
     JOIN toilets t ON t.id = r.toilet_id
     LEFT JOIN users u ON u.id = r.user_id
     WHERE r.images_json IS NOT NULL
     ORDER BY r.created_at DESC"
);
foreach ($stmt->fetchAll() as $r) {
    $images = json_decode($r['images_json'], true) ?? [];
    foreach ($images as $filename) {
        $result[] = [
            'type'        => 'review',
            'toilet_id'   => $r['toilet_id'],
            'toilet_name' => $r['toilet_name'],
            'area'        => $r['area'],
            'filename'    => $filename,
            'url'         => '/uploads/reviews/' . $filename,
            'review_id'   => $r['review_id'],
            'review_status'=> $r['status'],
            'uploader'    => $r['username'] ?? $r['guest_name'] ?? 'Gast',
            'created_at'  => $r['created_at'],
        ];
    }
}

echo json_encode(['photos' => $result]);
