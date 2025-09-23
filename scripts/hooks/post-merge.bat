@echo off
setlocal

set DB_NAME=casadb
set DB_USER=root
set DB_PASS=
set SQL_FILE=sql\casadb.sql
set MYSQL_BIN=C:\xampp\mysql\bin\mysql.exe

set HASH_FILE=.git\hooks\.dbsql_hash

if not exist "%SQL_FILE%" (
    echo No %SQL_FILE% found. Skipping DB update.
    exit /b 0
)

for /f "delims=" %%a in ('certutil -hashfile "%SQL_FILE%" SHA256 ^| find /i /v "hash" ^| find /i /v "CertUtil"') do set CUR_HASH=%%a

set LAST_HASH=
if exist "%HASH_FILE%" (
    set /p LAST_HASH=<"%HASH_FILE%"
)

if /I "%CUR_HASH%"=="%LAST_HASH%" (
    echo No changes in %SQL_FILE%. Skipping DB import.
    exit /b 0
)

echo Changes detected in %SQL_FILE%. Updating database %DB_NAME%...
echo Dropping local tables...
"%MYSQL_BIN%" -u %DB_USER% -p%DB_PASS% -e "DROP DATABASE IF EXISTS %DB_NAME%; CREATE DATABASE %DB_NAME%;"
echo Rehydrating from SQL file...
"%MYSQL_BIN%" -u %DB_USER% -p%DB_PASS% %DB_NAME% < "%SQL_FILE%"

if %ERRORLEVEL% EQU 0 (
    echo %CUR_HASH% > "%HASH_FILE%"
    echo %DATE% %TIME%: Database updated successfully!
) else (
    echo !!!Failed to update database. Check credentials and SQL syntax!!!
)

endlocal
