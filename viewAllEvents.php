<?php
    // Template for new VMS pages. Base your new page on this one

    // Make session information accessible, allowing us to associate
    // data with the logged-in user.
    session_cache_expire(30);
    session_start();

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
    
    //include 'domain/Event.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <?php require_once('universal.inc') ?>
        <link rel="stylesheet" href="css/messages.css"></link>
        <script src="js/messages.js"></script>
        <title>Rappahannock CASA | Events</title>
        <script lang="js">
			function dateError(){
				const box = document.getElementById('errorBox');
				box.style.visibility = 'visible';
			}
			function close(ObjID){
				const box = document.getElementById(ObjId);
				box.style.visibility = 'hidden';
			}
		</script>
    </head>
    <body>
        <?php require_once('header.php') ?>
        <?php require_once('database/dbEvents.php');?>
        <?php require_once('database/dbPersons.php');?>
        <h1>Events</h1>
        <main class="general">
            <!-- Fetch Events from Database and Display in Table -->
            <?php 
              
                //require_once('database/dbevents.php');
                //require_once('domain/Event.php');
                //$events = get_all_events();
                $events = get_all_events_sorted_by_date_not_archived();
                $archivedEvents = get_all_events_sorted_by_date_and_archived();
                $today = new DateTime(); // Current date
                
                // Filter out expired events
                $upcomingEvents = array_filter($events, function($event) use ($today) {
                    $eventDate = new DateTime($event->getStartDate());
                    return $eventDate >= $today; // Only include events on or after today
                });

                $user = retrieve_person($userID);

                if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['filter'])) {
                    $filter = $_GET['filter'];
                    if($filter === 'notStarted') {
                        $archivedEvents = array_filter($archivedEvents, function($event) {
                            return $event->getCompleted() == 0;
                        });
                    } elseif($filter === 'inProgress') {
                        $archivedEvents = array_filter($archivedEvents, function($event) {
                            return $event->getCompleted() == 1;
                        });
                    } elseif($filter === 'completed') {
                        $archivedEvents = array_filter($archivedEvents, function($event) {
                            return $event->getCompleted() == 2;
                        });
                    }
                }

                if (sizeof($upcomingEvents) > 0): ?>
                <div class="table-wrapper">
                    <h2>Upcoming Events</h2>
                    <table class="general">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Start Date</th>
                                <th>Start Time</th>
                                <th style="width:1px">End Date</th>
                                <th style="width:1px">End Time</th>
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
                                    $eventID = $event->getID();
                                    $title = $event->getName();
                                    $goalAmount = $event->getGoalAmount();
                                    $startDate = $event->getStartDate();
                                    $endDate = $event->getEndDate();
                                    $startTime = $event->getStartTime();
                                    $endTime = $event->getEndTime();
                                    $description = $event->getDescription();
                                    
                                    $completed = $event->getCompleted();
                            
                                    echo "
                                    <tr data-event-id='$eventID'>

                                        <td><a href='event.php?id=$eventID'>$title</a></td>
                                        <td>$startDate</td>
                                        <td>$startTime</td>
                                        <td>$endDate</td>
                                        <td>$endTime</td>
                                        <td>$$goalAmount</td>"; //money
                                    
                                    // Display Sign Up or Cancel button based on user sign-up status
                                       
                                    echo "</tr>";

                                    /*echo "
                                        <td>
                                            <a class='button cancel' href='#' onclick='document.getElementById(\"cancel-confirmation-wrapper-$eventID\").classList.remove(\"hidden\")'>Cancel</a>
                                            <div id='cancel-confirmation-wrapper-$eventID' class='modal hidden'>
                                                <div class='modal-content'>
                                                    <p>Are you sure you want to cancel your sign-up for this event?</p>
                                                    <p>This action cannot be undone.</p>
                                                    <form method='post' action='cancelEvent.php'>
                                                        <input type='submit' value='Cancel Sign-Up' class='button danger'>
                                                        <input type='hidden' name='event_id' value='$eventID'>
                                                        <input type='hidden' name='user_id' value='$userID'>
                                                    </form>
                                                    <button onclick=\"document.getElementById('cancel-confirmation-wrapper-$eventID').classList.add('hidden')\" class='button cancel'>Cancel</button>
                                                </div>
                                            </div>
                                        </td>";*/
                                    //if($accessLevel < 3) {
                                    //if($numSignups < $capacity) {
                                        /*echo "
                                        <tr data-event-id='$eventID'>
                                            <td>$restricted_signup</td>
                                            <td><a href='event.php?id=$eventID'>$title</a></td>
                                            <td>$date</td>
                                            <td>$numSignups / $capacity</td>
                                            <td><a class='button sign-up' href='eventSignUp.php?event_name=" . urlencode($title) . '&restricted=' . urlencode($restricted_signup) . "'>Sign Up</a></td>
                                        </tr>";*/
                                    //} else {
                                        /*echo "
                                        <tr data-event-id='$eventID'>
                                            <td>$restricted_signup</td>
                                            <td><a href='event.php?id=$eventID'>$title</a></td>
                                            <td>$date</td>
                                            <td>$numSignups / $capacity</td>
                                            <td><a class='button sign-up' style='background-color:#c73d06'>Sign Ups Closed!</a></td>
                                        </tr>";*/
                                    //}
                                    
                                    //} else {
                                        /*echo "
                                        <tr data-event-id='$eventID'>
                                            <td>$restricted_signup</td>
                                            <td><a href='Event.php?id=$eventID'>$title</a></td> <!-- Link updated here -->
                                            <td>$date</td>
                                            <td></td>
                                        </tr>";
                                    }
                                */}
                            ?>
                        </tbody>
                    </table>
                </div>

                <div class="table-wrapper">
                    <h2>Archived Events</h2>
                    <table class="general">
                        <tr>
                            <button onclick="window.location.href='viewAllEvents.php?filter=notStarted';">Not Started</button>
                            <button onclick="window.location.href='viewAllEvents.php?filter=inProgress';">In Progress</button>
                            <button onclick="window.location.href='viewAllEvents.php?filter=completed';">Completed</button>
                        </tr>
                        <thead>
                            <tr>
                                <th style="width:1px">Restricted</th>
                                <th>Title</th>
                                <th style="width:1px">Date</th>
                                <th style="width:1px">Capacity</th>
                                <th style="width:1px"></th>
                            </tr>
                        </thead>
                        <tbody class="standout">
                            <?php 
                                #require_once('database/dbPersons.php');
                                #require_once('include/output.php');
                                #$id_to_name_hash = [];
                                foreach ($archivedEvents as $event) {
                                   $eventID = $event->getID();
                                    $title = $event->getName();
                                    $goalAmount = $event->getGoalAmount();
                                    $startDate = $event->getStartDate();
                                    $endDate = $event->getEndDate();
                                    $startTime = $event->getStartTime();
                                    $endTime = $event->getEndTime();
                                    $description = $event->getDescription();
                                    
                                    $completed = $event->getCompleted();
                                    
                                    //if($accessLevel < 3) {
                                        echo "
                                        <tr data-event-id='$eventID'>
                                            <td><a href='event.php?id=$eventID'>$title</a></td>
                                            <td>$startDate</td>
                                            <td>$endDate</td>
                                        </tr>";
                                    //} else {
                                        /*echo "
                                        <tr data-event-id='$eventID'>
                                            <td>$restricted_signup</td>
                                            <td><a href='Event.php?id=$eventID'>$title</a></td> <!-- Link updated here -->
                                            <td>$date</td>
                                            <td></td>
                                        </tr>";
                                    }
                                */}
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