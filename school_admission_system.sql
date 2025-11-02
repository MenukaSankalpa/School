-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 02, 2025 at 03:42 PM
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
-- Database: `school_admission_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `email`, `password`) VALUES
(7, 'Menuka01', 'menuka.test@gmail.com', '123456789');

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `school_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `user_id`, `school_name`, `created_at`) VALUES
(4, 13, 'Vijaya National School', '2025-06-28 10:10:18'),
(5, 13, 'Al-Hambra Maha Vidyalaya', '2025-06-28 10:10:18'),
(6, 13, 'Holy Cross College', '2025-06-28 10:10:18'),
(7, 14, 'Vijaya National School', '2025-07-27 12:52:23'),
(8, 14, 'Holy Cross College', '2025-07-27 12:52:23'),
(9, 14, 'Kalutara Boys\' School', '2025-07-27 12:52:23'),
(10, 12, 'Vijaya National School', '2025-07-29 15:20:37'),
(11, 12, 'Al-Hambra Maha Vidyalaya', '2025-07-29 15:20:37'),
(12, 12, 'Holy Cross College', '2025-07-29 15:20:37'),
(13, 15, 'Vijaya National School', '2025-08-03 12:58:04'),
(14, 15, 'Holy Cross College', '2025-08-03 12:58:04'),
(15, 15, 'Kalutara Boys\' School', '2025-08-03 12:58:04'),
(16, 16, 'Vijaya National School', '2025-08-26 15:02:09'),
(17, 16, 'Al-Hambra Maha Vidyalaya', '2025-08-26 15:02:09'),
(18, 16, 'Holy Cross College', '2025-08-26 15:02:09'),
(19, 18, 'Vijaya National School', '2025-09-06 04:20:25'),
(20, 18, 'Al-Hambra Maha Vidyalaya', '2025-09-06 04:20:25'),
(21, 18, 'Holy Cross College', '2025-09-06 04:20:25'),
(28, 25, 'Al-Hambra Maha Vidyalaya', '2025-10-26 07:41:22'),
(29, 25, 'Holy Cross College', '2025-10-26 07:41:22'),
(30, 25, 'Kalutara Boys\' School', '2025-10-26 07:41:22'),
(31, 28, 'Al-Hambra Maha Vidyalaya', '2025-11-01 12:41:45'),
(32, 28, 'Holy Cross College', '2025-11-01 12:41:45'),
(33, 28, 'Kalutara Boys\' School', '2025-11-01 12:41:45');

-- --------------------------------------------------------

--
-- Table structure for table `application_info`
--

CREATE TABLE `application_info` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `child_full_name` varchar(255) DEFAULT NULL,
  `child_initials` varchar(255) DEFAULT NULL,
  `child_religion` varchar(100) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `applicant_full_name` varchar(255) DEFAULT NULL,
  `applicant_initials` varchar(255) DEFAULT NULL,
  `applicant_nic` varchar(20) DEFAULT NULL,
  `applicant_religion` varchar(100) DEFAULT NULL,
  `applicant_address` text DEFAULT NULL,
  `applicant_phone` varchar(20) DEFAULT NULL,
  `resident_district` varchar(100) DEFAULT NULL,
  `spouse_full_name` varchar(255) DEFAULT NULL,
  `spouse_initials` varchar(255) DEFAULT NULL,
  `spouse_nic` varchar(20) DEFAULT NULL,
  `spouse_religion` varchar(100) DEFAULT NULL,
  `spouse_address` text DEFAULT NULL,
  `spouse_phone` varchar(20) DEFAULT NULL,
  `spouse_district` varchar(100) DEFAULT NULL,
  `ebill_files` text DEFAULT NULL,
  `lbill_files` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT 'pending',
  `marks` int(3) DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `assigned_admin_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `application_info`
--

INSERT INTO `application_info` (`id`, `user_id`, `child_full_name`, `child_initials`, `child_religion`, `dob`, `age`, `applicant_full_name`, `applicant_initials`, `applicant_nic`, `applicant_religion`, `applicant_address`, `applicant_phone`, `resident_district`, `spouse_full_name`, `spouse_initials`, `spouse_nic`, `spouse_religion`, `spouse_address`, `spouse_phone`, `spouse_district`, `ebill_files`, `lbill_files`, `created_at`, `status`, `marks`, `feedback`, `assigned_admin_id`) VALUES
(1, 12, 'H.Menuka Deshapriya ', 'Heenpatiyalage Menuka Sankalpa Deshapriya', 'Sri lankan ', '2015-01-01', 6, 'H.I.C.Deshapriya', 'Heenpatiyalage iresh chinthaka deshapriya', '20031040768', 'sri lankan', '\"Deshapriya\" duwa temple road kalutara ', '0703492314', 'kalutara', 'H.R.R.Deshapriya', 'Heenpatiyalage romesh renuka deshapriya', '20031040768', 'sri lankan', '\"Deshapriya\" samagi mawatha road kalutara ', '0703492315', 'kalutara', 'uploads/ebill_1750180010image04.jpg, uploads/ebill_1750180010image03.avif, uploads/ebill_1750180010image01.jpg, uploads/ebill_1750180010image02.jpg, uploads/ebill_1750180010images01.png', 'uploads/lbill_1750180010image10.jpg, uploads/lbill_1750180010image09.png, uploads/lbill_1750180010image08.png, uploads/lbill_1750180010image07.jpg, uploads/lbill_1750180010image06.jpg', '2025-06-17 17:06:50', 'approved', 50, 'good and all clear', 26),
(4, 14, 'Herath Romesh Dananjaya Herath ', 'H.R.D.Herath ', 'Sri lankan ', '2016-01-01', 5, 'H.I.C.Deshapriya', 'Heenpatiyalage iresh chinthaka deshapriya', '20031040768', 'sri lankan', '\"Deshapriya\" duwa temple road kalutara ', '0703492314', 'kalutara', 'Herath sumudu priyangani  herath ', 'H.S.P.Herath ', '20031040769', 'sri lankan', '\"Deshapriya\" samagi mawatha road kalutara ', '0703492315', 'kalutara', '', '', '2025-07-27 12:57:41', 'rejected', NULL, NULL, 10),
(6, 15, 'Heenpatiyalge Menuka Sankalpa Deshapriya', 'H.M.S.Deshapriya', 'Sri lankan ', '2020-01-01', 5, 'Heenpatiyalage Iresh Chinthaka Deshapriya ', 'H.I.C.Deshapriya', '2003101010', 'sri lankan', 'kalutara', '0773785351', 'kalutara', 'Herath Mudiyansalage Sumudu Priyangani Herath', 'H.M.S.P.Herath', '2004101010', 'sri lankan', 'kaluatara', '0772781605', 'kalutara', 'uploads/ebill/15/1754226751_ebill05.jpg, uploads/ebill/15/1754226751_ebill04.jpg, uploads/ebill/15/1754226751_ebill03.jpg, uploads/ebill/15/1754226751_ebill02.webp, uploads/ebill/15/1754226751_ebill01.jpg', 'uploads/lbill/15/1754226751_lbill02.jpg, uploads/lbill/15/1754226751_lbill01.webp', '2025-08-03 13:12:31', 'approved', NULL, NULL, 10),
(7, 16, 'Kamla perera', 'a.kamal perera', 'sri lankan', '0220-02-20', 5, 'A.amal perera', 'A.A.perera', '200310400765', 'Sri lankan ', 'kalutara,duwa temple road,samagi mawatha.', '0703492311', 'Kalutara', 'B.Nimali Silva', 'B.N.Silva', '200310400764', 'sri lankan ', 'kalutara,duwa temple road,samagi mawatha.', '0703492311', 'Kalutara', 'uploads/ebill/16/1756220828_Screenshot 2025-07-14 081412.png, uploads/ebill/16/1756220828_Screenshot 2025-07-18 122545.png, uploads/ebill/16/1756220828_Screenshot 2025-07-21 093955.png', 'uploads/lbill/16/1756220828_Screenshot 2025-07-24 132822.png, uploads/lbill/16/1756220828_Screenshot 2025-07-25 154218.png, uploads/lbill/16/1756220828_Screenshot 2025-07-25 154253.png', '2025-08-26 15:07:08', 'approved', NULL, NULL, 19),
(8, 25, 'Menuka Sankalpa', 'm.sankalpa', 'sri lankan', '2019-12-29', 5, 'Menuka menuka', 'm.menuka', '200310400765', 'Sri lankan ', 'kalutara', '07034923145', 'Kalutara', 'test 01', 'test 1', '0123456789', 'sri lankan ', 'test we', '0225422355', 'Kalutara', 'uploads/ebill/25/1761465416_Screenshot 2025-10-22 093649.png, uploads/ebill/25/1761465416_Screenshot 2025-10-22 093712.png, uploads/ebill/25/1761465416_Screenshot 2025-10-22 093742.png', 'uploads/lbill/25/1761465416_Screenshot 2025-10-22 093759.png, uploads/lbill/25/1761465416_Screenshot 2025-10-22 120033.png, uploads/lbill/25/1761465416_Screenshot 2025-10-22 120042.png, uploads/lbill/25/1761465416_Screenshot 2025-10-25 212209.png', '2025-10-26 07:56:56', 'pending', NULL, NULL, NULL),
(9, 28, 'tim tim ', 't.t', 'sri lankan', '2020-02-04', 5, 'Tim Test', 't.t.', '1010101010', 'Sri lankan ', 'kalutara', '07034923111', 'Kalutara', 'female', 't.f.', '02020202020', 'sri lankan ', 'kalutara', '07777777712', 'Kalutara', 'uploads/ebill/28/1762001090_13 iphone 05.jpg, uploads/ebill/28/1762001090_13 iphone 04.jpg, uploads/ebill/28/1762001090_13 iphone 03.jpg, uploads/ebill/28/1762001090_13 iphone 05 - Copy.jpg', 'uploads/lbill/28/1762001090_13 iphone 02.jpg, uploads/lbill/28/1762001090_13 iphone 01.jpg', '2025-11-01 12:44:50', 'approved', 65, 'this one added and done ', 26);

-- --------------------------------------------------------

--
-- Table structure for table `application_schools`
--

CREATE TABLE `application_schools` (
  `id` int(11) NOT NULL,
  `application_id` int(11) DEFAULT NULL,
  `school_name` varchar(255) DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `communication_logs`
--

CREATE TABLE `communication_logs` (
  `id` int(11) NOT NULL,
  `applicant_id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `type` enum('email','message') NOT NULL,
  `message` text DEFAULT NULL,
  `sent_by` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `communication_logs`
--

INSERT INTO `communication_logs` (`id`, `applicant_id`, `application_id`, `type`, `message`, `sent_by`, `created_at`) VALUES
(1, 0, 1, 'message', 'good and wait ', 'admin', '2025-10-31 17:48:07');

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `complaint_text` text NOT NULL,
  `created_at` datetime NOT NULL,
  `reply_text` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`id`, `user_id`, `complaint_text`, `created_at`, `reply_text`) VALUES
(1, 1, 'The system is slow during login.', '0000-00-00 00:00:00', 'Will fix it'),
(2, 15, 'System must show who is the mark these details', '2025-08-03 18:43:47', 'will do it'),
(3, 16, 'to slow this', '2025-08-27 08:49:50', 'will fix'),
(4, 25, 'this one add please number', '2025-10-26 13:27:14', NULL),
(5, 28, 'I added Data but i think User interface must edit', '2025-11-01 18:15:35', NULL),
(6, 28, 'This one is good this for test case', '2025-11-02 18:35:24', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dashboard_messages`
--

CREATE TABLE `dashboard_messages` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `app_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email_notifications`
--

CREATE TABLE `email_notifications` (
  `id` int(11) NOT NULL,
  `app_id` int(11) NOT NULL,
  `email_type` varchar(50) NOT NULL,
  `status` enum('pending','sent') DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `applicant_id` int(11) NOT NULL,
  `sender` varchar(50) NOT NULL,
  `receiver` varchar(50) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `applicant_id`, `sender`, `receiver`, `subject`, `message`, `created_at`) VALUES
(1, 1, 'admin', 'parent', '', 'good and wait ', '2025-10-31 17:46:04'),
(2, 1, 'admin', 'parent', '', 'good and wait ', '2025-10-31 17:48:07'),
(4, 9, 'admin', 'parent', '', 'all done ', '2025-11-01 12:56:45'),
(6, 9, 'admin', 'parent', '', 'this one for test msg ', '2025-11-01 15:27:39'),
(7, 9, 'admin', 'parent', '', 'this one for test msg ', '2025-11-01 15:30:01'),
(8, 9, 'admin', 'parent', '', 'this one for test msg ', '2025-11-01 15:38:48'),
(9, 9, 'admin', 'parent', '', 'this one for test msg ', '2025-11-01 15:40:52'),
(10, 8, 'admin', 'parent', '', 'this one for test ', '2025-11-01 15:42:06');

-- --------------------------------------------------------

--
-- Table structure for table `parent_messages`
--

CREATE TABLE `parent_messages` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schools`
--

CREATE TABLE `schools` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('boy','girl','mixed') NOT NULL,
  `address` varchar(255) NOT NULL,
  `latitude` decimal(10,6) NOT NULL,
  `longitude` decimal(10,6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schools`
--

INSERT INTO `schools` (`id`, `name`, `type`, `address`, `latitude`, `longitude`) VALUES
(1, 'Kalutara Boys\' School', 'boy', 'Galle Road, Kalutara', 6.583600, 79.960200),
(2, 'Kalutara Balika Vidyalaya', 'girl', 'Main Street, Kalutara', 6.582300, 79.960900),
(3, 'Holy Cross College', 'mixed', 'Nagoda Road, Kalutara', 6.581000, 79.963100),
(4, 'Tissa Central College', 'mixed', 'Panadura Road, Kalutara', 6.586100, 79.960500),
(5, 'St. John\'s College', 'boy', 'Kuda Waskaduwa, Kalutara', 6.588700, 79.960000),
(6, 'Kalutara Muslim Girls School', 'girl', 'Beruwala Road, Kalutara', 6.582000, 79.962000),
(7, 'Al-Hambra Maha Vidyalaya', 'mixed', 'Katukurunda, Kalutara', 6.578200, 79.963500),
(8, 'St. Thomas\' Boys School', 'boy', 'Wadduwa, Kalutara', 6.634500, 79.928100),
(9, 'Sagara Balika Vidyalaya', 'girl', 'Payagala, Kalutara', 6.533400, 79.962200),
(10, 'Royal Central College', 'mixed', 'Nagoda, Kalutara', 6.580100, 79.952000),
(11, 'Vijaya National School', 'mixed', 'Maggona, Kalutara', 6.558800, 79.978000),
(12, 'St. Mary\'s Girls\' School', 'girl', 'Kalutara North', 6.590000, 79.958000),
(13, 'Vidyaloka Maha Vidyalaya', 'mixed', 'Bombuwala, Kalutara', 6.605000, 79.945000),
(14, 'Panadura Royal College', 'boy', 'Panadura, Kalutara District', 6.714300, 79.904000),
(15, 'Sethubandhan Girls\' College', 'girl', 'Beruwala, Kalutara District', 6.475000, 79.982000);

-- --------------------------------------------------------

--
-- Table structure for table `school_applications`
--

CREATE TABLE `school_applications` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `school_name` varchar(255) NOT NULL,
  `applied_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `submitted_schools`
--

CREATE TABLE `submitted_schools` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `applicant_name` varchar(255) DEFAULT NULL,
  `school_name` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `child_name` varchar(100) DEFAULT NULL,
  `role` enum('1','2','3') DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `national_id` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL,
  `child_gender` varchar(10) DEFAULT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `selected_schools` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `child_name`, `role`, `password`, `national_id`, `address`, `reset_token`, `token_expiry`, `child_gender`, `latitude`, `longitude`, `selected_schools`) VALUES
(1, 'admin', 'superadmin@gmail.com', 'N/A', '3', '202cb962ac59075b964b07152d234b70', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(9, 'Menuka Deshapriya', 'alone.monster13@gmail.com', 'Chamara Virasingha', '1', '25f9e794323b453885f5181f1b624d0b', '200310400766', '\"deshapriya\" samagi mawatha,duwa temple road , kalutara south ', '6f7652757aa41179b8aa80ed294185023765388a1776012112a6ba3f2b07481888bf9e622b0e1c0ad8e4e9ade2b6acc77bad', '2025-05-10 10:37:22', 'boy', 0, 0, NULL),
(10, 'Kavidu Chethiya', 'test01@gmail.com', 'N/A', '2', '25f9e794323b453885f5181f1b624d0b', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(12, 'Menu Menu', 'test001@gmail.com', 'amara kamal', '1', '202cb962ac59075b964b07152d234b70', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Vijaya National School, Al-Hambra Maha Vidyalaya, Holy Cross College'),
(13, 'Tharindu Randika ', 'test05@example.com', 'Randika Tharindu ', '1', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Vijaya National School, Al-Hambra Maha Vidyalaya, Holy Cross College'),
(14, 'Romesh Renuka', 'test05@gmail.com', 'Romesh Dananjaya', '1', '202cb962ac59075b964b07152d234b70', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Vijaya National School, Holy Cross College, Kalutara Boys\' School'),
(15, 'Chinthaka Deshapriya', 'chinthaka@gmail.com', 'H.Menuka Sankalpa Deshapriya', '1', '202cb962ac59075b964b07152d234b70', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Vijaya National School, Holy Cross College, Kalutara Boys\' School'),
(16, 'Amal Perera', 'test03@gmial.com', 'Kamal Perera', '1', '202cb962ac59075b964b07152d234b70', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Vijaya National School, Al-Hambra Maha Vidyalaya, Holy Cross College'),
(17, 'Amanda', 'test03@gmail.com', 'lahiru ', '1', '202cb962ac59075b964b07152d234b70', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(18, 'Samitha Ranasingha', 'samitha@gmail.com', 'kasun kalhara', '1', '202cb962ac59075b964b07152d234b70', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Vijaya National School, Al-Hambra Maha Vidyalaya, Holy Cross College'),
(19, 'Tom', 'tom@email.com', NULL, '2', '$2y$10$bCvMTBvEiFtBmy5GCBJ1GO3QcrRbcboJ4.BGPKvKRIIa8l3RFN2sy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(20, 'samitha', 'samitha@123', 'samitha samitha', '1', '202cb962ac59075b964b07152d234b70', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(22, 'samitha', 'samitha@test.com', 'samitha samitha', '1', '202cb962ac59075b964b07152d234b70', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(23, 'Test', 'test@gmail.com', NULL, '2', '123456', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(25, 'Menuka', 'haffy.buddy1996@gmail.com', 'Menuka Sankalpa', '1', '202cb962ac59075b964b07152d234b70', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Al-Hambra Maha Vidyalaya, Holy Cross College, Kalutara Boys\' School'),
(26, 'sanka', 'sanka@test', NULL, '2', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(28, 'Tim', 'menuka@ceyline-group.lk', 'tim tim', '1', '202cb962ac59075b964b07152d234b70', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Al-Hambra Maha Vidyalaya, Holy Cross College, Kalutara Boys\' School'),
(29, 'vihaga', 'vihaga@123.com', NULL, '2', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `application_info`
--
ALTER TABLE `application_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `application_schools`
--
ALTER TABLE `application_schools`
  ADD PRIMARY KEY (`id`),
  ADD KEY `application_id` (`application_id`);

--
-- Indexes for table `communication_logs`
--
ALTER TABLE `communication_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `dashboard_messages`
--
ALTER TABLE `dashboard_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`),
  ADD KEY `app_id` (`app_id`);

--
-- Indexes for table `email_notifications`
--
ALTER TABLE `email_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `parent_messages`
--
ALTER TABLE `parent_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schools`
--
ALTER TABLE `schools`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `school_applications`
--
ALTER TABLE `school_applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `submitted_schools`
--
ALTER TABLE `submitted_schools`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `application_info`
--
ALTER TABLE `application_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `application_schools`
--
ALTER TABLE `application_schools`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `communication_logs`
--
ALTER TABLE `communication_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `dashboard_messages`
--
ALTER TABLE `dashboard_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `email_notifications`
--
ALTER TABLE `email_notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `parent_messages`
--
ALTER TABLE `parent_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `schools`
--
ALTER TABLE `schools`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `school_applications`
--
ALTER TABLE `school_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `submitted_schools`
--
ALTER TABLE `submitted_schools`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `application_schools`
--
ALTER TABLE `application_schools`
  ADD CONSTRAINT `application_schools_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `application_info` (`id`);

--
-- Constraints for table `complaints`
--
ALTER TABLE `complaints`
  ADD CONSTRAINT `complaints_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `dashboard_messages`
--
ALTER TABLE `dashboard_messages`
  ADD CONSTRAINT `dashboard_messages_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `dashboard_messages_ibfk_2` FOREIGN KEY (`app_id`) REFERENCES `application_info` (`id`);

--
-- Constraints for table `school_applications`
--
ALTER TABLE `school_applications`
  ADD CONSTRAINT `school_applications_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `submitted_schools`
--
ALTER TABLE `submitted_schools`
  ADD CONSTRAINT `submitted_schools_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
