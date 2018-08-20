-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 25, 2014 at 07:46 PM
-- Server version: 5.6.16
-- PHP Version: 5.5.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `hongjileak`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_block`
--

CREATE TABLE IF NOT EXISTS `tbl_block` (
  `blockid` bigint(20) NOT NULL AUTO_INCREMENT,
  `userid` bigint(20) NOT NULL,
  `blocked` bigint(20) NOT NULL,
  `created` bigint(20) NOT NULL,
  PRIMARY KEY (`blockid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `tbl_block`
--

INSERT INTO `tbl_block` (`blockid`, `userid`, `blocked`, `created`) VALUES
(1, 6, 8, 1412908854),
(5, 2, 7, 1413134727);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_follow`
--

CREATE TABLE IF NOT EXISTS `tbl_follow` (
  `followid` bigint(20) NOT NULL AUTO_INCREMENT,
  `userid` bigint(20) NOT NULL,
  `following` bigint(20) NOT NULL,
  `created_at` bigint(20) NOT NULL,
  PRIMARY KEY (`followid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=30 ;

--
-- Dumping data for table `tbl_follow`
--

INSERT INTO `tbl_follow` (`followid`, `userid`, `following`, `created_at`) VALUES
(1, 4, 2, 1409802393),
(2, 4, 1, 1409802489),
(5, 2, 5, 1410112856),
(6, 5, 2, 1410199378),
(7, 6, 2, 1410229745),
(8, 2, 6, 1410229766),
(9, 7, 1, 1410893951),
(11, 7, 3, 1410893952),
(12, 7, 4, 1410893953),
(13, 7, 5, 1410893954),
(14, 7, 6, 1410893955),
(22, 11, 12, 1416722782),
(29, 19, 20, 1416939879),
(26, 20, 19, 1416856950),
(25, 12, 4, 1416724340);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_friend`
--

CREATE TABLE IF NOT EXISTS `tbl_friend` (
  `friendid` bigint(20) NOT NULL AUTO_INCREMENT,
  `userid` bigint(20) NOT NULL,
  `joined` bigint(20) NOT NULL,
  `created` bigint(20) NOT NULL,
  PRIMARY KEY (`friendid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_mark`
--

CREATE TABLE IF NOT EXISTS `tbl_mark` (
  `markid` bigint(20) NOT NULL AUTO_INCREMENT,
  `userid` bigint(20) NOT NULL,
  `postid` bigint(20) NOT NULL,
  `hearts` bigint(20) NOT NULL,
  `heart_eyes` bigint(20) NOT NULL,
  `angry_faces` bigint(20) NOT NULL,
  `question_marks` bigint(20) NOT NULL,
  `wtfs` bigint(20) NOT NULL,
  `thumb_downs` bigint(20) NOT NULL,
  `middle_fingers` bigint(20) NOT NULL,
  `saves` bigint(20) NOT NULL,
  `created_at` bigint(20) NOT NULL,
  PRIMARY KEY (`markid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=95 ;

--
-- Dumping data for table `tbl_mark`
--

INSERT INTO `tbl_mark` (`markid`, `userid`, `postid`, `hearts`, `heart_eyes`, `angry_faces`, `question_marks`, `wtfs`, `thumb_downs`, `middle_fingers`, `saves`, `created_at`) VALUES
(1, 4, 1, 0, 0, 1, 0, 0, 0, 0, 0, 1409802419),
(2, 4, 5, 0, 0, 1, 0, 0, 0, 0, 0, 1409851618),
(3, 4, 4, 1, 0, 0, 0, 0, 0, 0, 0, 1409851621),
(4, 4, 3, 0, 0, 0, 1, 0, 0, 0, 0, 1409851626),
(5, 4, 6, 1, 0, 0, 0, 0, 0, 0, 0, 1409853819),
(20, 2, 28, 0, 0, 0, 0, 1, 0, 0, 0, 1410212053),
(8, 4, 11, 0, 0, 0, 1, 0, 0, 0, 0, 1409860127),
(21, 5, 29, 0, 0, 0, 0, 0, 0, 1, 0, 1410212108),
(10, 4, 14, 0, 0, 0, 0, 0, 0, 1, 0, 1409887066),
(11, 4, 13, 0, 0, 1, 0, 0, 0, 0, 0, 1409887071),
(12, 4, 12, 0, 0, 0, 0, 1, 0, 0, 0, 1409887077),
(24, 2, 30, 0, 0, 0, 0, 0, 0, 1, 0, 1410212789),
(25, 4, 29, 0, 0, 0, 0, 1, 0, 0, 0, 1410237981),
(34, 7, 31, 0, 0, 0, 0, 0, 0, 1, 0, 1410894076),
(26, 4, 16, 1, 0, 0, 0, 0, 0, 0, 0, 1410237985),
(83, 11, 35, 1, 0, 0, 0, 0, 0, 0, 0, 1416721171),
(93, 19, 41, 0, 0, 0, 0, 0, 0, 0, 1, 1416856175),
(75, 11, 37, 0, 0, 0, 1, 0, 0, 0, 0, 1416696950),
(71, 11, 36, 0, 0, 0, 0, 0, 0, 0, 1, 1416696912),
(81, 12, 37, 0, 0, 0, 0, 0, 1, 0, 0, 1416721096),
(94, 19, 44, 0, 0, 0, 0, 0, 0, 0, 1, 1416857397);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_post`
--

CREATE TABLE IF NOT EXISTS `tbl_post` (
  `postid` bigint(20) NOT NULL AUTO_INCREMENT,
  `userid` bigint(20) NOT NULL,
  `reposted` bigint(20) NOT NULL,
  `replied` bigint(20) NOT NULL,
  `comment` varchar(256) NOT NULL,
  `created_at` bigint(20) NOT NULL,
  PRIMARY KEY (`postid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=46 ;

--
-- Dumping data for table `tbl_post`
--

INSERT INTO `tbl_post` (`postid`, `userid`, `reposted`, `replied`, `comment`, `created_at`) VALUES
(41, 19, 0, 0, 'AAA''s First post!', 1416784928),
(44, 19, 0, 0, 'This is AAA''s second Post.', 1416857387),
(43, 20, 0, 0, 'This is B post.', 1416856939),
(45, 20, 0, 0, 'This is BBB''s second post!', 1416940139);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_report`
--

CREATE TABLE IF NOT EXISTS `tbl_report` (
  `reportid` bigint(20) NOT NULL AUTO_INCREMENT,
  `userid` bigint(20) NOT NULL,
  `reported` bigint(20) NOT NULL,
  `created` bigint(20) NOT NULL,
  PRIMARY KEY (`reportid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `tbl_report`
--

INSERT INTO `tbl_report` (`reportid`, `userid`, `reported`, `created`) VALUES
(1, 8, 33, 1412929939),
(2, 8, 33, 1412929956),
(3, 2, 28, 1412964962),
(4, 2, 30, 1413134980),
(5, 12, 39, 1416742008),
(6, 19, 41, 1416785139);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE IF NOT EXISTS `tbl_user` (
  `userid` bigint(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `name` varchar(256) NOT NULL,
  `email` varchar(256) NOT NULL,
  `avatar` varchar(256) NOT NULL,
  `biography` varchar(256) NOT NULL,
  `notification` tinyint(1) NOT NULL,
  `privated` tinyint(1) NOT NULL,
  `facebook_id` varchar(256) NOT NULL,
  `twitter_id` varchar(256) NOT NULL,
  `created_at` varchar(256) NOT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`userid`, `username`, `password`, `name`, `email`, `avatar`, `biography`, `notification`, `privated`, `facebook_id`, `twitter_id`, `created_at`) VALUES
(19, 'aaaaaa', 'aaaaaaaa', 'AAA', 'a@yahoo.com', '', '', 0, 0, '', '', '1416784761'),
(20, 'bbbbbb', 'bbbbbbbb', 'BBB', 'b@hotmail.com', 'http://192.168.11.126/leak/uploads/avatars/avatar_1416856864.jpg', '', 0, 0, '', '', '1416856864');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_view`
--

CREATE TABLE IF NOT EXISTS `tbl_view` (
  `viewid` bigint(20) NOT NULL AUTO_INCREMENT,
  `userid` bigint(20) NOT NULL,
  `viewed` bigint(20) NOT NULL,
  `created_at` bigint(20) NOT NULL,
  PRIMARY KEY (`viewid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=64 ;

--
-- Dumping data for table `tbl_view`
--

INSERT INTO `tbl_view` (`viewid`, `userid`, `viewed`, `created_at`) VALUES
(1, 2, 1, 1409802062),
(2, 2, 1, 1409802066),
(3, 4, 2, 1409841794),
(4, 4, 2, 1409851639),
(5, 2, 4, 1409854077),
(6, 2, 4, 1409854409),
(7, 2, 1, 1409854423),
(8, 2, 4, 1409892374),
(9, 2, 4, 1409892464),
(10, 2, 4, 1409933407),
(11, 2, 4, 1409947784),
(12, 2, 3, 1409947798),
(13, 2, 1, 1409947801),
(14, 2, 4, 1409951314),
(15, 2, 4, 1410014930),
(16, 2, 5, 1410112858),
(17, 2, 1, 1410127258),
(18, 2, 5, 1410210488),
(19, 2, 4, 1410210514),
(20, 2, 5, 1410210608),
(21, 2, 5, 1410212085),
(22, 2, 5, 1410212844),
(23, 2, 5, 1410212845),
(24, 2, 4, 1410212876),
(25, 2, 5, 1410213052),
(26, 2, 6, 1410229764),
(27, 2, 6, 1410230157),
(28, 2, 6, 1410230979),
(29, 2, 6, 1410233711),
(30, 2, 4, 1410233722),
(31, 2, 5, 1410233738),
(32, 2, 6, 1410266428),
(33, 7, 1, 1410893978),
(34, 7, 2, 1410893987),
(35, 7, 6, 1410894083),
(36, 2, 7, 1412798667),
(37, 2, 7, 1412819154),
(38, 8, 6, 1412908105),
(39, 8, 6, 1412908241),
(40, 8, 6, 1412908658),
(41, 8, 6, 1412908869),
(42, 8, 6, 1412908963),
(43, 8, 6, 1412912126),
(44, 2, 7, 1412964824),
(45, 8, 4, 1413132206),
(46, 8, 4, 1413132215),
(47, 8, 4, 1413133151),
(48, 8, 7, 1413133315),
(49, 8, 4, 1413133354),
(50, 2, 7, 1413134032),
(51, 2, 7, 1413134054),
(52, 2, 7, 1413134722),
(53, 11, 12, 1416695969),
(54, 11, 12, 1416695984),
(55, 11, 12, 1416696218),
(56, 11, 12, 1416697635),
(57, 12, 11, 1416721956),
(58, 12, 8, 1416724155),
(59, 12, 7, 1416724181),
(60, 12, 2, 1416724313),
(61, 12, 2, 1416724333),
(62, 12, 2, 1416724349),
(63, 12, 9, 1416724492);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
