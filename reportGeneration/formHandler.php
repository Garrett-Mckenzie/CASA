<?php
#build out the command using options from the form
$pyPath = "./reportGen.py";

foreach ($_POST as $arg){
	echo $arg;
}

/*
$command = "python ".$pyPath." ".implode("",$args)	
		echo $command;

#Execute reportGen.py with options
$output = null;	
$output = exec($command);
echo $output;
*/
?>
