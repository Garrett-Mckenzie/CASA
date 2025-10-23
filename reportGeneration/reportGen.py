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
from io import BytesIO
from PIL import Image
from PIL import ImageReader

try:
    # Example Code
    # Create a PDF document
    pdf_file = "example.pdf"
    doc = SimpleDocTemplate(pdf_file, pagesize=letter)

    # Data for the table
    data = [
            ["Name", "Age", "City"],
            ["Alice", 30, "New York"],
            ["Bob", 25, "Los Angeles"],
            ["Charlie", 35, "Chicago"]
            ]

    # Create a table
    table = Table(data)

    # Define table style
    style = TableStyle([
        ('BACKGROUND', (0, 0), (-1, 0), colors.grey), # Header background color
        ('TEXTCOLOR', (0, 0), (-1, 0), colors.whitesmoke), # Header text color
        ('ALIGN', (0, 0), (-1, -1), 'CENTER'), # Center align all cells
        ('FONTNAME', (0, 0), (-1, 0), 'Helvetica-Bold'), # Header font
        ('BOTTOMPADDING', (0, 0), (-1, 0), 12), # Padding for header
        ('GRID', (0, 0), (-1, -1), 1, colors.black) # Add grid lines
        ])

    # Apply style to the table
    table.setStyle(style)


    # Add the table to the document elements
    elements = [table]

    # Build the PDF
    doc.build(elements)

    # Make a plot
    x = [1,2,3,4,5,6,7,8,9]
    y = [1,2,3,4,5,6,7,8,9]
    sns.scatterplot(x=x,y=y)
    buf = BytesIO()
    plt.savefig(buf,format='png')

    img = Image.open(buf)
    c = canvas.Canvas("example.pdf", pagesize=letter)
    width , height = letter

    # Add plot to pdf
    c.drawImage(ImageReader(img))
    c.save()

    print(f"PDF generated: {pdf_file}")
except Exception as e:
    print(f"Error Type: {type(e).__name__}, Message: {str(e)}")
