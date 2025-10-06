-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 06, 2025 at 04:17 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ju_lost_found`
--

-- --------------------------------------------------------

--
-- Table structure for table `found_items`
--

CREATE TABLE `found_items` (
  `found_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `found_location` varchar(150) DEFAULT NULL,
  `collection_point` varchar(150) DEFAULT NULL,
  `found_date` date DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `found_items`
--

INSERT INTO `found_items` (`found_id`, `user_id`, `item_name`, `description`, `found_location`, `collection_point`, `found_date`, `image_url`, `created_at`, `is_active`) VALUES
(1, 1, 'Wallet', 'Black', 'CSE', '15 no hall', '2025-10-06', '../uploads/1759733151_Screenshot 2025-10-06 122036.png', '2025-10-06 06:45:51', 1);

-- --------------------------------------------------------

--
-- Table structure for table `lost_items`
--

CREATE TABLE `lost_items` (
  `lost_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `last_seen_location` varchar(150) DEFAULT NULL,
  `lost_date` date DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lost_items`
--

INSERT INTO `lost_items` (`lost_id`, `user_id`, `item_name`, `description`, `last_seen_location`, `lost_date`, `image_url`, `created_at`, `is_active`) VALUES
(1, 1, 'Wallet', 'A black wallet', 'TSC', '2025-10-05', '../uploads/1759731945_Screenshot 2025-10-06 122036.png', '2025-10-06 06:25:45', 1),
(2, 1, 'Wallet', 'A black wallet', 'TSC', '2025-10-05', '../uploads/1759731970_Screenshot 2025-10-06 122036.png', '2025-10-06 06:26:10', 1),
(3, 1, 'Phone', 'Redmi note pro', 'CSE', '2025-10-01', '../uploads/1759732744_Screenshot 2025-10-06 122036.png', '2025-10-06 06:39:04', 1),
(4, 1, 'Phone', 'bla', 'TSC', '2025-10-06', '../uploads/1759733119_Screenshot 2025-10-06 122036.png', '2025-10-06 06:45:19', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `type` enum('student','teacher','staff') NOT NULL,
  `dept` varchar(100) DEFAULT NULL,
  `user_id` varchar(50) NOT NULL,
  `usertype` enum('lost','found') DEFAULT 'lost',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `type`, `dept`, `user_id`, `usertype`, `is_active`, `created_at`) VALUES
(1, 'AT', 'anikatasnim1066@gmail.com', '$2y$10$I.EWL4ryRjV8y2fcc1oBU.II26MULev8PuZfWwckZtG.xH8DC/R..', 'student', 'CSE', '384', 'lost', 1, '2025-10-05 15:49:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `found_items`
--
ALTER TABLE `found_items`
  ADD PRIMARY KEY (`found_id`),
  ADD KEY `fk_found_user` (`user_id`);

--
-- Indexes for table `lost_items`
--
ALTER TABLE `lost_items`
  ADD PRIMARY KEY (`lost_id`),
  ADD KEY `fk_lost_user` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `email_2` (`email`),
  ADD UNIQUE KEY `email_3` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `found_items`
--
ALTER TABLE `found_items`
  MODIFY `found_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `lost_items`
--
ALTER TABLE `lost_items`
  MODIFY `lost_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `found_items`
--
ALTER TABLE `found_items`
  ADD CONSTRAINT `fk_found_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lost_items`
--
ALTER TABLE `lost_items`
  ADD CONSTRAINT `fk_lost_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
