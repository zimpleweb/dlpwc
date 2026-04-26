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

$smtpHost      = isset($body['smtp_host'])       ? trim($body['smtp_host'])       : '';
$smtpPort      = isset($body['smtp_port'])       ? trim($body['smtp_port'])       : '587';
$smtpUser      = isset($body['smtp_user'])       ? trim($body['smtp_user'])       : '';
$smtpPass      = isset($body['smtp_pass'])       ? $body['smtp_pass']             : '';
$smtpSecure    = isset($body['smtp_secure'])     ? trim($body['smtp_secure'])     : 'tls';
$smtpFromName  = isset($body['smtp_from_name'])  ? trim($body['smtp_from_name'])  : '';
$smtpFromEmail = isset($body['smtp_from_email']) ? trim($body['smtp_from_email']) : '';

$upsertSql = 'INSERT INTO site_settings (setting_key, setting_value) VALUES (?, ?) '
           . 'ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)';
$stmt = $pdo->prepare($upsertSql);

$fieldsToSave = [
    'smtp_host'       => $smtpHost,
    'smtp_port'       => $smtpPort,
    'smtp_user'       => $smtpUser,
    'smtp_secure'     => $smtpSecure,
    'smtp_from_name'  => $smtpFromName,
    'smtp_from_email' => $smtpFromEmail,
];

foreach ($fieldsToSave as $key => $value) {
    $stmt->execute([$key, $value]);
}

// Only update password if a new one was supplied
if ($smtpPass !== '') {
    // Willekeurige IV per encryptie; IV wordt vóór de ciphertext opgeslagen (base64)
    $iv        = random_bytes(16);
    $cipher    = openssl_encrypt($smtpPass, 'AES-256-CBC', SMTP_ENC_KEY, OPENSSL_RAW_DATA, $iv);
    $stored    = base64_encode($iv . $cipher);
    $stmt->execute(['smtp_pass_enc', $stored]);
}

echo json_encode(['success' => true]);
