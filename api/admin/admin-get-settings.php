<?php
require '../db.php';
if (!isAdmin()) { http_response_code(403); echo json_encode(["error" => "Geen toegang"]); exit; }

$colors = $pdo->query(
    "SELECT setting_key, setting_value FROM site_settings
     WHERE setting_key IN ('color_primary','color_accent','color_green','color_red')"
)->fetchAll(PDO::FETCH_ASSOC);

$historyRow = $pdo->query(
    "SELECT setting_value FROM site_settings WHERE setting_key = 'color_history'"
)->fetch();
$history = $historyRow ? json_decode($historyRow['setting_value'], true) : null;

// Vertalingen ophalen (één rij per taal)
$transRows = $pdo->query(
    "SELECT setting_key, setting_value FROM site_settings
     WHERE setting_key IN ('translations_en','translations_nl','translations_fr')"
)->fetchAll(PDO::FETCH_ASSOC);

$translations = [];
foreach ($transRows as $row) {
    $lang = str_replace('translations_', '', $row['setting_key']);
    $translations[$lang] = json_decode($row['setting_value'], true) ?? [];
}

$rcRow = $pdo->query(
    "SELECT setting_key, setting_value FROM site_settings
     WHERE setting_key IN ('recaptcha_site_key','recaptcha_secret_key')"
)->fetchAll(PDO::FETCH_ASSOC);
$recaptcha = [];
foreach ($rcRow as $r) { $recaptcha[$r['setting_key']] = $r['setting_value']; }

echo json_encode([
    'colors'       => $colors,
    'color_history'=> $history,
    'translations' => $translations,
    'recaptcha'    => $recaptcha,
]);
