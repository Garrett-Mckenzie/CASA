# Server interaction script
import sys
from importlib.metadata import distributions
import subprocess
from pathlib import Path
from credentials import HOST, USER, PASSWORD, DATABASE
import pandas as pd
from sqlalchemy import create_engine
from sqlalchemy.exc import SQLAlchemyError

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


# Export
def export_excel():
    query = sys.argv[2]
    try:
        conn = winConnect():
        conn.
        output_file = sys.argv[3]
        df.to_excel(output_file, index=False)
    except Exception as e:
        print(str(e))
        raise e
        

   # Import
def import_excel():
    for arg in sys.argv[2:]:
        required_columns = ['Amount', 'Reason', 'Date', 'Fee', 'First', 'Last', 'Email', 'ZIP', 'City', 'State', 'Street', 'Phone', 'Gender', 'Notes']
        try:
            # MySQL
            url = f"mysql+mysqlconnector://{USER}:{PASSWORD}@{HOST}/{DATABASE}"
            engine = create_engine(url , echo=True)

            with engine.connect() as connection:
                print("gello")
                file = pd.read_excel(arg)
                for index, row in file.iterrows():
                    req = {
                            'amount': None,
                            'reason': None,
                            'date': None,
                            'fee': None,
                            'first': None,
                            'last': None,
                            'email': None,
                            'zip': None,
                            'city': None,
                            'state': None,
                            'street': None,
                            'phone': None,
                            'gender': None,
                            'notes': None,
                            }
                    for item in required_columns:
                        try:
                            req[item] = row[item]
                        except Exception as ignored:
                            pass
                    if req['amount'] == None:
                        return False
                    query = "INSERT INTO donor (" + ((req['amount'] + ",") if req['amount'] != None else "") + ((req['reason'] + ",") if req['reason'] != None else "") + ((req['date'] + ",") if req['date'] != None else "") + ((req['fee'] + ",") if req['fee'] != None else "") + ((req['first'] + ",") if req['first'] != None else "") + ((req['last'] + ",") if req['last'] != None else "") + ((req['email'] + ",") if req['email'] != None else "") + ((req['zip'] + ",") if req['zip'] != None else "") + ((req['city'] + ",") if req['city'] != None else "") + ((req['state'] + ",") if req['state'] != None else "") + ((req['street'] + ",") if req['street'] != None else "") + ((req['phone'] + ",") if req['phone'] != None else "") + ((req['gender'] + ",") if req['gender'] != None else "") + ((req['notes'] + ",") if req['notes'] != None else "")

#            if(connection.is_connected()):
#                # SQL
#                file = pd.read_excel(arg)
#                for index, row in file.iterrows():
#                    req = {
#                        'amount': None,
#                        'reason': None,
#                        'date': None,
#                        'fee': None,
#                        'first': None,
#                        'last': None,
#                        'email': None,
#                        'zip': None,
#                        'city': None,
#                        'state': None,
#                        'street': None,
#                        'phone': None,
#                        'gender': None,
#                        'notes': None,
#                    }
#                    for item in required_columns:
#                        try:
#                            req[item] = row[item]
#                        except Exception as ignored:
#                            pass
#                    if req['amount'] == None:
#                        return False
#                    query = "INSERT INTO donor (" + ((req['amount'] + ",") if req['amount'] != None else "") + ((req['reason'] + ",") if req['reason'] != None else "") + ((req['date'] + ",") if req['date'] != None else "") + ((req['fee'] + ",") if req['fee'] != None else "") + ((req['first'] + ",") if req['first'] != None else "") + ((req['last'] + ",") if req['last'] != None else "") + ((req['email'] + ",") if req['email'] != None else "") + ((req['zip'] + ",") if req['zip'] != None else "") + ((req['city'] + ",") if req['city'] != None else "") + ((req['state'] + ",") if req['state'] != None else "") + ((req['street'] + ",") if req['street'] != None else "") + ((req['phone'] + ",") if req['phone'] != None else "") + ((req['gender'] + ",") if req['gender'] != None else "") + ((req['notes'] + ",") if req['notes'] != None else "")
#                    #query = "INSERT INTO donor () VALUE ()"
#                    cursor = connection.cursor()
#                    cursor.execute(query)
#                    connection.commit()
 #               connection.close()
 #           else: return False
        except Exception as e:
            return False
    return True

# Main
def main():
    # Check file format
    ret = 1
    if len(sys.argv) < 2 or (sys.argv[1] == "-h" or sys.argv[1] == "--help"):
        print("Usage:\nCASA_DB_Calls.py -i|--import <file1.xlsx> [<file2.xlsx> ...]\nCASA_DB_Calls.py -e|--export <file1.xlsx> [<file2.xlsx> ...]")
        return 1

    if sys.argv[1] == "-i" or sys.argv[1] == "--import":
        for arg in sys.argv[2:]:
            if not Path(arg).is_file() or not arg.endswith('.xlsx'):
                print(f"Error: {arg} is not a valid .xlsx file")
                return 1
        ret = import_excel()
        print("Made It!")
    elif sys.argv[1] == "-e" or sys.argv[1] == "--export":
        if len(sys.argv) != 4:
            print("Error: Incorrect format")
        ret = export_excel()
    else:
        print("Error: Invalid option. Use -h or --help for usage information.")
        return 1

        return 0 if ret else 1

if __name__ == "__main__":
    main()
