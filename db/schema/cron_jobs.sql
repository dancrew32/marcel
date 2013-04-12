CREATE TABLE IF NOT EXISTS `cron_jobs` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `script` varchar(255) NOT NULL,
  `frequency` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `frequency` (`frequency`),
  KEY `script` (`script`),
  KEY `active` (`active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
