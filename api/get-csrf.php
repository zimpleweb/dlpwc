<?php
// Geeft het CSRF-token voor de huidige sessie terug
require 'db.php';
echo json_encode(['csrf_token' => $_SESSION['csrf_token']]);
