from importMeEthan import *
from importMeGarrett import *

try:
    #get options as specified by the user
    args = getArgs()

    ### data stuff ###
   

    ### end data stuff ###
    
    #connect to database
    #args['os'] = 'l'
    try:
        if args['os'] == 'w':
            conn = winConnect()
        else:
            conn = macConnect()
    except Exception as e:
        raise e

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
    pdf.insertTitle("CHEEEEEEESSSSSSSSSSSSSSSEEEEE!!!!!!!!!!!!!!")

    #Insert a subheading
    pdf.insertSubheading("Why I like it")

    #Insert a series of paragraphs
    pdf.insertParagraphs(["Because it is yummy. auidhcnaiuc niu d c hp hbcoer vhew ourhvbspi eruvpeirvpie uvhpeiufpeufv","And it is tasty."])

    #Insert a single paragraph
    pdf.insertParagraph("Y are U GAY????")

    #Insert a table (make sure to include header row in the matrix boy)
    data = [["PenarSize","FootSize"],[12,10],[40,12],[10000,10000]]
    cellWidths = [70,70]
    pdf.insertTable(data,cellWidths)

    #Insert a graph
    data = pd.DataFrame({"Height":[1,2,3,4,5,6,7,8,9,10],"Johnson":[2,4,6,8,10,12,14,16,18,20]})
    plot = sns.scatterplot(data=data,x="Height",y="Johnson")

    pdf.insertGraph(plot,5,5)

    #make the pdf (THIS MUST BE CALLED LAST)
    pdf.buildPDF()

    """
    This part is mandatory no touchie
    """
    print(f"{name}.pdf")
   
except Exception as e:
    traceback.print_exc()
