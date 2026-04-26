<?php
require_once '../db.php';

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

$validKeys = [
    'new_user_admin',
    'registration_confirm',
    'new_review_admin',
    'review_pending',
    'review_approved',
];

$validLangs = ['en', 'nl', 'fr'];

$upsertSql = 'INSERT INTO mail_templates (template_key, lang, subject, body) VALUES (?, ?, ?, ?) '
           . 'ON DUPLICATE KEY UPDATE subject = VALUES(subject), body = VALUES(body)';
$stmt = $pdo->prepare($upsertSql);

foreach ($body as $templateKey => $langs) {
    // Whitelist template keys to prevent arbitrary DB writes
    if (!in_array($templateKey, $validKeys, true)) {
        continue;
    }

    if (!is_array($langs)) {
        continue;
    }

    foreach ($langs as $lang => $fields) {
        // Whitelist languages
        if (!in_array($lang, $validLangs, true)) {
            continue;
        }

        if (!is_array($fields)) {
            continue;
        }

        $subject = isset($fields['subject']) ? $fields['subject'] : '';
        $bodyText = isset($fields['body'])    ? $fields['body']    : '';

        $stmt->execute([$templateKey, $lang, $subject, $bodyText]);
    }
}

echo json_encode(['success' => true]);
