-- --------------------------------------------------------
-- Table structure for table `propietaris`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `propietaris` (
  `id` int(11) unsigned NOT NULL,
  `nom` varchar(30) NOT NULL,
  `cognom` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

-- Dumping data for table `propietaris`
INSERT INTO `propietaris` (`id`, `nom`, `cognom`) VALUES
(1, 'Minho', 'Lee'),
(2, 'Richard', 'Grayson'),
(3, 'Sebastian', 'Stan');

-- --------------------------------------------------------
-- Table structure for table `departaments`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `departaments` (
  `id` int(11) unsigned NOT NULL,
  `departament` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Dumping data for table `departaments`
INSERT INTO `departaments` (`id`, `departament`) VALUES
(1, 'Modernització'),
(2, 'ADL'),
(3, 'RRHH');

-- --------------------------------------------------------
-- Table structure for table `dispositius`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `dispositius` (
  `id` int(11) unsigned NOT NULL,
  `dispositiu` varchar(50) NOT NULL,
  `departament_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `departament_id` (`departament_id`),
  CONSTRAINT `FK_dispositiu_departament` FOREIGN KEY (`departament_id`) REFERENCES `departaments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Dumping data for table `dispositius`
INSERT INTO `dispositius` (`id`, `dispositiu`, `departament_id`) VALUES
(1, 'Torre', 1),
(2, 'Torre', 2),
(3, 'Portàtil', 3);

-- --------------------------------------------------------
-- Table structure for table `caracteristiques_dispositiu`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `caracteristiques_dispositiu` (
  `id` int(11) unsigned NOT NULL,
  `dispositiu_id` int(11) unsigned NOT NULL,
  `caracteristica` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `dispositiu_id` (`dispositiu_id`),
  CONSTRAINT `FK_caracteristica_dispositiu` FOREIGN KEY (`dispositiu_id`) REFERENCES `dispositius` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

-- Dumping data for table `caracteristiques_dispositiu`
INSERT INTO `caracteristiques_dispositiu` (`id`, `dispositiu_id`, `caracteristica`) VALUES
(1, 1, 'Intel Core i7'),
(2, 1, '16GB RAM'),
(3, 1, '512GB SSD'),
(4, 2, 'Intel Core i5'),
(5, 2, '8GB RAM'),
(6, 3, 'Intel Core i5'),
(7, 3, '256GB SSD');

-- --------------------------------------------------------
-- Table structure for table `dispositiu_propietari`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `dispositiu_propietari` (
  `dispositiu_id` int(11) unsigned NOT NULL,
  `propietari_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`dispositiu_id`, `propietari_id`),
  KEY `FK_dispositiu_propietari_dispositiu` (`dispositiu_id`),
  KEY `FK_dispositiu_propietari_propietari` (`propietari_id`),
  CONSTRAINT `FK_dispositiu_propietari_dispositiu` FOREIGN KEY (`dispositiu_id`) REFERENCES `dispositius` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_dispositiu_propietari_propietari` FOREIGN KEY (`propietari_id`) REFERENCES `propietaris` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------
-- Dumping data for table `dispositiu_propietari`
-- --------------------------------------------------------
INSERT INTO `dispositiu_propietari` (`dispositiu_id`, `propietari_id`) VALUES
(1, 1),
(2, 2),
(3, 3),
(1, 2);  -- Example of a device having more than one owner

-- --------------------------------------------------------
-- Table structure for table `users`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL,
  `name` varchar(60) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_level` int(11) NOT NULL,
  `image` varchar(255) DEFAULT 'no_image.jpg',
  `status` int(1) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_level` (`user_level`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Dumping data for table `users`
INSERT INTO `users` (`id`, `name`, `username`, `password`, `user_level`, `image`, `status`, `last_login`) VALUES
(1, 'Admin User', 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 1, 'no_image.jpg', 1, '2015-09-27 22:00:53'),
(2, 'Special User', 'special', 'ba36b97a41e7faf742ab09bf88405ac04f99599a', 2, 'no_image.jpg', 1, '2015-09-27 21:59:59'),
(3, 'Default User', 'user', '12dea96fec20593566ab75692c9949596833adc9', 3, 'no_image.jpg', 1, '2015-09-27 22:00:15');

-- --------------------------------------------------------
-- AUTO_INCREMENT for tables
-- --------------------------------------------------------
ALTER TABLE `propietaris`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `departaments`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `dispositius`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `caracteristiques_dispositiu`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

ALTER TABLE `users`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;