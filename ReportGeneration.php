<?php
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

?>
<?php
	$os = NULL;
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'){
		$os = "w";
	}
	else{
		$os = "l";
	}

	$output = [];	
	if ($os == "w"){
		exec("dir reports /b",$output);
	}
	else{
		exec("ls reports",$output);
	}
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Report Generator</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

</head>

<body>
    <?php require_once('header.php') ?>

	<div class="container mt-4 p-4 bg-white rounded shadow">
		<h3 class="mb-3">Report Generator</h3>
		<hr style = "height: 1px; background-color: black;"></hr>
		<form id="emailForm" action="./formHandler.php" method="post">

			<div class = "mb-3">
				<label><b>Name of Report</b></label><br/>
				<input type = "text" id="reportName" name="reportName"></input>
			</div>

			<div class="mb-3">	
				<label><b>Starting Date For Report</b></label><br/>
				<input type = "datetime-local" id="startDate" name="startDate"></input>
			</div>

			<div class="mb-3">
				<label><b>Ending Date For Report</b></label><br/>
				<input type = "datetime-local" id="endDate" name="endDate"></input>
			</div>

			<div class="mb-3">
				<label><b>Donation Statistics</b></label><br/>
				<input type="checkbox" name="DonationsStatsOverview" value="True"> Incldue Donations Statistics Overview</input><br/>
				<input type="checkbox" name="GrowthAndTrends" value="True"> Include Growth/Trend Analysis</input><br/>
			</div>

			<!-- Put Other Parts Of The Form In These Divs -->
			<div class="mb-3">
				<label><b>Fundraiser Performance</b></label></br>
				<input type="checkbox" name="IncludeGraph" value="True"> Include Graph</input><br/>
				<input type="checkbox" name="IncludeTable" value="True"> Include Table</input><br/>
			</div>
		
			<div class="mb-3">
				<label><b>Donor Insights</b></label></br>
				 Number of Top Donors To Display <input type="quantity" name="NumTopDonors" min="0" max="100" step="1" value="10"></input><br/>
				<input type="checkbox" name="IncludeNewDonors" value="True"> Include New Donor Information</input><br/>
			</div>

			<div class="mb-3">
				<label><b>Visual Analysis</b></label></br>
				<input type="checkbox" name="IncludePareto" value="True"> Include Pareto Chart</label><br/>
				<input type="checkbox" name="IncludeFunnel" value="True"> Include Donor Funnel Chart</label><br/>
				<input type="checkbox" name="IncludeDonationsByState" value="True"> Include Top Donations By State</label><br/>
				<input type="checkbox" name="IncludeDonationsByZip" value="True"> Include Top Donations By Zip</input><br/>
			</div>

			<div class="mb-3">
				<label><b>Summary Section</b></label></br>
				<input type="checkbox" name="IncludeSummarySection" value="True"> Include Summary Paragraph </input></br>
			</div>

			<div class="mb-3">
				<label><b>Graph Descrpiptions</b></label><br/>
				<input type="checkbox" name="graphDesc" value="True"> Include Graph Descriptions</input><br/>	
			</div>

			<input type = "hidden" id="os" name="os" value="<?php echo$os; ?>">
			<button type="submit" class="btn btn-primary">Generate Report</button>

		</form>
	</div>
	<div class="container mt-4 p-4 bg-white rounded shadow">
		<form action="./reports/delHandler.php" method="post">
		<table style="width:100%">
			<tr>
				<th>Report History</th><th>Delete Report</th>
			<tr>
			<?php
				foreach ($output as $value){
					if (str_contains($value,".pdf")){
						echo "<tr>";
						echo "<td><a target='_blank' href=./reports/".$value.">".$value."</a></td>";
						echo "<td><input type='checkbox' id =".$value." name=".$value."></input></td>";
						echo "</tr>";
					flush();
					ob_flush();
					}
				}
			?>
		</table>
		<br/>
		<button type="submit" class="btn btn-primary">Submit Changes</button>
		</form>
	</div>
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
