-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 04, 2023 at 01:45 AM
-- Server version: 5.7.24
-- PHP Version: 8.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `heredb`
--

-- --------------------------------------------------------

--
-- Table structure for table `classattendancerecord`
--

CREATE TABLE `classattendancerecord` (
  `id` int(10) NOT NULL,
  `sectionNumber` int(10) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `classattendancerecord`
--

INSERT INTO `classattendancerecord` (`id`, `sectionNumber`, `date`) VALUES
(1, 3, '2023-11-01'),
(2, 2, '2023-10-30'),
(3, 3, '2023-11-02');

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `id` int(10) NOT NULL,
  `symbol` varchar(6) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`id`, `symbol`, `name`) VALUES
(1, 'IT320', 'Practical Software Engineering'),
(2, 'IT329', 'Advanced Web Technologies'),
(3, 'IC100', 'Studies in the prophet biography');

-- --------------------------------------------------------

--
-- Table structure for table `instructor`
--

CREATE TABLE `instructor` (
  `id` int(10) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `email_address` varchar(40) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `instructor`
--

INSERT INTO `instructor` (`id`, `first_name`, `last_name`, `email_address`, `password`) VALUES
(2, 'nouf', 'alessa', 'ns_f0@hotmail.com', '$2y$10$Y7.25FL.kVs2cAencx750eavjFZ8JmMIAAHfKSLcaKhwfy6B51qKK'),
(3, 'norah', 'albatily', '442202294@student.ksu.edu.sa', '$2y$10$I.P7M6URu3R3uYP9k0bXwerGboAIc7nK2z9iH7O7TCWW6VurQJZb.');

-- --------------------------------------------------------

--
-- Table structure for table `section`
--

CREATE TABLE `section` (
  `sectionNumber` int(10) NOT NULL,
  `courseID` int(10) NOT NULL,
  `type` varchar(10) NOT NULL,
  `hours` int(1) NOT NULL,
  `instructorID` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `section`
--

INSERT INTO `section` (`sectionNumber`, `courseID`, `type`, `hours`, `instructorID`) VALUES
(1, 1, 'Lab', 2, 2),
(2, 1, 'Lecture', 1, 2),
(3, 3, 'Lecture', 1, 2),
(5, 1, 'Lecture', 3, 2);

-- --------------------------------------------------------

--
-- Table structure for table `sectionstudents`
--

CREATE TABLE `sectionstudents` (
  `sectionNumber` int(10) NOT NULL,
  `studentKSUID` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sectionstudents`
--

INSERT INTO `sectionstudents` (`sectionNumber`, `studentKSUID`) VALUES
(2, 442200333),
(2, 442200444),
(1, 442200111),
(1, 442200222),
(3, 442200111),
(3, 442200555),
(5, 442201234);

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `KSUID` int(10) NOT NULL,
  `firstName` varchar(20) NOT NULL,
  `lastName` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`KSUID`, `firstName`, `lastName`) VALUES
(442200111, 'Jana', 'Mohammed'),
(442200222, 'Deema', 'Fahad'),
(442200333, 'Nora', 'Abdulrahman'),
(442200444, 'Raghad', 'Hamad'),
(442200555, 'Layan', 'Saleh'),
(442200666, 'Fatima', 'Bandar'),
(442200777, 'Nawal', 'Omar'),
(442200888, 'Fay', 'Khalid'),
(442201234, 'Lama', 'Ali'),
(442204321, 'Lulu', 'Ahmad');

-- --------------------------------------------------------

--
-- Table structure for table `studentaccount`
--

CREATE TABLE `studentaccount` (
  `id` int(10) NOT NULL,
  `password` varchar(100) NOT NULL,
  `KSUID` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `studentaccount`
--

INSERT INTO `studentaccount` (`id`, `password`, `KSUID`) VALUES
(1, '$2y$10$rx7SGaNPX6bhZ/0xBwUdQefwrAuYyoCbjedgNJ.zOqzrbnwDVnOWe', 442200111);

-- --------------------------------------------------------

--
-- Table structure for table `studentattendanceinrecord`
--

CREATE TABLE `studentattendanceinrecord` (
  `attendanceRecordID` int(10) NOT NULL,
  `studentKSUID` int(10) NOT NULL,
  `attendance` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `studentattendanceinrecord`
--

INSERT INTO `studentattendanceinrecord` (`attendanceRecordID`, `studentKSUID`, `attendance`) VALUES
(2, 442200333, 0),
(2, 442200444, 1),
(1, 442200111, 1),
(3, 442200111, 0);

-- --------------------------------------------------------

--
-- Table structure for table `uploadedexcuses`
--

CREATE TABLE `uploadedexcuses` (
  `id` int(10) NOT NULL,
  `studentAccountID` int(10) NOT NULL,
  `attendanceRecordID` int(10) NOT NULL,
  `absenceReason` varchar(300) NOT NULL,
  `uploadedExcuseFileName` varchar(40) NOT NULL,
  `decision` set('under consideration','approved','disapproved') NOT NULL DEFAULT 'under consideration'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `classattendancerecord`
--
ALTER TABLE `classattendancerecord`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sectionNumber` (`sectionNumber`);

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `instructor`
--
ALTER TABLE `instructor`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `section`
--
ALTER TABLE `section`
  ADD PRIMARY KEY (`sectionNumber`),
  ADD KEY `courseID` (`courseID`),
  ADD KEY `instructorID` (`instructorID`);

--
-- Indexes for table `sectionstudents`
--
ALTER TABLE `sectionstudents`
  ADD KEY `sectionNumber` (`sectionNumber`),
  ADD KEY `studentKSUID` (`studentKSUID`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`KSUID`);

--
-- Indexes for table `studentaccount`
--
ALTER TABLE `studentaccount`
  ADD PRIMARY KEY (`id`),
  ADD KEY `KSUID` (`KSUID`);

--
-- Indexes for table `studentattendanceinrecord`
--
ALTER TABLE `studentattendanceinrecord`
  ADD KEY `attendanceRecordID` (`attendanceRecordID`),
  ADD KEY `studentKSUID` (`studentKSUID`);

--
-- Indexes for table `uploadedexcuses`
--
ALTER TABLE `uploadedexcuses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `studentAccountID` (`studentAccountID`),
  ADD KEY `attendanceRecordID` (`attendanceRecordID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `classattendancerecord`
--
ALTER TABLE `classattendancerecord`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `instructor`
--
ALTER TABLE `instructor`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `studentaccount`
--
ALTER TABLE `studentaccount`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `uploadedexcuses`
--
ALTER TABLE `uploadedexcuses`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `classattendancerecord`
--
ALTER TABLE `classattendancerecord`
  ADD CONSTRAINT `classattendancerecord_ibfk_1` FOREIGN KEY (`sectionNumber`) REFERENCES `section` (`sectionNumber`) ON DELETE CASCADE;

--
-- Constraints for table `section`
--
ALTER TABLE `section`
  ADD CONSTRAINT `section_ibfk_1` FOREIGN KEY (`courseID`) REFERENCES `course` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `section_ibfk_2` FOREIGN KEY (`instructorID`) REFERENCES `instructor` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sectionstudents`
--
ALTER TABLE `sectionstudents`
  ADD CONSTRAINT `sectionstudents_ibfk_1` FOREIGN KEY (`sectionNumber`) REFERENCES `section` (`sectionNumber`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sectionstudents_ibfk_2` FOREIGN KEY (`studentKSUID`) REFERENCES `student` (`KSUID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `studentaccount`
--
ALTER TABLE `studentaccount`
  ADD CONSTRAINT `studentaccount_ibfk_1` FOREIGN KEY (`KSUID`) REFERENCES `student` (`KSUID`);

--
-- Constraints for table `studentattendanceinrecord`
--
ALTER TABLE `studentattendanceinrecord`
  ADD CONSTRAINT `studentattendanceinrecord_ibfk_1` FOREIGN KEY (`attendanceRecordID`) REFERENCES `classattendancerecord` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `studentattendanceinrecord_ibfk_2` FOREIGN KEY (`studentKSUID`) REFERENCES `student` (`KSUID`) ON DELETE CASCADE;

--
-- Constraints for table `uploadedexcuses`
--
ALTER TABLE `uploadedexcuses`
  ADD CONSTRAINT `uploadedexcuses_ibfk_1` FOREIGN KEY (`studentAccountID`) REFERENCES `studentaccount` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `uploadedexcuses_ibfk_2` FOREIGN KEY (`attendanceRecordID`) REFERENCES `classattendancerecord` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
