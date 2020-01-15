-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 17, 2019 at 07:34 AM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 5.6.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


--
-- Table structure for table `tbl_userroles`
--

CREATE TABLE `tbl_userroles` (
  `App_id` varchar(30) NOT NULL,
  `User_id` varchar(10) NOT NULL,
  `Role_id` varchar(20) NOT NULL,
  `User_dept` varchar(100) NOT NULL,
  `User_lastvisitrole` varchar(300) NOT NULL,
  `User_lastvisitdate` datetime NOT NULL,
  `User_status` varchar(30) NOT NULL,
  `User_statusdate` datetime NOT NULL,
  `Remarks` varchar(200) NOT NULL,
  `Created_userid` varchar(10) NOT NULL,
  `Regtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_userroles`
--

INSERT INTO `tbl_userroles` (`App_id`, `User_id`, `Role_id`, `User_dept`, `User_lastvisitrole`, `User_lastvisitdate`, `User_status`, `User_statusdate`, `Remarks`, `Created_userid`, `Regtime`) VALUES
('ALL', 'IN11137', '#SU#', 'SSG', 'ssg@iist.ac.in', '2017-06-07 11:06:17', 'Active', '1970-01-01 00:00:00', '', 'IN11137', '2017-08-04 06:06:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_userroles`
--
ALTER TABLE `tbl_userroles`
  ADD PRIMARY KEY (`App_id`,`User_id`),
  ADD KEY `Role_id` (`Role_id`),
  ADD KEY `App_id` (`App_id`),
  ADD KEY `User_id` (`User_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
