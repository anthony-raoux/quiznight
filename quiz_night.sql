-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 12 juin 2024 à 12:07
-- Version du serveur : 5.7.40
-- Version de PHP : 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `quiz_night`
--

-- --------------------------------------------------------

--
-- Structure de la table `answers`
--

DROP TABLE IF EXISTS `answers`;
CREATE TABLE IF NOT EXISTS `answers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) NOT NULL,
  `answer_text` text NOT NULL,
  `is_correct` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `question_id` (`question_id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `answers`
--

INSERT INTO `answers` (`id`, `question_id`, `answer_text`, `is_correct`, `created_at`) VALUES
(1, 1, 'Paris', 1, '2024-06-10 12:13:27'),
(2, 1, 'London', 0, '2024-06-10 12:13:27'),
(3, 1, 'Berlin', 0, '2024-06-10 12:13:27'),
(4, 2, 'Jupiter', 1, '2024-06-10 12:13:27'),
(5, 2, 'Saturn', 0, '2024-06-10 12:13:27'),
(6, 2, 'Earth', 0, '2024-06-10 12:13:27'),
(7, 3, 'H2O', 1, '2024-06-10 12:13:27'),
(8, 3, 'O2', 0, '2024-06-10 12:13:27'),
(9, 3, 'CO2', 0, '2024-06-10 12:13:27'),
(10, 4, 'Mars', 1, '2024-06-10 12:13:27'),
(11, 4, 'Venus', 0, '2024-06-10 12:13:27'),
(12, 4, 'Jupiter', 0, '2024-06-10 12:13:27'),
(13, 5, 'George Washington', 1, '2024-06-10 12:13:27'),
(14, 5, 'Abraham Lincoln', 0, '2024-06-10 12:13:27'),
(15, 5, 'Thomas Jefferson', 0, '2024-06-10 12:13:27'),
(16, 6, '1912', 1, '2024-06-10 12:13:27'),
(17, 6, '1905', 0, '2024-06-10 12:13:27'),
(18, 6, '1920', 0, '2024-06-10 12:13:27');

-- --------------------------------------------------------

--
-- Structure de la table `questions`
--

DROP TABLE IF EXISTS `questions`;
CREATE TABLE IF NOT EXISTS `questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quiz_id` int(11) NOT NULL,
  `question_text` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `quiz_id` (`quiz_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `questions`
--

INSERT INTO `questions` (`id`, `quiz_id`, `question_text`, `created_at`) VALUES
(1, 1, 'Quel est la capital de la France ?', '2024-06-10 12:13:27'),
(2, 1, 'Quelle est la plus grande planète de notre système solaire ?', '2024-06-10 12:13:27'),
(3, 2, 'Quel est le symbole chimique de l eau ?', '2024-06-10 12:13:27'),
(4, 2, 'Quelle planète est connue sous le nom de planète rouge ?', '2024-06-10 12:13:27'),
(5, 3, 'Qui fut le premier président des États-Unis ?', '2024-06-10 12:13:27'),
(6, 3, 'En quelle année le Titanic a-t-il coulé ?', '2024-06-10 12:13:27');

-- --------------------------------------------------------

--
-- Structure de la table `quiz`
--

DROP TABLE IF EXISTS `quiz`;
CREATE TABLE IF NOT EXISTS `quiz` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `quiz`
--

INSERT INTO `quiz` (`id`, `title`, `created_by`, `created_at`) VALUES
(1, ' Question général ', 1, '2024-06-10 12:13:27'),
(2, ' Science ', 2, '2024-06-10 12:13:27'),
(3, ' Manga ', 3, '2024-06-10 12:13:27'),
(4, ' Série ', 4, '2024-06-10 12:13:27'),
(5, ' Histoire ', 5, '2024-06-10 12:13:27');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `role` ENUM('player', 'admin') NOT NULL DEFAULT 'player',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`, `role`) VALUES
(1, 'admin', '$2y$10$KIXYQm5mYmRZaGhQ7qUyo.9tD47gOJ9zQ99jZQk8WQbOPmF4HgNle', '2024-06-10 12:13:27', 'admin'),
(2, 'okokokok', '$2y$10$IDeBVYEDwxFZY2OcuYLzOOb7vfflryB4c1bfk7fTJ/MHNvNmDFecq', '2024-06-10 13:03:19', 'player');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
