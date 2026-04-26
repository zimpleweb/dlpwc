<?php
require 'db.php';

$currentId = isset($_GET['id'])    ? (int)$_GET['id']    : 0;
$lat       = isset($_GET['lat'])   ? (float)$_GET['lat'] : 0.0;
$lng       = isset($_GET['lng'])   ? (float)$_GET['lng'] : 0.0;
$limit     = isset($_GET['limit']) ? min((int)$_GET['limit'], 20) : 5;

if (!$currentId || $lat === 0.0 || $lng === 0.0) {
    http_response_code(400);
    echo json_encode(['error' => 'Ongeldige parameters', 'received' => compact('currentId','lat','lng')]);
    exit;
}

// ─── Haversine in PHP (geen SQL-berekening) ───────────────────────
function haversineMeters(float $lat1, float $lng1, float $lat2, float $lng2): float {
    $R    = 6371000.0;
    $phi1 = deg2rad($lat1);
    $phi2 = deg2rad($lat2);
    $dphi = deg2rad($lat2 - $lat1);
    $dlam = deg2rad($lng2 - $lng1);
    $a    = sin($dphi / 2) ** 2 + cos($phi1) * cos($phi2) * sin($dlam / 2) ** 2;
    return round(6371000.0 * 2.0 * atan2(sqrt($a), sqrt(1.0 - $a)), 1);
}

function formatDistance(float $m): string {
    return $m < 1000 ? round($m) . ' m' : number_format($m / 1000, 1, '.', '') . ' km';
}

// ─── Haal alle toiletten op behalve het huidige ───────────────────
// Geen LIMIT in SQL — MariaDB gooit fout bij gebonden :lim parameter
$stmt = $pdo->prepare("SELECT id, name, area, latitude, longitude FROM toilets WHERE id != :id");
$stmt->execute([':id' => $currentId]);
$toilets = $stmt->fetchAll();

if (empty($toilets)) {
    echo json_encode([]);
    exit;
}

// ─── Bereken afstand + score voor elk toilet ──────────────────────
$result = [];
foreach ($toilets as $t) {
    $dist  = haversineMeters($lat, $lng, (float)$t['latitude'], (float)$t['longitude']);
    $score = calcToiletScore($pdo, (int)$t['id']);

    $result[] = [
        'id'             => (int)$t['id'],
        'name'           => $t['name'],
        'area'           => $t['area'],
        'latitude'       => $t['latitude'],
        'longitude'      => $t['longitude'],
        'distance_m'     => $dist,
        'distance_label' => formatDistance($dist),
        'score'          => $score['score'],
        'color'          => $score['color'],
        'review_count'   => $score['review_count'],
    ];
}

// ─── Sorteer op afstand, dichtstbij eerst ─────────────────────────
usort($result, fn($a, $b) => $a['distance_m'] <=> $b['distance_m']);

// ─── Limiteer in PHP, niet in SQL ────────────────────────────────
echo json_encode(array_slice($result, 0, $limit));
