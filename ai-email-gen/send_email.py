import smtplib
import sys
import json
from email.mime.text import MIMEText
from dotenv import load_dotenv
import os

def main():
    load_dotenv()  # load EMAIL_USER and EMAIL_PASS from .env

    sender = os.getenv("SMTP_USER")
    password = os.getenv("SMTP_PASS")

    if not sender or not password:
        print("Missing EMAIL_USER or EMAIL_PASS in .env file")
        return

    data = json.loads(sys.stdin.read())
    recipient = data["recipient_email"]
    body = data["body"]

    msg = MIMEText(body)
    msg["Subject"] = "Message from CASA"
    msg["From"] = sender
    msg["To"] = recipient

    # Gmail SMTP
    with smtplib.SMTP_SSL("smtp.gmail.com", 465) as server:
        server.login(sender, password)
        server.send_message(msg)

    print("Email sent successfully.")

if __name__ == "__main__":
    main()
