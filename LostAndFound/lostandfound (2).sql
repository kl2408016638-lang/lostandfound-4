-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 15, 2026 at 11:09 AM
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
-- Database: `lostandfound`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `role` enum('user','admin') NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `contactnum` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `profile_pic` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `role`, `name`, `contactnum`, `email`, `profile_pic`, `password`) VALUES
(51, 'user', 'nadrah', '0164210650', '', NULL, 'nad#123'),
(52, 'user', 'qayyum ', '012345', '', NULL, '1234'),
(61, 'admin', 'syazana ', '', 'syazana@gmail.com', 'profile_61_1771149521.jpg', 'Syaz123!'),
(62, 'user', 'kamal', '016325456', 'kamal@gmail.com', 'profile_62_1771145344.jpg', '1234');

-- --------------------------------------------------------

--
-- Table structure for table `admin_logs`
--

CREATE TABLE `admin_logs` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `admin_name` varchar(100) NOT NULL,
  `action` varchar(50) NOT NULL,
  `target_type` varchar(30) DEFAULT NULL,
  `target_id` int(11) DEFAULT NULL,
  `target_name` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_logs`
--

INSERT INTO `admin_logs` (`id`, `admin_id`, `admin_name`, `action`, `target_type`, `target_id`, `target_name`, `description`, `ip_address`, `created_at`) VALUES
(1, 44, 'halimah ', 'login', NULL, NULL, NULL, 'Admin logged into system', NULL, '2026-01-20 11:01:54'),
(2, 44, 'halimah ', 'login', NULL, NULL, NULL, 'Admin logged into system', NULL, '2026-01-20 11:06:07'),
(3, 44, 'halimah ', 'logout', NULL, NULL, NULL, 'Admin logged out of system', NULL, '2026-01-20 11:06:25'),
(4, 44, 'halimah ', 'login', NULL, NULL, NULL, 'Admin logged into system', NULL, '2026-01-20 11:06:40'),
(5, 44, 'halimah ', 'logout', NULL, NULL, NULL, 'Admin logged out of system', NULL, '2026-01-20 11:06:57'),
(6, 53, 'aina', 'login', NULL, NULL, NULL, 'Admin logged into system', NULL, '2026-01-20 11:11:55'),
(7, 53, 'aina', 'logout', NULL, NULL, NULL, 'Admin logged out of system', NULL, '2026-01-20 11:12:09'),
(8, 53, 'aina', 'login', NULL, NULL, NULL, 'Admin logged into system', NULL, '2026-01-20 11:12:18'),
(9, 53, 'aina', 'edit_user', 'user', 53, '0', 'Edited user account #53 (aina yasmin)', NULL, '2026-01-20 11:13:39'),
(10, 53, 'aina', 'edit_user', 'user', 53, '0', 'Edited user account #53 (aina )', NULL, '2026-01-20 11:13:51'),
(11, 53, 'aina', 'edit_user', 'user', 52, '0', 'Edited user account #52 (qayyum dinni)', NULL, '2026-01-20 11:14:05'),
(12, 53, 'aina', 'logout', NULL, NULL, NULL, 'Admin logged out of system', NULL, '2026-01-20 11:14:59'),
(13, 44, 'halimah ', 'login', NULL, NULL, NULL, 'Admin logged into system', NULL, '2026-01-20 11:15:38'),
(14, 44, 'halimah ', 'delete_user', 'user', 54, NULL, 'Deleted user account #54', NULL, '2026-01-20 11:16:21'),
(15, 44, 'halimah ', 'update_status', 'found_item', 11, '0', 'Updated item #11 status from  to closed', NULL, '2026-01-20 11:17:19'),
(16, 44, 'halimah ', 'update_status', 'found_item', 11, '0', 'Updated item #11 status from  to pending', NULL, '2026-01-20 11:17:58'),
(17, 44, 'halimah ', 'update_status', 'found_item', 11, '0', 'Updated item #11 status from  to pending', NULL, '2026-01-20 11:18:28'),
(18, 44, 'halimah ', 'update_status', 'found_item', 11, '0', 'Updated item #11 status from  to pending', NULL, '2026-01-20 11:18:58'),
(19, 44, 'halimah ', 'logout', NULL, NULL, NULL, 'Admin logged out of system', NULL, '2026-01-20 11:43:57'),
(20, 53, 'aina ', 'login', NULL, NULL, NULL, 'Admin logged into system', NULL, '2026-01-20 11:45:23'),
(21, 53, 'aina ', 'update_status', 'found_item', 12, '0', 'Updated item #12 status from pending to approved', NULL, '2026-01-20 11:45:30'),
(22, 53, 'aina ', 'logout', NULL, NULL, NULL, 'Admin logged out of system', NULL, '2026-01-20 11:47:36'),
(23, 44, 'halimah ', 'login', NULL, NULL, NULL, 'Admin logged into system', NULL, '2026-01-20 11:48:25'),
(24, 44, 'halimah ', 'update_status', 'found_item', 12, '0', 'Updated item #12 status from approved to claimed', NULL, '2026-01-20 12:15:27'),
(25, 44, 'halimah ', 'update_status', 'found_item', 8, '0', 'Updated item #8 status from closed to claimed', NULL, '2026-01-20 12:15:35'),
(26, 44, 'halimah ', 'logout', NULL, NULL, NULL, 'Admin logged out of system', NULL, '2026-01-20 12:31:19'),
(27, 44, 'halimah ', 'login', NULL, NULL, NULL, 'Admin logged into system', NULL, '2026-01-20 12:43:26'),
(28, 44, 'halimah ', 'logout', NULL, NULL, NULL, 'Admin logged out of system', NULL, '2026-01-20 12:47:57'),
(29, 44, 'halimah ', 'login', NULL, NULL, NULL, 'Admin logged into system', NULL, '2026-01-20 12:56:28'),
(30, 44, 'halimah ', 'logout', NULL, NULL, NULL, 'Admin logged out of system', NULL, '2026-01-20 13:15:40'),
(31, 44, 'halimah ', 'login', NULL, NULL, NULL, 'Admin logged into system', NULL, '2026-01-20 13:24:36'),
(32, 44, 'halimah ', 'logout', NULL, NULL, NULL, 'Admin logged out of system', NULL, '2026-01-20 13:39:31'),
(33, 57, 'shafiee', 'login', NULL, NULL, NULL, 'Admin logged into system', NULL, '2026-01-20 13:49:25'),
(34, 58, 'fety', 'login', NULL, NULL, NULL, 'Admin logged into system', NULL, '2026-01-21 09:25:03'),
(35, 58, 'fety', 'logout', NULL, NULL, NULL, 'Admin logged out of system', NULL, '2026-01-21 09:26:32'),
(36, 44, 'halimah ', 'login', NULL, NULL, NULL, 'Admin logged into system', NULL, '2026-01-21 11:12:06'),
(37, 44, 'halimah ', 'logout', NULL, NULL, NULL, 'Admin logged out of system', NULL, '2026-01-21 11:12:18'),
(38, 44, 'halimah ', 'login', NULL, NULL, NULL, 'Admin logged into system', NULL, '2026-01-21 11:12:34'),
(39, 44, 'halimah ', 'logout', NULL, NULL, NULL, 'Admin logged out of system', NULL, '2026-01-21 11:16:47'),
(40, 44, 'halimah ', 'login', NULL, NULL, NULL, 'Admin logged into system', NULL, '2026-01-21 11:18:24'),
(41, 44, 'halimah ', 'logout', NULL, NULL, NULL, 'Admin logged out of system', NULL, '2026-01-21 14:18:45'),
(42, 44, 'halimah ', 'login', NULL, NULL, NULL, 'Admin logged into system', '::1', '2026-01-22 16:14:23'),
(43, 44, 'halimah ', 'login', NULL, NULL, NULL, 'Admin logged into system', '::1', '2026-01-26 04:50:05'),
(44, 44, 'halimah ', 'login', NULL, NULL, NULL, 'Admin logged into system', '::1', '2026-01-28 06:53:19'),
(45, 44, 'halimah ', 'logout', NULL, NULL, NULL, 'Admin logged out of system', '::1', '2026-01-28 07:04:10'),
(46, 61, 'syazana', 'login', NULL, NULL, NULL, 'Admin logged into system', '::1', '2026-02-09 13:19:59'),
(47, 61, 'syazana', 'logout', NULL, NULL, NULL, 'Admin logged out of system', '::1', '2026-02-09 13:46:53'),
(48, 61, 'syazana', 'login', NULL, NULL, NULL, 'Admin logged into system', '::1', '2026-02-09 14:00:56'),
(49, 61, 'syazana', 'login', NULL, NULL, NULL, 'Admin logged into system', '::1', '2026-02-09 14:06:27'),
(50, 61, 'syazana', 'logout', NULL, NULL, NULL, 'Admin logged out of system', '::1', '2026-02-09 14:10:33'),
(51, 61, 'syazana', 'login', NULL, NULL, NULL, 'Admin logged into system', '::1', '2026-02-09 14:37:40'),
(52, 61, 'syazana', 'logout', NULL, NULL, NULL, 'Admin logged out of system', '::1', '2026-02-09 14:54:55'),
(53, 61, 'syazana', 'login', NULL, NULL, NULL, 'Admin logged into system', '::1', '2026-02-09 14:58:29'),
(54, 61, 'syazana', 'logout', NULL, NULL, NULL, 'Admin logged out of system', '::1', '2026-02-09 15:18:07'),
(55, 61, 'syazana', 'login', NULL, NULL, NULL, 'Admin logged into system', '::1', '2026-02-09 15:18:53'),
(56, 61, 'syazana', 'logout', NULL, NULL, NULL, 'Admin logged out of system', '::1', '2026-02-09 15:25:04'),
(57, 61, 'syazana', 'login', NULL, NULL, NULL, 'Admin logged into system', '::1', '2026-02-09 15:25:47'),
(58, 61, 'syazana', 'logout', NULL, NULL, NULL, 'Admin logged out of system', '::1', '2026-02-09 15:35:25'),
(59, 61, 'syazana', 'login', NULL, NULL, NULL, 'Admin logged into system', '::1', '2026-02-09 15:42:01'),
(60, 61, 'syazana', 'login', NULL, NULL, NULL, 'Admin logged into system', '::1', '2026-02-11 14:53:13'),
(61, 61, 'syazana', 'update_status', 'found_item', 1, '0', 'Updated found item #1 status from pending to matched', '::1', '2026-02-11 14:54:15'),
(62, 61, 'syazana', 'update_status', 'lost_item', 2, '0', 'Updated lost item #2 status from pending to claimed', '::1', '2026-02-11 14:54:45'),
(63, 61, 'syazana', 'login', NULL, NULL, NULL, 'Admin logged into system', '::1', '2026-02-11 15:10:54'),
(64, 61, 'syazana', 'login', NULL, NULL, NULL, 'Admin logged into system', '::1', '2026-02-11 15:12:01'),
(65, 61, 'syazana', 'login', NULL, NULL, NULL, 'Admin logged into system', '::1', '2026-02-11 15:33:39'),
(66, 61, 'syazana', 'logout', NULL, NULL, NULL, 'Admin logged out of system', '::1', '2026-02-11 15:35:55'),
(67, 61, 'syazana', 'login', NULL, NULL, NULL, 'Admin logged into system', '::1', '2026-02-11 15:36:10'),
(68, 61, 'syazana', 'logout', NULL, NULL, NULL, 'Admin logged out of system', '::1', '2026-02-11 15:40:03'),
(69, 61, 'syazana', 'login', NULL, NULL, NULL, 'Admin logged into system', '::1', '2026-02-11 15:40:12'),
(70, 61, 'syazana', 'edit_user', 'user', 60, '0', 'Edited user account #60 (azimah)', '::1', '2026-02-11 15:45:56'),
(71, 61, 'syazana', 'delete_user', 'user', 55, NULL, 'Deleted user account #55', '::1', '2026-02-11 15:46:40'),
(72, 61, 'syazana', 'update_status', 'lost_item', 3, '0', 'Updated lost item #3 status from pending to claimed', '::1', '2026-02-11 15:55:04'),
(73, 61, 'syazana', 'update_status', 'lost_item', 2, '0', 'Updated lost item #2 status from claimed to matched', '::1', '2026-02-11 15:55:23'),
(74, 61, 'syazana', 'update_status', 'found_item', 1, '0', 'Updated found item #1 status from matched to approved', '::1', '2026-02-11 15:55:56'),
(75, 61, 'syazana', 'update_status', 'found_item', 1, '0', 'Updated found item #1 status from approved to approved', '::1', '2026-02-11 15:56:26'),
(76, 61, 'syazana', 'update_status', 'found_item', 1, '0', 'Updated found item #1 status from approved to approved', '::1', '2026-02-11 15:56:56'),
(77, 61, 'syazana', 'update_status', 'found_item', 1, '0', 'Updated found item #1 status from approved to approved', '::1', '2026-02-11 15:57:26'),
(78, 61, 'syazana', 'logout', NULL, NULL, NULL, 'Admin logged out of system', '::1', '2026-02-11 15:57:30'),
(79, 61, 'syazana', 'login', NULL, NULL, NULL, 'Admin logged into system', '::1', '2026-02-15 07:55:21'),
(80, 61, 'syazana', 'update_status', 'found_item', 1, '0', 'Updated found item #1 status from approved to matched', '::1', '2026-02-15 08:15:50'),
(81, 61, 'syazana', 'update_status', 'lost_item', 3, '0', 'Updated lost item #3 status from claimed to claimed', '::1', '2026-02-15 08:15:55'),
(82, 61, 'syazana', 'logout', NULL, NULL, NULL, 'Admin logged out of system', '::1', '2026-02-15 08:17:07'),
(83, 61, 'syazana', 'login', NULL, NULL, NULL, 'Admin logged into system', '::1', '2026-02-15 08:17:32'),
(84, 61, 'syazana', 'logout', NULL, NULL, NULL, 'Admin logged out of system', '::1', '2026-02-15 08:17:52'),
(85, 61, 'syazana', 'login', NULL, NULL, NULL, 'Admin logged into system', '::1', '2026-02-15 08:19:15'),
(86, 61, 'syazana', 'logout', NULL, NULL, NULL, 'Admin logged out of system', '::1', '2026-02-15 08:40:40'),
(87, 61, 'syazana', 'login', NULL, NULL, NULL, 'Admin logged into system', '::1', '2026-02-15 08:49:48'),
(88, 61, 'syazana', 'update_status', 'found_item', 1, '0', 'Updated found item #1 status from matched to pending', '::1', '2026-02-15 08:53:52'),
(89, 61, 'syazana', 'update_status', 'found_item', 1, '0', 'Updated found item #1 status from pending to pending', '::1', '2026-02-15 08:54:22'),
(90, 61, 'syazana', 'edit_user', 'user', 62, '0', 'Edited user account #62 (kamal)', '::1', '2026-02-15 08:55:30'),
(91, 61, 'syazana', 'update_status', 'lost_item', 3, '0', 'Updated lost item #3 status from claimed to matched', '::1', '2026-02-15 09:17:21'),
(92, 61, 'syazana', 'logout', NULL, NULL, NULL, 'Admin logged out of system', '::1', '2026-02-15 09:20:42'),
(93, 61, 'syazana', 'login', NULL, NULL, NULL, 'Admin logged into system', '::1', '2026-02-15 09:20:50'),
(94, 61, 'syazana', 'update_email', 'admin', 61, '0', 'Updated email from \'syazana@gmail.com\' to \'syazana123@gmail.com\'', '::1', '2026-02-15 09:21:29'),
(95, 61, 'syazana', 'update_profile_pic', 'admin', 61, '0', 'Updated profile picture', '::1', '2026-02-15 09:21:56'),
(96, 61, 'syazana', 'update_password', 'admin', 61, '0', 'Changed password', '::1', '2026-02-15 09:22:24'),
(97, 61, 'syazana', 'update_name', 'admin', 61, '0', 'Updated name from \'syazana\' to \'syazana shafiee\'', '::1', '2026-02-15 09:22:46'),
(98, 61, 'syazana shafiee', 'update_name', 'admin', 61, '0', 'Updated name from \'syazana shafiee\' to \'syazana \'', '::1', '2026-02-15 09:23:08'),
(99, 61, 'syazana shafiee', 'update_email', 'admin', 61, '0', 'Updated email from \'syazana123@gmail.com\' to \'syazana@gmail.com\'', '::1', '2026-02-15 09:23:08'),
(100, 61, 'syazana shafiee', 'update_password', 'admin', 61, '0', 'Changed password', '::1', '2026-02-15 09:23:08'),
(101, 61, 'syazana ', 'update_status', 'found_item', 1, '0', 'Updated found item #1 status from pending to approved', '::1', '2026-02-15 09:23:41'),
(102, 61, 'syazana ', 'update_status', 'found_item', 1, '0', 'Updated found item #1 status from approved to matched', '::1', '2026-02-15 09:23:57'),
(103, 61, 'syazana ', 'update_status', 'found_item', 1, '0', 'Updated found item #1 status from matched to claimed', '::1', '2026-02-15 09:24:13'),
(104, 61, 'syazana ', 'update_status', 'lost_item', 3, '0', 'Updated lost item #3 status from matched to claimed', '::1', '2026-02-15 09:24:33'),
(105, 61, 'syazana ', 'update_status', 'lost_item', 2, '0', 'Updated lost item #2 status from matched to claimed', '::1', '2026-02-15 09:24:44'),
(106, 61, 'syazana ', 'edit_user', 'user', 52, '0', 'Edited user account #52 (qayyum dinni)', '::1', '2026-02-15 09:25:20'),
(107, 61, 'syazana ', 'edit_user', 'user', 52, '0', 'Edited user account #52 (qayyum )', '::1', '2026-02-15 09:25:52'),
(108, 61, 'syazana ', 'delete_user', 'user', 59, NULL, 'Deleted user account #59', '::1', '2026-02-15 09:26:09'),
(109, 61, 'syazana ', 'delete_user', 'user', 59, NULL, 'Deleted user account #59', '::1', '2026-02-15 09:26:13'),
(110, 61, 'syazana ', 'delete_user', 'user', 59, NULL, 'Deleted user account #59', '::1', '2026-02-15 09:26:44'),
(111, 61, 'syazana ', 'delete_user', 'user', 59, NULL, 'Deleted user account #59', '::1', '2026-02-15 09:26:48'),
(112, 61, 'syazana ', 'logout', NULL, NULL, NULL, 'Admin logged out of system', '::1', '2026-02-15 09:26:59'),
(113, 61, 'syazana ', 'login', NULL, NULL, NULL, 'Admin logged into system', '::1', '2026-02-15 09:28:05'),
(114, 61, 'syazana ', 'delete_user', 'user', 63, NULL, 'Deleted user account #63', '::1', '2026-02-15 09:28:12'),
(115, 61, 'syazana ', 'logout', NULL, NULL, NULL, 'Admin logged out of system', '::1', '2026-02-15 09:28:32'),
(116, 61, 'syazana ', 'login', NULL, NULL, NULL, 'Admin logged into system', '::1', '2026-02-15 09:29:05'),
(117, 61, 'syazana ', 'delete_user', 'user', 64, NULL, 'Deleted user account #64', '::1', '2026-02-15 09:29:15'),
(118, 61, 'syazana ', 'logout', NULL, NULL, NULL, 'Admin logged out of system', '::1', '2026-02-15 09:30:17'),
(119, 61, 'syazana ', 'login', NULL, NULL, NULL, 'Admin logged into system', '::1', '2026-02-15 09:31:09'),
(120, 61, 'syazana ', 'delete_user', 'user', 60, NULL, 'Deleted user account #60', '::1', '2026-02-15 09:31:37'),
(121, 61, 'syazana ', 'delete_user', 'user', 60, NULL, 'Deleted user account #60', '::1', '2026-02-15 09:32:07'),
(122, 61, 'syazana ', 'delete_user', 'user', 60, NULL, 'Deleted user account #60', '::1', '2026-02-15 09:32:37'),
(123, 61, 'syazana ', 'delete_user', 'user', 60, NULL, 'Deleted user account #60', '::1', '2026-02-15 09:33:07'),
(124, 61, 'syazana ', 'delete_user', 'user', 60, NULL, 'Deleted user account #60', '::1', '2026-02-15 09:33:37'),
(125, 61, 'syazana ', 'delete_user', 'user', 60, NULL, 'Deleted user account #60', '::1', '2026-02-15 09:34:07'),
(126, 61, 'syazana ', 'delete_user', 'user', 60, NULL, 'Deleted user account #60', '::1', '2026-02-15 09:34:37'),
(127, 61, 'syazana ', 'delete_user', 'user', 60, NULL, 'Deleted user account #60', '::1', '2026-02-15 09:35:07'),
(128, 61, 'syazana ', 'delete_user', 'user', 60, NULL, 'Deleted user account #60', '::1', '2026-02-15 09:35:37'),
(129, 61, 'syazana ', 'delete_user', 'user', 60, '0', 'Deleted user account #60 (Unknown)', '::1', '2026-02-15 09:35:47'),
(130, 61, 'syazana ', 'logout', NULL, NULL, NULL, 'Admin logged out of system', '::1', '2026-02-15 09:35:53'),
(131, 61, 'syazana ', 'login', NULL, NULL, NULL, 'Admin logged into system', '::1', '2026-02-15 09:36:30'),
(132, 61, 'syazana ', 'edit_user', 'user', 65, '0', 'Edited user account #65 from \'ain\' to \'ain iz\'', '::1', '2026-02-15 09:36:45'),
(133, 61, 'syazana ', 'delete_user', 'user', 65, '0', 'Deleted user account #65 (ain iz)', '::1', '2026-02-15 09:36:58'),
(134, 61, 'syazana ', 'update_status', 'found_item', 4, '0', 'Updated found item #4 status from pending to approved', '::1', '2026-02-15 09:37:30'),
(135, 61, 'syazana ', 'update_status', 'found_item', 4, '0', 'Updated found item #4 status from approved to matched', '::1', '2026-02-15 09:37:50'),
(136, 61, 'syazana ', 'logout', NULL, NULL, NULL, 'Admin logged out of system', '::1', '2026-02-15 09:39:22'),
(137, 61, 'syazana ', 'login', NULL, NULL, NULL, 'Admin logged into system', '::1', '2026-02-15 09:58:00'),
(138, 61, 'syazana ', 'logout', NULL, NULL, NULL, 'Admin logged out of system', '::1', '2026-02-15 09:58:11'),
(139, 61, 'syazana ', 'login', NULL, NULL, NULL, 'Admin logged into system', '::1', '2026-02-15 09:58:33'),
(140, 61, 'syazana ', 'update_profile_pic', 'admin', 61, '0', 'Updated profile picture', '::1', '2026-02-15 09:58:41'),
(141, 61, 'syazana ', 'logout', NULL, NULL, NULL, 'Admin logged out of system', '::1', '2026-02-15 10:02:56');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `item_type` enum('lost','found') NOT NULL,
  `type_item` varchar(50) NOT NULL,
  `custom_item` varchar(100) DEFAULT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `location` varchar(100) NOT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `status` enum('pending','approved','matched','claimed') DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `user_id`, `user_name`, `item_type`, `type_item`, `custom_item`, `date`, `time`, `location`, `picture`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 62, 'kamal', 'found', 'payung', NULL, '2026-02-09', '22:28:00', 'office', 'item_1770647324_62.png', 'payung', 'claimed', '2026-02-09 22:28:44', '2026-02-15 17:24:13'),
(2, 62, 'kamal', 'lost', 'wallet', NULL, '2026-02-09', '22:28:00', 'cafeteria', 'item_1770647345_62.png', 'wallet', 'claimed', '2026-02-09 22:29:05', '2026-02-15 17:24:44'),
(3, 62, 'kamal', 'lost', 'wallet', NULL, '2026-02-09', '22:28:00', 'cafeteria', 'item_1770647448_62.png', 'wallet', 'claimed', '2026-02-09 22:30:48', '2026-02-15 17:24:33'),
(4, 51, 'nadrah', 'found', 'books', NULL, '2026-02-15', '17:30:00', 'parking', 'item_1771147844_51.jpg', '00', 'matched', '2026-02-15 17:30:44', '2026-02-15 17:37:50');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `is_read` tinyint(4) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_logs`
--
ALTER TABLE `admin_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_admin_id` (`admin_id`),
  ADD KEY `idx_action` (`action`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `idx_target` (`target_type`,`target_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_item_type` (`item_type`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `is_read` (`is_read`),
  ADD KEY `created_at` (`created_at`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `admin_logs`
--
ALTER TABLE `admin_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=142;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
