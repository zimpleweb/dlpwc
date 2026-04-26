<?php
/**
 * DLPWC Mailer — leest templates uit DB en verstuurt via SMTP of mail().
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

    // ── SMTP-instellingen ophalen ──────────────────────────────────
    $rows = $pdo->query(
        "SELECT setting_key, setting_value FROM site_settings
         WHERE setting_key IN ('smtp_host','smtp_port','smtp_user','smtp_pass_enc',
                               'smtp_secure','smtp_from_name','smtp_from_email')"
    )->fetchAll(PDO::FETCH_KEY_PAIR);

    $fromEmail = $rows['smtp_from_email'] ?? '';
    $fromName  = $rows['smtp_from_name']  ?? 'DLPWC';
    $host      = $rows['smtp_host']       ?? '';

    // Wachtwoord ontcijferen
    $rawPass = '';
    if (!empty($rows['smtp_pass_enc'])) {
        if (!defined('DLPWC_CONFIG')) define('DLPWC_CONFIG', true);
        $configFile = __DIR__ . '/../config.php';
        if (file_exists($configFile)) require_once $configFile;
        if (defined('SMTP_ENC_KEY')) {
            // IV (16 bytes) is opgeslagen vóór de ciphertext; beide zijn raw binary, base64-encoded
            $raw    = base64_decode($rows['smtp_pass_enc']);
            $iv     = substr($raw, 0, 16);
            $cipher = substr($raw, 16);
            $dec    = openssl_decrypt($cipher, 'AES-256-CBC', SMTP_ENC_KEY, OPENSSL_RAW_DATA, $iv);
            $rawPass = ($dec !== false) ? $dec : '';
        }
    }

    if ($host && $fromEmail && $rawPass) {
        return _dlpwc_smtp_send(
            $host,
            (int)($rows['smtp_port']   ?? 587),
            $rows['smtp_secure']       ?? 'tls',
            $rows['smtp_user']         ?? '',
            $rawPass,
            $fromEmail, $fromName,
            $toEmail,   $toName,
            $subject,   $body
        );
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

function _dlpwc_smtp_send($host, $port, $secure, $user, $pass, $fromEmail, $fromName, $toEmail, $toName, $subject, $body) {
    $prefix  = ($secure === 'ssl') ? 'ssl://' : '';
    $timeout = 15;

    $sock = @fsockopen($prefix . $host, $port, $errno, $errstr, $timeout);
    if (!$sock) return false;

    stream_set_timeout($sock, $timeout);

    $read = function() use ($sock) {
        $data = '';
        while (!feof($sock)) {
            $line = fgets($sock, 515);
            if ($line === false) break;
            $data .= $line;
            if (strlen($line) >= 4 && $line[3] === ' ') break;
        }
        return (int)substr($data, 0, 3);
    };

    $cmd = function($c) use ($sock, $read) {
        fwrite($sock, $c . "\r\n");
        return $read();
    };

    $greeting = $read();
    if ($greeting !== 220) { fclose($sock); return false; }

    $localHost = gethostname() ?: 'localhost';
    $cmd('EHLO ' . $localHost);

    if ($secure === 'tls') {
        $code = $cmd('STARTTLS');
        if ($code !== 220) { fclose($sock); return false; }
        if (!stream_socket_enable_crypto($sock, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
            fclose($sock); return false;
        }
        $cmd('EHLO ' . $localHost);
    }

    $cmd('AUTH LOGIN');
    $cmd(base64_encode($user));
    $code = $cmd(base64_encode($pass));
    if ($code !== 235) { fclose($sock); return false; }

    $cmd("MAIL FROM:<$fromEmail>");
    $cmd("RCPT TO:<$toEmail>");
    $cmd('DATA');

    $encSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
    $encFrom    = '=?UTF-8?B?' . base64_encode($fromName) . '?= <' . $fromEmail . '>';
    $encTo      = $toName ? ('=?UTF-8?B?' . base64_encode($toName) . '?= <' . $toEmail . '>') : $toEmail;

    $msg  = "Date: " . date('r') . "\r\n";
    $msg .= "From: $encFrom\r\n";
    $msg .= "To: $encTo\r\n";
    $msg .= "Subject: $encSubject\r\n";
    $msg .= "MIME-Version: 1.0\r\n";
    $msg .= "Content-Type: text/html; charset=UTF-8\r\n";
    $msg .= "Content-Transfer-Encoding: base64\r\n\r\n";
    $msg .= chunk_split(base64_encode($body));
    $msg .= "\r\n.\r\n";

    fwrite($sock, $msg);
    $read();
    $cmd('QUIT');
    fclose($sock);
    return true;
}

/**
 * Haal het e-mailadres van de eerste admin op uit de database.
 */
function dlpwc_get_admin_email($pdo) {
    $row = $pdo->query(
        "SELECT email, name FROM users WHERE role = 'admin' ORDER BY id ASC LIMIT 1"
    )->fetch(PDO::FETCH_ASSOC);
    return $row ?: null;
}
