<?php
function create_donor($donor) {
    $connection = connect();
    $first = $donor["first"];
    $last = $donor["last"];
    $email = $donor["email"];
    $zip = $donor["zip"];
    $city = $donor["city"];    
    $state = $donor["state"];
    $street = $donor["street"];
    $phone = $donor['phone'];
    $notes = $donor['notes'];
    
    $completed = 0;
    $query = "
        insert into donors (first,last, email,zip, city, state, street, phone, notes)
        values ('$first','$last', '$email','$zip', '$city', '$state',  '$street', '$phone', '$notes')
    ";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        return null;
    }
    $id = mysqli_insert_id($connection);
    //add_services_to_event($id, $services);
    mysqli_commit($connection);
    mysqli_close($connection);
    return $id;
}


?>