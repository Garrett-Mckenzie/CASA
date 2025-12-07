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
        <title>Rappahannock CASA | Fundraisers</title>
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
        
        <h1>Fundraising Events</h1>
        <main class="general">
            <?php 
              
                //require_once('database/dbevents.php');
                //require_once('domain/Event.php');
                //$events = get_all_events();
                $events = fetch_all_events();
                $today = strtotime(date("Y-m-d"));
                
                // Filter out expired events
                $upcomingEvents = array_filter($events , fn($event)=> strtotime($event["endDate"])>=$today);
                $upcomingIDs = array_column($upcomingEvents, 'id');
                $user = retrieve_person($userID);

                if (sizeof($events) > 0): ?>
                    <!-- INLINE STYLES -->
                    <style>
                        .event-controls {
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            gap: 12px;        /* spacing between search bar + gear */
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
                            background: #787777ff;
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

                        .delete-toggle-btn:hover {
                            background: #5e5e5eff !important;
                            color: white !important;
                        }

                        #confirmDeleteButton:hover {
                            background: #8e1b1b !important;
                            color: white !important;
                        }

                        #confirmDeleteContainer {
                            text-align: center;
                            justify-content: center;
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

                        /* Gear Icon Button */
                        .gear-btn {
                            background: none;
                            border: none;
                            padding: 0;
                            cursor: pointer;
                            width: 40px;
                            height: 40px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                        }

                        .gear-icon svg {
                            width: 50px;
                            height: 50px;
                            transition: transform 0.4s ease;
                            transform-origin: 50% 50%; /* keeps rotation centered */
                        }

                        /* Coloring the gear */
                        .gear-icon svg path {
                            stroke: #555;      /* normal state color */
                        }

                        /* Toggled ON */
                        .gear-btn.active svg path {
                            stroke: #d9534f;   /* red to indicate delete mode */
                        }

                        .gear-btn.active svg {
                            transform: rotate(180deg); /* spins without shifting */
                        }
                        .circle-arrow-button {
                            width: 38px;
                            height: 38px;
                            border-radius: 50%;
                            background: #4CAF50;          /* green add button */
                            color: white;
                            font-size: 26px;
                            font-weight: bold;
                            border: none;
                            cursor: pointer;

                            display: flex;
                            align-items: center;
                            justify-content: center;

                            transition: background 0.25s ease, transform 0.2s ease;
                        }

                        .circle-arrow-button:hover {
                            background: #43a047;           /* darker green on hover */
                            transform: translateY(-2px);   /* subtle lift */
                        }

                        .circle-arrow-button:active {
                            transform: scale(0.95);        /* slight press effect */
                        }

                        .button-text {
                            line-height: 1;
                            margin-top: -3px; /* optical center for + sign */
                        }
                        .past-event {
                            background: #e6e6e6 !important;  /* light grey */
                            color: #555 !important;          /* darker text */
                            opacity: 0.75;
                        }

                        .past-event .event-title a {
                            color: #444 !important;
                        }

                        .past-event:hover {
                            transform: none; /* disable hover lift */
                            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
                        }
                        .edit-btn {
                            display: none; /* hidden normally */
                            position: absolute;
                            top: 6px;
                            right: 35px; /* moves left so it sits next to the checkbox */
                        }

                        .edit-icon {
                            width: 20px;
                            height: 20px;
                            cursor: pointer;
                            transition: transform 0.1s ease-in-out;
                        }

                        .edit-icon:hover {
                            transform: scale(1.15);
                        }

                    </style>

                    <!-- TOP CONTROLS -->
                    <div class="event-controls">
                        <input type="text" placeholder="Search events…" id="eventSearch" class="event-search"
                            onkeyup="filterEvents()">

                        <?php if ($accessLevel >= 2): ?>
                            <button class="event-btn gear-btn" id="gearToggle" onclick="toggleDeleteMode()">
                                <span class="gear-icon">
                                    <svg width="50px" height="50px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M17.2994 10.4527L19.2267 10.7677C19.3846 10.7935 19.5003 10.9298 19.5 11.0896V12.883C19.5 13.0412 19.3865 13.1768 19.2303 13.2042L17.3004 13.543C17.1885 13.9298 17.0349 14.3022 16.8415 14.6543L17.9823 16.2382C18.0759 16.3679 18.0612 16.5463 17.9483 16.6595L16.6804 17.9283C16.5682 18.0401 16.3921 18.0561 16.2623 17.9645L14.6627 16.8424C14.3099 17.0387 13.9352 17.1952 13.5442 17.3103L13.2034 19.231C13.176 19.3865 13.0406 19.5 12.8825 19.5H11.0888C10.9294 19.5 10.7934 19.3849 10.7676 19.228L10.4493 17.3168C10.059 17.204 9.6823 17.0485 9.32585 16.8525L7.73767 17.9648C7.60821 18.0558 7.43178 18.0401 7.31992 17.9283L6.05198 16.6595C5.93947 16.5463 5.9248 16.3686 6.01741 16.2391L7.13958 14.6697C6.94163 14.3116 6.78444 13.9337 6.67062 13.5414L4.76905 13.2042C4.61349 13.1765 4.5 13.0412 4.5 12.883V11.0896C4.5 10.9304 4.61544 10.7941 4.77263 10.768L6.67421 10.4514C6.78868 10.0582 6.94586 9.68022 7.14316 9.32315L6.0347 7.73739C5.94371 7.60793 5.95937 7.43185 6.07122 7.32L7.33883 6.0525C7.452 5.94 7.62908 5.925 7.7592 6.01793L9.33433 7.14293C9.68817 6.94924 10.0639 6.795 10.4552 6.6825L10.767 4.77359C10.7927 4.61576 10.929 4.5 11.0888 4.5H12.8825C13.041 4.5 13.1763 4.61413 13.2037 4.77L13.5399 6.68935C13.929 6.8025 14.304 6.95837 14.6591 7.15467L16.2385 6.01957C16.3683 5.92598 16.5464 5.94065 16.6595 6.05348L17.9278 7.32098C18.0397 7.43315 18.0553 7.60957 17.9643 7.73902L16.8392 9.34239C17.0323 9.69424 17.1865 10.066 17.2994 10.4527ZM9.71725 12C9.71725 13.2607 10.7393 14.2826 12.0001 14.2826C13.2608 14.2826 14.2829 13.2607 14.2829 12C14.2829 10.7394 13.2608 9.71742 12.0001 9.71742C10.7393 9.71742 9.71725 10.7394 9.71725 12Z" stroke="#000000"/>
                                    </svg>
                                </span>
                            </button>
                        <?php endif; ?>
                        <?php if ($accessLevel >= 2): ?>
                            <button class="circle-arrow-button" onclick="window.location.href='addEvent.php'">
                                <span class="button-text">
                                    +
                                </span>
                            </button>
                        <?php endif; ?>
                    </div>

                    <!-- DELETE CONFIRM -->
                    <div id="confirmDeleteContainer">
                        <form method="POST" action="deleteMultipleEvents.php" onsubmit="return confirm('Delete selected events?');">
                            <input type="hidden" id="selectedEventsInput" name="selectedEvents">
                            <button id="confirmDeleteButton">Delete Selected</button>
                        </form>
                    </div>

                    <!-- EVENT CARDS GRID -->
                    <div class="event-grid" id="eventGrid">
                    <?php foreach ($events as $event): 

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
                            $safeGoal = ($goal==0)? 1: $goal;
                            $completion = round($total / $safeGoal * 100, 1);
                        } else { $completion = 0; }
                        $isUpcoming = in_array($eventID, $upcomingIDs);
                    ?>
                        <div class="event-card <?= $isUpcoming ? '' : 'past-event' ?>"
                            data-title="<?= strtolower($title) ?>"
                            data-desc="<?= strtolower($desc) ?>"
                            data-date="<?= strtolower($dates) ?>">

                            <input type="checkbox" class="event-checkbox" value="<?= $eventID ?>">
                            <a class="edit-btn" href="editEvent.php?id=<?= $event['id'] ?>">
                                <svg width="20px" height="20px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">

                                <title/>

                                <g id="Complete">

                                <g id="edit">

                                <g>

                                <path d="M20,16v4a2,2,0,0,1-2,2H4a2,2,0,0,1-2-2V6A2,2,0,0,1,4,4H8" fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>

                                <polygon fill="none" points="12.5 15.8 22 6.2 17.8 2 8.3 11.5 8 16 12.5 15.8" stroke="#000000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>

                                </g>

                                </g>

                                </g>

                                </svg>
                            </a>

                            <div class="event-title">
                                <a href="specificEvent.php?id=<?= $eventID ?>" style="text-decoration:none; color:#036;">
                                    <?= htmlspecialchars($title) ?>
                                    <?php if (!$isUpcoming): ?>
                                        <span style="color:#777;">(past)</span>
                                    <?php endif; ?>
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
                        const editButtons = document.querySelectorAll(".edit-btn");
                        deleteMode = !deleteMode;

                        let checkboxes = document.querySelectorAll('.event-checkbox');
                        let confirmBox = document.getElementById('confirmDeleteContainer');
                        let gearBtn = document.getElementById('gearToggle');

                        // toggle button visual state
                        gearBtn.classList.toggle("active", deleteMode);

                        // toggle checkboxes
                        checkboxes.forEach(cb => {
                            cb.style.display = deleteMode ? "block" : "none";
                            cb.checked = false;
                        });
                        editButtons.forEach(btn => {
                                btn.style.display = deleteMode ? "inline-block" : "none";
                        });

                        // toggle delete confirmation box
                        confirmBox.style.display = deleteMode ? "flex" : "none";
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
    <footer class="footer">
            <!-- Left Side: Logo & Socials -->
            <div class="footer-left">
                <img src="images/RAPPAHANNOCK_v_White-300x300.png" alt="Logo" class="footer-logo">
                <div class="social-icons">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>

            <!-- Right Side: Page Links -->
            <div class="footer-right">
                <div class="footer-section">
                    <div class="footer-topic">Connect</div>
                    <a href="https://www.facebook.com/RappCASA/" target="_blank">Facebook</a>
                    <a href="https://www.instagram.com/rappahannock_casa/" target="_blank">Instagram</a>
                    <a href="https://rappahannockcasa.com/" target="_blank">Main Website</a>
                </div>
                <div class="footer-section">
                    <div class="footer-topic">Contact Us</div>
                    <a href="mailto:rappcasa@gmail.com">rappcasa@gmail.com</a>
                    <a href="tel:5407106199">540-710-6199</a>
                </div>
            </div>
	</footer>
</html>
