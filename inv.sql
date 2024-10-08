-- --------------------------------------------------------
-- Taula estructural per a la taula `propietaris`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `propietaris` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(30) NOT NULL,
  `cognom` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Inserir dades per a la taula `propietaris`
INSERT INTO `propietaris` (`id`, `nom`, `cognom`) VALUES
(1, 'Minho', 'Lee'),
(2, 'Richard', 'Grayson'),
(3, 'Sebastian', 'Stan');

-- --------------------------------------------------------
-- Taula estructural per a la taula `departaments`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `departaments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `departament` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Inserir dades per a la taula `departaments`
INSERT INTO `departaments` (`id`, `departament`) VALUES
(1, 'Modernització'),
(2, 'ADL'),
(3, 'RRHH');

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

-- Inserir dades per a la taula `dispositius`
INSERT INTO `dispositius` (`id`, `dispositiu`, `departament_id`) VALUES
(1, 'Torre', 1),
(2, 'Torre', 2),
(3, 'Portàtil', 3);

-- --------------------------------------------------------
-- Taula estructural per a la taula `caracteristiques_detalls`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `caracteristiques_detalls` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `dispositiu_id` int(11) unsigned NOT NULL,
  `uid` VARCHAR(50) NOT NULL,
  `id_anydesck` VARCHAR(50) NOT NULL,
  `processador` VARCHAR(50) NOT NULL,
  `ram` VARCHAR(50) NOT NULL,
  `capacitat` VARCHAR(50) NOT NULL,
  `marca` VARCHAR(50) NOT NULL,
  `dimensions` VARCHAR(50) NOT NULL,
  `tipus` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `dispositiu_id` (`dispositiu_id`),
  CONSTRAINT `FK_caracteristica_dispositiu` FOREIGN KEY (`dispositiu_id`) REFERENCES `dispositius` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Inserir dades per a la taula `caracteristiques_detalls`
INSERT INTO `caracteristiques_detalls` (`id`, `dispositiu_id`, `uid`, `id_anydesck`, `processador`, `ram`, `capacitat`) VALUES
(1, 1, 'UID123', 'A1B2C3', 'Intel Core i7', '16GB', '512GB SSD'),
(2, 1, 'UID124', 'A1B2C4', 'Intel Core i7', '16GB', '1TB SSD'),
(3, 2, 'UID125', 'A1B2C5', 'Intel Core i5', '8GB', '256GB SSD'),
(4, 3, 'UID126', 'A1B2C6', 'Intel Core i5', '8GB', '512GB SSD');

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

-- Inserir dades per a la taula `dispositiu_propietari`
INSERT INTO `dispositiu_propietari` (`dispositiu_id`, `propietari_id`) VALUES
(1, 1),
(2, 2),
(3, 3),
(1, 2);  -- Exemple de dispositiu amb més d'un propietari

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