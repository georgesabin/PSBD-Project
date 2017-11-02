-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.7.14 - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for test
CREATE DATABASE IF NOT EXISTS `psbd` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `psbd`;

-- Dumping structure for table test.camere
CREATE TABLE IF NOT EXISTS `camere` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `numar` int(11) NOT NULL DEFAULT '0',
  `etaj` int(11) NOT NULL DEFAULT '0',
  `tip` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table test.clienti
CREATE TABLE IF NOT EXISTS `clienti` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nume` varchar(100) NOT NULL,
  `cnp` int(14) NOT NULL,
  `nr_telefon` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table test.personal
CREATE TABLE IF NOT EXISTS `personal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nume` varchar(50) DEFAULT NULL,
  `functie` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table test.rezervare_ocupare
CREATE TABLE IF NOT EXISTS `rezervare_ocupare` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_client` int(11) NOT NULL DEFAULT '0',
  `id_camera` int(11) NOT NULL DEFAULT '0',
  `data_start` datetime DEFAULT CURRENT_TIMESTAMP,
  `data_sfarsit` datetime DEFAULT CURRENT_TIMESTAMP,
  `status_camera` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table test.tip
CREATE TABLE IF NOT EXISTS `tip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tip_camera` varchar(50) NOT NULL DEFAULT 'single',
  `pret_per_zi` double(5,2) NOT NULL DEFAULT '120.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE tip ADD CONSTRAINT fk_tip_id FOREIGN KEY (id) REFERENCES camere(id);
ALTER TABLE rezervare_ocupare ADD CONSTRAINT fk_r_o_camera_id FOREIGN KEY (id_camera) REFERENCES camere(id);
ALTER TABLE rezervare_ocupare ADD CONSTRAINT fk_r_o_client_id FOREIGN KEY (id_client) REFERENCES clienti(id);

-- Data exporting was unselected.
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
