# Steps to set up automatic DB-dumps

overview: this process will copy a "hook" called post-merge (no extension) into the .git/hooks directory for our project, this script will run after every git pull
the scripts are available in the scripts folder that are in the project repo; depending on your os and terminal configuration, different scripts and proccesses will be needed

1) go to your project directory in your terminal (i.e. directory that contains the .git, and other project files)
   
2) pull the most recent repo ``git pull``
### macOS/Linux/Git Bash Users: 
3) from the project directory run the following terminal commands:
- ``cp scripts/hooks/post-merge.bash .git/hooks/post-merge``
4) then give the new script executable: ``chmod +x .git/hooks/post-merge``
### Windows CMD / PowerShell
3) from the project directory run the following terminal commands:
- ``copy scripts\hooks\post-merge.bat .git\hooks\post-merge``
4) no need to give the script executable, continue to next steps
### Continue
5) update the scripts with the correct information (newly copied script is located in .git/hooks):
edit the post-merge file using vim or what ever editor you use, the top of the file has information you must verify:
- db name = the name of your local database (i.e. casadb) [not the name of the .sql file]
- db user = should just be root, check your phpMyAdmin in the casadb Privileges *(i dont have those)* tab
- db pass = should just be blank, pretty sure we set it to blank for everyone
- sql file= file path to the .sql dump file passed around through git
- **mysql bin = this one needs to be looked at, it is the location of your mysql client in your local xampp host**
**NOTE: the mysql bin path will look different depending on your os and where the file actually is**
- macOS XAMPP ex: `/Applications/XAMPP/xamppfiles/bin/mysql`
- Windows XAMPP ex:`C:\xampp\mysql\bin\mysql.exe`

6) **FROM THE PROJECT DIR** you can test and run the new script manually with the terminal cmd and putting in your db password when prompted (see step 5: if blank just press enter): ``.git/hooks/post-merge``
- **If this doesn't work for windows users try:** `cmd /c .git\hooks\post-merge`
8) From now on, whenever you git pull or git merge:
- The post-merge hook runs automatically.
- It imports db.sql into the local database.
- If MySQL isn’t running, the hook prints a warning and skips import.
- you will then need to manually run the script to pull the db changes (see step 6)

**NOTE**
This script will drop all db info in your local db when hydrating from an updated database, to avoid this, back up your local database using the export function in PHPmyAdmin, you can use your backup by importing it back in via the PHPmyAdmin





