<?php
require_once __DIR__ . '/load-env.php';

// ── Session beveiliging ───────────────────────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) {
    $isSecure = str_starts_with(getenv('APP_DOMAIN') ?: '', 'https');
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => $isSecure,
        'httponly' => true,
        'samesite' => 'Strict',
    ]);
    session_start();
}

// ── CSRF token genereren (eenmalig per sessie) ────────────────────────────────
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

header('Content-Type: application/json');

// ── Security headers ──────────────────────────────────────────────────────────
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('Referrer-Policy: strict-origin-when-cross-origin');
header("Content-Security-Policy: default-src 'none'");

// ── CORS: alleen eigen domein + localhost (dev) ───────────────────────────────
$appDomain = rtrim(getenv('APP_DOMAIN') ?: '', '/');
$allowed   = array_filter([$appDomain, 'http://localhost:4321', 'http://localhost', 'http://127.0.0.1']);
$origin    = $_SERVER['HTTP_ORIGIN'] ?? '';
if (in_array($origin, $allowed, true)) {
    header('Access-Control-Allow-Origin: ' . $origin);
}
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-CSRF-Token');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// ── Database verbinding ───────────────────────────────────────────────────────
$host = getenv('DB_HOST') ?: 'localhost';
$db   = getenv('DB_NAME') ?: '';
$user = getenv('DB_USER') ?: '';
$pass = getenv('DB_PASS') ?: '';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8mb4",
        $user, $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database verbinding mislukt']);
    exit;
}

function isAdmin(): bool {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function isModerator(): bool {
    return isset($_SESSION['role']) && in_array($_SESSION['role'], ['admin', 'moderator'], true);
}

function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

// Valideer CSRF-token via header of JSON body veld '_csrf'
function verifyCsrf(): void {
    $token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (!$token) {
        $body  = json_decode(file_get_contents('php://input'), true);
        $token = $body['_csrf'] ?? '';
        // Zet de invoer terug in een wrapper zodat aanroepende code hem nog kan lezen
        // (php://input is stream; aanroepende code moet $body hergebruiken of raw opslaan)
    }
    if (!$token || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(403);
        echo json_encode(['error' => 'Ongeldige CSRF-token']);
        exit;
    }
}

function scoreToColor($score): string {
    if ($score === null) return 'gray';
    if ($score < 2.0)  return '#ef4444';
    if ($score < 2.75) return '#f97316';
    if ($score < 3.5)  return '#eab308';
    if ($score < 4.25) return '#84cc16';
    return '#22c55e';
}

function calcToiletScore(PDO $pdo, int $toiletId): array {
    $stmt = $pdo->prepare(
        "SELECT is_admin_review, user_id,
                (hygiene + crowd + location + facilities) / 4.0 AS score
         FROM reviews
         WHERE toilet_id = :tid AND status = 'approved'"
    );
    $stmt->execute([':tid' => $toiletId]);
    $reviews = $stmt->fetchAll();

    $adminSum = 0; $adminCount = 0;
    $regSum   = 0; $regCount   = 0;
    $guestSum = 0; $guestCount = 0;

    foreach ($reviews as $r) {
        $s = (float)$r['score'];
        if ($r['is_admin_review']) {
            $adminSum += $s; $adminCount++;
        } elseif ($r['user_id']) {
            $regSum += $s; $regCount++;
        } else {
            $guestSum += $s; $guestCount++;
        }
    }

    $wa = 0.45; $wr = 0.35; $wg = 0.20;
    $adminAvg = $adminCount ? $adminSum / $adminCount : null;
    $regAvg   = $regCount   ? $regSum   / $regCount   : null;
    $guestAvg = $guestCount ? $guestSum / $guestCount : null;

    $totalWeight = 0;
    if ($adminAvg !== null) $totalWeight += $wa;
    if ($regAvg   !== null) $totalWeight += $wr;
    if ($guestAvg !== null) $totalWeight += $wg;

    $finalScore = null;
    if ($totalWeight > 0) {
        $finalScore = (
            ($adminAvg !== null ? $adminAvg * $wa : 0) +
            ($regAvg   !== null ? $regAvg   * $wr : 0) +
            ($guestAvg !== null ? $guestAvg * $wg : 0)
        ) / $totalWeight;
    }

    return [
        'score'        => $finalScore ? round($finalScore, 2) : null,
        'color'        => scoreToColor($finalScore),
        'review_count' => $adminCount + $regCount + $guestCount,
    ];
}
