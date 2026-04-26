<?php
require __DIR__ . '/db.php';
if (!isLoggedIn()) { http_response_code(401); echo json_encode(['error' => 'Niet ingelogd']); exit; }
verifyCsrf();

if (empty($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400); echo json_encode(['error' => 'Geen bestand ontvangen']); exit;
}

// Valideer via MIME — niet via extensie
$allowed_mime = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime  = $finfo->file($_FILES['avatar']['tmp_name']);
if (!in_array($mime, $allowed_mime)) {
    http_response_code(400); echo json_encode(['error' => 'Alleen JPG, PNG, WebP of GIF toegestaan']); exit;
}

// Max 2MB
if ($_FILES['avatar']['size'] > 2 * 1024 * 1024) {
    http_response_code(400); echo json_encode(['error' => 'Bestand mag maximaal 2MB zijn']); exit;
}

$ext      = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'][$mime];
$dir      = __DIR__ . '/../uploads/avatars/';
if (!is_dir($dir)) mkdir($dir, 0755, true);

// Verwijder oude avatar
$old = $pdo->prepare("SELECT avatar_url FROM users WHERE id = :id");
$old->execute([':id' => $_SESSION['user_id']]);
$oldFile = $old->fetchColumn();
if ($oldFile && file_exists($dir . $oldFile)) unlink($dir . $oldFile);

$filename = 'avatar_' . $_SESSION['user_id'] . '_' . time() . '.' . $ext;
if (!move_uploaded_file($_FILES['avatar']['tmp_name'], $dir . $filename)) {
    http_response_code(500); echo json_encode(['error' => 'Opslaan mislukt — controleer maprechten']); exit;
}

$pdo->prepare("UPDATE users SET avatar_url = :f WHERE id = :id")
    ->execute([':f' => $filename, ':id' => $_SESSION['user_id']]);

echo json_encode(['success' => true, 'filename' => $filename]);
