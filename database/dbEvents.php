<?php
include_once('dbinfo.php');
include_once(dirname(__FILE__).'/../domain/Event.php');

/*
 * add an event to dbEvents table: if already there, return false
 */

function add_event($event) {
    // if (!$event instanceof Event)
    //     die("Error: add_event type mismatch");
    $con=connect();
    $query = "SELECT * FROM dbevents WHERE id = '" . $event->getID() . "'";
    $result = mysqli_query($con,$query);
    //if there's no entry for this id, add it
    if ($result == null || mysqli_num_rows($result) == 0) {
        mysqli_query($con,'INSERT INTO dbevents VALUES("' .
                $event->getID() . '","' .
                $event->getDate() . '","' .
                $event->getStartTime() . "," .
                #$event->get_venue() . '","' .
                $event->getName() . '","' . 
                $event->getDescription() . '","' .
                $event->getCapacity() . "," .
                $event->getCompleted() . "," .
                $event->getRestrictedSignup() . "," .
                #$event->getID() .            
                '");');							
        mysqli_close($con);
        return true;
    }
    mysqli_close($con);
    return false;
}

/*function fetch_event_name_by_id($id) {
    $connection = connect();
    $id = mysqli_real_escape_string($connection, $id);
    $query = "select name from dbevents where id = '$id'";
    $result = mysqli_query($connection, $query);
    $event = mysqli_fetch_assoc($result);
    if ($event) {
        require_once('include/output.php');
        $event = hsc($event);
        mysqli_close($connection);
        return $event;
    }
    mysqli_close($connection);
    return null;
}*/

function request_event_signup($eventID, $account_name, $role, $notes) {
    $connection = connect();
    $query1 = "SELECT id FROM dbevents WHERE name LIKE '$eventID'";
    $result1 = mysqli_query($connection, $query1);
    $row = mysqli_fetch_assoc($result1);
    $value = $row['id'];
   
    $query2 = "SELECT userID FROM dbeventpersons WHERE eventID LIKE '$value' AND userID LIKE '$account_name'";
    $result2 = mysqli_query($connection, $query2);

    $query3 = "SELECT username FROM dbpendingsignups WHERE eventname LIKE '$value' AND username LIKE '$account_name'";
    $result3 = mysqli_query($connection, $query3);

    $row2 = null;
    $row2 = mysqli_fetch_assoc($result2);
    $row3 = null;
    $row3 = mysqli_fetch_assoc($result3);

    if(!is_null($row2) || !is_null($row3)) {
            $value2 = $row2['userID'];
            $value3 = $row3['username'];
            if($value2 == $account_name || $value3 == $account_name){
                return null;
        } 
    } else {       
            $query = "insert into dbpendingsignups (username, eventname, role, notes) values ('$account_name', '$value', '$role', '$notes')";
            $result = mysqli_query($connection, $query);
            mysqli_commit($connection);
            return $value;
        }
    return $value;
}


function all_pending_ids() {
    $connection = connect();
    $query = "SELECT eventname FROM dbpendingsignups";
    $result = mysqli_query($connection, $query);

    if (!$result) {
        die('Query failed: ' . mysqli_error($connection));
    }

    $signups = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $signups[] = $row;
    }

    mysqli_close($connection);
    return $signups;
}

function remove_user_from_event($event_id, $user_id) {    
    $query = "DELETE FROM dbeventpersons WHERE eventID LIKE '$event_id' AND userID LIKE '$user_id'";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    $result = boolval($result);
    mysqli_close($connection);
    return $result;
}

function remove_user_from_pending_event($event_id, $user_id) {    
    $query = "DELETE FROM dbpendingsignups WHERE eventname = '$event_id' AND username = '$user_id'";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    $result = boolval($result);
    mysqli_close($connection);
    return $result;
}

/* @@@ Thomas's work! */
/*
 * Returns true if the given event is archived.
 */
function is_archived($id) {
    // look-up 'completed' in the event's DB entry
    $connection = connect();
    $query1 = "SELECT completed FROM dbevents WHERE id = '$id'";
    $result1 = mysqli_query($connection, $query1);
    $row = mysqli_fetch_assoc($result1);
    mysqli_close($connection);

    if ($row == NULL) return False; // no match for that event ID

    if ($row['completed'] == 'yes') {
        // event is archived
        return True;
    } else {
        return False;
    }
}

/*
 * Mark an event as archived in the DB by setting the 'completed' column to 'yes'.
 */
function archive_event($id) {
    $con=connect();
    $query = "UPDATE dbevents SET completed = 'yes' WHERE id = '" .$id. "'";
    $result = mysqli_query($con, $query);
    mysqli_close($con);
    return $result;
}

/*
 * Mark an event as not archived in the DB by setting the 'completed' column to 'no'.
 */
function unarchive_event($id) {
    $con=connect();
    $query = "UPDATE dbevents SET completed = 'no' WHERE id = '" .$id. "'";
    $result = mysqli_query($con,$query);
    mysqli_close($con);
    return $result;
}

/* end of Thomas's work*/

/**/

/*
 * remove an event from dbEvents table.  If already there, return false
 */

function remove_event($id) {
    $con=connect();
    $query = 'SELECT * FROM dbevents WHERE id = "' . $id . '"';
    $result = mysqli_query($con,$query);
    if ($result == null || mysqli_num_rows($result) == 0) {
        mysqli_close($con);
        return false;
    }
    $query = 'DELETE FROM dbevents WHERE id = "' . $id . '"';
    $result = mysqli_query($con,$query);
    mysqli_close($con);
    return true;
}


/*
 * @return an Event from dbEvents table matching a particular id.
 * if not in table, return false
 */

function retrieve_event($id) {
    $con=connect();
    $query = "SELECT * FROM dbevents WHERE id = '" . $id . "'";
    $result = mysqli_query($con,$query);
    if (mysqli_num_rows($result) !== 1) {
        mysqli_close($con);
        return false;
    }
    $result_row = mysqli_fetch_assoc($result);
    // var_dump($result_row);
    $theEvent = make_an_event($result_row);
//    mysqli_close($con);
    return $theEvent;
}

function retrieve_event2($id) {
    $con=connect();
    $query = "SELECT * FROM dbevents WHERE id = '" . $id . "'";
    $result = mysqli_query($con,$query);
    if (mysqli_num_rows($result) !== 1) {
        mysqli_close($con);
        return false;
    }
    $result_row = mysqli_fetch_assoc($result);
//    var_dump($result_row);
    return $result_row;
}

// not in use, may be useful for future iterations in changing how events are edited (i.e. change the remove and create new event process)
function update_event_date($id, $new_event_date) {
	$con=connect();
	$query = 'UPDATE dbevents SET event_date = "' . $new_event_date . '" WHERE id = "' . $id . '"';
	$result = mysqli_query($con,$query);
	mysqli_close($con);
	return $result;
}

function make_an_event($result_row) {
	/*
	 ($en, $v, $sd, $description, $ev))
	 */
    $theEvent = new Event(
                    $result_row['id'],
                    $result_row['name'],
                    goalAmount: $result_row['goalAmount'],                   
                    startDate: $result_row['startDate'],
                    endDate: $result_row['endDate'],
                    startTime: $result_row['startTime'],
                    endTime: $result_row['endTime'],
                    description: $result_row['description'],
                    location: $result_row['location'],
                    completed: $result_row['completed']
                ); 
    return $theEvent;
}

function get_all_events() {
    $con=connect();
    $query = "SELECT * FROM dbevents" . 
            " ORDER BY completed";
    $result = mysqli_query($con,$query);
    $theEvents = array();
    while ($result_row = mysqli_fetch_assoc($result)) {
        $theEvent = make_an_event($result_row);
        $theEvents[] = $theEvent;
    }
    mysqli_close($con);
    return $theEvents;
 }
 
 function get_all_events_sorted_by_date_not_archived() {
    $con=connect();
    $query = "SELECT * FROM dbevents" .
            " WHERE completed = 0" .
            " ORDER BY startDate ASC";
    $result = mysqli_query($con,$query);
    $theEvents = array();
    while ($result_row = mysqli_fetch_assoc($result)) {
        $theEvent = make_an_event($result_row);
        $theEvents[] = $theEvent;
    }
    mysqli_close($con);
    return $theEvents;
 }

 function get_all_events_sorted_by_date_and_archived() {
    $con=connect();
    $query = "SELECT * FROM dbevents" .
            " WHERE completed = '1'" .
            " ORDER BY date ASC";
    $result = mysqli_query($con,$query);
    $theEvents = array();
    while ($result_row = mysqli_fetch_assoc($result)) {
        $theEvent = make_an_event($result_row);
        $theEvents[] = $theEvent;
    }
    mysqli_close($con);
    return $theEvents;
 }

// retrieve only those events that match the criteria given in the arguments
function getonlythose_dbEvents($name, $day, $venue) {
   $con=connect();
   $query = "SELECT * FROM dbevents WHERE event_name LIKE '%" . $name . "%'" .
           " AND event_name LIKE '%" . $name . "%'" .
           " AND venue = '" . $venue . "'" . 
           " ORDER BY event_name";
   $result = mysqli_query($con,$query);
   $theEvents = array();
   while ($result_row = mysqli_fetch_assoc($result)) {
       $theEvent = make_an_event($result_row);
       $theEvents[] = $theEvent;
   }
   mysqli_close($con);
   return $theEvents;
}

function fetch_events_in_date_range($start_date, $end_date) {
    $connection = connect();
    $start_date = mysqli_real_escape_string($connection, $start_date);
    $end_date = mysqli_real_escape_string($connection, $end_date);
    $query = "select * from dbevents
              where date >= '$start_date' and date <= '$end_date'
              order by startTime asc";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        mysqli_close($connection);
        return null;
    }
    require_once('include/output.php');
    $events = array();
    while ($result_row = mysqli_fetch_assoc($result)) {
        $key = $result_row['date'];
        if (isset($events[$key])) {
            $events[$key] []= hsc($result_row);
        } else {
            $events[$key] = array(hsc($result_row));
        }
    }
    mysqli_close($connection);
    return $events;
}

function fetch_events_on_date($date) {
    $connection = connect();
    $date = mysqli_real_escape_string($connection, $date);
    $query = "select * from dbevents
              where date = '$date' order by startTime asc";
    $results = mysqli_query($connection, $query);
    if (!$results) {
        mysqli_close($connection);
        return null;
    }
    require_once('include/output.php');
    $events = [];
    foreach ($results as $row) {
        $events []= hsc($row);
    }
    mysqli_close($connection);
    return $events;
}

function fetch_event_by_id($id) {
    $connection = connect();
    $id = mysqli_real_escape_string($connection, $id);
    $query = "select * from dbevents where id = '$id'";
    $result = mysqli_query($connection, $query);
    $event = mysqli_fetch_assoc($result);
    if ($event) {
        require_once('include/output.php');
        $event = hsc($event);
        mysqli_close($connection);
        return $event;
    }
    mysqli_close($connection);
    return null;
}

function create_event($event) {
    $connection = connect();
    $name = $event["name"];
    $goalAmount = $event["goalAmount"];
    $startDate = $event["startDate"];
    $endDate = $event["endDate"];
    $startTime = $event["startTime"];    
    $endTime = $event["endTime"];
    $description = $event["description"];
    $location = $event['location'];
    
    $completed = 0;
    $query = "
        insert into dbevents (name,goalAmount, startdate,endDate, startTime, endTime, description, completed, location)
        values ('$name','$goalAmount', '$startDate','$endDate', '$startTime', '$endTime',  '$description', '$completed', '$location')
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

function add_services_to_event($eventID, $serviceIDs) {
    $connection = connect();
    foreach($serviceIDs as $serviceID) {
        $query = "insert into dbeventsservices (eventID, serviceID) values ('$eventID', '$serviceID')";
        $result = mysqli_query($connection, $query);
        if (!$result) {
            return null;
        }
        $id = mysqli_insert_id($connection);
    }
    mysqli_commit($connection);
    return $id;
}

function update_event($eventID, $eventDetails) {
    $connection = connect();
    $id = $eventDetails["id"];
    $name = $eventDetails["name"];
    #$abbrevName = $eventDetails["abbrev-name"];
    $date = $eventDetails["date"];
    $startTime = $eventDetails["start-time"];
    #$restricted = $eventDetails["restricted"];
    $endTime = $eventDetails["end-time"];
    $description = $eventDetails["description"];
    #$completed = $eventDetails["completed"];
    #$restricted_signup = $eventDetails["restricted_signup"];
    $location = $eventDetails["location"];
    //$services = $eventDetails["service"];
    
    #$completed = $eventDetails["completed"];
    #$query = "
       # update dbEvents set name='$name', abbrevName='$abbrevName', date='$date', startTime='$startTime', restricted='$restricted', description='$description', locationID='$location', completed='$completed'
       # where id='$eventID'
    #";
   # $query = "
    #    update dbevents set id='$id', name='$name', date='$date', startTime='$startTime', endTime='$endTime', description='$description', capacity='$capacity', completed='$completed', event_type='$event_type', restricted_signup='$restricted_signup'
    #    where id='$eventID'
    #";
    $query = "
        update dbevents set id='$id', name='$name', date='$date', startTime='$startTime', endTime='$endTime', description='$description', location='$location'
        where id='$eventID'
    ";
    $result = mysqli_query($connection, $query);
    // update_services_for_event($eventID, $services);
    mysqli_commit($connection);
    mysqli_close($connection);
    return $result;
}


function update_services_for_event($eventID, $serviceIDs) {
    $connection = connect();

    $current_services = get_services($eventID);
    foreach($current_services as $curr_serv) {
        $curr_servIDs[] = $curr_serv['id'];
    }

    // add new services
    foreach($serviceIDs as $serviceID) {
        if (!in_array($serviceID, $curr_servIDs)) {
            $query = "insert into dbeventsservices (eventID, serviceID) values ('$eventID', '$serviceID')";
            $result = mysqli_query($connection, $query);
        }
    }
    // remove old services
    foreach($curr_servIDs as $curr_serv) {
        if (!in_array($curr_serv, $serviceIDs)) {
            $query = "delete from dbeventsservices where serviceID='$curr_serv'";
            $result = mysqli_query($connection, $query);
        }
    }
    mysqli_commit($connection);
    return;
}

function find_event($nameLike) {
    $connection = connect();
    $query = "
        select * from dbevents
        where name like '%$nameLike%'
    ";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        return null;
    }
    $all = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($connection);
    return $all;
}

function fetch_events_in_date_range_as_array($start_date, $end_date) {
    $connection = connect();
    $start_date = mysqli_real_escape_string($connection, $start_date);
    $end_date = mysqli_real_escape_string($connection, $end_date);
    $query = "select * from dbevents
              where date >= '$start_date' and date <= '$end_date'
              order by date, startTime asc";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        mysqli_close($connection);
        return null;
    }
    $events = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($connection);
    return $events;
}

function fetch_all_events() {
    $connection = connect();
    $query = "select * from dbevents
              order by date, startTime asc";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        mysqli_close($connection);
        return null;
    }
    $events = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($connection);
    return $events;
}

function get_description($id) {
    $connection = connect();
    $query = "select description from dbevents
              where id='$id'";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        return [];
    }
    $description = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($connection);
    return $description;
}
  

function get_location($id) {
    $connection = connect();
    $query = "select * from dblocations
              where id='$id'";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        return [];
    }
    $location = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($connection);
    return $location;
}

function get_services($eventID) {
    $connection = connect();
    $query = "select * from dbservices AS serv JOIN dbeventsservices AS es ON es.serviceID = serv.id
              where es.eventID='$eventID'";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        return [];
    }
    $services = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_close($connection);
    return $services;
}

function delete_event($id) {
    $query = "delete from dbevents where id='$id'";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    $result = boolval($result);
    mysqli_close($connection);
    return $result;
}

function cancel_event($event_id, $account_name) {
    $query = "DELETE from dbeventpersons where userID LIKE '$account_name' AND eventID LIKE $event_id";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    $result = boolval($result);
    mysqli_close($connection);
    return $result;
}



function reject_signup($event_id, $account_name, $position, $notes) {
    $query = "DELETE from dbpendingsignups where username = '$account_name' AND eventname = '$event_id'";
    $connection = connect();
    $result = mysqli_query($connection, $query);
    $result = boolval($result);
    
    return $result;
}


//There was a question mark followed by a > here
