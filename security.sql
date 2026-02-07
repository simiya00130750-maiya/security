-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 07, 2026 at 08:14 AM
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
-- Database: `security`
--

-- --------------------------------------------------------

--
-- Table structure for table `alerts`
--

CREATE TABLE `alerts` (
  `alert_id` int(11) NOT NULL,
  `event_id` int(11) DEFAULT NULL,
  `alert_type` enum('email','sms','system') DEFAULT NULL,
  `alert_time` datetime DEFAULT current_timestamp(),
  `status` enum('sent','pending','failed') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cameras`
--

CREATE TABLE `cameras` (
  `camera_id` int(11) NOT NULL,
  `device_id` int(11) DEFAULT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `resolution` varchar(20) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'inactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `camera_records`
--

CREATE TABLE `camera_records` (
  `record_id` int(11) NOT NULL,
  `camera_id` int(11) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `devices`
--

CREATE TABLE `devices` (
  `device_id` int(11) NOT NULL,
  `device_name` varchar(100) NOT NULL,
  `type_id` int(11) DEFAULT NULL,
  `location_id` int(11) DEFAULT NULL,
  `status` enum('online','offline','maintenance') DEFAULT 'offline',
  `install_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `devices`
--

INSERT INTO `devices` (`device_id`, `device_name`, `type_id`, `location_id`, `status`, `install_date`) VALUES
(1, 'Main Gate Camera', 1, 1, 'online', '2023-01-15'),
(2, 'Backyard Sensor', 2, 2, 'online', '2023-02-10'),
(3, 'Kitchen Smoke Detector', 3, 3, 'online', '2023-03-05');

-- --------------------------------------------------------

--
-- Table structure for table `device_types`
--

CREATE TABLE `device_types` (
  `type_id` int(11) NOT NULL,
  `type_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `device_types`
--

INSERT INTO `device_types` (`type_id`, `type_name`) VALUES
(1, 'CCTV Camera'),
(2, 'Motion Sensor'),
(3, 'Smoke Detector');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `location_id` int(11) NOT NULL,
  `location_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`location_id`, `location_name`, `description`) VALUES
(1, 'Main Gate', NULL),
(2, 'Backyard', NULL),
(3, 'Kitchen', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `action_time` datetime DEFAULT current_timestamp(),
  `ip_address` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `permission_id` int(11) NOT NULL,
  `permission_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`) VALUES
(1, 'Admin'),
(2, 'User'),
(3, 'Security');

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `security_events`
--

CREATE TABLE `security_events` (
  `event_id` int(11) NOT NULL,
  `device_id` int(11) DEFAULT NULL,
  `event_type` varchar(50) DEFAULT NULL,
  `event_time` datetime DEFAULT current_timestamp(),
  `severity` enum('low','medium','high') DEFAULT 'low',
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `security_events`
--

INSERT INTO `security_events` (`event_id`, `device_id`, `event_type`, `event_time`, `severity`, `description`) VALUES
(1, 1, 'รถยนตร์', '2026-01-24 15:11:35', 'low', 'พบรถยนตร์ผิดปกติ1คัน'),
(2, 1, 'มอเตอร์ไซต์', '2026-01-24 15:29:00', 'low', 'พบผู้ไม่มีชื่อในระบบเข้ามาโดยไม่ได้รับอนุญาติ'),
(3, 1, 'รถยนตร์', '2026-01-24 15:29:56', 'low', 'คนาน้ัทดาเด'),
(4, 1, 'ประตูเปิดค้าง', '2026-01-24 15:35:37', 'low', 'แมวแอบเข้าประตู'),
(5, 1, 'คน', '2026-01-24 15:36:36', 'low', 'มีึนแอบลับๆล่อๆในพื้นที่'),
(6, 1, 'รถยนตร์', '2026-01-24 15:37:14', 'low', 'มีรถไม่ทราบป้ายทะเบียนเข้ามาจอดในพื้นที่'),
(7, 1, 'ประตูห้องสุดท้าย', '2026-01-24 15:38:13', 'low', 'ปลิ่วเนื่องมีลมแรง'),
(8, 1, 'ต้นไม้', '2026-01-24 15:38:34', 'low', 'ต้นไม้ล้ม'),
(9, 1, 'รถยนตร์', '2026-01-24 15:40:10', 'low', 'รถชนกับเสาไฟฟ้า'),
(10, 1, 'รถยนตร์', '2026-01-24 15:40:48', 'low', 'ไม่ทราบป้ายทะเบียนเข้ามาตอนกลางดึก');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `setting_id` int(11) NOT NULL,
  `setting_name` varchar(100) NOT NULL,
  `setting_value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password_hash`, `email`, `role_id`, `status`, `last_login`) VALUES
(4, 'บีเดาะ', '$2y$10$9v7ZsGLqqrUiuionHj/DnepwH5MAK4zdL09scNbiOrIoX.2oKlg/q', 'abc@gmail.com', 2, 'active', NULL),
(5, 'แมะซง', '$2y$10$544ZqsGzU.ZwguhvDgj9sOb1dTuX/hTjCb.qOfsG72vQdaGbIg2qy', 'Def@gmail.com', 2, 'active', NULL),
(6, 'ตีเมาะ', '$2y$10$xSpVWrQgatpTAhMF9A7RSuSD9jmrV7bXXy9Vxk992j1SxpHobmrgC', 'pot@gmail.com', 2, 'active', NULL),
(7, 'เมาะเยาะ', '$2y$10$orE2SJQ1I.qB77tACdqKr.Iv4RTIogb.f89hfMHlI13JP9W06n7lG', 'NGR@mail.com', 2, 'active', NULL),
(8, 'มีเนาะ', '$2y$10$sZXkouFtVm1gVbzah3HvqubcqWWtvMhhi8/T40nCdUw1jGe6YsACO', 'WHER@gmail.com', 2, 'active', NULL),
(9, 'ซีตีรอกายะ', '$2y$10$DHJ5H.o3tu3sYy2oo00NeO0nC/h5jGpaLgku9gHluCgJrIFxBA6q.', 'SDF@gmail.com', 2, 'active', NULL),
(10, 'ยาเราะ', '$2y$10$ELoEI8NY0Ub5.mjyZiPMJ..0jPNfwIqZgJJ1L2LYR7lb56FYEVsHy', 'Vcx@gmail.com', 2, 'active', NULL),
(11, 'แมะนา', '$2y$10$rQqNpcwciIfxyipQ4cEgJ.hmVRxFVvSlD6icH9l.52fjgJ3Z9oJ1C', 'Kpi@mail.com', 2, 'active', NULL),
(12, 'แมะตา', '$2y$10$9J1IB9Q95gzdWgbfm7RGlu57yj5gxjDex/oTMi04FjrZsTS13fT8O', 'Poy@gmail.com', 2, 'active', NULL),
(13, 'เมาะแย', '$2y$10$CM7Ez13aHtF/RsxVkiHQkudFX.qyq.N32sqo9ak9YpHAa1Lii1Pgi', 'Zit@gmail.com', 2, 'active', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alerts`
--
ALTER TABLE `alerts`
  ADD PRIMARY KEY (`alert_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `cameras`
--
ALTER TABLE `cameras`
  ADD PRIMARY KEY (`camera_id`),
  ADD KEY `device_id` (`device_id`);

--
-- Indexes for table `camera_records`
--
ALTER TABLE `camera_records`
  ADD PRIMARY KEY (`record_id`),
  ADD KEY `camera_id` (`camera_id`);

--
-- Indexes for table `devices`
--
ALTER TABLE `devices`
  ADD PRIMARY KEY (`device_id`),
  ADD KEY `type_id` (`type_id`),
  ADD KEY `location_id` (`location_id`);

--
-- Indexes for table `device_types`
--
ALTER TABLE `device_types`
  ADD PRIMARY KEY (`type_id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`location_id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`permission_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`role_id`,`permission_id`),
  ADD KEY `permission_id` (`permission_id`);

--
-- Indexes for table `security_events`
--
ALTER TABLE `security_events`
  ADD PRIMARY KEY (`event_id`),
  ADD KEY `device_id` (`device_id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`setting_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alerts`
--
ALTER TABLE `alerts`
  MODIFY `alert_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cameras`
--
ALTER TABLE `cameras`
  MODIFY `camera_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `camera_records`
--
ALTER TABLE `camera_records`
  MODIFY `record_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `devices`
--
ALTER TABLE `devices`
  MODIFY `device_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `device_types`
--
ALTER TABLE `device_types`
  MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `location_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `permission_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `security_events`
--
ALTER TABLE `security_events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `alerts`
--
ALTER TABLE `alerts`
  ADD CONSTRAINT `alerts_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `security_events` (`event_id`);

--
-- Constraints for table `cameras`
--
ALTER TABLE `cameras`
  ADD CONSTRAINT `cameras_ibfk_1` FOREIGN KEY (`device_id`) REFERENCES `devices` (`device_id`);

--
-- Constraints for table `camera_records`
--
ALTER TABLE `camera_records`
  ADD CONSTRAINT `camera_records_ibfk_1` FOREIGN KEY (`camera_id`) REFERENCES `cameras` (`camera_id`);

--
-- Constraints for table `devices`
--
ALTER TABLE `devices`
  ADD CONSTRAINT `devices_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `device_types` (`type_id`),
  ADD CONSTRAINT `devices_ibfk_2` FOREIGN KEY (`location_id`) REFERENCES `locations` (`location_id`);

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`),
  ADD CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`permission_id`);

--
-- Constraints for table `security_events`
--
ALTER TABLE `security_events`
  ADD CONSTRAINT `security_events_ibfk_1` FOREIGN KEY (`device_id`) REFERENCES `devices` (`device_id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
