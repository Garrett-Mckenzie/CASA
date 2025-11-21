<?php
    session_cache_expire(30);
    session_start();

    $code = $_GET['edit'] ?? 0;

    if ($code == 1) {
        $msg = "Successfully Edited Fundraiser Event";
        header("refresh:2;url=viewAllEvents.php"); #these need to be above any html that echos to screen
    } 
    else {
        $msg = "Successfully Created Fundraiser Event";
        header("refresh:2;url=addEvent.php");
    }
?>

    <!DOCTYPE html>
    <html>
        <head>
            <?php require_once('universal.inc') ?>
            <title>Rappahannock CASA | Create Event</title>
            <style>
            .error-banner {
                width: 100%;
                background: #3ac628ff;               
                color: white;
                padding: 18px 22px;
                font-size: 20px;
                font-weight: bold;
                text-align: center;
                border-bottom: 4px solid #1c8e00ff;  
                box-shadow: 0 3px 8px rgba(0,0,0,0.25);
                margin-bottom: 20px;
            }
            </style>
        </head>
        <body>
            <?php 
                require_once('header.php');
            ?>
            <h1>Fundraisers</h1>
            <div class="error-banner">
                <?= htmlspecialchars($msg) ?>
            </div>
        </body>
    </html>