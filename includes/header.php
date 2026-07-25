<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= escape(SITE_NAME) ?> | Reliable Mining, Industrial & HSE Solutions</title>
    <meta name="description" content="Brethren Mining Solution Limited – Your trusted partner in mining, industrial engineering, construction, and HSE solutions across Tanzania.">
    <meta property="og:title" content="<?= escape(SITE_NAME) ?>">
    <meta property="og:description" content="Reliable mining, industrial & HSE solutions in Tanzania.">
    <meta property="og:url" content="<?= escape(SITE_URL) ?>">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="canonical" href="<?= escape(SITE_URL) ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">
    <link rel="icon" href="assets/images/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="assets/images/favicon-96x96.png">
</head>
<body>
    <header class="header" id="header">
        <div class="header-inner container">
            <a href="#" class="logo">
                <img src="assets/images/logo-white.png" alt="<?= escape(SITE_NAME) ?>" width="220" height="55">
            </a>
            <button class="hamburger" id="hamburger" aria-label="Toggle navigation menu">
                <span></span><span></span><span></span>
            </button>
            <nav class="nav" id="nav" role="navigation" aria-label="Main navigation">
                <ul class="nav-list">
                    <?php
                    $navItems = [];
                    try {
                        if (!isset($db)) {
                            require_once __DIR__ . '/database.php';
                            $db = Database::getInstance();
                        }
                        $navItems = $db->fetchAll('SELECT * FROM nav_items WHERE is_active = 1 ORDER BY sort_order');
                    } catch (\Exception $e) {}
                    if (empty($navItems)):
                    ?>
                    <li><a href="#home" class="nav-link">Home</a></li>
                    <li><a href="#about" class="nav-link">About</a></li>
                    <li><a href="#services" class="nav-link">Services</a></li>
                    <li><a href="#team" class="nav-link">Team</a></li>
                    <li><a href="#contact" class="nav-link">Contact</a></li>
                    <?php else: foreach ($navItems as $n): ?>
                    <li><a href="<?= escape($n['href']) ?>" class="nav-link" data-section="<?= ltrim(escape($n['href']), '#') ?>"><?= escape($n['label']) ?></a></li>
                    <?php endforeach; endif; ?>
                </ul>
                <div class="nav-cta">
                    <a href="#contact" class="btn btn-primary btn-sm">Get Quote</a>
                </div>
            </nav>
            <div class="header-social">
                <a href="#" class="header-social-link" aria-label="Facebook"><svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg></a>
                <a href="#" class="header-social-link" aria-label="LinkedIn"><svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg></a>
                <a href="#" class="header-social-link" aria-label="Twitter"><svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"/></svg></a>
            </div>
        </div>
    </header>
    <div class="nav-backdrop" id="navBackdrop"></div>
    <main>
