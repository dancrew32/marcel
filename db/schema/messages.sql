DROP TABLE IF EXISTS `messages`;
CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `data` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;