-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le :  mar. 17 avr. 2018 à 13:30
-- Version du serveur :  5.7.21
-- Version de PHP :  7.1.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `camagru`
--

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

CREATE TABLE `comment` (
  `comment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `image_id` int(11) NOT NULL,
  `comment` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `filter`
--

CREATE TABLE `filter` (
  `filter_id` int(11) NOT NULL,
  `path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `follow_user`
--

CREATE TABLE `follow_user` (
  `follow_user_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_id_2` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `hashtag`
--

CREATE TABLE `hashtag` (
  `hashtag_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `hashtag_image`
--

CREATE TABLE `hashtag_image` (
  `hashtag_image_id` int(11) NOT NULL,
  `image_id` int(11) NOT NULL,
  `hashtag_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `image`
--

CREATE TABLE `image` (
  `image_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `path` varchar(255) NOT NULL,
  `description` varchar(150) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `image`
--

INSERT INTO `image` (`image_id`, `user_id`, `path`, `description`, `date`) VALUES
(4, 3, 'photos/cafe.jpeg', 'A cup of cafe', '2018-04-17 20:28:27'),
(5, 3, 'photos/mountain.jpeg', 'A beautiful mountain', '2018-04-17 20:29:30'),
(6, 3, 'photos/test.jpeg', 'A simple test', '2018-04-17 20:29:52');

-- --------------------------------------------------------

--
-- Structure de la table `like`
--

CREATE TABLE `like` (
  `like_id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `image_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `like`
--

INSERT INTO `like` (`like_id`, `user_id`, `image_id`) VALUES
(54, 3, 4);

-- --------------------------------------------------------

--
-- Structure de la table `preference`
--

CREATE TABLE `preference` (
  `preference_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `preference`
--

INSERT INTO `preference` (`preference_id`, `name`) VALUES
(3, 'notification'),
(4, 'default_theme');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `login` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `signup_token` varchar(255) DEFAULT NULL,
  `password_token` varchar(255) DEFAULT NULL,
  `active` tinyint(4) NOT NULL,
  `bio` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`user_id`, `mail`, `login`, `password`, `last_name`, `first_name`, `gender`, `signup_token`, `password_token`, `active`, `bio`) VALUES
(3, 'a@b.fr', 'AnthonyLedru', '5b349742da6bb10907d815dfd8dcbc0b8945431c98f92531d73f2f31c21694a919a29fd146ac906f6d5818dd8d35aed70ad970da79a4602e251ef370b657aebf', 'Anthony', 'Ledru', 'Male', '75700896bb71fef1a95d9afc535a512afc5fe8ec5a591980ccce06cf01738da9d2c9345e8ec5de81b179fc19d8fd5efc8684', NULL, 1, '');

-- --------------------------------------------------------

--
-- Structure de la table `user_preference`
--

CREATE TABLE `user_preference` (
  `user_preference_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `preference_id` int(11) NOT NULL,
  `active` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `user_preference`
--

INSERT INTO `user_preference` (`user_preference_id`, `user_id`, `preference_id`, `active`) VALUES
(5, 3, 3, 1),
(6, 3, 4, 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`comment_id`,`user_id`,`image_id`),
  ADD KEY `fkIdx_285` (`user_id`),
  ADD KEY `fkIdx_289` (`image_id`);

--
-- Index pour la table `filter`
--
ALTER TABLE `filter`
  ADD PRIMARY KEY (`filter_id`);

--
-- Index pour la table `follow_user`
--
ALTER TABLE `follow_user`
  ADD PRIMARY KEY (`follow_user_id`,`user_id`,`user_id_2`),
  ADD KEY `fkIdx_230` (`user_id`),
  ADD KEY `fkIdx_234` (`user_id_2`);

--
-- Index pour la table `hashtag`
--
ALTER TABLE `hashtag`
  ADD PRIMARY KEY (`hashtag_id`);

--
-- Index pour la table `hashtag_image`
--
ALTER TABLE `hashtag_image`
  ADD PRIMARY KEY (`hashtag_image_id`,`image_id`,`hashtag_id`,`user_id`),
  ADD KEY `fkIdx_218` (`image_id`,`user_id`),
  ADD KEY `fkIdx_222` (`hashtag_id`);

--
-- Index pour la table `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`image_id`,`user_id`),
  ADD KEY `fkIdx_259` (`user_id`);

--
-- Index pour la table `like`
--
ALTER TABLE `like`
  ADD PRIMARY KEY (`like_id`,`user_id`,`image_id`),
  ADD KEY `fkIdx_268` (`user_id`),
  ADD KEY `fkIdx_272` (`image_id`,`user_id`);

--
-- Index pour la table `preference`
--
ALTER TABLE `preference`
  ADD PRIMARY KEY (`preference_id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- Index pour la table `user_preference`
--
ALTER TABLE `user_preference`
  ADD PRIMARY KEY (`user_preference_id`,`user_id`,`preference_id`),
  ADD KEY `fkIdx_117` (`user_id`),
  ADD KEY `fkIdx_125` (`preference_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `comment`
--
ALTER TABLE `comment`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `filter`
--
ALTER TABLE `filter`
  MODIFY `filter_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `follow_user`
--
ALTER TABLE `follow_user`
  MODIFY `follow_user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `hashtag`
--
ALTER TABLE `hashtag`
  MODIFY `hashtag_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `hashtag_image`
--
ALTER TABLE `hashtag_image`
  MODIFY `hashtag_image_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `image`
--
ALTER TABLE `image`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `like`
--
ALTER TABLE `like`
  MODIFY `like_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT pour la table `preference`
--
ALTER TABLE `preference`
  MODIFY `preference_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `user_preference`
--
ALTER TABLE `user_preference`
  MODIFY `user_preference_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `FK_285` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `FK_289` FOREIGN KEY (`image_id`) REFERENCES `image` (`image_id`);

--
-- Contraintes pour la table `follow_user`
--
ALTER TABLE `follow_user`
  ADD CONSTRAINT `FK_230` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `FK_234` FOREIGN KEY (`user_id_2`) REFERENCES `user` (`user_id`);

--
-- Contraintes pour la table `hashtag_image`
--
ALTER TABLE `hashtag_image`
  ADD CONSTRAINT `FK_218` FOREIGN KEY (`image_id`,`user_id`) REFERENCES `image` (`image_id`, `user_id`),
  ADD CONSTRAINT `FK_222` FOREIGN KEY (`hashtag_id`) REFERENCES `hashtag` (`hashtag_id`);

--
-- Contraintes pour la table `image`
--
ALTER TABLE `image`
  ADD CONSTRAINT `FK_259` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Contraintes pour la table `like`
--
ALTER TABLE `like`
  ADD CONSTRAINT `FK_268` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `FK_272` FOREIGN KEY (`image_id`,`user_id`) REFERENCES `image` (`image_id`, `user_id`);

--
-- Contraintes pour la table `user_preference`
--
ALTER TABLE `user_preference`
  ADD CONSTRAINT `FK_117` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `FK_125` FOREIGN KEY (`preference_id`) REFERENCES `preference` (`preference_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
