<?php
include_once('dbinfo.php');

//get donations for a specific event id
function fetch_donations_for_event($id) {
    $con=connect();
    $query = "SELECT * FROM donations WHERE eventID = '" . $id . "'";
    $result = mysqli_query($con,$query);
    if (!$result) {
        mysqli_close($con);
        return null;
    }
    $donations = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($con);
    return $donations;
}

?>