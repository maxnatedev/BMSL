<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/database.php';
$db = Database::getInstance();
$db->query("UPDATE admin_users SET password_hash = ? WHERE username = ?", ['$2y$12$GEB0MTBOrflNknsE7y7JgeocJB1ewzIX7o5HWkkYwcueKPxlHCD7u', 'admin']);
echo 'Password updated. New login: admin / :WaIP88kX)i76h';
