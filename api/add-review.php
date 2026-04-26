<?php
require 'db.php';
require_once __DIR__ . '/helpers/mailer.php';
require_once __DIR__ . '/rate-limit.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit; }

verifyCsrf();
rateLimitOrDie('add_review', 10, 3600); // max 10 reviews per uur per IP

$toiletId   = (int)($_POST['toilet_id']  ?? 0);
$hygiene    = (int)($_POST['hygiene']    ?? 0);
$crowd      = (int)($_POST['crowd']      ?? 0);
$location   = (int)($_POST['location']   ?? 0);
$facilities = (int)($_POST['facilities'] ?? 0);
$comment    = trim($_POST['comment']     ?? '');
$guestName  = trim($_POST['guest_name']  ?? '');
$reviewLang = trim($_POST['review_lang'] ?? 'en');

// Alleen toegestane talen
if (!in_array($reviewLang, ['en', 'nl', 'fr'])) $reviewLang = 'en';

if (!$toiletId) { http_response_code(400); echo json_encode(['error' => 'Ongeldig toilet']); exit; }

// ── reCAPTCHA verificatie ─────────────────────────────────────────────────
$rcSecretRow = $pdo->query(
    "SELECT setting_value FROM site_settings WHERE setting_key = 'recaptcha_secret_key' LIMIT 1"
)->fetch();
$rcSecret = $rcSecretRow ? trim($rcSecretRow['setting_value']) : '';

if ($rcSecret && !isAdmin()) {
    $rcToken = trim($_POST['recaptcha_token'] ?? '');
    if (!$rcToken) {
        http_response_code(400); echo json_encode(['error' => 'reCAPTCHA is verplicht']); exit;
    }
    $rcResp = @file_get_contents(
        'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($rcSecret)
        . '&response=' . urlencode($rcToken)
        . '&remoteip=' . urlencode($_SERVER['REMOTE_ADDR'] ?? '')
    );
    $rcData = $rcResp ? json_decode($rcResp, true) : null;
    if (!$rcData || !$rcData['success']) {
        http_response_code(400); echo json_encode(['error' => 'reCAPTCHA verificatie mislukt']); exit;
    }
}

foreach ([$hygiene, $crowd, $location, $facilities] as $v) {
    if ($v < 1 || $v > 5) {
        http_response_code(400); echo json_encode(['error' => 'Sterren 1-5 vereist']); exit;
    }
}

// ── Woordlimiet ───────────────────────────────────────────────────
if ($comment && str_word_count($comment) > 150) {
    http_response_code(400); echo json_encode(['error' => 'Max 150 woorden voor de recensie']); exit;
}

// ── Links blokkeren ───────────────────────────────────────────────
if ($comment && preg_match('/https?:\/\/|www\.|\.com|\.nl|\.org|\.net|\.io/i', $comment)) {
    http_response_code(400); echo json_encode(['error' => 'Links zijn niet toegestaan in een recensie']); exit;
}

// ── Verboden woorden ──────────────────────────────────────────────
$banned = [
    'fuck','shit','bitch','asshole','bastard','cunt','dick','pussy','cock','whore',
    'slut','nigger','nigga','faggot','fag','retard','twat','wanker','prick','arsehole',
    'motherfucker','fucker','bullshit','jackass','dumbass','idiot','moron','imbecile',
    'stupid','loser','ugly','hate','kill','die','murder','rape','molest','abuse',
    'terrorist','bomb','suicide','hang','stab','shoot','poison','nazi','hitler',
    'racist','sexist','pedophile','pedo','pervert','creep','scum','trash','garbage',
    'worthless','disgusting','gross','filthy','dirty','nasty','vile','despicable',
    'freak','psycho','maniac','lunatic','insane','crazy','dumb','brain-dead',
    'subhuman','inferior','degenerate','parasite','vermin','animal','beast',
    'monster','devil','satan','evil','wicked','damned','hell','damn','crap',
    'piss','fart','suck','blowjob','anal','porn','sex','nude','naked','boobs',
    'penis','vagina','orgasm','masturbate','ejaculate','fetish','kink','whip',
    'slave','torture','violence','assault','attack','threat','harass','stalk',
    'spam','scam','fraud','cheat','steal','thief','criminal','drug','cocaine',
    'heroin','meth','weed','dope',
];

if ($comment) {
    $lc = strtolower($comment);
    foreach ($banned as $word) {
        if (preg_match('/\b' . preg_quote($word, '/') . '\b/', $lc)) {
            http_response_code(400);
            echo json_encode(['error' => 'Je recensie bevat ongepaste taal die niet is toegestaan']);
            exit;
        }
    }
}

$userId        = isLoggedIn() ? (int)$_SESSION['user_id'] : null;
$isAdminReview = isAdmin() ? 1 : 0;
$ip            = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
$fingerprint   = trim($_POST['fingerprint'] ?? '');

// ── Geblokkeerde gebruiker check ──────────────────────────────────
if ($userId && !$isAdminReview) {
    $bStmt = $pdo->prepare("SELECT is_blocked, blocked_until FROM users WHERE id = :id");
    $bStmt->execute([':id' => $userId]);
    $u = $bStmt->fetch();

    $blockedUntil = $u['blocked_until'] ?? null;
    $isBlocked    = (int)($u['is_blocked'] ?? 0);

    if ($isBlocked && (!$blockedUntil || strtotime($blockedUntil) > time())) {
        $msg = $blockedUntil
            ? 'Je account is geblokkeerd tot ' . date('d-m-Y', strtotime($blockedUntil))
            : 'Je account is permanent geblokkeerd';
        http_response_code(403); echo json_encode(['error' => $msg, 'blocked' => true]); exit;
    }

    if ($isBlocked && $blockedUntil && strtotime($blockedUntil) <= time()) {
        $pdo->prepare("UPDATE users SET is_blocked = 0, blocked_until = NULL WHERE id = :id")
            ->execute([':id' => $userId]);
    }

    $pStmt = $pdo->prepare(
        "SELECT id FROM reviews WHERE toilet_id = :tid AND user_id = :uid AND status = 'pending' LIMIT 1"
    );
    $pStmt->execute([':tid' => $toiletId, ':uid' => $userId]);
    if ($pStmt->fetch()) {
        http_response_code(409);
        echo json_encode(['error' => 'Je hebt al een recensie in behandeling voor dit toilet', 'pending' => true]);
        exit;
    }
}

// ── Geblokkeerde gast check ───────────────────────────────────────
if (!$userId && !$isAdminReview) {
    $bStmt = $pdo->prepare(
        "SELECT blocked_until FROM guest_blocks
         WHERE (ip = :ip OR fingerprint = :fp)
         AND (blocked_until IS NULL OR blocked_until > NOW())
         LIMIT 1"
    );
    $bStmt->execute([':ip' => $ip, ':fp' => $fingerprint]);
    if ($bStmt->fetch()) {
        http_response_code(403); echo json_encode(['error' => 'Je bent geblokkeerd', 'blocked' => true]); exit;
    }

    $pStmt = $pdo->prepare(
        "SELECT id FROM guest_pending
         WHERE toilet_id = :tid AND (ip = :ip OR fingerprint = :fp) LIMIT 1"
    );
    $pStmt->execute([':tid' => $toiletId, ':ip' => $ip, ':fp' => $fingerprint]);
    if ($pStmt->fetch()) {
        http_response_code(409);
        echo json_encode(['error' => 'Je hebt al een recensie in behandeling voor dit toilet', 'pending' => true]);
        exit;
    }
}

// ── Foto's verwerken ──────────────────────────────────────────────
$images    = [];
$hasPhotos = false;

if (!empty($_FILES['images']['name'][0])) {
    $uploadDir = __DIR__ . '/../uploads/reviews/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
    $finfo = new finfo(FILEINFO_MIME_TYPE);

    foreach ($_FILES['images']['tmp_name'] as $i => $tmpName) {
        if ($_FILES['images']['error'][$i] !== UPLOAD_ERR_OK) continue;
        $mime = $finfo->file($tmpName);
        if (!in_array($mime, ['image/jpeg','image/png','image/webp'])) continue;
        $ext      = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'][$mime];
        $filename = bin2hex(random_bytes(16)) . '.' . $ext;
        move_uploaded_file($tmpName, $uploadDir . $filename);
        $images[]  = $filename;
        $hasPhotos = true;
        if (count($images) >= 3) break;
    }
}

// ── Status bepalen ────────────────────────────────────────────────
$status = 'pending';

if ($isAdminReview) {
    $status = 'approved';
} elseif ($userId) {
    $cntStmt = $pdo->prepare("SELECT approved_review_count FROM users WHERE id = :id");
    $cntStmt->execute([':id' => $userId]);
    $u = $cntStmt->fetch();
    if ($u && $u['approved_review_count'] >= 2 && !$hasPhotos) {
        $status = 'approved';
    }
}

// ── Review opslaan ────────────────────────────────────────────────
$stmt = $pdo->prepare(
    "INSERT INTO reviews
     (toilet_id, user_id, is_admin_review, hygiene, crowd, location, facilities,
      comment, guest_name, images_json, status, review_lang)
     VALUES (:tid, :uid, :adm, :hy, :cr, :lo, :fa, :co, :gn, :img, :st, :rl)"
);
$stmt->execute([
    ':tid' => $toiletId, ':uid' => $userId,       ':adm' => $isAdminReview,
    ':hy'  => $hygiene,  ':cr'  => $crowd,         ':lo'  => $location,
    ':fa'  => $facilities, ':co' => $comment ?: null,
    ':gn'  => $guestName ?: null,
    ':img' => $images ? json_encode($images) : null,
    ':st'  => $status,
    ':rl'  => $reviewLang,
]);
$reviewId = (int)$pdo->lastInsertId();

// ── Gast pending registreren ──────────────────────────────────────
if (!$userId && !$isAdminReview && $status === 'pending' && $fingerprint) {
    $pdo->prepare(
        "INSERT INTO guest_pending (review_id, toilet_id, ip, fingerprint)
         VALUES (:rid, :tid, :ip, :fp)"
    )->execute([':rid' => $reviewId, ':tid' => $toiletId, ':ip' => $ip, ':fp' => $fingerprint]);
}

// ── Mails versturen ───────────────────────────────────────────────────────
$toiletName = $pdo->prepare("SELECT name FROM toilets WHERE id = :id");
$toiletName->execute([':id' => $toiletId]);
$toiletRow  = $toiletName->fetch();
$tName      = $toiletRow ? $toiletRow['name'] : "toilet #$toiletId";

$mailLang = $reviewLang;
$reviewer = $guestName ?: 'Gast';

// Naar ingelogde gebruiker: review pending
if ($userId && $status === 'pending') {
    $uRow = $pdo->prepare("SELECT email, name FROM users WHERE id = :id");
    $uRow->execute([':id' => $userId]);
    $uData = $uRow->fetch();
    if ($uData) {
        dlpwc_send_mail($pdo, $uData['email'], $uData['name'], 'review_pending', $mailLang, [
            'name'   => $uData['name'],
            'toilet' => $tName,
        ]);
        $reviewer = $uData['name'];
    }
}

// Naar admin: nieuwe review binnengekomen
$admin = dlpwc_get_admin_email($pdo);
if ($admin && !$isAdminReview) {
    $avg = round(($hygiene + $crowd + $location + $facilities) / 4, 1);
    dlpwc_send_mail($pdo, $admin['email'], $admin['name'], 'new_review_admin', 'nl', [
        'toilet'   => $tName,
        'reviewer' => $reviewer,
        'score'    => $avg,
    ]);
}

echo json_encode(['success' => true, 'status' => $status]);
