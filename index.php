<?php
require_once __DIR__ . '/includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'contact') {
    header('Content-Type: application/json');
    try {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
            throw new \Exception('Invalid security token.');
        }
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $company = trim($_POST['company'] ?? '');
        $message = trim($_POST['message'] ?? '');
        if (strlen($name) < 2) throw new \Exception($content['form_error_name'] ?? 'Please enter your name.');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) throw new \Exception($content['form_error_email'] ?? 'Please enter a valid email.');
        if (strlen($message) < 10) throw new \Exception($content['form_error_message'] ?? 'Please enter at least 10 characters.');
        $rate_key = 'contact_' . $_SERVER['REMOTE_ADDR'];
        $attempts = $_SESSION[$rate_key] ?? 0;
        if ($attempts >= 3) throw new \Exception('Too many attempts. Please try later.');
        $_SESSION[$rate_key] = $attempts + 1;
        require_once __DIR__ . '/includes/database.php';
        $db = Database::getInstance();
        $db->insert('INSERT INTO contact_messages (name, email, phone, company, message) VALUES (?, ?, ?, ?, ?)', [$name, $email, $phone, $company, $message]);
        echo json_encode(['success' => true]);
    } catch (\Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}

$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

$content = [];
try {
    require_once __DIR__ . '/includes/database.php';
    $db = Database::getInstance();
    $rows = $db->fetchAll('SELECT page_section, content FROM site_content');
    foreach ($rows as $row) {
        $content[$row['page_section']] = $row['content'];
    }
} catch (\Exception $e) {
    // DB unavailable — use fallback hardcoded text
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="hero" id="home">
    <div class="hero-content">
        <span class="hero-badge"><?= escape($content['hero_badge'] ?? 'Brethren Mining Solution Limited') ?></span>
        <h1><?= escape($content['hero_heading'] ?? 'Reliable Mining, Industrial & HSE Solutions') ?></h1>
        <p><?= escape($content['hero_subtext'] ?? 'Your trusted partner in mining, industrial engineering, construction, and health, safety and environmental solutions across Tanzania.') ?></p>
        <div class="hero-buttons">
            <a href="<?= escape($content['cta_1_url'] ?? '#') ?>" class="btn btn-primary"><?= escape($content['cta_1_label'] ?? 'Company Profile') ?></a>
            <a href="<?= escape($content['cta_2_url'] ?? '#services') ?>" class="btn btn-outline"><?= escape($content['cta_2_label'] ?? 'Explore Services') ?></a>
        </div>
    </div>
    <div class="scroll-indicator" aria-hidden="true"></div>
</section>

<section class="section section-alt" id="director">
    <div class="container">
        <h2 class="section-title animate-in"><?= escape($content['director_heading'] ?? "Director's Message") ?></h2>
        <div class="director-grid">
            <div class="director-image animate-in-left">
                <img src="assets/images/director.webp" alt="<?= escape($content['director_name'] ?? 'Director') ?>" loading="lazy">
            </div>
            <div class="director-message animate-in-right">
                <?php foreach (explode('|||', $content['director_message'] ?? '') as $p): $t = trim($p); if ($t): ?>
                <p><?= escape($t) ?></p>
                <?php endif; endforeach; ?>
                <div class="director-signature">
                    <strong><?= escape($content['director_name'] ?? '') ?></strong>
                    <span><?= escape($content['director_title'] ?? '') ?></span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section" id="about">
    <div class="container">
        <div class="about-grid">
            <div class="about-image animate-in-left">
                <img src="assets/images/about.webp" alt="<?= escape($content['about_img_alt'] ?? 'About BMSL') ?>" loading="lazy">
            </div>
            <div class="about-content animate-in-right">
                <h2><?= escape($content['about_heading'] ?? 'About Brethren Mining Solution Limited') ?></h2>
                <p><?= escape($content['about_text'] ?? 'Brethren Mining Solution Limited is a proudly Tanzanian company dedicated to providing professional mining, industrial, engineering, construction, and HSE solutions.') ?></p>
                <p><?= escape($content['about_paragraph_2'] ?? 'With a deep understanding of the local landscape and a commitment to international standards, we deliver solutions that drive productivity, ensure safety, and create lasting value.') ?></p>
                <div class="about-stats">
                    <?php for ($i = 1; $i <= 3; $i++): ?>
                    <div class="about-stat"><div class="about-stat-number"><?= escape($content['about_stat_' . $i . '_value'] ?? '') ?></div><div class="about-stat-label"><?= escape($content['about_stat_' . $i . '_label'] ?? '') ?></div></div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section section-alt" id="vision">
    <div class="container">
        <h2 class="section-title animate-in"><?= escape($content['vmv_heading'] ?? 'Our Vision, Mission & Values') ?></h2>
        <p class="section-subtitle animate-in"><?= escape($content['vmv_subtitle'] ?? 'Guided by a clear purpose and strong principles that define who we are and how we operate.') ?></p>
        <div class="vmv-grid">
            <div class="vmv-card animate-scale">
                <div class="vmv-icon vision"><svg viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="16" cy="16" r="6"/><circle cx="16" cy="16" r="2"/><path d="M2 16s6-10 14-10 14 10 14 10-6 10-14 10S2 16 2 16z"/></svg></div>
                <h3><?= escape($content['vision_heading'] ?? 'Our Vision') ?></h3>
                <p><?= escape($content['vision_text'] ?? 'To be the leading provider of integrated mining and industrial solutions in Tanzania.') ?></p>
            </div>
            <div class="vmv-card animate-scale">
                <div class="vmv-icon mission"><svg viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="16" cy="16" r="12"/><circle cx="16" cy="16" r="4"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="16" y1="26" x2="16" y2="30"/><line x1="2" y1="16" x2="6" y2="16"/><line x1="26" y1="16" x2="30" y2="16"/></svg></div>
                <h3><?= escape($content['mission_heading'] ?? 'Our Mission') ?></h3>
                <p><?= escape($content['mission_text'] ?? 'To deliver exceptional value to our clients through innovative solutions.') ?></p>
            </div>
            <div class="vmv-card animate-scale">
                <div class="vmv-icon values"><svg viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polygon points="16,2 20,11 30,12 22,19 24,29 16,24 8,29 10,19 2,12 12,11"/></svg></div>
                <h3><?= escape($content['values_heading'] ?? 'Core Values') ?></h3>
                <p><?= escape($content['values_desc'] ?? 'The principles that guide every decision and action we take.') ?></p>
                <div class="values-list">
                    <?php for ($i = 1; $i <= 6; $i++): ?>
                    <span><?= escape($content['values_' . $i] ?? '') ?></span>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section" id="why-us">
    <div class="container">
        <h2 class="section-title animate-in"><?= escape($content['why_heading'] ?? 'Why Choose Us') ?></h2>
        <p class="section-subtitle animate-in"><?= escape($content['why_subtitle'] ?? 'What sets us apart in the mining and industrial solutions landscape.') ?></p>
        <div class="why-grid">
            <?php
            $whyIcons = ['shield','star','person','award','clock','link'];
            for ($i = 1; $i <= 6; $i++):
            $title = $content['why_' . $i . '_title'] ?? '';
            $desc = $content['why_' . $i . '_desc'] ?? '';
            if (!$title) continue;
            ?>
            <div class="why-card animate-scale">
                <div class="why-icon">
                    <?php if ($whyIcons[$i-1] === 'shield'): ?><svg viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M8 2h16l3 10-11 18-11-18 3-10z"/><polyline points="10,10 16,16 22,10"/></svg>
                    <?php elseif ($whyIcons[$i-1] === 'star'): ?><svg viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polygon points="16,2 20,11 30,12 22,19 24,29 16,24 8,29 10,19 2,12 12,11"/></svg>
                    <?php elseif ($whyIcons[$i-1] === 'person'): ?><svg viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="16" cy="10" r="6"/><path d="M4 30c0-8 4-12 12-12s12 4 12 12"/></svg>
                    <?php elseif ($whyIcons[$i-1] === 'award'): ?><svg viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polygon points="16,2 18,10 26,10 20,16 22,24 16,19 10,24 12,16 6,10 14,10"/></svg>
                    <?php elseif ($whyIcons[$i-1] === 'clock'): ?><svg viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="16" cy="16" r="12"/><polyline points="16,8 16,16 22,20"/></svg>
                    <?php elseif ($whyIcons[$i-1] === 'link'): ?><svg viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M10 22c-2-2-2-6 0-8l4-4c2-2 6-2 8 0s2 6 0 8l-2 2"/><path d="M22 10c2 2 2 6 0 8l-4 4c-2 2-6 2-8 0s-2-6 0-8l2-2"/></svg>
                    <?php endif; ?>
                </div>
                <h3><?= escape($title) ?></h3>
                <p><?= escape($desc) ?></p>
            </div>
            <?php endfor; ?>
        </div>
    </div>
</section>

<section class="section section-alt" id="services">
    <div class="container">
        <h2 class="section-title animate-in"><?= escape($content['services_heading'] ?? 'Our Services') ?></h2>
        <p class="section-subtitle animate-in"><?= escape($content['services_subtitle'] ?? 'Comprehensive mining, industrial, and HSE solutions tailored to your needs.') ?></p>
        <div class="services-grid">
            <?php for ($i = 1; $i <= 8; $i++):
            $title = $content['service_' . $i . '_title'] ?? '';
            $desc = $content['service_' . $i . '_desc'] ?? '';
            $modalKey = ['ppe','maintenance','construction','fabrication','electrical','branding','hse','mining'];
            if (!$title) continue;
            ?>
            <div class="service-card animate-scale">
                <img src="assets/images/service-<?= $modalKey[$i-1] ?>.webp" alt="<?= escape($title) ?>" class="service-image" loading="lazy">
                <div class="service-body">
                    <h3><?= escape($title) ?></h3>
                    <p><?= escape($desc) ?></p>
                    <button class="btn btn-primary btn-sm" data-modal="modal-<?= $modalKey[$i-1] ?>"><?= escape($content['read_more_text'] ?? 'Read More') ?></button>
                </div>
            </div>
            <?php endfor; ?>
        </div>
    </div>
</section>

<?php for ($i = 1; $i <= 8; $i++):
$title = $content['service_' . $i . '_title'] ?? '';
$modal = $content['service_' . $i . '_modal'] ?? '';
$modalKey = ['ppe','maintenance','construction','fabrication','electrical','branding','hse','mining'];
if (!$modal) continue;
?>
<div class="modal-overlay" id="modal-<?= $modalKey[$i-1] ?>">
    <div class="modal-content">
        <button class="modal-close" aria-label="Close">&times;</button>
        <h2><?= escape($title) ?></h2>
        <p><?= escape($modal) ?></p>
    </div>
</div>
<?php endfor; ?>

<section class="section" id="team">
    <div class="container">
        <h2 class="section-title animate-in"><?= escape($content['team_heading'] ?? 'Our Team') ?></h2>
        <p class="section-subtitle animate-in"><?= escape($content['team_subtitle'] ?? 'Experienced professionals dedicated to delivering excellence in every project.') ?></p>
        <div class="team-grid">
            <?php for ($i = 1; $i <= 3; $i++):
            $name = $content['team_' . $i . '_name'] ?? '';
            $role = $content['team_' . $i . '_role'] ?? '';
            $exp = $content['team_' . $i . '_exp'] ?? '';
            $bio = $content['team_' . $i . '_bio'] ?? '';
            if (!$name) continue;
            ?>
            <div class="team-card animate-scale">
                <img src="assets/images/team-<?= $i ?>.webp" alt="<?= escape($name) ?>" class="team-photo" loading="lazy">
                <div class="team-info">
                    <h3><?= escape($name) ?></h3>
                    <p class="team-role"><?= escape($role) ?></p>
                    <p class="team-exp"><?= escape($exp) ?></p>
                    <p class="team-bio"><?= escape($bio) ?></p>
                </div>
            </div>
            <?php endfor; ?>
        </div>
    </div>
</section>

<section class="commitment" id="commitment">
    <div class="container">
        <h2 class="animate-in"><?= escape($content['commitment_heading'] ?? 'Our Commitment') ?></h2>
        <p class="animate-in"><?= escape($content['commitment_text'] ?? 'We are committed to the highest standards of safety, quality, and environmental stewardship.') ?></p>
        <a href="<?= escape($content['commitment_cta_url'] ?? '#contact') ?>" class="btn btn-outline animate-in"><?= escape($content['commitment_cta_label'] ?? 'Partner With Us') ?></a>
    </div>
</section>

<section class="section section-alt" id="legal">
    <div class="container">
        <h2 class="section-title animate-in"><?= escape($content['legal_heading'] ?? 'Legal & Compliance') ?></h2>
        <p class="section-subtitle animate-in"><?= escape($content['legal_subtitle'] ?? 'We operate with full legal compliance and transparency.') ?></p>
        <div class="legal-grid">
            <div class="legal-card animate-scale" data-modal="modal-cert"><img src="assets/images/certificate.webp" alt="Certificate of Incorporation" loading="lazy"><p><?= escape($content['legal_card_1'] ?? 'Certificate of Incorporation') ?></p></div>
            <div class="legal-card animate-scale" data-modal="modal-tra"><img src="assets/images/tra-registration.webp" alt="TRA Registration" loading="lazy"><p><?= escape($content['legal_card_2'] ?? 'TRA Registration') ?></p></div>
        </div>
    </div>
</section>

<div class="modal-overlay" id="modal-cert"><div class="modal-content"><button class="modal-close" aria-label="Close">&times;</button><h2><?= escape($content['legal_card_1'] ?? 'Certificate of Incorporation') ?></h2><img src="assets/images/certificate.webp" alt="<?= escape($content['legal_cert_alt'] ?? 'Certificate of Incorporation') ?>" loading="lazy"></div></div>
<div class="modal-overlay" id="modal-tra"><div class="modal-content"><button class="modal-close" aria-label="Close">&times;</button><h2><?= escape($content['legal_card_2'] ?? 'TRA Registration') ?></h2><img src="assets/images/tra-registration.webp" alt="<?= escape($content['legal_tra_alt'] ?? 'TRA Registration') ?>" loading="lazy"></div></div>

<section class="section" id="contact">
    <div class="container">
        <h2 class="section-title animate-in"><?= escape($content['contact_heading'] ?? 'Get In Touch') ?></h2>
        <p class="section-subtitle animate-in"><?= escape($content['contact_subtitle'] ?? 'Ready to start your next project? Contact us today for a consultation.') ?></p>
        <div class="contact-grid">
            <div class="contact-info animate-in-left">
                <h3><?= escape($content['contact_info_heading'] ?? 'Contact Information') ?></h3>
                <div class="contact-detail"><span class="contact-detail-icon">&#9906;</span><div><h4>Address</h4><p><?= escape($content['contact_address'] ?? 'Nyamongo, Tarime, Tanzania') ?></p></div></div>
                <div class="contact-detail"><span class="contact-detail-icon">&#9742;</span><div><h4>Phone</h4><p><a href="tel:<?= escape($content['contact_phone'] ?? '') ?>"><?= escape($content['contact_phone'] ?? '+255 762 784 531') ?></a></p></div></div>
                <div class="contact-detail"><span class="contact-detail-icon">&#9993;</span><div><h4>Email</h4><p><a href="mailto:<?= escape($content['contact_email1'] ?? 'info@bmsl.co.tz') ?>"><?= escape($content['contact_email1'] ?? 'info@bmsl.co.tz') ?></a><?php if (!empty($content['contact_email2'])): ?><br><a href="mailto:<?= escape($content['contact_email2']) ?>"><?= escape($content['contact_email2']) ?></a><?php endif; ?></p></div></div>
                <div class="contact-detail"><span class="contact-detail-icon">&#127760;</span><div><h4>Website</h4><p><a href="https://<?= escape($content['contact_website'] ?? 'bmsl.co.tz') ?>"><?= escape($content['contact_website'] ?? 'www.bmsl.co.tz') ?></a></p></div></div>
                <iframe class="map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.654!2d34.0!3d-1.0!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMcKwMDAnMDAuMCJTIDM0wrAwMCcwMC4wIkU!5e0!3m2!1sen!2stz!4v1" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="BMSL Location"></iframe>
            </div>
            <div class="animate-in-right">
                <div class="form-card">
                    <h3><?= escape($content['form_heading'] ?? 'Send Us a Message') ?></h3>
                    <div class="form-success" id="formSuccess"></div>
                    <form id="contactForm" novalidate>
                        <input type="hidden" name="csrf_token" id="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        <div class="form-group"><label for="form_name"><?= escape($content['form_label_name'] ?? 'Name *') ?></label><input type="text" id="form_name" name="name" placeholder="<?= escape($content['form_placeholder_name'] ?? 'Your full name') ?>" required><div class="form-error" id="error_name"><?= escape($content['form_error_name'] ?? 'Please enter your name.') ?></div></div>
                        <div class="form-group"><label for="form_email"><?= escape($content['form_label_email'] ?? 'Email *') ?></label><input type="email" id="form_email" name="email" placeholder="<?= escape($content['form_placeholder_email'] ?? 'your@email.com') ?>" required><div class="form-error" id="error_email"><?= escape($content['form_error_email'] ?? 'Please enter a valid email.') ?></div></div>
                        <div class="form-group"><label for="form_phone"><?= escape($content['form_label_phone'] ?? 'Phone') ?></label><input type="tel" id="form_phone" name="phone" placeholder="<?= escape($content['form_placeholder_phone'] ?? '+255 XXX XXX XXX') ?>"><div class="form-error" id="error_phone"><?= escape($content['form_error_phone'] ?? 'Please enter a valid phone number.') ?></div></div>
                        <div class="form-group"><label for="form_company"><?= escape($content['form_label_company'] ?? 'Company') ?></label><input type="text" id="form_company" name="company" placeholder="<?= escape($content['form_placeholder_company'] ?? 'Your company name') ?>"></div>
                        <div class="form-group"><label for="form_message"><?= escape($content['form_label_message'] ?? 'Message *') ?></label><textarea id="form_message" name="message" placeholder="<?= escape($content['form_placeholder_message'] ?? 'Tell us about your project...') ?>" required></textarea><div class="form-error" id="error_message"><?= escape($content['form_error_message'] ?? 'Please enter at least 10 characters.') ?></div></div>
                        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;"><?= escape($content['form_submit_text'] ?? 'Send Message') ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
