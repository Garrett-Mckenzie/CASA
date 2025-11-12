<!DOCTYPE html>
<html>
    <head>
        <?php require_once('universal.inc');?>
        <?php require_once('database/dbEvents.php');?>
        <?php require_once('database/dbDonations.php');?>
        <?php require_once('database/dbPersons.php');  
            $event = retrieve_event2($_GET["id"])
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

                $donations = fetch_donations_for_event($_GET["id"]);
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