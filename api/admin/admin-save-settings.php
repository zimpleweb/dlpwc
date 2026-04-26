<?php
ini_set('display_errors', 0);
ini_set('log_errors', 1);
header('Content-Type: application/json');
require '../db.php';
if (!isAdmin()) { http_response_code(403); echo json_encode(["error" => "Geen toegang"]); exit; }

$raw  = file_get_contents('php://input');
$body = json_decode($raw, true);

// ── Kleuren opslaan ────────────────────────────────────────────────────────
if (!empty($body['colors'])) {
    $allowed = ['color_primary','color_accent','color_green','color_red'];

    $prev = [];
    $stmt = $pdo->query(
        "SELECT setting_key, setting_value FROM site_settings
         WHERE setting_key IN ('color_primary','color_accent','color_green','color_red')"
    );
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $r) {
        $prev[$r['setting_key']] = $r['setting_value'];
    }
    if ($prev) {
        $pdo->prepare(
            "INSERT INTO site_settings (setting_key, setting_value)
             VALUES ('color_history', ?)
             ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)"
        )->execute([json_encode($prev)]);
    }

    $stmt = $pdo->prepare(
        "INSERT INTO site_settings (setting_key, setting_value)
         VALUES (?, ?)
         ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)"
    );
    foreach ($body['colors'] as $key => $value) {
        if (in_array($key, $allowed) && preg_match('/^#[0-9a-fA-F]{6}$/', $value)) {
            $stmt->execute([$key, $value]);
        }
    }
    echo json_encode(['success' => true]);
    exit;
}

// ── Vertalingen opslaan ────────────────────────────────────────────────────
if (!empty($body['translations'])) {
    $stmt = $pdo->prepare(
        "INSERT INTO site_settings (setting_key, setting_value)
         VALUES (?, ?)
         ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)"
    );
    foreach (['en','nl','fr'] as $lang) {
        if (isset($body['translations'][$lang])) {
            $stmt->execute([
                "translations_{$lang}",
                json_encode($body['translations'][$lang], JSON_UNESCAPED_UNICODE),
            ]);
        }
    }
    echo json_encode(['success' => true]);
    exit;
}

// ── reCAPTCHA-sleutels opslaan ─────────────────────────────────────────────
if (isset($body['recaptcha'])) {
    $allowed = ['recaptcha_site_key', 'recaptcha_secret_key'];
    $stmt = $pdo->prepare(
        "INSERT INTO site_settings (setting_key, setting_value)
         VALUES (?, ?)
         ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)"
    );
    foreach ($body['recaptcha'] as $key => $value) {
        if (in_array($key, $allowed)) {
            $stmt->execute([$key, trim($value)]);
        }
    }
    echo json_encode(['success' => true]);
    exit;
}

http_response_code(400);
echo json_encode(['error' => 'Geen geldige payload']);
