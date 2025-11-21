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

</head>

<body>
<?php require_once('header.php') ?>

<div class="container mt-4 p-4 bg-white rounded shadow">
		<h3 class="mb-3">Add a New Donation</h3>
		<hr style = "height: 1px; background-color: black;"></hr>
		<form id="emailForm" action="addDonationHandler.php" method="post">

			<div class = "mb-3">
				<label><b>First Name of Donor</b></label><br/>
				<input type = "text" id="firstName" name="firstName"></input>
			</div>

			<div class = "mb-3">
				<label><b>Last Name of Donor</b></label><br/>
				<input type = "text" id="lastName" name="lastName"></input>
			</div>

			<div class="mb-3">	
				<label><b>Date of Donation</b></label><br/>
				<input type = "datetime-local" id="date" name="date"></input>
			</div>

			<div class="mb-3">	
				<label><b>Amount Donated</b></label><br/>
				<input type = "number" id="amount" name="amount" step = "0.01" min="0" placeholder="Ender amount in $USD"></input>
			</div>

			<div class="mb-3">	
				<label><b>Associated Fees</b></label><br/>
				<input type = "number" id="fee" name="fee" step = "0.01" min="0" placeholder="Ender amount in $USD"></input>
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

			<button type="submit" class="btn btn-primary">Add Donation</button>
		</form>
</div>
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
