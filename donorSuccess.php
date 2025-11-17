<?php
    session_cache_expire(30);
    session_start();
    header("refresh:2;url=addDonor.php");
?>

    <!DOCTYPE html>
    <html>
        <head>
            <?php require_once('universal.inc') ?>
            <title>Rappahannock CASA | Add Donor</title>
        </head>
        <body>
            <?php require_once('header.php') ?>
            <h1>Donor Added!</h1>
        </body>
    </html>