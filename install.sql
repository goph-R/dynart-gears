-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.8-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             11.2.0.6213
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table minicore.module
CREATE TABLE IF NOT EXISTS `module` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_hungarian_ci NOT NULL,
  `description` text COLLATE utf8mb4_hungarian_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

-- Dumping data for table minicore.module: ~0 rows (approximately)
/*!40000 ALTER TABLE `module` DISABLE KEYS */;
INSERT INTO `module` (`id`, `name`, `description`, `active`) VALUES
	(1, 'User', '', 1);
/*!40000 ALTER TABLE `module` ENABLE KEYS */;

-- Dumping structure for table minicore.permission
CREATE TABLE IF NOT EXISTS `permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

-- Dumping data for table minicore.permission: ~0 rows (approximately)
/*!40000 ALTER TABLE `permission` DISABLE KEYS */;
INSERT INTO `permission` (`id`) VALUES
	(1);
/*!40000 ALTER TABLE `permission` ENABLE KEYS */;

-- Dumping structure for table minicore.permission_text
CREATE TABLE IF NOT EXISTS `permission_text` (
  `id` int(11) NOT NULL,
  `locale` char(7) COLLATE utf8mb4_hungarian_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_hungarian_ci NOT NULL,
  PRIMARY KEY (`id`,`locale`),
  CONSTRAINT `permission_text_id_fk` FOREIGN KEY (`id`) REFERENCES `permission` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

-- Dumping data for table minicore.permission_text: ~2 rows (approximately)
/*!40000 ALTER TABLE `permission_text` DISABLE KEYS */;
INSERT INTO `permission_text` (`id`, `locale`, `name`) VALUES
	(1, 'en', 'Administration'),
	(1, 'hu', 'Adminisztráció');
/*!40000 ALTER TABLE `permission_text` ENABLE KEYS */;

-- Dumping structure for table minicore.role
CREATE TABLE IF NOT EXISTS `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

-- Dumping data for table minicore.role: ~0 rows (approximately)
/*!40000 ALTER TABLE `role` DISABLE KEYS */;
INSERT INTO `role` (`id`) VALUES
	(1);
/*!40000 ALTER TABLE `role` ENABLE KEYS */;

-- Dumping structure for table minicore.role_permission
CREATE TABLE IF NOT EXISTS `role_permission` (
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  PRIMARY KEY (`role_id`,`permission_id`),
  KEY `role_permission_permission_id_fk` (`permission_id`),
  CONSTRAINT `role_permission_permission_id_fk` FOREIGN KEY (`permission_id`) REFERENCES `permission` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `role_permission_role_id_fk` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

-- Dumping data for table minicore.role_permission: ~0 rows (approximately)
/*!40000 ALTER TABLE `role_permission` DISABLE KEYS */;
/*!40000 ALTER TABLE `role_permission` ENABLE KEYS */;

-- Dumping structure for table minicore.role_text
CREATE TABLE IF NOT EXISTS `role_text` (
  `id` int(11) NOT NULL,
  `locale` char(7) COLLATE utf8mb4_hungarian_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_hungarian_ci NOT NULL,
  PRIMARY KEY (`id`,`locale`),
  CONSTRAINT `role_text_id_fk` FOREIGN KEY (`id`) REFERENCES `role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

-- Dumping data for table minicore.role_text: ~2 rows (approximately)
/*!40000 ALTER TABLE `role_text` DISABLE KEYS */;
INSERT INTO `role_text` (`id`, `locale`, `name`) VALUES
	(1, 'en', 'Administrator'),
	(1, 'hu', 'Adminisztrátor');
/*!40000 ALTER TABLE `role_text` ENABLE KEYS */;

-- Dumping structure for table minicore.test
CREATE TABLE IF NOT EXISTS `test` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `number` int(11) NOT NULL DEFAULT 0,
  `created_on` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_on` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

-- Dumping data for table minicore.test: ~15 rows (approximately)
/*!40000 ALTER TABLE `test` DISABLE KEYS */;
INSERT INTO `test` (`id`, `number`, `created_on`, `updated_on`) VALUES
	(1, 1, '2021-08-02 12:44:18', NULL),
	(2, 2, '2021-08-02 12:44:18', '2021-08-03 01:53:22'),
	(3, 3, '2021-08-02 12:44:18', NULL),
	(14, 1, '2021-08-03 02:24:43', NULL),
	(15, 1, '2021-08-03 02:24:54', NULL),
	(16, 1, '2021-08-03 02:24:58', NULL),
	(17, 1, '2021-08-03 02:24:59', NULL),
	(18, 1, '2021-08-03 02:25:00', NULL),
	(19, 1, '2021-08-03 02:25:00', NULL),
	(20, 1, '2021-08-03 02:25:00', NULL),
	(21, 1, '2021-08-03 02:25:00', NULL),
	(22, 1, '2021-08-03 02:25:01', NULL),
	(23, 1, '2021-08-03 02:25:14', NULL),
	(24, 1, '2021-08-03 02:25:29', NULL),
	(25, 1, '2021-08-03 02:34:50', NULL),
	(26, 1, '2021-08-03 08:44:18', NULL);
/*!40000 ALTER TABLE `test` ENABLE KEYS */;

-- Dumping structure for table minicore.test_text
CREATE TABLE IF NOT EXISTS `test_text` (
  `id` int(11) NOT NULL,
  `locale` char(6) COLLATE utf8mb4_hungarian_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_hungarian_ci NOT NULL,
  PRIMARY KEY (`id`,`locale`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

-- Dumping data for table minicore.test_text: ~32 rows (approximately)
/*!40000 ALTER TABLE `test_text` DISABLE KEYS */;
INSERT INTO `test_text` (`id`, `locale`, `name`) VALUES
	(1, 'en', 'English 1'),
	(1, 'hu', 'Magyar 1'),
	(2, 'en', 'English 2'),
	(2, 'hu', 'Magyar 2'),
	(3, 'en', 'English 3'),
	(3, 'hu', 'Magyar 3'),
	(14, 'en', 'en'),
	(14, 'hu', 'Egy'),
	(15, 'en', 'en'),
	(15, 'hu', 'Kettő'),
	(16, 'en', 'en'),
	(16, 'hu', 'Lorem ipsom dolor at simet.'),
	(17, 'en', 'en'),
	(17, 'hu', 'Csiga'),
	(18, 'en', 'en'),
	(18, 'hu', 'Biga'),
	(19, 'en', 'en'),
	(19, 'hu', 'Gyere'),
	(20, 'en', 'en'),
	(20, 'hu', 'Ki'),
	(21, 'en', 'en'),
	(21, 'hu', 'Ég'),
	(22, 'en', 'en'),
	(22, 'hu', 'A'),
	(23, 'en', 'en'),
	(23, 'hu', 'Házad'),
	(24, 'en', 'en'),
	(24, 'hu', 'Ide'),
	(25, 'en', 'en'),
	(25, 'hu', 'Ki'),
	(26, 'en', 'en'),
	(26, 'hu', 'hu');
/*!40000 ALTER TABLE `test_text` ENABLE KEYS */;

-- Dumping structure for table minicore.user
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb4_hungarian_ci NOT NULL,
  `password` char(32) COLLATE utf8mb4_hungarian_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_hungarian_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_hungarian_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_hungarian_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `last_login` datetime DEFAULT NULL,
  `new_email` varchar(255) COLLATE utf8mb4_hungarian_ci DEFAULT NULL,
  `avatar` char(32) COLLATE utf8mb4_hungarian_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

-- Dumping data for table minicore.user: ~0 rows (approximately)
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`id`, `email`, `password`, `name`, `first_name`, `last_name`, `active`, `last_login`, `new_email`, `avatar`) VALUES
	(1, 'gopher.hu@gmail.com', 'fe8a47ff45203f85e028a18fc473464d', 'gopher', 'Gábor', 'László', 1, '2021-12-11 02:40:02', 'gopher.hu@gmail.coma', '');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;

-- Dumping structure for table minicore.user_hash
CREATE TABLE IF NOT EXISTS `user_hash` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` char(50) COLLATE utf8mb4_hungarian_ci NOT NULL,
  `hash` char(32) COLLATE utf8mb4_hungarian_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id_name` (`user_id`,`name`),
  CONSTRAINT `user_hash_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

-- Dumping data for table minicore.user_hash: ~1 rows (approximately)
/*!40000 ALTER TABLE `user_hash` DISABLE KEYS */;
INSERT INTO `user_hash` (`id`, `user_id`, `name`, `hash`) VALUES
	(3, 1, 'forgot', '67894bb3b9e3792ad2d71b84a8f72eaa'),
	(4, 1, 'new_email', 'b9069bb8cefe41a0100f08b82dc587dd');
/*!40000 ALTER TABLE `user_hash` ENABLE KEYS */;

-- Dumping structure for table minicore.user_role
CREATE TABLE IF NOT EXISTS `user_role` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `user_role_role_id_fk` (`role_id`),
  CONSTRAINT `user_role_role_id_fk` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_role_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

-- Dumping data for table minicore.user_role: ~0 rows (approximately)
/*!40000 ALTER TABLE `user_role` DISABLE KEYS */;
INSERT INTO `user_role` (`user_id`, `role_id`) VALUES
	(1, 1);
/*!40000 ALTER TABLE `user_role` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
