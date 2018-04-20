-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 18, 2018 at 04:14 AM
-- Server version: 5.7.21
-- PHP Version: 7.1.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `camagru`
--

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `comment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `image_id` int(11) NOT NULL,
  `comment` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`comment_id`, `user_id`, `image_id`, `comment`, `date`) VALUES
(1, 3, 2, 'That\'s beautiful', '2018-04-18 10:55:57'),
(2, 4, 3, 'Salut', '2018-04-18 11:09:41');

-- --------------------------------------------------------

--
-- Table structure for table `filter`
--

CREATE TABLE `filter` (
  `filter_id` int(11) NOT NULL,
  `path` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `follow_user`
--

CREATE TABLE `follow_user` (
  `follow_user_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_id_2` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hashtag`
--

CREATE TABLE `hashtag` (
  `hashtag_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hashtag_image`
--

CREATE TABLE `hashtag_image` (
  `hashtag_image_id` int(11) NOT NULL,
  `image_id` int(11) NOT NULL,
  `hashtag_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `image`
--

CREATE TABLE `image` (
  `image_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `path` varchar(255) NOT NULL,
  `description` varchar(150) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `image`
--

INSERT INTO `image` (`image_id`, `user_id`, `path`, `description`, `date`) VALUES
(1, 3, 'photos/cafe.jpeg', 'A cup of cafe', '2018-04-18 10:54:20'),
(2, 3, 'photos/mountain.jpeg', 'A beautiful mountain', '2018-04-18 10:54:50'),
(3, 3, 'photos/test.jpeg', 'A test', '2018-04-18 10:55:13');

-- --------------------------------------------------------

--
-- Table structure for table `like`
--

CREATE TABLE `like` (
  `like_id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `image_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `like`
--

INSERT INTO `like` (`like_id`, `user_id`, `image_id`) VALUES
(6, 3, 1),
(2, 4, 2),
(7, 4, 3);

-- --------------------------------------------------------

--
-- Table structure for table `preference`
--

CREATE TABLE `preference` (
  `preference_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `preference`
--

INSERT INTO `preference` (`preference_id`, `name`) VALUES
(3, 'notification'),
(4, 'default_theme');

-- --------------------------------------------------------

--
-- Table structure for table `user`
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
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `mail`, `login`, `password`, `last_name`, `first_name`, `gender`, `signup_token`, `password_token`, `active`, `bio`) VALUES
(3, 'test@test.fr', 'AnthonyLedru', '6fa663e52b3d7da9780204facb3032f889b243a82185d0ef4e21d1c3669896acca8aae3325dce8e72f82e9acde04f8c139b811423ca9732333ff31d75fbd9230', 'Anthony', 'Ledru', 'Male', 'f45d16563966a0b2a99734a83d1669104be2826f0d977fe600ff26ab8322dd943bd69e71b9398cfe0f668da17d55634a2b2f', NULL, 1, ''),
(4, 'test2@test.fr', 'SalutSalut', '6fa663e52b3d7da9780204facb3032f889b243a82185d0ef4e21d1c3669896acca8aae3325dce8e72f82e9acde04f8c139b811423ca9732333ff31d75fbd9230', 'Anthony', 'Salut', 'Male', 'f03a58ea42742d27f6a31bccea2df2b224cd970c0606bfa6cff3a2107cb8d00f6cda94b2e29d103a4d2343bde904a0fc52ac', NULL, 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `user_preference`
--

CREATE TABLE `user_preference` (
  `user_preference_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `preference_id` int(11) NOT NULL,
  `active` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_preference`
--

INSERT INTO `user_preference` (`user_preference_id`, `user_id`, `preference_id`, `active`) VALUES
(5, 3, 3, 1),
(6, 3, 4, 1),
(7, 4, 3, 1),
(8, 4, 4, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`comment_id`,`user_id`,`image_id`),
  ADD KEY `fkIdx_285` (`user_id`),
  ADD KEY `fkIdx_289` (`image_id`);

--
-- Indexes for table `filter`
--
ALTER TABLE `filter`
  ADD PRIMARY KEY (`filter_id`);

--
-- Indexes for table `follow_user`
--
ALTER TABLE `follow_user`
  ADD PRIMARY KEY (`follow_user_id`,`user_id`,`user_id_2`),
  ADD KEY `fkIdx_230` (`user_id`),
  ADD KEY `fkIdx_234` (`user_id_2`);

--
-- Indexes for table `hashtag`
--
ALTER TABLE `hashtag`
  ADD PRIMARY KEY (`hashtag_id`);

--
-- Indexes for table `hashtag_image`
--
ALTER TABLE `hashtag_image`
  ADD PRIMARY KEY (`hashtag_image_id`,`image_id`,`hashtag_id`,`user_id`),
  ADD KEY `fkIdx_218` (`image_id`,`user_id`),
  ADD KEY `fkIdx_222` (`hashtag_id`);

--
-- Indexes for table `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`image_id`,`user_id`),
  ADD KEY `fkIdx_259` (`user_id`);

--
-- Indexes for table `like`
--
ALTER TABLE `like`
  ADD PRIMARY KEY (`like_id`,`user_id`,`image_id`),
  ADD KEY `fkIdx_268` (`user_id`),
  ADD KEY `fkIdx_272` (`image_id`);

--
-- Indexes for table `preference`
--
ALTER TABLE `preference`
  ADD PRIMARY KEY (`preference_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_preference`
--
ALTER TABLE `user_preference`
  ADD PRIMARY KEY (`user_preference_id`,`user_id`,`preference_id`),
  ADD KEY `fkIdx_117` (`user_id`),
  ADD KEY `fkIdx_125` (`preference_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `filter`
--
ALTER TABLE `filter`
  MODIFY `filter_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `follow_user`
--
ALTER TABLE `follow_user`
  MODIFY `follow_user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hashtag`
--
ALTER TABLE `hashtag`
  MODIFY `hashtag_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hashtag_image`
--
ALTER TABLE `hashtag_image`
  MODIFY `hashtag_image_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `image`
--
ALTER TABLE `image`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `like`
--
ALTER TABLE `like`
  MODIFY `like_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `preference`
--
ALTER TABLE `preference`
  MODIFY `preference_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_preference`
--
ALTER TABLE `user_preference`
  MODIFY `user_preference_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `FK_285` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `FK_289` FOREIGN KEY (`image_id`) REFERENCES `image` (`image_id`);

--
-- Constraints for table `follow_user`
--
ALTER TABLE `follow_user`
  ADD CONSTRAINT `FK_230` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `FK_234` FOREIGN KEY (`user_id_2`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `hashtag_image`
--
ALTER TABLE `hashtag_image`
  ADD CONSTRAINT `FK_218` FOREIGN KEY (`image_id`,`user_id`) REFERENCES `image` (`image_id`, `user_id`),
  ADD CONSTRAINT `FK_222` FOREIGN KEY (`hashtag_id`) REFERENCES `hashtag` (`hashtag_id`);

--
-- Constraints for table `image`
--
ALTER TABLE `image`
  ADD CONSTRAINT `FK_259` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `like`
--
ALTER TABLE `like`
  ADD CONSTRAINT `FK_268` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `FK_272` FOREIGN KEY (`image_id`) REFERENCES `image` (`image_id`);

--
-- Constraints for table `user_preference`
--
ALTER TABLE `user_preference`
  ADD CONSTRAINT `FK_117` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `FK_125` FOREIGN KEY (`preference_id`) REFERENCES `preference` (`preference_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
