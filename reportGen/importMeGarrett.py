
from datetime import datetime, timedelta
import numpy as np
import pandas as pd


# Total donations over past time (by month, quarter, year)
#general overall stats (all donations)
def numDonationsOverTime(queryRows):
    # select date, from donations
    chungus = []
    for date in queryRows:
        dateObj = datetime.strptime(date[0], "%m-%d-%Y")
        chungus.append(dateObj)
    
    onFleek = datetime.today()
    perchance = onFleek - timedelta(days=30)
    stephen = onFleek - timedelta(days=90)
    rad = onFleek - timedelta(days=365)

    mTot = 0
    qTot = 0
    yTot = 0
    for row in chungus:
        if(rad<= row <= onFleek):
            mTot+=1
            if(stephen <=row<=onFleek):
                qTot += 1
                if(perchance <= row <= onFleek):
                    yTot += 1
                        
    return [mTot,qTot,yTot]
    

# Total number of donors
def totalDonors(queryRows):
    # select * from donors
    return len(queryRows)

# Average donation amount
def avgDonation(queryRows):
    # select amount from donations
    numDonations = len(queryRows)
    total = 0
    for row in queryRows:
        total += row[0]

    return total/numDonations

# Median donation amount
def medDonation(queryRows):
    # select amount from donations

    queryRows = [row[0] for row in queryRows]


    arrr = np.array(queryRows)
    return np.median(arrr)

# Donation growth rate (year-over-year, quarter-over-quarter)
def donationGrowth(queryRows, rType):
    """growth rate % of year to last year

    Args:
        queryRows (array of tuples): query results containing just date information of all donations
        rType (str): q for quartly, y for yearly
    Returns:
        float: rounded PERCENTAGE growth rate (zero if not enough/no past data)
    """
    #select date from donations:

    donationsDates = []
    for date in queryRows:
        dateObj = datetime.strptime(date[0], "%m-%d-%Y")
        donationsDates.append(dateObj)

    today = datetime.today()
    curDonations = 0
    pastDonations = 0

    if rType == "y":
        yearAgo = today - timedelta(days=365)
        twoYearAgo = yearAgo - timedelta(days=365) 
        
        for date in donationsDates:
            if yearAgo <= date <= today:
                curDonations +=1
            if twoYearAgo <= date <= yearAgo:
                pastDonations +=1
    else:
        quarterAgo = today - timedelta(days=90)
        twoQuarterAgo = quarterAgo - timedelta(days=90)
        
        for date in donationsDates:
            if quarterAgo <= date <= today:
                curDonations +=1
            if twoQuarterAgo <= date <= quarterAgo:
                pastDonations +=1

    if pastDonations == 0:
        return 0.0
    growth = ((curDonations/pastDonations)-1)*100
    return round(growth,2)



# Fundraiser goal achievement rate (% of goal reached per event)
def goalAchievementRate(eventRows,donationRows):
    """percent towards event goal for each event given

    Args:
        eventRows (array of tuples): #select id,name,goalAmount from dbevents
        donationRows (array of tuples): #select amount, eventID from donations

    Returns:
        pandas df: columns = ["id","name","completion"], completion is a rounded percentage value
    """
    #select id,name,goalAmount from dbevents
    #select amount, eventID from donations
    events = pd.DataFrame(eventRows,columns=["id","name","goalAmount"])
    events["aggDonations"] = 0
    for tuple in donationRows:
        if tuple[1] in events["id"]:
            events.at[tuple[1],"aggDonations"] += tuple[0]
    
    events["completion"] = ((events["aggDonations"]/events["goalAmount"])*100).round(decimals=2)

    return events[["id","name","completion"]]



# Completion rate of fundraisers (completed vs ongoing)
def totalCompletion(eventRows,donationRows):
    completionMatrix = goalAchievementRate(eventRows,donationRows)
    completionMatrix["completed"] = np.where(completionMatrix['completion']>=100,1,0)
    pp = completionMatrix[(completionMatrix["completed"]==1)]

    return round(len(pp)/len(completionMatrix)*100,2)

# Donor retention rate (repeat donors ÷ total donors) !!maybe skip

# New donor acquisition rate
def donorAcqRate(queryRows):
    #query of every unique donors first donation date
    donationsDates = []
    for date in queryRows:
        dateObj = datetime.strptime(date[0], "%m-%d-%Y")
        donationsDates.append(dateObj)

    today = datetime.today()
    monthAgo = today - timedelta(days=30)
    quarterAgo = today - timedelta(days=90)
    yearAgo = today - timedelta(days=365)

    q = 0;
    y = 0;
    m = 0;
    for date in donationsDates:
        if yearAgo <= date <= today:
            y+=1
            if quarterAgo <= date <= today:
                q+=1
                if monthAgo <= date <= today:
                    m+=1

    #return num of new donors in last month,quarter and year
    return[m,q,y]
##visuals
# Line chart of donations over time
def chartNumDonations(queryRows,rType,k):
    donationsDates = []
    for date in queryRows:
        dateObj = datetime.strptime(date[0], "%m-%d-%Y")
        donationsDates.append(dateObj)

    today = datetime.today()
    if(rType == "y"):
        timeAgo = today - timedelta(days=365*k)
    if(rType == "q"):
        timeAgo = today-timedelta(days=90*k)
    else:
        timeAgo = today-timedelta(days=30*k)

    return
# Bar chart of fundraiser goal vs. actual raised
# KPI dashboard tiles (Total Raised, Avg Donation, Active Donors, etc.)


# Top donors by total amount
# Donor frequency categories (e.g., one-time, occasional, recurring)
# Average donation per donor
# Lifetime donation value (sum of all donations per donor)
# Donation recency (days since last donation)
# Geographic distribution (donations by state, city, or ZIP)
# Demographic breakdowns (age group, gender, income range — if stored)
# Retention and churn analysis (who stopped donating?)
##visuals
# Pareto (80/20) chart — top 20% donors contributing 80% of total
# Heatmap of donations by region
# Funnel showing donor journey (first-time → repeat → major donor)

## Donation Metrics
# Donation amount distribution (histogram)
# Average fees per donation
# Net amount received after fees
# Percentage of donations thanked (and correlation with repeat giving)
# Mode of giving (if you have payment method: online, mail, event, etc.)
# Recurring vs. one-time donations

##Financial Efficiency
# Total fees vs total donations
# Thank-you impact analysis — e.g., does thanking increase donor retention or next donation size?

# Event Metrics
# Total raised per event
# % of goal achieved
# Number of donors per event
# Average donation per event
# Fundraiser duration vs. amount raised
# Geographic performance (location-based fundraiser success)
# Completion and follow-up rate (thanked donors per event)
# Advanced/Strategic
# Event ROI = (Total donations – event cost) / event cost (if cost data available)
# Seasonality — which times of year perform best?
# Correlation analysis between fundraiser attributes (goal size, duration, etc.) and success rate

# Cohort Analysis
# Group donors by first donation year and see how many remain active.
# Track how donation frequency changes over time.
# Behavior Correlation
# Do thanked donors give more?
# Do donors from specific fundraisers become repeat givers?
# Is there a threshold donation amount after which retention improves?
# Visuals
# Cohort retention heatmap
# Correlation matrix (thank-you status vs. repeat donations)


# If they’re data-driven and want more than descriptive stats:
# Donor lifetime value prediction (LTV modeling)
# Donation forecasting using time-series models
# Donor churn prediction (machine learning classifier)
# Event success prediction (based on early donations, past trends)
# Fee optimization analysis (predict how lowering fees affects net total)


# For day-to-day management:
# “Unthanked donations” report
# “Upcoming or active fundraisers” overview
# “Donors with high potential for next campaign”
# “Regions underperforming relative to goal”
