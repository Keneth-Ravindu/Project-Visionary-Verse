-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 12, 2026 at 05:08 PM
-- Server version: 8.4.7
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `visionary_verse`
--

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
CREATE TABLE IF NOT EXISTS `clients` (
  `client_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`client_id`),
  KEY `user_id` (`user_id`),
  KEY `idx_status` (`status`),
  KEY `idx_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`client_id`, `user_id`, `name`, `email`, `company`, `phone`, `status`, `created_at`, `updated_at`) VALUES
(2, 5, 'Anura Mahattaya', 'client2@startupco.com', 'Startup Co', '+94 761234567', 'active', '2026-03-06 23:17:39', '2026-03-12 12:16:07'),
(5, NULL, 'Keneth', 'ken@insightOp.com', 'InsightOps', '+94 761234567', 'active', '2026-03-07 05:11:01', '2026-03-12 05:59:25'),
(10, NULL, 'Nipuni', 'nipuni@gmail.com', 'Nipuni', '+94 761234567', 'active', '2026-03-12 06:07:24', '2026-03-12 15:17:31'),
(11, NULL, 'Ravindu', 'rav@gmail.com', 'StarBucks', '+94 761234567', 'inactive', '2026-03-12 12:39:01', '2026-03-12 16:02:31'),
(12, NULL, 'Chathumini', 'Rack@gmail.com', 'Kegalle', '+94 761234567', 'inactive', '2026-03-12 16:01:57', '2026-03-12 16:02:17');

-- --------------------------------------------------------

--
-- Table structure for table `deliverables`
--

DROP TABLE IF EXISTS `deliverables`;
CREATE TABLE IF NOT EXISTS `deliverables` (
  `deliverable_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `project_id` int NOT NULL,
  `uploaded_by` int NOT NULL,
  `file_path` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Pending','Approved','Changes Requested') COLLATE utf8mb4_unicode_ci DEFAULT 'Pending',
  `feedback` text COLLATE utf8mb4_unicode_ci,
  `submitted_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`deliverable_id`),
  KEY `uploaded_by` (`uploaded_by`),
  KEY `idx_status` (`status`),
  KEY `idx_project` (`project_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `deliverables`
--

INSERT INTO `deliverables` (`deliverable_id`, `name`, `project_id`, `uploaded_by`, `file_path`, `file_name`, `status`, `feedback`, `submitted_at`, `reviewed_at`) VALUES
(2, 'Ad Campaign Draft', 2, 3, NULL, NULL, 'Approved', NULL, '2026-03-06 23:17:39', '2026-03-07 18:52:10'),
(4, 'Draft 1', 2, 5, NULL, 'example.pdf', 'Approved', NULL, '2026-03-07 08:47:45', '2026-03-07 08:58:09'),
(6, 'Tiktok content', 2, 3, NULL, 'example3.pdf', 'Changes Requested', 'Changes requested by client.', '2026-03-07 12:09:51', '2026-03-07 18:53:51'),
(7, 'Draft3', 2, 1, NULL, 'example2.pdf', 'Approved', NULL, '2026-03-07 18:51:48', '2026-03-07 18:52:30'),
(9, 'Image2', 11, 5, '/pvv/public/uploads/deliverables/1773303097_John_Cena_Profile.png', 'John_Cena_Profile.png', 'Approved', NULL, '2026-03-12 08:11:37', '2026-03-12 08:12:04'),
(10, 'Backend Development', 10, 6, '/pvv/public/uploads/deliverables/1773318334_John_Cena_Profile.png', 'John_Cena_Profile.png', 'Approved', NULL, '2026-03-12 12:25:34', '2026-03-12 12:26:24'),
(11, 'Tiktok content', 10, 1, '/pvv/public/uploads/deliverables/1773318609_John_Cena_Profile.png', 'John_Cena_Profile.png', 'Approved', NULL, '2026-03-12 12:30:09', '2026-03-12 12:30:53'),
(12, 'Web design', 10, 6, '/pvv/public/uploads/deliverables/1773326403_John_Cena_Profile.png', 'John_Cena_Profile.png', 'Pending', NULL, '2026-03-12 14:40:03', NULL),
(13, 'John Cena Picture', 12, 6, '/pvv/public/uploads/deliverables/1773331702_John_Cena_Profile.png', 'John_Cena_Profile.png', 'Pending', NULL, '2026-03-12 16:08:22', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `notification_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `type` enum('task','approval','dss','system') COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`notification_id`),
  KEY `idx_user_read` (`user_id`,`is_read`),
  KEY `idx_created` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `user_id`, `type`, `message`, `is_read`, `created_at`) VALUES
(1, 1, 'task', 'Task \"Ad copy creation\" moved to Review', 1, '2026-03-06 23:17:39'),
(2, 1, 'approval', 'Deliverable \"Ad Campaign Draft\" needs approval', 1, '2026-03-06 23:17:39'),
(3, 4, 'approval', 'Your deliverable has been approved', 1, '2026-03-06 23:17:39'),
(4, 2, 'task', 'You have been assigned to \"On-page optimization\"', 1, '2026-03-06 23:17:39'),
(5, 1, 'approval', 'Deliverable \"Image2\" has been uploaded for approval', 0, '2026-03-12 08:11:37'),
(6, 5, 'approval', 'Your deliverable has been approved', 0, '2026-03-12 08:12:04'),
(7, 5, 'approval', 'Changes requested for deliverable \"Image1\"', 0, '2026-03-12 08:12:12'),
(8, 1, 'dss', 'High risk detected for project \"Project 2\"', 0, '2026-03-12 09:14:55'),
(9, 1, 'dss', 'High risk detected for project \"Project Opticals\"', 0, '2026-03-12 09:14:55'),
(10, 1, 'dss', 'High risk detected for project \"Tiktok content\"', 1, '2026-03-12 09:14:55'),
(11, 3, 'task', 'You have been assigned to \"Create Styles\"', 0, '2026-03-12 12:17:15'),
(12, 3, 'task', 'Task \"Create Styles\" moved to Review', 0, '2026-03-12 12:17:21'),
(13, 3, 'task', 'Task \"Create Styles\" moved to Done', 0, '2026-03-12 12:17:22'),
(14, 3, 'task', 'Task \"Create Styles\" moved to To Do', 0, '2026-03-12 12:17:23'),
(15, 6, 'task', 'You have been assigned to \"Web design\"', 0, '2026-03-12 12:18:15'),
(16, 6, 'approval', 'Deliverable \"Backend Development\" has been uploaded for approval', 0, '2026-03-12 12:25:34'),
(17, 6, 'approval', 'Your deliverable has been approved', 0, '2026-03-12 12:26:25'),
(18, 1, 'approval', 'Deliverable \"Tiktok content\" has been uploaded for approval', 0, '2026-03-12 12:30:09'),
(19, 1, 'approval', 'Your deliverable has been approved', 0, '2026-03-12 12:30:53'),
(20, 6, 'task', 'You have been assigned to \"Create Frontend\"', 0, '2026-03-12 12:41:43'),
(21, 5, 'task', 'You have been assigned to \"Create Backend\"', 0, '2026-03-12 12:43:18'),
(22, 1, 'dss', 'High risk detected for project \"StarBucks Project\"', 0, '2026-03-12 12:43:25'),
(23, 6, 'task', 'You have been assigned to \"Create Styles\"', 0, '2026-03-12 14:38:25'),
(24, 6, 'approval', 'Deliverable \"Web design\" has been uploaded for approval', 0, '2026-03-12 14:40:03'),
(25, 6, 'task', 'You have been assigned to \"Create Views\"', 0, '2026-03-12 16:06:59'),
(26, 6, 'approval', 'Deliverable \"John Cena Picture\" has been uploaded for approval', 0, '2026-03-12 16:08:22'),
(27, 6, 'task', 'Task \"Create Views\" moved to Review', 0, '2026-03-12 16:11:02'),
(28, 6, 'task', 'Task \"Create Views\" moved to Done', 0, '2026-03-12 16:11:02'),
(29, 6, 'task', 'Task \"Create Views\" moved to To Do', 0, '2026-03-12 16:11:04'),
(30, 6, 'task', 'Task \"Create Views\" moved to In Progress', 0, '2026-03-12 16:11:05');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
CREATE TABLE IF NOT EXISTS `projects` (
  `project_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_id` int NOT NULL,
  `service` enum('SEO','Ads','Social Media','Web Design','Content','Other') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('To Do','In Progress','Review','Completed') COLLATE utf8mb4_unicode_ci DEFAULT 'To Do',
  `due_date` date DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`project_id`),
  KEY `client_id` (`client_id`),
  KEY `idx_status` (`status`),
  KEY `idx_service` (`service`),
  KEY `idx_due_date` (`due_date`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`project_id`, `name`, `client_id`, `service`, `status`, `due_date`, `description`, `created_at`, `updated_at`) VALUES
(2, 'Ads Campaign', 2, 'Ads', 'Completed', '2026-03-30', 'Q1 Google Ads campaign for Startup Co', '2026-03-06 23:17:39', '2026-03-12 06:25:45'),
(8, 'Tiktok content', 2, 'Social Media', 'Completed', '2026-03-13', '', '2026-03-12 06:00:31', '2026-03-12 06:25:48'),
(9, 'Project VS', 5, 'Web Design', 'Completed', '2026-03-27', '', '2026-03-12 06:04:44', '2026-03-12 15:16:10'),
(10, 'Project Opticals', 10, 'Web Design', 'Completed', '2026-03-08', '', '2026-03-12 06:08:07', '2026-03-12 16:12:27'),
(11, 'Project 2', 10, 'Web Design', 'To Do', '2026-03-14', '', '2026-03-12 06:47:41', '2026-03-12 06:47:41'),
(12, 'StarBucks Project', 11, 'Web Design', 'Review', '2026-03-14', '', '2026-03-12 12:39:47', '2026-03-12 16:31:43');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

DROP TABLE IF EXISTS `reports`;
CREATE TABLE IF NOT EXISTS `reports` (
  `report_id` int NOT NULL AUTO_INCREMENT,
  `project_id` int NOT NULL,
  `progress_percentage` int DEFAULT '0',
  `tasks_todo` int DEFAULT '0',
  `tasks_in_progress` int DEFAULT '0',
  `tasks_review` int DEFAULT '0',
  `tasks_done` int DEFAULT '0',
  `risk_level` enum('Low','Medium','High') COLLATE utf8mb4_unicode_ci DEFAULT 'Low',
  `generated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`report_id`),
  KEY `idx_project` (`project_id`),
  KEY `idx_generated` (`generated_at`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`report_id`, `project_id`, `progress_percentage`, `tasks_todo`, `tasks_in_progress`, `tasks_review`, `tasks_done`, `risk_level`, `generated_at`) VALUES
(2, 2, 62, 3, 4, 2, 10, 'Low', '2026-03-06 23:17:39');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

DROP TABLE IF EXISTS `tasks`;
CREATE TABLE IF NOT EXISTS `tasks` (
  `task_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `project_id` int NOT NULL,
  `assignee_id` int DEFAULT NULL,
  `priority` enum('Low','Medium','High') COLLATE utf8mb4_unicode_ci DEFAULT 'Medium',
  `status` enum('To Do','In Progress','Review','Done') COLLATE utf8mb4_unicode_ci DEFAULT 'To Do',
  `deadline` date DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`task_id`),
  KEY `project_id` (`project_id`),
  KEY `assignee_id` (`assignee_id`),
  KEY `idx_status` (`status`),
  KEY `idx_priority` (`priority`),
  KEY `idx_deadline` (`deadline`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`task_id`, `name`, `project_id`, `assignee_id`, `priority`, `status`, `deadline`, `description`, `created_at`, `updated_at`) VALUES
(17, 'Create Frontend', 12, 6, 'High', 'To Do', '2026-03-13', '', '2026-03-12 12:41:43', '2026-03-12 12:41:43'),
(18, 'Create Backend', 12, 5, 'High', 'To Do', '2026-03-13', '', '2026-03-12 12:43:18', '2026-03-12 12:43:18'),
(19, 'Create Styles', 12, 6, 'High', 'To Do', '2026-03-12', '', '2026-03-12 14:38:25', '2026-03-12 14:38:25'),
(20, 'Create Views', 12, 6, 'High', 'In Progress', '2026-03-13', '', '2026-03-12 16:06:59', '2026-03-12 16:11:05');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','staff','client') COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_email` (`email`),
  KEY `idx_role` (`role`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `email`, `password`, `role`, `full_name`, `created_at`, `updated_at`) VALUES
(1, 'admin@visionaryverse.com', '$2y$10$bi9RTjc/ZE4MAQdnGCZfw.pxM2dDcTtCmla4/iW2tsEWbXCtv/BE6', 'admin', 'Admin User', '2026-03-06 23:17:39', '2026-03-12 09:37:49'),
(2, 'john@visionaryverse.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'staff', 'John Smith', '2026-03-06 23:17:39', '2026-03-06 23:17:39'),
(3, 'sarah@visionaryverse.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'staff', 'Sarah Johnson', '2026-03-06 23:17:39', '2026-03-06 23:17:39'),
(4, 'client1@techcorp.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'client', 'Mike Wilson', '2026-03-06 23:17:39', '2026-03-06 23:17:39'),
(5, 'client2@startupco.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'client', 'Emily Davis', '2026-03-06 23:17:39', '2026-03-06 23:17:39'),
(6, 'ken@gmail.com', '$2y$10$m1uJB8UNcdCJpAICHs/PqO4U9Op3/CUYVfSmUvfh70/g1Ls5Sy022', 'staff', 'Keneth Ravindu', '2026-03-12 10:44:14', '2026-03-12 10:44:14'),
(7, 'nipuni@gmail.com', '$2y$10$.FMNRogC/cwOFTDQ5CiUqe1zCzYChFosXK4W9fE4WCBsVIoLcMUFe', 'client', 'Nipuni Nivarthana', '2026-03-12 10:45:18', '2026-03-12 10:45:18'),
(8, 'randula@gmail.com', '$2y$10$XTwRsRgJbSYeLBz9lV14s.jmXkKrrfr2iOvEXOuUg65tmNFGA1jbS', 'staff', 'Randula', '2026-03-12 16:26:08', '2026-03-12 16:29:25');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `clients_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `deliverables`
--
ALTER TABLE `deliverables`
  ADD CONSTRAINT `deliverables_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `deliverables_ibfk_2` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`client_id`) ON DELETE CASCADE;

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE;

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tasks_ibfk_2` FOREIGN KEY (`assignee_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
