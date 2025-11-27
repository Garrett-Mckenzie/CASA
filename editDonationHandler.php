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

				$query = "SELECT donations.amount,donations.reason,donations.date,donations.fee,donations.thanked,donors.first,donors.last,donors.email,donors.zip,donors.city,donations.id,donors.id FROM donations JOIN donors ON donations.donorID = donors.id WHERE";
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

				var_dump($_POST);

				#handle donor
				$first = $_POST["first"];
				$last = $_POST["last"];
				$email = $_POST["email"];
				$donorID = "";

				$query = "SELECT id,first,last,email FROM donors WHERE LOWER(first) = '".strtolower($first)."' AND LOWER(last) = '".strtolower($last)."' AND LOWER(email) = '".strtolower($email)."';";

				$result = mysqli_query($con,$query);
				if (mysqli_num_rows($result) == 0){
								$_SESSION["editComplete"] = "f";
								$_SESSION["reason"] = "Donor with info ".$first.", ".$lower.", and ".$email."was not found in our system. You may need to add them first.";
								header("Location: donationAddEdit.php?editAttempt=true");
								exit();
				}
				else{
								$donorID = $result->fetch_all()[0][0][0];
				}

				#handle date
				$date = $_POST["date"];
				if ($date != ""){
								try{
												$datetime = DateTime::createFromFormat('m#d#Y',$date);
												if (!$datetime){
																throw Exception("bad format");
												}
												$day = (string)$datetime->format('d');
												$month = (string)$datetime->format('m');
												$year = (string)$datetime->format('y');
												$date = $month."/".$day."/".$year;
								}
								catch (Exception $e){
												$_SESSION["editComplete"] = "f";
												$_SESSION["reason"] = "Date of ".$date."is invalid.";
												header("Location: donationAddEdit.php?editAttempt=true");
												exit();
								}
				}

				#edit
				#note that there is not logic here, everytime there is an edit
				#that information gets put into the database as long as there are no
				#flaws in the edit request.

				$id = $_POST["donationID"];
				$reason = $_POST["reason"];
				$fee = $_POST["fee"];
				$thanked = $_POST["thanked"];
				$amount = $_POST["amount"];

				$query = "UPDATE donations SET amount = '".$amount."', reason = '".$reason."', date = '".$date."', fee = '".$fee."', thanked = '".$thanked."', donorID = '".$donorID."' WHERE id = '".$id."';";
				echo $query;
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
				$_SESSION["reason"] = "The donation now reflects the below information:</br>Amount: ".$amount."</br>Reason: ".$reason."</br>Fee: ".$fee."</br>Thanked: ".$thanked."</br>Amount: ".$amount."</br>Donor Name: ".$first." ".$last."</br>Donor Email: ".$email;
				header("Location: donationAddEdit.php?editAttempt=true");
				exit();
}
else{
				header("Location: ./index.php");
}
?>

