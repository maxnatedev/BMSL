    </main>
    <footer class="footer">
        <div class="footer-inner container">
            <div class="footer-grid">
                <div class="footer-col">
                    <img src="assets/images/logo-white.png" alt="<?= escape(SITE_NAME) ?>" width="200" height="50" class="footer-logo">
                    <p class="footer-desc"><?= escape($content['footer_tagline'] ?? 'Your trusted partner in mining, industrial, engineering, construction, and HSE solutions across Tanzania.') ?></p>
                </div>
                <div class="footer-col">
                    <h4 class="footer-heading"><?= escape($content['footer_heading_quicklinks'] ?? 'Quick Links') ?></h4>
                    <ul class="footer-links">
                        <?php foreach ($navItems ?? [] as $n): ?>
                        <li><a href="<?= escape($n['href']) ?>"><?= escape($n['label']) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4 class="footer-heading"><?= escape($content['footer_heading_services'] ?? 'Services') ?></h4>
                    <ul class="footer-links">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                        <li><a href="#services"><?= escape($content['service_' . $i . '_title'] ?? '') ?></a></li>
                        <?php endfor; ?>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4 class="footer-heading"><?= escape($content['footer_heading_contact'] ?? 'Contact') ?></h4>
                    <ul class="footer-links">
                        <li><?= escape($content['contact_address'] ?? 'Nyamongo, Tarime, Tanzania') ?></li>
                        <li><a href="tel:<?= escape($content['contact_phone'] ?? '') ?>"><?= escape($content['contact_phone'] ?? '+255 762 784 531') ?></a></li>
                        <li><a href="mailto:<?= escape($content['contact_email1'] ?? '') ?>"><?= escape($content['contact_email1'] ?? 'info@bmsl.co.tz') ?></a></li>
                    </ul>
                    <div class="footer-social">
                        <a href="<?= escape($content['social_url_facebook'] ?? '#') ?>" aria-label="Facebook" class="social-link">FB</a>
                        <a href="<?= escape($content['social_url_linkedin'] ?? '#') ?>" aria-label="LinkedIn" class="social-link">LI</a>
                        <a href="<?= escape($content['social_url_twitter'] ?? '#') ?>" aria-label="Twitter" class="social-link">TW</a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p><?= escape($content['footer_copyright'] ?? '© ' . date('Y') . ' ' . SITE_NAME . '. All rights reserved.') ?> &nbsp;|&nbsp; <a href="<?= escape($content['footer_credit_url'] ?? 'https://maxnate.com') ?>" target="_blank" rel="noopener" style="color:#F7941D;text-decoration:none;"><?= escape($content['footer_credit_text'] ?? 'Built by MAXNATE') ?></a></p>
            </div>
        </div>
        <button class="back-to-top" id="backToTop" aria-label="<?= escape($content['back_to_top_label'] ?? 'Back to top') ?>">&#8593;</button>
    </footer>
    <script>
    window.BMSL_FORM = {
        successText: <?= json_encode($content['form_success_text'] ?? 'Thank you! Your message has been sent successfully. We will get back to you shortly.') ?>,
        genericError: <?= json_encode($content['form_error_generic'] ?? 'Something went wrong. Please try again.') ?>,
        networkError: <?= json_encode($content['form_error_network'] ?? 'Network error. Please try again.') ?>,
        sendingText: <?= json_encode($content['form_sending_text'] ?? 'Sending...') ?>,
        submitText: <?= json_encode($content['form_submit_text'] ?? 'Send Message') ?>
    };
    </script>
    <script src="assets/js/script.js?v=<?= filemtime(__DIR__ . '/../assets/js/script.js') ?>" defer></script>
</body>
</html>
