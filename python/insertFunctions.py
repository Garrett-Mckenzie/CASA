import sys
from importlib.metadata import distributions
from pathlib import Path
from credentials import HOST, USER, PASSWORD, DATABASE
import pandas as pd
import mariadb
import random
import os
import numpy as np

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
                print("When Specifying a donation with a donor you must include a unique first,last,email set")
                goodInsert = 0

            elif "first" in insertCol and "last" in insertCol and "email" in insertCol:
                i = insertCol.index("first")
                insertCol.pop(i)
                first = insertData.pop(i)
                i = insertCol.index("last")
                insertCol.pop(i)
                last = insertData.pop(i)
                i = insertCol.index("email")
                insertCol.pop(i)
                email = insertData.pop(i)

                if first == "" or last == "" or email == "":
                    print("When Specifying a donation with a donor you must include a unique first,last,email set")
                    goodInsert = 0
                else:    
                    query = "SELECT id FROM donors WHERE first = ? AND last = ? AND email = ?"
                    executeTup = (first,last,email)
                    cursor.execute(query,executeTup)
                    donorId = cursor.fetchone()
                    if donorId == None:
                        goodInsert  = 0
                        print(f"Donor {first + ' ' + last} was not found")
                    else:
                        insertCol.append("donorID")
                        insertData.append(donorId[0])

            #deal with donation event relationship
            #need to write this part

            
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

        #attempts transaction commit
        try:
            conn.commit()
            print("The data for donations was commited")
        except Exception as e:
            conn.rollback()
            print("There was a problem")

def insertEvent(eventData,event_columns,conn,cursor):
    pass

#To insert a donor you need first,last,email
def insertDonor(donorData,donor_columns,conn,cursor):
    pass
