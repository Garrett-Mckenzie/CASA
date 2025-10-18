#SQL file to populate with excel data

DROP TABLE IF EXISTS db_media;
DROP TABLE IF EXISTS db_user;
DROP TABLE IF EXISTS db_fundraising_event;

CREATE TABLE db_media (
    id INT PRIMARY KEY AUTO_INCREMENT,

    eventID INT NOT NULL,

    file_name TEXT NOT NULL,

    type TEXT NOT NULL,

    file_format TEXT NOT NULL,

    description TEXT NOT NULL,

    altername_name TEXT NOT NULL,

    time_created TIMESTAMP NOT NULL,

    FOREIGN KEY (eventID) REFERENCES db_fundraising_events(id)
);

CREATE TABLE db_fundraising_event (
    id INT PRIMARY KEY AUTO_INCREMENT,

    name TEXT NOT NULL,

    startDate CHAR(5) NOT NULL,

    endDate CHAR(5) NOT NULL,

    description TEXT NOT NULL,

    target float NOT NULL,

    completed text NOT NULL,
);

CREATE TABLE db_users (
    id INT PRIMARY KEY AUTO_INCREMENT,

    first_name TEXT NOT NULL,

    last_name TEXT NOT NULL,

    email TEXT,

    password TEXT,
);

#db_users
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Phillip', 'Jenkins', '1philjenkins@gmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Jeri', 'Phillips', '4jphillips@comcast.net', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Christine', 'Repp', 'abcrepp@yahoo.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Adam', 'Fried', 'afried@atlanticbuilders.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Maha', 'Alattar', 'alattarm14@gmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Angela', 'Glidden', 'angiecasa135@gmail.com or aroubison1@gmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Amy', 'Faulkner-Hart', 'arfaulkner@hotmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Amy', 'Ridderhof', 'aridderhof@aol.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Brian', 'Pessolano', 'bapess@gmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Barbara & Guy', 'Miller-Richards', 'barbaramr6338@gmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Tina', 'Glass', 'beachbeans@gmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Betsy', 'Glassie', 'Bglassie@ail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Lesa', 'Aylor', 'bnldnj@aol.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Bobby', 'Anderson', 'bobbydeeanderson@yahoo.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('William', 'Turner', 'bturner23@gmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Cat', 'Paccasassi', 'c.paccasassi@gmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Michael', 'Camber', 'camberm@gmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Chad', 'Carter', 'ccarter@crrl.org', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Carolyn', 'Helfrich', 'chelfrich@verizon.net', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Chrissy', 'McDermott', 'chrissy@coolbreeze.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Cindy', 'Guerrero', 'cindyg62@comcast.net', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Colleen', 'Good', 'cpgood9@gmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Dolores "DD"', 'Lecky', 'd@lecky.org', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Danny', 'Fields', 'danfields65@verizon.net', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Daniel', 'Long', 'danielryan615@gmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Delise', 'Dickard', 'delisebd@msn.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Denise', 'Freeman', 'deniselynnfreeman@gmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Deborah', 'Cook', 'dlcook4@comcast.net', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Dennis', 'Keffer', 'dlkeffer@verizon.net', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Mike & Pat', 'Stevens', 'drmste@aol.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Elizabeth', 'Conn', 'esconn657@gmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Elizabeth', 'Brooks', 'esrrb@outlook.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Becky', 'Farris', 'Farrisra2@gmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Edythe & Kirk', 'Evans', 'gatoredie@yahoo.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Beth', 'McClain', 'gonedownunder@yahoo.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Bonita (Bonnie)', 'Darnell', 'grandmabonnied6@yahoo.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Grant', 'Smith', 'grantcs2580@gmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Hava', 'Marneweck', 'hava.marneweck@gmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Mari', 'Kelly', 'Homesbymkelly@gmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Ilana', 'Boivie', 'iboivie@yahoo.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Julie', 'Treanor', 'j.treanor@hotmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Jane', 'McDonald', 'janemcd56@yahoo.com or jane.yaun@yahoo.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Janet', 'Watkins', 'janmarshkins@yahoo.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('John', 'Bosserman', 'jbosserman@sigmacorps.com; john.bosserman@gmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Joseph', 'Di Bella', 'jdibella@umw.edu', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Joann', 'Farris', 'jfarris@iu.edu', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('John', 'Mayer', 'jhnmr86@gmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Joseph', 'Vines', 'jkv1229@cox.net', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Jacqueline', 'Burns', 'jlhburns@gmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('John', 'Calabrese', 'johnvincenzocalabrese@gmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Jane & Pete', 'Kolakowski', 'jskola9@verizon.net; prkola1@verizon.net', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Karen & David', 'Athanasiades', 'karena81@aol.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Katherine & Cornelius', 'Walsh', 'kathleen.5walsh@gmail.com bad email', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Kathryn', 'Waldron', 'kathryn@kauinc.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Katrina & Bud', 'Masterson', 'katrinalm@comcast.net', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Kelley', 'Helmstutler DiDio', 'kelley.didio@uvm.edu', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Kelly & Sam', 'Carniol', 'Kellycarniol@comcast.net', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Kelly', 'Morones', 'kellymorones@gmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('James', 'Kemp', 'kempjd623@yahoo.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Karen', 'Hawkins', 'khawkinsrdn@gmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Sue & David', 'Kleman', 'Klemanfamily@verizon.net', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Karin', 'Beals', 'klowebeals@gmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Joan', 'Koriath', 'koriath@sbcglobal.net', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Karen & David', 'Johnson', 'ksjohnson422@msn.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Elizabeth', 'LeDoux', 'ledoux2@mac.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Lucy', 'Walker', 'lewalker2@msn.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Liza', 'Fields', 'Lizafields@yahoo.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Loretta', 'Englman', 'llenglman@gmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('LaTonya', 'Turner', 'lnm0303@yahoo.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Margaret', 'Johnson', 'maggielea03@gmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Mona', 'Albertine', 'Malbertine@cox.net', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Meghan', 'Pcsolyar', 'mapcsolyar@gmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Maura', 'Schneider', 'maurawilson@gmail.com; and maurawilsonschneider@gmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Mary Carter', 'Frackelton', 'MCFrack@aol.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Michele', 'Utt', 'Michele.Utt@gmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Michelle', 'Purdy', 'michellepurdy@msn.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Emily', 'Simpson', 'Mlecatherine@gmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Mochel', 'Morris', 'Mochelmorris@vaumc.org', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Jim', 'Hall', 'moyer0@gmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Laura', 'Moyer', 'moyer0@gmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Maureen', 'Kennedy', 'mtkennedy@hotmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Nancy', 'Krause', 'nancykrause98@gmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Nancy', 'Pcsolyar', 'napcprn@gmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Nancy & Ron', 'Hicks', 'njc47@aol.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Nancy', 'Palmieri', 'nlpalmieri@aol.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Donna', 'Boyd', 'ohtoread@aol.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Paul', 'Wingeart', 'paul.wingeart16@hotmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Robert', 'Jones', 'R.rivers@riversjones.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Robert', 'Lane', 'roberterick@gmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Robert   ', 'Rycroft', 'robertrycroft@msn.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Rachel', 'Gredler', 'rochterc@gmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Sabina', 'Weitzman', 's.weitzman@verizon.net', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Susan', 'Barber', 'sbarber100@hotmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Sean', 'Bonney', 'sbonney@crrl.org', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Stacey', 'Strentz', 'sns@sgclawyers.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('George ', 'Solley', 'solleyg@cox.net', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Susan', 'Neal', 'ssneal@earthlink.net', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Laura', 'Joy ', 'starseeker111@hotmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Stephanie', 'Sinclair Hoben', 'stephsinclair@mac.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Scout', 'Tufankjian', 'stufankjian@gmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Susan', 'Park', 'susanduncanpark@gmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Stephen', 'Watkins', 'swatkins000@gmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Sydney', 'Simpson', 'sydneyjsimpson@gmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Amy', 'Umberger', 'tchakid2read@hotmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Jessica', 'Bloomfield', 'thebloomfields@verizon.net', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Timothy', 'Poe', 'timothyryanpoe@gmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Todd', 'Rump', 'todd.rump@protonmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Trina', 'Parsons', 'tparsons@verizon.net', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Tracy', 'Lloyd', 'tracyannlloyd@gmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('David', 'Bohmke', 'vabohmke@aol.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Sandra', 'Mahaffey', 'veryveggie@gmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Tiffany', 'Villanueva', 'Villantc@gmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Wendy', 'Cannon', 'wendyccannon@gmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('William', 'Triplett', 'wmtriple@aol.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Bruce', 'Sheaffer', 'wsheaffer@me.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Alissa', 'Merlo', 'agmm1830@gmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Stephanie', 'Yushchak', 'fdcsky23@gmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Melissa', 'Repetski', 'mrepetski@yahoo.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Teresa', 'Bullock', 'tcb405@cox.net', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Cindy', 'Simpson', 'cindy@goldensfam.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Gayle', 'Dillon', 'gddillon@gmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Ruth', 'Golmant', 'rgolmant@verizon.net', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Amy', 'Umble', 'umbleamy@gmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Kathy', 'Crutcher', 'kathy@shoutmousepress.org', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Martha', 'Moses', 'martha.moses@gmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Stokes', 'Elizabeth', 'Sleeplessinfla@aol.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Jessica', 'Varner', 'jessica.varner@dss.virginia.gov', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Shondella', 'Murray', 'shondellamarie@gmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Karen', 'Gross', 'kgross1605@aol.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Leslee', 'Barnicle', 'leslee16@gmail.com', '');
INSERT INTO db_users (first_name, last_name, email, password) VALUES ('Jacinta', 'Topps', 'jacinta.topps@comcast.net', '');

