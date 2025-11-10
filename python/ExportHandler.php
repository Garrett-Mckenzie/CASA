<?php
$python_script = 'dbManager.py';
$output = [];
$return_var = 0;
$temp = "";
$export_file = null;
if(is_dir("exports") === false){
				mkdir("exports");
}
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)){
				$req = $_POST['export'];
				if($req === 'donor'){
								$query = "SELECT * FROM donors";
								$export_file = "exports" . DIRECTORY_SEPARATOR . "donorExport.xlsx";
				}
				elseif($req === 'donation'){
								$query = "SELECT * FROM donations";
								$export_file = "exports" . DIRECTORY_SEPARATOR . "donationExport.xlsx";
				}
				else{
								header("Location: ../export.php?success=0");
				}

				if(file_exists($export_file)){
								if($_POST['overwrite'] !== 'true'){
												header("Location: ../export.php?success=0&error=file_exists");
												exit;
								}
								unlink($export_file);
				}
				if($export_file != null) { 
								exec("python -u \"$python_script\" -e \"$query\" \"$export_file\"", $output, $return_var);
				}

				else {
								header("Location: ../export.php?success=0&error=no_file&post=" . urldecode(serialize($_POST))); 
								exit; 
				}
				header("Location: ../export.php?success=1&file=$export_file");
				exit;
}

?>
