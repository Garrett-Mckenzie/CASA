#!/bin/bash
DB_NAME="casadb"   # your local DB name
DB_USER="root"           # your MySQL username
DB_PASS=""               # your MySQL password (empty string if none)
SQL_FILE="./sql/casadb.sql"      # expects casadb.sql in sql folder at repo root 

#path to mysql
MYSQL_BIN="/Applications/XAMPP/xamppfiles/bin/mysql"

# Check if SQL file exists
if [ ! -f "$SQL_FILE" ]; then
    echo "No $SQL_FILE found. Skipping DB update."
    exit 0
fi

# Check if MySQL client exists
if [ ! -x "$MYSQL_BIN" ]; then
    echo "!!!MySQL client not found at $MYSQL_BIN !!!"
    exit 1
fi

echo "Post-merge: Dropping and recreating database $DB_NAME..."
"$MYSQL_BIN" -u "$DB_USER" -p"$DB_PASS" -e "DROP DATABASE IF EXISTS $DB_NAME; CREATE DATABASE $DB_NAME;"

echo "Importing $SQL_FILE into $DB_NAME..."
"$MYSQL_BIN" -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" < "$SQL_FILE"

if [ $? -eq 0 ]; then
    echo "Database updated successfully!"
else
    echo "!!! Failed to update database. Check credentials and SQL syntax !!!"
fi

