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
-- Table structure for table `doctors`
--

DROP TABLE IF EXISTS `doctors`;
CREATE TABLE IF NOT EXISTS `doctors` (
  `id` int NOT NULL AUTO_INCREMENT,
  `doctor_full_name` varchar(255) NOT NULL,
  `doctor_contact` varchar(15) NOT NULL,
  `doctor_email` varchar(255) NOT NULL,
  `doctor_hospital` varchar(255) NOT NULL,
  `doctor_job_title` varchar(255) NOT NULL,
  `doctor_category` varchar(255) NOT NULL,
  `doctor_availability` text,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `doctor_full_name`, `doctor_contact`, `doctor_email`, `doctor_hospital`, `doctor_job_title`, `doctor_category`, `doctor_availability`, `created_at`) VALUES
(5, 'nnnnnnnnnnnnn', '3216549873', 'hh@gmail.com', 'dadad', 'rrrrrr', 'Neurology', '{\"monday\":{\"active\":\"true\",\"start\":\"00:17\",\"end\":\"01:17\"}}', '2025-06-01 11:17:39'),
(6, 'bbbbbbbb', '9876543215', 'rrr@gmail.com', 'vvvvvvvv', 'rrrrrrrrrrrrr', 'Pediatrics', '{\"thursday\":{\"active\":\"true\",\"start\":\"11:09\",\"end\":\"00:09\"},\"saturday\":{\"active\":\"true\",\"start\":\"01:09\",\"end\":\"02:09\"}}', '2025-06-02 08:09:42'),
(7, 'dddddddddddd', '9876543215', 'da@gmail.com', 'dddddddddddddddddddddd', 'cccccccccccccc', 'Psychiatry', '{\"saturday\":{\"active\":\"true\",\"start\":\"10:15\",\"end\":\"11:15\"},\"sunday\":{\"active\":\"true\",\"start\":\"01:15\",\"end\":\"02:15\"}}', '2025-06-02 09:15:39'),
(9, 'qqqqqqqqqqqqqq', '3216549873', 'qq@gmail.com', 'qqqqqqqqqqqq', 'qqqqqqqqqqqqq', 'Dermatology', '{\"monday\":{\"active\":\"true\",\"start\":\"00:46\",\"end\":\"01:46\"},\"tuesday\":{\"active\":\"true\",\"start\":\"02:46\",\"end\":\"03:46\"}}', '2025-06-03 11:46:26');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
