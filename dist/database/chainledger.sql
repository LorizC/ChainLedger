-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 02, 2025 at 09:08 AM
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
-- Database: `chainledger`
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

--
-- Dumping data for table `archivedtransactions`
--

INSERT INTO `archivedtransactions` (`transaction_id`, `account_id`, `old_account_id`, `old_username`, `username`, `detail`, `merchant`, `amount`, `currency`, `transaction_date`, `entry_date`, `transaction_type`, `status`, `archived_at`) VALUES
(1, 233348, 233348, 'Loriz Neil Carlos', 'Loriz Neil Carlos', 'Equipment', 'Gcash', 49999.00, 'PHP', '2021-01-28', '2025-10-28 15:18:23', 'PAYMENT', 'COMPLETED', '2025-10-28 22:34:02'),
(2, 762308, 762308, 'Maki Roll', 'Maki Roll', 'Maintenance', 'Paypal', 999.00, 'PHP', '2025-08-26', '2025-10-28 16:12:09', 'REFUND', 'COMPLETED', '2025-10-28 22:35:24'),
(3, 762308, 762308, 'Maki Roll', 'Maki Roll', 'Maintenance', 'Grabpay', 99.00, 'PHP', '2024-01-22', '2025-10-28 17:32:43', 'DEPOSIT', 'COMPLETED', '2025-10-28 22:27:24'),
(6, 762308, 762308, 'Maki Roll', 'Maki Roll', 'Health', 'Maya', 99.00, 'PHP', '2025-11-28', '2025-11-28 21:50:45', 'TRANSFER', 'COMPLETED', '2025-10-28 21:51:39');

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

--
-- Dumping data for table `company_owners`
--

INSERT INTO `company_owners` (`owner_id`, `account_id`, `username`, `company_role`) VALUES
(1, 233348, 'Loriz Neil Carlos', 'Business Owner'),
(2, 387797, 'Lady Queen', 'Business Owner'),
(3, 360277, 'John Johny', 'Business Owner');

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

--
-- Dumping data for table `company_personnel`
--

INSERT INTO `company_personnel` (`personnel_id`, `account_id`, `username`, `company_role`) VALUES
(1, 233348, 'Loriz Neil Carlos', 'Business Owner'),
(2, 762308, 'Maki Roll', 'Staff'),
(3, 516229, 'Choco Latte', 'Manager'),
(4, 941349, 'John Lee', 'Staff'),
(5, 840500, 'Jane Doe', 'Manager'),
(6, 483855, 'John Ng', 'Staff'),
(7, 428282, 'Loid Dean', 'Manager'),
(8, 339608, 'Mary Jane', 'Staff'),
(9, 387797, 'Lady Queen', 'Business Owner'),
(10, 400969, 'King TIger', 'Manager'),
(11, 457644, 'Mikey Mouse', 'Staff'),
(12, 360277, 'John Johny', 'Business Owner');

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

--
-- Dumping data for table `security`
--

INSERT INTO `security` (`security_id`, `account_id`, `username`, `security_question`, `security_answer`, `password`) VALUES
(1, 233348, 'Loriz Neil Carlos', 'First Pet', '$2y$10$8UFx.Tb0U6Fd9qQxBiKnl.4CGWK6QHUD1RoJk6oJ0/1igfR8fGiRm', '$2y$10$W6GZ971QHPyGUpOhR0NMWe51LVKXez2fEBne7T2ceYiJSZ8PSMkCu'),
(2, 762308, 'Maki Roll', 'First Pet', '$2y$10$63XG5tjsN58nZ3Y.b6A4JOX/mIlG4Cg3wj8Fsu4ie45tPZGJtCQKK', '$2y$10$cxey9KK8gZsBMNkw0UxXguqbbnwrVpcrznrtbzA3p3sP7UZJUznBa'),
(3, 516229, 'Choco Latte', 'First Pet', '$2y$10$Jt7gBr5sANoLtRIs1ExEAemqUEzCXqwctU6Y1gh5YpCSc.CV8jPi6', '$2y$10$p0aFGrt7bf6UCvdAQw7SaeXpfSskzRnA0WHPmCutGNfNEbiWndA8q'),
(4, 941349, 'John Lee', 'First Pet', '$2y$10$JA03p7Ebe/X1mUEJGLYjbO8KyyxY.mEwWH2qPNUD4KVrNT3AoaB8i', '$2y$10$VTS4RcFK.Ue4YuTsdD8qhu3eibCqjjAbFbb3DF/vTRDi3tJ9i.i.C'),
(5, 840500, 'Jane Doe', 'Elementary School', '$2y$10$i/j/uR2u2.no1lXxOW2GAuNG5T15yHw9N9uKpEe0LnH3cLwiGOpeC', '$2y$10$Rs6cNleNdfidB9wRSaPIXumb2Trr8R2JHALeX4Ft8hbRgKJE3AptK'),
(6, 428282, 'Loid Dean', 'Favorite Food', '$2y$10$aCrkOGJb/Yqi40O/HsBrBubNrhXKs62oii1vXwmmLRuoYW0Qbdcp.', '$2y$10$Uqb/ZASrBUdJczBnfWX1..6GiumcEVPTEiHZLW3eW2UlRBecy8V8a'),
(7, 483855, 'John Ng', 'Childhood Nickname', '$2y$10$jJUAI6qyf6/pwZkgvt./DOezytWoH/faIPwkLQZ7uVyEmuqrnwkje', '$2y$10$wOzhf8lHbgJhr2cHleQOYuZfOh/30Rt506EcQOo06i3QHaTHBokoW'),
(8, 339608, 'Mary Jane', 'Childhood Nickname', '$2y$10$I5usn7gGIJoPVjlf4hy7lurOmzw6aB.toO.Ty16Ek24f5lGdFSgnS', '$2y$10$YBrzB/ur2NupTJlFoKcTcujCjVU3nwpwolE2/nkFgZ2bKdaag2YBu'),
(9, 387797, 'Lady Queen', 'First Pet', '$2y$10$qyve2w17zatZ7AZrEq7vk.hAClcZTTxCPNUDCvNObsazWb9AqpEmG', '$2y$10$OWYPJE/.4WneTd.awLBhaeB8eTUmEUsBwm7nsehQegx6Rj1uQXrX2'),
(10, 400969, 'King TIger', 'First Pet', '$2y$10$.cse/fF441gWHzPYmZNHxu3ptghcAKkF6ugkbKAfpen1AtOoktf3m', '$2y$10$h6/JrNNTAk9Jd7XtEPXA2udFOI9LY/xN2zXqS5hi3Tmgw6ywSYYlS'),
(11, 457644, 'Mikey Mouse', 'First Pet', '$2y$10$PUu2bQyfxQF6Ws/bxhlDbeZZetfBdXKD2Wn4G5lBvwQihajrTtdMC', '$2y$10$D7mK9v14na3GHJapFvcAiOEYds38ymp9zqVg85tFzR/DbGInozJzq'),
(12, 360277, 'John Johny', 'First Pet', '$2y$10$Gw0ntplulXodbPxtd5h7quljV9yhlKnE0hmZBUcKYzsm9YSHxEkIK', '$2y$10$PEjC/gICzEZKKPNnDHxk/.N/6U.QsyfGEZTd.1BvwrxgiBfNuqJ2m');

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

--
-- Dumping data for table `security_logs`
--

INSERT INTO `security_logs` (`security_id`, `user_id`, `account_id`, `username`, `action`, `action_details`, `ip_address`, `device_info`, `user_agent`, `timestamp`) VALUES
(1, 1, 233348, 'Loriz Neil Carlos', 'ACCOUNT_CREATED', NULL, '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 07:13:52'),
(2, 1, 233348, 'Loriz Neil Carlos', 'PASSWORD_CHANGE', NULL, '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 07:17:25'),
(3, 1, 233348, 'Loriz Neil Carlos', 'LOGIN', NULL, '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 07:17:38'),
(4, 1, 233348, 'Loriz Neil Carlos', 'TRANSACTION_ADDED', NULL, '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 07:18:23'),
(5, 1, 233348, 'Loriz Neil Carlos', 'TRANSACTION_EDITED', 'Edited transaction #1 - Equipment, ₱50000, PAYMENT', '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 07:19:13'),
(6, 1, 233348, 'Loriz Neil Carlos', 'TRANSACTION_EDITED', 'Edited transaction #1 - Equipment, ₱49999, PAYMENT', '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 07:19:31'),
(7, 2, 762308, 'Maki Roll', 'ACCOUNT_CREATED', NULL, '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 07:47:57'),
(8, 2, 762308, 'Maki Roll', 'PASSWORD_CHANGE', NULL, '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 07:56:48'),
(9, 2, 762308, 'Maki Roll', 'LOGIN', NULL, '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 08:11:21'),
(10, 2, 762308, 'Maki Roll', 'TRANSACTION_ADDED', NULL, '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 08:12:09'),
(11, 2, 762308, 'Maki Roll', 'TRANSACTION_ADDED', NULL, '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 09:32:43'),
(12, 3, 516229, 'Choco Latte', 'ACCOUNT_CREATED', NULL, '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 12:32:05'),
(13, 3, 516229, 'Choco Latte', 'LOGIN', NULL, '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 12:32:28'),
(14, 2, 762308, 'Maki Roll', 'LOGIN', NULL, '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 12:33:14'),
(15, 1, 233348, 'Loriz Neil Carlos', 'LOGIN', NULL, '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 12:33:37'),
(16, 3, 516229, 'Choco Latte', 'TRANSACTION_ADDED', NULL, '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 12:52:01'),
(17, 3, 516229, 'Choco Latte', 'TRANSACTION_EDITED', 'Edited transaction #4 - Utilities, ₱9999.99, WITHDRAWAL', '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 13:03:13'),
(18, 3, 516229, 'Choco Latte', 'TRANSACTION_EDITED', 'Edited transaction #4 - Utilities, ₱9999.99, WITHDRAWAL', '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 13:17:51'),
(19, 1, 233348, 'Loriz Neil Carlos', 'TRANSACTION_EDITED', 'Edited transaction #4 - Utilities, ₱9999.99, WITHDRAWAL', '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 13:21:27'),
(20, 1, 233348, 'Loriz Neil Carlos', 'TRANSACTION_EDITED', 'Edited transaction #1 - Equipment, ₱49999, PAYMENT', '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 13:22:30'),
(21, 1, 233348, 'Loriz Neil Carlos', 'TRANSACTION_EDITED', 'Edited transaction #4 - Utilities, ₱9999.99, WITHDRAWAL', '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 13:24:56'),
(22, 1, 233348, 'Loriz Neil Carlos', 'TRANSACTION_EDITED', 'Edited transaction #1 - Equipment, ₱49999, PAYMENT', '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 13:26:08'),
(23, 1, 233348, 'Loriz Neil Carlos', 'TRANSACTION_ADDED', NULL, '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 13:41:17'),
(24, 1, 233348, 'Loriz Neil Carlos', 'TRANSACTION_EDITED', 'Edited transaction #5 - Transportation, ₱5000, TRANSFER', '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 13:46:43'),
(25, 1, 233348, 'Loriz Neil Carlos', 'TRANSACTION_EDITED', 'Edited transaction #5 - Transportation, ₱5000, TRANSFER', '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 13:47:03'),
(27, 1, 233348, 'Loriz Neil Carlos', 'DELETE_TRANSACTION', 'Transaction #1 (\'Equipment\', Amount: PHP 49,999.00) was archived and deleted by Loriz Neil Carlos.', '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 14:34:02'),
(28, 3, 516229, 'Choco Latte', 'DELETE_TRANSACTION', 'Transaction #2 (\'Maintenance\', Amount: PHP 999.00) was archived and deleted by Choco Latte.', '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 14:35:24'),
(29, 4, 941349, 'John Lee', 'ACCOUNT_CREATED', NULL, '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 14:59:16'),
(30, 5, 840500, 'Jane Doe', 'ACCOUNT_CREATED', NULL, '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 15:03:15'),
(31, 2, 762308, 'Maki Roll', 'LOGOUT', NULL, '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 15:14:46'),
(32, 7, 483855, 'John Ng', 'ACCOUNT_CREATED', NULL, '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 15:15:23'),
(33, 6, 428282, 'Loid Dean', 'ACCOUNT_CREATED', NULL, '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 15:24:40'),
(34, 3, 516229, 'Choco Latte', 'LOGOUT', NULL, '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 15:35:54'),
(35, 8, 339608, 'Mary Jane', 'ACCOUNT_CREATED', NULL, '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 15:38:05'),
(36, 9, 387797, 'Lady Queen', 'ACCOUNT_CREATED', NULL, '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 15:40:50'),
(37, 9, 387797, 'Lady Queen', 'PASSWORD_CHANGE', NULL, '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 15:43:25'),
(38, 10, 400969, 'King TIger', 'ACCOUNT_CREATED', NULL, '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 15:44:25'),
(39, 11, 457644, 'Mikey Mouse', 'ACCOUNT_CREATED', NULL, '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 15:44:57'),
(40, 3, 516229, 'Choco Latte', 'LOGIN', NULL, '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 15:46:28'),
(41, 1, 233348, 'Loriz Neil Carlos', 'TRANSACTION_ADDED', NULL, '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 15:48:49'),
(42, 1, 233348, 'Loriz Neil Carlos', 'TRANSACTION_ADDED', NULL, '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 15:49:03'),
(43, 1, 233348, 'Loriz Neil Carlos', 'TRANSACTION_ADDED', NULL, '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 15:49:27'),
(44, 1, 233348, 'Loriz Neil Carlos', 'TRANSACTION_ADDED', NULL, '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 15:49:49'),
(45, 1, 233348, 'Loriz Neil Carlos', 'TRANSACTION_ADDED', NULL, '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 15:50:21'),
(46, 3, 516229, 'Choco Latte', 'TRANSACTION_ADDED', NULL, '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 16:01:43'),
(47, 3, 516229, 'Choco Latte', 'TRANSACTION_ADDED', NULL, '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 16:02:01'),
(48, 3, 516229, 'Choco Latte', 'LOGOUT', NULL, '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 16:06:44'),
(49, 11, 457644, 'Mikey Mouse', 'LOGIN', NULL, '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 16:06:48'),
(50, 11, 457644, 'Mikey Mouse', 'TRANSACTION_ADDED', NULL, '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 16:08:16'),
(51, 11, 457644, 'Mikey Mouse', 'TRANSACTION_ADDED', NULL, '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 16:08:23'),
(52, 11, 457644, 'Mikey Mouse', 'TRANSACTION_ADDED', NULL, '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 16:08:31'),
(53, 11, 457644, 'Mikey Mouse', 'TRANSACTION_ADDED', NULL, '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 16:08:42'),
(54, 11, 457644, 'Mikey Mouse', 'TRANSACTION_ADDED', NULL, '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 16:08:54'),
(55, 11, 457644, 'Mikey Mouse', 'TRANSACTION_ADDED', NULL, '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 16:09:06'),
(56, 1, 233348, 'Loriz Neil Carlos', 'TRANSACTION_ADDED', NULL, '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 16:12:22'),
(57, 11, 457644, 'Mikey Mouse', 'LOGOUT', NULL, '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 16:12:31'),
(58, 1, 233348, 'Loriz Neil Carlos', 'LOGOUT', NULL, '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-28 16:12:40'),
(59, 1, 233348, 'Loriz Neil Carlos', 'LOGIN', NULL, '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 00:55:31'),
(60, 2, 762308, 'Maki Roll', 'LOGIN', NULL, '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 00:56:10'),
(61, 3, 516229, 'Choco Latte', 'LOGIN', NULL, '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 00:56:35'),
(62, 2, 762308, 'Maki Roll', 'TRANSACTION_ADDED', NULL, '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 01:10:32'),
(63, 12, 360277, 'John Johny', 'ACCOUNT_CREATED', NULL, '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 02:09:48'),
(64, 12, 360277, 'John Johny', 'LOGIN', NULL, '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 02:10:06'),
(65, 12, 360277, 'John Johny', 'TRANSACTION_ADDED', NULL, '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 02:15:02'),
(66, 2, 762308, 'Maki Roll', 'TRANSACTION_ADDED', NULL, '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 02:28:29'),
(67, 3, 516229, 'Choco Latte', 'LOGIN', NULL, '::1', 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-11-02 07:42:08');

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

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`transaction_id`, `account_id`, `username`, `detail`, `merchant`, `amount`, `currency`, `transaction_date`, `entry_date`, `transaction_type`, `status`) VALUES
(4, 516229, 'Choco Latte', 'Utilities', 'Maya', 9999.99, 'PHP', '2023-05-24', '2025-10-28 20:52:01', 'WITHDRAWAL', 'COMPLETED'),
(5, 233348, 'Loriz Neil Carlos', 'Transportation', 'Paypal', 5000.00, 'PHP', '2025-10-28', '2025-10-28 21:41:17', 'TRANSFER', 'COMPLETED'),
(7, 233348, 'Loriz Neil Carlos', 'Equipment', 'Gcash', 11111.00, 'PHP', '2025-10-28', '2025-10-28 23:48:49', 'PAYMENT', 'COMPLETED'),
(8, 233348, 'Loriz Neil Carlos', 'Food', 'Grabpay', 999.00, 'PHP', '2025-10-28', '2025-10-28 23:49:03', 'REFUND', 'COMPLETED'),
(9, 233348, 'Loriz Neil Carlos', 'Health', 'Googlepay', 99999.00, 'PHP', '2025-07-08', '2025-10-28 23:49:27', 'WITHDRAWAL', 'COMPLETED'),
(10, 233348, 'Loriz Neil Carlos', 'Maintenance', 'Maya', 88888.00, 'PHP', '2025-10-14', '2025-10-28 23:49:49', 'DEPOSIT', 'COMPLETED'),
(11, 233348, 'Loriz Neil Carlos', 'Utilities', 'Paypal', 9888.99, 'PHP', '2025-06-10', '2025-10-28 23:50:21', 'TRANSFER', 'COMPLETED'),
(12, 516229, 'Choco Latte', 'Health', 'Googlepay', 9999.00, 'PHP', '2025-10-28', '2025-10-29 00:01:43', 'WITHDRAWAL', 'COMPLETED'),
(13, 516229, 'Choco Latte', 'Maintenance', 'Grabpay', 1212121.00, 'PHP', '2025-09-28', '2025-10-29 00:02:01', 'REFUND', 'COMPLETED'),
(14, 457644, 'Mikey Mouse', 'Food', 'Grabpay', 9999.00, 'PHP', '2025-10-07', '2025-10-29 00:08:16', 'PAYMENT', 'COMPLETED'),
(15, 457644, 'Mikey Mouse', 'Maintenance', 'Grabpay', 123123.00, 'PHP', '2025-10-08', '2025-10-29 00:08:23', 'REFUND', 'COMPLETED'),
(16, 457644, 'Mikey Mouse', 'Food', 'Grabpay', 11111.00, 'PHP', '2025-10-12', '2025-10-29 00:08:31', 'REFUND', 'COMPLETED'),
(17, 457644, 'Mikey Mouse', 'Maintenance', 'Paypal', 22222.00, 'PHP', '2025-10-28', '2025-10-29 00:08:42', 'DEPOSIT', 'COMPLETED'),
(18, 457644, 'Mikey Mouse', 'Transportation', 'Maya', 33333.00, 'PHP', '2025-10-26', '2025-10-29 00:08:54', 'TRANSFER', 'COMPLETED'),
(19, 457644, 'Mikey Mouse', 'Transportation', 'Maya', 11111.00, 'PHP', '2025-10-28', '2025-10-29 00:09:06', 'DEPOSIT', 'COMPLETED'),
(20, 233348, 'Loriz Neil Carlos', 'Maintenance', 'Grabpay', 99.00, 'PHP', '2025-10-28', '2025-10-29 00:12:22', 'DEPOSIT', 'COMPLETED'),
(21, 762308, 'Maki Roll', 'Equipment', 'Gcash', 9999.00, 'PHP', '2025-10-29', '2025-10-29 09:10:32', 'PAYMENT', 'COMPLETED'),
(22, 360277, 'John Johny', 'Food', 'Gcash', 111111.00, 'PHP', '2025-10-29', '2025-10-29 10:15:02', 'PAYMENT', 'COMPLETED'),
(23, 762308, 'Maki Roll', 'Maintenance', 'Maya', 99999999.99, 'PHP', '2025-10-29', '2025-10-29 10:28:29', 'TRANSFER', 'COMPLETED');

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
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `account_id`, `first_name`, `last_name`, `birthdate`, `gender`, `username`, `profile_image`, `date_registered`) VALUES
(1, 233348, 'Loriz', 'Carlos', '2006-08-16', 'Female', 'Loriz Neil Carlos', '../assets/images/user/profile.png', '2025-10-28 07:13:20'),
(2, 762308, 'Maki', 'Roll', '2010-10-06', 'Others', 'Maki Roll', '../assets/images/user/profile.png', '2025-10-28 07:35:43'),
(3, 516229, 'Choco', 'Latte', '2001-01-01', 'Male', 'Choco Latte', '../assets/images/user/profile.png', '2025-10-28 12:26:06'),
(4, 941349, 'John', 'Lee', '2007-05-28', 'Male', 'John Lee', '../assets/images/user/profile.png', '2025-10-28 14:59:00'),
(5, 840500, 'Jane', 'Doe', '1999-01-01', 'Female', 'Jane Doe', '../assets/images/user/profile.png', '2025-10-28 14:59:49'),
(6, 428282, 'Loid', 'Dean', '1969-01-01', 'Male', 'Loid Dean', '../assets/images/user/profile.png', '2025-10-28 15:05:35'),
(7, 483855, 'John', 'Ng', '1999-01-01', 'Others', 'John Ng', '../assets/images/user/profile.png', '2025-10-28 15:15:12'),
(8, 339608, 'Mary', 'Jane', '2010-09-27', 'Female', 'Mary Jane', '../assets/images/user/profile.png', '2025-10-28 15:36:26'),
(9, 387797, 'Lady', 'Queen', '2006-01-19', 'Female', 'Lady Queen', '../assets/images/user/profile.png', '2025-10-28 15:40:40'),
(10, 400969, 'King', 'TIger', '2005-12-30', 'Male', 'King TIger', '../assets/images/user/profile.png', '2025-10-28 15:44:17'),
(11, 457644, 'Mikey', 'Mouse', '2000-01-01', 'Others', 'Mikey Mouse', '../assets/images/user/profile.png', '2025-10-28 15:44:52'),
(12, 360277, 'John', 'Johny', '2002-01-17', 'Male', 'John Johny', '../assets/images/user/profile.png', '2025-10-29 02:09:27');

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
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `company_owners`
--
ALTER TABLE `company_owners`
  MODIFY `owner_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `company_personnel`
--
ALTER TABLE `company_personnel`
  MODIFY `personnel_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `security`
--
ALTER TABLE `security`
  MODIFY `security_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `security_logs`
--
ALTER TABLE `security_logs`
  MODIFY `security_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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
