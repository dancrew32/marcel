DROP TABLE IF EXISTS `carts`;
CREATE TABLE `carts` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) DEFAULT NULL,
  `complete` tinyint(1) NOT NULL DEFAULT '0',
  `hash` varchar(32) NOT NULL,
  `data` text,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `hash` (`hash`),
  KEY `user_id` (`user_id`),
  KEY `complete` (`complete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;