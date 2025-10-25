import pandas as pd
import numpy as np
import seaborn as sns
import matplotlib.pyplot as plt
import sys
import plotly.express as px
from reportlab.lib.pagesizes import letter
from reportlab.platypus import SimpleDocTemplate, Table, TableStyle
from reportlab.lib import colors
from reportlab.pdfgen import canvas
import traceback
import mariadb

def connect():
    try:
        conn = mariadb.connect(
                user = "casadb",
                password = "casadb",
                host = "localhost",
                database = "casadb"
                )
        return conn
    except Exception as e:
        raise e

def executeQuery(query,conn):
    try:
        cur = conn.cursor()
        cur.execute(query)
        result = cur.fetchall()
        return result
    except Excetion as e:
        raise e

def getArgs():
    result = {}
    i = 0
    for cmd in sys.argv:
        if ".py" not in cmd:
            if ":" in cmd:
                result[cmd[0:cmd.index(":")]] = cmd[cmd.index(":") + 1 : ]
            else:
                result[i] = cmd
        i += 1
    return result

def createPDF(filepath):
    doc = SimpleDocTemplate()
    pass
def insertTable(data,left = 1):
    pass
def insertGraph(**kwargs):
    pass
