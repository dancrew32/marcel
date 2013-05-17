DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) DEFAULT NULL,
  `product_type_id` int(10) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `name` varchar(255) NOT NULL,
  `price` double NOT NULL,
  `description` text,
  `photo_ids` text,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `active` (`active`),
  KEY `product_type_id` (`product_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;