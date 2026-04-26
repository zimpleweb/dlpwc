<?php
require 'db.php';
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: public, max-age=60');

$lang = preg_replace('/[^a-z]/', '', $_GET['lang'] ?? 'en');
if (!in_array($lang, ['en','nl','fr'])) $lang = 'en';

$row = $pdo->prepare(
    "SELECT setting_value FROM site_settings WHERE setting_key = ?"
);
$row->execute(["translations_{$lang}"]);
$result = $row->fetchColumn();

if ($result) {
    // Decodeer en re-encodeer om te valideren, stuur als genest object
    $decoded = json_decode($result, true);
    echo json_encode($decoded ?? (object)[]);
} else {
    echo json_encode((object)[]);
}
