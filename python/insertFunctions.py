import sys
from importlib.metadata import distributions
from pathlib import Path
from credentials import HOST, USER, PASSWORD, DATABASE
import pandas as pd
import mariadb
import random
import os
import numpy as np
from datetime import datetime

#To insert a donation all it needs is an amount
def insertDonation(donationData,donation_columns,conn,cursor):
    if "amount" not in donationData.columns:
        print("Could Not Import Donation Data Missing Amount Column")
    else:
        for i,row in donationData.iterrows():
            insertCol = []
            insertData = []
            goodInsert = 1
            for col in donation_columns:
                try:
                    value = row[col]
                    if pd.isna(value):
                        value = ""
                    insertCol.append(col)
                    insertData.append(value)
                except KeyError as e:
                    pass

            #makes sure the amount is present
            if insertData[insertCol.index("amount")] == "": 
                goodInsert = 0
            
            #deal with donor donation relationship
            if (("first" in insertCol or "last" in insertCol or "email" in insertCol) and not ("first" in insertCol and "last" in insertCol and "email" in insertCol)):
                print(f"When Specifying a donation with a donor you must include a unique first,last,email collection on row {i+1}")
                goodInsert = 0

            elif "first" in insertCol and "last" in insertCol and "email" in insertCol:
                j = insertCol.index("first")
                insertCol.pop(j)
                first = insertData.pop(j)
                j = insertCol.index("last")
                insertCol.pop(j)
                last = insertData.pop(j)
                j = insertCol.index("email")
                insertCol.pop(j)
                email = insertData.pop(j)

                #kinda gross logic but it works so whateva
                if (first == "" or last == "" or email == "") and not (first == "" and last == ""and email == ""):
                    print(f"When Specifying a donation with a donor you must include a unique first,last,email collection on row {i+1}")
                    goodInsert = 0
                elif (first == "" and last == "" and email == ""):
                    #for anonymous donations
                    insertCol.append("donorID")
                    insertData.append(999)
                else:    
                    query = "SELECT id FROM donors WHERE first = ? AND last = ? AND email = ?"
                    executeTup = (first,last,email)
                    cursor.execute(query,executeTup)
                    donorId = cursor.fetchone()
                    if donorId == None:
                        goodInsert  = 0
                        print(f"Donor {first + ' ' + last} was not found on row {i+1}")
                    else:
                        insertCol.append("donorID")
                        insertData.append(donorId[0])

            #deal with donation event relationship
            #need to write this part 
            if ("eventName" in insertCol and insertData[insertCol.index("eventName")] != ""):
                j = insertCol.index("eventName")
                eventName = insertData.pop(j)
                query = "SELECT id FROM events WHERE name = ?"
                executeTup = (eventName,)
                cursor.execute(query,executeTup)
                eventID = cursor.fetchone()
                if eventID == None:
                    print(f"Event {eventName} was not found in our database on row {i}")
                    goodInsert = 1
                else:
                    insertCol.append("eventID")
                    insertData.append(eventID[0])

            if ("date" in insertCol):
                j = insertCol.index("date")
                insertCol.pop(j)
                d = insertData.pop(j)
                if isValidDate(d , noFuture = True):
                    insertCol.append("date")
                    insertData.append(d)
                else:
                    goodInsert = 0
                    print(f"Date {d} was not a valid date on row {i}")
                    
            if goodInsert:
                #builds the query
                queryCols = ",".join(insertCol)    
                queryValues = "?,"*len(insertCol)
                queryValues = queryValues[0:-1]
                query = f"INSERT INTO donations (" + queryCols  + ") VALUES (" + queryValues + ")" 
                executeTup = tuple(insertData)
                try:
                    #adds insert to transaction
                    cursor.execute(query,executeTup)
                except Exception as e:
                    print(e)
                    print(f"Could not insert row {i+1}")
            else:
                print(f"Could not insert row {i+1}")

        #attempts transaction commit
        try:
            conn.commit()
            print("The data for donations was commited except for rows where otherwise specified.")
        except Exception as e:
            conn.rollback()
            print("There was a problem")

# to insert col all you need is a unique name
def insertEvent(eventData,event_columns,conn,cursor):
    if "eventName" not in deventData.columns:
        print("could not insert event data no eventName column found")
        return

    for i,row in eventData.iterrows():
        insertCol = []
        insertData = []
        goodInsert = 1
        for col in event_columns:
            try:
                value = row[col]
                if pd.isna(value):
                    value = ""
                insertCol.append(col)
                insertData.append(value)
            except KeyError as e:
                pass

        if insertData[insertCol.index("eventName") == ""]:
            print(f"Event on row {i+1} was not named and therefore could not be inserted")
            goodInsert = 0

        if goodInsert:
            #builds the query
            queryCols = ",".join(insertCol)    
            queryValues = "?,"*len(insertCol)
            queryValues = queryValues[0:-1]
            query = f"INSERT INTO events (" + queryCols  + ") VALUES (" + queryValues + ")" 
            executeTup = tuple(insertData)
            try:
                #adds insert to transaction
                cursor.execute(query,executeTup)
            except Exception as e:
                print(e)    
                print(f"Could not insert row {i+1}")
        else:
            print(f"Could not insert row {i+1}")

    #attempts transaction commit
    try:
        conn.commit()
        print("The data for donors was commited except for rows where otherwise specified.")
    except Exception as e:
        conn.rollback()
        print("There was a problem")

#To insert a donor you need first,last,email
def insertDonor(donorData,donor_columns,conn,cursor):
    if "first" not in donorData.columns or "last" not in donorData.columns or "email" not in donorData.columns:
        print("could not insert donor data as the required columns of first,last,and email were not present")
        return
    
    for i,row in donorData.iterrows():
        insertCol = []
        insertData = []
        goodInsert = 1
        for col in donor_columns:
            try:
                value = row[col]
                if pd.isna(value):
                    value = ""
                insertCol.append(col)
                insertData.append(value)
            except KeyError as e:
                pass

        if insertData[insertCol.index("first")] == "" or insertData[insertCol.index("last")] == "" or insertData[insertCol.index("email")] == "":
            print(f"Must have a complete first,last,email collection on the {i}th row")
            goodInsert = 0

        if goodInsert:
            #builds the query
            queryCols = ",".join(insertCol)    
            queryValues = "?,"*len(insertCol)
            queryValues = queryValues[0:-1]
            query = f"INSERT INTO donors (" + queryCols  + ") VALUES (" + queryValues + ")" 
            executeTup = tuple(insertData)
            try:
                #adds insert to transaction
                cursor.execute(query,executeTup)
            except Exception as e:
                print(e)    
                print(f"Could not insert row {i+1}")
        else:
            print(f"Could not insert row {i+1}")

    #attempts transaction commit
    try:
        conn.commit()
        print("The data for donors was commited except for rows where otherwise specified.")
    except Exception as e:
        conn.rollback()
        print("There was a problem")

def isValidDate(date , noFuture = False , noPast = False):
    if noFuture and noPast:
        print("vorp?")
        return False
    try:
        month = date[0 : date.index("/")] 
        date = date[date.index("/") + 1 :]
        day = date[0 : date.index("/")]
        date = date[date.index("/") + 1 :]
        year = date[0 : date.index("/")]
    
        month = int(month.lstrip('0'))
        day = int(day.lstrip('0'))
        year = int(year)

        if month <= 0 or month > 12:
            return False
        if day <= 0 or day > 31:
            return False

        if noFuture or noPast:
            today = datetime.today().strftime('%Y-%m-%d')
            today = today.split("-")
            curYear = int(today[0])
            curMonth = int(today[1].lstrip('0'))
            curDay = int(today[2].lstrip('0'))

            if noFuture:
                if year > curYear:
                    return False
                if year >= curYear and month > curMonth:
                    return False
                if year >= curYear and month >= curMonth and day > curDay:
                    return False
            if noPast:
                if year < curYear:
                    return False
                if year <= curYear and month < curMonth:
                    return False
                if year <= curYear and month <= curMonth and day < curDay:
                    return False
        return True 
    except:
        return False
