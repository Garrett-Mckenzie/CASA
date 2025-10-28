#!/usr/bin/env python3
import os
import sys
import pandas as pd
import numpy as np
import matplotlib
matplotlib.use("Agg")  # required for headless servers
import matplotlib.pyplot as plt
from reportlab.platypus import SimpleDocTemplate, Paragraph, Image, Spacer
from reportlab.lib.styles import getSampleStyleSheet
from datetime import datetime
import mysql.connector
import importMeGarrett

# --- Logging for PHP debug ---
def log(msg):
    with open("/tmp/generate_report_debug.log", "a") as f:
        f.write(msg + "\n")

log("=== PYTHON STARTED ===")
log(f"Python executable: {sys.executable}")
log(f"CWD: {os.getcwd()}")
log(f"ARGV: {sys.argv}")

# --- Handle args from PHP ---
report_name = "report"
os_type = "u"
for arg in sys.argv[1:]:
    if arg.startswith("reportName:"):
        report_name = arg.split(":", 1)[1]
    elif arg.startswith("os:"):
        os_type = arg.split(":", 1)[1]

# --- Environment setup ---
BASE_DIR = os.path.dirname(os.path.abspath(__file__))
OUTPUT_DIR = os.path.join(BASE_DIR, "report_output")
os.makedirs(OUTPUT_DIR, exist_ok=True)

timestamp = datetime.now().strftime("%Y_%m_%d_%H_%M_%S")
OUTPUT_PDF = os.path.join(OUTPUT_DIR, f"{report_name}_{timestamp}.pdf")

# Fix matplotlib cache path for Apache/XAMPP (permissions issue)
os.environ["MPLCONFIGDIR"] = OUTPUT_DIR

# --- Database connection ---
DB_CONFIG = {
    'host': 'localhost',
    'user': 'casadb',
    'password': 'casadb',
    'database': 'casadb'
}

try:
    conn = mysql.connector.connect(**DB_CONFIG)
except mysql.connector.Error as err:
    log(f"Database connection failed: {err}")
    print(f"ERROR: {err}")
    sys.exit(1)

# --- Query ---
query = "SELECT date, amount FROM donations;"
try:
    df = pd.read_sql(query, conn)
    conn.close()
except Exception as e:
    log(f"SQL query failed: {e}")
    print(f"ERROR: {e}")
    sys.exit(2)

# --- Plot ---
try:
    plt.figure(figsize=(6,4))
    df.groupby('date')['amount'].sum().plot(kind='bar')
    plt.title('Total Donations Over Time')
    plt.xlabel('Date')
    plt.ylabel('Amount ($)')
    plt.tight_layout()

    plot_path = os.path.join(OUTPUT_DIR, f"{report_name}_{timestamp}.png")
    plt.savefig(plot_path)
    plt.close()
except Exception as e:
    log(f"Plot generation failed: {e}")
    print(f"ERROR: {e}")
    sys.exit(3)

# --- PDF ---
try:
    doc = SimpleDocTemplate(OUTPUT_PDF)
    styles = getSampleStyleSheet()
    story = []

    story.append(Paragraph("Donation Report", styles['Title']))
    story.append(Spacer(1, 12))
    story.append(Image(plot_path, width=400, height=300))
    story.append(Spacer(1, 12))
    story.append(Paragraph(f"Report Name: {report_name}", styles['Normal']))
    story.append(Paragraph("Generated using Python, pandas, and matplotlib.", styles['Normal']))

    doc.build(story)

    log(f"PDF successfully created: {OUTPUT_PDF}")
    print(os.path.basename(OUTPUT_PDF))  # <— print only filename for PHP to read
except Exception as e:
    log(f"PDF generation failed: {e}")
    print(f"ERROR: {e}")
    sys.exit(4)
