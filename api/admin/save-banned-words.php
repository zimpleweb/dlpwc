<?php
require '../db.php';

if (!isAdmin()) { http_response_code(403); echo json_encode(['error' => 'Geen toegang']); exit; }

header('Content-Type: application/json; charset=utf-8');

$body = json_decode(file_get_contents('php://input'), true);

if (!isset($body['entries']) || !is_array($body['entries'])) {
  http_response_code(400);
  echo json_encode(['error' => 'Ongeldige invoer']);
  exit;
}

$clean = array_values(array_filter(
  array_map(fn($e) => [
    'word' => preg_replace('/[^a-z0-9\-]/', '', strtolower(trim($e['word'] ?? ''))),
    'lang' => in_array($e['lang'] ?? '', ['all','en','nl','fr'], true) ? $e['lang'] : 'all',
  ], $body['entries']),
  fn($e) => strlen($e['word']) >= 2
));

try {
  $pdo->beginTransaction();

  $pdo->exec("
    CREATE TABLE IF NOT EXISTS banned_words (
      id   INT AUTO_INCREMENT PRIMARY KEY,
      word VARCHAR(100) NOT NULL,
      lang ENUM('all','en','nl','fr') NOT NULL DEFAULT 'all',
      UNIQUE KEY uq_word_lang (word, lang)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
  ");

  $pdo->exec("DELETE FROM banned_words");

  $stmt = $pdo->prepare("INSERT IGNORE INTO banned_words (word, lang) VALUES (?, ?)");
  foreach ($clean as $e) {
    $stmt->execute([$e['word'], $e['lang']]);
  }

  $pdo->commit();
  echo json_encode(['ok' => true, 'count' => count($clean)]);
} catch (Exception $e) {
  $pdo->rollBack();
  http_response_code(500);
  echo json_encode(['error' => $e->getMessage()]);
}
