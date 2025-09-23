@echo off
setlocal

set DB_NAME=casadb
set DB_USER=root
set DB_PASS=
set SQL_FILE=sql\casadb.sql

set MYSQL_BIN=C:\xampp\mysql\bin\mysql.exe

if exist "%SQL_FILE%" (
    if exist "%MYSQL_BIN%" (
        echo Post-merge: Importing %SQL_FILE% into %DB_NAME%...
        "%MYSQL_BIN%" -u %DB_USER% -p%DB_PASS% %DB_NAME% < "%SQL_FILE%"
        if %ERRORLEVEL% EQU 0 (
            echo Database updated successfully!
        ) else (
            echo !!!Failed to update database. Check credentials and SQL syntax!!!
        )
    ) else (
        echo !!!MySQL client not found at %MYSQL_BIN%
    )
) else (
    echo No %SQL_FILE% found. Skipping DB update.
)

endlocal

