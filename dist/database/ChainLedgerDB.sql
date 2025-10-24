-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 24, 2025 at 03:17 PM
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
-- Database: `chainledgerdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `archivedaccounts`
--

CREATE TABLE `archivedaccounts` (
  `archived_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `birthdate` date DEFAULT NULL,
  `gender` enum('Male','Female') DEFAULT NULL,
  `username` varchar(100) NOT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `date_registered` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `archived_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `archivedlogs`
--

CREATE TABLE `archivedlogs` (
  `archived_log_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  `username` varchar(100) NOT NULL,
  `action` enum('LOGIN','LOGOUT','PASSWORD_CHANGE','ACCOUNT_CREATED','ACCOUNT_DELETED','TRANSACTION_ADDED','TRANSACTION_DELETED','TRANSACTION_EDITED','FAILED_LOGIN') NOT NULL,
  `action_details` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `device_info` text DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  `archived_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `archivedtransactions`
--

CREATE TABLE `archivedtransactions` (
  `transaction_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `old_account_id` int(11) DEFAULT NULL,
  `old_username` varchar(255) DEFAULT NULL,
  `username` varchar(100) NOT NULL,
  `detail` enum('Food','Equipment','Transportation','Health','Maintenance','Utilities') NOT NULL,
  `merchant` enum('Gcash','Maya','Grabpay','Paypal','Googlepay') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(10) DEFAULT 'PHP',
  `transaction_date` date NOT NULL DEFAULT curdate(),
  `entry_date` datetime DEFAULT current_timestamp(),
  `transaction_type` enum('DEPOSIT','WITHDRAWAL','TRANSFER','PAYMENT','REFUND') NOT NULL,
  `status` enum('PENDING','COMPLETED','FAILED','CANCELLED') NOT NULL,
  `archived_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `company_owners`
--

CREATE TABLE `company_owners` (
  `owner_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `company_role` enum('Business Owner') DEFAULT 'Business Owner'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `company_personnel`
--

CREATE TABLE `company_personnel` (
  `personnel_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `company_role` enum('Business Owner','Manager','Staff') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `registered_accounts`
--

CREATE TABLE `registered_accounts` (
  `account_business_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `business_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `role` enum('Business Owner','Manager','Staff') NOT NULL,
  `date_registered` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `registered_businesses`
--

CREATE TABLE `registered_businesses` (
  `business_id` int(11) NOT NULL,
  `business_name` varchar(255) NOT NULL,
  `business_industry` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `date_registered` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `security`
--

CREATE TABLE `security` (
  `security_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `security_question` varchar(255) NOT NULL,
  `security_answer` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `security_logs`
--

CREATE TABLE `security_logs` (
  `security_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `account_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `action` varchar(255) NOT NULL,
  `action_details` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `device_info` text DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transaction_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `detail` enum('Food','Equipment','Transportation','Health','Maintenance','Utilities') NOT NULL,
  `merchant` enum('Gcash','Maya','Grabpay','Paypal','Googlepay') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(10) DEFAULT 'PHP',
  `transaction_date` date NOT NULL DEFAULT curdate(),
  `entry_date` datetime DEFAULT current_timestamp(),
  `transaction_type` enum('DEPOSIT','WITHDRAWAL','TRANSFER','PAYMENT','REFUND') NOT NULL,
  `status` enum('COMPLETED') NOT NULL DEFAULT 'COMPLETED'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `birthdate` date DEFAULT NULL,
  `gender` enum('Male','Female','Others') DEFAULT NULL,
  `username` varchar(100) NOT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `date_registered` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `archivedaccounts`
--
ALTER TABLE `archivedaccounts`
  ADD PRIMARY KEY (`archived_id`);

--
-- Indexes for table `archivedlogs`
--
ALTER TABLE `archivedlogs`
  ADD PRIMARY KEY (`archived_log_id`);

--
-- Indexes for table `archivedtransactions`
--
ALTER TABLE `archivedtransactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `username` (`username`);

--
-- Indexes for table `company_owners`
--
ALTER TABLE `company_owners`
  ADD PRIMARY KEY (`owner_id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `username` (`username`);

--
-- Indexes for table `company_personnel`
--
ALTER TABLE `company_personnel`
  ADD PRIMARY KEY (`personnel_id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `username` (`username`);

--
-- Indexes for table `registered_accounts`
--
ALTER TABLE `registered_accounts`
  ADD PRIMARY KEY (`account_business_id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `business_id` (`business_id`),
  ADD KEY `username` (`username`);

--
-- Indexes for table `registered_businesses`
--
ALTER TABLE `registered_businesses`
  ADD PRIMARY KEY (`business_id`),
  ADD UNIQUE KEY `business_name` (`business_name`);

--
-- Indexes for table `security`
--
ALTER TABLE `security`
  ADD PRIMARY KEY (`security_id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `username` (`username`);

--
-- Indexes for table `security_logs`
--
ALTER TABLE `security_logs`
  ADD PRIMARY KEY (`security_id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `username` (`username`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `username` (`username`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `account_id` (`account_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `archivedaccounts`
--
ALTER TABLE `archivedaccounts`
  MODIFY `archived_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `archivedlogs`
--
ALTER TABLE `archivedlogs`
  MODIFY `archived_log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `archivedtransactions`
--
ALTER TABLE `archivedtransactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `company_owners`
--
ALTER TABLE `company_owners`
  MODIFY `owner_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `company_personnel`
--
ALTER TABLE `company_personnel`
  MODIFY `personnel_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `registered_accounts`
--
ALTER TABLE `registered_accounts`
  MODIFY `account_business_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `security`
--
ALTER TABLE `security`
  MODIFY `security_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `security_logs`
--
ALTER TABLE `security_logs`
  MODIFY `security_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `company_owners`
--
ALTER TABLE `company_owners`
  ADD CONSTRAINT `company_owners_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `users` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `company_owners_ibfk_2` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `company_personnel`
--
ALTER TABLE `company_personnel`
  ADD CONSTRAINT `company_personnel_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `users` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `company_personnel_ibfk_2` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `registered_accounts`
--
ALTER TABLE `registered_accounts`
  ADD CONSTRAINT `registered_accounts_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `users` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `registered_accounts_ibfk_2` FOREIGN KEY (`business_id`) REFERENCES `registered_businesses` (`business_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `registered_accounts_ibfk_3` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `security`
--
ALTER TABLE `security`
  ADD CONSTRAINT `security_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `users` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `security_ibfk_2` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `security_logs`
--
ALTER TABLE `security_logs`
  ADD CONSTRAINT `security_logs_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `users` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `security_logs_ibfk_2` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `users` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
