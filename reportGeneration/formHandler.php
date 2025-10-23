<?php
echo "Attempting to build the pdf\n";
#build out the command using options from the form
$pyPath = "./reportGen.py";
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
