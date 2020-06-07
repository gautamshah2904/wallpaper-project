-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 07, 2020 at 02:38 PM
-- Server version: 10.3.16-MariaDB
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
-- Database: `id13390975_website`
--

-- --------------------------------------------------------

--
-- Table structure for table `categrious_list`
--

CREATE TABLE `categrious_list` (
  `cat_names` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categrious_list`
--

INSERT INTO `categrious_list` (`cat_names`) VALUES
('Game'),
('Nature'),
('Nature'),
('Background'),
('Bike'),
('movies'),
('Danger'),
('Horror'),
('Hero'),
('Animal'),
('Cartoon'),
('Car'),
('pubg'),
('codm'),
('cod'),
('pubgm');

-- --------------------------------------------------------

--
-- Table structure for table `login_user`
--

CREATE TABLE `login_user` (
  `ip_adr` varchar(100) NOT NULL,
  `email` varchar(30) NOT NULL,
  `login` int(1) NOT NULL DEFAULT 0,
  `matches` int(2) NOT NULL DEFAULT 0,
  `otp_no` int(4) NOT NULL DEFAULT 0,
  `otp_email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `login_user`
--

INSERT INTO `login_user` (`ip_adr`, `email`, `login`, `matches`, `otp_no`, `otp_email`) VALUES
('::1', 'gshah779@gmail.com', 1, 0, 6065, 'gshah779@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `main_cover`
--

CREATE TABLE `main_cover` (
  `main_cover_id` int(2) NOT NULL,
  `main_cover_name` varchar(20) NOT NULL,
  `main_cover_title` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `main_cover`
--

INSERT INTO `main_cover` (`main_cover_id`, `main_cover_name`, `main_cover_title`) VALUES
(1, 'game.jpg', 'Game'),
(2, 'nature.jpg', 'Nature'),
(3, 'background.jpeg', 'Background'),
(4, 'bike.jpg', 'Bike'),
(6, 'danger.jpg', 'Danger'),
(7, 'horror.jpeg', 'Horror'),
(9, 'hero.jpeg', 'Hero'),
(10, 'animal.jpeg', 'Animal'),
(11, 'cartoon.jpeg', 'Cartoon'),
(12, 'car.jpeg', 'Car'),
(5, 'movie.png', 'Movies'),
(13, 'others.jpg', 'others');

-- --------------------------------------------------------

--
-- Table structure for table `picture`
--

CREATE TABLE `picture` (
  `picture_id` int(5) NOT NULL,
  `picture_name` varchar(20) NOT NULL,
  `cat_name` varchar(20) NOT NULL,
  `email` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `picture`
--

INSERT INTO `picture` (`picture_id`, `picture_name`, `cat_name`, `email`) VALUES
(1, 'game1.jpg', 'game', ''),
(2, 'game2.jpg', 'game', 'gshah779@gmail.com'),
(4, 'map.PNG', 'others', 'gshah779@gmail.com'),
(6, 'walpaper12.jpg', 'game', 'gshah779@gmail.com'),
(7, 'walpaper13.jpg', 'others', 'gshah779@gmail.com'),
(8, 'walpaper5.jpg', 'pubg', 'gshah779@gmail.com'),
(9, 'walpaper5.jpg', 'others', 'gshah779@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `related_categrious`
--

CREATE TABLE `related_categrious` (
  `related_id` int(11) NOT NULL,
  `related_name` varchar(20) NOT NULL,
  `cat_name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `related_categrious`
--

INSERT INTO `related_categrious` (`related_id`, `related_name`, `cat_name`) VALUES
(1, 'codm', 'game'),
(2, 'pubg', 'game');

-- --------------------------------------------------------

--
-- Table structure for table `table`
--

CREATE TABLE `table` (
  `id` int(4) NOT NULL,
  `name` int(11) NOT NULL,
  `password` int(11) NOT NULL,
  `email` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(5) NOT NULL,
  `username` varchar(20) NOT NULL,
  `email` varchar(30) NOT NULL,
  `password` varchar(30) NOT NULL,
  `user_image_name` varchar(100) DEFAULT NULL,
  `shared_image_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `picture`
--
ALTER TABLE `picture`
  ADD PRIMARY KEY (`picture_id`);

--
-- Indexes for table `related_categrious`
--
ALTER TABLE `related_categrious`
  ADD PRIMARY KEY (`related_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `picture`
--
ALTER TABLE `picture`
  MODIFY `picture_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
