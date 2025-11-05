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

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Report Generator</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body class="p-4 bg-light">
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
				<label><b>Donation Analysis Statistical Complexity</b></label><br/>
				<input type="radio" name="QuantStatSize" value="all"> All Statistics</input><br/>
				<input type="radio" name="QuantStatSize" value="simple"> Simplified Statistics</input><br/>
				<input type="checkbox" name="QuantStatDesc" value="True"> Include Meaning Of Statistics as a Description</input><br/>	

			<!-- Put Other Parts Of The Form In These Divs -->
			</div>
			<div class="mb-3">
			</div>
			<div class="mb-3">
			</div>
			<div class="mb-3">
			</div>
			<div class="mb-3">
			</div>
			<div class="mb-3">
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
					}
				}
			?>
		</table>
		<br/>
		<button type="submit" class="btn btn-primary">Submit Changes</button>
		</form>
	</div>
</body> 
