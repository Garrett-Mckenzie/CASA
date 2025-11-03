# Server interaction script
import sys
from importlib.metadata import distributions
import subprocess
from pathlib import Path
from credentials import HOST, USER, PASSWORD, DATABASE

# Checks for dependencies and installs them if not present
installed_pd = False
installed_mysql = False
installed_alchemy = False
installed_pyxl = False
installed_sql = False
for dist in distributions():
    if dist.metadata['Name'] == 'pandas':
        installed_pd = True
    if dist.metadata['Name'] == 'mysql-connector-python':
        installed_mysql = True
    if dist.metadata['Name'] == 'SQLAlchemy':
        installed_alchemy = True
    if dist.metadata['Name'] == 'openpyxl':
        installed_pyxl = True
    if dist.metadata['Name'] == 'mysql':
        installed_sql = True

if not installed_pd:
    try:
        subprocess.check_call([sys.executable, '-m', 'pip', 'install', 'pandas'])
    except subprocess.CalledProcessError:
        subprocess.check_call([sys.executable, '-m', 'pip3', 'install', 'pandas'])

if not installed_mysql:
    try:
        subprocess.check_call([sys.executable, '-m', 'pip', 'install', 'mysql-connector-python'])
    except subprocess.CalledProcessError:
        subprocess.check_call([sys.executable, '-m', 'pip3', 'install', 'mysql-connector-python'])


if not installed_alchemy:
    try:
        subprocess.check_call([sys.executable, '-m', 'pip', 'install', 'SQLAlchemy'])
    except subprocess.CalledProcessError:
        subprocess.check_call([sys.executable, '-m', 'pip3', 'install', 'SQLAlchemy'])

if not installed_pyxl:
    try:
        subprocess.check_call([sys.executable, '-m', 'pip', 'install', 'openpyxl'])
    except subprocess.CalledProcessError:
        subprocess.check_call([sys.executable, '-m', 'pip3', 'install', 'openpyxl'])

if not installed_sql:
    try:
        subprocess.check_call([sys.executable, '-m', 'pip', 'install', 'mysql'])
    except subprocess.CalledProcessError:
        subprocess.check_call([sys.executable, '-m', 'pip3', 'install', 'mysql'])

import pandas as pd
from sqlalchemy import create_engine, text
from sqlalchemy.exc import SQLAlchemyError




# Export
def export_excel():
    engine = None
    try:
        url = f"mysql+mysqlconnector://{USER}:{PASSWORD}@{HOST}/{DATABASE}"
        engine = create_engine(url)
        query = sys.argv[2]
        print(f"Engine URL: {url}")
        print(f"Engine: {engine}")

        with engine.connect() as connection:
            df = pd.read_sql_query(query, connection)
            output_file = sys.argv[3]
            df.to_excel(output_file, index=False)

    except SQLAlchemyError as e:
        print(f"Error connecting to database: {e}")
        return False
    except Exception as e:
        print(f"General error: {e}")
        return False
    finally:
        if engine is not None:
            engine.dispose()
    return True

# Import
# Donor Import: first, last, email, zip, city, state, street, phone, gender, notes
# Donations Import: amount, reason, date, fee, thanked
def import_excel(doclist : list):
    for doc in doclist:
        engine = None
        try:
            url = f"mysql+mysqlconnector://{USER}:{PASSWORD}@{HOST}/{DATABASE}"
            engine = create_engine(url)
            with engine.connect() as connection:
                donor_data = {}
                donation_data = {}

                df = pd.read_excel(doc)
                for index, row in df.iterrows():

                    # Import donor data
                    donor_data = {
                        "first": row.get('first'),
                        "last": row.get('last'),
                        "email": row.get('email'),
                        "zip": row.get('zip'),
                        "city": row.get('city'),
                        "state": row.get('state'),
                        "street": row.get('street'),
                        "phone": row.get('phone'),
                        "gender": row.get('gender'),
                        "notes": row.get('notes')
                    }

                    # Remove None values
                    donor_data = {k: v for k, v in donor_data.items() if v is not None}
                    columns = ', '.join(donor_data.keys())
                    placeholders = ', '.join([f":{k}" for k in donor_data.keys()])

                    # Query and Execute
                    donorQuery = f"INSERT INTO donors ({columns}) VALUES ({placeholders})"
                    connection.execute(text(donorQuery), donor_data)
                    connection.commit()



                    # Import donation data
                    donation_data = {
                        "amount": row.get('amount'),
                        "reason": row.get('reason'),
                        "date": row.get('date'),
                        "fee": row.get('fee'),
                        "thanked": row.get('thanked')
                    }

                    # Remove None values
                    donation_data = {k: v for k, v in donation_data.items() if v is not None}
                    columns = ', '.join(donation_data.keys())
                    placeholders = ', '.join([f":{k}" for k in donation_data.keys()])

                    # Query and Execute
                    donationQuery = f"INSERT INTO donations ({columns}) VALUES ({placeholders})"
                    connection.execute(text(donationQuery), donation_data)
                    connection.commit()

        # Exception handling
        except SQLAlchemyError as e:
            print(f"Error connecting to database: {e}")
            if connection: # type: ignore
                connection.rollback()
            return False
        except Exception as e:
            print(f"Error: {e}")
            if connection: # type: ignore
                connection.rollback()
            return False
        finally:
            if engine is not None:
                engine.dispose()

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
        doclist = sys.argv[2:]
        ret = import_excel(doclist)
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