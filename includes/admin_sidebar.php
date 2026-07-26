<?php
$current = basename($_SERVER['PHP_SELF']);
?>
<div class="sidebar">
    <div class="sidebar-logo"><img src="../assets/images/logo-white.png" alt="<?= escape(SITE_NAME) ?>"></div>
    <nav class="sidebar-nav">
        <a href="dashboard.php" class="sidebar-item <?= $current === 'dashboard.php' ? 'active' : '' ?>">
            <span class="sidebar-icon">◉</span> Dashboard
        </a>
        <div class="sidebar-group">
            <div class="sidebar-group-title">Content</div>
            <a href="content.php#section-general" class="sidebar-item sidebar-sub <?= $current === 'content.php' ? 'active' : '' ?>" data-section="general">
                <span class="sidebar-icon">◆</span> General
            </a>
            <a href="content.php#section-values" class="sidebar-item sidebar-sub" data-section="values">
                <span class="sidebar-icon">◇</span> Values & Why
            </a>
            <a href="content.php#section-services" class="sidebar-item sidebar-sub" data-section="services">
                <span class="sidebar-icon">▲</span> Services
            </a>
            <a href="content.php#section-team" class="sidebar-item sidebar-sub" data-section="team">
                <span class="sidebar-icon">●</span> Team
            </a>
            <a href="content.php#section-contact" class="sidebar-item sidebar-sub" data-section="contact">
                <span class="sidebar-icon">■</span> Contact & Footer
            </a>
        </div>
        <a href="images.php" class="sidebar-item <?= $current === 'images.php' ? 'active' : '' ?>">
            <span class="sidebar-icon">▣</span> Media
        </a>
        <div class="sidebar-spacer"></div>
        <a href="../" class="sidebar-item">
            <span class="sidebar-icon">◁</span> View Site
        </a>
        <a href="logout.php" class="sidebar-item sidebar-logout">
            <span class="sidebar-icon">✕</span> Logout
        </a>
    </nav>
</div>
