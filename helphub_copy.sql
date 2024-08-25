-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 11, 2024 at 09:08 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `helphub_copy`
--

-- --------------------------------------------------------

--
-- Table structure for table `mis_employees`
--

CREATE TABLE `mis_employees` (
  `admin_number` int(15) NOT NULL,
  `password` varchar(50) NOT NULL,
  `f_name` varchar(255) NOT NULL,
  `l_name` varchar(255) NOT NULL,
  `position` text NOT NULL,
  `user_type` text NOT NULL,
  `email_address` varchar(45) NOT NULL,
  `birthday` varchar(45) NOT NULL,
  `age` int(10) NOT NULL,
  `sex` varchar(10) NOT NULL,
  `profile_picture` mediumblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mis_history_logs`
--

CREATE TABLE `mis_history_logs` (
  `history_id` int(255) NOT NULL,
  `admin_number` int(10) NOT NULL,
  `date_time` datetime NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_survey_feedback`
--

CREATE TABLE `tb_survey_feedback` (
  `survey_id` int(10) NOT NULL,
  `overall_satisfaction` varchar(50) NOT NULL,
  `service_rating` varchar(50) NOT NULL,
  `service_expectations` varchar(50) NOT NULL,
  `like_service` varchar(255) NOT NULL,
  `improvement` varchar(255) NOT NULL,
  `comments` varchar(255) NOT NULL,
  `user_id` int(15) NOT NULL,
  `ticket_id` int(10) NOT NULL,
  `taken` text NOT NULL,
  `date_time` datetime NOT NULL,
  `bayes_rating_like` varchar(10) NOT NULL,
  `bayes_rating_improve` varchar(10) NOT NULL,
  `bayes_rating_comment` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_tickets`
--

CREATE TABLE `tb_tickets` (
  `ticket_id` int(10) NOT NULL,
  `created_date` datetime NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `user_number` int(15) NOT NULL,
  `campus` text NOT NULL,
  `department` text NOT NULL,
  `course` text NOT NULL,
  `year_section` varchar(5) NOT NULL,
  `sex` text NOT NULL,
  `age` int(3) NOT NULL,
  `user_type` text NOT NULL,
  `issue` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `screenshot` mediumblob NOT NULL,
  `consent` varchar(10) NOT NULL,
  `status` text NOT NULL,
  `employee` varchar(50) NOT NULL,
  `opened_date` datetime DEFAULT NULL,
  `finished_date` datetime DEFAULT NULL,
  `duration` varchar(50) NOT NULL,
  `resolution` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE `tb_user` (
  `user_id` int(15) NOT NULL,
  `email_address` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(50) NOT NULL,
  `campus` text NOT NULL,
  `department` text NOT NULL,
  `course` text NOT NULL,
  `year_section` varchar(5) NOT NULL,
  `profile_picture` mediumblob NOT NULL,
  `sex` text NOT NULL,
  `birthday` date DEFAULT NULL,
  `age` int(3) NOT NULL,
  `user_type` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_logs`
--

CREATE TABLE `ticket_logs` (
  `history_id` int(255) NOT NULL,
  `ticket_id` int(10) NOT NULL,
  `date_time` datetime NOT NULL,
  `description` varchar(255) NOT NULL,
  `status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `timeset`
--

CREATE TABLE `timeset` (
  `open_time` varchar(10) NOT NULL,
  `close_time` varchar(10) NOT NULL,
  `purpose` varchar(45) NOT NULL,
  `time_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_history_logs`
--

CREATE TABLE `user_history_logs` (
  `history_id` int(255) NOT NULL,
  `user_id` int(10) NOT NULL,
  `date_time` datetime NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mis_employees`
--
ALTER TABLE `mis_employees`
  ADD PRIMARY KEY (`f_name`);

--
-- Indexes for table `mis_history_logs`
--
ALTER TABLE `mis_history_logs`
  ADD PRIMARY KEY (`history_id`);

--
-- Indexes for table `tb_survey_feedback`
--
ALTER TABLE `tb_survey_feedback`
  ADD PRIMARY KEY (`survey_id`);

--
-- Indexes for table `tb_tickets`
--
ALTER TABLE `tb_tickets`
  ADD PRIMARY KEY (`ticket_id`);

--
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `ticket_logs`
--
ALTER TABLE `ticket_logs`
  ADD PRIMARY KEY (`history_id`);

--
-- Indexes for table `timeset`
--
ALTER TABLE `timeset`
  ADD PRIMARY KEY (`time_id`);

--
-- Indexes for table `user_history_logs`
--
ALTER TABLE `user_history_logs`
  ADD PRIMARY KEY (`history_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mis_history_logs`
--
ALTER TABLE `mis_history_logs`
  MODIFY `history_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `tb_survey_feedback`
--
ALTER TABLE `tb_survey_feedback`
  MODIFY `survey_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `tb_tickets`
--
ALTER TABLE `tb_tickets`
  MODIFY `ticket_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `ticket_logs`
--
ALTER TABLE `ticket_logs`
  MODIFY `history_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `timeset`
--
ALTER TABLE `timeset`
  MODIFY `time_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_history_logs`
--
ALTER TABLE `user_history_logs`
  MODIFY `history_id` int(255) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
