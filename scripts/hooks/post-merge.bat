@echo off
set DB_NAME=casadb
set DB_USER=root
set DB_PASS=
set SQL_FILE=db.sql

if exist %SQL_FILE% (
  echo Post-merge: Importing %SQL_FILE% into %DB_NAME%...
  mysql -u %DB_USER% -p%DB_PASS% %DB_NAME% < %SQL_FILE%
  if %errorlevel%==0 (
    echo Database updated successfully!
  ) else (
    echo !!!Failed to update database. Check credentials and SQL syntax.!!!
  )
) else (
  echo No %SQL_FILE% found in repo root. Skipping DB update.
)

