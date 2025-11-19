import smtplib
import sys
import json
from email.mime.text import MIMEText
from dotenv import load_dotenv
import os
import traceback

def main():
    try:
        load_dotenv()

        smtp_server = os.getenv("SMTP_SERVER", "smtp.gmail.com")
        smtp_port   = int(os.getenv("SMTP_PORT", "587"))
        sender      = os.getenv("SMTP_USER")
        password    = os.getenv("SMTP_PASS")

        if not sender or not password:
            print("ERROR: Missing SMTP_USER or SMTP_PASS")
            sys.exit(1)

        raw = sys.stdin.read()
        data = json.loads(raw)

        recipient = data["recipient_email"]
        body      = data["body"]

        msg = MIMEText(body)
        msg["Subject"] = "Message from CASA"
        msg["From"]    = sender
        msg["To"]      = recipient

        with smtplib.SMTP(smtp_server, smtp_port) as server:
            server.ehlo()
            server.starttls()
            server.login(sender, password)
            server.send_message(msg)

        print("Email sent successfully.")
        sys.exit(0)

    except Exception as e:
        print("ERROR:", str(e))
        traceback.print_exc()
        sys.exit(1)

if __name__ == "__main__":
    main()
