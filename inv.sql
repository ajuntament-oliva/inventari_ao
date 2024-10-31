-- --------------------------------------------------------
-- Taula estructural per a la taula `propietaris`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `propietaris` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(30) NOT NULL,
  `cognom` varchar(30) NOT NULL,
  `nom_actual` varchar(30) DEFAULT NULL,
  `cognom_actual` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------
-- Taula estructural per a la taula `departaments`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `departaments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `departament` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------
-- Taula estructural per a la taula `dispositius`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `dispositius` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `dispositiu` varchar(50) NOT NULL,
  `departament_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `departament_id` (`departament_id`),
  CONSTRAINT `FK_dispositiu_departament` FOREIGN KEY (`departament_id`) REFERENCES `departaments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------
-- Taula estructural per a la taula `caracteristiques_detalls`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `caracteristiques_detalls` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `dispositiu_id` int(11) unsigned NOT NULL,
  `uid` VARCHAR(50) DEFAULT NULL,
  `id_anydesck` VARCHAR(50) DEFAULT NULL,
  `num_serie` VARCHAR(100) DEFAULT NULL,
  `processador` VARCHAR(50) DEFAULT NULL,
  `ram` VARCHAR(50) DEFAULT NULL,
  `capacitat` VARCHAR(50) DEFAULT NULL,
  `marca` VARCHAR(50) DEFAULT NULL,
  `dimensions` VARCHAR(50) DEFAULT NULL,
  `tipus` VARCHAR(50) DEFAULT NULL,
  `data_creacio` DATE NOT NULL,
  `hora_creacio` TIME NOT NULL,
  `data_actualitzacio` DATE NOT NULL,
  `hora_actualitzacio` TIME NOT NULL,
  PRIMARY KEY (`id`),
  KEY `dispositiu_id` (`dispositiu_id`),
  CONSTRAINT `FK_caracteristica_dispositiu` FOREIGN KEY (`dispositiu_id`) REFERENCES `dispositius` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------
-- Taula estructural per a la taula `dispositiu_propietari`
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
-- Taula estructural per a la taula `users`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_level` int(11) NOT NULL,
  `image` varchar(255) DEFAULT 'no_image.jpg',
  `status` int(1) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_level` (`user_level`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Inserir dades per a la taula `users`
INSERT INTO `users` (`id`, `name`, `username`, `password`, `user_level`, `image`, `status`, `last_login`) VALUES
(1, 'Admin User', 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 1, 'no_image.jpg', 1, '2015-09-27 22:00:53'),
(2, 'Special User', 'special', 'ba36b97a41e7faf742ab09bf88405ac04f99599a', 2, 'no_image.jpg', 1, '2015-09-27 21:59:59'),
(3, 'Default User', 'user', '12dea96fec20593566ab75692c9949596833adc9', 3, 'no_image.jpg', 1, '2015-09-27 22:00:15');