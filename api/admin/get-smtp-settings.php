<?php
require_once '../db.php';

define('DLPWC_CONFIG', 1);
require_once '../config.php';

header('Content-Type: application/json');

if (!isAdmin()) {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit;
}

$keys = [
    'brevo_api_key',
    'smtp_from_name',
    'smtp_from_email',
    'admin_notification_email',
];

$placeholders = implode(',', array_fill(0, count($keys), '?'));

$stmt = $pdo->prepare(
    "SELECT setting_key, setting_value FROM site_settings WHERE setting_key IN ($placeholders)"
);
$stmt->execute($keys);
$rows = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

$apiKey    = isset($rows['brevo_api_key']) ? $rows['brevo_api_key'] : '';
$hasApiKey = $apiKey !== '';

echo json_encode([
    'brevo_api_key'            => $hasApiKey ? '••••••••' : '',
    'has_api_key'              => $hasApiKey,
    'smtp_from_name'           => isset($rows['smtp_from_name'])           ? $rows['smtp_from_name']           : 'DLPWC',
    'smtp_from_email'          => isset($rows['smtp_from_email'])          ? $rows['smtp_from_email']          : '',
    'admin_notification_email' => isset($rows['admin_notification_email']) ? $rows['admin_notification_email'] : 'info@dlpwc.com',
]);
