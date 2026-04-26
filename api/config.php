<?php
// Prevent direct web access — must be included via a script that defines DLPWC_CONFIG
if (!defined('DLPWC_CONFIG')) {
    http_response_code(403);
    exit('Forbidden');
}

require_once dirname(__DIR__) . '/api/load-env.php';
$_smtpKey = getenv('SMTP_ENC_KEY') ?: '';
if (!$_smtpKey) { http_response_code(500); exit('SMTP_ENC_KEY niet geconfigureerd'); }
define('SMTP_ENC_KEY', $_smtpKey);
unset($_smtpKey);
