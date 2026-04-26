<?php
/**
 * IP-gebaseerde rate limiting via PHP session.
 * Gebruik: rateLimitOrDie('login', 5, 900)  → max 5 pogingen per 15 min
 */
function rateLimitOrDie(string $key, int $maxAttempts, int $windowSeconds): void {
    $ip      = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $sessKey = 'rl_' . $key . '_' . md5($ip);
    $now     = time();

    if (!isset($_SESSION[$sessKey])) {
        $_SESSION[$sessKey] = ['count' => 0, 'window_start' => $now];
    }

    $rl = &$_SESSION[$sessKey];

    if ($now - $rl['window_start'] > $windowSeconds) {
        $rl['count']        = 0;
        $rl['window_start'] = $now;
    }

    $rl['count']++;

    if ($rl['count'] > $maxAttempts) {
        $retryAfter = $rl['window_start'] + $windowSeconds - $now;
        header('Retry-After: ' . max(1, $retryAfter));
        http_response_code(429);
        echo json_encode(['error' => 'Te veel pogingen. Probeer het later opnieuw.']);
        exit;
    }
}
