<?php
require_once __DIR__ . '/../includes/config.php';
if (!isset($_SESSION['admin_id'])) redirect('login.php');

$uploadDir = __DIR__ . '/../assets/images/';
$allowedTypes = ['image/webp', 'image/jpeg', 'image/png'];
$maxSize = 250 * 1024;

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $file = $_FILES['image'];
    $targetName = basename($_POST['target'] ?? '');

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $message = 'Upload failed.';
    } elseif ($file['size'] > $maxSize) {
        $message = 'File too large. Max 250KB.';
    } elseif (!in_array($file['type'], $allowedTypes)) {
        $message = 'Only WebP, JPEG, and PNG allowed.';
    } elseif (!$targetName) {
        $message = 'No target specified.';
    } else {
        $ext = pathinfo($targetName, PATHINFO_EXTENSION);
        if (!$ext) $targetName .= '.webp';
        $destPath = $uploadDir . $targetName;
        if (move_uploaded_file($file['tmp_name'], $destPath)) {
            $message = 'Image uploaded successfully.';
        } else {
            $message = 'Failed to save file.';
        }
    }
}

$images = glob($uploadDir . '*.{webp,jpg,jpeg,png}', GLOB_BRACE);

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
        .sidebar { width: 240px; background: #0B1F33; min-height: 100vh; padding: 24px 0; }
        .sidebar h2 { color: #fff; font-size: 1rem; padding: 0 20px 20px; border-bottom: 1px solid rgba(255,255,255,0.08); margin-bottom: 16px; }
        .sidebar a { display: block; padding: 12px 20px; color: rgba(255,255,255,0.7); text-decoration: none; font-size: 0.9rem; }
        .sidebar a:hover, .sidebar a.active { background: rgba(255,255,255,0.06); color: #F7941D; }
        .main { flex: 1; padding: 30px; }
        .header-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .header-bar h1 { font-size: 1.5rem; color: #0B1F33; }
        .header-bar a { color: #6B7280; text-decoration: none; font-size: 0.9rem; }
        .msg { padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 0.9rem; }
        .msg-success { background: #d4edda; color: #155724; }
        .msg-error { background: #fef2f2; color: #dc3545; }
        .grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .image-card { background: #fff; border-radius: 12px; padding: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.05); }
        .image-card img { width: 100%; height: 160px; object-fit: cover; border-radius: 8px; margin-bottom: 10px; }
        .image-card .name { font-size: 0.82rem; color: #2E2E2E; word-break: break-all; }
        .image-card .size { font-size: 0.78rem; color: #6B7280; }
        .upload-form { background: #fff; border-radius: 12px; padding: 24px; box-shadow: 0 2px 12px rgba(0,0,0,0.05); margin-bottom: 30px; }
        .upload-form h3 { font-size: 1rem; color: #0B1F33; margin-bottom: 16px; }
        .form-row { display: flex; gap: 16px; align-items: flex-end; flex-wrap: wrap; }
        .form-group { flex: 1; min-width: 200px; }
        .form-group label { display: block; font-size: 0.85rem; font-weight: 500; color: #0B1F33; margin-bottom: 6px; }
        .form-group select, .form-group input[type=file] { width: 100%; padding: 10px; border: 1.5px solid #e5e7eb; border-radius: 8px; font-family: inherit; font-size: 0.9rem; }
        .btn { padding: 10px 24px; background: #F7941D; color: #fff; border: none; border-radius: 8px; font-size: 0.85rem; font-weight: 600; cursor: pointer; }
        .btn:hover { background: #e08515; }
        .file-input-wrap { display: flex; align-items: center; gap: 10px; }
        .btn-file { background: #0B1F33; cursor: pointer; display: inline-block; }
        .btn-file:hover { background: #1a3a5c; }
        .file-name { font-size: 0.85rem; color: #6B7280; }
        .file-input-hidden { position:fixed;top:-999px;left:-999px;width:1px;height:1px;opacity:0;overflow:hidden;pointer-events:none; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2><?= escape(SITE_NAME) ?></h2>
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
                        <option value="hero.webp">Hero Background</option>
                        <option value="about.webp">About Section</option>
                        <option value="logo.svg">Logo</option>
                        <option value="team-1.webp">Team Member 1</option>
                        <option value="team-2.webp">Team Member 2</option>
                        <option value="team-3.webp">Team Member 3</option>
                        <option value="certificate.webp">Certificate of Incorporation</option>
                        <option value="tra-registration.webp">TRA Registration</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>File (max 250KB, WebP preferred)</label>
                    <div class="file-input-wrap">
                        <input type="file" name="image" id="image" accept="image/webp,image/jpeg,image/png" required class="file-input-hidden">
                        <label for="image" class="btn btn-file">Choose File</label>
                        <span class="file-name" id="fileName">No file chosen</span>
                    </div>
                    <script>
                    document.getElementById('image').addEventListener('change', function() {
                        document.getElementById('fileName').textContent = this.files[0] ? this.files[0].name : 'No file chosen';
                    });
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
