<?php
require '../db.php';
if (!isAdmin()) { http_response_code(403); echo json_encode(['error' => 'Geen toegang']); exit; }
echo json_encode(['toilets' => $pdo->query("SELECT * FROM toilets ORDER BY area, name")->fetchAll()]);
