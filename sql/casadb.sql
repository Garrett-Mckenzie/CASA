START TRANSACTION;
SET FOREIGN_KEY_CHECKS = 0;

/*
Table structure for table `dbevents`
*/
DROP TABLE IF EXISTS `dbevents`; 
CREATE TABLE `dbevents` (
	`id` int NOT NULL AUTO_INCREMENT,
	`name` text,
	`goalAmount` DECIMAL(12,2),
	`date` char(10),
	`startDate` char(10),
	`endDate` char(10), 
	`startTime` char(5) DEFAULT '00:00',
	`endTime` char(5) DEFAULT '00:00',
	`description` text,
	`completed` text,
	`location` text,
	PRIMARY KEY (`id`)
);

/*
Table structure for table `dbpersons`
*/
DROP TABLE IF EXISTS `dbpersons`; 
CREATE TABLE `dbpersons` (
	`id` varchar(256) NOT NULL,
	`name` text,
   	`password` text,
	`accessLevel` int,
	PRIMARY KEY (`id`)
);

/*
Table structure for table `donations`
*/
DROP TABLE IF EXISTS `donations`; 
CREATE TABLE `donations` (
	`amount` DECIMAL(12,2),
	`id` int NOT NULL AUTO_INCREMENT,
	`reason` text,
	`date` char(10),
	`fee` DECIMAL(12,2),
	`thanked` int,
	`eventID` int DEFAULT NULL,
	`donorID` int DEFAULT NULL,
	PRIMARY KEY (`id`),
	FOREIGN KEY (`eventID`) REFERENCES dbevents(`id`),
	FOREIGN KEY (`donorID`) REFERENCES donors(`id`)
);

/*
Table structure for table `files`
*/
DROP TABLE IF EXISTS `files`; 
CREATE TABLE `files` (
	`filePath` varchar(256) NOT NULL,
	`linkToDrive` varchar(256) NOT NULL,
	PRIMARY KEY (`filePath`)
);

/*
Table structure for table `donors`
*/
DROP TABLE IF EXISTS `donors`; 
CREATE TABLE `donors` (
	`id` int NOT NULL AUTO_INCREMENT,
	`first` varchar(30),
	`last` varchar(30),
	`email` varchar(100),
	`zip` varchar(30),
	`city` varchar(30),
	`state` varchar(30),
	`street` varchar(100),
	`phone` varchar(30),
	`gender` varchar(30),
	`notes` text,
	PRIMARY KEY (`id`)
);

/*
Table structure for table `reports`
*/
DROP TABLE IF EXISTS `reports`; 
CREATE TABLE `reports` (
	`id` int NOT NULL AUTO_INCREMENT,
	`reportType` varchar(30),
	`dateRangeStart` char(10),
	`dateRangeEnd` char(10),
	`name` varchar(30),
	`filePath` varchar(256), 
	PRIMARY KEY (`id`),
	FOREIGN KEY (`filePath`) REFERENCES files(`filePath`)
);

/*
Table structure for table `emails`
*/
DROP TABLE IF EXISTS `emails`; 
CREATE TABLE `emails` (
	`id` int NOT NULL AUTO_INCREMENT,
	`message` text,
	`purpose` text,
	PRIMARY KEY (`id`)
);

/*
Table structure for table `associations`
*/
DROP TABLE IF EXISTS `associations`; 
CREATE TABLE `associations` (
	`eventID` int DEFAULT NULL,
	`donationID` int DEFAULT NULL,
	`donorID` int DEFAULT NULL,
	`emailID` int DEFAULT NULL,
	`filePath` varchar(256) DEFAULT NULL,
	FOREIGN KEY (`eventID`) REFERENCES dbevents(`id`),
	FOREIGN KEY (`donationID`) REFERENCES donations(`id`),
	FOREIGN KEY (`donorID`) REFERENCES donors(`id`),
	FOREIGN KEY (`emailID`) REFERENCES emails(`id`),
	FOREIGN KEY (`filePath`) REFERENCES files(`filePath`),
	PRIMARY KEY (`eventID`,`donationID`,`donorID`,`emailID`,`filePath`)
);

INSERT INTO dbpersons (`id`,`name`,`password`,`accessLevel`) VALUES ('vmsroot','SUPER ADMIN', '$2y$10$.3p8xvmUqmxNztEzMJQRBesLDwdiRU3xnt/HOcJtsglwsbUk88VTO', '0');

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;
