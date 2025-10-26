#data science stuff
import pandas as pd
import numpy as np
import seaborn as sns
import matplotlib.pyplot as plt
import plotly.express as px
import scipy.stats as stats

#pdf generation stuff with reportlab
from reportlab.platypus import SimpleDocTemplate, Table, TableStyle
from reportlab.platypus import Paragraph, Spacer
from reportlab.lib.pagesizes import LETTER
from reportlab.lib import colors
from reportlab.pdfgen import canvas
from reportlab.lib.styles import getSampleStyleSheet

#other
import sys
import traceback
import mariadb
from datetime import datetime

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

#icky solution but just role with it
def getArgs():
    result = {}
    i = 0
    name = ""
    inName = 0
    for cmd in sys.argv:
        if ".py" not in cmd:
            if "reportName" in cmd or inName == 1:
                if ":" in cmd and inName == 0:
                    name += str(cmd[cmd.index(":") + 1 : ])+"_"
                    inName = 1;
                    result["reportName"]=name[0:-1]
                elif ":" in cmd and inName == 1:
                    result["reportName"]=name[0:-1]
                    inName = 0;
                else:
                    name += str(cmd)+"_"
            if ":" in cmd:
                result[cmd[0:cmd.index(":")]] = cmd[cmd.index(":") + 1 : ]
            elif inName == 0:
                result[i] = cmd
        if name != "":
            result["reportName"]=name[0:-1]
        i += 1            
    return result

def createPDF(name):
    doc = SimpleDocTemplate(f'reports/{name}.pdf',pagesize=LETTER)
    return doc

def insertWriting(txt,doc,style = "Normal"):
    styles = getSampleStyleSheet()
    story = []
    story.append(Paragraph(txt,styles[style])) 
    story.append(Spacer(1,12))
    doc.build(story)
    return doc
def insertTable(data):
    pass
def insertGraph(**kwargs):
    pass
