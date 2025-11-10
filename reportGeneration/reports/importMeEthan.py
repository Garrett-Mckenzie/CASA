import os, tempfile, sys

# Force Matplotlib to use a safe backend and writable cache before anything else
# makes a tmp directory where the system can write to it without explicit perms
os.environ["MPLCONFIGDIR"] = os.path.join(tempfile.gettempdir(), "matplotlib")
os.environ["MPLBACKEND"] = "Agg"

import matplotlib
#required for xampp to not barf with matplotlib (something about xampp being headless)
matplotlib.use("Agg")

try:


    #data science stuff
    import pandas as pd
    import numpy as np
    import matplotlib.pyplot as plt
    import seaborn as sns
    

    #pdf generation stuff with reportlab
    from reportlab.platypus import SimpleDocTemplate, Table, TableStyle
    from reportlab.platypus import Paragraph, Spacer, Image, PageBreak
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

except Exception as e:
    print(str(e))


try:
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
            self.story = []
            self.doc = SimpleDocTemplate(f'./reports/{name}.pdf',pagesize=LETTER)
            self.elements = []
            self.styles = getSampleStyleSheet()
            self.styles.add(ParagraphStyle(name=".Title", alignment=TA_CENTER, fontSize=18, spaceAfter=20))
            self.styles.add(ParagraphStyle(name=".BodyText", fontSize=12, leading=15))
            self.styles.add(ParagraphStyle(name = ".Subheading" ,alignment=TA_LEFT,fontSize=14,spaceBefore=12,spaceAfter=8,leading=16,underlineWidth=0.5))

        def insertTitle(self, title):
            self.story.append(Spacer(1, 0.3 * inch))
            self.story.append(Paragraph(
                f"<b><font size=22 color='#2E4053'>{title}</font></b>", self.styles["Title"]
            ))
            self.story.append(Spacer(1, 0.25 * inch))
        def insertPageBreak(self):
            self.story.append(PageBreak())

        def insertSubheading(self, text):
            self.story.append(Spacer(1, 0.25 * inch))
            self.story.append(Paragraph(
                f"<b><font size=14 color='#1F618D'>{text}</font></b>", self.styles["Heading2"]
            ))
            self.story.append(Spacer(1, 0.15 * inch))

        def insertParagraph(self, text):
            self.story.append(Paragraph(text, self.styles["Normal"]))
            self.story.append(Spacer(1, 0.1 * inch))

        def insertParagraphs(self, paragraphs):
            for p in paragraphs:
                self.insertParagraph(p)

        def insertGraph(self, width=4, height=3, index=1):
            img_path = f"./reports/temp_plot_{index}.png"
            plt.savefig(img_path, bbox_inches="tight")
            plt.close()
            self.story.append(Image(img_path, width * inch, height * inch))
            self.story.append(Spacer(1, 0.25 * inch))

        def insertTable(self, data, colWidths=None):
            if not colWidths:
                colWidths = [80] * len(data[0])
            table = Table(data, colWidths=colWidths)
            style = TableStyle([
                ("BACKGROUND", (0, 0), (-1, 0), colors.HexColor("#1F618D")),
                ("TEXTCOLOR", (0, 0), (-1, 0), colors.white),
                ("ALIGN", (0, 0), (-1, -1), "CENTER"),
                ("GRID", (0, 0), (-1, -1), 0.5, colors.grey),
                ("FONTNAME", (0, 0), (-1, 0), "Helvetica-Bold"),
                ("BOTTOMPADDING", (0, 0), (-1, 0), 6),
                ("BACKGROUND", (0, 1), (-1, -1), colors.HexColor("#EBF5FB"))
            ])
            table.setStyle(style)
            self.story.append(table)
            self.story.append(Spacer(1, 0.2 * inch))
            
        #This method actually makes the pdf
        def buildPDF(self):
            self.doc.build(self.elements)
            self.doc.build(self.story) #using stories
except Exception as e:
    print(str(e))
    raise e