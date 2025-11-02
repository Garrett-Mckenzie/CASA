-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 02, 2025 at 01:44 AM
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
-- Database: `casadb`
--

-- --------------------------------------------------------
<<<<<<< HEAD

--
-- Table structure for table `associations`
--

CREATE TABLE `associations` (
  `eventID` int(11) NOT NULL,
  `donationID` int(11) NOT NULL,
  `donorID` int(11) NOT NULL,
  `emailID` int(11) NOT NULL,
  `filePath` varchar(256) NOT NULL
=======
--
-- Table structure for table `dbdiscussions`
--

DROP TABLE IF EXISTS `dbdiscussions`;
CREATE TABLE `dbdiscussions` (
  `author_id` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
>>>>>>> origin/jbyrne_dev
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dbevents`
--

CREATE TABLE `dbevents` (
  `id` int(11) NOT NULL,
  `name` text DEFAULT NULL,
  `goalAmount` decimal(12,2) DEFAULT NULL,
  `date` char(10) DEFAULT NULL,
  `startDate` char(10) DEFAULT NULL,
  `endDate` char(10) DEFAULT NULL,
  `startTime` char(5) DEFAULT '00:00',
  `endTime` char(5) DEFAULT '00:00',
  `description` text DEFAULT NULL,
  `completed` text DEFAULT NULL,
  `location` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dbevents`
--

INSERT INTO `dbevents` (`id`, `name`, `goalAmount`, `date`, `startDate`, `endDate`, `startTime`, `endTime`, `description`, `completed`, `location`) VALUES
(3, 'test', 1250.00, NULL, '2025-10-25', '2025-10-25', '00:00', '23:59', 'this is a test', '0', 'hcc'),
(4, 'test2', 10.00, NULL, '2025-10-26', '2025-10-26', '12:00', '17:00', 'test 2 for not requiring location', '0', ''),
(5, 'testOfViewEvents', 10000.00, NULL, '2025-10-30', '2026-01-31', '00:00', '23:59', 'this is a test of an ongoing event', '0', '');

-- --------------------------------------------------------

--
-- Table structure for table `dbpersons`
--

CREATE TABLE `dbpersons` (
  `id` varchar(256) NOT NULL,
  `name` text DEFAULT NULL,
  `password` text DEFAULT NULL,
  `accessLevel` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dbpersons`
--

INSERT INTO `dbpersons` (`id`, `name`, `password`, `accessLevel`) VALUES
('vmsroot', 'SUPER ADMIN', '$2y$10$.3p8xvmUqmxNztEzMJQRBesLDwdiRU3xnt/HOcJtsglwsbUk88VTO', 0);

-- --------------------------------------------------------

--
-- Table structure for table `donations`
--

CREATE TABLE `donations` (
  `amount` decimal(12,2) DEFAULT NULL,
  `id` int(11) NOT NULL,
  `reason` text DEFAULT NULL,
  `date` char(10) DEFAULT NULL,
  `fee` decimal(12,2) DEFAULT NULL,
  `thanked` int(11) DEFAULT NULL,
  `eventID` int(11) DEFAULT NULL,
  `donorID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donations`
--

INSERT INTO `donations` (`amount`, `id`, `reason`, `date`, `fee`, `thanked`, `eventID`, `donorID`) VALUES
(50.00, 1, '2014, 2015 and 2016 Community Give', '01/01/2015', 0.00, 0, NULL, 1),
(50.00, 2, 'Community Give; $100 in 2024 Giving Tuesday', '01/01/2016', 0.00, 0, NULL, 2),
(25.00, 3, 'Community Give: gave $25 in 2014, 2015 & 2016', '05/05/2015', 0.00, 0, NULL, 3),
(1100.00, 4, '$100 in 2014 Community Give; $1,100 in 2015; $1,000 in 2016 Community Give', '01/01/2015', 0.00, 0, NULL, 4),
(100.00, 5, 'In response to Community Give email from Janet; also $100 in 2015', '05/01/2014', 0.00, 0, NULL, 5),
(100.00, 6, 'Step Up for CASA Kids team -- 2024 Downtown Mile; CASA volunteer; $100 2024 Giving Tues.', '01/01/2024', 0.00, 0, NULL, 6),
(25.00, 7, 'Community Give in 2014 & 2015', '05/05/2015', 0.00, 0, NULL, 8),
(50.00, 8, 'Community Give: $50 in 2014; $30 in 2015; $25 in 2016; in 2020 gave $25 on Giving Tuesday', '01/01/2014', 0.00, 0, NULL, 9),
(NULL, 9, '2024 Downtown Mile donor', NULL, 0.00, 0, NULL, 10),
(50.00, 10, '$50 in 2020 In memory of Sally Rycroft; $250 during 2016 Community Give; $200 in 2021 Giving Tuesday; $100 in 2024 Giving Tues.', '03/07/2020', 0.00, 0, NULL, 11),
(25.00, 11, 'Community Give', '01/01/2014', 0.00, 0, NULL, 17),
(50.00, 12, 'Community Give', '01/01/2016', 0.00, 0, NULL, 18),
(NULL, 13, '2024 Downtown Mile donor', NULL, 0.00, 0, NULL, 19),
(1200.00, 14, '$225 in 2015; $1,000 in 2016 Community Give; $1,200 in 2017', '01/01/2017', 0.00, 0, NULL, 20),
(200.00, 15, 'Paypal Giving', '03/24/2016', 0.00, 0, NULL, 21),
(35.00, 16, 'Gave $40 during 2014 Community Give', '01/01/2015', 0.00, 0, NULL, 22),
(25.00, 17, '$250 in Jan. 2025', '01/01/2015', 0.00, 0, NULL, 23),
(50.00, 18, 'Community Give', '01/01/2014', 0.00, 0, NULL, 24),
(NULL, 19, '2024 Downtown Mile donor', NULL, 0.00, 0, NULL, 25),
(25.00, 20, '', '01/01/2015', 0.00, 0, NULL, 26),
(30.00, 21, 'Community Give', '01/01/2016', 0.00, 0, NULL, 27),
(25.00, 22, '', '11/28/2017', 0.00, 0, NULL, 28),
(25.00, 23, 'Community Give 2015: $25', '05/05/2015', 0.00, 0, NULL, 29),
(200.00, 24, 'Community Give', '01/01/2014', 0.00, 0, NULL, 32),
(0.00, 25, '2024 Downtown Mile donor', '01/01/2024', 0.00, 0, NULL, 33),
(50.00, 26, '$50 in 2015 & 2016 Community Give', '05/05/2015', 0.00, 0, NULL, 34),
(0.00, 27, 'Step Up for CASA Kids team -- 2024 Downtown Mile', '01/01/2024', 0.00, 0, NULL, 35),
(0.00, 28, '2024 Downtown Mile donor', '01/01/2024', 0.00, 0, NULL, 36),
(25.00, 29, 'Community Give', '01/01/2016', 0.00, 0, NULL, 37),
(2000.00, 30, 'gave 10K in May 2016; and $2,000 in 2017; $500 in 2016 Community Give', '12/05/2017', 0.00, 0, NULL, 38),
(50.00, 31, 'Community Give', '01/01/2016', 0.00, 0, NULL, 41),
(100.00, 32, '2023 Giving Tues; $100 2024 Giving Tues', '12/01/2023', 0.00, 0, NULL, 42),
(20.00, 33, '2023 Giving Tues', '12/01/2023', 0.00, 0, NULL, 45),
(25.00, 34, 'Community Give in 2014, 2015 & 2016', '05/05/2015', 0.00, 0, NULL, 46),
(40.00, 35, '', '01/01/2015', 0.00, 0, NULL, 50),
(100.00, 36, '$100 during 2015 and 2016 Community Give', '01/01/2015', 0.00, 0, NULL, 51),
(NULL, 37, '2024 Downtown Mile donor', NULL, 0.00, 0, NULL, 53),
(NULL, 38, '2024 Downtown Mile donor', NULL, 0.00, 0, NULL, 54),
(25.00, 39, 'Community Give: $525 in 2014, $650 in 2015 & $1,100 in 2016', '05/05/2015', 0.00, 0, NULL, 55),
(100.00, 40, 'I would like my donation to be listed in memory of Sally A. Rycroft', '02/13/2020', 0.00, 0, NULL, 56),
(400.00, 41, 'also gave $250 in 2015; $200 in 2023; $100 in 2024 GivTues', '02/04/2017', 0.00, 0, NULL, 57),
(NULL, 42, 'Step Up for CASA Kids team -- 2024 Downtown Mile; Board member; $50 for 2024 Giving Tues', NULL, 0.00, 0, NULL, 59),
(25.00, 43, 'Community Give: $25 in 2014; $75 in 2015; and $25 in 2016; 2024 Downtown Mile donor; $50 for 2024 Giving Tues', '05/05/2015', 0.00, 0, NULL, 61),
(2000.00, 44, '2023 EOY gift & 2024 EOY; 2024 Downtown Mile donor', '01/01/2023', 0.00, 0, NULL, 62),
(50.00, 45, 'In memory of Sally Rycroft', '02/08/2020', 0.00, 0, NULL, 65),
(NULL, 46, '2024 Downtown Mile donor', NULL, 0.00, 0, NULL, 66),
(NULL, 47, 'Step Up for CASA Kids team -- 2024 Downtown Mile', NULL, 0.00, 0, NULL, 67),
(NULL, 48, '2024 Downtown Mile donor', NULL, 0.00, 0, NULL, 68),
(NULL, 49, '2025 Downtown Mile donor', NULL, 0.00, 0, NULL, 69),
(NULL, 50, 'Step Up for CASA Kids team -- 2024 Downtown Mile', NULL, 0.00, 0, NULL, 70),
(200.00, 51, '$50 in 2014 Community Give; $200 in 2018; $500 in 2021', '12/17/2018', 0.00, 0, NULL, 71),
(250.00, 52, 'In memory of Sally Rycroft from Athanasiades Family', '02/13/2020', 0.00, 0, NULL, 77),
(100.00, 53, '', '01/01/2015', 0.00, 0, NULL, 79),
(NULL, 54, '2024 Downtown Mile donor', NULL, 0.00, 0, NULL, 81),
(350.00, 55, 'Bellydance event; also gave $500 in 2015; $2,050 in 2017; gave $2,500 in 2018; $1,600 in 2020; $250 2024 Giving Tues', '05/10/2014', 0.00, 0, NULL, 82),
(50.00, 56, '', '07/08/2018', 0.00, 0, NULL, 84),
(100.00, 57, 'In honor and with love for our amazing CASA Renee Cinalli, Community Give; $100 on 2024 Giving Tues', '01/01/2016', 0.00, 0, NULL, 87),
(NULL, 58, '2024 Downtown Mile donor & $50 at Giving Tuesday 2024', NULL, 0.00, 0, NULL, 90),
(NULL, 59, '2024 Downtown Mile donor and member of Step Up for CASA Kids team', NULL, 0.00, 0, NULL, 91),
(NULL, 60, '2024 Downtown Mile donor', NULL, 0.00, 0, NULL, 92),
(50.00, 61, 'Community Give', '01/01/2016', 0.00, 0, NULL, 93),
(NULL, 62, '2024 Downtown Mile donor', NULL, 0.00, 0, NULL, 95),
(50.00, 63, '2024 Downtown Mile donor and Giving Tuesday', '01/01/2024', 0.00, 0, NULL, 96),
(100.00, 64, 'Enclosed is a $100 donation in memory of Sally Rycroft. Thank you. $50 in 2014 In memory of Linda Pisenti; $100 in 2017; $100 in 2020 in memory of Sally Rycroft', '02/20/2020', 0.00, 0, NULL, 97),
(NULL, 65, '2024 Downtown Mile donor', NULL, 0.00, 0, NULL, 100),
(50.00, 66, '', '02/05/2017', 0.00, 0, NULL, 101),
(50.00, 67, 'Community Give', '01/01/2014', 0.00, 0, NULL, 102),
(NULL, 68, 'Step Up for CASA Kids team -- 2024 Downtown Mile', NULL, 0.00, 0, NULL, 103),
(NULL, 69, '2024 Downtown Mile donor', NULL, 0.00, 0, NULL, 104),
(20.00, 70, '', '01/01/2015', 0.00, 0, NULL, 105),
(250.00, 71, '2015 Community Give; $25 during 2014 Community Give; $100 in 2016 in honor of Linda Pisenti', '01/01/2015', 0.00, 0, NULL, 106),
(25.00, 72, 'Community Give: $25 in 2015 and 2016', '01/01/2016', 0.00, 0, NULL, 107),
(25.00, 73, 'Community Give: $25 in 2014, 2015 & 2016', '01/01/2014', 0.00, 0, NULL, 108),
(25.00, 74, '', '01/01/2015', 0.00, 0, NULL, 109),
(200.00, 75, 'Also $200 in 2024 Giving Tues', '01/01/2015', 0.00, 0, NULL, 112),
(100.00, 76, 'Community Give', '01/01/2016', 0.00, 0, NULL, 113),
(50.00, 77, 'Community Give: $150 in 2014; $200 in 2015; $150 in 2016', '05/05/2015', 0.00, 0, NULL, 114),
(NULL, 78, '2025 Downtown Mile donor', NULL, 0.00, 0, NULL, 115),
(200.00, 79, 'also gave $650 in 2017 & $150 in 2020 on Giving Tuesday; $250 in 2021 Giving Tuesday; & $200 on Giv Tues 2024', '11/27/2018', 0.00, 0, NULL, 116),
(200.00, 80, 'also gave $650 in 2017 & $150 in 2020 on Giving Tuesday; $250 in 2021 Giving Tuesday; & $200 on Giv Tues 2024', '11/27/2018', 0.00, 0, NULL, 119),
(2000.00, 81, '', '02/06/2017', 0.00, 0, NULL, 121),
(70.00, 82, 'Community Give: $25 in 2014 & 2015; $70 in 2016; $100 in 2018; $100 in 2020', '01/01/2016', 0.00, 0, NULL, 122),
(50.00, 83, 'Community Give: $250 in 2015; $50 in 2016', '05/05/2015', 0.00, 0, NULL, 123),
(100.00, 84, '', '01/01/2015', 0.00, 0, NULL, 124),
(25.00, 85, '', '02/12/2020', 0.00, 0, NULL, 126),
(25.00, 86, '', '01/01/2015', 0.00, 0, NULL, 127),
(NULL, 87, '2024 Downtown Mile donor', NULL, 0.00, 0, NULL, 128),
(50.00, 88, 'Community Give', '01/01/2014', 0.00, 0, NULL, 129),
(NULL, 89, '', NULL, 0.00, 0, NULL, 130),
(1350.00, 90, '$1,350 in 2017 & $250 in 2020 in honor of Sally Rycroft', '01/01/2017', 0.00, 0, NULL, 131),
(0.00, 91, 'Step Up for CASA Kids team -- 2024 Downtown Mile', '01/01/2024', 0.00, 0, NULL, 132),
(25.00, 92, 'Community Give', '01/01/2016', 0.00, 0, NULL, 133),
(50.00, 93, '', '07/14/2018', 0.00, 0, NULL, 134),
(25.00, 94, 'Community Give; $51.52 during 2024 Giving Tuesday', '05/05/2015', 0.00, 0, NULL, 135),
(NULL, 95, 'Sponsored Downtown Mile 2024 with law partners at Strentz, Greene & Coleman LPC', '01/01/2024', 0.00, 0, NULL, 136),
(100.00, 96, 'Community Give: $50 in 2015 & $100 in 2016', '01/01/2016', 0.00, 0, NULL, 137),
(100.00, 97, 'also gave $25 in 2015; $50 in 2024 Giving Tues.', '11/28/2017', 0.00, 0, NULL, 138),
(10.00, 98, 'Community Give', '01/01/2016', 0.00, 0, NULL, 139),
(100.00, 99, 'Community Give; $225 in 2024 Giving Tuesday', '01/01/2016', 0.00, 0, NULL, 140),
(35.00, 100, 'Community Give', '01/01/2014', 0.00, 0, NULL, 143),
(25.00, 101, 'Community Give; gave $25 in 2018', '01/01/2016', 0.00, 0, NULL, 144),
(50.00, 102, 'Community Give: $25 in 2014; $100 in 2015; $50 in 2016', '05/05/2015', 0.00, 0, NULL, 145),
(25.00, 103, 'Community Give: $25 in 2014; $90 in 2015', '05/05/2015', 0.00, 0, NULL, 146),
(40.00, 104, 'Donated $40 in honor of Sara and other deserving kids in 2016 Community Give; and $25 in 2015', '01/01/2016', 0.00, 0, NULL, 147),
(154.00, 105, '2024 Downtown Mile donor & Giving Tuesday', NULL, 0.00, 0, NULL, 148),
(25.00, 106, '', '01/01/2015', 0.00, 0, NULL, 149),
(NULL, 107, '2024 Downtown Mile donor', NULL, 0.00, 0, NULL, 150),
(25.00, 108, 'Community Give', '01/01/2014', 0.00, 0, NULL, 151),
(NULL, 109, '2024 Downtown Mile donor', NULL, 0.00, 0, NULL, 152),
(50.00, 110, 'Community Give; $50 for 2024 Giving Tues.', '01/01/2016', 0.00, 0, NULL, 153),
(25.00, 111, 'Community Give', '01/01/2016', 0.00, 0, NULL, 154),
(20.00, 112, '2023 Giving Tues; is a friend of volunteer Felicia Agnew', '12/01/2023', 0.00, 0, NULL, 155),
(10.00, 113, '', '01/01/2015', 0.00, 0, NULL, 156),
(NULL, 114, '2024 Downtown Mile donor', NULL, 0.00, 0, NULL, 157),
(100.00, 115, '7/18/2018 and 2023 Giving Tues', '07/18/2018', 0.00, 0, NULL, 158),
(50.00, 116, 'Donation in honor of Sally Rycroft', '02/26/2020', 0.00, 0, NULL, 159),
(100.00, 117, 'Please accept this gift in memory of Sally Rycroft.', '02/18/2020', 0.00, 0, NULL, 162),
(25.00, 118, 'Please accept this donation to honor the memory of my cousin\'s wife, Sally Rycroft. Thank you.', '02/18/2020', 0.00, 0, NULL, 163),
(100.00, 119, 'In memory of Sally Rycroft (Judy\'s sister)', '03/05/2020', 0.00, 0, NULL, 164),
(50.00, 120, 'In memory of Linda Pisenti', '04/01/2014', 0.00, 0, NULL, 167),
(150.00, 121, 'Teresa Bullock is in a Women\'s Missions Group there.', '11/21/2018', 0.00, 0, NULL, 170),
(100.00, 122, 'In memory of Linda Pisenti', '04/01/2014', 0.00, 0, NULL, 171),
(25.00, 123, 'In memory of Linda Pisenti', '04/01/2014', 0.00, 0, NULL, 172),
(20.00, 124, 'Please accept this memorial contribution in memory of my friend, Sally Rycroft. She was such a sweet, caring friend and will be missed.', '02/24/2020', 0.00, 0, NULL, 173),
(50.00, 125, 'After Spotsylvania public hearing', '03/26/2015', 0.00, 0, NULL, 175),
(468.00, 126, 'United Way office campaign', '01/01/2018', 0.00, 0, NULL, 178),
(10000.00, 127, '', '06/08/2021', 0.00, 0, NULL, 179),
(10.00, 128, 'Please accept this gift in memory of Sally Rycroft.', '02/20/2020', 0.00, 0, NULL, 189),
(50.00, 129, 'Enclosed please find a gift in memory of my friend Sally Rycroft. Thank you for all your good work. $50 in 2014 In memory of Linda Pisenti; $100 in 2017; $100 in 2020 in memory of Sally Rycroft', '02/24/2020', 0.00, 0, NULL, 191),
(500.00, 130, 'United Way office campaign', '01/01/2017', 0.00, 0, NULL, 193),
(50.00, 131, '', '01/01/2020', 0.00, 0, NULL, 194),
(1.00, 132, 'Tithing $1 of his allowance since 2022', '01/01/2022', 0.00, 0, NULL, 195),
(1500.00, 133, '', '01/01/2023', 0.00, 0, NULL, 196),
(1170.00, 134, 'Xmas tree challenge + donation; $1,250 in 2019 Xmas Tree Challenge', '01/01/2020', 0.00, 0, NULL, 197),
(20.00, 135, 'Bellydance event', '05/10/2014', 0.00, 0, NULL, 198),
(20.00, 136, 'Bellydance event', '05/10/2014', 0.00, 0, NULL, 199),
(80.00, 137, 'Bellydance event', '05/10/2014', 0.00, 0, NULL, 200),
(25.00, 138, 'Bellydance event', '05/10/2014', 0.00, 0, NULL, 204),
(15.00, 139, 'Donation in memory of Sally Rycroft. Please inform Prof. Rycroft.', '02/18/2020', 0.00, 0, NULL, 205),
(100.00, 140, 'In memory of Sally Rycroft - to help continue your important work', '06/15/2020', 0.00, 0, NULL, 206),
(100.00, 141, 'In honor of Sally Rycroft', '02/18/2020', 0.00, 0, NULL, 207),
(100.00, 142, 'Giving Tuesday', '01/01/2020', 0.00, 0, NULL, 210),
(50.00, 143, 'Please accept this donation in memory of Sally A. Rycroft. Thank you so much', '02/18/2020', 0.00, 0, NULL, 213),
(100.00, 144, 'In memory of Linda Pisenti', '04/01/2014', 0.00, 0, NULL, 216),
(2000.00, 145, '', '06/01/2018', 0.00, 0, NULL, 217),
(50.00, 146, 'In memory of Linda Pisenti', '04/01/2014', 0.00, 0, NULL, 218),
(100.00, 147, 'Please accept this donation in memory of Sally A. Rycroft, Robert Rycroft\'s wife.', '02/18/2020', 0.00, 0, NULL, 219),
(100.00, 148, 'Random end-of-year gift, though Jill said they may have given before', '12/31/2015', 0.00, 0, NULL, 221),
(100.00, 149, 'End-of-year gift from former volunteer', '12/31/2015', 0.00, 0, NULL, 224),
(200.00, 150, 'Giving Tuesday', '01/01/2021', 0.00, 0, NULL, 225),
(50.00, 151, 'In memory of Sally Rycorft', '05/01/2020', 0.00, 0, NULL, 228),
(30.00, 152, 'In memory of Sally Rycroft', '02/26/2020', 0.00, 0, NULL, 229),
(50.00, 153, 'In memory of Sally Rycroft', '04/15/2020', 0.00, 0, NULL, 232),
(NULL, 154, 'Fawn Lake Country Club', NULL, 0.00, 0, NULL, 235),
(50.00, 155, 'After Spotsylvania public hearing', '03/26/2015', 0.00, 0, NULL, 236),
(500.00, 156, 'In memory of Linda Pisenti', '04/01/2014', 0.00, 0, NULL, 239),
(6139.00, 157, 'Smiles for Life program', '01/01/2019', 0.00, 0, NULL, 242),
(50.00, 158, 'In memory of Linda Pisenti', '04/01/2014', 0.00, 0, NULL, 243),
(50.00, 159, '2016 & 2017 Community Give; $102.53 for 2024 Giving Tues', '01/03/2017', 0.00, 0, NULL, 246),
(100.00, 160, 'In remembrance of Sally Rycroft, please acept our donation for $100 (enclosed). Please acknowledge receipt to Ms. Rycroft\'s family.', '02/18/2020', 0.00, 0, NULL, 249),
(250.00, 161, 'Enclosed find check for your charitable work in memory of Sally Rycroft.', '02/18/2020', 0.00, 0, NULL, 250),
(200.00, 162, 'Also $250 EOY gift for 2024', '01/01/2020', 0.00, 0, NULL, 253),
(200.00, 163, '', '01/01/2020', 0.00, 0, NULL, 254),
(25.00, 164, '2024 Giving Tues.', '12/01/2024', 0.00, 0, NULL, 255),
(200.00, 165, '2024 Giving Tues.', '12/01/2024', 0.00, 0, NULL, 256),
(50.00, 166, '2024 Giving Tues. In honor of Cookie Gross, Edie\'s mom and a former teacher of her kids', '12/01/2024', 0.00, 0, NULL, 257),
(25.00, 167, '2024 Giving Tues.', '12/01/2024', 0.00, 0, NULL, 258),
(25.00, 168, '2024 Giving Tues.', '12/01/2024', 0.00, 0, NULL, 259),
(51.52, 169, '2024 Giving Tues.', '12/01/2024', 0.00, 0, NULL, 260),
(18.00, 170, '2024 Giving Tues.', '12/01/2024', 0.00, 0, NULL, 261),
(25.00, 171, '2024 Giving Tues.', '12/01/2024', 0.00, 0, NULL, 262),
(50.00, 172, '2024 Giving Tues.', '12/01/2024', 0.00, 0, NULL, 263),
(50.00, 173, '2024 Giving Tues.', '12/01/2024', 0.00, 0, NULL, 264),
(15.80, 174, '2024 Giving Tues.', '12/01/2024', 0.00, 0, NULL, 265),
(25.00, 175, '2024 Giving Tues. Saw a post on Facebook, maybe from Cookie Gross (Edie\'s mom), and donated.', '12/01/2024', 0.00, 0, NULL, 268),
(25.00, 176, '2024 Giving Tues. Fredericksburg CPS worker', '12/01/2024', 0.00, 0, NULL, 269),
(25.00, 177, '2024 Giving Tues. CASA board member and former volunteers', '12/01/2024', 0.00, 0, NULL, 270),
(50.00, 178, '2024 Giving Tues. Aunt of CASA director, Edie Evans', '12/01/2024', 0.00, 0, NULL, 271),
(50.00, 179, '2024 Giving Tues.', '12/01/2024', 0.00, 0, NULL, 272),
(200.00, 180, '2024 Giving Tues. Board member', '12/01/2024', 0.00, 0, NULL, 273),
(100.00, 181, 'In memory of Bobby Anderson', '06/05/2025', 0.00, 0, NULL, 275),
(50.00, 182, 'In memory of Bobby Anderson', '06/09/2025', 0.00, 0, NULL, 278),
(100.00, 183, 'In memory of Bobby Anderson', '06/11/2025', 0.00, 0, NULL, 281),
(50.00, 184, 'In memory of Bobby Anderson', '06/27/2025', 0.00, 0, NULL, 284),
(5.00, 185, 'test', '10/28/2025', 0.00, 1, 3, 4),
(15.00, 186, 'test', '10/28/2025', 0.00, 1, 4, 4),
(15.00, 187, 'test', '10/28/2025', 0.00, 1, 4, 4),
(1000.00, 188, 'test', '10/29/2025', 0.00, 1, 3, 288),
(1000.00, 189, 'test', '10/29/2025', 0.00, 1, 3, 288);

-- --------------------------------------------------------

--
-- Table structure for table `donors`
--

CREATE TABLE `donors` (
  `id` int(11) NOT NULL,
  `first` varchar(30) DEFAULT NULL,
  `last` varchar(30) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `zip` varchar(30) DEFAULT NULL,
  `city` varchar(30) DEFAULT NULL,
  `state` varchar(30) DEFAULT NULL,
  `street` varchar(100) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `gender` varchar(30) DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donors`
--

INSERT INTO `donors` (`id`, `first`, `last`, `email`, `zip`, `city`, `state`, `street`, `phone`, `gender`, `notes`) VALUES
(1, 'Phillip', 'Jenkins', '1philjenkins@gmail.com', '22405', 'Fredericksburg', 'VA', '54 Hamlin Drive', NULL, NULL, 'former colleagues of Janet and Edie'),
(2, 'Jeri', 'Phillips', '4jphillips@comcast.net', '22551', 'Spotsylvania', 'VA', '8432 Battle Park Drive', NULL, NULL, 'CASA volunteer'),
(3, 'Christine', 'Repp', 'abcrepp@yahoo.com', '22401', 'Fredericksburg', 'VA', '1407 Washington Ave', NULL, NULL, 'Christine & Brad Repp established the Repp Family Fund @ Comm Foundation; Brad is CEO at Dynovis & Chris is Comms director'),
(4, 'Adam', 'Fried', 'afried@atlanticbuilders.com', '22401', 'Fredericksburg', 'VA', '425 William Street', NULL, NULL, 'Janet\'s cousin'),
(5, 'Maha', 'Alattar', 'alattarm14@gmail.com', '22407', 'Fredericksburg', 'VA', NULL, NULL, NULL, 'doctor'),
(6, 'Angela', 'Glidden', 'angiecasa135@gmail.com', '22554', 'Stafford', 'VA', '603 Hatchers Run Court', '814-241-0219', NULL, 'CASA volunteer'),
(7, 'Angela', 'Glidden', 'aroubison1@gmail.com', '22554', 'Stafford', 'VA', '603 Hatchers Run Court', '814-241-0219', NULL, 'CASA volunteer'),
(8, 'Amy', 'Faulkner-Hart', 'arfaulkner@hotmail.com', '22401', 'Fredericksburg', 'VA', '923 Marye St.', NULL, NULL, 'finance manager at CRRL'),
(9, 'Amy', 'Ridderhof', 'aridderhof@aol.com', '22401', 'Fredericksburg', 'VA', '226 Princess Anne St.', NULL, NULL, 'healthcare navigator @ Micah'),
(10, 'Brian', 'Pessolano', 'bapess@gmail.com', '22407', 'Fredericksburg', 'VA', '1504 Candlewood Street', '540-834-9223', NULL, NULL),
(11, 'Barbara', 'Miller-Richards', 'barbaramr6338@gmail.com', '22401', 'Fredericksburg', 'VA', '1412 Prince Edward Street', NULL, NULL, 'board member'),
(12, 'Barbara', 'Miller', 'barbaramr6338@gmail.com', '22401', 'Fredericksburg', 'VA', '1412 Prince Edward Street', NULL, NULL, 'board member'),
(13, 'Barbara', 'Richards', 'barbaramr6338@gmail.com', '22401', 'Fredericksburg', 'VA', '1412 Prince Edward Street', NULL, NULL, 'board member'),
(14, 'Guy', 'Miller-Richards', 'barbaramr6338@gmail.com', '22401', 'Fredericksburg', 'VA', '1412 Prince Edward Street', NULL, NULL, 'board member'),
(15, 'Guy', 'Miller', 'barbaramr6338@gmail.com', '22401', 'Fredericksburg', 'VA', '1412 Prince Edward Street', NULL, NULL, 'board member'),
(16, 'Guy', 'Richards', 'barbaramr6338@gmail.com', '22401', 'Fredericksburg', 'VA', '1412 Prince Edward Street', NULL, NULL, 'board member'),
(17, 'Tina', 'Glass', 'beachbeans@gmail.com', '22401', 'Fredericksburg', 'VA', '205 Hillcrest Drive', NULL, NULL, NULL),
(18, 'Betsy', 'Glassie', 'Bglassie@ail.com', '22401', 'Fredericksburg', 'VA', '508 George St', NULL, NULL, 'local artist'),
(19, 'Lesa', 'Aylor', 'bnldnj@aol.com', '22401', 'Fredericksburg', 'VA', '822 Kenmore Avenue', NULL, NULL, NULL),
(20, 'Bobby', 'Anderson', 'bobbydeeanderson@yahoo.com', '22406', 'Fredericksburg', 'VA', '32 Aspen Hill Drive', NULL, NULL, 'Board member'),
(21, 'William', 'Turner', 'bturner23@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(22, 'Cat', 'Paccasassi', 'c.paccasassi@gmail.com', '22401', 'Fredericksburg', 'VA', '1220 Rowe St.', NULL, NULL, 'volunteers with Green Thumb growers'),
(23, 'Michael', 'Camber', 'camberm@gmail.com', '22401', 'Fredericksburg', 'VA', '913 Marye Street', NULL, NULL, 'Edie\'s friend'),
(24, 'Chad', 'Carter', 'ccarter@crrl.org', '22401', 'Fredericksburg', 'VA', NULL, NULL, NULL, NULL),
(25, 'Carolyn', 'Helfrich', 'chelfrich@verizon.net', '22401', 'Fredericksburg', 'VA', '828 Marye Street', NULL, NULL, NULL),
(26, 'Chrissy', 'McDermott', 'chrissy@coolbreeze.com', '22401', 'Fredericksburg', 'VA', '213 Caroline St', NULL, NULL, 'wife of Dr. Michael McDermott'),
(27, 'Cindy', 'Guerrero', 'cindyg62@comcast.net', '22508', 'Locust Grove', 'VA', '35326 Quail Meadow Lane', NULL, NULL, NULL),
(28, 'Colleen', 'Good', 'cpgood9@gmail.com', '23235', 'Richmond', 'VA', '8314 Charlise Rd.', NULL, NULL, NULL),
(29, 'Dolores \"DD\"', 'Lecky', 'd@lecky.org', '22407', 'Fredericksburg', 'VA', '11716 Eisenhower Lane', NULL, NULL, NULL),
(30, 'DD', 'Lecky', 'd@lecky.org', '22407', 'Fredericksburg', 'VA', '11716 Eisenhower Lane', NULL, NULL, NULL),
(31, 'Dolores', 'Lecky', 'd@lecky.org', '22407', 'Fredericksburg', 'VA', '11716 Eisenhower Lane', NULL, NULL, NULL),
(32, 'Danny', 'Fields', 'danfields65@verizon.net', '22403', 'Fredericksburg', 'VA', NULL, NULL, NULL, 'lawyer'),
(33, 'Daniel', 'Long', 'danielryan615@gmail.com', '22554', 'Stafford', 'VA', '160 Paynes Lane', '540-621-7880', NULL, NULL),
(34, 'Delise', 'Dickard', 'delisebd@msn.com', '22401', 'Fredericksburg', 'VA', '207 Amelia Street ', NULL, NULL, 'former board member'),
(35, 'Denise', 'Freeman', 'deniselynnfreeman@gmail.com', '22736', 'Richardsville', 'VA', '29603 Happy Hollow Lane', '401-527-2347', NULL, 'owner of Fleet Feet'),
(36, 'Deborah', 'Cook', 'dlcook4@comcast.net', '22554', 'Stafford', 'VA', '120 Oaklawn Road, Suite 107', '540-841-3481', NULL, NULL),
(37, 'Dennis', 'Keffer', 'dlkeffer@verizon.net', '22408', 'Fredericksburg', 'VA', '10823 Stacy Run', NULL, NULL, 'former board member'),
(38, 'Mike & Pat', 'Stevens', 'drmste@aol.com', '22405', 'Fredericksburg', 'VA', '103 Hampton Drive', NULL, NULL, NULL),
(39, 'Mike', 'Stevens', 'drmste@aol.com', '22405', 'Fredericksburg', 'VA', '103 Hampton Drive', NULL, NULL, NULL),
(40, 'Pat', 'Stevens', 'drmste@aol.com', '22405', 'Fredericksburg', 'VA', '103 Hampton Drive', NULL, NULL, NULL),
(41, 'Elizabeth', 'Conn', 'esconn657@gmail.com', '22405', 'Fredericksburg', 'VA', '2019 Sierra Drive', NULL, NULL, NULL),
(42, 'Elizabeth', 'Brooks/Liz Freeman', 'esrrb@outlook.com', '22554', 'Stafford', 'VA', '11 Old Mineral Road', NULL, NULL, 'CASA volunteer'),
(43, 'Elizabeth', 'Brooks', 'esrrb@outlook.com', '22554', 'Stafford', 'VA', '11 Old Mineral Road', NULL, NULL, 'CASA volunteer'),
(44, 'Liz', 'Freeman', 'esrrb@outlook.com', '22554', 'Stafford', 'VA', '11 Old Mineral Road', NULL, NULL, 'CASA volunteer'),
(45, 'Becky', 'Farris', 'Farrisra2@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, 'is a friend of volunteer Felicia Agnew'),
(46, 'Edythe \"Edie\" & Kirk', 'Evans', 'gatoredie@yahoo.com', '22401', 'Fredericksburg', 'VA', '17 Banbury Court', NULL, NULL, 'CASA staff'),
(47, 'Edythe', 'Evans', 'gatoredie@yahoo.com', '22401', 'Fredericksburg', 'VA', '17 Banbury Court', NULL, NULL, 'CASA staff'),
(48, 'Edie', 'Evans', 'gatoredie@yahoo.com', '22401', 'Fredericksburg', 'VA', '17 Banbury Court', NULL, NULL, 'CASA staff'),
(49, 'Kirk', 'Evans', 'gatoredie@yahoo.com', '22401', 'Fredericksburg', 'VA', '17 Banbury Court', NULL, NULL, 'CASA staff'),
(50, 'Beth', 'McClain', 'gonedownunder@yahoo.com', '22401', 'Fredericksburg', 'VA', '620 Stuart St.', NULL, NULL, 'farm manager at Downtown Greens'),
(51, 'Bonita (Bonnie)', 'Darnell', 'grandmabonnied6@yahoo.com', '22508', 'Locust Grove', 'VA', '222 Musket Lane', NULL, NULL, NULL),
(52, 'Bonnie', 'Darnell', 'grandmabonnied6@yahoo.com', '22508', 'Locust Grove', 'VA', '222 Musket Lane', NULL, NULL, NULL),
(53, 'Grant', 'Smith', 'grantcs2580@gmail.com', '22401', 'Fredericksburg', 'VA', '102 Hanover Street', '720-245-5607', NULL, NULL),
(54, 'Hava', 'Marneweck', 'hava.marneweck@gmail.com', '22406', 'Fredericksburg', 'VA', '952 Ramoth Church Road', NULL, NULL, NULL),
(55, 'Mari', 'Kelly', 'Homesbymkelly@gmail.com', '22401', 'Fredericksburg', 'VA', '1303 Caroline street', NULL, NULL, 'Long & Foster Realty'),
(56, 'Ilana', 'Boivie', 'iboivie@yahoo.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(57, 'Admiral Julie', 'Treanor', 'j.treanor@hotmail.com', '23507', 'Norfolk', 'VA', '424 Fairfax Ave', NULL, NULL, 'former classmate of Janet Watkins'),
(58, 'Julie', 'Treanor', 'j.treanor@hotmail.com', '23507', 'Norfolk', 'VA', '424 Fairfax Ave', NULL, NULL, 'former classmate of Janet Watkins'),
(59, 'Jane', 'McDonald', 'janemcd56@yahoo.com', NULL, NULL, NULL, NULL, NULL, NULL, 'current CASA Board member; former RACSB director'),
(60, 'Jane', 'McDonald', 'jane.yaun@yahoo.com', NULL, NULL, NULL, NULL, NULL, NULL, 'current CASA Board member; former RACSB director'),
(61, 'Janet', 'Watkins', 'janmarshkins@yahoo.com', '22401', 'Fredericksburg', 'VA', '1206 Walker Drive', '540-842-6566', NULL, 'former Exec Director of CASA'),
(62, 'John', 'Bosserman', 'jbosserman@sigmacorps.com; john.bosserman@gmail.com', '22554', 'Stafford', 'VA', '3710 Aquia Drive', NULL, NULL, 'Marine Corps vet and CEO of Sigma Corps Solutions contractor'),
(63, 'John', 'Bosserman', 'jbosserman@sigmacorps.com', '22554', 'Stafford', 'VA', '3710 Aquia Drive', NULL, NULL, 'Marine Corps vet and CEO of Sigma Corps Solutions contractor'),
(64, 'John', 'Bosserman', 'john.bosserman@gmail.com', '22554', 'Stafford', 'VA', '3710 Aquia Drive', NULL, NULL, 'Marine Corps vet and CEO of Sigma Corps Solutions contractor'),
(65, 'Joseph', 'Di Bella', 'jdibella@umw.edu', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(66, 'Joann', 'Farris', 'jfarris@iu.edu', '46142', 'Greenwood', 'IN', '489 Savannah Drive', '317-478-5622', NULL, NULL),
(67, 'John', 'Mayer', 'jhnmr86@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(68, 'Joseph', 'Vines', 'jkv1229@cox.net', '22405', 'Fredericksburg', 'VA', '101 Carriage Hill Drive', '540-372-1457', NULL, NULL),
(69, 'Jacqueline', 'Burns', 'jlhburns@gmail.com', '22406', 'Fredericksburg', 'VA', '21 Wood Duck Place', '919-452-8002', NULL, NULL),
(70, 'John', 'Calabrese', 'johnvincenzocalabrese@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(71, 'Jane', 'Kolakowski', 'jskola9@verizon.net; prkola1@verizon.net', '53593-8039', 'Verona', 'WI', '9001 Hawks Reserve Lane, Unit 203', NULL, NULL, 'former CASA volunteer'),
(72, 'Jane', 'Kolakowski', 'jskola9@verizon.net', '53593-8039', 'Verona', 'WI', '9001 Hawks Reserve Lane, Unit 203', NULL, NULL, 'former CASA volunteer'),
(73, 'Jane', 'Kolakowski', 'prkola1@verizon.net', '53593-8039', 'Verona', 'WI', '9001 Hawks Reserve Lane, Unit 203', NULL, NULL, 'former CASA volunteer'),
(74, 'Pete', 'Kolakowski', 'jskola9@verizon.net; prkola1@verizon.net', '53593-8039', 'Verona', 'WI', '9001 Hawks Reserve Lane, Unit 203', NULL, NULL, 'former CASA volunteer'),
(75, 'Pete', 'Kolakowski', 'jskola9@verizon.net', '53593-8039', 'Verona', 'WI', '9001 Hawks Reserve Lane, Unit 203', NULL, NULL, 'former CASA volunteer'),
(76, 'Pete', 'Kolakowski', 'prkola1@verizon.net', '53593-8039', 'Verona', 'WI', '9001 Hawks Reserve Lane, Unit 203', NULL, NULL, 'former CASA volunteer'),
(77, 'Karen', 'Athanasiades', 'karena81@aol.com', NULL, NULL, NULL, NULL, NULL, NULL, 'Sally Rycroft\'s sister'),
(78, 'David', 'Athanasiades', 'karena81@aol.com', NULL, NULL, NULL, NULL, NULL, NULL, 'Sally Rycroft\'s sister'),
(79, 'Cornelius', 'Walsh', NULL, '22401', 'Fredericksburg', 'VA', '1408 Prince Edward St', NULL, NULL, 'neighbors of Barbara Miller-Richards'),
(80, 'Katherine', 'Walsh', NULL, '22401', 'Fredericksburg', 'VA', '1408 Prince Edward St', NULL, NULL, 'neighbors of Barbara Miller-Richards'),
(81, 'Kathryn', 'Waldron', 'kathryn@kauinc.com', '22408', 'Fredericksburg', 'VA', '4208 Rosemont Lane', '703-898-2010', NULL, NULL),
(82, 'Katrina', 'Masterson', 'katrinalm@comcast.net', '22554', 'Stafford', 'VA', '1 Lakewind Lane', NULL, NULL, NULL),
(83, 'Bud', 'Masterson', 'katrinalm@comcast.net', '22554', 'Stafford', 'VA', '1 Lakewind Lane', NULL, NULL, NULL),
(84, 'Kelley', 'Helmstutler DiDio', 'kelley.didio@uvm.edu', NULL, NULL, NULL, NULL, NULL, NULL, 'Janet\'s BFF'),
(85, 'Kelley', 'DiDio', 'kelley.didio@uvm.edu', NULL, NULL, NULL, NULL, NULL, NULL, 'Janet\'s BFF'),
(86, 'Kelley', 'Helmstutler', 'kelley.didio@uvm.edu', NULL, NULL, NULL, NULL, NULL, NULL, 'Janet\'s BFF'),
(87, 'Kelly & Sam', 'Carniol', 'Kellycarniol@comcast.net', '22408', 'Fredericksburg', 'VA', '10829 Samantha Place', NULL, NULL, 'Adoptive parents and fans of their kids\' CASA, Renee Cinalli.'),
(88, 'Sam', 'Carniol', 'Kellycarniol@comcast.net', '22408', 'Fredericksburg', 'VA', '10829 Samantha Place', NULL, NULL, 'Adoptive parents and fans of their kids\' CASA, Renee Cinalli.'),
(89, 'Kelly', 'Carniol', 'Kellycarniol@comcast.net', '22408', 'Fredericksburg', 'VA', '10829 Samantha Place', NULL, NULL, 'Adoptive parents and fans of their kids\' CASA, Renee Cinalli.'),
(90, 'Kelly', 'Morones', 'kellymorones@gmail.com', '22407', 'Fredericksburg', 'VA', '11301 Crown Court', '540-538-2217', NULL, 'Friend & former colleague of Edie\'s'),
(91, 'James', 'Kemp', 'kempjd623@yahoo.com', '22405', 'Fredericksburg', 'VA', '12 Jessica Rae Lane', '540-710-3166', NULL, NULL),
(92, 'Karen', 'Hawkins', 'khawkinsrdn@gmail.com', '23005', 'Ashland', 'VA', '11228 Hill Ridge Court', '804-922-2250', NULL, NULL),
(93, 'Sue', 'Kleman', 'Klemanfamily@verizon.net', '22401', 'Fredericksburg', 'VA', '429 Woodford St.', NULL, NULL, NULL),
(94, 'David', 'Kleman', 'Klemanfamily@verizon.net', '22401', 'Fredericksburg', 'VA', '429 Woodford St.', NULL, NULL, NULL),
(95, 'Karin', 'Beals', 'klowebeals@gmail.com', '22405', 'Fredericksburg', 'VA', '10 Fairfax Circle', '540-848-5036', NULL, NULL),
(96, 'Joan', 'Koriath', 'koriath@sbcglobal.net', '60089', 'Buffalo Grove', 'IL', '842 Dunhill Drive', NULL, NULL, 'Edie\'s distant cousin'),
(97, 'Karen & David', 'Johnson', 'ksjohnson422@msn.com', '22401', 'Fredericksburg', 'VA', '1104 Douglas St. ', NULL, NULL, NULL),
(98, 'Karen', 'Johnson', 'ksjohnson422@msn.com', '22401', 'Fredericksburg', 'VA', '1104 Douglas St. ', NULL, NULL, NULL),
(99, 'David', 'Johnson', 'ksjohnson422@msn.com', '22401', 'Fredericksburg', 'VA', '1104 Douglas St. ', NULL, NULL, NULL),
(100, 'Elizabeth', 'LeDoux', 'ledoux2@mac.com', '22401', 'Fredericksburg', 'VA', '1202 Wright Court', '703-338-3982', NULL, NULL),
(101, 'Lucy', 'Walker', 'lewalker2@msn.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(102, 'Liza', 'Fields', 'Lizafields@yahoo.com', '22401', 'Fredericksburg', 'VA', '809 Cornell St', NULL, NULL, 'C-ville area divorce & custody mediator'),
(103, 'Loretta', 'Englman', 'llenglman@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(104, 'LaTonya', 'Turner', 'lnm0303@yahoo.com', '22406', 'Fredericksburg', 'VA', '140 Everett Lane', '402-871-8203', NULL, NULL),
(105, 'Margaret', 'Johnson', 'maggielea03@gmail.com', '22401', 'Fredericksburg', 'VA', '607 Pelham St', NULL, NULL, 'Steve Watkins\' oldest Maggie.'),
(106, 'Mona', 'Albertine', 'Malbertine@cox.net', '22405', 'Fredericksburg', 'VA', '100 Federal Drive', NULL, NULL, 'owner of Jabberwocky books & board member at Va. Partners'),
(107, 'Meghan', 'Pcsolyar', 'mapcsolyar@gmail.com', '22405', 'Fredericksburg', 'VA', '799 Winterberry Drive', NULL, NULL, NULL),
(108, 'Maura', 'Schneider', 'maurawilson@gmail.com; and maurawilsonschneider@gmail.com', '22401', 'Fredericksburg', 'VA', '107 Caroline Street', NULL, NULL, 'Married to Matthew Schneider; writer and graphic artist'),
(109, 'Mary Carter', 'Frackelton', 'MCFrack@aol.com', '22401', 'Fredericksburg', 'VA', NULL, NULL, NULL, 'former educator, established a scholarship thru Comm Foundation; managing partner at Frackelton Block Company'),
(110, 'Mary', 'Frackelton', 'MCFrack@aol.com', '22401', 'Fredericksburg', 'VA', NULL, NULL, NULL, 'former educator, established a scholarship thru Comm Foundation; managing partner at Frackelton Block Company'),
(111, 'Carter', 'Frackelton', 'MCFrack@aol.com', '22401', 'Fredericksburg', 'VA', NULL, NULL, NULL, 'former educator, established a scholarship thru Comm Foundation; managing partner at Frackelton Block Company'),
(112, 'Michele', 'Utt', 'Michele.Utt@gmail.com', '22405', 'Fredericksburg', 'VA', '304 Ingleside Drive', NULL, NULL, 'CASA volunteer'),
(113, 'Michelle', 'Purdy', 'michellepurdy@msn.com', '22408', 'Fredericksburg', 'VA', '10821 Stacy Run', NULL, NULL, NULL),
(114, 'Emily', 'Simpson', 'Mlecatherine@gmail.com', '22401', 'Fredericksburg', 'VA', '805 Caroline st', NULL, NULL, 'Jan\'s friend'),
(115, 'Morris', 'Mochel', 'Mochelmorris@vaumc.org', '22401', 'Fredericksburg', 'VA', '701 Cobblestone Blvd., Unit 101', '202-431-3802', NULL, NULL),
(116, 'Laura Moyer & Jim', 'Hall', 'moyer0@gmail.com', '22401', 'Fredericksburg', 'VA', '1605 Sunken Rd.', NULL, NULL, 'former colleagues of Janet and Edie'),
(117, 'Jim', 'Hall', 'moyer0@gmail.com', '22401', 'Fredericksburg', 'VA', '1605 Sunken Rd.', NULL, NULL, 'former colleagues of Janet and Edie'),
(118, 'Laura', 'Moyer', 'moyer0@gmail.com', '22401', 'Fredericksburg', 'VA', '1605 Sunken Rd.', NULL, NULL, 'former colleagues of Janet and Edie'),
(119, 'Laura Moyer & Jim', 'Moyer', 'moyer0@gmail.com', '22401', 'Fredericksburg', 'VA', '1605 Sunken Rd.', NULL, NULL, 'former colleague of Edie and Jan'),
(120, 'Jim', 'Moyer', 'moyer0@gmail.com', '22401', 'Fredericksburg', 'VA', '1605 Sunken Rd.', NULL, NULL, 'former colleague of Edie and Jan'),
(121, 'Maureen', 'Kennedy', 'mtkennedy@hotmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(122, 'Nancy', 'Krause', 'nancykrause98@gmail.com', '22553', 'Spotsylvania', 'VA', '9800 San Mar Place', NULL, NULL, NULL),
(123, 'Nancy', 'Pcsolyar', 'napcprn@gmail.com', '22405', 'Fredericksburg', 'VA', '799 Winterberry Drive', NULL, NULL, 'board member'),
(124, 'Nancy', 'Hicks', 'njc47@aol.com', '22401', 'Fredericksburg', 'VA', '1107 Westwood Drive', NULL, NULL, 'have a fund at the Community Foundation'),
(125, 'Ron', 'Hicks', 'njc47@aol.com', '22401', 'Fredericksburg', 'VA', '1107 Westwood Drive', NULL, NULL, 'have a fund at the Community Foundation'),
(126, 'Nancy', 'Palmieri', 'nlpalmieri@aol.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(127, 'Donna', 'Boyd', 'ohtoread@aol.com', '22485', 'King George', 'VA', '8157 Zepp Drive', NULL, NULL, NULL),
(128, 'Paul', 'Wingeart', 'paul.wingeart16@hotmail.com', '22485', 'King George', 'VA', '8330 Hoover Drive', '734-756-2038', NULL, NULL),
(129, 'Robert', 'Jones', 'R.rivers@riversjones.com', '91604', 'Studio City', 'CA', '11688 Laurel Wood Drive', NULL, NULL, NULL),
(130, 'Robert', 'Lane', 'roberterick@gmail.com', '22401', 'Fredericksburg', 'VA', '1009 Hotchkiss Place', '615-351-1667', NULL, NULL),
(131, 'Bob', 'Rycroft', 'robertrycroft@msn.com', '22401', 'Fredericksburg', 'VA', '707 Mary Ball Street', '540/371-3431', NULL, 'board member'),
(132, 'Rachel', 'Gredler', 'rochterc@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(133, 'Sabina', 'Weitzman', 's.weitzman@verizon.net', '22401', 'Fredericksburg', 'VA', '913 Marye St', NULL, NULL, 'Edie\'s friend'),
(134, 'Susan', 'Barber', 'sbarber100@hotmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(135, 'Sean', 'Bonney', 'sbonney@crrl.org', '22401', 'Fredericksburg', 'VA', '909 Brompton Street', NULL, NULL, 'Janet\'s friend'),
(136, 'Stacey', 'Strentz', 'sns@sgclawyers.com', '22401', 'Fredericksburg', 'VA', '620 Princess Anne St', NULL, NULL, NULL),
(137, 'George', 'Solley', 'solleyg@cox.net', '22401', 'Fredericksburg', 'VA', '1303 Caroline St', NULL, NULL, 'former city councilman'),
(138, 'Susan', 'Neal', 'ssneal@earthlink.net', '22407', 'Fredericksburg', 'VA', '5517 River Road', NULL, NULL, 'former colleague of Edie and Jan'),
(139, 'Laura', 'Joy', 'starseeker111@hotmail.com', '22407', 'Fredericksburg', 'VA', '7 Saint Patrick Street', NULL, NULL, 'friends with Jan and the Neustatters'),
(140, 'Stephanie', 'Sinclair Hoben', 'stephsinclair@mac.com', '10567', 'Cortlandt Manor', 'NY', '73 South Hill Road', NULL, NULL, 'Edie\'s BFF'),
(141, 'Stephanie', 'Hoben', 'stephsinclair@mac.com', '10567', 'Cortlandt Manor', 'NY', '73 South Hill Road', NULL, NULL, 'Edie\'s BFF'),
(142, 'Stephanie', 'Sinclair', 'stephsinclair@mac.com', '10567', 'Cortlandt Manor', 'NY', '73 South Hill Road', NULL, NULL, 'Edie\'s BFF'),
(143, 'Scout', 'Tufankjian', 'stufankjian@gmail.com', '11215', 'Brooklyn', 'NY', '433 7th Ave, Apt 2', NULL, NULL, 'Edie\'s friend'),
(144, 'Susan', 'Park', 'susanduncanpark@gmail.com', '22485', 'King George', 'VA', '10064 Francis Folsom Drive', NULL, NULL, NULL),
(145, 'Stephen', 'Watkins', 'swatkins000@gmail.com', '22401', 'Fredericksburg', 'VA', '1206 Walker Drive', NULL, NULL, 'former CASA volunteer'),
(146, 'Sydney', 'Simpson', 'sydneyjsimpson@gmail.com', '22401', 'Fredericksburg', 'VA', '909 Brompton street', NULL, NULL, 'Jan\'s friend'),
(147, 'Amy', 'Umberger', 'tchakid2read@hotmail.com', '22508', 'Locust Grove', 'VA', '32119 Wilderness Farms Road', NULL, NULL, 'friend of Janet Watkins from UMW; former reading specialist in Spotsy schools and now in Orange Co.;'),
(148, 'Jessica', 'Bloomfield', 'thebloomfields@verizon.net', '22553', 'Spotsylvania', 'VA', '10413 Dana Court', '540-850-1240', NULL, 'Friend of Edie\'s'),
(149, 'Timothy', 'Poe', 'timothyryanpoe@gmail.com', '22401', 'Fredericksburg', 'VA', '608 Spottswood St', NULL, NULL, 'realtor?'),
(150, 'Todd', 'Rump', 'todd.rump@protonmail.com', '22408', 'Fredericksburg', 'VA', '13103 Willow Point Drive', NULL, NULL, NULL),
(151, 'Trina', 'Parsons', 'tparsons@verizon.net', '22405', 'Fredericksburg', 'VA', '92 Boscobel Road', NULL, NULL, 'husband is Jerry M. Parsons Jr.'),
(152, 'Tracy', 'Lloyd', 'tracyannlloyd@gmail.com', '22408', 'Fredericksburg', 'VA', '12114 Kingswood Blvd.', '540-809-2308', NULL, NULL),
(153, 'David', 'Bohmke', 'vabohmke@aol.com', '22405', 'Fredericksburg', 'VA', '416 Collingwood Drive', NULL, NULL, 'Sr. VP at Union Bank & Trust; wife Meg Bohmke was on Stafford BOS'),
(154, 'Sandra', 'Mahaffey', 'veryveggie@gmail.com', '04103', 'Portland', 'ME', '80 Hennessey Drive', NULL, NULL, 'former colleague of Edie and Jan; moved from 121 Ashby St., 22401 to Maine in 2019'),
(155, 'Tiffany', 'Villanueva', 'Villantc@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, 'is a friend of volunteer Felicia Agnew'),
(156, 'Wendy', 'Cannon', 'wendyccannon@gmail.com', '22407', 'Fredericksburg', 'VA', '6600 Hot Spring Lane', NULL, NULL, 'Janet\'s friend'),
(157, 'William', 'Triplett', 'wmtriple@aol.com', '22026', 'Dumfries', 'VA', '18088 Red Mulberry Road', '540-429-6137', NULL, NULL),
(158, 'Bruce', 'Sheaffer', 'wsheaffer@me.com', NULL, NULL, NULL, 'wendy_sheaffer@hotmail.com', NULL, NULL, NULL),
(159, 'John & Rosemarie', 'McKeown', NULL, '22401', 'Fredericksburg', 'VA', '910 Monroe St.', NULL, NULL, 'John is a board member w/ Fbg Symphony Orchestra'),
(160, 'Rosemarie', 'McKeown', NULL, '22401', 'Fredericksburg', 'VA', '910 Monroe St.', NULL, NULL, 'John is a board member w/ Fbg Symphony Orchestra'),
(161, 'John', 'McKeown', NULL, '22401', 'Fredericksburg', 'VA', '910 Monroe St.', NULL, NULL, 'John is a board member w/ Fbg Symphony Orchestra'),
(162, 'Judith', 'Downing', NULL, '17815', 'Bloomsburg', 'PA', '33 Duke of Gloucester', NULL, NULL, NULL),
(163, 'Jill', 'Carroll', NULL, '83713', 'Boise', 'ID', '1071 N. Silver Ash Ave.', NULL, NULL, 'child & family therapist?'),
(164, 'Jerald & Judith', 'Caira', NULL, '14450', 'Fairport', 'NY', '38 Tea Olive Lane', NULL, NULL, 'In memory of Sally Rycroft (Judy\'s sister)'),
(165, 'Jerald', 'Caira', NULL, '14450', 'Fairport', 'NY', '38 Tea Olive Lane', NULL, NULL, 'In memory of Sally Rycroft (Judy\'s sister)'),
(166, 'Judith', 'Caira', NULL, '14450', 'Fairport', 'NY', '38 Tea Olive Lane', NULL, NULL, 'In memory of Sally Rycroft (Judy\'s sister)'),
(167, 'Bob & Harriet', 'Burch', NULL, '22406', 'Falmouth', 'VA', '304 Poplar Road', NULL, NULL, NULL),
(168, 'Harriet', 'Burch', NULL, '22406', 'Falmouth', 'VA', '304 Poplar Road', NULL, NULL, NULL),
(169, 'Bob', 'Burch', NULL, '22406', 'Falmouth', 'VA', '304 Poplar Road', NULL, NULL, NULL),
(170, 'Community Baptist', 'Church', NULL, '22405', 'Falmouth', 'VA', '44 Lorenzo Drive', NULL, NULL, 'Teresa Bullock is in a Women\'s Missions Group there.'),
(171, 'Vicki', 'Silver', NULL, '22405', 'Falmouth', 'VA', '50 Silver Ridge Ln.', NULL, NULL, 'tutor'),
(172, 'Doris', 'Watkins', NULL, '76110', 'Fort Worth', 'TX', '2237 Hawthorne Ave.', NULL, NULL, 'court services?'),
(173, 'Gail E.', 'Taylor', NULL, '22407', 'Fredericksburg', 'VA', '100 Roanoke St.', NULL, NULL, 'home sold in 2021'),
(174, 'Gail', 'Taylor', NULL, '22407', 'Fredericksburg', 'VA', '100 Roanoke St.', NULL, NULL, 'home sold in 2021'),
(175, 'Mark & Jacqueline', 'Keith', NULL, '22407', 'Fredericksburg', 'VA', '11809 Clarence Drive', NULL, NULL, 'Jackie is a former Spotsy school librarian and Google Certified Data Analyst, Consultant/Owner Better Way Consulting, LLC.'),
(176, 'Mark', 'Keith', NULL, '22407', 'Fredericksburg', 'VA', '11809 Clarence Drive', NULL, NULL, 'Jackie is a former Spotsy school librarian and Google Certified Data Analyst, Consultant/Owner Better Way Consulting, LLC.'),
(177, 'Jacqueline', 'Keith', NULL, '22407', 'Fredericksburg', 'VA', '11809 Clarence Drive', NULL, NULL, 'Jackie is a former Spotsy school librarian and Google Certified Data Analyst, Consultant/Owner Better Way Consulting, LLC.'),
(178, 'Shelby', 'Gilliam', NULL, '22407', 'Fredericksburg', 'VA', '12022 Grantwood Drive', NULL, NULL, 'in insurance biz'),
(179, 'Nathan Eskin & Mary', 'DeMerle', NULL, '22407', 'Fredericksburg', 'VA', '12515 Single Oak Road', NULL, NULL, 'Mary is a UMW grad and office manager at The Hearing Aid Place'),
(180, 'Mary', 'DeMerle', NULL, '22407', 'Fredericksburg', 'VA', '12515 Single Oak Road', NULL, NULL, 'Mary is a UMW grad and office manager at The Hearing Aid Place'),
(181, 'Nathan Eskin', 'DeMerle', NULL, '22407', 'Fredericksburg', 'VA', '12515 Single Oak Road', NULL, NULL, 'Mary is a UMW grad and office manager at The Hearing Aid Place'),
(182, 'David & Linda', 'Neustatter', NULL, '22407', 'Fredericksburg', 'VA', '1307 Princess Anne St.', NULL, NULL, 'friends of Janet Watkins'),
(183, 'Linda', 'Neustatter', NULL, '22407', 'Fredericksburg', 'VA', '1307 Princess Anne St.', NULL, NULL, 'friends of Janet Watkins'),
(184, 'David', 'Neustatter', NULL, '22407', 'Fredericksburg', 'VA', '1307 Princess Anne St.', NULL, NULL, 'friends of Janet Watkins'),
(185, 'James & Mary', 'Miller', NULL, '22401', 'Fredericksburg', 'VA', '216 Caroline Street', NULL, NULL, 'Martin J. Miller & wife Elaine; attorney?'),
(186, 'James', 'Miller', NULL, '22401', 'Fredericksburg', 'VA', '216 Caroline Street', NULL, NULL, 'Martin J. Miller & wife Elaine; attorney?'),
(187, 'Mary', 'Miller', NULL, '22401', 'Fredericksburg', 'VA', '216 Caroline Street', NULL, NULL, 'Martin J. Miller & wife Elaine; attorney?'),
(188, 'Elaine', 'Miller', NULL, '22401', 'Fredericksburg', 'VA', '216 Caroline Street', NULL, NULL, 'Martin J. Miller & wife Elaine; attorney?'),
(189, 'Marjorie R.', 'Tankersley', NULL, '22401', 'Fredericksburg', 'VA', '208 Braehead Drive', '540/273-4080', NULL, 'former Hugh Mercer Elem principal'),
(190, 'Marjorie', 'Tankersley', NULL, '22401', 'Fredericksburg', 'VA', '208 Braehead Drive', '540/273-4080', NULL, 'former Hugh Mercer Elem principal'),
(191, 'Mary Raye', 'Cox', NULL, '22405', 'Fredericksburg', 'VA', '21 Lawrence Lane', '540/371-5469', NULL, NULL),
(192, 'Mary', 'Cox', NULL, '22405', 'Fredericksburg', 'VA', '21 Lawrence Lane', '540/371-5469', NULL, NULL),
(193, 'Martin', 'Miller', NULL, '22401', 'Fredericksburg', 'VA', '216 Caroline Street', NULL, NULL, 'Martin J. Miller & wife Elaine; attorney?'),
(194, 'Anita', 'Marshall', NULL, '22401', 'Fredericksburg', 'VA', '2700 Cowan Blvd. #126', NULL, NULL, 'Janet Watkins\' mother'),
(195, 'Brandon', 'Thompson', NULL, '22405', 'Fredericksburg', 'VA', '35 Wayne St.', NULL, NULL, NULL),
(196, 'Redeemer Lutheran', 'Church', NULL, '22408', 'Fredericksburg', 'VA', '5120 Harrison Road', NULL, NULL, NULL),
(197, 'Pohanka Auto', 'Center', NULL, '22408', 'Fredericksburg', 'VA', '5200 Jefferson Davis Hwy', NULL, NULL, NULL),
(198, 'Monica', 'Dionne', NULL, '22408', 'Fredericksburg', 'VA', '531 Laurel Avenue', NULL, NULL, 'Monica has done bellydancing'),
(199, 'Benjamin', 'McDavid', NULL, '22408', 'Fredericksburg', 'VA', '531 Laurel Avenue', NULL, NULL, NULL),
(200, 'Amy', 'Limbrick (aka Kiyaana)', NULL, '22405', 'Fredericksburg', 'VA', '544 McCarty Road', NULL, NULL, NULL),
(201, 'Amy', 'Limbrick', NULL, '22405', 'Fredericksburg', 'VA', '544 McCarty Road', NULL, NULL, NULL),
(202, 'Alan', 'Hart', NULL, '22407', 'Fredericksburg', 'VA', '5707 Cambridge Drive', NULL, NULL, 'Alan is former VP of Cambridge HOA;'),
(203, 'Mary', 'Hart', NULL, '22407', 'Fredericksburg', 'VA', '5707 Cambridge Drive', NULL, NULL, 'Alan is former VP of Cambridge HOA;'),
(204, 'Alan & Mary', 'Hart', NULL, '22407', 'Fredericksburg', 'VA', '5707 Cambridge Drive', NULL, NULL, 'Alan is former VP of Cambridge HOA;'),
(205, 'Shirley', 'Bozicevic', NULL, '22405', 'Fredericksburg', 'VA', '603 Elmwood Drive', NULL, NULL, 'Catholic'),
(206, 'Elizabeth', 'Morris', NULL, '22401', 'Fredericksburg', 'VA', '603 Faquier Street', NULL, NULL, 'husband James P. Morris died in 2020'),
(207, 'Kurt & Linda', 'Glaeser', NULL, '22401', 'Fredericksburg', 'VA', '705 Mary Ball St.', NULL, NULL, 'Kurt is retired UMW coach of women\'s soccer and men\'s lacrosse'),
(208, 'Linda', 'Glaeser', NULL, '22401', 'Fredericksburg', 'VA', '705 Mary Ball St.', NULL, NULL, 'Kurt is retired UMW coach of women\'s soccer and men\'s lacrosse'),
(209, 'Kurt', 'Glaeser', NULL, '22401', 'Fredericksburg', 'VA', '705 Mary Ball St.', NULL, NULL, 'Kurt is retired UMW coach of women\'s soccer and men\'s lacrosse'),
(210, 'Anne & Carl', 'Little', NULL, '22401', 'Fredericksburg', 'VA', '726 William Street', NULL, NULL, 'Jan\'s friend; Tree Fbg'),
(211, 'Carl', 'Little', NULL, '22401', 'Fredericksburg', 'VA', '726 William Street', NULL, NULL, 'Jan\'s friend; Tree Fbg'),
(212, 'Anne', 'Little', NULL, '22401', 'Fredericksburg', 'VA', '726 William Street', NULL, NULL, 'Jan\'s friend; Tree Fbg'),
(213, 'Carol & Joseph', 'Dreiss', NULL, '22401', 'Fredericksburg', 'VA', '809 Sylvania Ave.', NULL, NULL, 'Joe is a prof of art history at UMW'),
(214, 'Joseph', 'Dreiss', NULL, '22401', 'Fredericksburg', 'VA', '809 Sylvania Ave.', NULL, NULL, 'Joe is a prof of art history at UMW'),
(215, 'Carol', 'Dreiss', NULL, '22401', 'Fredericksburg', 'VA', '809 Sylvania Ave.', NULL, NULL, 'Joe is a prof of art history at UMW'),
(216, 'John and Marilyn', 'Farrington', NULL, '22401', 'Fredericksburg', 'VA', '900 Cornell Street', NULL, NULL, 'Marilyn is a trustee at St. George\'s Episcopal and John is Senior Financial Advisor at Merrill Lynch Wealth Management'),
(217, 'Benevolent & Protective Order ', '875', NULL, '22408', 'Fredericksburg', 'VA', 'c/o The Bullocks, 405 Lorraine Ave.', NULL, NULL, NULL),
(218, 'Debbie', 'Cheshire', NULL, '65101', 'Jefferson City', 'MO', '1719 Green Meadow Drive', NULL, NULL, 'Husband Steve passed in 2016; their son, Jay, died at age 52 in Oct. 2023.'),
(219, 'Lyla M.', 'Hogle', NULL, '20707', 'Laurel', 'MD', '14408 Mayfair Drive', NULL, NULL, 'Please accept this donation in memory of Sally A. Rycroft, Robert Rycroft\'s wife.'),
(220, 'Lyla', 'Hogle', NULL, '20707', 'Laurel', 'MD', '14408 Mayfair Drive', NULL, NULL, 'Please accept this donation in memory of Sally A. Rycroft, Robert Rycroft\'s wife.'),
(221, 'Doug & Peggy', 'Pope', NULL, '22508', 'Locust Grove', 'VA', '219 Harpers Ferry Drive', NULL, NULL, 'Random end-of-year gift, though Jill said they may have given before'),
(222, 'Peggy', 'Pope', NULL, '22508', 'Locust Grove', 'VA', '219 Harpers Ferry Drive', NULL, NULL, 'Random end-of-year gift, though Jill said they may have given before'),
(223, 'Doug', 'Pope', NULL, '22508', 'Locust Grove', 'VA', '219 Harpers Ferry Drive', NULL, NULL, 'Random end-of-year gift, though Jill said they may have given before'),
(224, 'Ellen', 'Ashwell', NULL, '23117', 'Mineral', 'VA', '6051 Lost Cove Drive', NULL, NULL, 'volunteered 2001-2012'),
(225, 'Penny & Ron', 'Saulnier', NULL, '28560', 'New Bern', 'NC', '226 New Street', NULL, NULL, NULL),
(226, 'Penny', 'Saulnier', NULL, '28560', 'New Bern', 'NC', '226 New Street', NULL, NULL, NULL),
(227, 'Ron', 'Saulnier', NULL, '28560', 'New Bern', 'NC', '226 New Street', NULL, NULL, NULL),
(228, 'Elizabeth', 'Parsnick', NULL, '14305', 'Niagara Falls', 'NY', '4217 McKoon Ave.', NULL, NULL, NULL),
(229, 'John and Barbara', 'Jesz', NULL, '14305', 'Niagara Falls', 'NY', '6937 Colonial Drive', NULL, NULL, 'Barbara is a retired health dept employee in NY; the couple\'s son died in Oct. 2020 at age 49'),
(230, 'Barbara', 'Jesz', NULL, '14305', 'Niagara Falls', 'NY', '6937 Colonial Drive', NULL, NULL, 'Barbara is a retired health dept employee in NY; the couple\'s son died in Oct. 2020 at age 49'),
(231, 'John', 'Jesz', NULL, '14305', 'Niagara Falls', 'NY', '6937 Colonial Drive', NULL, NULL, 'Barbaras partner; the couple\'s son died in Oct. 2020 at age 49'),
(232, 'Deborah & Wayne', 'Seitl', NULL, '34238', 'Sarasota', 'FL', '3893 Boca Pointe Drive', NULL, NULL, NULL),
(233, 'Wayne', 'Seitl', NULL, '34238', 'Sarasota', 'FL', '3893 Boca Pointe Drive', NULL, NULL, NULL),
(234, 'Deborah', 'Seitl', NULL, '34238', 'Sarasota', 'FL', '3893 Boca Pointe Drive', NULL, NULL, NULL),
(235, 'Elisabeth', 'Cunard', NULL, '22551', 'Spotsylvania', 'VA', '11305 Longstreet Drive', NULL, NULL, 'Fawn Lake Country Club, 540-972-6200'),
(236, 'Georgia & John', 'Locks', NULL, '22553', 'Spotsylvania', 'VA', '11901 Powder Mill Ct.', NULL, NULL, NULL),
(237, 'John', 'Locks', NULL, '22553', 'Spotsylvania', 'VA', '11901 Powder Mill Ct.', NULL, NULL, NULL),
(238, 'Georgia', 'Locks', NULL, '22553', 'Spotsylvania', 'VA', '11901 Powder Mill Ct.', NULL, NULL, NULL),
(239, 'Sue & David', 'Roberts', NULL, '22553', 'Spotsylvania', 'VA', 'PO Box 1621', NULL, NULL, NULL),
(240, 'David', 'Roberts', NULL, '22553', 'Spotsylvania', 'VA', 'PO Box 1621', NULL, NULL, NULL),
(241, 'Sue', 'Roberts', NULL, '22553', 'Spotsylvania', 'VA', 'PO Box 1621', NULL, NULL, NULL),
(242, 'Garrisonville', 'Dental', NULL, '22554', 'Stafford', 'VA', '481 Garrisonville Rd., Suite 105', NULL, NULL, NULL),
(243, 'Bill & Cecelia', 'Howell', NULL, '22405', 'Stafford', 'VA', '6 Hunters Ct.', NULL, NULL, NULL),
(244, 'Cecelia', 'Howell', NULL, '22405', 'Stafford', 'VA', '6 Hunters Ct.', NULL, NULL, NULL),
(245, 'Bill', 'Howell', NULL, '22405', 'Stafford', 'VA', '6 Hunters Ct.', NULL, NULL, NULL),
(246, 'Cookie & Steve', 'Gross', NULL, '33596', 'Valrico', 'FL', '2735 Brookville Drive', NULL, NULL, 'Edie\'s parents'),
(247, 'Steve', 'Gross', NULL, '33596', 'Valrico', 'FL', '2735 Brookville Drive', NULL, NULL, 'Edie\'s parents'),
(248, 'Cookie', 'Gross', NULL, '33596', 'Valrico', 'FL', '2735 Brookville Drive', NULL, NULL, 'Edie\'s parents'),
(249, 'Integrated Direct', 'Marketing', NULL, '20036', 'Washington', 'D.C.', '1250 Connecticut Ave. NW, #700', '202/517-7162', NULL, NULL),
(250, 'Michael P.', 'Wiedemer', NULL, '37398', 'Winchester', 'TN', '362 Oak Circle', NULL, NULL, NULL),
(251, 'Michael', 'Wiedemer', NULL, '37398', 'Winchester', 'TN', '362 Oak Circle', NULL, NULL, NULL),
(252, 'Laurel Fensterer and Timothy', 'Davis', NULL, '22580', 'Woodford', 'VA', '3418 Fredericksburg Turnpike', NULL, NULL, NULL),
(253, 'Timothy', 'Davis', NULL, '22580', 'Woodford', 'VA', '3418 Fredericksburg Turnpike', NULL, NULL, NULL),
(254, 'Laurel', 'Fensterer', NULL, '22580', 'Woodford', 'VA', '3418 Fredericksburg Turnpike', NULL, NULL, NULL),
(255, 'Alissa', 'Merlo', 'agmm1830@gmail.com', '33028', 'Pembroke Pines', 'FL', '1261 NW 159th Ave.', NULL, NULL, 'Edie\'s cousin'),
(256, 'Stephanie', 'Yushchak', 'fdcsky23@gmail.com', '22407', 'Fredericksburg', 'VA', '10620 Courthouse Road', NULL, NULL, NULL),
(257, 'Melissa', 'Repetski', 'mrepetski@yahoo.com', '22309', 'Alexandria', 'VA', '8329 Orange Court', NULL, NULL, 'In honor of Cookie Gross, Edie\'s mom and a former teacher of her kids'),
(258, 'Teresa', 'Bullock', 'tcb405@cox.net', '22408', 'Fredericksburg', 'VA', '405 Lorraine Ave.', NULL, NULL, NULL),
(259, 'Cindy', 'Simpson', 'cindy@goldensfam.com', '22554', 'Stafford', 'VA', '60 Brush Everard Ct.', NULL, NULL, NULL),
(260, 'Gayle', 'Dillon', 'gddillon@gmail.com', '23192', 'Montpelier', 'VA', '15094 Hawks Climb Lane', NULL, NULL, NULL),
(261, 'Ruth', 'Golmant', 'rgolmant@verizon.net', '22554', 'Stafford', 'VA', '3 Bloomington Lane', NULL, NULL, 'Friend of Edie\'s'),
(262, 'Amy', 'Umble', 'umbleamy@gmail.com', '22407', 'Fredericksburg', 'VA', '611 Halleck Street', NULL, NULL, 'Friend and former colleague of Edie\'s; works at RACSB'),
(263, 'Kathy', 'Crutcher', 'kathy@shoutmousepress.org', '20009', 'Washington', 'D.C.', '1735 17th St NW', NULL, NULL, NULL),
(264, 'Martha', 'Moses', 'martha.moses@gmail.com', '85718', 'Tucson', 'AZ', '7534 N Mystic Canyon Drive', NULL, NULL, 'Longtime family friend of Edie'),
(265, 'Ana', 'Valle-Greene', NULL, '28412', 'Wilmington', 'NC', '6340 Branford Road', NULL, NULL, NULL),
(266, 'Ana', 'Valle', NULL, '28412', 'Wilmington', 'NC', '6340 Branford Road', NULL, NULL, NULL),
(267, 'Ana', 'Greene', NULL, '28412', 'Wilmington', 'NC', '6340 Branford Road', NULL, NULL, NULL),
(268, 'Elizabeth', 'Stokes', 'Sleeplessinfla@aol.com', NULL, 'Florida', NULL, NULL, NULL, NULL, 'Saw a post on Facebook, maybe from Cookie Gross (Edie\'s mom), and donated.'),
(269, 'Jessica', 'Varner', 'jessica.varner@dss.virginia.gov', NULL, NULL, NULL, NULL, NULL, NULL, 'Fredericksburg CPS worker'),
(270, 'Shondella', 'Murray', 'shondellamarie@gmail.com', '22407', 'Fredericksburg', 'VA', '6905 Hawthorne Woods Circle', NULL, NULL, 'CASA board member and former volunteers'),
(271, 'Karen', 'Gross', 'kgross1605@aol.com', '33141', 'Miami Beach', 'FL', '1830 Bay Drive', NULL, NULL, 'Edie\'s aunt'),
(272, 'Leslee', 'Barnicle', 'leslee16@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(273, 'Dr. Jacinta', 'Topps', 'jacinta.topps@comcast.net', '22553', 'Spotsylvania', 'VA', '11510 Balmartin Court', NULL, NULL, 'board member'),
(274, 'Jacinta', 'Topps', 'jacinta.topps@comcast.net', '22553', 'Spotsylvania', 'VA', '11510 Balmartin Court', NULL, NULL, 'board member'),
(275, 'Daniel and Karen', 'Durham', NULL, '20854-3848', 'Potomac', 'MD', '7209 Masters Drive', NULL, NULL, 'In memory of Bobby Anderson'),
(276, 'Karen', 'Durham', NULL, '20854-3848', 'Potomac', 'MD', '7209 Masters Drive', NULL, NULL, 'In memory of Bobby Anderson'),
(277, 'Daniel', 'Durham', NULL, '20854-3848', 'Potomac', 'MD', '7209 Masters Drive', NULL, NULL, 'In memory of Bobby Anderson'),
(278, 'Robert and Maxine', 'Moore', NULL, '22405', 'Fredericksburg', 'VA', '609 Falmouth Drive', '540-373-7076', NULL, 'In memory of Bobby Anderson'),
(279, 'Maxine', 'Moore', NULL, '22405', 'Fredericksburg', 'VA', '609 Falmouth Drive', '540-373-7076', NULL, 'In memory of Bobby Anderson'),
(280, 'Robert', 'Moore', NULL, '22405', 'Fredericksburg', 'VA', '609 Falmouth Drive', '540-373-7076', NULL, 'In memory of Bobby Anderson'),
(281, 'Jack & Patsy', 'Rowley', NULL, '22406', 'Fredericksburg', 'VA', '1142 Truslow Road', NULL, NULL, 'In memory of Bobby Anderson'),
(282, 'Patsy', 'Rowley', NULL, '22406', 'Fredericksburg', 'VA', '1142 Truslow Road', NULL, NULL, 'In memory of Bobby Anderson'),
(283, 'Jack', 'Rowley', NULL, '22406', 'Fredericksburg', 'VA', '1142 Truslow Road', NULL, NULL, 'In memory of Bobby Anderson'),
(284, 'Michael & Marietta', 'D\'Ostilio', NULL, '22406', 'Fredericksburg', 'VA', '31 Sanibel Drive', NULL, NULL, 'In memory of Bobby Anderson'),
(285, 'Marietta', 'D\'Ostilio', NULL, '22406', 'Fredericksburg', 'VA', '31 Sanibel Drive', NULL, NULL, 'In memory of Bobby Anderson'),
(286, 'Michael', 'D\'Ostilio', NULL, '22406', 'Fredericksburg', 'VA', '31 Sanibel Drive', NULL, NULL, 'In memory of Bobby Anderson'),
(287, 'test', 'test', 'test@gmail.com', '20112', 'test', 'test', 'test', '999-999-9999', 'test', NULL),
(288, 'test', 'test', 'test@gmail.com', '20112', 'test', 'test', 'test', '999-999-9999', 'test', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `emails`
--

CREATE TABLE `emails` (
  `id` int(11) NOT NULL,
  `message` text DEFAULT NULL,
  `purpose` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `filePath` varchar(256) NOT NULL,
  `linkToDrive` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `reportType` varchar(30) DEFAULT NULL,
  `dateRangeStart` char(10) DEFAULT NULL,
  `dateRangeEnd` char(10) DEFAULT NULL,
  `name` varchar(30) DEFAULT NULL,
  `filePath` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

<<<<<<< HEAD
--
-- Indexes for table `associations`
--
ALTER TABLE `associations`
  ADD PRIMARY KEY (`eventID`,`donationID`,`donorID`,`emailID`,`filePath`),
  ADD KEY `donationID` (`donationID`),
  ADD KEY `donorID` (`donorID`),
  ADD KEY `emailID` (`emailID`),
  ADD KEY `filePath` (`filePath`);
=======


--
-- Indexes for table `dbdiscussions`
--
ALTER TABLE `dbdiscussions`
  ADD PRIMARY KEY (`author_id`(255),`title`);

--
-- Indexes for table `dbeventpersons`
--
ALTER TABLE `dbeventpersons`
  ADD KEY `FKeventID` (`eventID`),
  ADD KEY `FKpersonID` (`userID`);
>>>>>>> origin/jbyrne_dev

--
-- Indexes for table `dbevents`
--
ALTER TABLE `dbevents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dbpersons`
--
ALTER TABLE `dbpersons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `eventID` (`eventID`),
  ADD KEY `donorID` (`donorID`);

--
-- Indexes for table `donors`
--
ALTER TABLE `donors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emails`
--
ALTER TABLE `emails`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`filePath`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `filePath` (`filePath`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dbevents`
--
ALTER TABLE `dbevents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `donations`
--
ALTER TABLE `donations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=190;

--
-- AUTO_INCREMENT for table `donors`
--
ALTER TABLE `donors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=289;

--
-- AUTO_INCREMENT for table `emails`
--
ALTER TABLE `emails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `associations`
--
ALTER TABLE `associations`
  ADD CONSTRAINT `associations_ibfk_1` FOREIGN KEY (`eventID`) REFERENCES `dbevents` (`id`),
  ADD CONSTRAINT `associations_ibfk_2` FOREIGN KEY (`donationID`) REFERENCES `donations` (`id`),
  ADD CONSTRAINT `associations_ibfk_3` FOREIGN KEY (`donorID`) REFERENCES `donors` (`id`),
  ADD CONSTRAINT `associations_ibfk_4` FOREIGN KEY (`emailID`) REFERENCES `emails` (`id`),
  ADD CONSTRAINT `associations_ibfk_5` FOREIGN KEY (`filePath`) REFERENCES `files` (`filePath`);

--
-- Constraints for table `donations`
--
ALTER TABLE `donations`
  ADD CONSTRAINT `donations_ibfk_1` FOREIGN KEY (`eventID`) REFERENCES `dbevents` (`id`),
  ADD CONSTRAINT `donations_ibfk_2` FOREIGN KEY (`donorID`) REFERENCES `donors` (`id`);

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`filePath`) REFERENCES `files` (`filePath`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
