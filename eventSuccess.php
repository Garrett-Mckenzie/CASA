<?php
    session_cache_expire(30);
    session_start();
    header("refresh:2;url=addEvent.php");
?>

    <!DOCTYPE html>
    <html>
        <head>
            <?php require_once('universal.inc') ?>
            <title>Rappahannock CASA | Create Event</title>
            <script>
                const KEY = "AddEventData";
                const cache = localStorage.getItem(KEY);
                if (cache) {
                    localStorage.removeItem(KEY);
                }
            </script>
                
        </head>
        <body>
            <?php require_once('header.php') ?>
            <h1>Event Created!</h1>
        </body>
    </html>