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
				}
				elseif($req === 'donation'){
								$query = "SELECT * FROM donations";
				}
				else{
								header("Location: ../export.php?success=0");
				}

				#fuck siteground useless fucking ass shit hosting service
				$count = 25;
				$randomVar = "Export";
				while ($count > 0){
								$num = rand(0,9);
								$randomVar = $randomVar . strval($num);
								$count -= 1;
				}

				$export_file = "exports" . DIRECTORY_SEPARATOR . $randomVar . ".xlsx";

				if($export_file != null) { 
								exec("python -u \"$python_script\" -e \"$query\" \"$export_file\"", $output, $return_var);
				}

				else {
								header("Location: ../export.php?success=0&error=no_file&post=" . urldecode(serialize($_POST))); 
								exit; 
				}
				header("Location: ../export.php?success=1&file=python/$export_file");
				exit;
}

?>
