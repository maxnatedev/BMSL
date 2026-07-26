<?php
require_once __DIR__ . '/../includes/config.php';
if (!isset($_SESSION['admin_id'])) redirect('login.php');

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$imgDir = __DIR__ . '/../assets/images/';
$fileDir = __DIR__ . '/../uploads/';
$allowedMimes = ['image/webp', 'image/jpeg', 'image/png', 'image/svg+xml', 'image/x-icon', 'image/vnd.microsoft.icon', 'application/pdf'];
$maxSize = 250 * 1024;
$pdfMaxSize = 10 * 1024 * 1024;

$allowedTargets = [
    'logo-white.png', 'logo-blue.png', 'logo-original.png',
    'favicon.ico', 'favicon.svg', 'favicon-96x96.png',
    'hero.webp', 'about.webp',
    'service-ppe.webp', 'service-maintenance.webp', 'service-construction.webp', 'service-fabrication.webp',
    'service-electrical.webp', 'service-branding.webp', 'service-hse.webp', 'service-mining.webp',
    'team-1.webp', 'team-2.webp', 'team-3.webp', 'director.webp',
    'certificate.webp', 'tra-registration.webp',
    'company-profile.pdf',
];

$uploadErrors = [
    UPLOAD_ERR_OK => 'Upload successful.',
    UPLOAD_ERR_INI_SIZE => 'File exceeds server upload limit (upload_max_filesize).',
    UPLOAD_ERR_FORM_SIZE => 'File exceeds form size limit.',
    UPLOAD_ERR_PARTIAL => 'File was only partially uploaded.',
    UPLOAD_ERR_NO_FILE => 'No file was selected.',
    UPLOAD_ERR_NO_TMP_DIR => 'Server temporary folder missing.',
    UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
    UPLOAD_ERR_EXTENSION => 'Upload stopped by a PHP extension.',
];

$message = '';
$msgType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $file = $_FILES['image'];
    $targetName = basename($_POST['target'] ?? '');
    $csrfOk = isset($_POST['csrf_token']) && hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token']);
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    $isPdf = pathinfo($targetName, PATHINFO_EXTENSION) === 'pdf';

    if (!$csrfOk) {
        $message = 'Invalid security token. Please refresh and try again.';
        $msgType = 'error';
    } elseif (!$targetName || !in_array($targetName, $allowedTargets)) {
        $message = 'Invalid upload target.';
        $msgType = 'error';
    } elseif ($file['error'] !== UPLOAD_ERR_OK) {
        $message = $uploadErrors[$file['error']] ?? 'Upload failed (error code ' . $file['error'] . ').';
        $msgType = 'error';
    } elseif (($isPdf && $file['size'] > $pdfMaxSize) || (!$isPdf && $file['size'] > $maxSize)) {
        $message = 'File too large. ' . ($isPdf ? 'Max 10MB.' : 'Max 250KB.');
        $msgType = 'error';
    } elseif (!in_array(mime_content_type($file['tmp_name']), $allowedMimes)) {
        $message = 'Invalid file type. Allowed: WebP, JPEG, PNG, SVG, ICO, PDF.';
        $msgType = 'error';
    } else {
        $destPath = $isPdf ? ($fileDir . $targetName) : ($imgDir . $targetName);
        $targetExt = strtolower(pathinfo($targetName, PATHINFO_EXTENSION));

        if ($targetExt === 'webp') {
            $srcMime = mime_content_type($file['tmp_name']);
            if ($srcMime === 'image/webp') {
                if (move_uploaded_file($file['tmp_name'], $destPath)) {
                    $message = 'File uploaded successfully.';
                    $msgType = 'success';
                } else {
                    $message = 'Failed to save file — check folder permissions.';
                    $msgType = 'error';
                }
            } else {
                switch ($srcMime) {
                    case 'image/jpeg': $src = imagecreatefromjpeg($file['tmp_name']); break;
                    case 'image/png': $src = imagecreatefrompng($file['tmp_name']); break;
                    default: $src = false;
                }
                if ($src && imagewebp($src, $destPath, 85)) {
                    imagedestroy($src);
                    $message = 'File uploaded and converted to WebP.';
                    $msgType = 'success';
                } else {
                    if ($src) imagedestroy($src);
                    $message = 'Image conversion to WebP failed.';
                    $msgType = 'error';
                }
            }
        } else {
            if (move_uploaded_file($file['tmp_name'], $destPath)) {
                $message = 'File uploaded successfully.';
                $msgType = 'success';
            } else {
                $message = 'Failed to save file — check folder permissions.';
                $msgType = 'error';
            }
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
    <title>Media - Admin - <?= escape(SITE_NAME) ?></title>
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
        .grid-section-title { font-size: 0.9rem; color: #233d7e; margin: 28px 0 12px; text-transform: uppercase; letter-spacing: 0.5px; padding-bottom: 8px; border-bottom: 2px solid #F7941D; }
        .file-input-wrap { display: flex; flex-direction: column; gap: 4px; }
        .form-group input[type=file] { box-sizing: border-box; overflow: visible; width: auto !important; padding: 0 !important; border: none !important; }
        .btn-file { background: #233d7e; display: inline-block; padding: 10px 24px; border-radius: 8px; font-size: 0.85rem; font-weight: 600; color: #fff; }
        .btn-file:hover { background: #1a2f61; }
        .file-name { font-size: 0.85rem; color: #77797d; }
    </style>
</head>
<body>
<?php require_once __DIR__ . '/../includes/admin_sidebar.php'; ?>
    <div class="main">
        <div class="header-bar">
            <h1>Media</h1>
            <a href="dashboard.php">Back to Dashboard</a>
        </div>

        <?php if ($message): ?>
        <div class="msg <?= $msgType === 'success' ? 'msg-success' : 'msg-error' ?>"><?= escape($message) ?></div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data" class="upload-form">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <h3>Replace a File</h3>
            <div class="form-row">
                <div class="form-group">
                    <label for="target">Image to replace</label>
                    <select name="target" id="target" required>
                        <option value="">Select image...</option>
                        <optgroup label="Branding &amp; Logos">
                        <option value="logo-white.png">Header &amp; Footer Logo</option>
                        <option value="logo-blue.png">Admin Logo (light background)</option>
                        <option value="logo-original.png">Admin Login Logo (original)</option>
                        <option value="favicon.ico">Favicon (ICO)</option>
                        <option value="favicon.svg">Favicon (SVG)</option>
                        <option value="favicon-96x96.png">Favicon (96x96 PNG)</option>
                        </optgroup>
                        <optgroup label="Hero &amp; About">
                        <option value="hero.webp">Hero Background</option>
                        <option value="about.webp">About Section Image</option>
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
                        <optgroup label="Team &amp; Leadership">
                        <option value="team-1.webp">Team Member 1</option>
                        <option value="team-2.webp">Team Member 2</option>
                        <option value="team-3.webp">Team Member 3</option>
                        <option value="director.webp">Director Photo</option>
                        </optgroup>
                        <optgroup label="Legal &amp; Compliance">
                        <option value="certificate.webp">Certificate of Incorporation</option>
                        <option value="tra-registration.webp">TRA Registration</option>
                        </optgroup>
                        <optgroup label="Documents">
                        <option value="company-profile.pdf">Company Profile PDF</option>
                        </optgroup>
                    </select>
                </div>
                <div class="form-group">
                    <label id="fileLabel">File (max 250KB, WebP preferred)</label>
                    <div>
                        <input type="file" name="image" id="image" accept="image/webp,image/jpeg,image/png" required>
                        <span id="fileName" style="margin-left:8px;font-size:0.85rem;color:#6B7280"></span>
                    </div>
                    <script>
                    (function(){
                        var input = document.getElementById('image');
                        var target = document.getElementById('target');
                        var label = document.getElementById('fileLabel');
                        var name = document.getElementById('fileName');
                        var acceptMap = {
                            'favicon.ico': { accept: 'image/x-icon,.ico', label: 'File (max 250KB, ICO format)' },
                            'favicon.svg': { accept: 'image/svg+xml,.svg', label: 'File (max 250KB, SVG format)' },
                            'favicon-96x96.png': { accept: 'image/png,.png', label: 'File (max 250KB, PNG format)' },
                            'logo-white.png': { accept: 'image/png,.png', label: 'File (max 250KB, PNG format)' },
                            'logo-blue.png': { accept: 'image/png,.png', label: 'File (max 250KB, PNG format)' },
                            'logo-original.png': { accept: 'image/png,.png', label: 'File (max 250KB, PNG format)' },
                            'company-profile.pdf': { accept: 'application/pdf,.pdf', label: 'File (max 10MB, PDF format)' }
                        };
                        var defaults = { accept: 'image/webp,image/jpeg,image/png', label: 'File (max 250KB, WebP preferred)' };
                        function updateAccept() {
                            var cfg = acceptMap[target.value] || defaults;
                            input.accept = cfg.accept;
                            label.textContent = cfg.label;
                        }
                        target.addEventListener('change', updateAccept);
                        input.addEventListener('change', function(){
                            if (this.files && this.files.length > 0) {
                                name.textContent = this.files[0].name;
                            }
                        });
                    })();
                    </script>
                </div>
                <button type="submit" class="btn">Upload</button>
            </div>
        </form>

        <?php
        $categories = [
            'Branding &amp; Logos' => ['logo-', 'favicon'],
            'Hero &amp; About' => ['hero.', 'about.'],
            'Services' => ['service-'],
            'Team &amp; Leadership' => ['team-', 'director.'],
            'Legal &amp; Compliance' => ['certificate.', 'tra-registration.'],
        ];
        $grouped = [];
        $uncategorized = [];
        foreach ($images as $img) {
            $name = basename($img);
            $cat = null;
            foreach ($categories as $label => $prefixes) {
                foreach ($prefixes as $pfx) {
                    if (strpos($name, $pfx) === 0) { $cat = $label; break 2; }
                }
            }
            if ($cat) { $grouped[$cat][] = $img; } else { $uncategorized[] = $img; }
        }
        foreach ($categories as $label => $_):
            if (!empty($grouped[$label])):
        ?>
        <h3 class="grid-section-title"><?= $label ?></h3>
        <div class="grid">
            <?php foreach ($grouped[$label] as $img): $name = basename($img); $size = filesize($img); ?>
            <div class="image-card">
                <img src="../assets/images/<?= urlencode($name) ?>" alt="<?= escape($name) ?>" loading="lazy">
                <div class="name"><?= escape($name) ?></div>
                <div class="size"><?= round($size / 1024, 1) ?> KB</div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; endforeach; ?>
        <?php if (!empty($uncategorized)): ?>
        <h3 class="grid-section-title">Other</h3>
        <div class="grid">
            <?php foreach ($uncategorized as $img): $name = basename($img); $size = filesize($img); ?>
            <div class="image-card">
                <img src="../assets/images/<?= urlencode($name) ?>" alt="<?= escape($name) ?>" loading="lazy">
                <div class="name"><?= escape($name) ?></div>
                <div class="size"><?= round($size / 1024, 1) ?> KB</div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
