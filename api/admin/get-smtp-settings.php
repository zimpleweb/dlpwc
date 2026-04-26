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
    'smtp_host',
    'smtp_port',
    'smtp_user',
    'smtp_pass_enc',
    'smtp_secure',
    'smtp_from_name',
    'smtp_from_email',
];

$placeholders = implode(',', array_fill(0, count($keys), '?'));

$stmt = $pdo->prepare(
    "SELECT setting_key, setting_value FROM site_settings WHERE setting_key IN ($placeholders)"
);
$stmt->execute($keys);
$rows = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

$smtpPassEnc = isset($rows['smtp_pass_enc']) ? $rows['smtp_pass_enc'] : '';
$hasPassword = $smtpPassEnc !== '';

echo json_encode([
    'smtp_host'       => isset($rows['smtp_host'])       ? $rows['smtp_host']       : '',
    'smtp_port'       => isset($rows['smtp_port'])       ? $rows['smtp_port']       : '587',
    'smtp_user'       => isset($rows['smtp_user'])       ? $rows['smtp_user']       : '',
    'smtp_pass'       => $hasPassword ? '••••••••' : '',
    'smtp_secure'     => isset($rows['smtp_secure'])     ? $rows['smtp_secure']     : 'tls',
    'smtp_from_name'  => isset($rows['smtp_from_name'])  ? $rows['smtp_from_name']  : '',
    'smtp_from_email' => isset($rows['smtp_from_email']) ? $rows['smtp_from_email'] : '',
    'has_password'    => $hasPassword,
]);
