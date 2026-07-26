<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/database.php';
$db = Database::getInstance();
$db->query("INSERT INTO site_content (page_section, content) VALUES ('footer_credit_text','Built by MAXNATE'),('footer_credit_url','https://maxnate.com') ON DUPLICATE KEY UPDATE content = VALUES(content)");
echo 'done';
