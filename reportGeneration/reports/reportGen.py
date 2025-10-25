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

def createPDF(name):
    doc = SimpleDocTemplate(f'{name}.pdf',pagesize=LETTER)
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
