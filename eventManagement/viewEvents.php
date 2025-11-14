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
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <?php require_once('universal.inc') ?>
        <?php require_once('config.php') ?>
        <link rel="stylesheet" href="css/messages.css"></link>
        <script src="js/messages.js"></script>
        <title>Rappahannock CASA | Events</title>
        <script lang="js">
            function filterEvents(status){
                fetch('events.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({filter: status})
                })
                .then(response => response.json())
                .then(data => {
                    updateTable(data);
                })
                .catch(error => console.error('Error:', error));
            }
            function updateTable(events){
                const body = document.getElementById('eventBody');
                body.innerHTML = '';
                events.forEach(event => {
                    const row = body.insertRow();
                    row.innerHTML = `
                        <td>${event.name}</td>
                        <td>${event.goal}</td>
                        <td>${event.start_date}</td>
                        <td>${event.end_date}</td>
                        <td>${event.start_time}</td>
                        <td>${event.end_time}</td>
                        <td>${event.description}</td>
                        <td>${event.location}</td>
                        <td>${event.status}</td>
                    `;
                });
            }

            document.addEventListener("DOMContentLoaded", function(){
                const filter = (new URLSearchParams(window.location.search).get('filter')) || 'all';
                filterEvents(filter);
            });
        </script>
    </head>
    <body>
        <?php require_once('header.php'); ?>
        <?php require_once('database/dbEvents.php'); ?>
        <?php require_once('database/dbPersons.php'); ?>
        <?php
            // Fetch event status from APCu cache (all, notStarted, inProgress, completed)
            $status = apcu_fetch('event_status', $success);
            if(!$success){
                $progress = "inProgress";
            }
        ?>
        <main>
            <article>
                <table class="inProgress">
                    <tr class="eventButtons">
                        <button id="notStartedBtn" onclick="filterEvents('notStarted')">Not Started</button>
                        <button id="inProgressBtn" onclick="filterEvents('inProgress')">In Progress</button>
                        <button id="completedBtn" onclick="filterEvents('completed')">Completed</button>
                    </tr>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Goal</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Description</th>
                            <th>Location</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="eventBody">
                    </tbody>
                </table>
            </article>
        </main>
    </body>
</html>
