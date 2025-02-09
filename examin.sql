-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 02, 2024 at 01:57 PM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `examin`
--

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

DROP TABLE IF EXISTS `courses`;
CREATE TABLE IF NOT EXISTS `courses` (
  `course_id` int NOT NULL AUTO_INCREMENT,
  `course_name` varchar(100) NOT NULL,
  `course_code` varchar(20) NOT NULL,
  PRIMARY KEY (`course_id`),
  UNIQUE KEY `course_code` (`course_code`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`course_id`, `course_name`, `course_code`) VALUES
(10, 'MA journalism', 'CBCS103p'),
(9, 'MCOM', 'CBCS102p'),
(8, 'BCOM', 'CBCS102'),
(7, 'BCA', 'CBCS101');

-- --------------------------------------------------------

--
-- Table structure for table `exams`
--

DROP TABLE IF EXISTS `exams`;
CREATE TABLE IF NOT EXISTS `exams` (
  `exam_id` int NOT NULL AUTO_INCREMENT,
  `exam_name` varchar(150) NOT NULL,
  `course_id` int DEFAULT NULL,
  `semester_id` int DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  PRIMARY KEY (`exam_id`),
  KEY `course_id` (`course_id`),
  KEY `semester_id` (`semester_id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `exams`
--

INSERT INTO `exams` (`exam_id`, `exam_name`, `course_id`, `semester_id`, `start_date`, `end_date`) VALUES
(14, 'First Internal Exmaination', 7, 9, '2024-10-10', '2024-10-20'),
(16, 'model exam', 7, 9, '2024-11-21', '2024-11-30');

-- --------------------------------------------------------

--
-- Table structure for table `exam_subjects`
--

DROP TABLE IF EXISTS `exam_subjects`;
CREATE TABLE IF NOT EXISTS `exam_subjects` (
  `subject_exam_id` int NOT NULL AUTO_INCREMENT,
  `exam_id` int DEFAULT NULL,
  `subject_id` int DEFAULT NULL,
  `exam_date` date NOT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  PRIMARY KEY (`subject_exam_id`),
  KEY `exam_id` (`exam_id`),
  KEY `subject_id` (`subject_id`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `exam_subjects`
--

INSERT INTO `exam_subjects` (`subject_exam_id`, `exam_id`, `subject_id`, `exam_date`, `start_time`, `end_time`) VALUES
(27, 16, 6, '2024-11-25', '09:30:00', '12:30:00'),
(26, 16, 8, '2024-11-21', '09:30:00', '12:30:00'),
(25, 15, 10, '2024-11-15', '09:00:00', '10:00:00'),
(24, 14, 13, '2024-10-16', '10:00:00', '11:00:00'),
(23, 14, 9, '2024-10-15', '11:00:00', '12:00:00'),
(22, 14, 8, '2024-10-14', '10:00:00', '11:00:00'),
(21, 14, 10, '2024-10-13', '01:00:00', '02:00:00'),
(20, 14, 6, '2024-10-12', '10:10:00', '11:10:00');

-- --------------------------------------------------------

--
-- Table structure for table `hall_tickets`
--

DROP TABLE IF EXISTS `hall_tickets`;
CREATE TABLE IF NOT EXISTS `hall_tickets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `admission_number` varchar(50) NOT NULL,
  `pdf_path` varchar(255) NOT NULL,
  `generated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admission_number` (`admission_number`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `hall_tickets`
--

INSERT INTO `hall_tickets` (`id`, `admission_number`, `pdf_path`, `generated_at`) VALUES
(13, '22326', 'hall_tickets/22326_16.pdf', '2024-11-12 06:13:48'),
(8, '22309', 'hall_tickets/22309_14.pdf', '2024-11-10 04:29:12'),
(11, '22335', 'hall_tickets/22335_16.pdf', '2024-11-11 06:03:22');

-- --------------------------------------------------------

--
-- Table structure for table `semesters`
--

DROP TABLE IF EXISTS `semesters`;
CREATE TABLE IF NOT EXISTS `semesters` (
  `semester_id` int NOT NULL AUTO_INCREMENT,
  `semester_name` varchar(20) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  PRIMARY KEY (`semester_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `semesters`
--

INSERT INTO `semesters` (`semester_id`, `semester_name`, `start_date`, `end_date`) VALUES
(4, '1', '2022-05-05', '2022-10-20'),
(9, '5', '2024-09-01', '2025-01-04'),
(6, '2', '2023-03-21', '2023-07-18'),
(7, '3', '2023-12-19', '2024-03-24'),
(8, '4', '2024-05-04', '2024-08-29'),
(10, '6', '2025-01-05', '2025-04-04');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
CREATE TABLE IF NOT EXISTS `students` (
  `student_id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact_number` varchar(15) DEFAULT NULL,
  `guardian_name` varchar(50) DEFAULT NULL,
  `guardian_contact_number` varchar(15) DEFAULT NULL,
  `address` text,
  `admission_number` varchar(20) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `batch` varchar(20) DEFAULT NULL,
  `year_of_study` tinyint DEFAULT NULL,
  `course_id` int DEFAULT NULL,
  `semester_id` int DEFAULT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`student_id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `admission_number` (`admission_number`),
  KEY `course_id` (`course_id`),
  KEY `semester_id` (`semester_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `first_name`, `last_name`, `email`, `contact_number`, `guardian_name`, `guardian_contact_number`, `address`, `admission_number`, `date_of_birth`, `batch`, `year_of_study`, `course_id`, `semester_id`, `photo_path`) VALUES
(3, 'Jitto', 'Abraham', 'jitto@gmail.com', '8976541321', 'Abraham Poti', '6543209811', 'Kuttoor Thiruvalla Pathanamthitta', '22326', '2004-03-11', '2022-25', 3, 7, 10, '../imagesprfl5.jpeg'),
(4, 'A', 'J', 'a@gmail.com', '212312231223', 'li', '3456677889', 'jyfjy', '22309', '2323-03-01', '2323-23', 3, 7, 9, '../imagesprfl2.jpeg'),
(5, 'Noel', 'Anil', 'noel01@gmail.com', '1234567890', 'Anil Kuriakose', '0987654321', 'abc', '22335', '2004-10-10', '2022-25', 3, 7, 9, '../imagesprfl3.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

DROP TABLE IF EXISTS `subjects`;
CREATE TABLE IF NOT EXISTS `subjects` (
  `subject_id` int NOT NULL AUTO_INCREMENT,
  `subject_name` varchar(100) NOT NULL,
  `course_id` int DEFAULT NULL,
  `semester_id` int DEFAULT NULL,
  PRIMARY KEY (`subject_id`),
  KEY `course_id` (`course_id`),
  KEY `semester_id` (`semester_id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`subject_id`, `subject_name`, `course_id`, `semester_id`) VALUES
(8, 'java programming using linux', 7, 9),
(6, 'IT and Environment', 7, 9),
(9, 'Computer Networks', 7, 9),
(10, 'English for Careers ', 7, 9),
(13, 'Software Development Lab 1', 7, 9),
(14, 'Software Lab 5', 7, 9);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `admission_number` varchar(20) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','student') NOT NULL DEFAULT 'student',
  PRIMARY KEY (`id`),
  UNIQUE KEY `admission_number` (`admission_number`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `admission_number`, `username`, `password`, `role`) VALUES
(7, 'uday', 'krishnam', 'bcad2bca@gmail.com', '22346', 'user22346', 'BSCASd132', 'student'),
(3, 'aj', 'aj', 'ajinsalim01@gmail.com', '22305', 'user22305', 'BSCAS80c0', 'student'),
(4, 'a', 'j', 'ajinsalim01@gmail.com', '22306', 'user22306', 'BSCAS35f5', 'student'),
(5, 'a', 'a', 'ajinsalim01@gmail.com', '22300', 'user22300', 'BSCAS45d6', 'admin'),
(6, 'joel', 'mathew', 'ajinsalim01@gmail.com', '22328', 'user22328', 'BSCASf548', 'student'),
(8, 'a', 'a', 'a@gmail.com', '0', 'usera', 'BSCAScc38', 'student');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
