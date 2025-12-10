#!/usr/bin/env python
# Server interaction script
import sys
from importlib.metadata import distributions
from pathlib import Path
from python.credentials import HOST, USER, PASSWORD, DATABASE
import pandas as pd
import random
import numpy as np
from python.insertFunctions import *
import mysql.connector

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
    query = sys.argv[2]
    output_file = sys.argv[3]
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
            elif "last=" in item:
                last = item.split("=")[1]
            elif "email=" in item:
                email = item.split("=")[1]
            elif "zip=" in item:
                zipcode = item.split("=")[1]
            elif "city=" in item:
                city = item.split("=")[1]
            elif "state=" in item:
                state = item.split("=")[1]
            elif "street=" in item:
                street = item.split("=")[1]
            elif "phone=" in item:
                phone = item.split("=")[1]
            elif "gender=" in item:
                gender = item.split("=")[1]
        
        query = "SELECT (first, last, email, zip, city, state, street, phone, gender) FROM donors WHERE " + ("first=%s AND " if first is not None else "") + ("last=%s AND " if last is not None else "") + ("email=%s AND " if email is not None else "") + ("zip=%s AND " if zip is not None else "") + ("city=%s AND " if city is not None else "") + ("state=%s AND " if state is not None else "") + ("street=%s AND " if street is not None else "") + ("phone=%s AND " if phone is not None else "") + ("gender=%s AND " if gender is not None else "")
        query = query[:-5]  # Remove the last ' AND '
        query += ";"

        cursor.execute(query, tuple(filter(None, [first, last, email, zipcode, city, state, street, phone, gender])))
        

    except Exception as e:
        print(e)
        raise e

    
"""
need to work on this part here
"""
def import_excel():
    try:
        conn = connect()
        cursor = conn.cursor(buffered=True)


        #load up data
        file_path = sys.argv[2]
        data = pd.read_excel(file_path)
        cols = data.columns

        #these arrays define the current state of the database its static to enforce that someone look at this code whenever changes are made to the db schema.
        donation_columns = ["amount","reason","date","fee","thanked","eventID","first","last","email","eventName"]
        donor_columns = ["first","last","email","zip","city","state","street","phone","gender","notes"] 
        event_columns = ["name","goalAmount","date","startDate","endDate","description","completed","location"]

        donationData = pd.DataFrame()
        donorData = pd.DataFrame()
        eventData = pd.DataFrame()

        for col in data.columns:
            if col in donation_columns:
                donationData[col] = data[col].values
            if col in donor_columns:
                donorData[col] = data[col].values
            if col in event_columns:
                eventData = data[col].values

        #For a donation to be valid it must have at a minimum an amount
        haveEventData = not (isinstance(eventData,np.ndarray)) 
        haveDonorData = not (isinstance(donorData,np.ndarray))
        haveDonationData = not (isinstance(donationData,np.ndarray))
        
        if (not haveEventData and not haveDonorData and not haveDonationData):
            print("No valid information to import")
            return

        print(f"Attempting to import the file at {file_path}")
        if (haveDonorData):
            insertDonor(donorData,donor_columns,conn,cursor)
            conn = connect()
            cursor = conn.cursor(buffered=True)

        if (haveDonationData):
            insertDonation(donationData,donation_columns,conn,cursor)
            conn = connect()
            cursor = conn.cursor(buffered=True)

        if (haveEventData):
            insertEvent(eventData,event_columns,conn,cursor)
            conn = connect()
            cursor = conn.cursor(buffered=True)

        return 0
                            
    except Exception as e:
        print(e)
        raise e

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
        ret = search_donors(search)
        
    #export
    elif sys.argv[1] == "-r" or sys.argv[1] == "--remove":
        for arg in sys.argv[2:]:
            if type(arg) is not int:
                print(f"Error: {arg} is not a valid donor ID")
                return 1

if __name__ == "__main__":
    main()
