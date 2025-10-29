# /Applications/XAMPP/xamppfiles/htdocs/CASA/reportGeneration/reports/test_exec.py
import pandas as pd
import numpy as np
import seaborn as sns
import matplotlib.pyplot as plt
import plotly.express as px
import scipy.stats as stats
import mysql.connector
import pprint

import importMeGarrett as data

print("Hello from Python!")

DB_CONFIG = {
    'host': 'localhost',
    'user': 'casadb',
    'password': 'casadb',
    'database': 'casadb'
}
try:
    conn = mysql.connector.connect(**DB_CONFIG)
    cur = conn.cursor()
except mysql.connector.Error as err:
    print(f"ERROR: {err}")

print("numDonationsOverTime ",end="")
cur.execute("select date from donations where date is not null")
print(data.numDonationsOverTime(cur.fetchall()))

print("totalDonors ",end="")
cur.execute("select * from donors")
print(data.totalDonors(cur.fetchall()))

print("avg donation ",end="")
cur.execute("select amount from donations where amount is not null")
print(data.avgDonation(cur.fetchall()))

print("med donation ",end="")
cur.execute("select amount from donations where amount is not null")
print(data.medDonation(cur.fetchall()))

print("number donation growth, quarterly ",end="")
cur.execute("select date from donations where date is not null")
print(data.donationGrowth(cur.fetchall(),"q"))

print("number donation growth, yearly ",end="")
cur.execute("select date from donations where date is not null")
print(data.donationGrowth(cur.fetchall(),"y"))

print("fundraiser completion % for each event")
cur.execute("select id,name, goalAmount from dbevents where goalAmount is not null")
rows1 = cur.fetchall()
cur.execute("select amount, eventID from donations where amount is not null and eventID is not null")
rows2 = cur.fetchall()
pprint.pprint(data.goalAchievementRate(rows1,rows2))

print("total completion: ",end="")
print(data.totalCompletion(rows1,rows2))

print("# new donors in last (m,q,y): ",end="")

print(data.donorAcqRate())

cur.close()
conn.close()
