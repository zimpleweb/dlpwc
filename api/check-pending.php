<?php
require 'db.php';

$toiletId    = (int)($_GET['toilet_id']   ?? 0);
$fingerprint = trim($_GET['fingerprint']  ?? '');
if (!$toiletId) { echo json_encode(['pending' => false, 'blocked' => false]); exit; }

$ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

if (isLoggedIn()) {
    $uid = (int)$_SESSION['user_id'];

    // Blokkade check
    $bStmt = $pdo->prepare("SELECT is_blocked, blocked_until FROM users WHERE id = :id");
    $bStmt->execute([':id' => $uid]);
    $u = $bStmt->fetch();
    $blockedUntil = $u['blocked_until'] ?? null;
    $isBlocked    = (int)($u['is_blocked'] ?? 0);

    if ($isBlocked && (!$blockedUntil || strtotime($blockedUntil) > time())) {
        echo json_encode(['pending' => false, 'blocked' => true, 'blocked_until' => $blockedUntil]);
        exit;
    }

    // Pending check
    $pStmt = $pdo->prepare(
        "SELECT id FROM reviews WHERE toilet_id = :tid AND user_id = :uid AND status = 'pending' LIMIT 1"
    );
    $pStmt->execute([':tid' => $toiletId, ':uid' => $uid]);
    echo json_encode(['pending' => (bool)$pStmt->fetch(), 'blocked' => false]);

} else {
    // Gast blokkade check
    $bStmt = $pdo->prepare(
        "SELECT blocked_until FROM guest_blocks
         WHERE (ip = :ip OR fingerprint = :fp)
         AND (blocked_until IS NULL OR blocked_until > NOW())
         LIMIT 1"
    );
    $bStmt->execute([':ip' => $ip, ':fp' => $fingerprint]);
    $block = $bStmt->fetch();

    if ($block) {
        echo json_encode(['pending' => false, 'blocked' => true, 'blocked_until' => $block['blocked_until']]);
        exit;
    }

    // Gast pending check
    $pStmt = $pdo->prepare(
        "SELECT id FROM guest_pending
         WHERE toilet_id = :tid AND (ip = :ip OR fingerprint = :fp) LIMIT 1"
    );
    $pStmt->execute([':tid' => $toiletId, ':ip' => $ip, ':fp' => $fingerprint]);
    echo json_encode(['pending' => (bool)$pStmt->fetch(), 'blocked' => false]);
}
