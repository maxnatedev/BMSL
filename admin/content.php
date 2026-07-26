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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nav_id'])) {
    $id = (int)$_POST['nav_id'];
    $label = trim($_POST['nav_label']);
    $href = trim($_POST['nav_href']);
    if ($label && $href) {
        $db->query('UPDATE nav_items SET label = ?, href = ? WHERE id = ?', [$label, $href, $id]);
        $_SESSION['flash'] = 'Navigation updated successfully.';
    }
    redirect('content.php');
}

$sections = $db->fetchAll('SELECT * FROM site_content ORDER BY id');
$navItems = $db->fetchAll('SELECT * FROM nav_items ORDER BY sort_order');
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
        body { font-family: 'Inter', system-ui, sans-serif; background: #f0f2f5; display: flex; height: 100vh; overflow: hidden; }
        .sidebar { width: 240px; background: #233d7e; height: 100vh; padding: 0; display: flex; flex-direction: column; flex-shrink: 0; overflow-y: auto; }
        .sidebar-logo { padding: 20px; border-bottom: 1px solid rgba(255,255,255,0.08); text-align: center; }
        .sidebar-logo img { max-width: 160px; height: auto; }
        .sidebar-nav { padding: 8px 0; flex: 1; display: flex; flex-direction: column; }
        .sidebar-item { display: flex; align-items: center; gap: 10px; padding: 10px 20px; color: rgba(255,255,255,0.65); text-decoration: none; font-size: 0.88rem; transition: all 0.15s; border-left: 3px solid transparent; }
        .sidebar-item:hover { background: rgba(255,255,255,0.05); color: #fff; }
        .sidebar-item.active { background: rgba(255,255,255,0.08); color: #F7941D; border-left-color: #F7941D; }
        .sidebar-icon { font-size: 0.75rem; width: 18px; text-align: center; flex-shrink: 0; }
        .sidebar-group { margin: 4px 0; }
        .sidebar-group-title { padding: 8px 20px 4px; font-size: 0.7rem; color: rgba(255,255,255,0.35); text-transform: uppercase; letter-spacing: 1px; font-weight: 600; }
        .sidebar-sub { padding-left: 48px; font-size: 0.84rem; }
        .sidebar-spacer { flex: 1; }
        .sidebar-logout { border-top: 1px solid rgba(255,255,255,0.06); margin-top: 8px; padding-top: 12px; color: rgba(255,255,255,0.4); }
        .sidebar-logout:hover { color: #dc3545 !important; }
        .main { flex: 1; padding: 30px; min-width: 0; height: 100vh; overflow-y: auto; }
        .header-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .header-bar h1 { font-size: 1.5rem; color: #233d7e; }
        .header-bar a { color: #77797d; text-decoration: none; font-size: 0.9rem; }
        .flash { background: #d4edda; color: #155724; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 0.9rem; }
        .content-list { display: grid; gap: 16px; }
        .content-item { background: #fff; border-radius: 12px; padding: 20px; box-shadow: 0 2px 12px rgba(0,0,0,0.05); display: none; }
        .content-item.visible { display: block; }
        .content-item h3 { font-size: 0.9rem; color: #233d7e; margin-bottom: 12px; text-transform: capitalize; }
        .content-item textarea { width: 100%; min-height: 80px; padding: 12px; border: 1.5px solid #e5e7eb; border-radius: 8px; font-family: inherit; font-size: 0.9rem; resize: vertical; }
        .content-item textarea:focus { outline: none; border-color: #F7941D; }
        .content-item .btn { margin-top: 10px; padding: 10px 24px; background: #F7941D; color: #fff; border: none; border-radius: 8px; font-size: 0.85rem; font-weight: 600; cursor: pointer; }
        .content-item .btn:hover { background: #e08515; }
        .content-item .hint { font-size: 0.8rem; color: #6B7280; margin-top: 6px; }
        .section-label { font-size: 0.85rem; font-weight: 600; color: #233d7e; padding: 8px 0 4px; text-transform: uppercase; letter-spacing: 0.5px; display: none; }
        .section-label.visible { display: block; }
        .no-items { color: #77797d; font-size: 0.9rem; padding: 30px; text-align: center; display: none; }
        .no-items.visible { display: block; }
    </style>
</head>
<body>
<?php require_once __DIR__ . '/../includes/admin_sidebar.php'; ?>
    <div class="main">
        <div class="header-bar">
            <h1>Site Content</h1>
            <a href="dashboard.php">Back to Dashboard</a>
        </div>
        <?php if ($flash): ?><div class="flash"><?= escape($flash) ?></div><?php endif; ?>

        <div id="section-general" class="section-label visible">Navigation Links</div>
        <div class="content-list" style="margin-bottom:30px">
            <?php foreach ($navItems as $n): ?>
            <form method="post" class="content-item visible" data-section="nav" style="display:flex;gap:12px;align-items:center;flex-wrap:wrap">
                <input type="hidden" name="nav_id" value="<?= $n['id'] ?>">
                <div style="flex:1;min-width:120px">
                    <label style="display:block;font-size:0.78rem;color:#77797d;margin-bottom:4px">Label</label>
                    <input type="text" name="nav_label" value="<?= escape($n['label']) ?>" style="width:100%;padding:8px 12px;border:1.5px solid #e5e7eb;border-radius:6px;font-family:inherit;font-size:0.9rem">
                </div>
                <div style="flex:1;min-width:120px">
                    <label style="display:block;font-size:0.78rem;color:#77797d;margin-bottom:4px">URL</label>
                    <input type="text" name="nav_href" value="<?= escape($n['href']) ?>" style="width:100%;padding:8px 12px;border:1.5px solid #e5e7eb;border-radius:6px;font-family:inherit;font-size:0.9rem">
                </div>
                <button type="submit" class="btn" style="margin-top:18px;white-space:nowrap">Save</button>
            </form>
            <?php endforeach; ?>
        </div>

        <?php
        $groups = [
            'general' => 'General',
            'values' => 'Values & Why Choose Us',
            'services' => 'Services',
            'team' => 'Team',
            'contact' => 'Contact & Footer',
        ];
        $groupRules = [
            'general' => ['hero_','about_','commitment_','page_title','meta_','og_','nav_cta','director_','read_more','back_to_top','form_','legal_','nav_'],
            'values' => ['values_','why_'],
            'services' => ['service_'],
            'team' => ['team_'],
            'contact' => ['contact_','footer_'],
        ];
        $rendered = [];
        foreach ($sections as $s):
            $sec = $s['page_section'];
            $group = 'general';
            foreach ($groupRules as $g => $prefixes) {
                foreach ($prefixes as $p) {
                    if (strpos($sec, $p) === 0) { $group = $g; break; }
                }
                if ($group === $g && $g !== 'general') break;
            }
            if (!isset($rendered[$group])):
        ?>
        <div id="section-<?= $group ?>" class="section-label"><?= $groups[$group] ?></div>
        <?php $rendered[$group] = true; endif; ?>
        <form method="post" class="content-item" data-section="<?= $group ?>">
            <input type="hidden" name="section" value="<?= escape($sec) ?>">
            <h3><?= escape(str_replace('_', ' ', $sec)) ?></h3>
            <textarea name="content"><?= escape($s['content']) ?></textarea>
            <div class="hint">Last updated: <?= date('d M Y H:i', strtotime($s['updated_at'])) ?></div>
            <button type="submit" class="btn">Save</button>
        </form>
        <?php endforeach; ?>
        <div id="section-contact" class="no-items">Select a section from the sidebar.</div>
    </div>
    <script>
    (function(){
        var hash = window.location.hash.replace('#section-', '');
        var items = document.querySelectorAll('.content-item');
        var labels = document.querySelectorAll('.section-label');
        var noItems = document.querySelector('.no-items');
        function showSection(section) {
            var hasVisible = false;
            items.forEach(function(item){
                var match = !section || item.getAttribute('data-section') === section;
                item.classList.toggle('visible', match);
                if (match) hasVisible = true;
            });
            labels.forEach(function(l){
                var match = !section || l.id === 'section-' + section;
                l.classList.toggle('visible', match);
            });
            if (noItems) noItems.classList.toggle('visible', !hasVisible && section !== '');
        }
        if (hash) { showSection(hash); }
        document.querySelectorAll('.sidebar-sub[data-section]').forEach(function(link){
            link.addEventListener('click', function(e){
                var section = this.getAttribute('data-section');
                showSection(section);
                document.querySelectorAll('.sidebar-item').forEach(function(s){ s.classList.remove('active'); });
                this.classList.add('active');
            });
        });
    })();
    </script>
</body>
</html>
