DROP TABLE IF EXISTS `stocks`;
CREATE TABLE `stocks` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `symbol` varchar(6) NOT NULL,
  `name` varchar(128) NOT NULL,
  `data` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `symbol` (`symbol`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
