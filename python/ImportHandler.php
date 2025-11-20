<?php
echo "
<!DOCTYPE html>
<html lang='en'>
<head>
<meta charset='UTF-8'>
<title>Export Handler</title>
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
</style>                                                                                         <body class='p-4 bg-light'>
<div class = 'loader'></div>
<div class = 'loading-text'>
<b>Importing The Data One Moment</b></div>
</body>
</html>";
ob_flush();
flush();
$python_script = 'dbManager.py';
$return_var = 0;

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['files'])){
				$fileset = $_FILES['files'];
				foreach($fileset['name'] as $index => $name){
								if($fileset['error'][$index] === UPLOAD_ERR_OK){
												// Fetch from temp directory
												$temp = sys_get_temp_dir() . DIRECTORY_SEPARATOR . basename($name);
												if(move_uploaded_file($fileset['tmp_name'][$index], $temp)){
																exec("python -u \"$python_script\" -i \"$temp\" 2>&1", $output, $return_var);
																session_start();
																$_SESSION["importStatus"] = $output;
																if($return_var === 0){
																				header("Location: ../import.php?success=1&file=$name");
																				
																} else{
																				header("Location: ../import.php?success=1&file=$name");
																}
																unlink($temp);
												}
												else {
																header("Location: ../import.php?success=0");
												}
								} else {
												header("Location: ../import.php?success=0");
								}
				}
} else {
				header("Location: ../import.php?success=0");
}
?>
