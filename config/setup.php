<?php

require_once __DIR__ . '/../class/myPDO.class.php';
require_once __DIR__ . '/database.php';

try {
    myPDO::setConfiguration("mysql:host=localhost;charset=utf8", $DB_USER, $DB_PASSWORD);
    $setupQuery = myPDO::getInstance()->prepare(<<<SQL
      SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
      SET AUTOCOMMIT = 0;
      START TRANSACTION;
      SET time_zone = "+00:00";

      /*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
      /*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
      /*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
      /*!40101 SET NAMES utf8mb4 */;

      CREATE DATABASE IF NOT EXISTS `camagru` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
      USE `camagru`;

      CREATE TABLE `comment` (
        `comment_id` int(11) NOT NULL,
        `user_id` int(11) NOT NULL,
        `image_id` int(11) NOT NULL,
        `comment` varchar(255) NOT NULL,
        `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

      CREATE TABLE `filter` (
        `filter_id` int(11) NOT NULL,
        `path` varchar(255) NOT NULL,
        `name` varchar(255) NOT NULL
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

      INSERT INTO `filter` (`filter_id`, `path`, `name`) VALUES
      (1, 'photos/filters/border1.png', 'Border 1'),
      (2, 'photos/filters/border2.png', 'Border 2'),
      (3, 'photos/filters/cat.png', 'Cat'),
      (4, 'photos/filters/orange.png', 'Orange'),
      (5, 'photos/filters/black.png', 'Black');

      CREATE TABLE `follow` (
        `follow_id` int(11) NOT NULL,
        `user_id_followed` int(11) NOT NULL,
        `user_id_follower` int(11) NOT NULL
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

      CREATE TABLE `image` (
        `image_id` int(11) NOT NULL,
        `user_id` int(11) NOT NULL,
        `path` varchar(255) NOT NULL,
        `description` varchar(150) NOT NULL,
        `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

      CREATE TABLE `like` (
        `like_id` bigint(20) NOT NULL,
        `user_id` int(11) NOT NULL,
        `image_id` int(11) NOT NULL
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

      CREATE TABLE `preference` (
        `preference_id` int(11) NOT NULL,
        `name` varchar(255) NOT NULL
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

      CREATE TABLE `user` (
        `user_id` int(11) NOT NULL,
        `image_id` int(11) DEFAULT NULL,
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

      CREATE TABLE `user_preference` (
        `user_preference_id` int(11) NOT NULL,
        `user_id` int(11) NOT NULL,
        `preference_id` int(11) NOT NULL,
        `active` tinyint(4) NOT NULL
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8;


      ALTER TABLE `comment`
        ADD PRIMARY KEY (`comment_id`,`user_id`,`image_id`),
        ADD KEY `fkIdx_285` (`user_id`),
        ADD KEY `fkIdx_289` (`image_id`);

      ALTER TABLE `filter`
        ADD PRIMARY KEY (`filter_id`);

      ALTER TABLE `follow`
        ADD PRIMARY KEY (`follow_id`,`user_id_followed`,`user_id_follower`),
        ADD KEY `fkIdx_230` (`user_id_followed`),
        ADD KEY `fkIdx_234` (`user_id_follower`);

      ALTER TABLE `image`
        ADD PRIMARY KEY (`image_id`,`user_id`),
        ADD KEY `fkIdx_259` (`user_id`);

      ALTER TABLE `like`
        ADD PRIMARY KEY (`like_id`,`user_id`,`image_id`),
        ADD KEY `fkIdx_268` (`user_id`),
        ADD KEY `fkIdx_272` (`image_id`);

      ALTER TABLE `preference`
        ADD PRIMARY KEY (`preference_id`);

      ALTER TABLE `user`
        ADD PRIMARY KEY (`user_id`),
        ADD KEY `fkIdx_232` (`image_id`);

      ALTER TABLE `user_preference`
        ADD PRIMARY KEY (`user_preference_id`,`user_id`,`preference_id`),
        ADD KEY `fkIdx_117` (`user_id`),
        ADD KEY `fkIdx_125` (`preference_id`);


      ALTER TABLE `comment`
        MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT;

      ALTER TABLE `filter`
        MODIFY `filter_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

      ALTER TABLE `follow`
        MODIFY `follow_id` int(11) NOT NULL AUTO_INCREMENT;

      ALTER TABLE `image`
        MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT;

      ALTER TABLE `like`
        MODIFY `like_id` bigint(20) NOT NULL AUTO_INCREMENT;

      ALTER TABLE `preference`
        MODIFY `preference_id` int(11) NOT NULL AUTO_INCREMENT;

      ALTER TABLE `user`
        MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

      ALTER TABLE `user_preference`
        MODIFY `user_preference_id` int(11) NOT NULL AUTO_INCREMENT;


      ALTER TABLE `image`
        ADD CONSTRAINT `FK_259` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

      ALTER TABLE `like`
        ADD CONSTRAINT `FK_268` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
        ADD CONSTRAINT `FK_272` FOREIGN KEY (`image_id`) REFERENCES `image` (`image_id`) ON DELETE CASCADE;

      ALTER TABLE `user`
        ADD CONSTRAINT `FK_232` FOREIGN KEY (`image_id`) REFERENCES `image` (`image_id`) ON DELETE SET NULL;

      ALTER TABLE `user_preference`
        ADD CONSTRAINT `FK_117` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
        ADD CONSTRAINT `FK_125` FOREIGN KEY (`preference_id`) REFERENCES `preference` (`preference_id`);
      COMMIT;

      /*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
      /*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
      /*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


SQL
    );
    $setupQuery->setFetchMode(PDO::FETCH_CLASS, __CLASS__ );
    $setupQuery->execute();
    echo "DB created";

} catch (Exception $e) {
    echo $e->getMessage();
}