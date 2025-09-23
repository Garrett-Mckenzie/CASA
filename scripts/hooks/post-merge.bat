@echo off
setlocal

set DB_NAME=casadb
set DB_USER=root
set DB_PASS=
set SQL_FILE=sql\db.sql
set MYSQL_BIN=C:\xampp\mysql\bin\mysql.exe

if not exist "%SQL_FILE%" (
    echo No %SQL_FILE% found. Skipping DB update.
    exit /b 0
)

if not exist "%MYSQL_BIN%" (
    echo !!!MySQL client not found at %MYSQL_BIN% !!!
    exit /b 1
)

echo Post-merge: Dropping and recreating database %DB_NAME%...
"%MYSQL_BIN%" -u %DB_USER% -p%DB_PASS% -e "DROP DATABASE IF EXISTS %DB_NAME%; CREATE DATABASE %DB_NAME%;"

echo Importing %SQL_FILE% into %DB_NAME%...
"%MYSQL_BIN%" -u %DB_USER% -p%DB_PASS% %DB_NAME% < "%SQL_FILE%"

if %ERRORLEVEL% EQU 0 (
    echo Database updated successfully!
) else (
    echo !!!Failed to update database. Check credentials and SQL syntax!!!
)

endlocal

