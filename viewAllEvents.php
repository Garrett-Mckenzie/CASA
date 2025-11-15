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
    include 'database/dbEvents.php';
    
?>
<!DOCTYPE html>
<html>
    <head>
        <?php require_once('universal.inc') ?>
        <link rel="stylesheet" href="css/messages.css"></link>
        <script src="js/messages.js"></script>
        <title>Rappahannock CASA | Events</title>
    </head>
    <body>
        <?php require_once('header.php') ?>
        <?php require_once('database/dbEvents.php');?>
        <?php require_once('database/dbPersons.php');?>
        <?php 
            require_once('database/dbDonations.php');
            require('include/output.php');
            require('include/time.php');
        ?>
        
        <h1>Events</h1>
        <main class="general">
            <?php 
              
                //require_once('database/dbevents.php');
                //require_once('domain/Event.php');
                //$events = get_all_events();
                $events = fetch_all_events();
                $today = strtotime(date("Y-m-d"));
                
                // Filter out expired events
                $upcomingEvents = array_filter($events , fn($event)=> strtotime($event["endDate"])>=$today);
                $user = retrieve_person($userID);

                if (sizeof($upcomingEvents) > 0): ?>
                <div class="table-wrapper">
                    <h2>Upcoming Events</h2>
                    <table class="general">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Date</th>     
                                <th>Time</th>
                                <th style="width:1px">Fundraiser Goal</th>
                                <th style="width:1px"></th>
                            </tr>
                        </thead>
                        <tbody class="standout">
                            <?php 
                                #require_once('database/dbPersons.php');
                                #require_once('include/output.php');
                                #$id_to_name_hash = [];
                                foreach ($upcomingEvents as $event) {
                                    $eventID = $event['id'];
                                    $title = $event['name'];
                                    $goalAmount = $event['goalAmount'];
                                    $startDate = $event['startDate'];
                                    $endDate = $event['endDate'];
                                    $startTime = time24hto12h($event['startTime']);
                                    $endTime = time24hto12h($event['endTime']);
                                    $description = $event['description'];

                                    $donations = fetch_donations_for_event($eventID);
                                    $totalRaised = 0;
                                    if ($donations){
                                        foreach ($donations as $donation){
                                            $totalRaised += $donation["amount"];
                                        }
                                        $completion = round($totalRaised/$goalAmount,2)*100;
                                        }
                                        else{
                                            $completion = 0;
                                        }

                                    $completed = ($completion>=100)? 1:0;

                                    if ($startDate == $endDate){
                                        $dates = $startDate;
                                    }
                                    else{
                                        $dates = $startDate . " to " . $endDate;
                                    }
                            
                                    echo "
                                    <tr data-event-id='$eventID'>

                                        <td><a href='specificEvent.php?id=$eventID'>$title</a></td>
                                        <td>$dates</td>
                                        <td>$startTime - $endTime</td>
                                        <td>$$goalAmount</td>"; //money
                                    
                                   
                                       
                                    echo "</tr>";

                                    }
                            ?>
                        </tbody>
                    </table>
                </div>

                <?php else: ?>
                <p class="no-events standout">There are currently no events available to view.<a class="button add" href="addEvent.php">Create a New Event</a> </p>
            <?php endif ?>
            <a class="button cancel" href="index.php">Return to Dashboard</a>
        </main>
    

    </body>
</html>