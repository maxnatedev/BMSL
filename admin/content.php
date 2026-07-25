<?php
require_once __DIR__ . '/../includes/config.php';
if (!isset($_SESSION['admin_id'])) redirect('login.php');

require_once __DIR__ . '/../includes/database.php';
$db = Database::getInstance();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['section'])) {
    $section = $_POST['section'];
    $content = $_POST['content'];
    $db->query('INSERT INTO site_content (page_section, content) VALUES (?, ?) ON DUPLICATE KEY UPDATE content = VALUES(content)', [$section, $content]);
    $_SESSION['flash'] = 'Content updated successfully.';
    redirect('content.php');
}

$sections = $db->fetchAll('SELECT * FROM site_content ORDER BY id');
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Content - Admin - <?= escape(SITE_NAME) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', system-ui, sans-serif; background: #f0f2f5; display: flex; }
        .sidebar { width: 240px; background: #233d7e; min-height: 100vh; padding: 24px 0; display: flex; flex-direction: column; }
        .sidebar .sidebar-logo { padding: 0 20px 20px; border-bottom: 1px solid rgba(255,255,255,0.08); margin-bottom: 16px; }
        .sidebar .sidebar-logo img { max-width: 180px; height: auto; }
        .sidebar a { display: block; padding: 12px 20px; color: rgba(255,255,255,0.7); text-decoration: none; font-size: 0.9rem; }
        .sidebar a:hover, .sidebar a.active { background: rgba(255,255,255,0.06); color: #F7941D; }
        .main { flex: 1; padding: 30px; }
        .header-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .header-bar h1 { font-size: 1.5rem; color: #233d7e; }
        .header-bar a { color: #77797d; text-decoration: none; font-size: 0.9rem; }
        .flash { background: #d4edda; color: #155724; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 0.9rem; }
        .content-list { display: grid; gap: 16px; }
        .content-item { background: #fff; border-radius: 12px; padding: 20px; box-shadow: 0 2px 12px rgba(0,0,0,0.05); }
        .content-item h3 { font-size: 0.9rem; color: #233d7e; margin-bottom: 12px; text-transform: capitalize; }
        .content-item textarea { width: 100%; min-height: 80px; padding: 12px; border: 1.5px solid #e5e7eb; border-radius: 8px; font-family: inherit; font-size: 0.9rem; resize: vertical; }
        .content-item textarea:focus { outline: none; border-color: #F7941D; }
        .content-item .btn { margin-top: 10px; padding: 10px 24px; background: #F7941D; color: #fff; border: none; border-radius: 8px; font-size: 0.85rem; font-weight: 600; cursor: pointer; }
        .content-item .btn:hover { background: #e08515; }
        .content-item .hint { font-size: 0.8rem; color: #6B7280; margin-top: 6px; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-logo"><img src="../assets/images/logo-white.png" alt="<?= escape(SITE_NAME) ?>"></div>
        <a href="dashboard.php">Dashboard</a>
        <a href="content.php" class="active">Site Content</a>
        <a href="images.php">Images</a>
        <a href="../">View Site</a>
        <a href="logout.php" style="margin-top:40px;color:rgba(255,255,255,0.4);">Logout</a>
    </div>
    <div class="main">
        <div class="header-bar">
            <h1>Site Content</h1>
            <a href="dashboard.php">Back to Dashboard</a>
        </div>
        <?php if ($flash): ?><div class="flash"><?= escape($flash) ?></div><?php endif; ?>
        <div class="content-list">
            <?php foreach ($sections as $s): ?>
            <form method="post" class="content-item">
                <input type="hidden" name="section" value="<?= escape($s['page_section']) ?>">
                <h3><?= escape(str_replace('_', ' ', $s['page_section'])) ?></h3>
                <textarea name="content"><?= escape($s['content']) ?></textarea>
                <div class="hint">Last updated: <?= date('d M Y H:i', strtotime($s['updated_at'])) ?></div>
                <button type="submit" class="btn">Save</button>
            </form>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
