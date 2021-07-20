-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : Dim 07 mars 2021 à 11:16
-- Version du serveur :  8.0.22-0ubuntu0.20.04.3
-- Version de PHP : 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `tweet-academy-bdd`
--

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_tweet` int NOT NULL,
  `id_user` int NOT NULL,
  `content` text NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_tweet` (`id_tweet`),
  KEY `id_user` (`id_user`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `comments`
--

INSERT INTO `comments` (`id`, `id_tweet`, `id_user`, `content`, `date`) VALUES
(1, 7, 20, 'test', '2021-03-06 14:08:02');

-- --------------------------------------------------------

--
-- Structure de la table `hashtags`
--

DROP TABLE IF EXISTS `hashtags`;
CREATE TABLE IF NOT EXISTS `hashtags` (
  `id` int NOT NULL AUTO_INCREMENT,
  `content` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `hashtags`
--

INSERT INTO `hashtags` (`id`, `content`) VALUES
(1, '<a href=\'/views/search_results.php?search=salult\'>#salult</a>'),
(2, '@onélahein'),
(3, 'Salut salut'),
(4, '<a href=\'/views/search_results.php?search=test\'>#test</a>'),
(5, 'Bonjour bonjour'),
(6, '<a href=\'/views/search_results.php?search=retest\'>#retest</a>'),
(7, 'test');

-- --------------------------------------------------------

--
-- Structure de la table `likes`
--

DROP TABLE IF EXISTS `likes`;
CREATE TABLE IF NOT EXISTS `likes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_tweet` int NOT NULL,
  `id_user` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_tweet` (`id_tweet`),
  KEY `id_user` (`id_user`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `likes`
--

INSERT INTO `likes` (`id`, `id_tweet`, `id_user`) VALUES
(1, 1, 20),
(2, 5, 21),
(3, 4, 21);

-- --------------------------------------------------------

--
-- Structure de la table `link_tweet_hashtag`
--

DROP TABLE IF EXISTS `link_tweet_hashtag`;
CREATE TABLE IF NOT EXISTS `link_tweet_hashtag` (
  `id_hashtag` int NOT NULL,
  `id_tweet` int NOT NULL,
  KEY `id_hashtag` (`id_hashtag`),
  KEY `id_tweet` (`id_tweet`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `link_user_follower_user_following`
--

DROP TABLE IF EXISTS `link_user_follower_user_following`;
CREATE TABLE IF NOT EXISTS `link_user_follower_user_following` (
  `id_follower` int NOT NULL,
  `id_following` int NOT NULL,
  KEY `id_follower` (`id_follower`),
  KEY `id_following` (`id_following`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `link_user_follower_user_following`
--

INSERT INTO `link_user_follower_user_following` (`id_follower`, `id_following`) VALUES
(22, 20),
(21, 20),
(21, 22),
(20, 22),
(22, 21),
(20, 21);

-- --------------------------------------------------------

--
-- Structure de la table `link_user_tweet`
--

DROP TABLE IF EXISTS `link_user_tweet`;
CREATE TABLE IF NOT EXISTS `link_user_tweet` (
  `id_user` int NOT NULL,
  `id_tweet` int NOT NULL,
  KEY `id_tweet` (`id_tweet`),
  KEY `id_user` (`id_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `link_user_tweet`
--

INSERT INTO `link_user_tweet` (`id_user`, `id_tweet`) VALUES
(22, 2);

-- --------------------------------------------------------

--
-- Structure de la table `media`
--

DROP TABLE IF EXISTS `media`;
CREATE TABLE IF NOT EXISTS `media` (
  `id` int NOT NULL AUTO_INCREMENT,
  `link` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `message`
--

DROP TABLE IF EXISTS `message`;
CREATE TABLE IF NOT EXISTS `message` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_from` int NOT NULL,
  `id_to` int NOT NULL,
  `content` text NOT NULL,
  `date` datetime NOT NULL,
  `id_media` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_from` (`id_from`),
  KEY `id_to` (`id_to`),
  KEY `id_media` (`id_media`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `notification_id` int NOT NULL AUTO_INCREMENT,
  `notification_receiver_id` int NOT NULL,
  `notification_text` text NOT NULL,
  `read_notification` enum('no','yes') NOT NULL,
  PRIMARY KEY (`notification_id`)
) ENGINE=InnoDB AUTO_INCREMENT=225 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `notification_receiver_id`, `notification_text`, `read_notification`) VALUES
(202, 22, '<b>zakaria</b> vous a follow.', 'yes'),
(203, 21, '<b>zakaria</b> vous a follow.', 'yes'),
(204, 21, '<b>onélahein</b> vous a follow.', 'yes'),
(205, 20, '<b>onélahein</b> vous a follow.', 'yes'),
(206, 22, '<b>test</b> vous a follow.', 'yes'),
(207, 20, '<b>test</b> vous a follow.', 'yes'),
(208, 20, '<b>zakaria</b> a partagé un nouveau tweet', 'yes'),
(209, 20, '<b>zakaria</b> a partagé un nouveau tweet', 'yes'),
(210, 20, '\r\n				<b>zakaria</b> a liké votre post - \"...\"\r\n				', 'yes'),
(211, 20, '<b>zakaria</b> a partagé un nouveau tweet', 'yes'),
(212, 20, '<b>zakaria</b> a partagé un nouveau tweet', 'yes'),
(213, 22, '<b>onélahein</b> a partagé un nouveau tweet', 'no'),
(214, 22, '<b>onélahein</b> a partagé un nouveau tweet', 'no'),
(215, 22, '<b>onélahein</b> a partagé un nouveau tweet', 'no'),
(216, 22, '<b>onélahein</b> a partagé un nouveau tweet', 'no'),
(217, 21, '<b>test</b> a partagé un nouveau tweet', 'no'),
(218, 21, '<b>test</b> a partagé un nouveau tweet', 'no'),
(219, 22, '\r\n				<b>test</b> a liké votre post - \"@onélahein...\"\r\n				', 'no'),
(220, 22, '\r\n				<b>test</b> a liké votre post - \"...\"\r\n				', 'no'),
(221, 21, '<b>test</b> a partagé un nouveau tweet', 'no'),
(222, 21, '<b>test</b> a partagé un nouveau tweet', 'no'),
(223, 21, '<b>zakaria</b> a commenté votre tweet - \"...\"', 'no'),
(224, 22, '<b>zakaria</b> vous a follow.', 'no');

-- --------------------------------------------------------

--
-- Structure de la table `tweet`
--

DROP TABLE IF EXISTS `tweet`;
CREATE TABLE IF NOT EXISTS `tweet` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_user` int NOT NULL,
  `content` text NOT NULL,
  `id_hashtag` int DEFAULT NULL,
  `id_user_mention` int DEFAULT NULL,
  `id_media` int DEFAULT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_hashtag` (`id_hashtag`),
  KEY `id_user_mention` (`id_user_mention`),
  KEY `id_media` (`id_media`),
  KEY `id_user` (`id_user`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `tweet`
--

INSERT INTO `tweet` (`id`, `id_user`, `content`, `id_hashtag`, `id_user_mention`, `id_media`, `date`) VALUES
(1, 20, '<a href=\'/views/search_results.php?search=salult\'>#salult</a>', 1, NULL, NULL, '2021-03-06 10:33:03'),
(2, 20, '@onélahein', 2, NULL, NULL, '2021-03-06 10:33:47'),
(3, 22, 'Salut salut', 3, NULL, NULL, '2021-03-06 11:12:50'),
(4, 22, '<a href=\'/views/search_results.php?search=test\'>#test</a>', 4, NULL, NULL, '2021-03-06 11:12:55'),
(5, 22, '@onélahein', NULL, NULL, NULL, '2021-03-06 11:12:57'),
(6, 21, 'Bonjour bonjour', 5, NULL, NULL, '2021-03-06 11:13:22'),
(7, 21, '<a href=\'/views/search_results.php?search=retest\'>#retest</a>', 6, NULL, NULL, '2021-03-06 11:13:33');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nickname` varchar(255) NOT NULL,
  `birthday` date NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `registration_date` datetime NOT NULL,
  `bio` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `theme` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `nickname` (`nickname`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `nickname`, `birthday`, `email`, `password`, `registration_date`, `bio`, `theme`) VALUES
(22, 'onélahein', '1993-06-07', 'zakaribel@hotmail.com', '9e8c3ed0f82aca1babf03623677a124b1a8af671', '0000-00-00 00:00:00', 'jui 1 pangolin', ''),
(21, 'test', '1993-06-07', 'retest@test.fr', '9e8c3ed0f82aca1babf03623677a124b1a8af671', '0000-00-00 00:00:00', 'mdrr', ''),
(20, 'zakaria', '1993-06-07', 'test@test.fr', '9e8c3ed0f82aca1babf03623677a124b1a8af671', '0000-00-00 00:00:00', 'bio bio bio', '');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
