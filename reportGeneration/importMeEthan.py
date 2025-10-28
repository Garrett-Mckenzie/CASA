#try:

#data science stuff


import pandas as pd
import numpy as np
import seaborn as sns
import matplotlib.pyplot as plt

import plotly.express as px
import scipy.stats as stats

#pdf generation stuff with reportlab
from reportlab.platypus import SimpleDocTemplate, Table, TableStyle
from reportlab.platypus import Paragraph, Spacer, Image
from reportlab.lib.pagesizes import LETTER
from reportlab.lib import colors
from reportlab.pdfgen import canvas
from reportlab.lib.styles import getSampleStyleSheet
from reportlab.lib.styles import ParagraphStyle
from reportlab.lib.enums import TA_CENTER,TA_LEFT,TA_RIGHT
from reportlab.lib.units import inch

#other
from pathlib import Path
import sys
import os
import traceback
import mariadb
from datetime import datetime

#except Exception as e:
    #print(str(e))


def winConnect():
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
    
def macConnect():
    try:
        conn = mariadb.connect(
            user="casadb",
            password="casadb",
            host="localhost",
            database="casadb",
            unix_socket="/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock"
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
    except Exception as e:
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

class PDF:

    def __init__(self,name):
        self.doc = SimpleDocTemplate(f'reports/{name}.pdf',pagesize=LETTER)
        self.elements = []
        self.styles = getSampleStyleSheet()
        self.styles.add(ParagraphStyle(name=".Title", alignment=TA_CENTER, fontSize=18, spaceAfter=20))
        self.styles.add(ParagraphStyle(name=".BodyText", fontSize=12, leading=15))
        self.styles.add(ParagraphStyle(name = ".Subheading" ,alignment=TA_LEFT,fontSize=14,spaceBefore=12,spaceAfter=8,leading=16,underlineWidth=0.5))

    def insertTitle(self,txt):
        self.elements.append(Paragraph(txt,self.styles[".Title"]))

    def insertSubheading(self,txt):
        self.elements.append(Paragraph(txt,self.styles[".Subheading"]))

    #better make sure ts a list brotha. Every element in the list is its own paragraph
    def insertParagraphs(self,paragraph):
        for txt in paragraph:
            self.elements.append(Paragraph(txt,self.styles[".BodyText"]))
            self.elements.append(Spacer(1,12))
            
    def insertParagraph(self,txt):
        self.elements.append(Paragraph(txt,self.styles[".BodyText"]))

    def insertTable(self,data,cellWidths):
        table = Table(data , colWidths = cellWidths)
        table_style = TableStyle([
            ("BACKGROUND", (0, 0), (-1, 0), 
            colors.grey),
            ("TEXTCOLOR", (0, 0), (-1, 0), colors.whitesmoke),
            ("ALIGN", (0, 0), (-1, -1), "CENTER"),
            ("FONTNAME", (0, 0), (-1, 0), "Helvetica-Bold"),
            ("BOTTOMPADDING", (0, 0), (-1, 0), 10),
            ("BACKGROUND", (0, 1), (-1, -1), colors.whitesmoke),
            ("GRID", (0, 0), (-1, -1), 0.5, colors.grey),
            ("BOX", (0,0),(-1,-1),1.0,colors.black),
            ("INNERGRID",(0,0),(-1,-1),0.5,colors.grey)
            ])
        table.setStyle(table_style)
        self.elements.append(table)
        self.elements.append(Spacer(1,12))
    
    def insertGraph(self,plot,width,height):
        plt.savefig("./reports/plot.png") 
        self.elements.append(Image("reports/plot.png",width=width*inch,height=height*inch))
    
    #This method actually makes the pdf
    def buildPDF(self):
        self.doc.build(self.elements)
