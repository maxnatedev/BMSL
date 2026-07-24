<?php
$env = parse_ini_file(__DIR__ . '/../.env');

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
