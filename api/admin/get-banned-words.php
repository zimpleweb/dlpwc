<?php
require '../db.php';

if (!isAdmin()) { http_response_code(403); echo json_encode(['error' => 'Geen toegang']); exit; }

header('Content-Type: application/json; charset=utf-8');

try {
  $pdo->exec("
    CREATE TABLE IF NOT EXISTS banned_words (
      id   INT AUTO_INCREMENT PRIMARY KEY,
      word VARCHAR(100) NOT NULL,
      lang ENUM('all','en','nl','fr') NOT NULL DEFAULT 'all',
      UNIQUE KEY uq_word_lang (word, lang)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
  ");

  $rows = $pdo->query(
    "SELECT word, lang FROM banned_words ORDER BY lang, word"
  )->fetchAll(PDO::FETCH_ASSOC);

  echo json_encode([
    'entries' => $rows,
    'words'   => array_column($rows, 'word'),
  ]);
} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(['error' => $e->getMessage()]);
}
