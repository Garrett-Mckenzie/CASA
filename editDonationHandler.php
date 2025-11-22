<?php

include_once './database/dbinfo.php';
session_start();

$con = connect();

#HANDLE SEARCHING DONATIONS
if ($_POST["goal"] == "search"){

	#read post
	$first = $_POST["firstName"];
	$last = $_POST["lastName"];
	$email = $_POST["email"];
	$date = $_POST["date"];
	$max = $_POST["maxAmount"];
	$min =$_POST["minAmount"];

	#extract date
	if ($date != ""){
		$year = substr($date,0,4);
		$month = substr($date,5,2);
		$day = substr($date,8,2);
		$date = $month."/".$day."/".$year;
	}
	else{
		$date = NULL;
	}

	#get donor
	$donorID = NULL;
	if ($first != "" and $last != "" and $email !=""){
		$query = "SELECT id FROM donors WHERE LOWER(first)='".strtolower($first)."' AND LOWER(last)='".strtolower($last)."' AND LOWER(email)='".strtolower($email)."';";	
		$result = mysqli_query($con,$query);
		if (mysqli_num_rows($result) == 0){
			$_SESSION["searchComplete"] = "f";
			$_SESSION["reason"] = "No donor with information of first name= ".$first.", last name= ".$last.", and email= ".$email." was found in the storage system.";
			header("Location: donationAddEdit.php?searchAttempt=true");
			exit();
		}
		else{
			$donorID = $result->fetch_all()[0][0]; 	
		}

	}
	else if (($first == "" and ($last != "" or $email != "")) or ($last == "" and ($first != "" or $email != "")) or ($email == "" and ($first != "" or $last != ""))){
		$_SESSION["searchComplete"] = "f";
		$_SESSION["reason"] = "If any of first name, last name, or email are specified, then all fields of first name, last name, and email must be complete.";
		header("Location: donationAddEdit.php?searchAttempt=true");
		exit();
	}
	else{
		$donorID = NULL;
	}

	#build query

	$query = "SELECT donations.amount,donations.reason,donations.date,donations.fee,donations.thanked,donors.first,donors.last,donors.email,donors.zip,donors.city,donations.id FROM donations JOIN donors ON donations.donorID = donors.id WHERE";
	$selectAll = true;

	if  (isset($donorID)){
		$query = $query." donations.donorID='".$donorID."'";
		$selectAll = false;
		if (isset($date) or $max != "0" or $min != "0"){
			$query = $query." AND";
		}
	}
	if (isset($date)){
		$query = $query." donations.date='".$date."'";
		$selectAll = false;
		if ($max != "0" or $min != "0"){
			$query = $query." AND";
		}
	}
	if ($max != "0"){
		$query = $query." donations.amount <= ".$max;
		$selectAll = false;
		if ($min != "0"){
			$query = $query." AND";
		}
	}
	if ($min != "0"){
		$query = $query." donations.amount >=".$min;
		$selectAll = false;
	}

	if ($selectAll){
		$query= "SELECT donations.amount,donations.reason,donations.date,donations.fee,donations.thanked,donors.first,donors.last,donors.email,donors.zip,donors.city,donations.id FROM donations JOIN donors ON donations.donorID = donors.id;";
	}
	else{
		$query = $query.";";
	}


	try{
		$result = mysqli_query($con,$query);
		if (mysqli_num_rows($result) == 0){
			throw new Exception("!!!No Search Results Found!!!");
		}
		$_SESSION["searchComplete"] = "t";
		$_SESSION["reason"] = $result->fetch_all();
		header("Location: donationAddEdit.php?searchAttempt=true");
		exit();
	}
	catch (Exception $e){
		$_SESSION["searchComplete"] = "f";
		$_SESSION["reason"] = $e->getMessage();
		header("Location: donationAddEdit.php?searchAttempt=true");
		exit();
	}

}

#HANDLE EDITING DONATIONS
else{
	echo "hello world";
}

?>

