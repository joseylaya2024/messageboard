-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 11, 2024 at 10:56 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `message_board`
--

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE `conversations` (
  `id` int(11) NOT NULL,
  `userId_1` int(11) NOT NULL,
  `userId_2` int(11) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `conversations`
--

INSERT INTO `conversations` (`id`, `userId_1`, `userId_2`, `createdAt`) VALUES
(130, 6, 5, '2024-04-11 05:48:04'),
(131, 4, 5, '2024-04-11 06:29:34');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `conversationId` int(11) NOT NULL,
  `senderId` int(11) NOT NULL,
  `recipientId` int(11) NOT NULL,
  `messageContent` text NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp(),
  `modifiedAt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `conversationId`, `senderId`, `recipientId`, `messageContent`, `createdAt`, `modifiedAt`) VALUES
(37, 131, 5, 4, 'hey', '2024-04-11 14:29:40', '2024-04-11 14:29:40'),
(40, 130, 5, 6, 'how are you?', '2024-04-11 16:48:08', '2024-04-11 16:48:08');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `imageLink` varchar(255) DEFAULT NULL,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp(),
  `modifiedAt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `username`, `password`, `imageLink`, `createdAt`, `modifiedAt`) VALUES
(4, 'test', 'jmtest@gmail.com', 'test', 'ab9e8d013429df47fca682be2a36ee733a1afa9e', 'message-board/app/webroot/img/uploads/2024/04/03/660d14dd3debe-5.jpg', '2024-04-02 11:02:21', '2024-04-02 11:02:21'),
(5, 'jm', 'jm@gmail.com', 'jm', '0e597d44a5bafac2ddd0bf63bb1a48f07e15786e', 'message-board/app/webroot/img/uploads/2024/04/03/660ce5f732fa2-Screenshot_2024-04-02_at_3.43.04_PM.png', '2024-04-02 11:53:23', '2024-04-02 11:53:23'),
(6, 'juan', 'juan@gmail.com', 'juan', '0e597d44a5bafac2ddd0bf63bb1a48f07e15786e', 'message-board/app/webroot/img/uploads/2024/04/03/660d14e6baa54-6.jpg', '2024-04-02 11:54:31', '2024-04-02 11:54:31'),
(7, 'pedro', 'pedro@gmail.com', 'pedro', '0e597d44a5bafac2ddd0bf63bb1a48f07e15786e', 'message-board/app/webroot/img/uploads/2024/04/03/660d14ee507fe-images.jpg', '2024-04-02 11:55:13', '2024-04-02 11:55:13'),
(8, 'test2', 'jmtest@gmail.com', 'test2', 'cd1ebbaa04918e409d41069e66ec3696f5e1776c', 'message-board/app/webroot/img/uploads/2024/04/03/660d14f8a36ed-images2.jpg', '2024-04-03 13:41:18', '2024-04-03 13:41:18'),
(9, 'test3', 'jm@gmail.com', 'test3', 'ab9e8d013429df47fca682be2a36ee733a1afa9e', 'message-board/app/webroot/img/uploads/2024/04/03/660d14fcbdb47-images3.jpg', '2024-04-03 13:43:18', '2024-04-03 13:43:18'),
(10, 'test4', 'test4@gmail.com', 'test4', 'ab9e8d013429df47fca682be2a36ee733a1afa9e', 'message-board/app/webroot/img/uploads/2024/04/03/660d150131462-istockphoto-1300512215-170667a.webp', '2024-04-03 13:47:25', '2024-04-03 13:47:25'),
(11, 'test5', 'test4@gmail.com', 'test5', 'ab9e8d013429df47fca682be2a36ee733a1afa9e', 'message-board/app/webroot/img/uploads/2024/04/03/660d15096e4c3-istockphoto-1317804578-612x612.jpg', '2024-04-03 13:48:07', '2024-04-03 13:48:07'),
(12, 'test6', 'jmtest@gmail.com', 'test6', 'ab9e8d013429df47fca682be2a36ee733a1afa9e', 'message-board/app/webroot/img/uploads/2024/04/03/660d1511a9db7-6.jpg', '2024-04-03 13:49:32', '2024-04-03 13:49:32'),
(13, 'test7', 'jmtest@gmail.com', 'test7', '0e597d44a5bafac2ddd0bf63bb1a48f07e15786e', 'message-board/app/webroot/img/uploads/2024/04/03/660cfbc81c2f4-Screenshot_2024-04-03_at_10.30.11_AM.png', '2024-04-03 14:48:26', '2024-04-03 14:48:26'),
(14, 'rwqewewq', 'ewqeqwewqdsa@dsadas.dsadsa', 'dsadsa', 'ab9e8d013429df47fca682be2a36ee733a1afa9e', NULL, '2024-04-11 11:40:03', '2024-04-11 11:40:03'),
(15, 'rwqewewq', 'ewqeqdsadasdsawewqdsa@dsaddsadsadsaas.dsadsa', 'dsadsa', 'ab9e8d013429df47fca682be2a36ee733a1afa9e', NULL, '2024-04-11 11:42:00', '2024-04-11 11:42:00'),
(16, 'dsadsa', 'dasdsadsa312321@dsadas.dsadas', 'dsadsa', '4bff0c24ccce31f01b611c0e937013c9d1895f50', NULL, '2024-04-11 11:42:33', '2024-04-11 11:42:33'),
(17, 'dsadsadsaewqewq', 'ewqeqwewq@dsadasxzcx.dsadsadsa', 'dsadsa', '87f182217f947a9041e4dfbf2d3c8560b2e28c84', NULL, '2024-04-11 11:44:40', '2024-04-11 11:44:40'),
(18, 'xcxzcxzc', 'cxcxzcxz@dsadxzcxz.comdsadsa', 'dasdasdsa', 'ab9e8d013429df47fca682be2a36ee733a1afa9e', NULL, '2024-04-11 11:49:24', '2024-04-11 11:49:24'),
(19, 'dsadas', 'dsadas@mdsadasds.dsada', 'dsadasda', 'ab9e8d013429df47fca682be2a36ee733a1afa9e', NULL, '2024-04-11 11:49:55', '2024-04-11 11:49:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
