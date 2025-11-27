<?php
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

?>
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
try{
				include_once("database/dbinfo.php");
				$con = connect();
				$query = "SELECT name FROM dbevents";
				$eventNames = mysqli_query($con,$query);
}
catch(Exception $e){
				echo "Message. ".e->getMessage();
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Add and Edit Donations</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
	<meta http-equiv="Content-Type" content="text/html">
	<link rel="stylesheet" href="css/base.css">
	<link rel="stylesheet" href="css/messages.css"></link>


</head>

<body>
<?php require_once('header.php') ?>
<h1 class="page-title">Donation Managment</h1>
<?php

if (isset($_GET["addAttempt"]) and isset($_SESSION["addComplete"]) and isset($_SESSION["reason"])){

				$status = $_SESSION["addComplete"];
				unset($_SESSION["addComplete"]);
				$reason = $_SESSION["reason"];
				unset($_SESSION["reason"]);

				echo '<div class="container mt-4 p-4 bg-white rounded shadow">';
				if ($status == "t"){
								echo '<h3 class="mb-3">New Donation Has Been Added!</h3>';
								echo '<p>'.$reason.'</p>';
				}
				else{
								echo '<h3 class="mb-3">There Was An Issue Adding The Donation</h3>';
								echo '<p>'.$reason.'</p>';
				}
				echo '</div>';
}

?>
<div class="container mt-4 p-4 bg-white rounded shadow">
		<h3 class="mb-3">Add a New Donation</h3>
		<hr style = "height: 1px; background-color: black;"></hr>
		<form id="addDonation" action="addDonationHandler.php" method="post">


			<div class = "mb-3">
				<label><b>First Name of Donor</b></label><br/>
				<input type = "text" id="firstName" name="firstName"></input>
			</div>

			<div class = "mb-3">
				<label><b>Last Name of Donor</b></label><br/>
				<input type = "text" id="lastName" name="lastName"></input>
			</div>

			<div class = "mb-3">
				<label><b>Donor Email</b></label><br/>
				<input type = "text" id="donorEmail" name="donorEmail"></input>
			</div>


			<div class="mb-3">	
				<label><b>Date of Donation</b></label><br/>
				<input type = "datetime-local" id="date" name="date"></input>
			</div>

			<div class="mb-3">	
				<label><b>Amount Donated*</b></label><br/>
				<input type = "number" id="amount" name="amount" step = "0.01" min="0" placeholder="Ender amount in $USD" required></input>
			</div>

			<div class="mb-3">	
				<label><b>Associated Fees</b></label><br/>
				<input type = "number" id="fee" name="fee" value = "0" step = "0.01" min="0" placeholder="Ender amount in $USD"></input>

			</div>

			<div class = "mb-3">
				<label><b>Associated Fundraising Event</b></label><br/>
				<select name="eventName" id="eventName">
					<option value = "none">none</option>
<?php
foreach($eventNames as $row)
{
				echo "<option value = ".$row["name"].">".$row["name"]."</option>";
}
?>
				</select>
			</div>

			<div class = "mb-3">
				<label><b>Reason For Donation</b></label><br/>
				<textarea name="reason" cols="50"></textarea>
			</div>

			<div class="mb-3">	
				<label><b>Donation Already Thanked?</b></label><br/>
				<input type="checkbox" id="thanked" name="thanked" value="y"></input>
			</div>

			<button type="submit" class="btn btn-primary">Add Donation</button>
		</form>
</div>
<div class="container mt-4 p-4 bg-white rounded shadow">
		<h3 class="mb-3">Find and Edit Donations</h3>
		<hr style = "height: 1px; background-color: black;"></hr>
		<form id="editDonation" action="editDonationHandler.php" method="post">

			<div class = "mb-3">
				<label><b>First Name of Donor</b></label><br/>
				<input type = "text" id="firstName" name="firstName"></input>
			</div>

			<div class = "mb-3">
				<label><b>Last Name of Donor</b></label><br/>
				<input type = "text" id="lastName" name="lastName"></input>
			</div>

			<div class = "mb-3">
				<label><b>Email of Donor</b></label><br/>
				<input type = "text" id="email" name="email"></input>
			</div>


			<div class="mb-3">	
				<label><b>Date of Donation</b></label><br/>
				<input type = "datetime-local" id="date" name="date"></input>
			</div>

			<div class = "mb-3">
				<label><b>Max Donation Amount</b></label><br/>
				<input type = "number" id="maxAmount" name="maxAmount" min="0" max="999999999" placeholder="Ender amount in $USD" value = "0" step ="0.1"></input>
			</div>
			<div class = "mb-3">
				<label><b>Min Donation Amount</b></label><br/>
				<input type = "number" id="minAmount" name="minAmount" min="0" max="999999999" placeholder="Ender amount in $USD" value = "0" step ="0.1"></input>

			</div>


		<input type = "hidden" id="goal" name="goal" value ="search">
		<button type="submit" class="btn btn-primary">Search</button>
</form>
</div>

<?php

if (isset($_GET["searchAttempt"]) and isset($_SESSION["searchComplete"]) and isset($_SESSION["reason"])){

				$status = $_SESSION["searchComplete"];
				unset($_SESSION["searchComplete"]);
				$reason = $_SESSION["reason"];
				unset($_SESSION["reason"]);

				echo '<div class="container mt-4 p-4 bg-white rounded shadow">';
				if ($status == "t"){
								echo '<h3 class="table-heading">Search Results</h3>';
								echo '<p>Donation info can be directly edited from this table by clicking the edit button after making any changes.</p>';
								echo '<table id="myTable" class="casa-table">';
								echo '<tr>';
								echo '<th><b>amount</b></th>';
								echo '<th><b>reason</b></th>';
								echo '<th><b>date (mm-dd-yyyy)</b></th>';
								echo '<th><b>donation fees</b></th>';
								echo '<th><b>thanked</b></th>';
								echo '<th><b>first name</b></th>';
								echo '<th><b>last name</b></th>';
								echo '<th><b>email</b></th>';
								echo '<th><b>donor zip</b></th>';
								echo '<th><b>donor city</b></th>';
								echo '<th><b>edit donation</b></th>';
								echo '</tr>';
								foreach($reason as $row){
												echo '<tr>';
												echo '<form action="./editDonationHandler.php" method="post">';
												echo '<input type = "hidden" id="goal" name="goal" value ="edit">';
												echo '<td><input type = "number" id="amount" name="amount" min="0" max="999999" step="0.1" value="'.$row[0].'" placeholder="'.$row[0].'"></input></td>';
												echo '<td><input type="text" id = "reason" name = "reason" value="'.$row[1].'"></input></td>';
												echo '<td><input type="text" id="date" name="date" value="'.$row[2].'"></input></td>';
												echo '<td><input type="number" id="fee" name="fee" min="0.00" max="999999" step="0.1" value="'.$row[3].'" placeholder="'.$row[3].'"></input></td>';
												echo '<td><input type="number" id="thanked" name="thanked" min="0" max="1" step="1" value="'.$row[4].'" placeholder="'.$row[4].'"></input></td>';
												echo '<td><input type="text" id = "first" name = "first" value="'.$row[5].'"></input></td>';
												echo '<td><input type="text" id = "last" name = "last" value="'.$row[6].'"></input></td>';
												echo '<td><input type="text" id = "email" name = "email" value="'.$row[7].'"></input></td>';
												echo '<td>'.$row[8].'</td>';
												echo '<td>'.$row[9].'</td>';
												echo '<td>';
												echo '<input type="hidden" id = "donorID" name = "donorID" value="'.$row[11].'"></input>';
												echo '<input type="hidden" id = "donationID" name = "donationID" value="'.$row[10].'"></input>';
												echo '<button type="submit" style="background: none;border: none;">';
												echo '<svg width="20px" height="20px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">

																<title/>

																<g id="Complete">

																<g id="edit">

																<g>

																<path d="M20,16v4a2,2,0,0,1-2,2H4a2,2,0,0,1-2-2V6A2,2,0,0,1,4,4H8" fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>

																<polygon fill="none" points="12.5 15.8 22 6.2 17.8 2 8.3 11.5 8 16 12.5 15.8" stroke="#000000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>

																</g>

																</g>

																</g>

																</svg>';
												echo '</button>';
												echo '</a>';
												echo '</td>';
												echo '</form>';
												echo '</tr>';
								}
								echo "</table>";
				}
				else{
								echo '<h3 class="mb-3">There Was An Issue Searching Through Donations</h3>';
								echo '<p>'.$reason.'</p>';
				}
				echo '</div>';
}

?>

<br/>
<br/>

<footer class="footer" style="footer">
			<!-- Left Side: Logo & Socials -->
			<div class="footer-left">
		<img src="images/RAPPAHANNOCK_v_White-300x300.png" alt="Logo" class="footer-logo">
		<div class="social-icons">
				<a href="#"><i class="fab fa-facebook"></i></a>
				<a href="#"><i class="fab fa-twitter"></i></a>
				<a href="#"><i class="fab fa-instagram"></i></a>
				<a href="#"><i class="fab fa-linkedin"></i></a>
		</div>
			</div>

			<!-- Right Side: Page Links -->
			<div class="footer-right">
		<div class="footer-section">
				<div class="footer-topic">Connect</div>
				<a href="https://www.facebook.com/RappCASA/" target="_blank">Facebook</a>
				<a href="https://www.instagram.com/rappahannock_casa/" target="_blank">Instagram</a>
				<a href="https://rappahannockcasa.com/" target="_blank">Main Website</a>
		</div>
		<div class="footer-section">
				<div class="footer-topic">Contact Us</div>
				<a href="mailto:rappcasa@gmail.com">rappcasa@gmail.com</a>
				<a href="tel:5407106199">540-710-6199</a>
		</div>
			</div>
	</footer>
</body> 
