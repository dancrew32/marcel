DROP TABLE IF EXISTS `product_categories`;
CREATE TABLE `product_categories` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;