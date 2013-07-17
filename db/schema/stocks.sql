DROP TABLE IF EXISTS `stocks`;
CREATE TABLE `stocks` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `symbol` varchar(6) NOT NULL,
  `name` varchar(128) DEFAULT NULL,
  `data` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `symbol` (`symbol`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;