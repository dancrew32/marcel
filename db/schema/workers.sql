DROP TABLE IF EXISTS `workers`;
CREATE TABLE `workers` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `class` varchar(64) NOT NULL,
  `method` varchar(32) NOT NULL,
  `args` text,
  `hash` varchar(32) NOT NULL,
  `run_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `active` (`active`),
  KEY `hash` (`hash`),
  KEY `run_at` (`run_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;