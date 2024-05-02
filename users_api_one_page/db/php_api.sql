-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3308
-- Generation Time: May 02, 2024 at 06:38 AM
-- Server version: 5.7.28
-- PHP Version: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `php_api`
--

DELIMITER $$
--
-- Procedures
--
DROP PROCEDURE IF EXISTS `register_user`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `register_user` (IN `prm_fullname` VARCHAR(100), IN `prm_username` VARCHAR(50), IN `prm_password` VARCHAR(255))  BEGIN

DECLARE user_count INT;
-- Check if the username already exists
SELECT COUNT(*) INTO user_count FROM users WHERE username = prm_username;

-- If username already exists, return error
IF user_count > 0 THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Username already exists';
ELSE
	-- Otherwise, perform the insert
	INSERT INTO users (fullname, username, password)
    VALUES (prm_fullname, prm_username, prm_password);
END IF;

END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `fullname` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `fullname`, `username`, `password`) VALUES
(1, 'Sandeep Chauhan', 'sandeep', '$2y$10$YhVimO/u5sFX9IrkjW/FiuWT3F9CcVa.CBRngEUAeR6Jj8WwI8GRu'),
(2, 'Dewas Rai', 'dewas', '$2y$10$tOrJj/pp.pify8bdIsexNeDqRwkX8Ndf.xYRbMzxSDi.Cli6v81ye');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
