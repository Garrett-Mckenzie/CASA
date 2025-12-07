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
		exec("dir reportGeneration/reports /b",$output);
	}
	else{
		exec("ls reportGeneration/reports",$output);
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
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/yourkit.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
	<meta http-equiv="Content-Type" content="text/html">
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/messages.css">

    <style>
        /* --- GLOBAL RESET & FONTS --- */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        * { box-sizing: border-box; }

        body {
            font-family: 'Quicksand', sans-serif;
            background-color: #f9f9f9;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }


        /* --- CARD CONTAINER --- */
        .content-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.15);
            width: 100%;
            max-width: 900px;
            margin: 20px auto;
        }

        h3 {
            margin-top: 0;
            color: #00447b;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 15px;
            font-weight: bold;
        }

        /* --- FORM ELEMENTS --- */
        .mb-3 {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            color: #444;
            margin-bottom: 8px;
            font-size: 14px;
        }

        /* Styled inputs */
        input[type="text"],
        input[type="datetime-local"],
        input[type="number"],
        input[type="quantity"] {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border-radius: 8px;
            border: 1px solid #aaa;
            font-family: 'Quicksand', sans-serif;
            background-color: #fff;
        }

        input:focus {
            outline: 2px solid #00447b;
            border-color: #00447b;
        }

        input[type="checkbox"] {
            transform: scale(1.3);
            margin-right: 8px;
            margin-left: 2px;
        }

        /* --- BUTTONS --- */
        .btn-primary {
            background-color: #00447b;
            color: white;
            padding: 12px 24px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 8px;
            border: none;
            width: 100%;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 10px;
        }

        .btn-primary:hover {
            background-color: #003366;
            transform: translateY(-2px);
        }

        /* --- TABLE STYLING --- */
        .casa-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            margin-top: 10px;
        }

        .casa-table th {
            background-color: #00447b;
            color: white;
            padding: 12px;
            text-align: left;
        }

        .casa-table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .casa-table a {
            color: #00447b;
            font-weight: bold;
            text-decoration: none;
        }
        .casa-table a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
  <?php require_once('header.php') ?>

	<h1 class="page-title">Report Generation</h1>

    <div class="content-card">
		<h3 class="mb-3">Report Generator</h3>

		<form id="emailForm" action="reportGeneration/formHandler.php" method="post">

			<div class="mb-3">
				<label><b>Name of Report</b></label>
				<input type="text" id="reportName" name="reportName">
			</div>

			<div class="mb-3">
				<label><b>Quick Report</b></label>
				<div><input type="checkbox" name="checkAll" value="True"> Select All Options</div>
				<div style="margin-top:5px;"><input type="checkbox" name="allData" value="True"> Full Date Range</div>
			</div>

			<div class="mb-3">
				<label><b>Starting Date For Report</b></label>
				<input type="datetime-local" id="startDate" name="startDate">
			</div>

			<div class="mb-3">
				<label><b>Ending Date For Report</b></label>
				<input type="datetime-local" id="endDate" name="endDate">
			</div>

			<div class="mb-3">
				<label><b>Donation Statistics</b></label>
				<div><input type="checkbox" name="DonationsStatsOverview" value="True"> Include Donations Statistics Overview</div>
				<div style="margin-top:5px;"><input type="checkbox" name="GrowthAndTrends" value="True"> Include Growth/Trend Analysis</div>
			</div>

			<div class="mb-3">
				<label><b>Fundraiser Performance</b></label>
				<div><input type="checkbox" name="IncludeGraph" value="True"> Include Graph</div>
				<div style="margin-top:5px;"><input type="checkbox" name="IncludeTable" value="True"> Include Table</div>
			</div>

			<div class="mb-3">
				<label><b>Donor Insights</b></label>
				 <div style="margin-bottom:10px;">Number of Top Donors To Display <input type="number" name="NumTopDonors" min="0" max="100" step="1" value="10" style="width:80px; display:inline-block; padding:5px;"></div>
				<input type="checkbox" name="IncludeNewDonors" value="True"> Include New Donor Information
			</div>

			<div class="mb-3">
				<label><b>Visual Analysis</b></label>
				<div><input type="checkbox" name="IncludePareto" value="True"> Include Pareto Chart</div>
				<div style="margin-top:5px;"><input type="checkbox" name="IncludeFunnel" value="True"> Include Donor Funnel Chart</div>
				<div style="margin-top:5px;"><input type="checkbox" name="IncludeDonationsByState" value="True"> Include Top Donations By State</div>
				<div style="margin-top:5px;"><input type="checkbox" name="IncludeDonationsByZip" value="True"> Include Top Donations By Zip</div>
			</div>

			<div class="mb-3">
				<label><b>Summary Section</b></label>
				<input type="checkbox" name="IncludeSummarySection" value="True"> Include Summary Paragraph
			</div>

			<div class="mb-3">
				<label><b>Graph Descriptions</b></label>
				<input type="checkbox" name="graphDesc" value="True"> Include Graph Descriptions
			</div>


			<input type="hidden" id="os" name="os" value="<?php echo$os; ?>">
			<button type="submit" class="btn btn-primary">Generate Report</button>

		</form>
	</div>

    <div class="content-card">
        <h3>Report History</h3>
		<form action="./reportGeneration/reports/delHandler.php" method="post">
		<table class="casa-table">
			<thead>
                <tr>
                    <th>Report History</th>
                    <th style="width: 150px; text-align: center;">Delete Report</th>
                </tr>
            </thead>
            <tbody>
			<?php
				foreach ($output as $value){
					if (str_contains($value,".pdf")){
						echo "<tr>";
						echo "<td><a target='_blank' href=./reportGeneration/reports/".$value.">".$value."</a></td>";
						echo "<td style='text-align: center;'><input type='checkbox' id =".$value." name=".$value."></td>";
						echo "</tr>";
					flush();
					ob_flush();
					}
				}
			?>
            </tbody>
		</table>
		<br/>
		<button type="submit" class="btn btn-primary">Submit Changes</button>
		</form>
	</div>

    <?php require_once('footer.php'); ?>
</body>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const checkAll = document.querySelector('input[name="checkAll"]');
    const fullDateRange = document.querySelector('input[name="allData"]');

    const allOtherCheckboxes = document.querySelectorAll(
        'input[type="checkbox"]:not([name="checkAll"]):not([name="allData"])'
    );

    const startDate = document.getElementById("startDate");
    const endDate = document.getElementById("endDate");

    // --- Helpers ---
    function setDisabled(elements, disabled) {
        elements.forEach(el => {
            el.disabled = disabled;
            el.style.opacity = disabled ? "0.5" : "1";
        });
    }

    function setFieldDisabled(field, disabled) {
        field.disabled = disabled;
        field.style.opacity = disabled ? "0.5" : "1";
    }

    // --- When "Select All Options" toggles ---
    checkAll.addEventListener("change", () => {
        const disabled = checkAll.checked;

        // Disable ALL other checkboxes except the date ones
        setDisabled(allOtherCheckboxes, disabled);

        // IMPORTANT: Do NOT grey out date fields
        setFieldDisabled(startDate, fullDateRange.checked);
        setFieldDisabled(endDate, fullDateRange.checked);
    });

    // --- When "Full Date Range" toggles ---
    fullDateRange.addEventListener("change", () => {
        const disabled = fullDateRange.checked;

        setFieldDisabled(startDate, disabled);
        setFieldDisabled(endDate, disabled);
    });
});
</script>

</html>