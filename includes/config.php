<?php
$envFile = __DIR__ . '/../.env';
$env = [];

if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || strpos($line, '#') === 0) continue;
        $parts = explode('=', $line, 2);
        if (count($parts) === 2) {
            $key = trim($parts[0]);
            $val = trim($parts[1]);
            $val = trim($val, '"');
            $env[$key] = $val;
        }
    }
}

define('DB_HOST', $env['DB_HOST'] ?? 'localhost');
define('DB_NAME', $env['DB_NAME'] ?? '');
define('DB_USER', $env['DB_USER'] ?? '');
define('DB_PASS', $env['DB_PASS'] ?? '');
define('SITE_URL', $env['SITE_URL'] ?? 'https://bmsl.co.tz');
define('SITE_NAME', $env['SITE_NAME'] ?? 'Brethren Mining Solution Limited');
define('ADMIN_EMAIL', $env['ADMIN_EMAIL'] ?? 'info@bmsl.co.tz');
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('ASSET_DIR', 'assets/');

session_start();

function escape($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function redirect($url) {
    header('Location: ' . $url);
    exit;
}
