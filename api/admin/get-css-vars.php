<?php
require_once dirname(__DIR__) . '/load-env.php';

$defaults = [
    'color_primary' => '#3d1f8c',
    'color_accent'  => '#f5a800',
    'color_green'   => '#00915a',
    'color_red'     => '#e8231a',
];

header('Content-Type: text/css');
header('Cache-Control: no-store');
header('X-Content-Type-Options: nosniff');

try {
    $host = getenv('DB_HOST') ?: 'localhost';
    $db   = getenv('DB_NAME') ?: '';
    $user = getenv('DB_USER') ?: '';
    $pass = getenv('DB_PASS') ?: '';

    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );
    $stmt   = $pdo->query(
        "SELECT setting_key, setting_value FROM site_settings
         WHERE setting_key IN ('color_primary','color_accent','color_green','color_red')"
    );
    $colors = array_merge($defaults, $stmt->fetchAll(PDO::FETCH_KEY_PAIR));
} catch (Exception $e) {
    $colors = $defaults;
}

echo ":root {\n";
echo "  --color-primary: {$colors['color_primary']};\n";
echo "  --color-accent:  {$colors['color_accent']};\n";
echo "  --color-green:   {$colors['color_green']};\n";
echo "  --color-red:     {$colors['color_red']};\n";
echo "}\n";
