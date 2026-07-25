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
