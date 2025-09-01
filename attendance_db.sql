-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 31, 2025 at 10:50 PM
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
-- Database: `attendance_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admindocuments`
--

CREATE TABLE `admindocuments` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deadline` date DEFAULT NULL,
  `purpose` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admindocuments`
--

INSERT INTO `admindocuments` (`id`, `title`, `file_name`, `uploaded_at`, `deadline`, `purpose`) VALUES
(3, 'REGISTRATION', 'WAHDA ISSA WINDA REGISTRATION.docx', '2025-08-31 10:37:17', '2025-09-30', NULL),
(4, 'Certificate templete', 'WAHDA ISSA WINDA CERTIFICATE.DOCX', '2025-08-31 13:13:55', '2025-10-08', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `department_id` int(11) NOT NULL,
  `display_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `message`, `department_id`, `display_date`, `created_at`, `updated_at`) VALUES
(1, 'mapenzi', 'mapenzi kazini hayaruhusiwi', 1, '2025-08-28', '2025-08-27 12:21:39', '2025-08-27 12:21:39'),
(2, 'machozi', 'machozi hayaruhusiwi kazini', 3, '2025-09-06', '2025-08-31 09:33:39', '2025-08-31 13:12:44');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` enum('checkin','checkout') DEFAULT 'checkin',
  `checkin_time` datetime DEFAULT NULL,
  `checkout_time` datetime DEFAULT NULL,
  `status` enum('present','absent','late') DEFAULT 'present',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `check_in_limit` time NOT NULL,
  `check_out_limit` time NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `group_start_date` date NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `check_in_limit`, `check_out_limit`, `created_at`, `group_start_date`, `updated_at`) VALUES
(1, 'developers', '08:00:00', '16:00:00', '2025-08-26 14:58:17', '2025-08-31', '2025-08-31 13:13:04'),
(2, 'networking', '08:30:00', '16:30:00', '2025-08-26 14:58:17', '2025-08-26', '2025-08-26 14:58:17'),
(3, 'IT surport', '08:10:00', '16:50:00', '2025-08-31 10:18:57', '2025-08-31', '2025-08-31 10:18:57');

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` enum('certificate','registration') NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('uploaded','missing') DEFAULT 'missing',
  `purpose` varchar(50) DEFAULT NULL,
  `comment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`id`, `user_id`, `type`, `file_name`, `uploaded_at`, `status`, `purpose`, `comment`) VALUES
(1, 4, 'certificate', '\"C:\\Users\\RAMSEY\\Desktop\\WAHDA ISSA WINDA CERTIFICATE.DOCX\"', '2025-08-27 14:53:57', 'uploaded', NULL, NULL),
(2, 4, 'registration', '\"C:\\Users\\RAMSEY\\Desktop\\WAHDA ISSA WINDA REGISTRATION.docx\"', '2025-08-27 14:53:57', 'uploaded', NULL, NULL),
(3, 2, 'registration', '\"C:\\Users\\RAMSEY\\Desktop\\UDOM_FIELD_SAID_S._HEMED_signed.docx\"', '2025-08-27 15:58:33', 'uploaded', NULL, NULL),
(4, 3, 'certificate', '\"C:\\Users\\RAMSEY\\Desktop\\UDOM_FIELD_SAID_S._HEMED_signed.docx\"', '2025-08-27 15:58:33', 'uploaded', NULL, NULL),
(5, 5, 'certificate', 'MARRY.docx', '2025-08-31 14:58:10', 'uploaded', NULL, NULL),
(6, 5, 'registration', 'entry-log system.docx', '2025-08-31 14:58:29', 'uploaded', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `field_worker_profiles`
--

CREATE TABLE `field_worker_profiles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `department_id` int(11) NOT NULL,
  `college_name` varchar(150) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `group_name` enum('A','B') NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `field_worker_profiles`
--

INSERT INTO `field_worker_profiles` (`id`, `user_id`, `full_name`, `phone`, `department_id`, `college_name`, `start_date`, `end_date`, `email`, `group_name`, `updated_at`) VALUES
(2, 3, 'adija mustafa', '0789000765', 2, 'UDSM', '2025-08-01', '2025-09-30', 'adijamustafa@gmail.com', 'A', '2025-08-31 13:36:07'),
(3, 4, 'wahda winda', '08067361023', 3, 'MZUMBE', '2025-08-01', '2025-09-30', 'wahdawinda@gmail.com', 'B', '2025-08-31 13:39:21'),
(4, 5, 'MUGOL MUGISHA', '0789000765', 1, 'MIPANGO', '2025-08-08', '2025-09-30', 'mugolmugisha@gmail.com', 'A', '2025-08-31 13:41:20'),
(6, 6, 'moses pelegrine', '+255759342883', 3, 'university of dodoma', '2025-08-01', '2025-09-30', 'moses@gmail.com', 'B', '2025-08-31 18:58:16');

-- --------------------------------------------------------

--
-- Table structure for table `ip_logs`
--

CREATE TABLE `ip_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` enum('login','check_in','check_out') NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `key_name` varchar(50) NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key_name`, `value`) VALUES
(1, 'attendance_location', '{\"latitude\":\"-6.217180437556974\",\"longitude\":\"35.814640869036765\"}');

-- --------------------------------------------------------

--
-- Table structure for table `system_documents`
--

CREATE TABLE `system_documents` (
  `id` int(11) NOT NULL,
  `type` enum('certificate','registration') NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `deadline` date NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','field_worker') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'isaac', '123456789', 'admin', '2025-08-18 05:36:17'),
(2, 'juma', 'zxcvbnm', 'field_worker', '2025-08-26 15:02:07'),
(3, 'adija', '123456789', 'field_worker', '2025-08-26 15:02:07'),
(4, 'wahda', '123456789', 'field_worker', '2025-08-26 16:16:35'),
(5, 'mugol', '123456789', 'field_worker', '2025-08-31 11:05:13'),
(6, 'moses', '123456789', 'field_worker', '2025-08-31 16:06:26');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admindocuments`
--
ALTER TABLE `admindocuments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_announcements_department` (`department_id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `field_worker_profiles`
--
ALTER TABLE `field_worker_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `ip_logs`
--
ALTER TABLE `ip_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key_name` (`key_name`);

--
-- Indexes for table `system_documents`
--
ALTER TABLE `system_documents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admindocuments`
--
ALTER TABLE `admindocuments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `field_worker_profiles`
--
ALTER TABLE `field_worker_profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `ip_logs`
--
ALTER TABLE `ip_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `system_documents`
--
ALTER TABLE `system_documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `fk_announcements_department` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `field_worker_profiles`
--
ALTER TABLE `field_worker_profiles`
  ADD CONSTRAINT `field_worker_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `field_worker_profiles_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`);

--
-- Constraints for table `ip_logs`
--
ALTER TABLE `ip_logs`
  ADD CONSTRAINT `ip_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
