-- Contact info labels (editable h4 headings in contact section)
INSERT INTO `site_content` (`page_section`, `content`) VALUES
('contact_label_address', 'Address'),
('contact_label_phone', 'Phone'),
('contact_label_email', 'Email'),
('contact_label_website', 'Website')
ON DUPLICATE KEY UPDATE content = VALUES(content);

-- Social media URLs (header + footer)
INSERT INTO `site_content` (`page_section`, `content`) VALUES
('social_url_facebook', '#'),
('social_url_linkedin', '#'),
('social_url_twitter', '#')
ON DUPLICATE KEY UPDATE content = VALUES(content);

-- Footer tagline & copyright (used in footer but missing from original schema)
INSERT INTO `site_content` (`page_section`, `content`) VALUES
('footer_tagline', 'Your trusted partner in mining, industrial, engineering, construction, and HSE solutions across Tanzania.'),
('footer_copyright', '© 2026 Brethren Mining Solution Limited. All rights reserved.')
ON DUPLICATE KEY UPDATE content = VALUES(content);
