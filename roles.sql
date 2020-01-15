-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 19, 2019 at 10:36 AM
-- Server version: 5.5.36
-- PHP Version: 5.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `db_admin`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_roles`
--

CREATE TABLE IF NOT EXISTS `tbl_roles` (
  `App_id` varchar(30) NOT NULL,
  `Role_id` varchar(20) NOT NULL,
  `Role_name` varchar(200) NOT NULL,
  `Role_description` text NOT NULL,
  `Remarks` int(11) NOT NULL,
  `User_id` varchar(10) NOT NULL,
  `Regtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`App_id`,`Role_id`),
  KEY `Role_id` (`Role_id`),
  KEY `App_id` (`App_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_roles`
--

INSERT INTO `tbl_roles` (`App_id`, `Role_id`, `Role_name`, `Role_description`, `Remarks`, `User_id`, `Regtime`) VALUES
('ALL', 'SU', 'Super User', 'All powers', 0, '1', '2014-05-29 09:26:42'),
('ALL', 'DIR', 'Director', 'Director', 0, 'IN11137', '2015-10-01 04:40:20'),
('ALL', 'RGR', 'Registrar', 'Registrar', 0, 'IN10125', '2018-01-07 22:31:08'),
('ALL', 'FC', 'Faculty', 'Faculty', 0, '1', '2014-06-27 01:33:40'),
('ALL', 'ST', 'Student', 'Student', 0, 'IN10125', '2014-07-02 07:49:17');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
