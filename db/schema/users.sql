DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_type_id` int(10) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(35) DEFAULT NULL,
  `password` char(60) NOT NULL,
  `salt` char(60) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `first` varchar(35) DEFAULT NULL,
  `last` varchar(35) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `last_login_ip` varchar(32) DEFAULT NULL,
  `login_count` int(10) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`),
  KEY `active` (`active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;