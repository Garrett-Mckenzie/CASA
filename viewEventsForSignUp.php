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
?>
<!DOCTYPE html>
<html>
    <head>
        <?php require_once('universal.inc') ?>
        <link rel="stylesheet" href="css/messages.css"></link>
        <script src="js/messages.js"></script>
        <title>Rappahannock CASA | Events Open for Sign-Up</title>
    </head>
    <body>
        <?php require_once('header.php') ?>
        <h1>Events</h1>
        <main class="general">
            <h2>Open for Sign-Up</h2>
            
                <p class="no-events standout">There are currently no events available for sign-up.</p>
            <a class="button cancel" href="index.php">Return to Dashboard</a>
        </main>
    </body>
</html>