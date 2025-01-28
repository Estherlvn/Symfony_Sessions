-- --------------------------------------------------------
-- Hôte:                         127.0.0.1
-- Version du serveur:           8.0.30 - MySQL Community Server - GPL
-- SE du serveur:                Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Listage de la structure de la base pour ledlevsymfonysessions
CREATE DATABASE IF NOT EXISTS `ledlevsymfonysessions` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `ledlevsymfonysessions`;

-- Listage de la structure de table ledlevsymfonysessions. categorie
CREATE TABLE IF NOT EXISTS `categorie` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom_categorie` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table ledlevsymfonysessions.categorie : ~5 rows (environ)
INSERT INTO `categorie` (`id`, `nom_categorie`) VALUES
	(1, 'Bureautique'),
	(2, 'Développement web'),
	(3, 'Comptabilité'),
	(4, 'Commerce & Vente'),
	(5, 'RH & Direction');

-- Listage de la structure de table ledlevsymfonysessions. doctrine_migration_versions
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Listage des données de la table ledlevsymfonysessions.doctrine_migration_versions : ~1 rows (environ)
INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
	('DoctrineMigrations\\Version20250128140745', '2025-01-28 14:08:10', 1003);

-- Listage de la structure de table ledlevsymfonysessions. formateur
CREATE TABLE IF NOT EXISTS `formateur` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table ledlevsymfonysessions.formateur : ~4 rows (environ)
INSERT INTO `formateur` (`id`, `nom`, `prenom`, `email`) VALUES
	(1, 'LEDOYEN', 'Daniel', 'daniel@formatech.com'),
	(2, 'LEJEUNE', 'Christelle', 'christelle@formatech.com'),
	(3, 'NARCISSE', 'Roger', 'roger@formatech.com'),
	(4, 'SERAN', 'Gregoire', 'gregoire@formatech.com');

-- Listage de la structure de table ledlevsymfonysessions. formation
CREATE TABLE IF NOT EXISTS `formation` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom_formation` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table ledlevsymfonysessions.formation : ~6 rows (environ)
INSERT INTO `formation` (`id`, `nom_formation`) VALUES
	(1, 'Découverte du numérique'),
	(2, 'Inititation au développement web'),
	(3, 'Réussir une vente'),
	(4, 'Introduction à la suite Office'),
	(5, 'Outils en comptabilité'),
	(6, 'Gestion RH');

-- Listage de la structure de table ledlevsymfonysessions. messenger_messages
CREATE TABLE IF NOT EXISTS `messenger_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table ledlevsymfonysessions.messenger_messages : ~0 rows (environ)

-- Listage de la structure de table ledlevsymfonysessions. module
CREATE TABLE IF NOT EXISTS `module` (
  `id` int NOT NULL AUTO_INCREMENT,
  `categorie_id` int DEFAULT NULL,
  `nom_module` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_C242628BCF5E72D` (`categorie_id`),
  CONSTRAINT `FK_C242628BCF5E72D` FOREIGN KEY (`categorie_id`) REFERENCES `categorie` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table ledlevsymfonysessions.module : ~24 rows (environ)
INSERT INTO `module` (`id`, `categorie_id`, `nom_module`) VALUES
	(1, 1, 'Word'),
	(2, 1, 'Excel'),
	(3, 1, 'Teams'),
	(4, 2, 'HTML & CSS'),
	(5, 2, 'Responsive Web Design'),
	(6, 2, 'Web design'),
	(7, 2, 'Base de données  & SGBD'),
	(8, 2, 'Algorithmie'),
	(9, 2, 'Manipulation du DOM'),
	(10, 2, 'Javascript'),
	(11, 2, 'PHP'),
	(12, 2, 'Librairie vs Framework'),
	(13, 2, 'Front vs Back'),
	(14, 2, 'Symfony'),
	(15, 2, 'React'),
	(16, 2, 'SQL'),
	(17, 2, 'SEO'),
	(18, 2, 'Sécurité'),
	(19, 4, 'Techniques de vente'),
	(20, 4, 'Service client'),
	(21, 4, 'Gestion de stocks'),
	(22, 5, 'Communication'),
	(23, 5, 'Teambuilding'),
	(24, 5, 'Gestion des ressources');

-- Listage de la structure de table ledlevsymfonysessions. programme
CREATE TABLE IF NOT EXISTS `programme` (
  `id` int NOT NULL AUTO_INCREMENT,
  `session_id` int NOT NULL,
  `module_id` int DEFAULT NULL,
  `nb_jour` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_3DDCB9FF613FECDF` (`session_id`),
  KEY `IDX_3DDCB9FFAFC2B591` (`module_id`),
  CONSTRAINT `FK_3DDCB9FF613FECDF` FOREIGN KEY (`session_id`) REFERENCES `session` (`id`),
  CONSTRAINT `FK_3DDCB9FFAFC2B591` FOREIGN KEY (`module_id`) REFERENCES `module` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table ledlevsymfonysessions.programme : ~11 rows (environ)
INSERT INTO `programme` (`id`, `session_id`, `module_id`, `nb_jour`) VALUES
	(1, 1, 4, 3),
	(2, 1, 5, 1),
	(3, 1, 9, 1),
	(4, 2, 13, 2),
	(5, 2, 8, 3),
	(6, 2, 10, 5),
	(7, 3, 2, 3),
	(8, 3, 22, 2),
	(9, 4, 22, 2),
	(10, 4, 23, 3),
	(11, 4, 24, 4);

-- Listage de la structure de table ledlevsymfonysessions. session
CREATE TABLE IF NOT EXISTS `session` (
  `id` int NOT NULL AUTO_INCREMENT,
  `formation_id` int NOT NULL,
  `formateur_id` int NOT NULL,
  `intitule` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `nb_place` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D044D5D45200282E` (`formation_id`),
  KEY `IDX_D044D5D4155D8F51` (`formateur_id`),
  CONSTRAINT `FK_D044D5D4155D8F51` FOREIGN KEY (`formateur_id`) REFERENCES `formateur` (`id`),
  CONSTRAINT `FK_D044D5D45200282E` FOREIGN KEY (`formation_id`) REFERENCES `formation` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table ledlevsymfonysessions.session : ~4 rows (environ)
INSERT INTO `session` (`id`, `formation_id`, `formateur_id`, `intitule`, `date_debut`, `date_fin`, `nb_place`) VALUES
	(1, 1, 1, 'Créer une page web statique', '2025-02-28', '2025-03-28', 15),
	(2, 1, 1, 'Développement web fullstack', '2025-04-28', '2025-07-28', 15),
	(3, 4, 3, 'Informatique de l\'administration', '2025-01-28', '2025-02-28', 20),
	(4, 6, 2, 'Premiers pas RH', '2025-06-01', '2025-07-15', 20);

-- Listage de la structure de table ledlevsymfonysessions. session_stagiaire
CREATE TABLE IF NOT EXISTS `session_stagiaire` (
  `session_id` int NOT NULL,
  `stagiaire_id` int NOT NULL,
  PRIMARY KEY (`session_id`,`stagiaire_id`),
  KEY `IDX_C80B23B613FECDF` (`session_id`),
  KEY `IDX_C80B23BBBA93DD6` (`stagiaire_id`),
  CONSTRAINT `FK_C80B23B613FECDF` FOREIGN KEY (`session_id`) REFERENCES `session` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_C80B23BBBA93DD6` FOREIGN KEY (`stagiaire_id`) REFERENCES `stagiaire` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table ledlevsymfonysessions.session_stagiaire : ~0 rows (environ)

-- Listage de la structure de table ledlevsymfonysessions. stagiaire
CREATE TABLE IF NOT EXISTS `stagiaire` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_naissance` date NOT NULL,
  `ville` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table ledlevsymfonysessions.stagiaire : ~3 rows (environ)
INSERT INTO `stagiaire` (`id`, `nom`, `prenom`, `date_naissance`, `ville`, `email`, `telephone`) VALUES
	(1, 'MARCHAND', 'Maxime', '1988-01-21', 'STRASBOURG', 'maxime@stagiaire.com', '0645796525'),
	(2, 'LOYAL', 'Clara', '2000-02-23', 'MULHOUSE', 'clara@stagiaire.com', '0645963521'),
	(3, 'FRAISE', 'Guillaume', '1997-07-17', 'STRASBOURG', 'guillaume@stagiaire.com', '0645852535');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
