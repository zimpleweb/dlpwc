<?php
require 'db.php';
if (!isLoggedIn()) { http_response_code(401); echo json_encode(['error' => 'Niet ingelogd']); exit; }
verifyCsrf();

$data    = json_decode(file_get_contents('php://input'), true);
$current = trim($data['current_password'] ?? '');
$new     = trim($data['new_password']     ?? '');

if (!$current || !$new) {
    http_response_code(400); echo json_encode(['error' => 'Vul alle velden in']); exit;
}
if (strlen($new) < 8) {
    http_response_code(400); echo json_encode(['error' => 'Nieuw wachtwoord minimaal 8 tekens']); exit;
}

$stmt = $pdo->prepare("SELECT password_hash FROM users WHERE id = :id");
$stmt->execute([':id' => $_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user || !password_verify($current, $user['password_hash'])) {
    http_response_code(403); echo json_encode(['error' => 'Huidig wachtwoord is onjuist']); exit;
}

$hash = password_hash($new, PASSWORD_BCRYPT, ['cost' => 12]);
$pdo->prepare("UPDATE users SET password_hash = :h WHERE id = :id")
    ->execute([':h' => $hash, ':id' => $_SESSION['user_id']]);

echo json_encode(['success' => true]);
