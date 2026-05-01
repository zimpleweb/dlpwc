<?php
require __DIR__ . '/../db.php';
if (!isModerator()) { http_response_code(403); echo json_encode(['error' => 'Geen toegang']); exit; }

$toiletId = (int)($_POST['toilet_id'] ?? 0);
if (!$toiletId) { http_response_code(400); echo json_encode(['error' => 'Ongeldig toilet_id']); exit; }

// Verwijderen modus
if (!empty($_POST['remove'])) {
    $stmt = $pdo->prepare("SELECT editorial_photo FROM toilets WHERE id = :id");
    $stmt->execute([':id' => $toiletId]);
    $t = $stmt->fetch();
    if ($t && $t['editorial_photo']) {
        $path = __DIR__ . '/../../uploads/editorial/' . $t['editorial_photo'];
        if (file_exists($path)) unlink($path);
    }
    $pdo->prepare("UPDATE toilets SET editorial_photo = NULL WHERE id = :id")
        ->execute([':id' => $toiletId]);
    echo json_encode(['success' => true]);
    exit;
}

// Upload modus
if (empty($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400); echo json_encode(['error' => 'Geen bestand ontvangen']); exit;
}

// Valideer bestandstype via MIME (veiliger dan extensie alleen)
$allowed_mime = ['image/jpeg', 'image/png', 'image/webp'];
$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime  = $finfo->file($_FILES['photo']['tmp_name']);
if (!in_array($mime, $allowed_mime)) {
    http_response_code(400); echo json_encode(['error' => 'Alleen JPG, PNG of WebP toegestaan']); exit;
}

// Max 5MB
if ($_FILES['photo']['size'] > 5 * 1024 * 1024) {
    http_response_code(400); echo json_encode(['error' => 'Bestand mag maximaal 5MB zijn']); exit;
}

$ext      = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'][$mime];
$dir      = __DIR__ . '/../../uploads/editorial/';
if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
    file_put_contents($dir . '.htaccess', "Options -ExecCGI\nAddHandler cgi-script .php .php3 .php4 .php5 .phtml .pl .py .rb .cgi\nOptions -Indexes\n");
}

// Verwijder oude foto eerst
$stmt = $pdo->prepare("SELECT editorial_photo FROM toilets WHERE id = :id");
$stmt->execute([':id' => $toiletId]);
$old = $stmt->fetch();
if ($old && $old['editorial_photo']) {
    $oldPath = $dir . $old['editorial_photo'];
    if (file_exists($oldPath)) unlink($oldPath);
}

$filename = 'editorial_' . $toiletId . '_' . time() . '.' . $ext;
if (!move_uploaded_file($_FILES['photo']['tmp_name'], $dir . $filename)) {
    http_response_code(500); echo json_encode(['error' => 'Opslaan mislukt — controleer maprechten']); exit;
}

$pdo->prepare("UPDATE toilets SET editorial_photo = :f WHERE id = :id")
    ->execute([':f' => $filename, ':id' => $toiletId]);

echo json_encode(['success' => true, 'filename' => $filename]);
