<?php
require 'db.php';
require_once __DIR__ . '/rate-limit.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Methode niet toegestaan']);
    exit;
}

verifyCsrf();
rateLimitOrDie('login', 5, 900); // max 5 pogingen per 15 min

$raw  = file_get_contents('php://input');
$data = json_decode($raw, true);

$email    = trim($data['email']    ?? '');
$password = trim($data['password'] ?? '');

if (!$email || !$password) {
    http_response_code(400);
    echo json_encode(['error' => 'E-mail en wachtwoord zijn verplicht']);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE email = :e LIMIT 1");
$stmt->execute([':e' => $email]);
$u = $stmt->fetch();

if (!$u) {
    http_response_code(401);
    echo json_encode(['error' => 'Geen account gevonden met dit e-mailadres']);
    exit;
}

if (!password_verify($password, $u['password_hash'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Onjuist wachtwoord']);
    exit;
}

if ((int)$u['is_blocked'] === 1) {
    http_response_code(403);
    echo json_encode(['error' => 'Dit account is geblokkeerd']);
    exit;
}

// Sessie opslaan
$_SESSION['user_id']  = $u['id'];
$_SESSION['username'] = $u['username'];
$_SESSION['role']     = $u['role'];

echo json_encode([
    'success'  => true,
    'user_id'  => $u['id'],
    'username' => $u['username'],
    'role'     => $u['role'],
    'avatar'   => $u['avatar_url'],
]);
