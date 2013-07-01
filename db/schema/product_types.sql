DROP TABLE IF EXISTS `product_types`;
CREATE TABLE `product_types` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `product_category_id` int(10) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `product_category_id` (`product_category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;