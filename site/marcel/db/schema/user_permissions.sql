DROP TABLE IF EXISTS `user_permissions`;
CREATE TABLE `user_permissions` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `feature_id` int(10) NOT NULL,
  `user_type_id` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `feature_id` (`feature_id`),
  KEY `user_type_id` (`user_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;