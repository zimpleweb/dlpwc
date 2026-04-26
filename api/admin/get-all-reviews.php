<?php
require '../db.php';

if (!isModerator()) {
    http_response_code(403);
    echo json_encode(['error' => 'Geen toegang']);
    exit;
}

try {
    $stmt = $pdo->query("
        SELECT r.id, r.toilet_id, t.name AS toilet_name,
               r.user_id, u.username, r.guest_name,
               r.hygiene, r.crowd, r.location, r.facilities,
               r.comment, r.status, r.review_lang,
               r.created_at, r.is_admin_review
        FROM reviews r
        LEFT JOIN toilets t ON t.id = r.toilet_id
        LEFT JOIN users   u ON u.id = r.user_id
        WHERE r.status = 'approved'
        ORDER BY r.created_at DESC
    ");

    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
