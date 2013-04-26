DROP TABLE IF EXISTS `carts`;
CREATE TABLE `carts` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `hash` varchar(32) NOT NULL,
  `data` text,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `active` (`active`),
  KEY `hash` (`hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
