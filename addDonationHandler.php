<?php

include_once '../database/dbinfo.php';

var_dump($_POST);
//Read in donation info
$firstName = $_POST["firstName"];
$lastName = $_POST["lastName"];
$date = $_POST["date"];
$amount = $_POST["amount"];
$fee = $_POST["fee"];
$eventName = $_POST["eventName"];
$reason = $_POST["reason"];

$con = connect();


?>
