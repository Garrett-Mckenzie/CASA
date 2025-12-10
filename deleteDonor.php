<!--
    Removes donors from the database based on first and last name search
    Access Level: 2 (Admin)+
    
    The user first searches for donors by first and last name.
    A list of donors with the corresponding names is displayed with checkboxes.
    The user selects the donors to delete and clicks the delete button.
    The users are removed from the database, ignoring relations.
    If the deletion is successful, the user is informed and redirected to the donor management page.
    Else, an error message is displayed.
-->

<?php

	session_cache_expire(30);
	session_start();

	$loggedIn = false;
	$accessLevel = -1;
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
        <link rel="stylesheet" href="css/messages.css">
        <link rel="stylesheet" href="delDonor.scss">
        <script src="js/messages.js"></script>
        <title>Rappahannock CASA | Delete Donor</title>
        <?php
            if($accessLevel < 2){
                header("Location: index.php");
                exit();
            }
        ?>
        <?php include('header.php'); ?>
	</head>
	<main>
        <script type="text/javascript">
            // Fetches donors based on first and last name input
            function showDonors(){
                var first = document.getElementById("firstname").value;
                var last = document.getElementById("lastname").value;

                if(first !== "" && last !== ""){
                    exec("donorDB.py -s " + first + " " + last, function(output){
                        var donors = output.trim().split("\n");
                        var select = document.getElementById("donors");
                        select.innerHTML = "";

                        donors.forEach(function(donor){
                            var parts = donor.split(" - ");
                            if(parts.length === 2){
                                var nameEmail = parts[0];
                                var email = parts[1];
                                var nameParts = nameEmail.split(" ");
                                var firstName = nameParts[0];
                                var lastName = nameParts.slice(1).join(" ");
                                var option = document.createElement("option");
                                option.value = donor; // Assuming donor string is unique identifier
                                option.text = nameEmail + " - " + email;
                                select.appendChild(option);
                            }
                        });
                    });
                }
            }
            // Removes selected donors from the database
            function removeDonors(){
                var select = document.getElementById("donors");
                var selected = Array.from(select.selectedOptions).map(option => option.value);

                var xhttp = new XMLHttpRequest();
                xhttp.open("POST", "deleteDonor.php", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send("deleteIDs=" + JSON.stringify(selected));
            }
        </script>
		<div>
            <table id="step1H">
                <tr><th>1</th><th>Search Donor</th></tr>
                <tr><td>First Name</td><td><input id="firstname" type="text"></td></tr>
                <tr><td>Last Name</td><td><input id="lastname" type="text"></td></tr>
                <tr><td><button onclick="showDonors()">Search</button></td></tr>
            </table>
            <table id="step2H">
                <tr><th>2</th><td>Delete Donor</td></tr>
                <tr id="donorList"><td>
                    <select id="donors" multiple size="10" style="width:300px;">
                    </select>
                </tr>
                <tr><td><button onclick="removeDonors()">Delete</button></td></tr>
            </table>
        </div>
	</main>
</html>