# Server interaction script
import sys
from importlib.metadata import distributions
import subprocess
from pathlib import Path

# Checks for dependencies and installs them if not present
installed_pd = False
installed_mysql = False
for dist in distributions():
    if dist.metadata['Name'] == 'pandas':
        installed_pd = True
    if dist.metadata['Name'] == 'mysql-connector-python':
        installed_mysql = True

if not installed_pd:
    subprocess.check_call([sys.executable, '-m', 'pip', 'install', 'pandas'])

if not installed_mysql:
    subprocess.check_call([sys.executable, '-m', 'pip', 'install', 'mysql-connector-python'])

import pandas as pd
import mysql.connector
from mysql.connector import Error

# Exports data from the database to an Excel file
def export():
    for arg in sys.argv[2:]:
        try:
            connection = mysql.connector.connect(
                host='localhost',
                user='placeholder',
                password='placeholder',
                database='casadb'
            )
            print("Connected to database")
            if connection.is_connected():
                query = "SELECT * FROM sample;"
                df = pd.read_sql(query, connection)
                df.to_excel(arg, index=False)
                print(f"Data exported successfully as {arg}")
            connection.close()
        except Error:
            print("Error while connecting to MySQL", Error)

# Format: Last_Name: VARCHAR(20), First_Name: VARCHAR(32), Address1: VARCHAR(32), Address2: VARCHAR(16), City: VARCHAR(24), State: VARCHAR(2), ZIP: INT, Phone: INT, Email: VARCHAR(32), Donation: FLOAT, Date: VARCHAR(10) [Date Format: MM/DD/YYYY], Reason: VARCHAR(256), Notes: VARCHAR(160)
def validate_excel():
    valid = [True for _ in range(len(sys.argv) - 2)]
    index = 0

    for arg in sys.argv[2:]:
        isValid = True
        frame = pd.read_excel(arg)

        # Check for required columns
        required_columns = ['Last_Name', 'First_Name', 'Address_1', 'Address_2', 'City', 'State', 'ZIP', 'Phone', 'Email', 'Donation', 'Date', 'Reason', 'Notes']
        for col in required_columns:
            if col not in frame.columns:
                print(f"Error: Missing required column '{col}'")
                isValid = False

        print()

        # Check for excessive columns
        for col in frame.columns:
            if col not in required_columns:
                print(f"Error: Unexpected column '{col}' found")
                isValid = False
        if not isValid:
            valid[index] = False

        if not isValid:
            return False    
        
        # Check for database conformity
        for index, row in frame.iterrows():
            if len(str(row['Last_Name'])) > 20:
                print(f"Error: Last_Name is too long in row {index + 2}")
                isValid = False
            if len(str(row['First_Name'])) > 32:
                print(f"Error: First_Name is too long in row {index + 2}")
                isValid = False
            if len(str(row['Address1'])) > 32:
                print(f"Error: Address1 is too long in row {index + 2}")
                isValid = False
            if len(str(row['Address2'])) > 16:
                print(f"Error: Address2 is too long in row {index + 2}")
                isValid = False
            if len(str(row['City'])) > 24:
                print(f"Error: City is too long in row {index + 2}")
                isValid = False
            if len(str(row['State'])) != 2:
                print(f"Error: State must be exactly 2 characters in row {index + 2}")
                isValid = False
            if not str(row['ZIP']).isdigit() or len(str(row['ZIP'])) != 5:
                print(f"Error: ZIP must be a 5-digit integer in row {index + 2}")
                isValid = False
            if not str(row['Phone']).isdigit() or len(str(row['Phone'])) != 10:
                print(f"Error: Phone must be a 10-digit integer in row {index + 2}")
                isValid = False
            if len(str(row['Email'])) > 32:
                print(f"Error: Email is too long in row {index + 2}")
                isValid = False
            try:
                float(row['Donation'])
            except ValueError:
                print(f"Error: Donation must be a valid float in row {index + 2}")
                isValid = False
            if len(str(row['Date'])) != 10 or str(row['Date'])[2] != '/' or str(row['Date'])[5] != '/':
                print(f"Error: Date must be in MM/DD/YYYY format in row {index + 2}")
                isValid = False
            if len(str(row['Reason'])) > 256:
                print(f"Error: Reason is too long in row {index + 2}")
                isValid = False
            if len(str(row['Notes'])) > 160:
                print(f"Error: Notes is too long in row {index + 2}")
                isValid = False
        
        if not isValid:
            valid[index] = False
        
        index += 1

    return True


def main():
    # Check file format
    if len(sys.argv) < 2 or (sys.argv[1] == "-h" or sys.argv[1] == "--help"):
        print("Usage: CASA_DB_Calls.py -i|--import <file1.xlsx> [<file2.xlsx> ...] OR CASA_DB_Calls.py -e|--export <file1.xlsx> [<file2.xlsx> ...]")
        return
    
    for arg in sys.argv[2:]:
        if not Path(arg).is_file() or not arg.endswith('.xlsx'):
            print(f"Error: {arg} is not a valid .xlsx file")
            return

    if sys.argv[1] == "-i" or sys.argv[1] == "--import":
        validate_excel()
    elif sys.argv[1] == "-e" or sys.argv[1] == "--export":
        export()
    else:
        print("Error: Invalid option. Use -h or --help for usage information.")
        return

if __name__ == "__main__":
    main()