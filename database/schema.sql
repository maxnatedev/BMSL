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

INSERT INTO `site_content` (`page_section`, `content`) VALUES
('hero_heading', 'Reliable Mining, Industrial & HSE Solutions'),
('hero_subtext', 'Your trusted partner in mining, industrial engineering, construction, and health, safety and environmental solutions across Tanzania.'),
('about_text', 'Brethren Mining Solution Limited is a proudly Tanzanian company dedicated to providing professional mining, industrial, engineering, construction, and HSE solutions. We build reliable partnerships through customer-focused service and operational excellence.'),
('vision_text', 'To be the leading provider of integrated mining and industrial solutions in Tanzania, setting the standard for quality, safety, and reliability.'),
('mission_text', 'To deliver exceptional value to our clients through innovative solutions, unwavering commitment to safety, and a team of dedicated professionals.'),
('commitment_text', 'We are committed to the highest standards of safety, quality, and environmental stewardship in every project we undertake. Our team works tirelessly to ensure that every client receives solutions that are not only effective but also sustainable and responsible.');
