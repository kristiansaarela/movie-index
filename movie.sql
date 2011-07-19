-- phpMyAdmin SQL Dump
-- version 3.4.0-rc2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 19, 2011 at 10:08 PM
-- Server version: 5.5.8
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `movie`
--

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE IF NOT EXISTS `movies` (
  `id` smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `year` int(4) unsigned NOT NULL,
  `encoder` tinyint(2) unsigned DEFAULT '0',
  `imdb` varchar(47) DEFAULT NULL,
  `cover` varchar(150) NOT NULL,
  `topic` varchar(70) NOT NULL,
  `type` tinyint(2) unsigned NOT NULL,
  `add_time` datetime NOT NULL,
  `edit_time` datetime DEFAULT NULL,
  `genre` set('action','adventure','animation','biography','comedy','crime','documentary','drama','family','fantasy','foreign','history','horror','musical','mystery','romance','scifi','sport','thriller','war','western') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `genre` (`genre`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
