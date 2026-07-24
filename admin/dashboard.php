<?php
require_once __DIR__ . '/../includes/config.php';
if (!isset($_SESSION['admin_id'])) redirect('login.php');

require_once __DIR__ . '/../includes/database.php';
$db = Database::getInstance();

$messageCount = $db->fetch('SELECT COUNT(*) as count FROM contact_messages')['count'];
$recentMessages = $db->fetchAll('SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 5');
$contentCount = $db->fetch('SELECT COUNT(*) as count FROM site_content')['count'];
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin - <?= escape(SITE_NAME) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', system-ui, sans-serif; background: #f0f2f5; display: flex; }
        .sidebar { width: 240px; background: #0B1F33; min-height: 100vh; padding: 24px 0; }
        .sidebar h2 { color: #fff; font-size: 1rem; padding: 0 20px 20px; border-bottom: 1px solid rgba(255,255,255,0.08); margin-bottom: 16px; }
        .sidebar a { display: block; padding: 12px 20px; color: rgba(255,255,255,0.7); text-decoration: none; font-size: 0.9rem; transition: all 0.2s; }
        .sidebar a:hover, .sidebar a.active { background: rgba(255,255,255,0.06); color: #F7941D; }
        .main { flex: 1; padding: 30px; }
        .header-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .header-bar h1 { font-size: 1.5rem; color: #0B1F33; }
        .header-bar a { color: #6B7280; text-decoration: none; font-size: 0.9rem; }
        .header-bar a:hover { color: #F7941D; }
        .cards { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px; }
        .card { background: #fff; border-radius: 12px; padding: 24px; box-shadow: 0 2px 12px rgba(0,0,0,0.05); }
        .card h3 { font-size: 0.85rem; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; }
        .card .number { font-size: 2rem; font-weight: 700; color: #0B1F33; }
        .table-wrap { background: #fff; border-radius: 12px; padding: 20px; box-shadow: 0 2px 12px rgba(0,0,0,0.05); }
        .table-wrap h3 { font-size: 1rem; color: #0B1F33; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; font-size: 0.85rem; }
        th { text-align: left; padding: 12px 8px; border-bottom: 2px solid #f0f2f5; color: #6B7280; font-weight: 600; }
        td { padding: 12px 8px; border-bottom: 1px solid #f0f2f5; color: #2E2E2E; }
        tr:hover td { background: #fafafa; }
        .badge { display: inline-block; padding: 2px 10px; border-radius: 50px; font-size: 0.75rem; font-weight: 500; }
        .badge-new { background: #fef3e2; color: #F7941D; }
        .empty { color: #6B7280; font-size: 0.9rem; padding: 20px; text-align: center; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2><?= escape(SITE_NAME) ?></h2>
        <a href="dashboard.php" class="active">Dashboard</a>
        <a href="content.php">Site Content</a>
        <a href="images.php">Images</a>
        <a href="../">View Site</a>
        <a href="logout.php" style="margin-top:40px;color:rgba(255,255,255,0.4);">Logout</a>
    </div>
    <div class="main">
        <div class="header-bar">
            <h1>Dashboard</h1>
            <a href="logout.php">Logout</a>
        </div>
        <div class="cards">
            <div class="card"><h3>Messages</h3><div class="number"><?= $messageCount ?></div></div>
            <div class="card"><h3>Content Sections</h3><div class="number"><?= $contentCount ?></div></div>
            <div class="card"><h3>Admin</h3><div class="number" style="font-size:1rem;font-weight:400;padding-top:8px;"><?= escape($_SESSION['admin_user']) ?></div></div>
        </div>
        <div class="table-wrap">
            <h3>Recent Messages</h3>
            <?php if (empty($recentMessages)): ?>
                <div class="empty">No messages yet.</div>
            <?php else: ?>
            <table>
                <thead><tr><th>Name</th><th>Email</th><th>Company</th><th>Date</th></tr></thead>
                <tbody>
                    <?php foreach ($recentMessages as $msg): ?>
                    <tr>
                        <td><?= escape($msg['name']) ?></td>
                        <td><a href="mailto:<?= escape($msg['email']) ?>"><?= escape($msg['email']) ?></a></td>
                        <td><?= escape($msg['company'] ?: '-') ?></td>
                        <td><?= date('d M Y', strtotime($msg['created_at'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
