<?php
require '../db.php';

if (!isAdmin()) { http_response_code(403); echo json_encode(['error' => 'Geen toegang']); exit; }

$data = json_decode(file_get_contents('php://input'), true);
$name = trim($data['name'] ?? '');
$area = $data['area'] ?? '';
$lat  = (float)($data['latitude']  ?? 0);
$lng  = (float)($data['longitude'] ?? 0);
$desc = trim($data['description'] ?? '');

$validAreas = ['PARK','STUDIOS','VILLAGE','HOTEL','PARKING'];
if (!$name || !in_array($area, $validAreas) || !$lat || !$lng) {
    http_response_code(400); echo json_encode(['error' => 'Vul alle verplichte velden in']); exit;
}

$stmt = $pdo->prepare(
    "INSERT INTO toilets (name, area, latitude, longitude, description) VALUES (:n,:a,:la,:lo,:d)"
);
$stmt->execute([':n' => $name, ':a' => $area, ':la' => $lat, ':lo' => $lng, ':d' => $desc ?: null]);

echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
