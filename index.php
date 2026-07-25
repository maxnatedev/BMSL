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

        if (strlen($name) < 2) throw new \Exception('Name is required.');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) throw new \Exception('Valid email is required.');
        if (strlen($message) < 10) throw new \Exception('Message must be at least 10 characters.');

        $rate_key = 'contact_' . $_SERVER['REMOTE_ADDR'];
        $attempts = $_SESSION[$rate_key] ?? 0;
        if ($attempts >= 3) throw new \Exception('Too many attempts. Please try later.');
        $_SESSION[$rate_key] = $attempts + 1;

        require_once __DIR__ . '/includes/database.php';
        $db = Database::getInstance();
        $db->insert(
            'INSERT INTO contact_messages (name, email, phone, company, message) VALUES (?, ?, ?, ?, ?)',
            [$name, $email, $phone, $company, $message]
        );

        echo json_encode(['success' => true]);
    } catch (\Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}

$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

require_once __DIR__ . '/includes/header.php';
?>

<section class="hero" id="home">
    <div class="hero-content">
        <span class="hero-badge">Brethren Mining Solution Limited</span>
        <h1>Reliable Mining, Industrial & HSE Solutions</h1>
        <p>Your trusted partner in mining, industrial engineering, construction, and health, safety and environmental solutions across Tanzania.</p>
        <div class="hero-buttons">
            <a href="#contact" class="btn btn-primary">Get Quote</a>
            <a href="#services" class="btn btn-outline">Explore Services</a>
        </div>
    </div>
    <div class="scroll-indicator" aria-hidden="true"></div>
</section>

<section class="section" id="about">
    <div class="container">
        <div class="about-grid">
            <div class="about-image animate-in-left">
                <img src="assets/images/about.webp" alt="About BMSL" loading="lazy">
            </div>
            <div class="about-content animate-in-right">
                <h2>About Brethren Mining Solution Limited</h2>
                <p>Brethren Mining Solution Limited is a proudly Tanzanian company dedicated to providing professional mining, industrial, engineering, construction, and HSE solutions. We build reliable partnerships through customer-focused service and operational excellence.</p>
                <p>With a deep understanding of the local landscape and a commitment to international standards, we deliver solutions that drive productivity, ensure safety, and create lasting value for our clients across Tanzania and beyond.</p>
                <div class="about-stats">
                    <div class="about-stat">
                        <div class="about-stat-number">50+</div>
                        <div class="about-stat-label">Projects Completed</div>
                    </div>
                    <div class="about-stat">
                        <div class="about-stat-number">8</div>
                        <div class="about-stat-label">Service Lines</div>
                    </div>
                    <div class="about-stat">
                        <div class="about-stat-number">100%</div>
                        <div class="about-stat-label">Client Commitment</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section section-alt" id="vision">
    <div class="container">
        <h2 class="section-title animate-in">Our Vision, Mission & Values</h2>
        <p class="section-subtitle animate-in">Guided by a clear purpose and strong principles that define who we are and how we operate.</p>
        <div class="vmv-grid">
            <div class="vmv-card animate-scale">
                <div class="vmv-icon vision"><svg viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="16" cy="16" r="6"/><circle cx="16" cy="16" r="2"/><path d="M2 16s6-10 14-10 14 10 14 10-6 10-14 10S2 16 2 16z"/></svg></div>
                <h3>Our Vision</h3>
                <p>To be the leading provider of integrated mining and industrial solutions in Tanzania, setting the standard for quality, safety, and reliability.</p>
            </div>
            <div class="vmv-card animate-scale">
                <div class="vmv-icon mission"><svg viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="16" cy="16" r="12"/><circle cx="16" cy="16" r="4"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="16" y1="26" x2="16" y2="30"/><line x1="2" y1="16" x2="6" y2="16"/><line x1="26" y1="16" x2="30" y2="16"/></svg></div>
                <h3>Our Mission</h3>
                <p>To deliver exceptional value to our clients through innovative solutions, unwavering commitment to safety, and a team of dedicated professionals.</p>
            </div>
            <div class="vmv-card animate-scale">
                <div class="vmv-icon values"><svg viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polygon points="16,2 20,11 30,12 22,19 24,29 16,24 8,29 10,19 2,12 12,11"/></svg></div>
                <h3>Core Values</h3>
                <p>The principles that guide every decision and action we take.</p>
                <div class="values-list">
                    <span>Integrity</span>
                    <span>Honesty</span>
                    <span>Ownership</span>
                    <span>Innovation</span>
                    <span>Safety</span>
                    <span>Teamwork</span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section" id="why-us">
    <div class="container">
        <h2 class="section-title animate-in">Why Choose Us</h2>
        <p class="section-subtitle animate-in">What sets us apart in the mining and industrial solutions landscape.</p>
        <div class="why-grid">
            <div class="why-card animate-scale"><div class="why-icon"><svg viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M8 2h16l3 10-11 18-11-18 3-10z"/><polyline points="10,10 16,16 22,10"/></svg></div><h3>Reliable Products</h3><p>We source and deliver only the highest quality products that meet rigorous industry standards.</p></div>
            <div class="why-card animate-scale"><div class="why-icon"><svg viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polygon points="16,2 20,11 30,12 22,19 24,29 16,24 8,29 10,19 2,12 12,11"/></svg></div><h3>Professional Expertise</h3><p>Our team brings decades of combined experience in mining, engineering, and industrial services.</p></div>
            <div class="why-card animate-scale"><div class="why-icon"><svg viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="16" cy="10" r="6"/><path d="M4 30c0-8 4-12 12-12s12 4 12 12"/></svg></div><h3>Customer Focus</h3><p>Every solution is tailored to meet the unique needs and challenges of each client.</p></div>
            <div class="why-card animate-scale"><div class="why-icon"><svg viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polygon points="16,2 18,10 26,10 20,16 22,24 16,19 10,24 12,16 6,10 14,10"/></svg></div><h3>Quality</h3><p>We maintain strict quality control processes across all our service deliveries and products.</p></div>
            <div class="why-card animate-scale"><div class="why-icon"><svg viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="16" cy="16" r="12"/><polyline points="16,8 16,16 22,20"/></svg></div><h3>Timely Delivery</h3><p>We understand the value of time and consistently deliver projects on schedule.</p></div>
            <div class="why-card animate-scale"><div class="why-icon"><svg viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M10 22c-2-2-2-6 0-8l4-4c2-2 6-2 8 0s2 6 0 8l-2 2"/><path d="M22 10c2 2 2 6 0 8l-4 4c-2 2-6 2-8 0s-2-6 0-8l2-2"/></svg></div><h3>Long-Term Partnerships</h3><p>We build lasting relationships based on trust, transparency, and mutual growth.</p></div>
        </div>
    </div>
</section>

<section class="section section-alt" id="services">
    <div class="container">
        <h2 class="section-title animate-in">Our Services</h2>
        <p class="section-subtitle animate-in">Comprehensive mining, industrial, and HSE solutions tailored to your needs.</p>
        <div class="services-grid">
            <div class="service-card animate-scale">
                <img src="assets/images/service-ppe.webp" alt="PPE Supply" class="service-image" loading="lazy">
                <div class="service-body">
                    <h3>PPE Supply</h3>
                    <p>High-quality personal protective equipment sourced from trusted manufacturers to ensure worker safety.</p>
                    <button class="btn btn-primary btn-sm" data-modal="modal-ppe">Read More</button>
                </div>
            </div>
            <div class="service-card animate-scale">
                <img src="assets/images/service-maintenance.webp" alt="Maintenance" class="service-image" loading="lazy">
                <div class="service-body">
                    <h3>Maintenance</h3>
                    <p>Comprehensive maintenance services for mining and industrial equipment to maximize uptime and reliability.</p>
                    <button class="btn btn-primary btn-sm" data-modal="modal-maintenance">Read More</button>
                </div>
            </div>
            <div class="service-card animate-scale">
                <img src="assets/images/service-construction.webp" alt="Construction" class="service-image" loading="lazy">
                <div class="service-body">
                    <h3>Construction</h3>
                    <p>Industrial and commercial construction services delivered with precision, safety, and efficiency.</p>
                    <button class="btn btn-primary btn-sm" data-modal="modal-construction">Read More</button>
                </div>
            </div>
            <div class="service-card animate-scale">
                <img src="assets/images/service-fabrication.webp" alt="Fabrication" class="service-image" loading="lazy">
                <div class="service-body">
                    <h3>Fabrication</h3>
                    <p>Custom metal fabrication and welding services for industrial applications and infrastructure projects.</p>
                    <button class="btn btn-primary btn-sm" data-modal="modal-fabrication">Read More</button>
                </div>
            </div>
            <div class="service-card animate-scale">
                <img src="assets/images/service-electrical.webp" alt="Electrical Installation" class="service-image" loading="lazy">
                <div class="service-body">
                    <h3>Electrical Installation</h3>
                    <p>Professional electrical installation, wiring, and maintenance services for industrial facilities.</p>
                    <button class="btn btn-primary btn-sm" data-modal="modal-electrical">Read More</button>
                </div>
            </div>
            <div class="service-card animate-scale">
                <img src="assets/images/service-branding.webp" alt="Branding" class="service-image" loading="lazy">
                <div class="service-body">
                    <h3>Branding</h3>
                    <p>Corporate branding and signage solutions that communicate your brand identity effectively.</p>
                    <button class="btn btn-primary btn-sm" data-modal="modal-branding">Read More</button>
                </div>
            </div>
            <div class="service-card animate-scale">
                <img src="assets/images/service-hse.webp" alt="HSE Consultancy" class="service-image" loading="lazy">
                <div class="service-body">
                    <h3>HSE Consultancy</h3>
                    <p>Health, safety, and environmental consultancy services to ensure compliance and protect your workforce.</p>
                    <button class="btn btn-primary btn-sm" data-modal="modal-hse">Read More</button>
                </div>
            </div>
            <div class="service-card animate-scale">
                <img src="assets/images/service-mining.webp" alt="Mining Support" class="service-image" loading="lazy">
                <div class="service-body">
                    <h3>Mining Support</h3>
                    <p>Comprehensive mining support services including logistics, equipment supply, and operational assistance.</p>
                    <button class="btn btn-primary btn-sm" data-modal="modal-mining">Read More</button>
                </div>
            </div>
        </div>
    </div>
</section>

<?php $services_detail = [
    'ppe' => ['PPE Supply', 'We supply a comprehensive range of personal protective equipment including helmets, gloves, safety boots, high-visibility clothing, respiratory protection, fall arrest systems, and more. All our products meet international safety standards and are sourced from reputable manufacturers. We conduct regular quality checks to ensure every item delivers the protection your workforce deserves.'],
    'maintenance' => ['Maintenance Services', 'Our maintenance team provides scheduled and emergency maintenance for mining equipment, processing plants, industrial machinery, and facility infrastructure. We minimize downtime through proactive maintenance planning, rapid response times, and a team of skilled technicians with extensive experience in the mining and industrial sectors.'],
    'construction' => ['Construction Services', 'We deliver industrial and commercial construction projects from concept to completion. Our services include site preparation, foundation works, structural steel erection, concrete works, and project management. Every project is executed with strict adherence to safety protocols and quality standards, ensuring durable and reliable infrastructure.'],
    'fabrication' => ['Fabrication Services', 'Our fabrication workshop is equipped to handle custom metalwork including structural steel, piping systems, tanks, platforms, and specialized equipment. We use modern welding and cutting techniques to produce high-quality fabricated components that meet exact specifications and industry standards.'],
    'electrical' => ['Electrical Installation', 'We provide complete electrical services for industrial facilities including power distribution, lighting systems, motor control centers, switchgear installation, and electrical maintenance. Our electricians are certified and experienced in handling complex industrial electrical systems safely and efficiently.'],
    'branding' => ['Branding & Signage', 'Our branding services help mining and industrial companies establish a strong visual identity. We design and install corporate signage, vehicle branding, safety signage, billboards, and promotional materials. Our solutions are durable, weather-resistant, and designed to make your brand stand out.'],
    'hse' => ['HSE Consultancy', 'Our HSE consultancy helps organizations achieve and maintain compliance with health, safety, and environmental regulations. We offer risk assessments, safety audits, training programs, environmental impact assessments, and HSE management system development. We are committed to creating safer workplaces across Tanzania.'],
    'mining' => ['Mining Support', 'We provide end-to-end mining support services including equipment sourcing and supply, logistics coordination, operational support, site management assistance, and workforce solutions. Our deep understanding of the mining industry allows us to deliver practical, effective support that keeps your operations running smoothly.'],
]; ?>

<?php foreach ($services_detail as $key => $detail): ?>
<div class="modal-overlay" id="modal-<?= $key ?>">
    <div class="modal-content">
        <button class="modal-close" aria-label="Close">&times;</button>
        <h2><?= escape($detail[0]) ?></h2>
        <p><?= escape($detail[1]) ?></p>
    </div>
</div>
<?php endforeach; ?>

<section class="section" id="team">
    <div class="container">
        <h2 class="section-title animate-in">Our Team</h2>
        <p class="section-subtitle animate-in">Experienced professionals dedicated to delivering excellence in every project.</p>
        <div class="team-grid">
            <div class="team-card animate-scale">
                <img src="assets/images/team-1.webp" alt="Team Member" class="team-photo" loading="lazy">
                <div class="team-info">
                    <h3>Eng. John Mwangi</h3>
                    <p class="team-role">Managing Director</p>
                    <p class="team-exp">20+ years experience</p>
                    <p class="team-bio">Leading BMSL with a vision for excellence in mining and industrial solutions across Tanzania.</p>
                </div>
            </div>
            <div class="team-card animate-scale">
                <img src="assets/images/team-2.webp" alt="Team Member" class="team-photo" loading="lazy">
                <div class="team-info">
                    <h3>Sarah Lema</h3>
                    <p class="team-role">HSE Manager</p>
                    <p class="team-exp">15+ years experience</p>
                    <p class="team-bio">Ensuring the highest safety standards across all operations and client engagements.</p>
                </div>
            </div>
            <div class="team-card animate-scale">
                <img src="assets/images/team-3.webp" alt="Team Member" class="team-photo" loading="lazy">
                <div class="team-info">
                    <h3>David Shayo</h3>
                    <p class="team-role">Operations Director</p>
                    <p class="team-exp">18+ years experience</p>
                    <p class="team-bio">Overseeing project delivery, logistics, and operational excellence across all service lines.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="commitment" id="commitment">
    <div class="container">
        <h2 class="animate-in">Our Commitment</h2>
        <p class="animate-in">We are committed to the highest standards of safety, quality, and environmental stewardship in every project we undertake. Our team works tirelessly to ensure that every client receives solutions that are not only effective but also sustainable and responsible.</p>
        <a href="#contact" class="btn btn-outline animate-in">Partner With Us</a>
    </div>
</section>

<section class="section section-alt" id="legal">
    <div class="container">
        <h2 class="section-title animate-in">Legal & Compliance</h2>
        <p class="section-subtitle animate-in">We operate with full legal compliance and transparency. Click to view our certifications.</p>
        <div class="legal-grid">
            <div class="legal-card animate-scale" data-modal="modal-cert">
                <img src="assets/images/certificate.webp" alt="Certificate of Incorporation" loading="lazy">
                <p>Certificate of Incorporation</p>
            </div>
            <div class="legal-card animate-scale" data-modal="modal-tra">
                <img src="assets/images/tra-registration.webp" alt="TRA Registration" loading="lazy">
                <p>TRA Registration</p>
            </div>
        </div>
    </div>
</section>

<div class="modal-overlay" id="modal-cert">
    <div class="modal-content">
        <button class="modal-close" aria-label="Close">&times;</button>
        <h2>Certificate of Incorporation</h2>
        <img src="assets/images/certificate.webp" alt="Certificate of Incorporation" loading="lazy">
    </div>
</div>
<div class="modal-overlay" id="modal-tra">
    <div class="modal-content">
        <button class="modal-close" aria-label="Close">&times;</button>
        <h2>TRA Registration</h2>
        <img src="assets/images/tra-registration.webp" alt="TRA Registration" loading="lazy">
    </div>
</div>

<section class="section" id="contact">
    <div class="container">
        <h2 class="section-title animate-in">Get In Touch</h2>
        <p class="section-subtitle animate-in">Ready to start your next project? Contact us today for a consultation.</p>
        <div class="contact-grid">
            <div class="contact-info animate-in-left">
                <h3>Contact Information</h3>
                <div class="contact-detail">
                    <span class="contact-detail-icon">&#9906;</span>
                    <div><h4>Address</h4><p>Nyamongo, Tarime, Tanzania</p></div>
                </div>
                <div class="contact-detail">
                    <span class="contact-detail-icon">&#9742;</span>
                    <div><h4>Phone</h4><p><a href="tel:+255762784531">+255 762 784 531</a></p></div>
                </div>
                <div class="contact-detail">
                    <span class="contact-detail-icon">&#9993;</span>
                    <div><h4>Email</h4><p><a href="mailto:info@bmsl.co.tz">info@bmsl.co.tz</a><br><a href="mailto:enquiries@bmsl.co.tz">enquiries@bmsl.co.tz</a></p></div>
                </div>
                <div class="contact-detail">
                    <span class="contact-detail-icon">&#127760;</span>
                    <div><h4>Website</h4><p><a href="https://bmsl.co.tz">www.bmsl.co.tz</a></p></div>
                </div>
                <iframe class="map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.654!2d34.0!3d-1.0!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMcKwMDAnMDAuMCJTIDM0wrAwMCcwMC4wIkU!5e0!3m2!1sen!2stz!4v1" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="BMSL Location"></iframe>
            </div>
            <div class="animate-in-right">
                <div class="form-card">
                    <h3>Send Us a Message</h3>
                    <div class="form-success" id="formSuccess"></div>
                    <form id="contactForm" novalidate>
                        <input type="hidden" name="csrf_token" id="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        <div class="form-group">
                            <label for="form_name">Name *</label>
                            <input type="text" id="form_name" name="name" placeholder="Your full name" required>
                            <div class="form-error" id="error_name">Please enter your name.</div>
                        </div>
                        <div class="form-group">
                            <label for="form_email">Email *</label>
                            <input type="email" id="form_email" name="email" placeholder="your@email.com" required>
                            <div class="form-error" id="error_email">Please enter a valid email.</div>
                        </div>
                        <div class="form-group">
                            <label for="form_phone">Phone</label>
                            <input type="tel" id="form_phone" name="phone" placeholder="+255 XXX XXX XXX">
                            <div class="form-error" id="error_phone">Please enter a valid phone number.</div>
                        </div>
                        <div class="form-group">
                            <label for="form_company">Company</label>
                            <input type="text" id="form_company" name="company" placeholder="Your company name">
                        </div>
                        <div class="form-group">
                            <label for="form_message">Message *</label>
                            <textarea id="form_message" name="message" placeholder="Tell us about your project..." required></textarea>
                            <div class="form-error" id="error_message">Please enter at least 10 characters.</div>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
