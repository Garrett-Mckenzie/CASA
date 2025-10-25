<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Report Generator</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<style>
	.loader {
		border: 8px solid #f3f3f3; /* Light grey */
		border-top: 8px solid #3498db; /* Blue */
		border-radius: 50%;
		width: 50px;
		height: 50px;
		animation: spin 1s linear infinite;
		margin: 100px auto;
	}
	@keyframes spin {
		0% { transform: rotate(0deg); }
		100% { transform: rotate(360deg); }
	}

	.loading-text {
		text-align: center;
		font-family: Arial, sans-serif;
		margin-top: 10px;
	}
</style>

<body class="p-4 bg-light">
	<div class = "loader"></div>
	<div class = "loading-text"><b>Working On The Report One Moment</b></div> 	
</body>
</html>

<?php
#build out the command using options from the form
$pyPath = "./makeReport.py";
$args = array();
foreach ($_POST as $key => $value){
	if ($value != NULL){
		array_push($args,$key.":".$value);
	}
}
$command = "python ".$pyPath." ".implode(" ",$args);	
#Execute reportGen.py with options
$output = null;	
$output = exec($command);
echo $output;
?>
