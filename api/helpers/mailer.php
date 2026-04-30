<?php
/**
 * DLPWC Mailer — leest templates uit DB en verstuurt via Brevo API.
 * Gebruik: dlpwc_send_mail($pdo, $toEmail, $toName, $templateKey, $lang, $vars)
 */

function dlpwc_send_mail($pdo, $toEmail, $toName, $templateKey, $lang, $vars = []) {
    if (!$toEmail || !filter_var($toEmail, FILTER_VALIDATE_EMAIL)) return false;

    // ── Template ophalen ───────────────────────────────────────────
    $tStmt = $pdo->prepare(
        "SELECT subject, body FROM mail_templates
         WHERE template_key = :k AND lang = :l LIMIT 1"
    );
    $tStmt->execute([':k' => $templateKey, ':l' => $lang]);
    $tpl = $tStmt->fetch(PDO::FETCH_ASSOC);

    if (!$tpl || !$tpl['subject']) {
        // Probeer Engels als fallback
        $tStmt->execute([':k' => $templateKey, ':l' => 'en']);
        $tpl = $tStmt->fetch(PDO::FETCH_ASSOC);
    }
    if (!$tpl || !$tpl['subject']) return false;

    $subject = _dlpwc_replace_vars($tpl['subject'], $vars);
    $body    = _dlpwc_replace_vars($tpl['body'],    $vars);

    // ── Brevo-instellingen ophalen ─────────────────────────────────
    $rows = $pdo->query(
        "SELECT setting_key, setting_value FROM site_settings
         WHERE setting_key IN ('brevo_api_key','smtp_from_name','smtp_from_email')"
    )->fetchAll(PDO::FETCH_KEY_PAIR);

    $apiKey    = $rows['brevo_api_key']   ?? '';
    $fromEmail = $rows['smtp_from_email'] ?? '';
    $fromName  = $rows['smtp_from_name']  ?? 'DLPWC';

    if ($apiKey && $fromEmail) {
        return _dlpwc_brevo_send($apiKey, $fromEmail, $fromName, $toEmail, $toName, $subject, $body);
    }

    // ── Fallback: PHP mail() ───────────────────────────────────────
    if (!$fromEmail) return false;
    $headers  = "From: =?UTF-8?B?" . base64_encode($fromName) . "?= <$fromEmail>\r\n";
    $headers .= "Reply-To: $fromEmail\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    return @mail($toEmail, '=?UTF-8?B?' . base64_encode($subject) . '?=', $body, $headers);
}

function _dlpwc_replace_vars($text, $vars) {
    foreach ($vars as $k => $v) {
        $text = str_replace('{{' . $k . '}}', htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'), $text);
    }
    return $text;
}

function _dlpwc_brevo_send($apiKey, $fromEmail, $fromName, $toEmail, $toName, $subject, $body) {
    $payload = json_encode([
        'sender'      => ['name' => $fromName, 'email' => $fromEmail],
        'to'          => [['email' => $toEmail, 'name' => $toName ?: $toEmail]],
        'subject'     => $subject,
        'htmlContent' => $body,
    ]);

    $context = stream_context_create([
        'http' => [
            'method'        => 'POST',
            'header'        => "Content-Type: application/json\r\nAccept: application/json\r\napi-key: " . $apiKey,
            'content'       => $payload,
            'timeout'       => 15,
            'ignore_errors' => true,
        ],
    ]);

    @file_get_contents('https://api.brevo.com/v3/smtp/email', false, $context);

    if (!isset($http_response_header[0])) return false;
    $status = (int)preg_replace('/^HTTP\/\S+ (\d+).*/', '$1', $http_response_header[0]);
    return $status >= 200 && $status < 300;
}

/**
 * Haal het admin-notificatie-e-mailadres op (instelling of standaard).
 */
function dlpwc_get_admin_email($pdo) {
    $row = $pdo->query(
        "SELECT setting_value FROM site_settings WHERE setting_key = 'admin_notification_email' LIMIT 1"
    )->fetch(PDO::FETCH_ASSOC);

    $email = $row ? trim($row['setting_value']) : '';
    if (!$email) $email = 'info@dlpwc.com';

    return ['email' => $email, 'name' => 'DLPWC Admin'];
}
