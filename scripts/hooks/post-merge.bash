#!/bin/bash
DB_NAME="casadb"   # your local DB name
DB_USER="root"           # your MySQL username
DB_PASS=""               # your MySQL password (empty string if none)
SQL_FILE="./casadb.sql"      # expects casadb.sql at repo root

#path to mysql
MYSQL_BIN="/Applications/XAMPP/xamppfiles/bin/mysql"

if [ -f "$SQL_FILE" ]; then
  if [ -x "$MYSQL_BIN" ]; then
    echo "Post-merge: Importing $SQL_FILE into $DB_NAME..."
    "$MYSQL_BIN" -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" < "$SQL_FILE"
    if [ $? -eq 0 ]; then
      echo "Database updated successfully!"
    else
      echo "!!!Failed to update database. Check credentials and SQL syntax!!!"
    fi
  else
    echo "!!!MySQL client not found at $MYSQL_BIN !!!"
  fi
else
  echo "No $SQL_FILE found. Skipping DB update."
fi
