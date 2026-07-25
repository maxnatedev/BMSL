CREATE TABLE IF NOT EXISTS `contact_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `admin_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `admin_users` (`username`, `password_hash`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

CREATE TABLE IF NOT EXISTS `site_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_section` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_section` (`page_section`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `nav_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(100) NOT NULL,
  `href` varchar(255) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `nav_items` (`label`, `href`, `sort_order`) VALUES
('Home', '#home', 1),
('About', '#about', 2),
('Services', '#services', 3),
('Team', '#team', 4),
('Contact', '#contact', 5);

INSERT INTO `site_content` (`page_section`, `content`) VALUES
('hero_heading', 'Reliable Mining, Industrial & HSE Solutions'),
('hero_subtext', 'Your trusted partner in mining, industrial engineering, construction, and health, safety and environmental solutions across Tanzania.'),
('about_heading', 'About Brethren Mining Solution Limited'),
('about_text', 'Brethren Mining Solution Limited is a proudly Tanzanian company dedicated to providing professional mining, industrial, engineering, construction, and HSE solutions. We build reliable partnerships through customer-focused service and operational excellence.'),
('about_paragraph_2', 'With a deep understanding of the local landscape and a commitment to international standards, we deliver solutions that drive productivity, ensure safety, and create lasting value for our clients across Tanzania and beyond.'),
('about_stat_1_value','50+'),('about_stat_1_label','Projects Completed'),
('about_stat_2_value','8'),('about_stat_2_label','Service Lines'),
('about_stat_3_value','100%'),('about_stat_3_label','Client Commitment'),
('vision_heading', 'Our Vision'),
('vision_text', 'To be the leading provider of integrated mining and industrial solutions in Tanzania, setting the standard for quality, safety, and reliability.'),
('mission_heading', 'Our Mission'),
('mission_text', 'To deliver exceptional value to our clients through innovative solutions, unwavering commitment to safety, and a team of dedicated professionals.'),
('commitment_heading', 'Our Commitment'),
('commitment_text', 'We are committed to the highest standards of safety, quality, and environmental stewardship in every project we undertake. Our team works tirelessly to ensure that every client receives solutions that are not only effective but also sustainable and responsible.'),
('values_heading', 'Core Values'),
('values_desc', 'The principles that guide every decision and action we take.'),
('services_heading', 'Our Services'),
('services_subtitle', 'Comprehensive mining, industrial, and HSE solutions tailored to your needs.'),
('team_heading', 'Our Team'),
('team_subtitle', 'Experienced professionals dedicated to delivering excellence in every project.'),
('values_1', 'Integrity'),('values_2', 'Honesty'),('values_3', 'Ownership'),('values_4', 'Innovation'),('values_5', 'Safety'),('values_6', 'Teamwork'),
('why_1_title','Reliable Products'),('why_1_desc','We source and deliver only the highest quality products that meet rigorous industry standards.'),
('why_2_title','Professional Expertise'),('why_2_desc','Our team brings decades of combined experience in mining, engineering, and industrial services.'),
('why_3_title','Customer Focus'),('why_3_desc','Every solution is tailored to meet the unique needs and challenges of each client.'),
('why_4_title','Quality'),('why_4_desc','We maintain strict quality control processes across all our service deliveries and products.'),
('why_5_title','Timely Delivery'),('why_5_desc','We understand the value of time and consistently deliver projects on schedule.'),
('why_6_title','Long-Term Partnerships'),('why_6_desc','We build lasting relationships based on trust, transparency, and mutual growth.'),
('service_1_title','PPE Supply'),('service_1_desc','High-quality personal protective equipment sourced from trusted manufacturers to ensure worker safety.'),('service_1_modal','We supply a comprehensive range of personal protective equipment including helmets, gloves, safety boots, high-visibility clothing, respiratory protection, fall arrest systems, and more. All our products meet international safety standards and are sourced from reputable manufacturers.'),
('service_2_title','Maintenance'),('service_2_desc','Comprehensive maintenance services for mining and industrial equipment to maximize uptime and reliability.'),('service_2_modal','Our maintenance team provides scheduled and emergency maintenance for mining equipment, processing plants, industrial machinery, and facility infrastructure. We minimize downtime through proactive maintenance planning, rapid response times, and a team of skilled technicians.'),
('service_3_title','Construction'),('service_3_desc','Industrial and commercial construction services delivered with precision, safety, and efficiency.'),('service_3_modal','We deliver industrial and commercial construction projects from concept to completion. Our services include site preparation, foundation works, structural steel erection, concrete works, and project management.'),
('service_4_title','Fabrication'),('service_4_desc','Custom metal fabrication and welding services for industrial applications and infrastructure projects.'),('service_4_modal','Our fabrication workshop is equipped to handle custom metalwork including structural steel, piping systems, tanks, platforms, and specialized equipment. We use modern welding and cutting techniques to produce high-quality fabricated components.'),
('service_5_title','Electrical Installation'),('service_5_desc','Professional electrical installation, wiring, and maintenance services for industrial facilities.'),('service_5_modal','We provide complete electrical services for industrial facilities including power distribution, lighting systems, motor control centers, switchgear installation, and electrical maintenance.'),
('service_6_title','Branding'),('service_6_desc','Corporate branding and signage solutions that communicate your brand identity effectively.'),('service_6_modal','Our branding services help mining and industrial companies establish a strong visual identity. We design and install corporate signage, vehicle branding, safety signage, billboards, and promotional materials.'),
('service_7_title','HSE Consultancy'),('service_7_desc','Health, safety, and environmental consultancy services to ensure compliance and protect your workforce.'),('service_7_modal','Our HSE consultancy helps organizations achieve and maintain compliance with health, safety, and environmental regulations. We offer risk assessments, safety audits, training programs, environmental impact assessments, and HSE management system development.'),
('service_8_title','Mining Support'),('service_8_desc','Comprehensive mining support services including logistics, equipment supply, and operational assistance.'),('service_8_modal','We provide end-to-end mining support services including equipment sourcing and supply, logistics coordination, operational support, site management assistance, and workforce solutions.'),
('team_1_name','Eng. John Mwangi'),('team_1_role','Managing Director'),('team_1_exp','20+ years experience'),('team_1_bio','Leading BMSL with a vision for excellence in mining and industrial solutions across Tanzania.'),
('team_2_name','Sarah Lema'),('team_2_role','HSE Manager'),('team_2_exp','15+ years experience'),('team_2_bio','Ensuring the highest safety standards across all operations and client engagements.'),
('team_3_name','David Shayo'),('team_3_role','Operations Director'),('team_3_exp','18+ years experience'),('team_3_bio','Overseeing project delivery, logistics, and operational excellence across all service lines.'),
('hero_badge','Brethren Mining Solution Limited'),
('cta_1_label','Company Profile'),('cta_1_url','uploads/company-profile.pdf'),
('cta_2_label','Explore Services'),('cta_2_url','#services'),
('contact_address','Nyamongo, Tarime, Tanzania'),
('contact_phone','+255 762 784 531'),
('contact_email1','info@bmsl.co.tz'),
('contact_email2','enquiries@bmsl.co.tz'),
('contact_website','www.bmsl.co.tz'),
('footer_tagline','Your trusted partner in mining, industrial, engineering, construction, and HSE solutions across Tanzania.');
