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
                    <!-- INLINE STYLES -->
                    <style>
                        .event-controls {
                            text-align: center;
                            margin: 20px 0;
                        }

                        .event-btn {
                            padding: 10px 16px;
                            margin: 4px;
                            font-size: 15px;
                            cursor: pointer;
                            border-radius: 8px;
                            border: none;
                        }

                        .delete-toggle-btn {
                            background: #c62828;
                            color: white;
                        }

                        .event-search {
                            padding: 10px 15px;
                            width: 280px;
                            font-size: 16px;
                            border-radius: 8px;
                            border: 1px solid #aaa;
                        }

                        .event-grid {
                            display: flex;
                            flex-wrap: wrap;
                            justify-content: center;
                            gap: 25px;
                        }

                        .event-card {
                            position: relative;
                            width: 320px;
                            background: #ffffff;
                            border-radius: 12px;
                            padding: 16px;
                            box-shadow: 0 3px 10px rgba(0,0,0,0.15);
                            transition: transform 0.15s ease;
                        }

                        .event-card:hover {
                            transform: translateY(-4px);
                        }

                        .event-checkbox {
                            position: absolute;
                            top: 10px;
                            right: 10px;
                            transform: scale(1.4);
                            display: none; /* hidden until delete mode */
                        }

                        .event-title {
                            font-size: 19px;
                            font-weight: bold;
                            margin-bottom: 8px;
                            color: #036;
                        }

                        .event-date {
                            font-size: 14px;
                            margin-bottom: 6px;
                            color: #444;
                        }

                        .progress-bg {
                            width: 100%;
                            height: 10px;
                            background: #eee;
                            border-radius: 5px;
                            overflow: hidden;
                            margin-top: 8px;
                        }

                        .progress-fill {
                            height: 100%;
                            background: #4CAF50;
                        }

                        #confirmDeleteContainer {
                            text-align: center;
                            margin-top: 20px;
                            display: none;
                        }

                        #confirmDeleteButton {
                            background: #b71c1c;
                            color: white;
                            padding: 12px 20px;
                            border: none;
                            border-radius: 8px;
                            font-size: 16px;
                            cursor: pointer;
                        }
                    </style>

                    <!-- TOP CONTROLS -->
                    <div class="event-controls">
                        <input type="text" placeholder="Search events…" id="eventSearch" class="event-search"
                            onkeyup="filterEvents()">

                        <?php if ($accessLevel >= 2): ?>
                        <button class="event-btn delete-toggle-btn" onclick="toggleDeleteMode()">Delete Events</button>
                        <?php endif; ?>
                    </div>

                    <!-- DELETE CONFIRM -->
                    <div id="confirmDeleteContainer">
                        <form method="POST" action="deleteMultipleEvents.php" onsubmit="return confirm('Delete selected events?');">
                            <input type="hidden" id="selectedEventsInput" name="selectedEvents">
                            <button id="confirmDeleteButton">Confirm Delete Selected</button>
                        </form>
                    </div>

                    <!-- EVENT CARDS GRID -->
                    <div class="event-grid" id="eventGrid">
                    <?php foreach ($upcomingEvents as $event): 

                        $eventID = $event['id'];
                        $title = $event['name'];
                        $desc = $event['description'];
                        $goal = $event['goalAmount'];
                        $dates = ($event['startDate'] === $event['endDate'])
                            ? $event['startDate']
                            : ($event['startDate'] . " to " . $event['endDate']);

                        $donations = fetch_donations_for_event($eventID);
                        $total = 0;
                        if ($donations){
                            foreach ($donations as $d){ $total += $d["amount"]; }
                            $completion = round($total / $goal * 100, 1);
                        } else { $completion = 0; }

                    ?>
                        <div class="event-card"
                            data-title="<?= strtolower($title) ?>"
                            data-desc="<?= strtolower($desc) ?>"
                            data-date="<?= strtolower($dates) ?>">

                            <input type="checkbox" class="event-checkbox" value="<?= $eventID ?>">

                            <div class="event-title">
                                <a href="specificEvent.php?id=<?= $eventID ?>" style="text-decoration:none; color:#036;">
                                    <?= htmlspecialchars($title) ?>
                                </a>
                            </div>

                            <div class="event-date"><?= $dates ?></div>

                            <div><strong>Goal:</strong> $<?= $goal ?></div>
                            <div><strong>Raised:</strong> $<?= $total ?> (<?= $completion ?>%)</div>

                            <div class="progress-bg">
                                <div class="progress-fill" style="width: <?= min($completion, 100) ?>%;"></div>
                            </div>
                        </div>

                    <?php endforeach; ?>
                    </div>

                    <!-- INLINE JS -->
                    <script>
                    let deleteMode = false;

                    function toggleDeleteMode() {
                        deleteMode = !deleteMode;

                        let checkboxes = document.querySelectorAll('.event-checkbox');
                        let confirmBox = document.getElementById('confirmDeleteContainer');

                        checkboxes.forEach(cb => {
                            cb.style.display = deleteMode ? "block" : "none";
                            cb.checked = false;
                        });

                        confirmBox.style.display = deleteMode ? "block" : "none";
                    }

                    function filterEvents() {
                        let query = document.getElementById('eventSearch').value.toLowerCase();
                        let cards = document.querySelectorAll('.event-card');

                        cards.forEach(card => {
                            let text = card.dataset.title + " " +
                                    card.dataset.desc + " " +
                                    card.dataset.date;

                            card.style.display = text.includes(query) ? "block" : "none";
                        });
                    }

                    document.getElementById("confirmDeleteContainer").addEventListener("submit", () => {
                        let checked = [...document.querySelectorAll(".event-checkbox:checked")]
                                    .map(cb => cb.value);

                        document.getElementById("selectedEventsInput").value = JSON.stringify(checked);
                    });
                    </script>


                <?php else: ?>
                <p class="no-events standout">There are currently no events available to view.<a class="button add" href="addEvent.php">Create a New Event</a> </p>
            <?php endif ?>
            <a class="button cancel" href="index.php">Return to Dashboard</a>
        </main>
    

    </body>
</html>