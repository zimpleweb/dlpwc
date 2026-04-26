<?php
require __DIR__ . '/../db.php';
if (!isModerator()) { http_response_code(403); echo json_encode(['error' => 'Geen toegang']); exit; }

$data     = json_decode(file_get_contents('php://input'), true);
$type     = $data['type']     ?? '';
$filename = basename($data['filename'] ?? '');  // basename voorkomt path traversal

if (!$filename) { http_response_code(400); echo json_encode(['error' => 'Geen bestandsnaam']); exit; }

if ($type === 'editorial') {
    $toiletId = (int)($data['toilet_id'] ?? 0);
    if (!$toiletId) { http_response_code(400); echo json_encode(['error' => 'Ongeldig toilet_id']); exit; }

    $path = __DIR__ . '/../../uploads/editorial/' . $filename;
    if (file_exists($path)) unlink($path);

    $pdo->prepare("UPDATE toilets SET editorial_photo = NULL WHERE id = :id AND editorial_photo = :f")
        ->execute([':id' => $toiletId, ':f' => $filename]);

} elseif ($type === 'review') {
    $reviewId = (int)($data['review_id'] ?? 0);
    if (!$reviewId) { http_response_code(400); echo json_encode(['error' => 'Ongeldig review_id']); exit; }

    // Haal huidige images_json op
    $stmt = $pdo->prepare("SELECT images_json FROM reviews WHERE id = :id");
    $stmt->execute([':id' => $reviewId]);
    $row = $stmt->fetch();
    if (!$row) { http_response_code(404); echo json_encode(['error' => 'Review niet gevonden']); exit; }

    $images = json_decode($row['images_json'], true) ?? [];
    $images = array_values(array_filter($images, fn($f) => $f !== $filename));

    // Verwijder fysiek bestand
    $path = __DIR__ . '/../../uploads/reviews/' . $filename;
    if (file_exists($path)) unlink($path);

    // Update DB — zet op NULL als geen foto's meer over
    $pdo->prepare("UPDATE reviews SET images_json = :j WHERE id = :id")
        ->execute([
            ':j'  => count($images) ? json_encode($images) : null,
            ':id' => $reviewId,
        ]);

} else {
    http_response_code(400); echo json_encode(['error' => 'Onbekend type']); exit;
}

echo json_encode(['success' => true]);
