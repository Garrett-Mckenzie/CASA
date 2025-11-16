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

?>


<!DOCTYPE html>
<html>
    <head>
        <?php require_once('universal.inc');?>
        <?php require_once('database/dbEvents.php');?>
        <?php require_once('database/dbDonations.php');?>
        <?php require_once('database/dbPersons.php');
            require_once('include/input-validation.php');  
            $get = sanitize($_GET);
            $event = retrieve_event2($get["id"])
        ?>
        
        
        <title>Rappahannock CASA | <?php echo $event["name"];?></title>
    </head>
    <body>
        <?php require_once('header.php') ?>

        <h1> <?php echo $event["name"];?></h1>
        <main class="general">
            <?php
                //event attributes
                $goal = $event["goalAmount"];
                $desc = $event["description"];

                $donations = fetch_donations_for_event($get["id"]);
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

                $completed = ($completion>=100)? 1:0;
            ?>

            <p> <?php echo $desc;?></p>
            <p> <?php if($completed == 1){echo "completed";}else{echo "incomplete";}?></p>
            <p> Goal Amount: $<?php echo $goal;?></p>
            <p> Current Total Donations: $<?php echo $totalRaised;?> </p>
            <p> Completion: <?php echo $completion;?>%</p>
        </main>
    

    </body>
</html>