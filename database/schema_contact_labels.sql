INSERT INTO `site_content` (`page_section`, `content`) VALUES
('contact_label_address', 'Address'),
('contact_label_phone', 'Phone'),
('contact_label_email', 'Email'),
('contact_label_website', 'Website')
ON DUPLICATE KEY UPDATE content = VALUES(content);
