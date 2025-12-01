<?php

include_once 'database/dbinfo.php';

session_start();

//Read in donation info
$first = trim($_POST["firstName"]);
$last = trim($_POST["lastName"]);
$email = trim($_POST["donorEmail"]);
$date = $_POST["date"];
$amount = $_POST["amount"];
$fee = $_POST["fee"];
$event = $_POST["eventName"];
$reason = trim($_POST["reason"]);
$thanked = "0";

if (array_key_exists("thanked",$_POST)){
	$thanked = "1";
}


#attempt insertion
$_SESSION["addComplete"]  = "t";

#validate the date (dont think this is needed but better safe)
try{
	new DateTime($date);
}
catch (Exception $e){
	$_SESSION["addComplete"] = "f";
	$_SESSION["reason"] = "Date: ".$date." is invalid.";
	header("Location: donationAddEdit.php?addAttempt=true");
	exit();
}


#connect
$con = NULL;
try{
	$con = connect();
}
catch (Exception $e){
	$_SESSION["addComplete"] = "f";
	$_SESSION["reason"] = "Connection to storage failed";
	header("Location: donationAddEdit.php?addAttempt=true");
	exit();
}

#get donor id
$donorID = NULL;
if ($first != "" and $last != "" and $email !=""){
	$query = "SELECT id FROM donors WHERE LOWER(first)='".strtolower($first)."' AND LOWER(last)='".strtolower($last)."' AND LOWER(email)='".strtolower($email)."';";	
	$result = mysqli_query($con,$query);
	if (mysqli_num_rows($result) == 0){
		$_SESSION["addComplete"] = "f";
		$_SESSION["reason"] = "No donor with information of first name= ".$first.", last name= ".$last.", and email= ".$email." was found in the storage system.";
		header("Location: donationAddEdit.php?addAttempt=true");
		exit();
	}
	else{
		$donorID = $result->fetch_all()[0][0]; 	
	}

}
else if (($first == "" and ($last != "" or $email != "")) or ($last == "" and ($first != "" or $email != "")) or ($email == "" and ($first != "" or $last != ""))){

	$_SESSION["addComplete"] = "f";
	$_SESSION["reason"] = "If any of first name, last name, or email are specified, then all fields of first name, last name, and email must be complete.";
	header("Location: donationAddEdit.php?addAttempt=true");
	exit();
}
else{
	$donorID = 999;
}

#get event id
$eventID = NULL;
if ($event != "none"){
	$query="SELECT id FROM dbevents WHERE LOWER(name)='".strtolower($event)."';";	
	$result = mysqli_query($con,$query);
	$eventID = $result->fetch_all()[0][0]; 
}

#parse date
if ($date != ""){
	$year = substr($date,0,4);
	$month = substr($date,5,2);
	$day = substr($date,8,2);
	$date = $month."/".$day."/".$year;
}
else{
	$date = NULL;
}

if ($reason == ""){
	$reason = NULL;
}

#insert new donation into database
$query = "INSERT INTO donations (amount,reason,date,fee,thanked,eventID,donorID) VALUES (?,?,?,?,?,?,?)";
$data = [$amount,$reason,$date,$fee,$thanked,$eventID,$donorID];
try{
	$exec = $con->prepare($query);
	$exec->execute($data);
}
catch (Exception $e){
	$_SESSION["addComplete"] = "f";
	$_SESSION["reason"] = $e->getMessage();
	header("Location: donationAddEdit.php?addAttempt=true");
	exit();
}

$_SESSION["reason"] = "The new donation information has been added to the storage system! Below are the details of the donation.</br>Amount: ".$amount."</br>Reason: ".$reason."</br>Date: ".$date."</br>Fees: ".$fee."</br>Thanked: ".$thanked."</br>Donor Name: ".$first." ".$last."</br>Donor Email: ".$email;
header("Location: donationAddEdit.php?addAttempt=true");
exit();
?>

