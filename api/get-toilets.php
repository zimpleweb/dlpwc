<?php
require 'db.php';

$area     = $_GET['area']     ?? null;
$minScore = isset($_GET['minScore']) ? (float)$_GET['minScore'] : null;

$sql    = "SELECT * FROM toilets";
$params = [];

if ($area) {
    $sql .= " WHERE area = :area";
    $params[':area'] = $area;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$toilets = $stmt->fetchAll();

$result = [];
foreach ($toilets as $t) {
    $scoreData = calcToiletScore($pdo, $t['id']);
    if ($minScore !== null && ($scoreData['score'] === null || $scoreData['score'] < $minScore)) {
        continue;
    }
    $result[] = array_merge($t, $scoreData);
}

echo json_encode($result);
