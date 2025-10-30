
try:
    from importMeEthan import *
    from importMeGarrett import *
    import time

    #get options as specified by the user
    args = getArgs()

    ### data stuff ###
   

    ### end data stuff ###
    
    #connect to database

    #args['os'] = 'l'
    if 'os' in args.keys() and args['os'] == 'w':
        conn = winConnect()
    else:
        conn = macConnect()

    #Make the pdf object
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
    pdf = PDF(name); 

    """
    Everything below is example of using the methods of the pdf class
    to write to the pdf. Replace this shiii with yo analysis fool.
    """ 
    #Insert a title
    pdf.insertTitle("HELLO CASA PEOPLE THIS IS AN EXAMPLE")

    #Insert a subheading
    pdf.insertSubheading("This is a Subheading")

    #Insert a series of paragraphs
    pdf.insertParagraphs(["CASA is a very cool organization and we are excited to be working with them. Sea of Thieves is also cool. It is a very fun video game where you drive a BOAT around and dig up treasure."])

    #Insert a single paragraph
    pdf.insertParagraph("Hey would you look at that we can have multiple paragraphs in a document isnt that cool. I am now going to say gibberish to demonstrate is working. odcnapidcnaipsdcnapisdjcasdc asdic asdc asc nqic asc adc iadcp ad apnc ap apicj jdcn apdijc a.")
    #Insert a table (make sure to include header row in the matrix boy)
    pdf.insertSubheading("Hey a table thats cool!!")
    data = [["Money","Donors"],[1,10],[2,20],[3,30],[4,40],[5,50]]
    cellWidths = [70,70]
    pdf.insertTable(data,cellWidths)

    #Insert a graph
    data = pd.DataFrame({"Height":[1,2,3,4,5,6,7,8,9,10],"Width":[20,44,60,81,10,12,14,16,18,20]})
    sns.scatterplot(data=data,x="Height",y="Width")
    pdf.insertGraph(3,3) 

    #make the pdf (THIS MUST BE CALLED LAST)
    pdf.buildPDF()

    """
    This part is mandatory no touchie
    """
    print(f"{name}.pdf")
   
except Exception as e:
    traceback.print_exc()
    raise e
