import smtplib
import sys
import json
from email.mime.text import MIMEText

def main():
    data = json.loads(sys.stdin.read())
    sender = data["sender"]
    recipient = data["recipient_email"]
    body = data["body"]

    msg = MIMEText(body)
    msg["Subject"] = "Message from CASA"
    msg["From"] = sender
    msg["To"] = recipient

    # Gmail SMTP
    with smtplib.SMTP_SSL("smtp.gmail.com", 465) as server:
        # replace below with your Gmail app password
        server.login(sender, "ghsb jsez nlzz vdjm")
        server.send_message(msg)

if __name__ == "__main__":
    main()
