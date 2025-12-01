<?php
echo "
<!DOCTYPE html>
<html lang='en'>
<head>
	<meta charset='UTF-8'>
	<title>Report Generator</title>
	<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css'>
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

<body class='p-4 bg-light'>
	<div class = 'loader'></div>
	<div class = 'loading-text'><b>Removing Old Reports One Moment</b></div> 	
</body>
</html>";

#flush the page to display the stuff while we wait for the py
ob_flush();
flush();


#get arguments
$args = [];
foreach ($_POST as $key=>$value){
	$len = strlen($key);
	$key[$len-4] = ".";
	array_push($args,$key);
}

#get os
$os = NULL;
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'){
				$os = "w";
}
else{
				$os = "l";
}

if ($os == "l"){
	foreach ($args as $file){
					exec("rm ".$file);
					echo $file;
	}
}

else{
	foreach ($args as $file){
		exec("del ".$file);
	}
}

echo '<script>window.location.href="../../ReportGeneration.php";</script>';
exit();
?>
