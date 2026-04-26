<?php
require __DIR__ . '/../db.php';
if (!isModerator()) { http_response_code(403); echo json_encode(['error' => 'Geen toegang']); exit; }

$q = trim($_GET['q'] ?? '');

$sql = "
    SELECT r.id, r.hygiene, r.crowd, r.location, r.facilities,
           r.comment, r.created_at, r.guest_name,
           u.username, t.name AS toilet_name
    FROM reviews r
    LEFT JOIN users u ON u.id = r.user_id
    JOIN toilets t ON t.id = r.toilet_id
    WHERE r.status = 'approved'
";
$params = [];
if ($q) {
    $sql .= " AND (t.name LIKE :q OR u.username LIKE :q OR r.guest_name LIKE :q)";
    $params[':q'] = '%' . $q . '%';
}
$sql .= " ORDER BY r.created_at DESC LIMIT 100";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
echo json_encode($stmt->fetchAll());
