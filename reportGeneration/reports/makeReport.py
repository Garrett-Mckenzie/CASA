from reportGen import *

try:
    #get options as specified by the user
    args = getArgs()

    ### data stuff ###
    #questions to answer with report:
        #top x donors by total amount donated
        #top x donors by avg amount donated
        #boxplots for top 5 cities and the average donation amount
        #same for zipcodes ^

        #given an event, total donations toward event y over time x (this might be included on the specific event page)
        #descriptive stats over sample dataset
            
        

    ### end data stuff ###

    #connect to database
    try:
        conn = connect()
    except Exception as e:
        raise e

    #make the doc
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
   
except Exception as e:
    traceback.print_exc()
