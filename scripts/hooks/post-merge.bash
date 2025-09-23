#!/bin/bash
DB_NAME="casadb"   # your local DB name
DB_USER="root"           # your MySQL username
DB_PASS=""               # your MySQL password (empty string if none)
SQL_FILE="./sql/casadb.sql"      # expects casadb.sql in sql folder at repo root 

#path to mysql
MYSQL_BIN="/Applications/XAMPP/xamppfiles/bin/mysql"
#for determining changes to .sql
HASH_FILE=".git/hooks/.dbsql_hash"

# Check if SQL file exists
if [ ! -f "$SQL_FILE" ]; then
    echo "No $SQL_FILE found. Skipping DB update."
    exit 0
fi

# Determine hash command
if command -v sha256sum &> /dev/null; then
    HASH_CMD="sha256sum"
elif command -v shasum &> /dev/null; then
    HASH_CMD="shasum -a 256"
else
    echo "No SHA256 utility found. Skipping DB import."
    exit 0
fi

# Compute current hash
CUR_HASH=$($HASH_CMD "$SQL_FILE" | awk '{print $1}')

# Read last imported hash
if [ -f "$HASH_FILE" ]; then
    LAST_HASH=$(cat "$HASH_FILE")
else
    LAST_HASH=""
fi

# Skip import if hash hasn’t changed
if [ "$CUR_HASH" = "$LAST_HASH" ]; then
    echo "No changes in $SQL_FILE. Skipping DB import."
    exit 0
fi


# Drop and recreate DB
echo "Changes detected in $SQL_FILE. Updating database $DB_NAME..."
echo "Dropping local tables..."
"$MYSQL_BIN" -u "$DB_USER" -p"$DB_PASS" -e "DROP DATABASE IF EXISTS $DB_NAME; CREATE DATABASE $DB_NAME;"
echo "Rehydrating from sql file..."
"$MYSQL_BIN" -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" < "$SQL_FILE"

if [ $? -eq 0 ]; then
# Update hash file
    echo "$CUR_HASH" > "$HASH_FILE"
    echo "Database updated successfully!"
else
    echo "!!!Failed to update database. Check credentials and SQL syntax!!!"
fi

