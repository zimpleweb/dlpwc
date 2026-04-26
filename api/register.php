<?php
require 'db.php';
require_once __DIR__ . '/helpers/mailer.php';
require_once __DIR__ . '/rate-limit.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit; }

verifyCsrf();
rateLimitOrDie('register', 3, 3600); // max 3 registraties per uur per IP

$data = json_decode(file_get_contents('php://input'), true);
$name     = trim($data['name']     ?? '');
$username = trim($data['username'] ?? '');
$email    = trim($data['email']    ?? '');
$password = $data['password']       ?? '';

if (!$name || !$username || !$email || !$password) {
    http_response_code(400); echo json_encode(['error' => 'Alle velden zijn verplicht']); exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400); echo json_encode(['error' => 'Ongeldig e-mailadres']); exit;
}
if (strlen($password) < 8) {
    http_response_code(400); echo json_encode(['error' => 'Wachtwoord minimaal 8 tekens']); exit;
}

// Duplicate check
$chk = $pdo->prepare("SELECT id FROM users WHERE email = :e OR username = :u");
$chk->execute([':e' => $email, ':u' => $username]);
if ($chk->fetch()) {
    http_response_code(409); echo json_encode(['error' => 'E-mail of gebruikersnaam bestaat al']); exit;
}

$hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
$ins  = $pdo->prepare(
    "INSERT INTO users (name, username, email, password_hash, role) VALUES (:n, :u, :e, :p, 'user')"
);
$ins->execute([':n' => $name, ':u' => $username, ':e' => $email, ':p' => $hash]);
$newId = (int)$pdo->lastInsertId();

$_SESSION['user_id']  = $newId;
$_SESSION['username'] = $username;
$_SESSION['role']     = 'user';

// ── Mails versturen (niet-blokkerend) ────────────────────────────────────
$mailLang = in_array($data['lang'] ?? '', ['en','nl','fr']) ? $data['lang'] : 'nl';

dlpwc_send_mail($pdo, $email, $name, 'registration_confirm', $mailLang, [
    'name'     => $name,
    'username' => $username,
]);

$admin = dlpwc_get_admin_email($pdo);
if ($admin) {
    dlpwc_send_mail($pdo, $admin['email'], $admin['name'], 'new_user_admin', 'nl', [
        'name'     => $name,
        'email'    => $email,
        'username' => $username,
    ]);
}

echo json_encode(['success' => true, 'user_id' => $newId, 'username' => $username, 'role' => 'user']);
