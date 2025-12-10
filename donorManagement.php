<?php

	session_cache_expire(30);
	session_start();

	$loggedIn = false;
	$accessLevel = 0;
	$userID = null;
	if(isset($_SESSION['_id'])){
		$loggedIn = true;
		$accessLevel = $_SESSION['access_level'];
		$userID = $_SESSION['_id'];
	}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html">
        <link rel="stylesheet" href="css/base.css">
        <link rel="stylesheet" href="css/messages.css"></link>
        <script src="js/messages.js"></script>
        <title>Rappahannock CASA | Donor Management</title>
        <?php
            if($accessLevel < 2){
                header("Location: index.php");
                exit();
            }
        ?>
    </head>
    <body>
        <h1 class="page-title">Donor Management</h1>
        <div class="container">
            <p>Welcome to the Donor Management page. Here you can manage donor information.</p>
            <!-- Additional donor management functionalities can be added here -->
        </div>
        <div class="full-width-bar-sub">
            <div class="context-box-test">
                <div class="icon-overlay">
                    <img style="border-radius: 5px;" src="images/user-plus-solid.svg" alt="Add Donor Icon">
                </div>
                <img class="background-image" src="images/blank-white-background.svg">
                <div class="large-text-sub">Add Donor</div>
                <button class="arrow-button" onclick="window.href.location='addDonor.php'">→</button>
            </div>
        </div>


        <div class="full-width-bar-sub">
            <div class="context-box-test">
                <div class="icon-overlay">
                    <img style="border-radius: 5px;" src="images/user-plus-solid.svg" alt="Add Donor Icon">
                </div>
                <img class="background-image" src="images/blank-white-background.svg">
                <div class="large-text-sub">Remove Donor</div>
                <button class="arrow-button" onclick="window.href.location='deleteDonor.php'">→</button>
            </div>
        </div>
    </body>
    <?php include('header.php') ?>
</html>