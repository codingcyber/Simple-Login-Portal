-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 23, 2019 at 02:15 PM
-- Server version: 10.3.15-MariaDB
-- PHP Version: 7.1.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `login-portal`
--
CREATE DATABASE IF NOT EXISTS `login-portal` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `login-portal`;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset`
--

CREATE TABLE `password_reset` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `reset_token` varchar(255) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created`, `updated`) VALUES
(6, 'test', 'test@gmail.com', '$2y$10$j27SRq1xwMCgSueYeHm93emtmo71gUrwfZ/DIKhfk2PdmnYOtVbTO', '2019-07-22 21:08:23', '2019-07-23 12:41:36'),
(7, 'test1', 'test1@gmail.com', '$2y$10$GWrh2OaW9JMhJZ6.4rqgs.Pz62/YwGmrJsYo.yjyogXUuysZGNWGu', '2019-07-22 21:11:16', '2019-07-22 21:11:16'),
(8, 'vivek', 'vivek@codingcyber.com', '$2y$10$2EMIBRVxzpPEIqUkBbi.5OMDJmlgoyRQ8wAVES/GIavSE.5dvHOve', '2019-07-22 21:11:45', '2019-07-23 13:10:21'),
(9, 'vivek1', 'vivek1@codingcyber.com', '$2y$10$s3YkycHU18UFrWfYl3.t..IL6l0ef.HpnlOVdW0TlpbXUh2ObTv/S', '2019-07-22 21:20:44', '2019-07-22 21:20:44'),
(10, 'test2', 'test2@gmail.com', '$2y$10$6dPJc0Jmy.GfPTke7QToz.ECQfSfsf9FC2.rHtLkxzDMgtZFdc30q', '2019-07-23 15:10:38', '2019-07-23 15:10:38'),
(12, 'test3', 'test3@gmail.com', '$2y$10$JEuyLygXMfQyJmMMZJ3.je23UhqW4bI8pR9QQEhYIMcDtsE4mQkRC', '2019-07-23 15:14:53', '2019-07-23 15:14:53');

-- --------------------------------------------------------

--
-- Table structure for table `user_info`
--

CREATE TABLE `user_info` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_info`
--

INSERT INTO `user_info` (`id`, `uid`, `fname`, `lname`, `mobile`, `created`, `updated`) VALUES
(1, 12, '', '', '9876543210', '2019-07-23 15:14:53', '2019-07-23 15:14:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `password_reset`
--
ALTER TABLE `password_reset`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_info`
--
ALTER TABLE `user_info`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `password_reset`
--
ALTER TABLE `password_reset`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `user_info`
--
ALTER TABLE `user_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
