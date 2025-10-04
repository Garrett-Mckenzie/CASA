-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 21, 2025 at 05:53 PM
-- Server version: 8.0.43-34
-- PHP Version: 8.2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbkzrh4cfmxbt0`
--

-- --------------------------------------------------------


-- --------------------------------------------------------


--
-- Table structure for table `dbeventmedia`
--

DROP TABLE IF EXISTS `dbeventmedia`;
CREATE TABLE `dbeventmedia` (
  `id` int NOT NULL,
  `eventID` int NOT NULL,
  `file_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_format` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `altername_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `time_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dbeventpersons`
--

DROP TABLE IF EXISTS `dbeventpersons`;
CREATE TABLE `dbeventpersons` (
  `eventID` int NOT NULL,
  `userID` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dbeventpersons`
--

INSERT INTO `dbeventpersons` (`eventID`, `userID`, `position`, `notes`) VALUES
(64, 'vmsroot', 'v', 'Skills:  | Dietary restrictions:  | Disabilities:  | Materials: '),
(100, 'john_doe', 'v', 'Skills:  | Dietary restrictions:  | Disabilities:  | Materials: '),
(64, 'vmsroot', 'v', 'Skills:  | Dietary restrictions:  | Disabilities:  | Materials: '),
(100, 'john_doe', 'v', 'Skills:  | Dietary restrictions:  | Disabilities:  | Materials: ');

-- --------------------------------------------------------

--
-- Table structure for table `dbevents`
--

DROP TABLE IF EXISTS `dbevents`;
CREATE TABLE `dbevents` (
  `id` int NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` char(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `startTime` char(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `endTime` char(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `capacity` int NOT NULL,
  `completed` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `restricted_signup` tinyint(1) NOT NULL,
  `location` text COLLATE utf8mb4_unicode_ci,
  `type` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dbevents`
--

INSERT INTO `dbevents` (`id`, `name`, `date`, `startTime`, `endTime`, `description`, `capacity`, `completed`, `restricted_signup`, `location`, `type`) VALUES
(112, 'DOGGIE WALKIES', '2025-04-30', '13:00', '15:00', 'walking the doggies in the woods', 20, 'yes', 0, 'Miami, USA', 'blah'),
(117, 'Color Test', '2025-05-02', '13:00', '14:00', 'Testing the colors in the calendar', 12, 'no', 0, 'Fred', 'Test'),
(118, 'Halloween Event', '2025-10-31', '18:00', '20:30', 'It is halloween!!', 50, 'no', 0, 'Fredericksburg, VA', 'Holiday'),
(119, 'party :)', '2026-01-14', '01:00', '01:01', 'dancin', 1, 'no', 0, 'my house', 'party :)'),
(120, 'SDLFjkafs', '2025-09-10', '12:00', '14:00', 'j;aksdfj', 99999, 'no', 0, 'asdf;j', 'sadj'),
(121, 'Whikey Valor Tasting', '2025-09-24', '15:00', '18:00', 'Come have a taste of fine barrel aged whiskey with fellow Vets.', 25, 'no', 0, 'Old Silk Mill', 'Tasting'),
(122, 'Event', '2025-12-01', '13:00', '14:00', 'Use Case Event', 77, 'no', 0, 'UMW', 'Group'),
(123, 'Ethan&#039;s Birthday Party', '2025-10-03', '07:30', '19:30', 'Ethan is going to eat my cake.', 2147483647, 'no', 0, 'Eagle 225', 'Party'),
(124, 'Example event', '2025-09-11', '12:00', '14:00', 'This is a test event', 42, 'no', 0, 'UMW', 'A test'),
(125, 'Pet Adoption', '2025-09-13', '11:00', '17:00', 'Pet Adoption', 50, 'no', 0, 'Fredericksburg, Virginia', 'Pet Adoption'),
(126, 'Squirrel Watching', '2025-09-22', '06:00', '09:00', 'Watch the squirrels to make sure they do not eat the bird seed', 6, 'no', 0, '275 Butler Rd, Fredericksburg, VA 22405', 'Squirrel'),
(127, 'Whoosky Volar Tasting', '2025-09-15', '09:00', '13:00', 'Test Event', 42, 'no', 0, 'House', 'Get-Together'),
(128, 'Event', '2025-12-01', '13:30', '14:00', 'Use Case Event', 77, 'no', 0, 'UMW', 'Person'),
(129, 'Test event Woak', '2025-10-31', '15:00', '18:00', 'testing thsi woa', 99, 'no', 0, 'required but not listed', 'not listed as req'),
(130, 'Class Example', '2025-09-24', '12:00', '14:00', 'This is an example', 10, 'no', 0, 'Farmer', 'Shit storm');

-- --------------------------------------------------------



--
-- Table structure for table `dbpendingsignups`
--

DROP TABLE IF EXISTS `dbpendingsignups`;
CREATE TABLE `dbpendingsignups` (
  `username` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `eventname` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dbpendingsignups`
--

INSERT INTO `dbpendingsignups` (`username`, `eventname`, `role`, `notes`) VALUES
('vmsroot', '108', 'v', 'Skills: non | Dietary restrictions: ojnjo | Disabilities: jonoj | Materials: knock'),
('vmsroot', '101', 'v', 'Skills: rvwav | Dietary restrictions: varv | Disabilities: var | Materials: arv'),
('vmsroot', '108', 'v', 'Skills: non | Dietary restrictions: ojnjo | Disabilities: jonoj | Materials: knock'),
('vmsroot', '101', 'v', 'Skills: rvwav | Dietary restrictions: varv | Disabilities: var | Materials: arv');

-- --------------------------------------------------------

--
-- Table structure for table `dbpersonhours`
--

DROP TABLE IF EXISTS `dbpersonhours`;
CREATE TABLE `dbpersonhours` (
  `personID` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL,
  `eventID` int NOT NULL,
  `start_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `end_time` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dbpersonhours`
--

INSERT INTO `dbpersonhours` (`personID`, `eventID`, `start_time`, `end_time`) VALUES
('john_doe', 100, '2024-11-23 22:00:00', '2024-11-23 23:00:00'),
('john_doe', 100, '2024-11-23 22:00:00', '2024-11-23 23:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `dbpersons`
--

DROP TABLE IF EXISTS `dbpersons`;
CREATE TABLE `dbpersons` (
  `id` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` text,
  `first_name` text NOT NULL,
  `last_name` text,
  `street_address` text,
  `city` text,
  `state` varchar(2) DEFAULT NULL,
  `zip_code` text,
  `phone1` varchar(12) NOT NULL,
  `phone1type` text,
  `emergency_contact_phone` varchar(12) DEFAULT NULL,
  `emergency_contact_phone_type` text,
  `birthday` text,
  `email` text,
  `emergency_contact_first_name` text NOT NULL,
  `contact_num` varchar(255) DEFAULT 'n/a',
  `emergency_contact_relation` text NOT NULL,
  `contact_method` text,
  `type` text,
  `status` text,
  `notes` text,
  `password` text,
  `skills` text NOT NULL,
  `interests` text NOT NULL,
  `archived` tinyint(1) NOT NULL,
  `emergency_contact_last_name` text NOT NULL,
  `is_new_volunteer` tinyint(1) NOT NULL DEFAULT '1',
  `is_community_service_volunteer` tinyint(1) NOT NULL DEFAULT '0',
  `total_hours_volunteered` decimal(5,2) DEFAULT '0.00',
  `volunteer_of_the_month` tinyint(1) DEFAULT '0',
  `votm_awarded_month` date DEFAULT NULL,
  `training_level` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `dbpersons`
--

INSERT INTO `dbpersons` (`id`, `start_date`, `first_name`, `last_name`, `street_address`, `city`, `state`, `zip_code`, `phone1`, `phone1type`, `emergency_contact_phone`, `emergency_contact_phone_type`, `birthday`, `email`, `emergency_contact_first_name`, `contact_num`, `emergency_contact_relation`, `contact_method`, `type`, `status`, `notes`, `password`, `skills`, `interests`, `archived`, `emergency_contact_last_name`, `is_new_volunteer`, `is_community_service_volunteer`, `total_hours_volunteered`, `volunteer_of_the_month`, `votm_awarded_month`, `training_level`) VALUES
('ameyer123', '2025-05-01', 'Aidan', 'Meyer', '1541 Surry Hill Court', 'Charlottesville', 'VA', '22901', '4344222910', 'home', '4344222910', 'home', '2003-08-17', 'aidanmeyer32@gmail.com', 'Aidan', 'n/a', 'Father', NULL, 'participant', 'Inactive', NULL, '$2y$10$2VDZjrW0EacO0VA5hIYIl.fKqPC5wUdSSQ1lXXRSgC0eWxVslPcOC', 'a', 'a', 0, 'Meyer', 0, 0, 0.00, 0, NULL, 'None'),
('ameyer3', '2025-03-26', 'Aidan', 'Meyer', '1541 Surry Hill Court', 'Charlottesville', 'VA', '22901', '4344222910', 'home', '4344222910', 'home', '2003-08-17', 'aidanmeyer32@gmail.com', 'Aidan', 'n/a', 'Father', NULL, 'volunteer', 'Active', NULL, '$2y$10$0R5pX4uTxS0JZ4rc7dGprOK4c/d1NEs0rnnaEmnW4sz8JIQVyNdBC', 'a', 'a', 0, 'Meyer', 0, 0, 70.00, 1, '2025-09-10', NULL),
('BobVolunteer', '2025-04-29', 'Bob', 'SPCA', '123 Dog Ave', 'Dogville', 'VA', '54321', '9806761234', 'home', '1234567788', 'home', '2020-03-03', 'fred54321@gmail.com', 'Luke', 'n/a', 'Bff', NULL, 'volunteer', 'Active', NULL, '$2y$10$4wUwAW0yoizxi5UFy1/OZu.yfYY7rzUsuYcZCdvfplLj95r7OknvG', 'No epic skills', 'No interests', 0, 'Blair', 0, 0, 70.00, 0, NULL, 'None'),
('lukeg', '2025-04-29', 'Luke', 'Gibson', '22 N Ave', 'Fredericksburg', 'VA', '22401', '1234567890', 'cellphone', '1234567890', 'cellphone', '2025-04-28', 'volunteer@volunteer.com', 'NoName', 'n/a', 'Brother', NULL, 'volunteer', 'Active', NULL, '$2y$10$KsNVJYhvO5D287GpKYsIPuci9FnL.Eng9R6lBpaetu2Y0yVJ7Uuiq', 'reading', 'none', 0, 'YesName', 0, 0, 0.00, 0, NULL, 'None'),
('maddiev', '2025-04-28', 'maddie', 'van buren', '123 Blue st', 'fred', 'VA', '12343', '1234567890', 'cellphone', '1234567819', 'cellphone', '2003-05-17', 'mvanbure@mail.umw.edu', 'mommy', 'n/a', 'mom', NULL, 'volunteer', 'Active', NULL, '$2y$10$0mv3.e6gjqoIg.HfT5qVXOsI.Ca5E93DAy8BnT124W1PvMDxpfoxy', 'coding', 'yoga', 0, 'van buren', 0, 0, -8.98, 0, NULL, 'None'),
('michael_smith', '2025-03-16', 'Michael', 'Smith', '789 Pine Street', 'Charlottesville', 'VA', '22903', '4345559876', 'mobile', '4345553322', 'work', '1995-08-22', 'michaelsmith@email.com', 'Sarah', '4345553322', 'Sister', 'email', 'volunteer', 'Active', '', '$2y$10$XYZ789xyz456LMN123DEF', 'Cooking, Basketball', 'Homeless Shelter Assistance', 0, 'Smith', 0, 1, 0.00, 0, NULL, NULL),
('michellevb', '2025-04-29', 'Michelle', 'Van Buren', '1234 Red St', 'Freddy', 'VA', '22401', '1234567890', 'cellphone', '0987654321', 'cellphone', '1980-08-18', 'michelle.vb@gmail.com', 'Madison', 'n/a', 'daughter', NULL, 'volunteer', 'Active', NULL, '$2y$10$bkqOWUdIJoSa6kZoRo5KH.cerZkBQf74RYsponUUgefJxNc8ExppK', 'programming', 'doggies', 0, 'Van Buren', 0, 0, 60.00, 0, NULL, 'None'),
('test_acc', '2025-04-29', 'test', 'test', 'test', 'test', 'VA', '22405', '5555555555', 'cellphone', '5555555555', 'cellphone', '2003-03-03', 'test@gmail.com', 'test', 'n/a', 't', NULL, 'volunteer', 'Active', NULL, '$2y$10$kpVA41EXvoJyv896uDBEF.fHCPmSlkVSaXjHojBl7DqbRnEm//kxy', '', '', 0, 'test', 0, 0, -4.99, 0, NULL, 'None'),
('vmsroot', NULL, 'vmsroot', '', 'N/A', 'N/A', 'VA', 'N/A', '', 'N/A', 'N/A', 'N/A', NULL, '', 'vmsroot', 'N/A', 'N/A', 'email', 'superadmin', 'Active', 'System root user account', '$2y$10$.3p8xvmUqmxNztEzMJQRBesLDwdiRU3xnt/HOcJtsglwsbUk88VTO', 'N/A', 'N/A', 0, 'vmsroot', 0, 0, 0.00, 0, NULL, NULL),
('Volunteer25', '2025-04-30', 'Volley', 'McTear', '123 Dog St', 'Dogville', 'VA', '56748', '9887765543', 'home', '6565651122', 'home', '2025-04-29', 'volly@gmail.com', 'Holly', 'n/a', 'Besty', NULL, 'volunteer', 'Active', NULL, '$2y$10$45gKdbjW78pNKX/5ROtb7eU9OykSCsP/QCyTAvqBtord4J7V3Ywga', 'None', 'None', 0, 'McTear', 0, 0, 10.00, 0, NULL, 'None');

-- --------------------------------------------------------





--
-- Table structure for table `monthly_hours_snapshot`
--

DROP TABLE IF EXISTS `monthly_hours_snapshot`;
CREATE TABLE `monthly_hours_snapshot` (
  `id` int NOT NULL,
  `person_id` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `month_year` date DEFAULT NULL,
  `hours` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `monthly_hours_snapshot`
--

INSERT INTO `monthly_hours_snapshot` (`id`, `person_id`, `month_year`, `hours`) VALUES
(36, 'ameyer3', '2025-03-15', 77),
(37, 'jane_doe', '2025-03-15', 0),
(38, 'john_doe', '2025-03-15', 0),
(39, 'michael_smith', '2025-03-15', 0),
(40, 'vmsroot', '2025-03-15', 0),
(57, 'ameyer3', '2025-04-01', 96),
(58, 'jane_doe', '2025-04-01', 3),
(59, 'john_doe', '2025-04-01', 6),
(60, 'michael_smith', '2025-04-01', 8),
(61, 'vmsroot', '2025-04-01', 0);

-- --------------------------------------------------------


--
-- Indexes for dumped tables
--

--
-- Indexes for table `dbeventpersons`
--
ALTER TABLE `dbeventpersons`
  ADD KEY `FKeventID` (`eventID`),
  ADD KEY `FKpersonID` (`userID`);

--
-- Indexes for table `dbevents`
--
ALTER TABLE `dbevents`
  ADD PRIMARY KEY (`id`);


--
-- Indexes for table `dbpersonhours`
--
ALTER TABLE `dbpersonhours`
  ADD KEY `FkpersonID2` (`personID`),
  ADD KEY `FKeventID3` (`eventID`);

--
-- Indexes for table `dbpersons`
--
ALTER TABLE `dbpersons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `monthly_hours_snapshot`
--
ALTER TABLE `monthly_hours_snapshot`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dbevents`
--
ALTER TABLE `dbevents`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=131;


--
-- AUTO_INCREMENT for table `monthly_hours_snapshot`
--
ALTER TABLE `monthly_hours_snapshot`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
