<?php
require '../db.php';
if (!isAdmin()) { http_response_code(403); echo json_encode(['error' => 'Geen toegang']); exit; }

$data = json_decode(file_get_contents('php://input'), true);
$to   = trim($data['email'] ?? '');
if (!$to || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400); echo json_encode(['error' => 'Ongeldig e-mailadres']); exit;
}

require_once __DIR__ . '/../helpers/mailer.php';

$rows = $pdo->query(
    "SELECT setting_key, setting_value FROM site_settings
     WHERE setting_key IN ('smtp_host','smtp_port','smtp_user','smtp_pass_enc',
                           'smtp_secure','smtp_from_name','smtp_from_email')"
)->fetchAll(PDO::FETCH_KEY_PAIR);

$host      = $rows['smtp_host']       ?? '';
$fromEmail = $rows['smtp_from_email'] ?? '';
if (!$host || !$fromEmail) { echo json_encode(['error' => 'SMTP niet geconfigureerd']); exit; }

$rawPass = '';
if (!empty($rows['smtp_pass_enc'])) {
    if (!defined('DLPWC_CONFIG')) define('DLPWC_CONFIG', true);
    $cf = __DIR__ . '/../config.php';
    if (file_exists($cf)) require_once $cf;
    if (defined('SMTP_ENC_KEY')) {
        $raw    = base64_decode($rows['smtp_pass_enc']);
        $iv     = substr($raw, 0, 16);
        $cipher = substr($raw, 16);
        $dec    = openssl_decrypt($cipher, 'AES-256-CBC', SMTP_ENC_KEY, OPENSSL_RAW_DATA, $iv);
        $rawPass = ($dec !== false) ? $dec : '';
    }
}

$ok = _dlpwc_smtp_send(
    $host,
    (int)($rows['smtp_port']   ?? 587),
    $rows['smtp_secure']       ?? 'tls',
    $rows['smtp_user']         ?? '',
    $rawPass,
    $fromEmail,
    $rows['smtp_from_name']    ?? 'DLPWC',
    $to, $to,
    'DLPWC SMTP Test',
    '<p>Dit is een testmail van DLPWC om je SMTP-configuratie te verifiëren.</p>'
);

echo json_encode($ok ? ['success' => true] : ['error' => 'Verzenden mislukt — controleer SMTP-instellingen']);
