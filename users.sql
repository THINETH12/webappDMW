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
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `full_name` varchar(150) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `address` text NOT NULL,
  `dob` date NOT NULL,
  `contact_number` varchar(10) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `medical_condition` text NOT NULL,
  `role` varchar(20) DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `gender`, `address`, `dob`, `contact_number`, `email`, `password`, `medical_condition`, `role`, `created_at`) VALUES
(1, 'qqqqqqqqqqqqqqqqqqq', 'Male', 'qqqqqqqqqqqqqqqqqq', '2025-06-16', '1234567893', 'cc@gmail.com', '$2y$10$USUG6pyNCt8e1rKHdqUXq.f2KpjL5lj3NpOIV4oTsOlX7akMZ2hXW', 'dddddddddddddddddddddddddddddddddddd', 'user', '2025-06-01 15:40:18'),
(2, 'dula', 'Male', 'adadadadada', '2021-10-26', '0112345678', 'ff@gmail.com', '$2y$10$5NL4PQgR3B0zs7W5hn9mm.eDmhlsRZlvWQguM0uGLvSxYWtbyKr0O', 'ddddddddddddaaaaaaaaaaaaadddddddddddddaaaaaaaaaaaaaaaaaaaaa', 'user', '2025-06-02 03:47:55'),
(3, 'wwwwwwwwwwwww', 'Male', 'wwwwwwwwwwwwwwwwwwwww', '2025-01-05', '1234567893', 'jj@gmail.com', '$2y$10$w7Fw/JcXuCMjLSMadFPV5eWrdaoexFq1e2Z/BvHo501Pg5dp.Jj8.', 'aaaaaaaaaaaaaaaaaaaaaaaaa', 'user', '2025-06-02 16:21:19'),
(9, 'saman', 'Male', 'ss', '2025-06-01', '1234567893', 'saman@gmail.com', '$2y$10$t1ss4NM2rA6dqwfCxorBMOQRgIM3M3flfhoYzk/iiETDuej0Zz.qC', 'good', 'user', '2025-06-03 15:00:55'),
(8, 'admin', 'Male', 'colombo', '2025-06-01', '0112789487', 'admin@gmail.com', '$2y$10$VRMIVlphiHmfMUudPvMVkeRaXMaXcsfvZARYJqH3yWvv9ea9s5k7K', 'good', 'admin', '2025-06-03 03:02:12');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
