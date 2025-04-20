-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 25, 2024 at 05:11 PM
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
-- Database: `school_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `assembly_performance_reports`
--

CREATE TABLE `assembly_performance_reports` (
  `id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `activity` text NOT NULL,
  `students_participated` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assembly_performance_reports`
--

INSERT INTO `assembly_performance_reports` (`id`, `teacher_id`, `class_id`, `date`, `activity`, `students_participated`, `created_at`) VALUES
(1, 7, 3, '2024-12-26', 'lead assembly', 'ashu', '2024-12-25 14:51:33'),
(2, 7, 6, '2024-12-19', 'dance', 'abc', '2024-12-25 15:08:20'),
(3, 7, 3, '2024-12-18', 'dance', 'manoj bhai', '2024-12-25 15:23:41');

-- --------------------------------------------------------

--
-- Table structure for table `attendance_reports`
--

CREATE TABLE `attendance_reports` (
  `id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `total_classroom_days` int(11) NOT NULL,
  `average_attendance` float NOT NULL,
  `students_with_3_or_more_absences` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance_reports`
--

INSERT INTO `attendance_reports` (`id`, `teacher_id`, `class_id`, `total_classroom_days`, `average_attendance`, `students_with_3_or_more_absences`, `created_at`) VALUES
(1, 7, 3, 24, 80, 'Rudra, Prachi', '2024-12-25 14:39:50'),
(2, 7, 6, 20, 60, 'anish', '2024-12-25 15:08:04');

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`id`, `name`) VALUES
(1, '3A'),
(2, ' 4C'),
(3, '9C'),
(5, '10C'),
(6, '11A'),
(7, '3B');

-- --------------------------------------------------------

--
-- Table structure for table `subjective_reports`
--

CREATE TABLE `subjective_reports` (
  `id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `lesson_completed` text NOT NULL,
  `activities` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjective_reports`
--

INSERT INTO `subjective_reports` (`id`, `teacher_id`, `class_id`, `subject`, `lesson_completed`, `activities`, `created_at`) VALUES
(1, 7, 6, 'Economics', '2', 'paper done', '2024-12-25 15:05:16'),
(2, 7, 6, 'sanskrit', '12', 'bcd', '2024-12-25 15:08:52');

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `password_reset_token` varchar(255) DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`id`, `name`, `email`, `password`, `password_reset_token`, `token_expiry`) VALUES
(3, 'Kunjan', 'kunjan@gmail.com', '$2y$10$sXHttApK9a1eNeMj3T/JBeaFEEEHOIGhOUeOJhzu6q1WbI35XKmsG', NULL, NULL),
(5, 'Honey', 'honey@gmail.com', '$2y$10$jWW8zvtaO3uzf26nMtH6gusefBJey5u11k9wFQ4xo55QSrDeo7LCi', NULL, NULL),
(6, 'Mahima', 'mahima@gmail.com', '$2y$10$Gqk0xDF0d5kulS.yNjtpX.UsvwoSXzJ0791gBYyVxdSIjoFDtyfhW', '6578d81ff2e89c33e17197f69b7d0ed18d915ac0a4fa03f7986e35b3e336fe9d', '2024-12-25 13:27:42'),
(7, 'Chayan', 'chayan@gmail.com', '$2y$10$8OVUvDD8bDxqe3nWoEgxfuHQJGcilTkaYudPfAQeLilMXu7toJWoq', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `teacher_classes`
--

CREATE TABLE `teacher_classes` (
  `id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teacher_classes`
--

INSERT INTO `teacher_classes` (`id`, `teacher_id`, `class_id`) VALUES
(4, 3, 3),
(6, 5, 3),
(7, 7, 3),
(8, 7, 6);

-- --------------------------------------------------------

--
-- Table structure for table `teacher_reports`
--

CREATE TABLE `teacher_reports` (
  `id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `academic_session` varchar(50) NOT NULL,
  `month` varchar(20) NOT NULL,
  `class_teacher_name` varchar(100) NOT NULL,
  `total_students` int(11) NOT NULL,
  `submission_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teacher_reports`
--

INSERT INTO `teacher_reports` (`id`, `teacher_id`, `class_id`, `academic_session`, `month`, `class_teacher_name`, `total_students`, `submission_time`) VALUES
(1, 7, 6, '2024-2025', 'May', 'Kunjan', 12, '2024-12-25 12:48:08'),
(2, 7, 6, '2024-2025', 'March', 'chayan', 40, '2024-12-25 15:07:45');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` enum('admin','teacher') DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `password`, `created_at`) VALUES
(1, 'Admin', 'xyz@gmail.com', 'admin', '$2y$10$qSX6SlBpQU8jOSF/rhvUMOZZDbPuox7bBuQsOT0lrT/vW5oQS36G.', '2024-12-24 11:29:34'),
(3, 'Laki', 'laki@gmail.com', 'teacher', '$2y$10$17wOn1er3CziQqUkzvmOiOAm1IVfc1TMYbKOYLzsPxNaghahwpcFK', '2024-12-25 02:58:31'),
(4, 'Ashutosh', 'parakhashutosh@gmail.com', 'teacher', '$2y$10$pDYWHKGZW5xfZCjmTjy53uCmFZzsj8rf3b.5FTpU2DvD4tUK0AW8S', '2024-12-25 06:43:30');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assembly_performance_reports`
--
ALTER TABLE `assembly_performance_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `attendance_reports`
--
ALTER TABLE `attendance_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subjective_reports`
--
ALTER TABLE `subjective_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teacher_classes`
--
ALTER TABLE `teacher_classes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `teacher_reports`
--
ALTER TABLE `teacher_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `class_id` (`class_id`);

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
-- AUTO_INCREMENT for table `assembly_performance_reports`
--
ALTER TABLE `assembly_performance_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `attendance_reports`
--
ALTER TABLE `attendance_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `subjective_reports`
--
ALTER TABLE `subjective_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `teacher_classes`
--
ALTER TABLE `teacher_classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `teacher_reports`
--
ALTER TABLE `teacher_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assembly_performance_reports`
--
ALTER TABLE `assembly_performance_reports`
  ADD CONSTRAINT `assembly_performance_reports_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `assembly_performance_reports_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `attendance_reports`
--
ALTER TABLE `attendance_reports`
  ADD CONSTRAINT `attendance_reports_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `attendance_reports_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subjective_reports`
--
ALTER TABLE `subjective_reports`
  ADD CONSTRAINT `subjective_reports_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subjective_reports_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `teacher_classes`
--
ALTER TABLE `teacher_classes`
  ADD CONSTRAINT `teacher_classes_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `teacher_classes_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `teacher_reports`
--
ALTER TABLE `teacher_reports`
  ADD CONSTRAINT `teacher_reports_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `teacher_reports_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
