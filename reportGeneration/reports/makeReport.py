from importMeEthan import *
from importMeGarrett import *

try:
    #get options as specified by the user
    args = getArgs()
    
    #connect to database
    try:
        conn = connect()
    except Exception as e:
        raise e

    #make the doc
    name = None
    if "reportName" not in args.keys():
        time = str(datetime.now())
        date = time[0:time.index(" ")].replace("-","_")
        time = time[time.index(" ") + 1 : time.index(".")].replace(":","_")
        name = "NotNamed_"+date+time
    elif "reportName" in args.keys():
        name = args["reportName"]
    else:
        raise Exception("bad filename")

    doc = createPDF(name)
    
    #insert some text
    txt = "ethan = gay"
    insertWriting(txt,doc,style = "Normal")
    print(f"{name}.pdf")

    #insert a table
   
except Exception as e:
    traceback.print_exc()
