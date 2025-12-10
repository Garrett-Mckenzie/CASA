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
		<div>
            <form id="donorSearch" method="post">
                <table id="step1H">
                    <tr><th>1</th><th>Search Donor</th></tr>
                    <tr><td>First Name</td><td><input id="firstname" name="firstSearch" type="text"></td></tr>
                    <tr><td>Last Name</td><td><input id="lastname" name="lastSearch" type="text"></td></tr>
                    <tr><td><button type="submit">Search</button></td></tr>
                </table>
            </form>
            <?php
                if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['firstSearch']) && isset($_POST['lastSearch'])){
                    
                    $call = 'python -u "donorDB.py" -s "first=' . escapeshellarg($_POST['firstSearch']) . '" "last=' . escapeshellarg($_POST['lastSearch']) . '"';
                    
                    echo <<<TEST
                    <p>Call: {$call}</p>
                    <p>POST Requests: "{$_POST['firstSearch']} {$_POST['lastSearch']}"</p>
                    TEST;

                    exec($call, $res, $return_var);
                    if($return_var != 0) {
                        echo "<p>Error executing Python script. Return code: $return_var</p>";
                        echo file_exists("donorDB.py") ? "<p>py file exists</p>" : "<p>donorDB.py file not found.</p>";
                    }

                    echo $res ? "<p>Python script executed successfully. Output:</br>$res</p>" : "<p>No output from Python script.</p>";
                }
            ?>
            <form id="donorDelete" method="post">
                <table id="step2H">
                    <tr><th>2</th><td>Delete Donor</td></tr>
                    <tr id="donorList"></tr>
                    <tr><td><button type="submit">Delete</button></td></tr>
                </table>
            </form>
        </div>
	</main>
</html>