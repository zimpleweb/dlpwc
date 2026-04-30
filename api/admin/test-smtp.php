<?php
require '../db.php';
if (!isAdmin()) { http_response_code(403); echo json_encode(['error' => 'Geen toegang']); exit; }

$data = json_decode(file_get_contents('php://input'), true);
$to   = trim($data['email'] ?? '');
if (!$to || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400); echo json_encode(['error' => 'Ongeldig e-mailadres']); exit;
}

$rows = $pdo->query(
    "SELECT setting_key, setting_value FROM site_settings
     WHERE setting_key IN ('brevo_api_key','smtp_from_name','smtp_from_email')"
)->fetchAll(PDO::FETCH_KEY_PAIR);

$apiKey    = $rows['brevo_api_key']   ?? '';
$fromEmail = $rows['smtp_from_email'] ?? '';
$fromName  = $rows['smtp_from_name']  ?? 'DLPWC';

if (!$apiKey || !$fromEmail) {
    echo json_encode(['error' => 'Brevo niet geconfigureerd (API-sleutel of e-mailadres afzender ontbreekt)']);
    exit;
}

require_once __DIR__ . '/../helpers/mailer.php';

$ok = _dlpwc_brevo_send(
    $apiKey, $fromEmail, $fromName,
    $to, $to,
    'DLPWC Brevo Test',
    '<p>Dit is een testmail van DLPWC om je Brevo-configuratie te verifiëren.</p>'
);

echo json_encode($ok ? ['success' => true] : ['error' => 'Verzenden mislukt — controleer de Brevo API-sleutel en afzender-e-mail']);
