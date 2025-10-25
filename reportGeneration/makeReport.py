from reportGen import *

"""
result = None
    try:
       result = executeQuery("select * from donations",conn) 
    except Exception as e:
        raise e
    print(result)
"""

try:
    #get options as specified by the user
    args = getArgs()

    #connect to database
    try:
        conn = connect()
    except Exception as e:
        raise e

    
except Exception as e:
    traceback.print_exc()
