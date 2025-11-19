<?php
    // Template for new VMS pages. Base your new page on this one

    // Make session information accessible, allowing us to associate
    // data with the logged-in user.
    session_cache_expire(30);
    session_start();
    date_default_timezone_set("America/New_York");

    $loggedIn = false;
    $accessLevel = 0;
    $userID = null;
    if (isset($_SESSION['_id'])) {
        $loggedIn = true;
        // 0 = not logged in, 1 = standard user, 2 = manager (Admin), 3 super admin (TBI)
        $accessLevel = $_SESSION['access_level'];
        $userID = $_SESSION['_id'];
    }
    if ($accessLevel < 1) {
        header('Location: login.php');
        die();
    }
    if (!isset($_GET['date'])) {
        header('Location: calendar.php');
        die();
    }
    require_once('include/input-validation.php');
    $get = sanitize($_GET);
    $date = $get['date']; //do not strtotime() this, it is needed in string format later
    $datePattern = '/[0-9]{4}-[0-9]{2}-[0-9]{2}/';
    $timeStamp = strtotime($date);
    
    //requires date in string format
    if (!preg_match($datePattern, $date) || !$timeStamp) {
        header('Location: calendar.php');
        die();
    }

?>
<!DOCTYPE html>
<html>
    <head>
        <?php require_once('universal.inc') ?>
        <title>Rappahannock CASA | View Date</title>
    </head>
    <body>
        <?php require_once('header.php') ?>
        <h1>View Day</h1>
        <main class="date">
            <h2>Events for <?php echo date('l, F j, Y', $timeStamp) ?></h2>
            <!-- Loop -->
            <?php
                require('database/dbEvents.php');
                require('include/output.php');
                require('include/time.php');
                require('database/dbDonations.php');
                $events = fetch_all_events();
                $events = array_filter($events , fn($event)=> strtotime($event["startDate"])<=$timeStamp && strtotime($event["endDate"])>=$timeStamp);

                if ($events) {
                    foreach ($events as $event) {
                        require_once('include/output.php');
                        $event_name = $event['name'];
                        $event_startTime = time24hto12h($event['startTime']);
                        $event_description = $event['description'];
                        $goal = $event["goalAmount"];

                        $donations = fetch_donations_for_event($event["id"]);
                        $totalRaised = 0;
                        if ($donations){
                            foreach ($donations as $donation){
                                $totalRaised += $donation["amount"];
                            }
                            $completion = round($totalRaised/$goal,2)*100;
                        }
                        else{
                            $completion = 0;
                        }
                        $completed = ($completion>=100)? "complete":"incomplete";
                        require_once('include/time.php');
        
                        echo "
                            <table class='event'>
                                <thead>
                                    <tr>
                                        <th colspan='2' data-event-id='" . $event['id'] . "'>" . $event['name'] . " (". $completed.")"."</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr><td>Time</td><td>" . time24hto12h($event['startTime']) ." - ".time24hto12h($event['endTime']). "</td></tr>
                                    <tr><td>Location</td><td>" . /*$location .*/ "</td></tr>
                                    <tr><td>Description</td><td>" . $event['description'] . "</td></tr>
                                    <tr><td>Fundraiser Goal</td><td> $" . $goal . "</td></tr>
                                    <tr><td>Total Amount Raised</td><td> $" . $totalRaised . "</td></tr>
                                </tbody>
                              </table>
                        ";
                        echo "
                            <div style='text-align:center; margin-top: 10px; margin-bottom: 30px;'>
                                <form action='deleteEvent.php' method='GET' 
                                    onsubmit=\"return confirm('Are you sure you want to delete the event: ".htmlspecialchars($event_name, ENT_QUOTES)." ?');\" 
                                    style='display:inline-block;'>
                                    
                                    <input type='hidden' name='id' value='".$event['id']."'>

                                    <button type='submit'
                                        style=\"
                                            background-color: #d9534f; 
                                            color: white; 
                                            border: none; 
                                            padding: 8px 14px; 
                                            font-size: 14px; 
                                            border-radius: 4px; 
                                            cursor: pointer;
                                            transition: background-color 0.3s;
                                        \"
                                        onmouseover=\"this.style.backgroundColor='#c9302c'\"
                                        onmouseout=\"this.style.backgroundColor='#d9534f'\"
                                    >
                                        Delete Event
                                    </button>
                                </form>
                            </div>
                        ";
                    }
                } else {
                    echo '<p class="none-scheduled">There are no events scheduled on this day</p>';
                }
            ?>
            <?php
            if ($accessLevel >= 2) {
                echo '
                    <a class="button" href="addEvent.php?date=' . $date . '">
                        Create New Event
                    </a>';
                /*echo '
                    <a class="button" href="editHours.php?date=' . $date . '">
                        Edit Hours for an event
                    </a>';*/
            }
            ?>
			<a href="calendar.php?month=<?php echo substr($date, 0, 7) ?>" class="button cancel" style="margin-top: -.5rem">Return to Calendar</a>
        </main>
    </body>
</html>