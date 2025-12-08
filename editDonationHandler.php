<?php
#HANDLE SEARCHING DONATIONS
if (isset($_POST["goal"]) and $_POST["goal"] == "search"){

				include_once './database/dbinfo.php';
				session_start();

				$con = connect();

				#read post
				$first = $_POST["firstName"];
				$last = $_POST["lastName"];
				$email = $_POST["email"];
				$date = $_POST["date"];
				$max = $_POST["maxAmount"];
				$min =$_POST["minAmount"];

				#extract date
				$date = trim($date);
				if ($date != ""){
								$year = substr($date,0,4);
								$month = substr($date,5,2);
								$day = substr($date,8,2);
								$date = $month."/".$day."/".$year;
				}
				else{
								$date = NULL;
				}
				
				#build query
				$query = "SELECT donations.amount,donations.reason,donations.date,donations.fee,donations.thanked,donors.first,donors.last,donors.email,donors.zip,donors.city,donations.id,donors.id FROM donations JOIN donors ON donations.donorID = donors.id WHERE";
				$selectAll = true;

				if  ($first != ""){
								$query = $query." LOWER(donors.first)='".strtolower($first)."'";
								$selectAll = false;
								if (isset($date) or $max != "0" or $min != "0" or $last != "" or $email != ""){
												$query = $query." AND";
								}
				}

				if ($last != ""){
								$query = $query." LOWER(donors.last)='".strtolower($last)."'";
								$selectAll = false;
								if (isset($date) or $max != "0" or $min != "0" or $email != ""){
												$query = $query." AND";
								}
				}

				if ($email != ""){
								$query = $query." LOWER(donors.email)='".strtolower($email)."'";
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
								$query= "SELECT donations.amount,donations.reason,donations.date,donations.fee,donations.thanked,donors.first,donors.last,donors.email,donors.zip,donors.city,donations.id,donors.id FROM donations JOIN donors ON donations.donorID = donors.id;";
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
else if  (isset($_POST["goal"]) and $_POST["goal"] == "edit"){
				session_start();
				include_once './database/dbinfo.php';
				$con = connect();

				#handle donor
				$first = $_POST["first"];
				$last = $_POST["last"];
				$email = $_POST["email"];
				$donorID = "";

				$query = "SELECT id,first,last,email FROM donors WHERE LOWER(first) = '".strtolower($first)."' AND LOWER(last) = '".strtolower($last)."' AND LOWER(email) = '".strtolower($email)."';";

				$result = mysqli_query($con,$query);
				if (mysqli_num_rows($result) == 0){
								$_SESSION["editComplete"] = "f";
								$_SESSION["reason"] = "Donor with info ".$first.", ".$lower.", and ".$email."was not found in our system. You may need to <a href='addDonor.php'>add them </a>first.";
								header("Location: donationAddEdit.php?editAttempt=true");
								exit();
				}
				else if (mysqli_num_rows($result) > 1){
								$_SESSION["editComplete"] = "f";
								$_SESSION["reason"] = "Duplicate Donor detected. There are problems in our database contact someone technical";
								header("Location: donationAddEdit.php?editAttempt=true");
								exit();

				}
				else{
								$donorID = ($result->fetch_all())[0][0];
				}

				#handle date
				$date = trim($_POST["date"]);
				if ($date != ""){
								try{
												$datetime = DateTime::createFromFormat('m#d#Y',$date);
												if (!$datetime){
																throw new Exception("bad format");
												}
												$day = (string)$datetime->format('d');
												$month = (string)$datetime->format('m');
												$year = (string)$datetime->format('Y');
												$numYear = (int)$year;
												if ($numYear < 1900){
																throw new Exception("bad format");
												}
												$date = $month."/".$day."/".$year;
								}
								catch (Exception $e){
												$_SESSION["editComplete"] = "f";
												$_SESSION["reason"] = "Date of ".$date." is invalid.";
												header("Location: donationAddEdit.php?editAttempt=true");
												exit();
								}
				}

				#edit
				#note that there is no logic here, everytime there is an edit
				#that information gets put into the database as long as there are no
				#flaws in the edit request.

				$id = $_POST["donationID"];
				$reason = $_POST["reason"];
				$fee = $_POST["fee"];
				$thanked = $_POST["thanked"];
				$amount = $_POST["amount"];

				$query = "UPDATE donations SET amount = '".$amount."', reason = '".$reason."', date = '".$date."', fee = '".$fee."', thanked = '".$thanked."', donorID = '".$donorID."' WHERE id = '".$id."';";
				try{
								mysqli_query($con,$query);
				}
				catch (Exception $e){
								$_SESSION["editComplete"] = "f";
								$_SESSION["reason"] = "Date of ".$date."is invalid.";
								header("Location: donationAddEdit.php?editAttempt=true");
								exit();

				}
				$_SESSION["editComplete"] = "t";
				$_SESSION["reason"] = "The donation now reflects the below information:</br>Amount: ".$amount."</br>Reason: ".$reason."</br>Fee: ".$fee."</br>Thanked: ".$thanked."</br>Amount: ".$amount."</br>Donor Name: ".$first." ".$last."</br>Donor Email: ".$email."</br>Date: ".$date;
				header("Location: donationAddEdit.php?editAttempt=true");
				exit();
}
else{
				header("Location: ./index.php");
}
?>

