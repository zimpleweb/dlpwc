<?php
require 'db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) { http_response_code(400); echo json_encode(['error' => 'Geen ID']); exit; }

$stmt = $pdo->prepare("SELECT * FROM toilets WHERE id = :id");
$stmt->execute([':id' => $id]);
$toilet = $stmt->fetch();

if (!$toilet) { http_response_code(404); echo json_encode(['error' => 'Niet gevonden']); exit; }

$scoreData = calcToiletScore($pdo, $id);

$sub = $pdo->prepare(
    "SELECT
       AVG(hygiene) AS avg_hygiene, AVG(crowd) AS avg_crowd,
       AVG(location) AS avg_location, AVG(facilities) AS avg_facilities
     FROM reviews WHERE toilet_id = :tid AND status = 'approved'"
);
$sub->execute([':tid' => $id]);
$subscores = $sub->fetch();

$adminReview = $pdo->prepare(
    "SELECT r.*, u.username FROM reviews r
     LEFT JOIN users u ON r.user_id = u.id
     WHERE r.toilet_id = :tid AND r.is_admin_review = 1 AND r.status = 'approved'
     ORDER BY r.created_at DESC LIMIT 1"
);
$adminReview->execute([':tid' => $id]);
$featured = $adminReview->fetch();

$scores = [
    'hygiene'    => (float)($subscores['avg_hygiene']    ?? 0),
    'crowd'      => (float)($subscores['avg_crowd']      ?? 0),
    'location'   => (float)($subscores['avg_location']   ?? 0),
    'facilities' => (float)($subscores['avg_facilities'] ?? 0),
];
arsort($scores);
$topCriterium = array_key_first($scores);

$revStmt = $pdo->prepare(
    "SELECT r.id, r.hygiene, r.crowd, r.location, r.facilities,
            r.comment, r.guest_name, r.created_at, r.images_json,
            r.review_lang,
            u.username, r.is_admin_review
     FROM reviews r
     LEFT JOIN users u ON r.user_id = u.id
     WHERE r.toilet_id = :tid AND r.status = 'approved'
     ORDER BY r.is_admin_review DESC, r.created_at DESC
     LIMIT 20"
);
$revStmt->execute([':tid' => $id]);
$reviews = $revStmt->fetchAll();

echo json_encode([
    'toilet'        => array_merge($toilet, $scoreData),
    'subscores'     => $subscores,
    'top_criterium' => $topCriterium,
    'featured'      => $featured ?: null,
    'reviews'       => $reviews,
]);
