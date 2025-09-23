#!/bin/bash
DB_NAME="casadb"   # your local DB name
DB_USER="root"           # your MySQL username
DB_PASS=""               # your MySQL password (empty string if none)
SQL_FILE="./db.sql"      # expects db.sql at repo root

if [ -f "$SQL_FILE" ]; then
  echo "Post-merge: Importing $SQL_FILE into $DB_NAME..."
  mysql -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" < "$SQL_FILE"
  if [ $? -eq 0 ]; then
    echo "Database updated successfully!"
  else
    echo "!!! Failed to update database. Check credentials and SQL syntax. !!!"
  fi
else
  echo "No $SQL_FILE found in repo root. Skipping DB update."
fi
