<?php
require_once '../db.php';

header('Content-Type: application/json');

if (!isAdmin()) {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit;
}

$validKeys = [
    'new_user_admin',
    'registration_confirm',
    'new_review_admin',
    'review_pending',
    'review_approved',
];

$stmt = $pdo->prepare(
    'SELECT template_key, lang, subject, body
     FROM mail_templates
     ORDER BY template_key, lang'
);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Build nested structure: [template_key][lang] = {subject, body}
$result = [];

// Pre-populate all known keys so the response is predictable even if DB has no rows yet
foreach ($validKeys as $key) {
    $result[$key] = [];
}

foreach ($rows as $row) {
    $tKey = $row['template_key'];
    $lang = $row['lang'];

    if (!isset($result[$tKey])) {
        $result[$tKey] = [];
    }

    $result[$tKey][$lang] = [
        'subject' => $row['subject'],
        'body'    => $row['body'],
    ];
}

echo json_encode($result);
