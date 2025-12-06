import importMeEthan as Ethan #needed for matplotlib fix
from datetime import datetime, timedelta
import matplotlib.pyplot as plt
import numpy as np
import pandas as pd
import seaborn as sns


def queryDatesToDates(queryRows, col):
    """helper function, grab dates from rows, return them as date time objects

    Args:
        queryRows (array of tuples): should have a date column

    Returns:
        array: array of datetime objects
    """
    rtnDates = []
    for row in queryRows:
        date = row[col]
        if isinstance(date, (str)):
            date = date.strip()
            if date == "": #check if empty (null dates wont be in query)
                continue
            datetimeObj = datetime.strptime(date, "%m/%d/%Y")
            rtnDates.append(datetimeObj)
            
        else:
            datetimeObj = datetime.combine(date, datetime.min.time())
            rtnDates.append(datetimeObj)
    return rtnDates

# Total donations over past time (by month, quarter, year)
#general overall stats (all donations)
def numDonationsOverTime(queryRows):
    """Total donations over past time (by month, quarter, year); general overall stats (all donations)

    Args:
        queryRows (array of tuples): select date, from donations

    Returns:
        list: [monthTotal,quarterTotal,yearlyTotal]
    """
    chungus = queryDatesToDates(queryRows,0)
    
    # cur date
    onFleek = datetime.today()
    # different time ranges
    perchance = onFleek - timedelta(days=30)
    stephen = onFleek - timedelta(days=90)
    rad = onFleek - timedelta(days=365)

    # totals over past month, quarter, an year
    mTot = 0
    qTot = 0
    yTot = 0
    for row in chungus:
        if(rad<= row <= onFleek):
            yTot+=1
            if(stephen <=row<=onFleek):
                qTot += 1
                if(perchance <= row <= onFleek):
                    mTot += 1
    
    return [mTot,qTot,yTot]
    

# Total number of donors
def totalDonors(queryRows):
    """Total number of donors

    Args:
        queryRows (array of tuples): select * from donors

    Returns:
        int: number of donors
    """
    # select * from donors
    return len(queryRows)

# Average donation amount
def avgDonation(queryRows):
    """Average donation amount

    Args:
        queryRows (array of tuples): select amount from donations

    Returns:
        float: average donation amount rounded to 2 digits past decimal
    """
    # select amount from donations
    numDonations = len(queryRows)
    total = 0
    for row in queryRows:
        total += row[0]

    return round(total/numDonations,2)

# Median donation amount
def medDonation(queryRows):
    """Median donation amount

    Args:
        queryRows (array of tuples): select amount from donations

    Returns:
        float: median donation
    """
    # select amount from donations

    queryRows = [row[0] for row in queryRows]

    arrr = np.array(queryRows)
    return np.median(arrr)

def totalRaised(queryRows,rType,k):
    """returns total moneies raised over a period of time or all time

    Args:
        queryRows (array of tuples): select amount, date from donations
        rType (str): a=all,y=year,q=quarter,m=month. Time type to go back by
        k (float): number of rType to go back (if rType==a, k is irrelevant)

    Returns:
        float: total amount raised rounded to 2 digits past decimal
    """
    if(rType.lower()=="a"):
        df = pd.DataFrame(queryRows,columns=["amount","date"])
        total = df['amount'].sum()
        return round(total,2)
    
    today = datetime.today()
    if(rType.lower() == "y"):
        timeAgo = today - timedelta(days=(round(365.0*k,0)))
    elif(rType.lower()== "q"):
        timeAgo = today-timedelta(days=(round(90.0*k,0)))
    else:
        timeAgo = today-timedelta(days=(round(30.0*k,0)))

    #get just in time range
    df = pd.DataFrame(queryRows,columns=["amount","date"]) 
    df = df[(timeAgo <= df["date"])&(df["date"] <= today)]

    total = df['amount'].sum()
    return round(total,2)

# Donation growth rate (year-over-year, quarter-over-quarter)
def donationGrowth(queryRows, rType):
    """growth rate % (year-over-lastYear, quarter-over-lastQuarter)

    Args:
        queryRows (array of tuples): query results containing just date information of all donations
        rType (str): q for quartly, y for yearly
    Returns:
        float: rounded PERCENTAGE growth rate (zero if not enough/no past data)
    """
    #select date from donations:

    donationsDates = queryDatesToDates(queryRows,0)
    
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

    if pastDonations ==0 or curDonations == 0:
        return 0.0 #no growth and catches divide by 0 error
    growth = (((curDonations-pastDonations)/pastDonations))*100
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
    events["aggDonations"] = 0.00

    for tuple in donationRows:
        if tuple[1] in events["id"].values:
            events.loc[events["id"] == tuple[1],"aggDonations"] += float(tuple[0])
    
    goal = events["goalAmount"].astype(float)
    agg = events["aggDonations"].astype(float)

    events["completion"] = np.where(
        goal <= 0, 
        np.where(agg > 0, (100 + agg).round(2), 0.00), 
        ((agg / goal) * 100).round(2)
    )
    return events[["id","name","completion"]]



# Completion rate of fundraisers (completed vs ongoing)
def totalCompletion(eventRows,donationRows):
    """Completion rate of fundraisers (completed vs ongoing)

    Args:
        eventRows (array of tuples): select id,name,goalAmount from dbevents
        donationRows (array of tuples): select amount, eventID from donations

    Returns:
        float: rounded PERCENTAGE of completed events (completed/allEvents)
    """
    completionMatrix = goalAchievementRate(eventRows,donationRows)
    completionMatrix["completed"] = np.where(completionMatrix['completion']>=100,1,0)
    pp = completionMatrix[(completionMatrix["completed"]==1)]

    return round(len(pp)/len(completionMatrix)*100,2)

# Donor retention rate (repeat donors ÷ total donors) !!maybe skip

# New donor acquisition rate
def donorAcqRate(queryRows):
    """New donor acquisition rate

    Args:
        queryRows (array of tuples ((donorID,date))): select donorID, MIN(STR_TO_DATE(date,'%m/%d/%Y')) as date from donations where date is not null group by donorID order by date

    Returns:
        list: [newWithinMonth,newWithinQuarter,newWithinYear]
    """
    #query of every unique donors first donation date
    donationsDates = queryDatesToDates(queryRows,1)

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
    return [m,q,y]
##visuals

def chartNumDonations(queryRows,rType='y',gType='hist',k=1, b=12):
    """create chart of donations over time

    Args:
        queryRows (array of tuples): elect date from donations where date is not null
        rType (str, optional): time type (y=year,q=quarter,m=month). Defaults to 'y'.
        gType (str, optional): graph type [hist or line]. Defaults to 'hist'.
        k (int, optional): number of rType. Defaults to 1.
        b (int, optional): number of bins. Defaults to 12.

    Returns:
        _type_: _description_
    """
    donationsDates = queryDatesToDates(queryRows,0)

    today = datetime.today()
    if(rType.lower() == "y"):
        timeAgo = today - timedelta(days=(round(365.0*k,0)))
    elif(rType.lower()== "q"):
        timeAgo = today-timedelta(days=(round(90.0*k,0)))
    else:
        timeAgo = today-timedelta(days=(round(30.0*k,0)))

    #get just in time range
    df = pd.DataFrame(donationsDates,columns=["date"]) 
    df = df[(timeAgo <= df["date"])&(df["date"] <= today)]

    if(gType.lower()=="line"):        
        df['date'] = pd.to_datetime(df['date'])
        # Count number of donations per day
        daily_counts = df.groupby('date').size().reset_index(name='count')
        #Line plot of counts over time
        fig, ax = plt.subplots()
        sns.lineplot(data=daily_counts, x='date', y='count')
        ax.set_title("Donations Over Time")
        ax.set_xlabel("Date")
        ax.set_ylabel("Donation Count")
        plt.tight_layout()
        return fig

    else:
        fig, ax = plt.subplots()
        sns.histplot(data=df, x="date", bins=b)
        ax.set_title("Donations Over Time")
        ax.set_xlabel("Date")
        ax.set_ylabel("Donation Count")
        plt.tight_layout()
        return fig


    
# Bar chart of fundraiser goal vs. actual raised
def chartFundraiserGoals(eventRows,donationRows):
    """Bar chart of fundraiser goal vs. actual raised

    Args:
        eventRows (array of tuples): select id,name,goalAmount from dbevents
        donationRows (array of tuples): select amount, eventID from donations

    Returns:
        matplotlib graph: graph figure which can be shown through plt.show()
    """
    #select id,name,goalAmount from dbevents
    #select amount, eventID from donations
    events = pd.DataFrame(eventRows,columns=["id","name","Goal Amount"])
    events["Total Donations"] = 0.00

    for tuple in donationRows:
        if tuple[1] in events["id"].values:
            events.loc[events["id"] == tuple[1],"Total Donations"] += float(tuple[0])
    
    #events["completion"] = ((events["Total Donations"]/events["Goal Amount"].astype(float))*100).round(2)
    events= events[["name","Goal Amount","Total Donations"]] 
    events["Goal Amount"] = events["Goal Amount"].astype(float)

    melted = events.melt(id_vars='name', value_vars=['Goal Amount', 'Total Donations'],var_name='metric', value_name='amount')
    fig, ax = plt.subplots()
    sns.histplot(data=melted, x='name', hue='metric', weights='amount', multiple='dodge', shrink=0.8, ax=ax)
    ax.set_title("Goal vs Donations per Event")
    ax.set_xlabel("")
    ax.set_ylabel("Dollar Amount")
    ax.tick_params(axis="x", rotation=45)
    plt.tight_layout()
    return fig
    
# Top donors by total amount
def topDonors(queryRows, top_n=10):
    """Top donors by total donated amount

    Args:
        queryRows (array of tuples): SELECT d.id AS donorID, d.first, d.last, dn.amount FROM donations AS dn JOIN donors AS d ON dn.donorID = d.id
        top_n (int, optional): number of top donors to return. Defaults to 10.

    Returns:
        pandas.DataFrame: columns ["donorID","fullName","totalAmount"], sorted descending
    """
    cols = ["donorID", "first", "last", "amount"]
    df = pd.DataFrame(queryRows, columns=cols)

    df["fullName"] = df["first"].str.strip() + " " + df["last"].str.strip()
    df_grouped = df.groupby(["donorID", "fullName"])["amount"].sum().reset_index()
    df_grouped = df_grouped.sort_values(by="amount", ascending=False)
    return df_grouped.head(top_n).rename(columns={"amount": "totalAmount"})


# Donor frequency categories (one-time, occasional, recurring)
def donorFrequency(queryRows):
    """Categorize donors by donation frequency

    Args:
        queryRows (array of tuples): select donorID, firstName, lastName from donations JOIN donors

    Returns:
        dict: {"one-time": count, "occasional": count, "recurring": count}
    """
    cols = ["donorID", "firstName", "lastName"]
    df = pd.DataFrame(queryRows, columns=cols)

    freq = df["donorID"].value_counts()
    one_time = (freq == 1).sum()
    occasional = ((freq >= 2) & (freq <= 5)).sum()
    recurring = (freq > 5).sum()

    return {
        "one-time": int(one_time),
        "occasional": int(occasional),
        "recurring": int(recurring)
    }


# Average donation per donor
def avgDonationPerDonor(queryRows):
    """Average donation per donor

    Args:
        queryRows (array of tuples): select donorID, amount from donations

    Returns:
        float: average total donation per donor
    """
    df = pd.DataFrame(queryRows, columns=["donorID", "amount"])
    total_per_donor = df.groupby("donorID")["amount"].sum()
    return round(total_per_donor.mean(), 2)


# Lifetime donation value per donor
def lifetimeDonationValue(queryRows):
    """Lifetime donation value for each donor

    Args:
        queryRows (array of tuples): select donorID, firstName, lastName, amount from donations JOIN donors

    Returns:
        pandas.DataFrame: columns ["donorID","fullName","lifetimeValue"]
    """
    cols = ["donorID", "firstName", "lastName", "amount"]
    df = pd.DataFrame(queryRows, columns=cols)
    df["fullName"] = df["firstName"].str.strip() + " " + df["lastName"].str.strip()

    lifetime = df.groupby(["donorID", "fullName"])["amount"].sum().reset_index()
    lifetime = lifetime.rename(columns={"amount": "lifetimeValue"})
    return lifetime


# Donation recency (days since last donation)
def donationRecency(queryRows):
    """Days since last donation for each donor

    Args:
        queryRows (array of tuples): select donorID, firstName, lastName, date from donations JOIN donors

    Returns:
        pandas.DataFrame: columns ["donorID","fullName","daysSinceLast"]
    """
    cols = ["donorID", "firstName", "lastName", "date"]
    df = pd.DataFrame(queryRows, columns=cols)
    df["fullName"] = df["firstName"].str.strip() + " " + df["lastName"].str.strip()

    # Normalize date
    df["date"] = df["date"].apply(
        lambda d: datetime.strptime(d, "%m/%d/%Y") if isinstance(d, str)
        else datetime.combine(d, datetime.min.time())
    )

    latest = df.groupby(["donorID", "fullName"])["date"].max().reset_index()
    today = datetime.today()
    latest["daysSinceLast"] = (today - latest["date"]).dt.days

    return latest[["donorID", "fullName", "daysSinceLast"]]


# Geographic distribution (donations by state, city, or ZIP)
def geoDistribution(donationRows, donorRows, level="state"):
    """Geographic distribution of total donated amounts by donor location.

    Args:
        donationRows (array of tuples): select donorID, amount from donations
        donorRows (array of tuples): select id, city, state, zip from donors
        level (str, optional): one of 'state', 'city', or 'zip'. Defaults to 'state'.

    Returns:
        pandas.DataFrame: columns [level, "totalAmount"], sorted descending
    """
    valid_levels = {"state", "city", "zip"}
    if level not in valid_levels:
        raise ValueError(f"Invalid level '{level}'. Must be one of {valid_levels}.")

    # Build DataFrames
    donations = pd.DataFrame(donationRows, columns=["donorID", "amount"])
    donors = pd.DataFrame(donorRows, columns=["id", "city", "state", "zip"])

    # Merge donations with donor locations
    merged = donations.merge(donors, left_on="donorID", right_on="id", how="inner")

    # Group by geographic level and sum total donations
    grouped = merged.groupby(level)["amount"].sum().reset_index()

    # Sort and rename for readability
    grouped = grouped.sort_values(by="amount", ascending=False)
    grouped = grouped.rename(columns={"amount": "totalAmount"})

    return grouped



# Number of donors per event
def donorsPerEvent(queryRows):
    """Number of unique donors per event

    Args:
        queryRows (array of tuples): select eventID, donorID from donations

    Returns:
        pandas.DataFrame: columns ["eventID","donorCount"]
    """
    df = pd.DataFrame(queryRows, columns=["eventID", "donorID"])
    counts = df.groupby("eventID")["donorID"].nunique().reset_index()
    counts = counts.rename(columns={"donorID": "donorCount"})
    return counts


# Average donation per event
def avgDonationPerEvent(queryRows):
    """Average donation per event

    Args:
        queryRows (array of tuples): select eventID, amount from donations

    Returns:
        pandas.DataFrame: columns ["eventID","avgDonation"]
    """
    df = pd.DataFrame(queryRows, columns=["eventID", "amount"])
    avg_df = df.groupby("eventID")["amount"].mean().round(2).reset_index()
    avg_df = avg_df.rename(columns={"amount": "avgDonation"})
    return avg_df


# Total raised per event
def totalRaisedPerEvent(queryRows):
    """Total raised per event

    Args:
        queryRows (array of tuples): select eventID, amount from donations

    Returns:
        pandas.DataFrame: columns ["eventID","totalRaised"]
    """
    df = pd.DataFrame(queryRows, columns=["eventID", "amount"])
    totals = df.groupby("eventID")["amount"].sum().round(2).reset_index()
    totals = totals.rename(columns={"amount": "totalRaised"})
    return totals


# Fundraiser duration vs. amount raised
def fundraiserDurationPerformance(eventRows, donationRows):
    """Correlate fundraiser duration with total amount raised

    Args:
        eventRows (array of tuples): select id, name, startDate, endDate from dbevents
        donationRows (array of tuples): select eventID, amount from donations

    Returns:
        pandas.DataFrame: columns ["id","name","durationDays","totalRaised"]
    """
    events = pd.DataFrame(eventRows, columns=["id", "name", "startDate", "endDate"])
    donations = pd.DataFrame(donationRows, columns=["eventID", "amount"])

    # Ensure dates are datetime
    for col in ["startDate", "endDate"]:
        events[col] = events[col].apply(
            lambda d: datetime.strptime(d, "%m/%d/%Y") if isinstance(d, str)
            else datetime.combine(d, datetime.min.time())
        )

    # Calculate duration in days
    events["durationDays"] = (events["endDate"] - events["startDate"]).dt.days

    # Aggregate donations per event
    totalRaised = donations.groupby("eventID")["amount"].sum().reset_index()
    merged = events.merge(totalRaised, left_on="id", right_on="eventID", how="left").fillna(0)
    merged = merged[["id", "name", "durationDays", "amount"]].rename(columns={"amount": "totalRaised"})

    return merged


# Seasonality — donations by month
def seasonalityAnalysis(queryRows):
    """Donation seasonality analysis (total donations by month)

    Args:
        queryRows (array of tuples): select amount, date from donations

    Returns:
        pandas.DataFrame: columns ["month","totalAmount"]
    """
    df = pd.DataFrame(queryRows, columns=["amount", "date"])
    df["date"] = df["date"].apply(
        lambda d: datetime.strptime(d, "%m/%d/%Y") if isinstance(d, str)
        else datetime.combine(d, datetime.min.time())
    )

    df["month"] = df["date"].dt.month
    monthly = df.groupby("month")["amount"].sum().reset_index()
    monthly = monthly.rename(columns={"amount": "totalAmount"})
    monthly["month"] = monthly["month"].apply(lambda m: datetime(1900, m, 1).strftime('%B'))
    return monthly



##visuals
def plotGeoDistributionBar(queryRows, level="state", top_n=10):
    """
    Bar chart of donation totals by geographic level.
    Args:
        queryRows (list of tuples): e.g. SELECT state, SUM(amount) FROM donations GROUP BY state
        level (str): column name label ("state", "city", etc.)
        top_n (int): number of top results to show
    """
    if not queryRows:
        raise ValueError("No query results provided.")

    # If queryRows includes donorID, ignore it for this chart
    if len(queryRows[0]) == 3:  # donorID, state, sum(amount)
        df = pd.DataFrame(queryRows, columns=["donorID", level, "totalAmount"])
    else:
        df = pd.DataFrame(queryRows, columns=[level, "totalAmount"])

    # Group by the level in case multiple rows per state exist
    df_grouped = df.groupby(level)["totalAmount"].sum().sort_values(ascending=False).head(top_n)

    fig, ax = plt.subplots(figsize=(8, 5))
    ax.bar(df_grouped.index, df_grouped.values, color="#4B9CD3")
    ax.set_title(f"Top {top_n} Donation Totals by {level.title()}", fontsize=14)
    ax.set_xlabel(level.title())
    ax.set_ylabel("Total Donations ($)")
    ax.tick_params(axis='x', rotation=45)
    ax.grid(axis='y', linestyle='--', alpha=0.7)

    fig.tight_layout()
    return fig


def plotGeoDistributionPie(queryRows, level="state", top_n=5):
    """
    Pie chart showing percentage of total donations by geographic level.
    Args:
        queryRows (list of tuples): e.g. SELECT state, SUM(amount) FROM donations GROUP BY state
        level (str): column name label ("state", "city", etc.)
        top_n (int): number of top results to show
    """
    if not queryRows:
        raise ValueError("No query results provided.")

    # If queryRows includes donorID, ignore it for this chart
    if len(queryRows[0]) == 3:  # donorID, state, sum(amount)
        df = pd.DataFrame(queryRows, columns=["donorID", level, "totalAmount"])
    else:
        df = pd.DataFrame(queryRows, columns=[level, "totalAmount"])

    df_grouped = df.groupby(level)["totalAmount"].sum().sort_values(ascending=False).head(top_n)

    fig, ax = plt.subplots(figsize=(6, 6))
    ax.pie(
        df_grouped.values,
        labels=df_grouped.index,
        autopct="%1.1f%%",
        startangle=140,
        colors=plt.cm.Paired.colors
    )
    ax.set_title(f"Top {top_n} Donation Shares by {level.title()}", fontsize=14)
    return fig



def chartParetoTopDonors(queryRows, top_n=10):
    """
    Pareto (80/20) chart — top donors contributing to total, with cumulative line across all donors.

    Args:
        queryRows (array of tuples): SELECT d.id AS donorID, d.first, d.last, dn.amount FROM donations AS dn JOIN donors AS d ON dn.donorID = d.id
        top_n (int, optional): number of top donors to show on chart. Defaults to 10.

    Returns:
        matplotlib.figure.Figure: Pareto chart showing top donors and cumulative contribution
    """
    import pandas as pd
    import matplotlib.pyplot as plt
    import seaborn as sns

    # Build full donor DataFrame
    df = pd.DataFrame(queryRows, columns=["donorID", "first", "last", "amount"])
    df["amount"] = pd.to_numeric(df["amount"], errors="coerce")
    df = df.dropna(subset=["amount"])
    df["fullName"] = df["first"].str.strip() + " " + df["last"].str.strip()

    # Aggregate all donors for total and cumulative %
    donor_totals = (
        df.groupby(["donorID", "fullName"])["amount"]
        .sum()
        .reset_index()
        .sort_values(by="amount", ascending=False)
    )
    donor_totals["cumulativePct"] = donor_totals["amount"].cumsum() / donor_totals["amount"].sum() * 100

    # Get top_n subset for the bars (to avoid overcrowding)
    df_top = donor_totals.head(top_n)

    # Plot setup
    fig, ax1 = plt.subplots(figsize=(8, 5))
    sns.barplot(x="fullName", y="amount", data=df_top, ax=ax1, color="skyblue")
    ax1.set_ylabel("Total Donated ($)", color="skyblue")
    ax1.set_xlabel("")
    ax1.tick_params(axis="x", rotation=45, labelsize=7)
    ax1.tick_params(axis="y", labelcolor="skyblue")

    # Second y-axis: cumulative percentage (using all donors, but only plot top_n points)
    ax2 = ax1.twinx()
    ax2.plot(
        df_top["fullName"],
        donor_totals["cumulativePct"][:top_n],  # top_n slice of full cumulative curve
        color="orange",
        marker="o",
        linewidth=2,
    )
    ax2.set_ylabel("Cumulative % of Donations", color="orange")
    ax2.tick_params(axis="y", labelcolor="orange")
    ax2.set_ylim(0, 110)

    ax1.set_title("Pareto Chart: Top Donors and Cumulative Contribution", fontsize=11, fontweight="bold")
    fig.tight_layout()

    return fig




def chartDonorFunnel(queryRows):
    df = pd.DataFrame(queryRows, columns=["donorID", "firstName", "lastName", "amount"])
    df["amount"] = pd.to_numeric(df["amount"], errors="coerce")
    df = df.dropna(subset=["amount"])

    donor_counts = df.groupby("donorID").size()
    funnel = pd.DataFrame({
        "Stage": ["First-time Donors", "Repeat Donors", "Frequent Donors"],
        "Count": [
            (donor_counts == 1).sum(),
            ((donor_counts >= 2) & (donor_counts < 5)).sum(),
            (donor_counts >= 5).sum()
        ]
    })

    fig, ax = plt.subplots(figsize=(6, 4))
    sns.barplot(
        y="Stage", x="Count",
        data=funnel,
        hue="Stage", palette="viridis", legend=False
    )
    ax.set_title("Donor Funnel: Conversion from First-Time to Major Donors")
    ax.set_xlabel("Number of Donors")
    ax.set_ylabel("")
    plt.tight_layout()
    return fig


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
