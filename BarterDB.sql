-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Oct 22, 2024 at 12:18 AM
-- Server version: 8.0.35
-- PHP Version: 8.2.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `BarterDB`
--

-- --------------------------------------------------------

--
-- Table structure for table `BulletinBoard`
--

CREATE TABLE `BulletinBoard` (
  `post_id` int NOT NULL,
  `ItemPosted` varchar(50) NOT NULL,
  `ItenRequested` varchar(50) NOT NULL,
  `user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `equivalence_table`
--

CREATE TABLE `equivalence_table` (
  `equivalence_id` int NOT NULL,
  `itema_name` varchar(50) NOT NULL,
  `itemb_name` varchar(50) NOT NULL,
  `equivalence_value` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `item_id` int NOT NULL,
  `value` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transaction_id` int NOT NULL,
  `hash_code` int NOT NULL,
  `status` varchar(50) NOT NULL,
  `itema_name` varchar(50) NOT NULL,
  `itemb_name` varchar(50) NOT NULL,
  `itema_cost` int NOT NULL,
  `itemb_cost` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `partner_id` int DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `HashCodePart` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `partner_id`, `role`, `HashCodePart`) VALUES
(7, 'hailcity16', 'hailcity16@gmail.com', '566ca1c3178fe97afcd2512abd4f36eb', NULL, NULL, NULL),
(8, 'h16', 'h@gmail.com', '566ca1c3178fe97afcd2512abd4f36eb', NULL, NULL, NULL),
(9, '1', '2', 'eccbc87e4b5ce2fe28308fd9f2a7baf3', NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `BulletinBoard`
--
ALTER TABLE `BulletinBoard`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `equivalence_table`
--
ALTER TABLE `equivalence_table`
  ADD PRIMARY KEY (`equivalence_id`),
  ADD KEY `itema_name` (`itema_name`),
  ADD KEY `itemb_name` (`itemb_name`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `idx_itema_name` (`itema_name`),
  ADD KEY `idx_itemb_name` (`itemb_name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `BulletinBoard`
--
ALTER TABLE `BulletinBoard`
  MODIFY `post_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `equivalence_table`
--
ALTER TABLE `equivalence_table`
  MODIFY `equivalence_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `item_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `BulletinBoard`
--
ALTER TABLE `BulletinBoard`
  ADD CONSTRAINT `bulletinboard_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `equivalence_table`
--
ALTER TABLE `equivalence_table`
  ADD CONSTRAINT `equivalence_table_ibfk_1` FOREIGN KEY (`itema_name`) REFERENCES `transactions` (`itema_name`) ON DELETE CASCADE,
  ADD CONSTRAINT `equivalence_table_ibfk_2` FOREIGN KEY (`itemb_name`) REFERENCES `transactions` (`itemb_name`) ON DELETE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
