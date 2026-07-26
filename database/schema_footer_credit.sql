INSERT INTO `site_content` (`page_section`, `content`) VALUES
('footer_credit_text', 'Built by MAXNATE'),
('footer_credit_url', 'https://maxnate.com')
ON DUPLICATE KEY UPDATE content = VALUES(content);
