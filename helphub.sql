-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 05, 2024 at 05:01 AM
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
-- Database: `helphub`
--

-- --------------------------------------------------------

--
-- Table structure for table `mis_employees`
--

CREATE TABLE `mis_employees` (
  `employee_number` int(15) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(50) NOT NULL,
  `position` text NOT NULL,
  `specialization` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mis_employees`
--

INSERT INTO `mis_employees` (`employee_number`, `name`, `password`, `position`, `specialization`) VALUES
(1000000000, 'John Felix Pascual', '1234567890', 'MIS Staff', 'Account Management');

-- --------------------------------------------------------

--
-- Table structure for table `student_user`
--

CREATE TABLE `student_user` (
  `student_number` int(15) NOT NULL,
  `email_address` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(50) NOT NULL,
  `department` text NOT NULL,
  `course` text NOT NULL,
  `year_section` varchar(5) NOT NULL,
  `profile_picture` varchar(255) NOT NULL,
  `sex` text NOT NULL,
  `user_type` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_user`
--

INSERT INTO `student_user` (`student_number`, `email_address`, `name`, `password`, `department`, `course`, `year_section`, `profile_picture`, `sex`, `user_type`) VALUES
(2021306790, '2021306790@dhvsu.edu.ph', 'Alvin Nebres Jr.', '2021306790', 'College Of Computing Studies', 'Bachelor Of Science In Information Technology', '3G', '2021306790.jpg', 'Male', 'Student'),
(2021306781, '2021306781@dhvsu.edu.ph', 'Earvin John Miranda', '2021306781', 'College Of Computing Studies', 'Bachelor Of Science In Information Technology', '3G', '2021306781.jpg', 'Male', 'Student'),
(2021304903, '2021304903@dhvsu.edu.ph', 'Ricayelle Medina', '2021304903', 'College Of Computing Studies', 'Bachelor Of Science In Information Technology', '3G', '2021304903.jpg', 'Female', 'Student'),
(2021305564, '2021305564@dhvsu.edu.ph', 'Maria Angela Mungcal', '2021305564', 'College Of Computing Studies', 'Bachelor Of Science In Information Technology', '3G', '2021305564.jpg', 'Female', 'Student'),
(2021304882, '2021304882@dhvsu.edu.ph', 'Jhon Felix Pascual', '2021304882', 'College Of Computing Studies', 'Bachelor Of Science In Information Technology', '3G', '2021304882.jpg', 'Male', 'Student'),
(2021305272, '2021305272@dhvsu.edu.ph', 'Jean Nerilot Palayar', '2021305272', 'College Of Computing Studies', 'Bachelor Of Science In Information Technology', '3G', '2021305272.jpg', 'Female', 'Student');

-- --------------------------------------------------------

--
-- Table structure for table `tb_tickets`
--

CREATE TABLE `tb_tickets` (
  `ticket_id` int(10) NOT NULL,
  `user_number` int(15) NOT NULL,
  `issue` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `screenshot` mediumblob NOT NULL,
  `consent` varchar(10) NOT NULL,
  `status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_tickets`
--

INSERT INTO `tb_tickets` (`ticket_id`, `user_number`, `issue`, `description`, `screenshot`, `consent`, `status`) VALUES
(1, 2021306790, 'sms account', '', '', '', 'Due'),
(2, 2021306790, 'google account', '', '', '', 'Completed'),
(3, 2021306790, 'SMS Account', '', '', '', 'Returned'),
(4, 2021306790, 'Google Account', '', '', '', 'Returned'),
(5, 2021306790, 'SMS Account', '', '', '', 'Pending'),
(6, 2021306790, 'Google Account', '', '', '', 'Pending');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
