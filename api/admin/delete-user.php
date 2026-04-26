<?php
require '../db.php';
if (!isAdmin()) { http_response_code(403); echo json_encode(['error' => 'Geen toegang']); exit; }
verifyCsrf();

$data = json_decode(file_get_contents('php://input'), true);
$id   = (int)($data['id'] ?? 0);
if (!$id) { echo json_encode(['error' => 'Ongeldig ID']); exit; }

// Beschermde superadmin mag nooit verwijderd worden
$stmt = $pdo->prepare("SELECT email FROM users WHERE id = :id");
$stmt->execute([':id' => $id]);
$u = $stmt->fetch();
if (!$u) { echo json_encode(['error' => 'Gebruiker niet gevonden']); exit; }
if ($u['email'] === 'admin@dlpwc.nl') {
    echo json_encode(['error' => 'Dit account kan niet verwijderd worden']); exit;
}

$pdo->prepare("DELETE FROM users WHERE id = :id")->execute([':id' => $id]);
echo json_encode(['success' => true]);
