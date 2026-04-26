<?php
// Laad .env één keer per request — roep aan vóór db.php/config.php
if (defined('DLPWC_ENV_LOADED')) return;
define('DLPWC_ENV_LOADED', true);

$envFile = dirname(__DIR__) . '/.env';
if (!file_exists($envFile)) return;

foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
    if (str_starts_with(trim($line), '#') || !str_contains($line, '=')) continue;
    [$key, $val] = explode('=', $line, 2);
    $key = trim($key);
    $val = trim($val);
    if (!getenv($key)) putenv("$key=$val");
}
