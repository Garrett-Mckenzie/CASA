#!/usr/bin/env python
# Server interaction script
import sys
from importlib.metadata import distributions
from pathlib import Path
from credentials import HOST, USER, PASSWORD, DATABASE
import pandas as pd
import mariadb
import random
import os

def winConnect():
    try:
        conn = mariadb.connect(
                user = USER,
                password = PASSWORD,
                host = HOST,
                database = DATABASE
                )
        return conn
    except Exception as e:
        raise e

def macConnect():
    try:
        conn = mariadb.connect(
                user=USER,
                password=PASSWORD,
                host=HOST,
                database=DATABASE,
                unix_socket="/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock"
                )
        return conn
    except Exception as e:
        raise e

# Export
def export_excel():
    query = sys.argv[2]
    output_file = sys.argv[3]
    try:
        #make the cursour and connection
        conn = winConnect()
        cursor = conn.cursor()

        cols = []
        cursor.execute(f"SHOW COLUMNS FROM {query.split(" ")[-1]}")
        for row in cursor:
            cols.append(row[0])

        cursor.execute(query)
        data = []
        for row in cursor:
            data.append(list(row))
        df = pd.DataFrame(data,columns = cols) 
        try:
            #this will need to change on a linux os worrying about it later
            os.system('del exports\\Export.xlsx')
        except Exception as e:
            print(e)
            pass
        df.to_excel('exports/Export.xlsx', index=False)

    except Exception as e:
        raise e
        
"""
The code here just isnt good Ill replace it later
"""
def import_excel():
    pass

# Main
def main():
    # Check file format
    ret = 1
    if len(sys.argv) < 2 or (sys.argv[1] == "-h" or sys.argv[1] == "--help"):
        print("Usage:\nCASA_DB_Calls.py -i|--import <file1.xlsx> [<file2.xlsx> ...]\nCASA_DB_Calls.py -e|--export <file1.xlsx> [<file2.xlsx> ...]")
        return 1

    #import
    if sys.argv[1] == "-i" or sys.argv[1] == "--import":
        for arg in sys.argv[2:]:
            if not Path(arg).is_file() or not arg.endswith('.xlsx'):
                print(f"Error: {arg} is not a valid .xlsx file")
                return 1
        ret = import_excel()

    #export
    elif sys.argv[1] == "-e" or sys.argv[1] == "--export":
        if len(sys.argv) != 4:
            print("Error: Incorrect format")
        ret = export_excel()
    else:
        print("Error: Invalid option. Use -h or --help for usage information.")
        return 1

        return 0 if ret else 1

if __name__ == "__main__":
    main()
