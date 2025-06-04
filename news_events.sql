-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 03, 2025 at 05:46 PM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `zigma_hospital_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `news_events`
--

DROP TABLE IF EXISTS `news_events`;
CREATE TABLE IF NOT EXISTS `news_events` (
  `id` int NOT NULL AUTO_INCREMENT,
  `news_event_name` varchar(200) NOT NULL,
  `news_content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `news_events`
--

INSERT INTO `news_events` (`id`, `news_event_name`, `news_content`, `created_at`) VALUES
(7, 'daadad', 'adadadad', '2025-05-30 23:40:44'),
(8, 'aaaaaaa', 'l', '2025-05-31 19:46:15'),
(9, 'dddddddddddddddddd', 'ddddddddddddddddddddddddddddddddddddddddd', '2025-06-01 05:47:13'),
(10, 'aaaaaaaaaaa', 'aaaaaaaaaaaa', '2025-06-03 05:21:22'),
(11, 'qqqqqqqqqqqqqqq', 'qqqqqqqqqqqqqqqqqqqqqqqqqqqqqaaaaaaaaaaaaaaaaaaaaaaa', '2025-06-03 06:16:35'),
(5, 'dadadadadad', 'adadadadadadadadadadada', '2025-05-30 23:31:23'),
(4, 'aaaaaaa', 'dadadadadadadad', '2025-05-30 23:26:31');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
