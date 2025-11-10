<?php
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
																				echo "Imported $name<br>";
																				header("Location: ../import.php?success=1&file=$name");
																} else{
																				echo "Error importing ".$name."<br/>";
																}
																unlink($temp);
												}
												else {
																echo "Failed to fetch $name<br>";
																header("Location: ../import.php?success=0");
												}
								} else {
												echo "Failed to upload $name<br>";
												header("Location: ../import.php?success=0");
								}
				}
} else {
				echo "Invalid Request<br>";
				echo "Expected POST, Recieved " . $_SERVER['REQUEST_METHOD'] . "<br>";
				echo "Recieved" . $_FILES['files'] . "<br>";
				header("Location: ../import.php?success=0");
}
?>
