<?php
require_once '../db.php';

define('DLPWC_CONFIG', 1);
require_once '../config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

if (!isAdmin()) {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit;
}

$body = json_decode(file_get_contents('php://input'), true);
if (!is_array($body)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON body']);
    exit;
}

$brevoApiKey           = isset($body['brevo_api_key'])            ? trim($body['brevo_api_key'])            : '';
$fromName              = isset($body['smtp_from_name'])            ? trim($body['smtp_from_name'])            : '';
$fromEmail             = isset($body['smtp_from_email'])           ? trim($body['smtp_from_email'])           : '';
$adminNotificationEmail = isset($body['admin_notification_email']) ? trim($body['admin_notification_email']) : 'info@dlpwc.com';

$upsertSql = 'INSERT INTO site_settings (setting_key, setting_value) VALUES (?, ?) '
           . 'ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)';
$stmt = $pdo->prepare($upsertSql);

$fieldsToSave = [
    'smtp_from_name'           => $fromName,
    'smtp_from_email'          => $fromEmail,
    'admin_notification_email' => $adminNotificationEmail,
];

foreach ($fieldsToSave as $key => $value) {
    $stmt->execute([$key, $value]);
}

// Only update API key if a new one was supplied
if ($brevoApiKey !== '' && $brevoApiKey !== '••••••••') {
    $stmt->execute(['brevo_api_key', $brevoApiKey]);
}

echo json_encode(['success' => true]);
