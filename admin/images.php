<?php
require_once __DIR__ . '/../includes/config.php';
if (!isset($_SESSION['admin_id'])) redirect('login.php');

$imgDir = __DIR__ . '/../assets/images/';
$fileDir = __DIR__ . '/../uploads/';
$allowedTypes = ['image/webp', 'image/jpeg', 'image/png', 'application/pdf'];
$maxSize = 250 * 1024;
$pdfMaxSize = 10 * 1024 * 1024;

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $file = $_FILES['image'];
    $targetName = basename($_POST['target'] ?? '');
    $isPdf = pathinfo($targetName, PATHINFO_EXTENSION) === 'pdf';

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $message = 'Upload failed.';
    } elseif (($isPdf && $file['size'] > $pdfMaxSize) || (!$isPdf && $file['size'] > $maxSize)) {
        $message = 'File too large. ' . ($isPdf ? 'Max 10MB.' : 'Max 250KB.');
    } elseif (!in_array($file['type'], $allowedTypes)) {
        $message = 'Only WebP, JPEG, PNG, and PDF allowed.';
    } elseif (!$targetName) {
        $message = 'No target specified.';
    } else {
        $destPath = $isPdf ? ($fileDir . $targetName) : ($imgDir . $targetName);
        if (move_uploaded_file($file['tmp_name'], $destPath)) {
            $message = 'File uploaded successfully.';
        } else {
            $message = 'Failed to save file.';
        }
    }
}

$images = glob($imgDir . '*.{webp,jpg,jpeg,png}', GLOB_BRACE);
$pdfs = glob($fileDir . '*.pdf');

require_once __DIR__ . '/../includes/database.php';
$db = Database::getInstance();
$contentSections = $db->fetchAll('SELECT * FROM site_content ORDER BY id');
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Images - Admin - <?= escape(SITE_NAME) ?></title>
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
        .msg { padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 0.9rem; }
        .msg-success { background: #d4edda; color: #155724; }
        .msg-error { background: #fef2f2; color: #dc3545; }
        .grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .image-card { background: #fff; border-radius: 12px; padding: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.05); }
        .image-card img { width: 100%; height: 160px; object-fit: cover; border-radius: 8px; margin-bottom: 10px; }
        .image-card .name { font-size: 0.82rem; color: #2E2E2E; word-break: break-all; }
        .image-card .size { font-size: 0.78rem; color: #77797d; }
        .upload-form { background: #fff; border-radius: 12px; padding: 24px; box-shadow: 0 2px 12px rgba(0,0,0,0.05); margin-bottom: 30px; }
        .upload-form h3 { font-size: 1rem; color: #233d7e; margin-bottom: 16px; }
        .form-row { display: flex; gap: 16px; align-items: flex-end; flex-wrap: wrap; }
        .form-group { flex: 1; min-width: 200px; }
        .form-group label { display: block; font-size: 0.85rem; font-weight: 500; color: #233d7e; margin-bottom: 6px; }
        .form-group select, .form-group input[type=file] { width: 100%; padding: 10px; border: 1.5px solid #e5e7eb; border-radius: 8px; font-family: inherit; font-size: 0.9rem; }
        .btn { padding: 10px 24px; background: #F7941D; color: #fff; border: none; border-radius: 8px; font-size: 0.85rem; font-weight: 600; cursor: pointer; }
        .btn:hover { background: #e08515; }
        .file-input-wrap { display: flex; flex-direction: column; gap: 4px; }
        .form-group input[type=file] { box-sizing: border-box; overflow: visible; width: auto !important; padding: 0 !important; border: none !important; }
        .btn-file { background: #233d7e; display: inline-block; padding: 10px 24px; border-radius: 8px; font-size: 0.85rem; font-weight: 600; color: #fff; }
        .btn-file:hover { background: #1a2f61; }
        .file-name { font-size: 0.85rem; color: #77797d; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-logo"><img src="../assets/images/logo-white.png" alt="<?= escape(SITE_NAME) ?>"></div>
        <a href="dashboard.php">Dashboard</a>
        <a href="content.php">Site Content</a>
        <a href="images.php" class="active">Images</a>
        <a href="../">View Site</a>
        <a href="logout.php" style="margin-top:40px;color:rgba(255,255,255,0.4);">Logout</a>
    </div>
    <div class="main">
        <div class="header-bar">
            <h1>Images</h1>
            <a href="dashboard.php">Back to Dashboard</a>
        </div>

        <?php if ($message): ?>
        <div class="msg <?= strpos($message, 'success') !== false ? 'msg-success' : 'msg-error' ?>"><?= escape($message) ?></div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data" class="upload-form">
            <h3>Replace an Image</h3>
            <div class="form-row">
                <div class="form-group">
                    <label for="target">Image to replace</label>
                    <select name="target" id="target" required>
                        <option value="">Select image...</option>
                        <optgroup label="Site">
                        <option value="hero.webp">Hero Background</option>
                        <option value="about.webp">About Section</option>
                        <option value="logo-white.png">Header Logo</option>
                        <option value="logo-blue.png">Admin Logo (light bg)</option>
                        <option value="logo-original.png">Admin Logo (original)</option>
                        </optgroup>
                        <optgroup label="Services">
                        <option value="service-ppe.webp">PPE Supply</option>
                        <option value="service-maintenance.webp">Maintenance</option>
                        <option value="service-construction.webp">Construction</option>
                        <option value="service-fabrication.webp">Fabrication</option>
                        <option value="service-electrical.webp">Electrical Installation</option>
                        <option value="service-branding.webp">Branding</option>
                        <option value="service-hse.webp">HSE Consultancy</option>
                        <option value="service-mining.webp">Mining Support</option>
                        </optgroup>
                        <optgroup label="Team">
                        <option value="team-1.webp">Team Member 1</option>
                        <option value="team-2.webp">Team Member 2</option>
                        <option value="team-3.webp">Team Member 3</option>
                        </optgroup>
                        <optgroup label="Director">
                        <option value="director.webp">Director Photo</option>
                        </optgroup>
                        <optgroup label="Legal">
                        <option value="certificate.webp">Certificate of Incorporation</option>
                        <option value="tra-registration.webp">TRA Registration</option>
                        </optgroup>
                        <optgroup label="Documents">
                        <option value="company-profile.pdf">Company Profile PDF</option>
                        </optgroup>
                    </select>
                </div>
                <div class="form-group">
                    <label>File (max 250KB, WebP preferred)</label>
                    <div>
                        <input type="file" name="image" id="image" accept="image/webp,image/jpeg,image/png" required>
                        <span id="fileName" style="margin-left:8px;font-size:0.85rem;color:#6B7280"></span>
                    </div>
                    <div id="debugLog" style="margin-top:4px;font-size:0.78rem;color:#6B7280;font-family:monospace"></div>
                    <script>
                    (function(){
                        var input = document.getElementById('image');
                        var log = document.getElementById('debugLog');
                        var name = document.getElementById('fileName');
                        function dbg(m){ console.log('[FILE-UPLOAD] '+m); if(log) log.textContent=m; }
                        if(!input){ dbg('ERROR: input not found'); return; }
                        dbg('Input exists. noCustomStyle=true');
                        input.addEventListener('change', function(){
                            var f = this.files;
                            if(f && f.length>0){ name.textContent = f[0].name; dbg('SELECTED: '+f[0].name); }
                        });
                    })();
                    </script>
                </div>
                <button type="submit" class="btn">Upload</button>
            </div>
        </form>

        <div class="grid">
            <?php foreach ($images as $img): $name = basename($img); $size = filesize($img); ?>
            <div class="image-card">
                <img src="../assets/images/<?= urlencode($name) ?>" alt="<?= escape($name) ?>" loading="lazy">
                <div class="name"><?= escape($name) ?></div>
                <div class="size"><?= round($size / 1024, 1) ?> KB</div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
