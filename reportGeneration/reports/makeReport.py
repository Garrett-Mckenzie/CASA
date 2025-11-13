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

    IncludeSummarySection = True
    if "IncludeSummarySection" not in args.keys():
        IncludeSummarySection = False

    graphDesc = True
    if "graphDesc" not in args.keys():
        graphDesc = False

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
    # SECTION 1: Overview Stats
    # ---------------------------------------------------------------
    pdf.insertSubheading("1. Overview of CASA Donations")

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

    # ---------------------------------------------------------------
    # SECTION 2: Growth & Trends
    # ---------------------------------------------------------------
    pdf.insertSubheading("2. Growth & Trends")

    yGrowth = donationGrowth(donationDates, "y")
    qGrowth = donationGrowth(donationDates, "q")

    pdf.insertParagraphs([
        f"Year-over-year donation growth rate: {yGrowth}%.",
        f"Quarter-over-quarter donation growth rate: {qGrowth}%."
    ])

    fig_hist = chartNumDonations(donationDates, "y", "hist", 1)
    pdf.insertGraph(4, 3, 1)
    if graphDesc:
        pdf.insertParagraph(
            "Note: ..."
        )

    fig_line = chartNumDonations(donationDates, "y", "line", 1)
    pdf.insertGraph(4, 3, 2)
    if graphDesc:
        pdf.insertParagraph(
            "Note: ..."
        )

    # ---------------------------------------------------------------
    # SECTION 3: Fundraiser Performance
    # ---------------------------------------------------------------
    pdf.insertSubheading("3. Fundraiser Performance")

    completionRate = totalCompletion(eventRows, donationEventRows)
    pdf.insertParagraph(f"Overall, {completionRate}% of CASA fundraisers have achieved or exceeded their fundraising goals.")

    fig_fund = chartFundraiserGoals(eventRows, donationEventRows)
    pdf.insertGraph(4, 3, 3)
    if graphDesc:
        pdf.insertParagraph(
            "Note: ..."
        )

    completionDF = goalAchievementRate(eventRows, donationEventRows)
    pdf.insertTable(
        [["Event Name", "Completion (%)"]] +
        completionDF[["name", "completion"]].values.tolist(),
        [175, 100]
    )

    # ---------------------------------------------------------------
    # SECTION 4: Donor Insights
    # ---------------------------------------------------------------
    pdf.insertSubheading("4. Donor Insights")

    top = topDonors(donorAmountRows, top_n=10)
    pdf.insertParagraph("Top 10 donors by total contributions:")

    pdf.insertTable(
        [["Donor Name", "Total Donated ($)"]] +
        top[["fullName", "totalAmount"]].values.tolist(),
        [175, 100]
    )

    newDonors = donorAcqRate(donorFirstDates)
    pdf.insertParagraphs([
        f"New donors in the past month: {newDonors[0]}",
        f"New donors in the past quarter: {newDonors[1]}",
        f"New donors in the past year: {newDonors[2]}"
    ])

    # ---------------------------------------------------------------
    # SECTION 5: Visual Analytics
    # ---------------------------------------------------------------
    pdf.insertPageBreak()
    pdf.insertSubheading("5. Visual Analytics")

    fig_pareto = chartParetoTopDonors(donorAmountRows,10)
    pdf.insertGraph(4, 3, 4)
    if graphDesc:
        pdf.insertParagraph(
            "Note: ..."
        )

    fig_funnel = chartDonorFunnel(donorAmountRows)
    pdf.insertGraph(4, 3, 5)
    if graphDesc:
        pdf.insertParagraph(
            "Note: repeat donor is a donor who has donated more than once. Major donors are those who have donated 5 or more times "
        )

    fig_geo = plotGeoDistributionBar(donorSumByState)
    pdf.insertGraph(4, 3, 6)
    if graphDesc:
        pdf.insertParagraph(
            "Note: ..."
        )

    fig_geo = plotGeoDistributionBar(donorSumByZip,"zipcode")
    pdf.insertGraph(4, 3, 7)
    if graphDesc:
        pdf.insertParagraph(
            "Note: ..."
        )

    # ---------------------------------------------------------------
    # SECTION 6: Summary
    # ---------------------------------------------------------------
    pdf.insertPageBreak()
    pdf.insertSubheading("6. Summary and Next Steps")
    pdf.insertParagraph(
        "The data indicates that CASA’s donor base continues to grow steadily, "
        "with healthy year-over-year growth and a strong base of recurring donors. "
        "However, the fundraiser completion rate suggests opportunities for better "
        "goal calibration and follow-up engagement strategies. Efforts should focus "
        "on converting first-time donors into repeat supporters and increasing major gifts."
    )

    # ---------------------------------------------------------------
    # BUILD REPORT
    # ---------------------------------------------------------------
    pdf.buildPDF()
    print(f"{name}.pdf")

except Exception as e:
    traceback.print_exc()
    raise e
