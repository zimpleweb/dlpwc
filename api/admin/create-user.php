<?php
require __DIR__ . '/../db.php';
if (!isAdmin()) { http_response_code(403); echo json_encode(['error' => 'Geen toegang']); exit; }

$data     = json_decode(file_get_contents('php://input'), true);
$name     = trim($data['name']     ?? '');
$username = trim($data['username'] ?? '');
$email    = trim($data['email']    ?? '');
$password = trim($data['password'] ?? '');
$role     = in_array($data['role'] ?? '', ['admin', 'user']) ? $data['role'] : 'user';

if (!$name || !$username || !$email || !$password) {
    http_response_code(400); echo json_encode(['error' => 'Alle velden zijn verplicht']); exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400); echo json_encode(['error' => 'Ongeldig e-mailadres']); exit;
}
if (strlen($password) < 8) {
    http_response_code(400); echo json_encode(['error' => 'Wachtwoord minimaal 8 tekens']); exit;
}

$chk = $pdo->prepare("SELECT id FROM users WHERE email = :e OR username = :u");
$chk->execute([':e' => $email, ':u' => $username]);
if ($chk->fetch()) {
    http_response_code(409); echo json_encode(['error' => 'E-mail of gebruikersnaam bestaat al']); exit;
}

$hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
$ins  = $pdo->prepare(
    "INSERT INTO users (name, username, email, password_hash, role) VALUES (:n, :u, :e, :p, :r)"
);
$ins->execute([':n' => $name, ':u' => $username, ':e' => $email, ':p' => $hash, ':r' => $role]);

echo json_encode(['success' => true]);
