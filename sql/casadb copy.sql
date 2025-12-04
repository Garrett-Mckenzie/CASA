START TRANSACTION;
SET FOREIGN_KEY_CHECKS = 0;
-- ----------------------------
INSERT IGNORE INTO `donors` (`id`, `first`, `last`, `email`, `zip`, `city`, `state`, `street`, `phone`, `gender`, `notes`) VALUES
(1, 'Phillip', 'Jenkins', '1philjenkins@gmail.com', '22405', 'Fredericksburg', 'VA', '54 Hamlin Drive', NULL, NULL, 'former colleagues of Janet and Edie'),
(2, 'Jeri', 'Phillips', '4jphillips@comcast.net', '22551', 'Spotsylvania', 'VA', '8432 Battle Park Drive', NULL, NULL, 'CASA volunteer'),
(3, 'Christine', 'Repp', 'abcrepp@yahoo.com', '22401', 'Fredericksburg', 'VA', '1407 Washington Ave', NULL, NULL, 'Christine & Brad Repp established the Repp Family Fund @ Comm Foundation; Brad is CEO at Dynovis & Chris is Comms director'),
(4, 'Adam', 'Fried', 'afried@atlanticbuilders.com', '22401', 'Fredericksburg', 'VA', '425 William Street', NULL, NULL, 'Janet''s cousin'),
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
(23, 'Michael', 'Camber', 'camberm@gmail.com', '22401', 'Fredericksburg', 'VA', '913 Marye Street', NULL, NULL, 'Edie''s friend'),
(24, 'Chad', 'Carter', 'ccarter@crrl.org', '22401', 'Fredericksburg', 'VA', NULL, NULL, NULL, NULL),
(25, 'Carolyn', 'Helfrich', 'chelfrich@verizon.net', '22401', 'Fredericksburg', 'VA', '828 Marye Street', NULL, NULL, NULL),
(26, 'Chrissy', 'McDermott', 'chrissy@coolbreeze.com', '22401', 'Fredericksburg', 'VA', '213 Caroline St', NULL, NULL, 'wife of Dr. Michael McDermott'),
(27, 'Cindy', 'Guerrero', 'cindyg62@comcast.net', '22508', 'Locust Grove', 'VA', '35326 Quail Meadow Lane', NULL, NULL, NULL),
(28, 'Colleen', 'Good', 'cpgood9@gmail.com', '23235', 'Richmond', 'VA', '8314 Charlise Rd.', NULL, NULL, NULL),
(29, 'Dolores "DD"', 'Lecky', 'd@lecky.org', '22407', 'Fredericksburg', 'VA', '11716 Eisenhower Lane', NULL, NULL, NULL),
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
(46, 'Edythe "Edie" & Kirk', 'Evans', 'gatoredie@yahoo.com', '22401', 'Fredericksburg', 'VA', '17 Banbury Court', NULL, NULL, 'CASA staff'),
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
(77, 'Karen', 'Athanasiades', 'karena81@aol.com', NULL, NULL, NULL, NULL, NULL, NULL, 'Sally Rycroft''s sister'),
(78, 'David', 'Athanasiades', 'karena81@aol.com', NULL, NULL, NULL, NULL, NULL, NULL, 'Sally Rycroft''s sister'),
(79, 'Cornelius', 'Walsh', NULL, '22401', 'Fredericksburg', 'VA', '1408 Prince Edward St', NULL, NULL, 'neighbors of Barbara Miller-Richards'),
(80, 'Katherine', 'Walsh', NULL, '22401', 'Fredericksburg', 'VA', '1408 Prince Edward St', NULL, NULL, 'neighbors of Barbara Miller-Richards'),
(81, 'Kathryn', 'Waldron', 'kathryn@kauinc.com', '22408', 'Fredericksburg', 'VA', '4208 Rosemont Lane', '703-898-2010', NULL, NULL),
(82, 'Katrina', 'Masterson', 'katrinalm@comcast.net', '22554', 'Stafford', 'VA', '1 Lakewind Lane', NULL, NULL, NULL),
(83, 'Bud', 'Masterson', 'katrinalm@comcast.net', '22554', 'Stafford', 'VA', '1 Lakewind Lane', NULL, NULL, NULL),
(84, 'Kelley', 'Helmstutler DiDio', 'kelley.didio@uvm.edu', NULL, NULL, NULL, NULL, NULL, NULL, 'Janet''s BFF'),
(85, 'Kelley', 'DiDio', 'kelley.didio@uvm.edu', NULL, NULL, NULL, NULL, NULL, NULL, 'Janet''s BFF'),
(86, 'Kelley', 'Helmstutler', 'kelley.didio@uvm.edu', NULL, NULL, NULL, NULL, NULL, NULL, 'Janet''s BFF'),
(87, 'Kelly & Sam', 'Carniol', 'Kellycarniol@comcast.net', '22408', 'Fredericksburg', 'VA', '10829 Samantha Place', NULL, NULL, 'Adoptive parents and fans of their kids'' CASA, Renee Cinalli.'),
(88, 'Sam', 'Carniol', 'Kellycarniol@comcast.net', '22408', 'Fredericksburg', 'VA', '10829 Samantha Place', NULL, NULL, 'Adoptive parents and fans of their kids'' CASA, Renee Cinalli.'),
(89, 'Kelly', 'Carniol', 'Kellycarniol@comcast.net', '22408', 'Fredericksburg', 'VA', '10829 Samantha Place', NULL, NULL, 'Adoptive parents and fans of their kids'' CASA, Renee Cinalli.'),
(90, 'Kelly', 'Morones', 'kellymorones@gmail.com', '22407', 'Fredericksburg', 'VA', '11301 Crown Court', '540-538-2217', NULL, 'Friend & former colleague of Edie''s'),
(91, 'James', 'Kemp', 'kempjd623@yahoo.com', '22405', 'Fredericksburg', 'VA', '12 Jessica Rae Lane', '540-710-3166', NULL, NULL),
(92, 'Karen', 'Hawkins', 'khawkinsrdn@gmail.com', '23005', 'Ashland', 'VA', '11228 Hill Ridge Court', '804-922-2250', NULL, NULL),
(93, 'Sue', 'Kleman', 'Klemanfamily@verizon.net', '22401', 'Fredericksburg', 'VA', '429 Woodford St.', NULL, NULL, NULL),
(94, 'David', 'Kleman', 'Klemanfamily@verizon.net', '22401', 'Fredericksburg', 'VA', '429 Woodford St.', NULL, NULL, NULL),
(95, 'Karin', 'Beals', 'klowebeals@gmail.com', '22405', 'Fredericksburg', 'VA', '10 Fairfax Circle', '540-848-5036', NULL, NULL),
(96, 'Joan', 'Koriath', 'koriath@sbcglobal.net', '60089', 'Buffalo Grove', 'IL', '842 Dunhill Drive', NULL, NULL, 'Edie''s distant cousin'),
(97, 'Karen & David', 'Johnson', 'ksjohnson422@msn.com', '22401', 'Fredericksburg', 'VA', '1104 Douglas St. ', NULL, NULL, NULL),
(98, 'Karen', 'Johnson', 'ksjohnson422@msn.com', '22401', 'Fredericksburg', 'VA', '1104 Douglas St. ', NULL, NULL, NULL),
(99, 'David', 'Johnson', 'ksjohnson422@msn.com', '22401', 'Fredericksburg', 'VA', '1104 Douglas St. ', NULL, NULL, NULL),
(100, 'Elizabeth', 'LeDoux', 'ledoux2@mac.com', '22401', 'Fredericksburg', 'VA', '1202 Wright Court', '703-338-3982', NULL, NULL),
(101, 'Lucy', 'Walker', 'lewalker2@msn.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(102, 'Liza', 'Fields', 'Lizafields@yahoo.com', '22401', 'Fredericksburg', 'VA', '809 Cornell St', NULL, NULL, 'C-ville area divorce & custody mediator'),
(103, 'Loretta', 'Englman', 'llenglman@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(104, 'LaTonya', 'Turner', 'lnm0303@yahoo.com', '22406', 'Fredericksburg', 'VA', '140 Everett Lane', '402-871-8203', NULL, NULL),
(105, 'Margaret', 'Johnson', 'maggielea03@gmail.com', '22401', 'Fredericksburg', 'VA', '607 Pelham St', NULL, NULL, 'Steve Watkins'' oldest Maggie.'),
(106, 'Mona', 'Albertine', 'Malbertine@cox.net', '22405', 'Fredericksburg', 'VA', '100 Federal Drive', NULL, NULL, 'owner of Jabberwocky books & board member at Va. Partners'),
(107, 'Meghan', 'Pcsolyar', 'mapcsolyar@gmail.com', '22405', 'Fredericksburg', 'VA', '799 Winterberry Drive', NULL, NULL, NULL),
(108, 'Maura', 'Schneider', 'maurawilson@gmail.com; and maurawilsonschneider@gmail.com', '22401', 'Fredericksburg', 'VA', '107 Caroline Street', NULL, NULL, 'Married to Matthew Schneider; writer and graphic artist'),
(109, 'Mary Carter', 'Frackelton', 'MCFrack@aol.com', '22401', 'Fredericksburg', 'VA', NULL, NULL, NULL, 'former educator, established a scholarship thru Comm Foundation; managing partner at Frackelton Block Company'),
(110, 'Mary', 'Frackelton', 'MCFrack@aol.com', '22401', 'Fredericksburg', 'VA', NULL, NULL, NULL, 'former educator, established a scholarship thru Comm Foundation; managing partner at Frackelton Block Company'),
(111, 'Carter', 'Frackelton', 'MCFrack@aol.com', '22401', 'Fredericksburg', 'VA', NULL, NULL, NULL, 'former educator, established a scholarship thru Comm Foundation; managing partner at Frackelton Block Company'),
(112, 'Michele', 'Utt', 'Michele.Utt@gmail.com', '22405', 'Fredericksburg', 'VA', '304 Ingleside Drive', NULL, NULL, 'CASA volunteer'),
(113, 'Michelle', 'Purdy', 'michellepurdy@msn.com', '22408', 'Fredericksburg', 'VA', '10821 Stacy Run', NULL, NULL, NULL),
(114, 'Emily', 'Simpson', 'Mlecatherine@gmail.com', '22401', 'Fredericksburg', 'VA', '805 Caroline st', NULL, NULL, 'Jan''s friend'),
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
(133, 'Sabina', 'Weitzman', 's.weitzman@verizon.net', '22401', 'Fredericksburg', 'VA', '913 Marye St', NULL, NULL, 'Edie''s friend'),
(134, 'Susan', 'Barber', 'sbarber100@hotmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(135, 'Sean', 'Bonney', 'sbonney@crrl.org', '22401', 'Fredericksburg', 'VA', '909 Brompton Street', NULL, NULL, 'Janet''s friend'),
(136, 'Stacey', 'Strentz', 'sns@sgclawyers.com', '22401', 'Fredericksburg', 'VA', '620 Princess Anne St', NULL, NULL, NULL),
(137, 'George', 'Solley', 'solleyg@cox.net', '22401', 'Fredericksburg', 'VA', '1303 Caroline St', NULL, NULL, 'former city councilman'),
(138, 'Susan', 'Neal', 'ssneal@earthlink.net', '22407', 'Fredericksburg', 'VA', '5517 River Road', NULL, NULL, 'former colleague of Edie and Jan'),
(139, 'Laura', 'Joy', 'starseeker111@hotmail.com', '22407', 'Fredericksburg', 'VA', '7 Saint Patrick Street', NULL, NULL, 'friends with Jan and the Neustatters'),
(140, 'Stephanie', 'Sinclair Hoben', 'stephsinclair@mac.com', '10567', 'Cortlandt Manor', 'NY', '73 South Hill Road', NULL, NULL, 'Edie''s BFF'),
(141, 'Stephanie', 'Hoben', 'stephsinclair@mac.com', '10567', 'Cortlandt Manor', 'NY', '73 South Hill Road', NULL, NULL, 'Edie''s BFF'),
(142, 'Stephanie', 'Sinclair', 'stephsinclair@mac.com', '10567', 'Cortlandt Manor', 'NY', '73 South Hill Road', NULL, NULL, 'Edie''s BFF'),
(143, 'Scout', 'Tufankjian', 'stufankjian@gmail.com', '11215', 'Brooklyn', 'NY', '433 7th Ave, Apt 2', NULL, NULL, 'Edie''s friend'),
(144, 'Susan', 'Park', 'susanduncanpark@gmail.com', '22485', 'King George', 'VA', '10064 Francis Folsom Drive', NULL, NULL, NULL),
(145, 'Stephen', 'Watkins', 'swatkins000@gmail.com', '22401', 'Fredericksburg', 'VA', '1206 Walker Drive', NULL, NULL, 'former CASA volunteer'),
(146, 'Sydney', 'Simpson', 'sydneyjsimpson@gmail.com', '22401', 'Fredericksburg', 'VA', '909 Brompton street', NULL, NULL, 'Jan''s friend'),
(147, 'Amy', 'Umberger', 'tchakid2read@hotmail.com', '22508', 'Locust Grove', 'VA', '32119 Wilderness Farms Road', NULL, NULL, 'friend of Janet Watkins from UMW; former reading specialist in Spotsy schools and now in Orange Co.;'),
(148, 'Jessica', 'Bloomfield', 'thebloomfields@verizon.net', '22553', 'Spotsylvania', 'VA', '10413 Dana Court', '540-850-1240', NULL, 'Friend of Edie''s'),
(149, 'Timothy', 'Poe', 'timothyryanpoe@gmail.com', '22401', 'Fredericksburg', 'VA', '608 Spottswood St', NULL, NULL, 'realtor?'),
(150, 'Todd', 'Rump', 'todd.rump@protonmail.com', '22408', 'Fredericksburg', 'VA', '13103 Willow Point Drive', NULL, NULL, NULL),
(151, 'Trina', 'Parsons', 'tparsons@verizon.net', '22405', 'Fredericksburg', 'VA', '92 Boscobel Road', NULL, NULL, 'husband is Jerry M. Parsons Jr.'),
(152, 'Tracy', 'Lloyd', 'tracyannlloyd@gmail.com', '22408', 'Fredericksburg', 'VA', '12114 Kingswood Blvd.', '540-809-2308', NULL, NULL),
(153, 'David', 'Bohmke', 'vabohmke@aol.com', '22405', 'Fredericksburg', 'VA', '416 Collingwood Drive', NULL, NULL, 'Sr. VP at Union Bank & Trust; wife Meg Bohmke was on Stafford BOS'),
(154, 'Sandra', 'Mahaffey', 'veryveggie@gmail.com', '04103', 'Portland', 'ME', '80 Hennessey Drive', NULL, NULL, 'former colleague of Edie and Jan; moved from 121 Ashby St., 22401 to Maine in 2019'),
(155, 'Tiffany', 'Villanueva', 'Villantc@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, 'is a friend of volunteer Felicia Agnew'),
(156, 'Wendy', 'Cannon', 'wendyccannon@gmail.com', '22407', 'Fredericksburg', 'VA', '6600 Hot Spring Lane', NULL, NULL, 'Janet''s friend'),
(157, 'William', 'Triplett', 'wmtriple@aol.com', '22026', 'Dumfries', 'VA', '18088 Red Mulberry Road', '540-429-6137', NULL, NULL),
(158, 'Bruce', 'Sheaffer', 'wsheaffer@me.com', NULL, NULL, NULL, 'wendy_sheaffer@hotmail.com', NULL, NULL, NULL),
(159, 'John & Rosemarie', 'McKeown', NULL, '22401', 'Fredericksburg', 'VA', '910 Monroe St.', NULL, NULL, 'John is a board member w/ Fbg Symphony Orchestra'),
(160, 'Rosemarie', 'McKeown', NULL, '22401', 'Fredericksburg', 'VA', '910 Monroe St.', NULL, NULL, 'John is a board member w/ Fbg Symphony Orchestra'),
(161, 'John', 'McKeown', NULL, '22401', 'Fredericksburg', 'VA', '910 Monroe St.', NULL, NULL, 'John is a board member w/ Fbg Symphony Orchestra'),
(162, 'Judith', 'Downing', NULL, '17815', 'Bloomsburg', 'PA', '33 Duke of Gloucester', NULL, NULL, NULL),
(163, 'Jill', 'Carroll', NULL, '83713', 'Boise', 'ID', '1071 N. Silver Ash Ave.', NULL, NULL, 'child & family therapist?'),
(164, 'Jerald & Judith', 'Caira', NULL, '14450', 'Fairport', 'NY', '38 Tea Olive Lane', NULL, NULL, 'In memory of Sally Rycroft (Judy''s sister)'),
(165, 'Jerald', 'Caira', NULL, '14450', 'Fairport', 'NY', '38 Tea Olive Lane', NULL, NULL, 'In memory of Sally Rycroft (Judy''s sister)'),
(166, 'Judith', 'Caira', NULL, '14450', 'Fairport', 'NY', '38 Tea Olive Lane', NULL, NULL, 'In memory of Sally Rycroft (Judy''s sister)'),
(167, 'Bob & Harriet', 'Burch', NULL, '22406', 'Falmouth', 'VA', '304 Poplar Road', NULL, NULL, NULL),
(168, 'Harriet', 'Burch', NULL, '22406', 'Falmouth', 'VA', '304 Poplar Road', NULL, NULL, NULL),
(169, 'Bob', 'Burch', NULL, '22406', 'Falmouth', 'VA', '304 Poplar Road', NULL, NULL, NULL),
(170, 'Community Baptist', 'Church', NULL, '22405', 'Falmouth', 'VA', '44 Lorenzo Drive', NULL, NULL, 'Teresa Bullock is in a Women''s Missions Group there.'),
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
(194, 'Anita', 'Marshall', NULL, '22401', 'Fredericksburg', 'VA', '2700 Cowan Blvd. #126', NULL, NULL, 'Janet Watkins'' mother'),
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
(207, 'Kurt & Linda', 'Glaeser', NULL, '22401', 'Fredericksburg', 'VA', '705 Mary Ball St.', NULL, NULL, 'Kurt is retired UMW coach of women''s soccer and men''s lacrosse'),
(208, 'Linda', 'Glaeser', NULL, '22401', 'Fredericksburg', 'VA', '705 Mary Ball St.', NULL, NULL, 'Kurt is retired UMW coach of women''s soccer and men''s lacrosse'),
(209, 'Kurt', 'Glaeser', NULL, '22401', 'Fredericksburg', 'VA', '705 Mary Ball St.', NULL, NULL, 'Kurt is retired UMW coach of women''s soccer and men''s lacrosse'),
(210, 'Anne & Carl', 'Little', NULL, '22401', 'Fredericksburg', 'VA', '726 William Street', NULL, NULL, 'Jan''s friend; Tree Fbg'),
(211, 'Carl', 'Little', NULL, '22401', 'Fredericksburg', 'VA', '726 William Street', NULL, NULL, 'Jan''s friend; Tree Fbg'),
(212, 'Anne', 'Little', NULL, '22401', 'Fredericksburg', 'VA', '726 William Street', NULL, NULL, 'Jan''s friend; Tree Fbg'),
(213, 'Carol & Joseph', 'Dreiss', NULL, '22401', 'Fredericksburg', 'VA', '809 Sylvania Ave.', NULL, NULL, 'Joe is a prof of art history at UMW'),
(214, 'Joseph', 'Dreiss', NULL, '22401', 'Fredericksburg', 'VA', '809 Sylvania Ave.', NULL, NULL, 'Joe is a prof of art history at UMW'),
(215, 'Carol', 'Dreiss', NULL, '22401', 'Fredericksburg', 'VA', '809 Sylvania Ave.', NULL, NULL, 'Joe is a prof of art history at UMW'),
(216, 'John and Marilyn', 'Farrington', NULL, '22401', 'Fredericksburg', 'VA', '900 Cornell Street', NULL, NULL, 'Marilyn is a trustee at St. George''s Episcopal and John is Senior Financial Advisor at Merrill Lynch Wealth Management'),
(217, 'Benevolent & Protective Order of Elks, Fredericksburg Lodge', '875', NULL, '22408', 'Fredericksburg', 'VA', 'c/o The Bullocks, 405 Lorraine Ave.', NULL, NULL, NULL),
(218, 'Debbie', 'Cheshire', NULL, '65101', 'Jefferson City', 'MO', '1719 Green Meadow Drive', NULL, NULL, 'Husband Steve passed in 2016; their son, Jay, died at age 52 in Oct. 2023.'),
(219, 'Lyla M.', 'Hogle', NULL, '20707', 'Laurel', 'MD', '14408 Mayfair Drive', NULL, NULL, 'Please accept this donation in memory of Sally A. Rycroft, Robert Rycroft''s wife.'),
(220, 'Lyla', 'Hogle', NULL, '20707', 'Laurel', 'MD', '14408 Mayfair Drive', NULL, NULL, 'Please accept this donation in memory of Sally A. Rycroft, Robert Rycroft''s wife.'),
(221, 'Doug & Peggy', 'Pope', NULL, '22508', 'Locust Grove', 'VA', '219 Harpers Ferry Drive', NULL, NULL, 'Random end-of-year gift, though Jill said they may have given before'),
(222, 'Peggy', 'Pope', NULL, '22508', 'Locust Grove', 'VA', '219 Harpers Ferry Drive', NULL, NULL, 'Random end-of-year gift, though Jill said they may have given before'),
(223, 'Doug', 'Pope', NULL, '22508', 'Locust Grove', 'VA', '219 Harpers Ferry Drive', NULL, NULL, 'Random end-of-year gift, though Jill said they may have given before'),
(224, 'Ellen', 'Ashwell', NULL, '23117', 'Mineral', 'VA', '6051 Lost Cove Drive', NULL, NULL, 'volunteered 2001-2012'),
(225, 'Penny & Ron', 'Saulnier', NULL, '28560', 'New Bern', 'NC', '226 New Street', NULL, NULL, NULL),
(226, 'Penny', 'Saulnier', NULL, '28560', 'New Bern', 'NC', '226 New Street', NULL, NULL, NULL),
(227, 'Ron', 'Saulnier', NULL, '28560', 'New Bern', 'NC', '226 New Street', NULL, NULL, NULL),
(228, 'Elizabeth', 'Parsnick', NULL, '14305', 'Niagara Falls', 'NY', '4217 McKoon Ave.', NULL, NULL, NULL),
(229, 'John and Barbara', 'Jesz', NULL, '14305', 'Niagara Falls', 'NY', '6937 Colonial Drive', NULL, NULL, 'Barbara is a retired health dept employee in NY; the couple''s son died in Oct. 2020 at age 49'),
(230, 'Barbara', 'Jesz', NULL, '14305', 'Niagara Falls', 'NY', '6937 Colonial Drive', NULL, NULL, 'Barbara is a retired health dept employee in NY; the couple''s son died in Oct. 2020 at age 49'),
(231, 'John', 'Jesz', NULL, '14305', 'Niagara Falls', 'NY', '6937 Colonial Drive', NULL, NULL, 'Barbaras partner; the couple''s son died in Oct. 2020 at age 49'),
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
(246, 'Cookie & Steve', 'Gross', NULL, '33596', 'Valrico', 'FL', '2735 Brookville Drive', NULL, NULL, 'Edie''s parents'),
(247, 'Steve', 'Gross', NULL, '33596', 'Valrico', 'FL', '2735 Brookville Drive', NULL, NULL, 'Edie''s parents'),
(248, 'Cookie', 'Gross', NULL, '33596', 'Valrico', 'FL', '2735 Brookville Drive', NULL, NULL, 'Edie''s parents'),
(249, 'Integrated Direct', 'Marketing', NULL, '20036', 'Washington', 'D.C.', '1250 Connecticut Ave. NW, #700', '202/517-7162', NULL, NULL),
(250, 'Michael P.', 'Wiedemer', NULL, '37398', 'Winchester', 'TN', '362 Oak Circle', NULL, NULL, NULL),
(251, 'Michael', 'Wiedemer', NULL, '37398', 'Winchester', 'TN', '362 Oak Circle', NULL, NULL, NULL),
(252, 'Laurel Fensterer and Timothy', 'Davis', NULL, '22580', 'Woodford', 'VA', '3418 Fredericksburg Turnpike', NULL, NULL, NULL),
(253, 'Timothy', 'Davis', NULL, '22580', 'Woodford', 'VA', '3418 Fredericksburg Turnpike', NULL, NULL, NULL),
(254, 'Laurel', 'Fensterer', NULL, '22580', 'Woodford', 'VA', '3418 Fredericksburg Turnpike', NULL, NULL, NULL),
(255, 'Alissa', 'Merlo', 'agmm1830@gmail.com', '33028', 'Pembroke Pines', 'FL', '1261 NW 159th Ave.', NULL, NULL, 'Edie''s cousin'),
(256, 'Stephanie', 'Yushchak', 'fdcsky23@gmail.com', '22407', 'Fredericksburg', 'VA', '10620 Courthouse Road', NULL, NULL, NULL),
(257, 'Melissa', 'Repetski', 'mrepetski@yahoo.com', '22309', 'Alexandria', 'VA', '8329 Orange Court', NULL, NULL, 'In honor of Cookie Gross, Edie''s mom and a former teacher of her kids'),
(258, 'Teresa', 'Bullock', 'tcb405@cox.net', '22408', 'Fredericksburg', 'VA', '405 Lorraine Ave.', NULL, NULL, NULL),
(259, 'Cindy', 'Simpson', 'cindy@goldensfam.com', '22554', 'Stafford', 'VA', '60 Brush Everard Ct.', NULL, NULL, NULL),
(260, 'Gayle', 'Dillon', 'gddillon@gmail.com', '23192', 'Montpelier', 'VA', '15094 Hawks Climb Lane', NULL, NULL, NULL),
(261, 'Ruth', 'Golmant', 'rgolmant@verizon.net', '22554', 'Stafford', 'VA', '3 Bloomington Lane', NULL, NULL, 'Friend of Edie''s'),
(262, 'Amy', 'Umble', 'umbleamy@gmail.com', '22407', 'Fredericksburg', 'VA', '611 Halleck Street', NULL, NULL, 'Friend and former colleague of Edie''s; works at RACSB'),
(263, 'Kathy', 'Crutcher', 'kathy@shoutmousepress.org', '20009', 'Washington', 'D.C.', '1735 17th St NW', NULL, NULL, NULL),
(264, 'Martha', 'Moses', 'martha.moses@gmail.com', '85718', 'Tucson', 'AZ', '7534 N Mystic Canyon Drive', NULL, NULL, 'Longtime family friend of Edie'),
(265, 'Ana', 'Valle-Greene', NULL, '28412', 'Wilmington', 'NC', '6340 Branford Road', NULL, NULL, NULL),
(266, 'Ana', 'Valle', NULL, '28412', 'Wilmington', 'NC', '6340 Branford Road', NULL, NULL, NULL),
(267, 'Ana', 'Greene', NULL, '28412', 'Wilmington', 'NC', '6340 Branford Road', NULL, NULL, NULL),
(268, 'Elizabeth', 'Stokes', 'Sleeplessinfla@aol.com', NULL, 'Florida', NULL, NULL, NULL, NULL, 'Saw a post on Facebook, maybe from Cookie Gross (Edie''s mom), and donated.'),
(269, 'Jessica', 'Varner', 'jessica.varner@dss.virginia.gov', NULL, NULL, NULL, NULL, NULL, NULL, 'Fredericksburg CPS worker'),
(270, 'Shondella', 'Murray', 'shondellamarie@gmail.com', '22407', 'Fredericksburg', 'VA', '6905 Hawthorne Woods Circle', NULL, NULL, 'CASA board member and former volunteers'),
(271, 'Karen', 'Gross', 'kgross1605@aol.com', '33141', 'Miami Beach', 'FL', '1830 Bay Drive', NULL, NULL, 'Edie''s aunt'),
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
(284, 'Michael & Marietta', 'D''Ostilio', NULL, '22406', 'Fredericksburg', 'VA', '31 Sanibel Drive', NULL, NULL, 'In memory of Bobby Anderson'),
(285, 'Marietta', 'D''Ostilio', NULL, '22406', 'Fredericksburg', 'VA', '31 Sanibel Drive', NULL, NULL, 'In memory of Bobby Anderson'),
(286, 'Michael', 'D''Ostilio', NULL, '22406', 'Fredericksburg', 'VA', '31 Sanibel Drive', NULL, NULL, 'In memory of Bobby Anderson');

INSERT IGNORE INTO `donations` (`date`, `amount`, `reason`, `eventID`, `donorID`, `fee`, `thanked`) VALUES
-- Donor ID 1: Phillip Jenkins
('01/01/2015', 50.00, '2014, 2015 and 2016 Community Give', 3, 1, 0.00, 1),

-- Donor ID 2: Jeri Phillips
('01/01/2016', 50.00, 'Community Give; $100 in 2024 Giving Tuesday', 3, 2, 0.00, 1),

-- Donor ID 3: Christine Repp
('05/05/2015', 25.00, 'Community Give: gave $25 in 2014, 2015 & 2016', 3, 3, 0.00, 1),

-- Donor ID 4: Adam Fried
('01/01/2015', 1100.00, '$100 in 2014 Community Give; $1,100 in 2015; $1,000 in 2016 Community Give', 3, 4, 0.00, 1),

-- Donor ID 5: Dr. Maha Alattar
('05/01/2014', 100.00, 'In response to Community Give email from Janet; also $100 in 2015', 3, 5, 0.00, 1),

-- Donor ID 6/7: Angela Glidden
('01/01/2024', 100.00, 'Step Up for CASA Kids team -- 2024 Downtown Mile; CASA volunteer; $100 2024 Giving Tues.', NULL, 6, 0.00, 0), -- Using main row data, amount is often null. Actual GT $100 applied later.

-- Donor ID 8: Amy Faulkner-Hart
('05/05/2015', 25.00, 'Community Give in 2014 & 2015', NULL, 8, 0.00, 0),

-- Donor ID 9: Amy Ridderhof
('01/01/2014', 50.00, 'Community Give: $50 in 2014; $30 in 2015; $25 in 2016; in 2020 gave $25 on Giving Tuesday', NULL, 9, 0.00, 0),

-- Donor ID 10: Brian Pessolano
(NULL, NULL, '2024 Downtown Mile donor', NULL, 10, 0.00, 0),

-- Donor ID 11: Barbara & Guy Miller-Richards
('03/07/2020', 50.00, '$50 in 2020 In memory of Sally Rycroft; $250 during 2016 Community Give; $200 in 2021 Giving Tuesday; $100 in 2024 Giving Tues.', NULL, 11, 0.00, 0),

-- Donor ID 17: Tina Glass
('01/01/2014', 25.00, 'Community Give', NULL, 17, 0.00, 0),

-- Donor ID 18: Betsy Glassie
('01/01/2016', 50.00, 'Community Give', NULL, 18, 0.00, 0),

-- Donor ID 19: Lesa Aylor
(NULL, NULL, '2024 Downtown Mile donor', NULL, 19, 0.00, 0),

-- Donor ID 20: Bobby Anderson
('01/01/2017', 1200.00, '$225 in 2015; $1,000 in 2016 Community Give; $1,200 in 2017', NULL, 20, 0.00, 0),

-- Donor ID 21: William Turner
('03/24/2016', 200.00, 'Paypal Giving', NULL, 21, 0.00, 0),

-- Donor ID 22: Cat Paccasassi
('01/01/2015', 35.00, 'Gave $40 during 2014 Community Give', NULL, 22, 0.00, 0),

-- Donor ID 23: Michael Camber
('01/01/2015', 25.00, '$250 in Jan. 2025', NULL, 23, 0.00, 0),

-- Donor ID 24: Chad Carter
('01/01/2014', 50.00, 'Community Give', NULL, 24, 0.00, 0),

-- Donor ID 25: Carolyn Helfrich
(NULL, NULL, '2024 Downtown Mile donor', NULL, 25, 0.00, 0),

-- Donor ID 26: Chrissy McDermott
('01/01/2015', 25.00, '', NULL, 26, 0.00, 0),

-- Donor ID 27: Cindy Guerrero
('01/01/2016', 30.00, 'Community Give', NULL, 27, 0.00, 0),

-- Donor ID 28: Colleen Good
('11/28/2017', 25.00, '', NULL, 28, 0.00, 0),

-- Donor ID 29: Dolores "DD" Lecky
('05/05/2015', 25.00, 'Community Give 2015: $25', NULL, 29, 0.00, 0),

-- Donor ID 32: Danny Fields
('01/01/2014', 200.00, 'Community Give', NULL, 32, 0.00, 0),

-- Donor ID 33: Daniel Long
('01/01/2024', 0.00, '2024 Downtown Mile donor', NULL, 33, 0.00, 0),

-- Donor ID 34: Delise Dickard
('05/05/2015', 50.00, '$50 in 2015 & 2016 Community Give', NULL, 34, 0.00, 0),

-- Donor ID 35: Denise Freeman
('01/01/2024', 0.00, 'Step Up for CASA Kids team -- 2024 Downtown Mile', NULL, 35, 0.00, 0),

-- Donor ID 36: Deborah Cook
('01/01/2024', 0.00, '2024 Downtown Mile donor', NULL, 36, 0.00, 0),

-- Donor ID 37: Dennis Keffer
('01/01/2016', 25.00, 'Community Give', NULL, 37, 0.00, 0),

-- Donor ID 38: Drs. Mike and Pat Stevens
('12/05/2017', 2000.00, 'gave 10K in May 2016; and $2,000 in 2017; $500 in 2016 Community Give', NULL, 38, 0.00, 0),

-- Donor ID 41: Elizabeth Conn
('01/01/2016', 50.00, 'Community Give', NULL, 41, 0.00, 0),

-- Donor ID 42: Elizabeth Brooks/Liz Freeman
('12/01/2023', 100.00, '2023 Giving Tues; $100 2024 Giving Tues', NULL, 42, 0.00, 0),

-- Donor ID 45: Becky Farris
('12/01/2023', 20.00, '2023 Giving Tues', NULL, 45, 0.00, 0),

-- Donor ID 46: Edythe "Edie" & Kirk Evans
('05/05/2015', 25.00, 'Community Give in 2014, 2015 & 2016', NULL, 46, 0.00, 0),

-- Donor ID 50: Beth McClain
('01/01/2015', 40.00, '', NULL, 50, 0.00, 0),

-- Donor ID 51: Bonita (Bonnie) Darnell
('01/01/2015', 100.00, '$100 during 2015 and 2016 Community Give', NULL, 51, 0.00, 0),

-- Donor ID 53: Grant Smith
(NULL, NULL, '2024 Downtown Mile donor', NULL, 53, 0.00, 0),

-- Donor ID 54: Hava Marneweck
(NULL, NULL, '2024 Downtown Mile donor', NULL, 54, 0.00, 0),

-- Donor ID 55: Mari Kelly
('05/05/2015', 25.00, 'Community Give: $525 in 2014, $650 in 2015 & $1,100 in 2016', NULL, 55, 0.00, 0),

-- Donor ID 56: Ilana Boivie
('02/13/2020', 100.00, 'I would like my donation to be listed in memory of Sally A. Rycroft', NULL, 56, 0.00, 0),

-- Donor ID 57: Admiral Julie Treanor
('02/04/2017', 400.00, 'also gave $250 in 2015; $200 in 2023; $100 in 2024 GivTues', NULL, 57, 0.00, 0),

-- Donor ID 59/60: Jane McDonald
(NULL, NULL, 'Step Up for CASA Kids team -- 2024 Downtown Mile; Board member; $50 for 2024 Giving Tues', NULL, 59, 0.00, 0),

-- Donor ID 61: Janet Watkins
('05/05/2015', 25.00, 'Community Give: $25 in 2014; $75 in 2015; and $25 in 2016; 2024 Downtown Mile donor; $50 for 2024 Giving Tues', NULL, 61, 0.00, 0),

-- Donor ID 62: John Bosserman
('01/01/2023', 2000.00, '2023 EOY gift & 2024 EOY; 2024 Downtown Mile donor', NULL, 62, 0.00, 0),

-- Donor ID 65: Joseph Di Bella
('02/08/2020', 50.00, 'In memory of Sally Rycroft', NULL, 65, 0.00, 0),

-- Donor ID 66: Joann Farris
(NULL, NULL, '2024 Downtown Mile donor', NULL, 66, 0.00, 0),

-- Donor ID 67: John Mayer
(NULL, NULL, 'Step Up for CASA Kids team -- 2024 Downtown Mile', NULL, 67, 0.00, 0),

-- Donor ID 68: Joseph Vines
(NULL, NULL, '2024 Downtown Mile donor', NULL, 68, 0.00, 0),

-- Donor ID 69: Jacqueline Burns
(NULL, NULL, '2025 Downtown Mile donor', NULL, 69, 0.00, 0),

-- Donor ID 70: John Calabrese
(NULL, NULL, 'Step Up for CASA Kids team -- 2024 Downtown Mile', NULL, 70, 0.00, 0),

-- Donor ID 71: Jane & Pete Kolakowski
('12/17/2018', 200.00, '$50 in 2014 Community Give; $200 in 2018; $500 in 2021', NULL, 71, 0.00, 0),

-- Donor ID 77: Karen & David Athanasiades
('02/13/2020', 250.00, 'In memory of Sally Rycroft from Athanasiades Family', NULL, 77, 0.00, 0),

-- Donor ID 79/80: Katherine & Cornelius Walsh
('01/01/2015', 100.00, '', NULL, 79, 0.00, 0),

-- Donor ID 81: Kathryn Waldron
(NULL, NULL, '2024 Downtown Mile donor', NULL, 81, 0.00, 0),

-- Donor ID 82: Katrina & Bud Masterson
('05/10/2014', 350.00, 'Bellydance event; also gave $500 in 2015; $2,050 in 2017; gave $2,500 in 2018; $1,600 in 2020; $250 2024 Giving Tues', NULL, 82, 0.00, 0),

-- Donor ID 84: Kelley Helmstutler DiDio
('07/08/2018', 50.00, '', NULL, 84, 0.00, 0),

-- Donor ID 87: Kelly & Sam Carniol
('01/01/2016', 100.00, 'In honor and with love for our amazing CASA Renee Cinalli, Community Give; $100 on 2024 Giving Tues', NULL, 87, 0.00, 0),

-- Donor ID 90: Kelly Morones
(NULL, NULL, '2024 Downtown Mile donor & $50 at Giving Tuesday 2024', NULL, 90, 0.00, 0),

-- Donor ID 91: James Kemp
(NULL, NULL, '2024 Downtown Mile donor and member of Step Up for CASA Kids team', NULL, 91, 0.00, 0),

-- Donor ID 92: Karen Hawkins
(NULL, NULL, '2024 Downtown Mile donor', NULL, 92, 0.00, 0),

-- Donor ID 93: Sue & David Kleman
('01/01/2016', 50.00, 'Community Give', NULL, 93, 0.00, 0),

-- Donor ID 95: Karin Beals
(NULL, NULL, '2024 Downtown Mile donor', NULL, 95, 0.00, 0),

-- Donor ID 96: Joan Koriath
('01/01/2024', 50.00, '2024 Downtown Mile donor and Giving Tuesday', NULL, 96, 0.00, 0),

-- Donor ID 97: Karen & David Johnson
('02/20/2020', 100.00, 'Enclosed is a $100 donation in memory of Sally Rycroft. Thank you. $50 in 2014 In memory of Linda Pisenti; $100 in 2017; $100 in 2020 in memory of Sally Rycroft', NULL, 97, 0.00, 0),

-- Donor ID 100: Elizabeth LeDoux
(NULL, NULL, '2024 Downtown Mile donor', NULL, 100, 0.00, 0),

-- Donor ID 101: Lucy Walker
('02/05/2017', 50.00, '', NULL, 101, 0.00, 0),

-- Donor ID 102: Liza Fields
('01/01/2014', 50.00, 'Community Give', NULL, 102, 0.00, 0),

-- Donor ID 103: Loretta Englman
(NULL, NULL, 'Step Up for CASA Kids team -- 2024 Downtown Mile', NULL, 103, 0.00, 0),

-- Donor ID 104: LaTonya Turner
(NULL, NULL, '2024 Downtown Mile donor', NULL, 104, 0.00, 0),

-- Donor ID 105: Margaret Johnson
('01/01/2015', 20.00, '', NULL, 105, 0.00, 0),

-- Donor ID 106: Mona Albertine
('01/01/2015', 250.00, '2015 Community Give; $25 during 2014 Community Give; $100 in 2016 in honor of Linda Pisenti', NULL, 106, 0.00, 0),

-- Donor ID 107: Meghan Pcsolyar
('01/01/2016', 25.00, 'Community Give: $25 in 2015 and 2016', NULL, 107, 0.00, 0),

-- Donor ID 108: Maura Schneider
('01/01/2014', 25.00, 'Community Give: $25 in 2014, 2015 & 2016', NULL, 108, 0.00, 0),

-- Donor ID 109: Mary Carter Frackelton
('01/01/2015', 25.00, '', NULL, 109, 0.00, 0),

-- Donor ID 112: Michele Utt
('01/01/2015', 200.00, 'Also $200 in 2024 Giving Tues', NULL, 112, 0.00, 0),

-- Donor ID 113: Michelle Purdy
('01/01/2016', 100.00, 'Community Give', NULL, 113, 0.00, 0),

-- Donor ID 114: Emily Simpson
('05/05/2015', 50.00, 'Community Give: $150 in 2014; $200 in 2015; $150 in 2016', NULL, 114, 0.00, 0),

-- Donor ID 115: Morris Mochel
(NULL, NULL, '2025 Downtown Mile donor', NULL, 115, 0.00, 0),

-- Donor ID 116/118: Laura Moyer & Jim Hall
('11/27/2018', 200.00, 'also gave $650 in 2017 & $150 in 2020 on Giving Tuesday; $250 in 2021 Giving Tuesday; & $200 on Giv Tues 2024', NULL, 116, 0.00, 0),
('11/27/2018', 200.00, 'also gave $650 in 2017 & $150 in 2020 on Giving Tuesday; $250 in 2021 Giving Tuesday; & $200 on Giv Tues 2024', NULL, 119, 0.00, 0),

-- Donor ID 121: Maureen Kennedy
('02/06/2017', 2000.00, '', NULL, 121, 0.00, 0),

-- Donor ID 122: Nancy Krause
('01/01/2016', 70.00, 'Community Give: $25 in 2014 & 2015; $70 in 2016; $100 in 2018; $100 in 2020', NULL, 122, 0.00, 0),

-- Donor ID 123: Nancy Pcsolyar
('05/05/2015', 50.00, 'Community Give: $250 in 2015; $50 in 2016', NULL, 123, 0.00, 0),

-- Donor ID 124: Nancy & Ron Hicks
('01/01/2015', 100.00, '', NULL, 124, 0.00, 0),

-- Donor ID 126: Nancy Palmieri
('02/12/2020', 25.00, '', NULL, 126, 0.00, 0),

-- Donor ID 127: Donna Boyd
('01/01/2015', 25.00, '', NULL, 127, 0.00, 0),

-- Donor ID 128: Paul Wingeart
(NULL, NULL, '2024 Downtown Mile donor', NULL, 128, 0.00, 0),

-- Donor ID 129: Robert Jones
('01/01/2014', 50.00, 'Community Give', NULL, 129, 0.00, 0),

-- Donor ID 130: Robert Lane
(NULL, NULL, '', NULL, 130, 0.00, 0),

-- Donor ID 131: Bob Rycroft
('01/01/2017', 1350.00, '$1,350 in 2017 & $250 in 2020 in honor of Sally Rycroft', NULL, 131, 0.00, 0),

-- Donor ID 132: Rachel Gredler
('01/01/2024', 0.00, 'Step Up for CASA Kids team -- 2024 Downtown Mile', NULL, 132, 0.00, 0),

-- Donor ID 133: Sabina Weitzman
('01/01/2016', 25.00, 'Community Give', NULL, 133, 0.00, 0),

-- Donor ID 134: Susan Barber
('07/14/2018', 50.00, '', NULL, 134, 0.00, 0),

-- Donor ID 135: Sean Bonney
('05/05/2015', 25.00, 'Community Give; $51.52 during 2024 Giving Tuesday', NULL, 135, 0.00, 0),

-- Donor ID 136: Stacey Strentz
('01/01/2024', NULL, 'Sponsored Downtown Mile 2024 with law partners at Strentz, Greene & Coleman LPC', NULL, 136, 0.00, 0),

-- Donor ID 137: George Solley
('01/01/2016', 100.00, 'Community Give: $50 in 2015 & $100 in 2016', NULL, 137, 0.00, 0),

-- Donor ID 138: Susan Neal
('11/28/2017', 100.00, 'also gave $25 in 2015; $50 in 2024 Giving Tues.', NULL, 138, 0.00, 0),

-- Donor ID 139: Laura Joy
('01/01/2016', 10.00, 'Community Give', NULL, 139, 0.00, 0),

-- Donor ID 140: Stephanie Sinclair Hoben
('01/01/2016', 100.00, 'Community Give; $225 in 2024 Giving Tuesday', NULL, 140, 0.00, 0),

-- Donor ID 143: Scout Tufankjian
('01/01/2014', 35.00, 'Community Give', NULL, 143, 0.00, 0),

-- Donor ID 144: Susan Park
('01/01/2016', 25.00, 'Community Give; gave $25 in 2018', NULL, 144, 0.00, 0),

-- Donor ID 145: Stephen Watkins
('05/05/2015', 50.00, 'Community Give: $25 in 2014; $100 in 2015; $50 in 2016', NULL, 145, 0.00, 0),

-- Donor ID 146: Sydney Simpson
('05/05/2015', 25.00, 'Community Give: $25 in 2014; $90 in 2015', NULL, 146, 0.00, 0),

-- Donor ID 147: Amy Umberger
('01/01/2016', 40.00, 'Donated $40 in honor of Sara and other deserving kids in 2016 Community Give; and $25 in 2015', NULL, 147, 0.00, 0),

-- Donor ID 148: Jessica Bloomfield
(NULL, 154.00, '2024 Downtown Mile donor & Giving Tuesday', NULL, 148, 0.00, 0),

-- Donor ID 149: Timothy Poe
('01/01/2015', 25.00, '', NULL, 149, 0.00, 0),

-- Donor ID 150: Todd Rump
(NULL, NULL, '2024 Downtown Mile donor', NULL, 150, 0.00, 0),

-- Donor ID 151: Trina Parsons
('01/01/2014', 25.00, 'Community Give', NULL, 151, 0.00, 0),

-- Donor ID 152: Tracy Lloyd
(NULL, NULL, '2024 Downtown Mile donor', NULL, 152, 0.00, 0),

-- Donor ID 153: David & Meg Bohmke
('01/01/2016', 50.00, 'Community Give; $50 for 2024 Giving Tues.', NULL, 153, 0.00, 0),

-- Donor ID 154: Sandra Mahaffey
('01/01/2016', 25.00, 'Community Give', NULL, 154, 0.00, 0),

-- Donor ID 155: Tiffany Villanueva
('12/01/2023', 20.00, '2023 Giving Tues; is a friend of volunteer Felicia Agnew', NULL, 155, 0.00, 0),

-- Donor ID 156: Wendy Cannon
('01/01/2015', 10.00, '', NULL, 156, 0.00, 0),

-- Donor ID 157: William Triplett
(NULL, NULL, '2024 Downtown Mile donor', NULL, 157, 0.00, 0),

-- Donor ID 158: Bruce Sheaffer
('07/18/2018', 100.00, '7/18/2018 and 2023 Giving Tues', NULL, 158, 0.00, 0),

-- Donor ID 159: John & Rosemarie McKeown
('02/26/2020', 50.00, 'Donation in honor of Sally Rycroft', NULL, 159, 0.00, 0),

-- Donor ID 162: Judith Downing
('02/18/2020', 100.00, 'Please accept this gift in memory of Sally Rycroft.', NULL, 162, 0.00, 0),

-- Donor ID 163: Jill Carroll
('02/18/2020', 25.00, 'Please accept this donation to honor the memory of my cousin''s wife, Sally Rycroft. Thank you.', NULL, 163, 0.00, 0),

-- Donor ID 164: Jerald & Judith Caira
('03/05/2020', 100.00, 'In memory of Sally Rycroft (Judy''s sister)', NULL, 164, 0.00, 0),

-- Donor ID 167: Bob & Harriet Burch
('04/01/2014', 50.00, 'In memory of Linda Pisenti', NULL, 167, 0.00, 0),

-- Donor ID 170: Community Baptist Church
('11/21/2018', 150.00, 'Teresa Bullock is in a Women''s Missions Group there.', NULL, 170, 0.00, 0),

-- Donor ID 171: Vicki Silver
('04/01/2014', 100.00, 'In memory of Linda Pisenti', NULL, 171, 0.00, 0),

-- Donor ID 172: Doris Watkins
('04/01/2014', 25.00, 'In memory of Linda Pisenti', NULL, 172, 0.00, 0),

-- Donor ID 173: Gail E. Taylor
('02/24/2020', 20.00, 'Please accept this memorial contribution in memory of my friend, Sally Rycroft. She was such a sweet, caring friend and will be missed.', NULL, 173, 0.00, 0),

-- Donor ID 175: Mark & Jacqueline Keith
('03/26/2015', 50.00, 'After Spotsylvania public hearing', NULL, 175, 0.00, 0),

-- Donor ID 178: Shelby Gilliam
('01/01/2018', 468.00, 'United Way office campaign', NULL, 178, 0.00, 0),

-- Donor ID 179: Nathan Eskin & Mary DeMerle
('06/08/2021', 10000.00, '', NULL, 179, 0.00, 0),

-- Donor ID 189: Marjorie R. Tankersley
('02/20/2020', 10.00, 'Please accept this gift in memory of Sally Rycroft.', NULL, 189, 0.00, 0),

-- Donor ID 191: Mary Raye Cox
('02/24/2020', 50.00, 'Enclosed please find a gift in memory of my friend Sally Rycroft. Thank you for all your good work. $50 in 2014 In memory of Linda Pisenti; $100 in 2017; $100 in 2020 in memory of Sally Rycroft', NULL, 191, 0.00, 0),

-- Donor ID 193: Martin Miller
('01/01/2017', 500.00, 'United Way office campaign', NULL, 193, 0.00, 0),

-- Donor ID 194: Anita Marshall
('01/01/2020', 50.00, '', NULL, 194, 0.00, 0),

-- Donor ID 195: Brandon Thompson
('01/01/2022', 1.00, 'Tithing $1 of his allowance since 2022', NULL, 195, 0.00, 0),

-- Donor ID 196: Redeemer Lutheran Church
('01/01/2023', 1500.00, '', NULL, 196, 0.00, 0),

-- Donor ID 197: Pohanka Auto Center
('01/01/2020', 1170.00, 'Xmas tree challenge + donation; $1,250 in 2019 Xmas Tree Challenge', NULL, 197, 0.00, 0),

-- Donor ID 198: Monica Dionne
('05/10/2014', 20.00, 'Bellydance event', NULL, 198, 0.00, 0),

-- Donor ID 199: Benjamin McDavid
('05/10/2014', 20.00, 'Bellydance event', NULL, 199, 0.00, 0),

-- Donor ID 200: Amy Limbrick (aka Kiyaana)
('05/10/2014', 80.00, 'Bellydance event', NULL, 200, 0.00, 0),

-- Donor ID 204: Alan & Mary Hart
('05/10/2014', 25.00, 'Bellydance event', NULL, 204, 0.00, 0),

-- Donor ID 205: Shirley Bozicevic
('02/18/2020', 15.00, 'Donation in memory of Sally Rycroft. Please inform Prof. Rycroft.', NULL, 205, 0.00, 0),

-- Donor ID 206: Elizabeth Morris
('06/15/2020', 100.00, 'In memory of Sally Rycroft - to help continue your important work', NULL, 206, 0.00, 0),

-- Donor ID 207: Kurt & Linda Glaeser
('02/18/2020', 100.00, 'In honor of Sally Rycroft', NULL, 207, 0.00, 0),

-- Donor ID 210: Anne & Carl Little
('01/01/2020', 100.00, 'Giving Tuesday', NULL, 210, 0.00, 0),

-- Donor ID 213: Carol & Joseph Dreiss
('02/18/2020', 50.00, 'Please accept this donation in memory of Sally A. Rycroft. Thank you so much', NULL, 213, 0.00, 0),

-- Donor ID 216: John and Marilyn Farrington
('04/01/2014', 100.00, 'In memory of Linda Pisenti', NULL, 216, 0.00, 0),

-- Donor ID 217: Benevolent & Protective Order of Elks, Fredericksburg Lodge #875
('06/01/2018', 2000.00, '', NULL, 217, 0.00, 0),

-- Donor ID 218: Debbie Cheshire
('04/01/2014', 50.00, 'In memory of Linda Pisenti', NULL, 218, 0.00, 0),

-- Donor ID 219: Lyla M. Hogle
('02/18/2020', 100.00, 'Please accept this donation in memory of Sally A. Rycroft, Robert Rycroft''s wife.', NULL, 219, 0.00, 0),

-- Donor ID 221: Doug & Peggy Pope
('12/31/2015', 100.00, 'Random end-of-year gift, though Jill said they may have given before', NULL, 221, 0.00, 0),

-- Donor ID 224: Ellen Ashwell
('12/31/2015', 100.00, 'End-of-year gift from former volunteer', NULL, 224, 0.00, 0),

-- Donor ID 225: Penny & Ron Saulnier
('01/01/2021', 200.00, 'Giving Tuesday', NULL, 225, 0.00, 0),

-- Donor ID 228: Elizabeth Parsnick
('05/01/2020', 50.00, 'In memory of Sally Rycorft', NULL, 228, 0.00, 0),

-- Donor ID 229: John and Barbara Jesz
('02/26/2020', 30.00, 'In memory of Sally Rycroft', NULL, 229, 0.00, 0),

-- Donor ID 232: Deborah & Wayne Seitl
('04/15/2020', 50.00, 'In memory of Sally Rycroft', NULL, 232, 0.00, 0),

-- Donor ID 235: Elisabeth Cunard
(NULL, NULL, 'Fawn Lake Country Club', NULL, 235, 0.00, 0),

-- Donor ID 236: Georgia & John Locks
('03/26/2015', 50.00, 'After Spotsylvania public hearing', NULL, 236, 0.00, 0),

-- Donor ID 239: Sue & David Roberts
('04/01/2014', 500.00, 'In memory of Linda Pisenti', NULL, 239, 0.00, 0),

-- Donor ID 242: Garrisonville Dental
('01/01/2019', 6139.00, 'Smiles for Life program', NULL, 242, 0.00, 0),

-- Donor ID 243: Bill & Cecelia Howell
('04/01/2014', 50.00, 'In memory of Linda Pisenti', NULL, 243, 0.00, 0),

-- Donor ID 246: Cookie & Steve Gross
('01/03/2017', 50.00, '2016 & 2017 Community Give; $102.53 for 2024 Giving Tues', NULL, 246, 0.00, 0),

-- Donor ID 249: Integrated Direct Marketing
('02/18/2020', 100.00, 'In remembrance of Sally Rycroft, please acept our donation for $100 (enclosed). Please acknowledge receipt to Ms. Rycroft''s family.', NULL, 249, 0.00, 0),

-- Donor ID 250: Michael P. Wiedemer
('02/18/2020', 250.00, 'Enclosed find check for your charitable work in memory of Sally Rycroft.', NULL, 250, 0.00, 0),

-- Donor ID 252: Laurel Fensterer and Timothy Davis
('01/01/2020', 200.00, 'Also $250 EOY gift for 2024', NULL, 253, 0.00, 0),
('01/01/2020', 200.00, '', NULL, 254, 0.00, 0),

-- Donor ID 255: Alissa Merlo
('12/01/2024', 25.00, '2024 Giving Tues.', NULL, 255, 0.00, 0),

-- Donor ID 256: Stephanie Yushchak
('12/01/2024', 200.00, '2024 Giving Tues.', NULL, 256, 0.00, 0),

-- Donor ID 257: Melissa Repetski
('12/01/2024', 50.00, '2024 Giving Tues. In honor of Cookie Gross, Edie''s mom and a former teacher of her kids', NULL, 257, 0.00, 0),

-- Donor ID 258: Teresa Bullock
('12/01/2024', 25.00, '2024 Giving Tues.', NULL, 258, 0.00, 0),

-- Donor ID 259: Cindy Simpson
('12/01/2024', 25.00, '2024 Giving Tues.', NULL, 259, 0.00, 0),

-- Donor ID 260: Gayle Dillon
('12/01/2024', 51.52, '2024 Giving Tues.', NULL, 260, 0.00, 0),

-- Donor ID 261: Ruth Golmant
('12/01/2024', 18.00, '2024 Giving Tues.', NULL, 261, 0.00, 0),

-- Donor ID 262: Amy Umble
('12/01/2024', 25.00, '2024 Giving Tues.', NULL, 262, 0.00, 0),

-- Donor ID 263: Kathy Crutcher
('12/01/2024', 50.00, '2024 Giving Tues.', NULL, 263, 0.00, 0),

-- Donor ID 264: Martha Moses
('12/01/2024', 50.00, '2024 Giving Tues.', NULL, 264, 0.00, 0),

-- Donor ID 265: Ana Valle-Greene
('12/01/2024', 15.80, '2024 Giving Tues.', NULL, 265, 0.00, 0),

-- Donor ID 268: Elizabeth Stokes
('12/01/2024', 25.00, '2024 Giving Tues. Saw a post on Facebook, maybe from Cookie Gross (Edie''s mom), and donated.', NULL, 268, 0.00, 0),

-- Donor ID 269: Jessica Varner
('12/01/2024', 25.00, '2024 Giving Tues. Fredericksburg CPS worker', NULL, 269, 0.00, 0),

-- Donor ID 270: Shondella Murray
('12/01/2024', 25.00, '2024 Giving Tues. CASA board member and former volunteers', NULL, 270, 0.00, 0),

-- Donor ID 271: Karen Gross
('12/01/2024', 50.00, '2024 Giving Tues. Aunt of CASA director, Edie Evans', NULL, 271, 0.00, 0),

-- Donor ID 272: Leslee Barnicle
('12/01/2024', 50.00, '2024 Giving Tues.', NULL, 272, 0.00, 0),

-- Donor ID 273: Dr. Jacinta Topps
('12/01/2024', 200.00, '2024 Giving Tues. Board member', NULL, 273, 0.00, 1),

-- Donor ID 275: Daniel and Karen Durham
('06/05/2025', 100.00, 'In memory of Bobby Anderson', NULL, 275, 0.00, 1),

-- Donor ID 278: Robert and Maxine Moore
('06/09/2025', 50.00, 'In memory of Bobby Anderson', NULL, 278, 0.00, 1),

-- Donor ID 281: Jack & Patsy Rowley
('06/11/2025', 100.00, 'In memory of Bobby Anderson', NULL, 281, 0.00, 1),

-- Donor ID 284: Michael & Marietta D'Ostilio
('06/27/2025', 50.00, 'In memory of Bobby Anderson', NULL, 284, 0.00, 1);

INSERT IGNORE INTO `dbevents` (`id`, `name`, `goalAmount`, `date`, `startDate`, `endDate`, `startTime`, `endTime`, `description`, `completed`, `location`) VALUES
(3, 'test', 1250.00, NULL, '2025-10-25', '2025-10-25', '00:00', '23:59', 'this is a test', '0', 'hcc'),
(4, 'test2', 10.00, NULL, '2025-10-26', '2025-10-26', '12:00', '17:00', 'test 2 for not requiring location', '0', ''),
(5, 'testOfViewEvents', 10000.00, NULL, '2025-10-30', '2026-01-31', '00:00', '23:59', 'this is a test of an ongoing event', '0', '');

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;
