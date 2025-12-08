
# Rappahannock CASA 
(540) 710-6199 | rappahannockcasa.com | 509 Lafayette Blvd, Fredericksburg, VA 22401

## Purpose
This project is the result of a semester's worth of collaboration among UMW students. The goal of the project was to create a web application that better suits the needs of Rappahannock CASA, specifically as a system to manage their donors and donations.

## Authors
Ethan Bostick

Garrett McKenzie

Maximilian (Max) Redman

Joshua (Josh) Byrne

Carter Walker

James Heathcock

## Features
AI backed automated email generation

Importing donation and donor data through excel files.

Exporting donor and donation data to an excel file.

Automatic report generation that gives insight into donation and donor trends downloadable to PDF.

Option to manually add and remove donors as well as donations.

Ability to explore stored donation and donor information while taking advantages of preset optional filters.

Ability to create, track, and edit fundraising events.

Ability to create new admin users with permission to access the system.

## Suggested Browser
We reccomend using Chrome for this application. Microsoft Edge as well as Safari are also supported. Other browsers may have unknown issues if this application is used with them.

## Siteground Installation
Note that these instructions assume that you have already created a new siteground project such that you have an accessible site link and access to the siteground site tools.
1) For set-up, you will need to SSH into the siteground host. To do this begin by clicking the "devs" dropdown and from there selecting the "SSH Keys Manager" button.
2) Next, create an SSH key and copy it to a file.
3) On your local machine run the command "ssh-add path/to/file"
4) Return to siteground and click the kebab menu for the SSH key you just created. Then select the "SSH Creditials" option.
5) Use these credentials to SSH into siteground utilizing the command "ssh username.hostname -p port".
6) Once you are on siteground, navigate to public_html underneath the directory with the same name as your site.
7) Run the command "git init"
8) Run the command "git pull https://github.com/Garrett-Mckenzie/CASA.git"
9) Navigate to the "python" directory
10) Run the command "python -m pip install -r requirements.txt"
11) Move back to the "public_html" directory. In this directory you will need to create a .env and .api_env file. If you Dr. Polack then these files will be in the files we submitted for the final. If you are not Dr. Polack, then you need to add your own information into these files. Go to the ".env example" section for an example.
12) Navigate back to the directory "/home/username" and vim into the .bashrc file.
13) In the .bashrc put in the lines below
    
    export OPENBLAS_NUM_THREADS=1
    
    export OMP_NUM_THREADS=1
    
    export NUMEXPR_NUM_THREADS=1
    
16) :wq out of the .bashrc file.
17) Run the command "exit" to leave siteground as everything should now be setup filewise.
18) Now to setup the database, go back to the siteground site tools webpage and select the "mysql" option under the "site" dropdown.
19) Select database and go through the steps to create a new database.
20) Create a user and grant them access to the database.
21) Navigate to phpMyAdmin found under phpMyAdmin.
22) Import the database using the "casadb.sql" file.
23) Update the dbinfo file found in the database directory of publich_html through the "File Manager" tab.
24) Everything should now be set up.

## .env examples

In ".env"
SMTP_SERVER=

SMTP_PORT=

SMTP_USER=

SMTP_PASS=

In ".api_env"

OPENAI_API_KEY=

## License
The project remains under the [GNU General Public License v3.0](https://www.gnu.org/licenses/gpl.txt).

## Acknowledgements
Thank you to Dr. Polack and Rappahannock CASA for the opportunity to work on this project.
