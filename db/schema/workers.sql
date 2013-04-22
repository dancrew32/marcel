CREATE TABLE IF NOT EXISTS `workers` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `class` varchar(64) NOT NULL,
  `method` varchar(32) NOT NULL,
  `args` text DEFAULT NULL,
  `hash` varchar(32) NOT NULL,
  `run_on` datetime DEFAULT NULL,
  `active_on` datetime DEFAULT NULL,
  `created_on` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `active` (`active`),
  KEY `hash` (`hash`),
  KEY `run_on` (`run_on`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
