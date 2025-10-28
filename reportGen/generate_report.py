#!/usr/bin/env python3
import os
os.environ["MPLCONFIGDIR"] = "/Applications/XAMPP/xamppfiles/htdocs/CASA/reportGen/report_output"

import sys
import os
import pandas as pd
import numpy as np
import matplotlib
matplotlib.use("Agg")  # required for headless servers
import matplotlib.pyplot as plt
from reportlab.platypus import SimpleDocTemplate, Paragraph, Image, Spacer
from reportlab.lib.styles import getSampleStyleSheet
import mysql.connector

# --- Configuration ---
DB_CONFIG = {
    'host': 'localhost',
    'user': 'casadb',           # change if needed
    'password': 'casadb',           # or your MySQL password
    'database': 'casadb'  # your database name

}

BASE_DIR = os.path.dirname(os.path.abspath(__file__))
OUTPUT_DIR = os.path.join(BASE_DIR, "report_output")
os.makedirs(OUTPUT_DIR, exist_ok=True)
OUTPUT_PDF = os.path.join(OUTPUT_DIR, "report.pdf")

# --- Connect to MariaDB ---
try:
    conn = mysql.connector.connect(**DB_CONFIG)
except mysql.connector.Error as err:
    print(f"Database connection failed: {err}")
    sys.exit(1)

# --- Load data ---
query = "SELECT date, amount FROM donations;"  # adjust for your schema
df = pd.read_sql(query, conn)
conn.close()

# --- Create plot ---
plt.figure(figsize=(6,4))
df.groupby('date')['amount'].sum().plot(kind='bar')
plt.title('Total Donations Over Time')
plt.xlabel('Date')
plt.ylabel('Amount ($)')
plt.tight_layout()

plot_path = os.path.join(OUTPUT_DIR, "plot.png")
plt.savefig(plot_path)
plt.close()

# --- Generate PDF ---
doc = SimpleDocTemplate(OUTPUT_PDF)
styles = getSampleStyleSheet()
story = []

story.append(Paragraph("Donation Report", styles['Title']))
story.append(Spacer(1, 12))
story.append(Image(plot_path, width=400, height=300))
story.append(Spacer(1, 12))
story.append(Paragraph("Generated using Python, pandas, and seaborn/matplotlib.", styles['Normal']))

doc.build(story)

print(OUTPUT_PDF)
