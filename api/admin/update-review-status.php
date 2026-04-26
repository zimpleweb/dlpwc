<?php
require '../db.php';
require_once __DIR__ . '/../helpers/mailer.php';

if (!isModerator()) {
    http_response_code(403);
    echo json_encode(['error' => 'Geen toegang']);
    exit;
}
verifyCsrf();

$data     = json_decode(file_get_contents('php://input'), true);
$id       = (int)($data['id']       ?? 0);
$status   = $data['status']         ?? '';
$duration = $data['block_duration'] ?? null;

if (!$id || !in_array($status, ['approved', 'rejected', 'blocked'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Ongeldige invoer']);
    exit;
}

// Bereken blocked_until
$blockedUntil = null;
if ($status === 'blocked' && $duration) {
    $durationMap = [
        '3days'  => date('Y-m-d H:i:s', strtotime('+3 days')),
        '1week'  => date('Y-m-d H:i:s', strtotime('+1 week')),
        '1month' => date('Y-m-d H:i:s', strtotime('+1 month')),
        'forever'=> null,
    ];
    $blockedUntil = array_key_exists($duration, $durationMap) ? $durationMap[$duration] : null;
}

$pdo->prepare("UPDATE reviews SET status = :s, blocked_until = :bu WHERE id = :id")
    ->execute([':s' => $status, ':bu' => $blockedUntil, ':id' => $id]);

// Haal review op voor verdere acties
$rev = $pdo->prepare(
    "SELECT r.user_id, r.toilet_id, r.review_lang,
            r.hygiene, r.crowd, r.location, r.facilities,
            t.name AS toilet_name,
            u.email AS user_email, u.name AS user_name
     FROM reviews r
     LEFT JOIN toilets t ON t.id = r.toilet_id
     LEFT JOIN users   u ON u.id = r.user_id
     WHERE r.id = :id"
);
$rev->execute([':id' => $id]);
$r = $rev->fetch();

if ($status === 'approved') {
    if ($r['user_id']) {
        $pdo->prepare(
            "UPDATE users SET approved_review_count = approved_review_count + 1 WHERE id = :uid"
        )->execute([':uid' => $r['user_id']]);
    }
    $pdo->prepare("DELETE FROM guest_pending WHERE review_id = :rid")->execute([':rid' => $id]);
}

if ($status === 'blocked') {
    if ($r['user_id']) {
        $pdo->prepare(
            "UPDATE users SET is_blocked = 1, blocked_until = :bu WHERE id = :uid"
        )->execute([':bu' => $blockedUntil, ':uid' => $r['user_id']]);
    } else {
        $gpStmt = $pdo->prepare(
            "SELECT ip, fingerprint FROM guest_pending WHERE review_id = :rid LIMIT 1"
        );
        $gpStmt->execute([':rid' => $id]);
        $gp = $gpStmt->fetch();
        if ($gp) {
            $pdo->prepare(
                "INSERT INTO guest_blocks (review_id, ip, fingerprint, toilet_id, blocked_until)
                 VALUES (:rid, :ip, :fp, :tid, :bu)"
            )->execute([
                ':rid' => $id, ':ip' => $gp['ip'], ':fp' => $gp['fingerprint'],
                ':tid' => $r['toilet_id'], ':bu' => $blockedUntil,
            ]);
            $pdo->prepare("DELETE FROM guest_pending WHERE review_id = :rid")->execute([':rid' => $id]);
        }
    }
    $pdo->prepare("DELETE FROM guest_pending WHERE review_id = :rid")->execute([':rid' => $id]);
}

if ($status === 'rejected') {
    $pdo->prepare("DELETE FROM guest_pending WHERE review_id = :rid")->execute([':rid' => $id]);
}

// ── Mail bij goedkeuring ───────────────────────────────────────────────────
if ($status === 'approved' && !empty($r['user_email'])) {
    $avg      = round(($r['hygiene'] + $r['crowd'] + $r['location'] + $r['facilities']) / 4, 1);
    $mailLang = in_array($r['review_lang'] ?? '', ['en','nl','fr']) ? $r['review_lang'] : 'nl';
    dlpwc_send_mail($pdo, $r['user_email'], $r['user_name'] ?? '', 'review_approved', $mailLang, [
        'name'   => $r['user_name'] ?? '',
        'toilet' => $r['toilet_name'] ?? "toilet #" . ($r['toilet_id'] ?? '?'),
        'score'  => $avg,
    ]);
}

echo json_encode(['success' => true]);
