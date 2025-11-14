<?php
    require_once('auth/config.php');
    $db = new mysqli(host, USER, PASSWORD, DB);
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['filter'])){
        $status = $_POST['filter'];
        $query = "SELECT name, goalAmount, startDate, endDate, startTime, endTime, description, completed, location FROM events";
        
    }
?>