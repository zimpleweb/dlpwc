<?php
header('Content-Type: application/json');
require 'db.php';

$row = $pdo->query(
    "SELECT setting_value FROM site_settings WHERE setting_key = 'recaptcha_site_key' LIMIT 1"
)->fetch();

echo json_encode([
    'recaptcha_site_key' => ($row && $row['setting_value']) ? $row['setting_value'] : '',
]);
