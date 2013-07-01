DROP TABLE IF EXISTS `email_threads`;
CREATE TABLE `email_threads` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `hash` varchar(64) NOT NULL,
  `to` tinytext NOT NULL,
  `from` varchar(128) NOT NULL,
  `from_name` varchar(128) DEFAULT NULL,
  `cc` tinytext,
  `subject` varchar(256) DEFAULT NULL,
  `body` text,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;