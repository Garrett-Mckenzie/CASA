import traceback
try:
    from importMeEthan import *  # must come before importMeGarrett
    from importMeGarrett import *
    import time

    # ---------------------------------------------------------------
    # ARGUMENTS & CONNECTION
    # ---------------------------------------------------------------
    args = getArgs()

    conn = connect()
    cur = conn.cursor(buffered=True)

    # ---------------------------------------------------------------
    # report content control and global variables
    # ---------------------------------------------------------------
    startDate = "NoStart"
    if "startDate" in args.keys():
        startDate = args["startDate"]
    
    endDate = "NoEnd"
    if "startDate" in args.keys():
        endDate = args["endDate"]

    DonationStatOverview = True
    if "DonationStatsOverview" not in args.keys():
        DonationStatOverview = False

    GrowthAndTrends = True
    if "GrowthAndTrends" not in args.keys():
        GrowthAndTrends = False
    
    IncludeGraph = True
    if "IncludeGraph" not in args.keys():
        IncludeGraph = False

    IncludeTable = True
    if "IncludeTable" not in args.keys():
        IncludeTable = False

    NumTopDonors = 10
    if "NumTopDonors" in args.keys():
        NumTopDonors = int(args["NumTopDonors"])

    IncludeNewDonors = True
    if "IncudeNewDonors" not in args.keys():
        IncludeNewDonors = False

    IncludePareto = True
    if "IncludePareto" not in args.keys():
        IncludePareto = False

    IncludeFunnel = True
    if "IncludeFunnel" not in args.keys():
        IncludeFunnel = False

    IncludeDonationsByState = True
    if "IncludeDonationsByState" not in args.keys():
        IncludeDonationsByState = False

    IncludeDonationsByZip = True
    if "IncludeDonationsByZip" not in args.keys():
        IncludeDonationsByZip = False

    IncludeSummarySection = True
    if "IncludeSummarySection" not in args.keys():
        IncludeSummarySection = False

    graphDesc = True
    if "graphDesc" not in args.keys():
        graphDesc = False
    
    checkAll = True
    if "checkAll" not in args.keys():
        checkAll = False

    section = 1

    # ---------------------------------------------------------------
    # PDF INIT
    # ---------------------------------------------------------------
    if "reportName" not in args.keys():
        timeNow = str(datetime.now())
        date = timeNow[0:timeNow.index(" ")].replace("-", "_")
        hour = timeNow[timeNow.index(" ") + 1:timeNow.index(".")].replace(":", "_")
        name = "NotNamed_" + date + "_" + hour
    else:
        name = args["reportName"]

    pdf = PDF(name)
    pdf.insertTitle("CASA Donations & Fundraising Report")

    # ---------------------------------------------------------------
    # DATA QUERIES
    # ---------------------------------------------------------------
    cur.execute("SELECT date FROM donations WHERE date IS NOT NULL")
    donationDates = cur.fetchall()

    cur.execute("SELECT * FROM donors")
    donorRows = cur.fetchall()

    cur.execute("SELECT amount FROM donations WHERE amount IS NOT NULL")
    donationAmounts = cur.fetchall()

    cur.execute("SELECT amount, date FROM donations WHERE date IS NOT NULL")
    amountDate = cur.fetchall()

    cur.execute("SELECT donorID, MIN(STR_TO_DATE(date,'%m/%d/%Y')) AS date FROM donations WHERE date IS NOT NULL GROUP BY donorID ORDER BY date")
    donorFirstDates = cur.fetchall()

    cur.execute("SELECT id, name, goalAmount FROM dbevents WHERE goalAmount IS NOT NULL")
    eventRows = cur.fetchall()

    cur.execute("SELECT amount, eventID FROM donations WHERE amount IS NOT NULL AND eventID IS NOT NULL")
    donationEventRows = cur.fetchall()

    cur.execute("""
    SELECT d.id AS donorID, d.first, d.last, dn.amount
    FROM donations AS dn
    JOIN donors AS d ON dn.donorID = d.id
    """)
    donorAmountRows = cur.fetchall()

    cur.execute("""SELECT d.id AS donorID, d.state, SUM(dn.amount)
            FROM donations AS dn
            JOIN donors AS d ON dn.donorID = d.id
            GROUP BY state""")
    donorSumByState = cur.fetchall()

    cur.execute("""SELECT d.id AS donorID, d.zip, SUM(dn.amount)
            FROM donations AS dn
            JOIN donors AS d ON dn.donorID = d.id
            GROUP BY zip""")
    donorSumByZip = cur.fetchall()

    conn.close()

    # ---------------------------------------------------------------
    # SECTION: Overview Stats
    # ---------------------------------------------------------------
    if DonationStatOverview or checkAll:
        pdf.insertSubheading(f"{section}. Overview of CASA Donations")

        totals = numDonationsOverTime(donationDates)
        pdf.insertParagraphs([
            f"In the last month, CASA received {totals[0]} donations.",
            f"In the last quarter, {totals[1]} donations were recorded.",
            f"In the last year, {totals[2]} donations were recorded."
        ])

        avg = avgDonation(donationAmounts)
        med = medDonation(donationAmounts)
        donors = totalDonors(donorRows)
        total = totalRaised(amountDate, "a", 0)

        pdf.insertParagraphs([
            f"CASA currently has {donors} total donors.",
            f"The average donation amount is ${avg}.",
            f"The median donation amount is ${med}.",
            f"The total raised all-time is ${total}."
        ])

        section += 1

    # ---------------------------------------------------------------
    # SECTION: Growth & Trends
    # ---------------------------------------------------------------

    if (GrowthAndTrends) or checkAll:

        pdf.insertSubheading(f"{section}. Growth & Trends")
        yGrowth = donationGrowth(donationDates, "y")
        qGrowth = donationGrowth(donationDates, "q")

        pdf.insertParagraphs([
            f"Year-over-year donation growth rate: {yGrowth}%.",
            f"Quarter-over-quarter donation growth rate: {qGrowth}%."
        ])

        fig_hist = chartNumDonations(donationDates, "y", "hist", 1)
        pdf.insertGraph(4, 3, 1)

        fig_line = chartNumDonations(donationDates, "y", "line", 1)
        pdf.insertGraph(4, 3, 2)
        if graphDesc or checkAll:
            pdf.insertParagraph(
                "The two graphs above display the frequency of donations over two month periods."
                "On the y-axis is the number of donations and on the x-axis is the 2 month time frame in consideration."
                "Both graphs represent the same underlying information.The only diffrence is that the first uses bars and the second uses a line."
            )
        section += 1

    # ---------------------------------------------------------------
    # SECTION: Fundraiser Performance
    # ---------------------------------------------------------------
    if IncludeGraph or IncludeTable or checkAll:

        pdf.insertSubheading(f"{section}. Fundraiser Performance")

        completionRate = totalCompletion(eventRows, donationEventRows)
        pdf.insertParagraph(f"Overall, {completionRate}% of CASA fundraisers have achieved or exceeded their fundraising goals.")

        fig_fund = chartFundraiserGoals(eventRows, donationEventRows)
        if IncludeGraph or checkAll:
            pdf.insertGraph(4, 3, 3)
            if graphDesc or checkAll:

                pdf.insertParagraph(
                    "This figure compares target fundraising amounts to the actual amount raised for each event within the timeframe of the report."
                    "The y-axis denotes the total amount of money raised for that event while the x-axis denotes the name of the event in question."
                )

        if IncludeTable or checkAll:

            completionDF = goalAchievementRate(eventRows, donationEventRows)
            pdf.insertTable(
                [["Event Name", "Completion (%)"]] +
                completionDF[["name", "completion"]].values.tolist(),
                [175, 100]
            )
        section += 1

    # ---------------------------------------------------------------
    # SECTION: Donor Insights
    # ---------------------------------------------------------------
    
    if NumTopDonors > 0 or IncludeNewDonors or checkAll:
        pdf.insertSubheading(f"{section}. Donor Insights")

        top = topDonors(donorAmountRows, top_n=NumTopDonors) 
        pdf.insertParagraph(f"Top {NumTopDonors} donors by total contributions:")

        pdf.insertTable(
            [["Donor Name", "Total Donated ($)"]] +
            top[["fullName", "totalAmount"]].values.tolist(),
            [175, 100]
        )

        if IncludeNewDonors or checkAll:

            newDonors = donorAcqRate(donorFirstDates)
            pdf.insertParagraphs([
                f"New donors in the past month: {newDonors[0]}",
                f"New donors in the past quarter: {newDonors[1]}",
                f"New donors in the past year: {newDonors[2]}"
            ])
        section += 1

    # ---------------------------------------------------------------
    # SECTION: Visual Analytics
    # ---------------------------------------------------------------
    if (IncludePareto or IncludeFunnel or IncludeDonationsByState or IncludeDonationsByZip or checkAll):
        
        pdf.insertSubheading(f"{section}. Visual Analytics")

        if IncludePareto or checkAll:

            fig_pareto = chartParetoTopDonors(donorAmountRows,NumTopDonors)
            pdf.insertGraph(4, 3, 4)
            if graphDesc or checkAll:

                pdf.insertParagraph(
                    "This figure displays information about CASA's top donors as well as what % of total dollars donated to CASA the collection of top donors represents."
                    "The x-axis is the name of each respective donor while the left-hand y-axis is the amount of money a specific donor gave."
                    "The right-hand y-axis tells what % of total money donated to CASA is made up of the donations from donors up to and including the donor along the x-axis that is in line with that point on that Pareto chart."
                )
        
        if IncludeFunnel or checkAll:

            fig_funnel = chartDonorFunnel(donorAmountRows)
            pdf.insertGraph(4, 3, 5)
            if graphDesc or checkAll:

                pdf.insertParagraph(
                    "This graph displays donors grouped by the frequency of their donations."
                    "On the y-axis is what group is being represented."
                    "Note that a repeat donor is a donor who has donated more than once. On the other hand, frequent donors are those who have donated 5 or more times "
                    "Finally, the x-axis displays the number of donors falling into each respective group."
                )

        if IncludeDonationsByState or checkAll:

            fig_geo = plotGeoDistributionBar(donorSumByState)
            pdf.insertGraph(4, 3, 6)
            if graphDesc or checkAll:

                pdf.insertParagraph(
                    "This graph represents the total amount of money donated by the 10 states with the most cummulative donations."
                    "On the y-axis is the amount of money donated by a certain state."
                    "On the x-axis is the state being refrenced."
                )
        
        if IncludeDonationsByZip or checkAll:

            fig_geo = plotGeoDistributionBar(donorSumByZip,"zipcode")
            pdf.insertGraph(4, 3, 7)
            if graphDesc or checkAll:

                pdf.insertParagraph(
                    "This graph represents the total amount of money donated by the 10 zipcodes with the most cummulative donations."
                    "On the y-axis is the amount of money donated by a certain zip."
                    "On the x-axis is the zipcode being refrenced."

                )
        section += 1

    # ---------------------------------------------------------------
    # SECTION: Summary
    # ---------------------------------------------------------------
    if IncludeSummarySection or checkAll:

        """
        totals = numDonationsOverTime(donationDates)
        pdf.insertParagraphs([
            f"In the last month, CASA received {totals[0]} donations.",
            f"In the last quarter, {totals[1]} donations were recorded.",
            f"In the last year, {totals[2]} donations were recorded."
        ])
        """

        avg = avgDonation(donationAmounts)
        top = topDonors(donorAmountRows, top_n=5) 
        newDonors = donorAcqRate(donorFirstDates)
        totals = numDonationsOverTime(donationDates)
        completionRate = totalCompletion(eventRows, donationEventRows)
        yGrowth = donationGrowth(donationDates, "y")
        pdf.insertSubheading(f"{section}. Summary and Next Steps")
        pdf.insertParagraph(
            f"Over the last year, {totals[2]} donations were recorded."
            f"This represents year over year growth of {yGrowth}%."
            f"Futher, average donation amount was {avg}."
            f"As for donors, your top donor is {top.values.tolist()[0][1]}."
            f"They donated {top.values.tolist()[0][0]}"
            f"You also gained {newDonors[2]} new donors over the year!"
            f"Finally, {completionRate}% of events were complete."
        )

    # ---------------------------------------------------------------
    # BUILD REPORT
    # ---------------------------------------------------------------
    pdf.buildPDF() #Garrett Is super cool for making this easy
    print(f"{name}.pdf")

except Exception as e:
    traceback.print_exc()
    raise e
