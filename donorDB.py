#!/usr/bin/env python
# Server interaction script
import sys
from python.insertFunctions import *
import mysql.connector
import os

SYSTEM = os.name

def connect():
    try:
        conn = mysql.connector.connect(
                user = "casadb",
                password = "casadb",
                host = "localhost",
                database = "casadb"
                )
        return conn
    except Exception as e:
        raise e

# Export
def search_donors(info):
    try:
        conn = connect()
        cursor = conn.cursor(buffered=True)

        first = None
        last = None
        email = None
        zipcode = None
        city = None
        state = None
        street = None
        phone = None
        gender = None

        for item in info:
            if "first=" in item:
                first = item.split("=")[1]
                first = first.replace('"', '').replace("'", "")
                first = first.strip()

            elif "last=" in item:
                last = item.split("=")[1]
                last = last.replace('"', '').replace("'", "")
                last = last.strip()

            elif "email=" in item:
                email = item.split("=")[1]
                email = email.replace('"', '').replace("'", "")
                email = email.strip()

            elif "zip=" in item:
                zipcode = item.split("=")[1]
                zipcode = zipcode.replace('"', '').replace("'", "")
                zipcode = zipcode.strip()

            elif "city=" in item:
                city = item.split("=")[1]
                city = city.replace('"', '').replace("'", "")
                city = city.strip()

            elif "state=" in item:
                state = item.split("=")[1]
                state = state.replace('"', '').replace("'", "")
                state = state.strip()

            elif "street=" in item:
                street = item.split("=")[1]
                street = street.replace('"', '').replace("'", "")
                street = street.strip()

            elif "phone=" in item:
                phone = item.split("=")[1]
                phone = phone.replace('"', '').replace("'", "")
                phone = phone.strip()

            elif "gender=" in item:
                gender = item.split("=")[1]
                gender = gender.replace('"', '').replace("'", "")
                gender = gender.strip()
        
        queryFrom = "SELECT (" + ("first, " if first is not None else "") + ("last, " if last is not None else "") + ("email, " if email is not None else "") + ("zip, " if zipcode is not None else "") + ("city, " if city is not None else "") + ("state, " if state is not None else "") + ("street, " if street is not None else "") + ("phone, " if phone is not None else "") + ("gender, " if gender is not None else "")
        queryLimit = " FROM donors WHERE " + ("first=%s AND " if first is not None else "") + ("last=%s AND " if last is not None else "") + ("email=%s AND " if email is not None else "") + ("zip=%s AND " if zipcode is not None else "") + ("city=%s AND " if city is not None else "") + ("state=%s AND " if state is not None else "") + ("street=%s AND " if street is not None else "") + ("phone=%s AND " if phone is not None else "") + ("gender=%s AND " if gender is not None else "")
        queryFrom = queryFrom[:-2]  # Remove the last ', '
        queryFrom += ")"

        queryLimit = queryLimit[:-5]  # Remove the last ' AND '
        query = queryFrom + queryLimit
        query += ";"

        open(r"C:\xampp\apache\logs\error.log", "a").write(f"Executing query: {query} with params: {tuple(filter(None, [first, last, email, zipcode, city, state, street, phone, gender]))}\n")
        cursor.execute(query, tuple(filter(None, [first, last, email, zipcode, city, state, street, phone, gender])))
        open(r"C:\xampp\apache\logs\error.log", "a").write(f"Query executed successfully.\n")
        res = cursor.fetchall()
        open(r"C:\xampp\apache\logs\error.log", "a").write(f"Query result: {res}\n")
        return res
        

    except Exception as e:
        print(e)
        return e


def remove_donor(donor_id):
    try:
        conn = connect()
        cursor = conn.cursor(buffered=True)
        query = "DELETE FROM donors WHERE donorID = %s;"
        cursor.execute(query, donor_id)
        conn.commit()
        return 0
    except Exception as e:
        return 1

# Main
def main():
    # Check file format
    ret = 1
    if len(sys.argv) < 2 or (sys.argv[1] == "-h" or sys.argv[1] == "--help"):
        print("Usage:\nCASA_DB_Calls.py -i|--import <file1.xlsx> [<file2.xlsx> ...]\nCASA_DB_Calls.py -e|--export <file1.xlsx> [<file2.xlsx> ...]")
        return 1

    #import
    if sys.argv[1] == "-s" or sys.argv[1] == "--search":
        search = sys.argv[2:]
        return search_donors(search)
        
    #export
    elif sys.argv[1] == "-r" or sys.argv[1] == "--remove":
        retList = []
        for arg in sys.argv[2:]:
            if type(arg) is not int:
                return f"Error: {arg} in {sys.argv[2:]} is not a valid donor ID"
            else:
                ret = remove_donor(arg)
                retList.append(ret)
        return retList

if __name__ == "__main__":
    main()
