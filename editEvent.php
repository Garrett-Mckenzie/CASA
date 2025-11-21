<?php 
session_cache_expire(30);
session_start();

ini_set("display_errors",1);
error_reporting(E_ALL);

// Session / Access control
$loggedIn = false;
$accessLevel = 0;
$userID = null;
require_once('include/input-validation.php');
require_once('database/dbEvents.php');

if (isset($_SESSION['_id'])) {
    $loggedIn = true;
    $accessLevel = $_SESSION['access_level'];
    $userID = $_SESSION['_id'];
}
if ($accessLevel < 2) {
    header('Location: login.php');
    die("bad access level");
}

// ----------------------------------------------------
//  LOAD EVENT (GET)
// ----------------------------------------------------
if (!isset($_GET['id'])) {
    die("Missing event ID");
}

$eventID = intval($_GET['id']);
$event = fetch_event_by_id($eventID);

if (!$event) {
    die("Event not found");
}

// Prefill fields
$name        = $event["name"];
$goalAmount  = $event["goalAmount"];
$startDate   = $event["startDate"];
$endDate     = $event["endDate"];
$startTime   = time24hto12h($event["startTime"]);   // convert "14:00" → "2:00 PM"
$endTime     = time24hto12h($event["endTime"]);
$description = $event["description"];
$location    = $event["location"];

// ----------------------------------------------------
//  UPDATE EVENT (POST)
// ----------------------------------------------------
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $args = sanitize($_POST, null);
    $required = array("name","goalAmount", "startDate","endDate", "startTime", "endTime", "description");

    if (!wereRequiredFieldsSubmitted($args, $required)) {
        header("Location: eventFailure.php?e=1&edit=1&id=".$eventID);
        exit();
    }

    $startTime24 = to24h($args['startTime']);
    $endTime24   = to24h($args['endTime']);
    $startDate   = validateDate($args["startDate"]);
    $endDate     = validateDate($args["endDate"]);

    // Validate date ordering
    $start = $startDate . ' ' . $startTime24;
    $end   = $endDate . ' ' . $endTime24;

    if ($start >= $end) {
        header('Location: eventFailure.php?e=2&edit=1&id='.$eventID);
        exit();
    }

    if (!$startTime24 || !$endTime24 || !$endDate || !$startDate) {
        header('Location: eventFailure.php?e=3&edit=1&id='.$eventID);
        exit();
    }

    // Attach final processed values
    $args['startTime'] = $startTime24;
    $args['endTime']   = $endTime24;
    $args['startDate'] = $startDate;
    $args['endDate']   = $endDate;
    $args['id']        = $eventID;

    $success = update_event($eventID,$args);

    if (!$success) {
        die("Update failed.");
    } else {
        header('Location: eventSuccess.php?edit=1');
        exit();
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <?php require_once('universal.inc') ?>
    <title>Edit Event</title>

    <style>
        main.date {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 30px 20px;
        }
        #edit-event-form {
            width: 100%;
            max-width: 920px;
            background: #ffffff;
            padding: 35px 32px;
            border-radius: 14px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.12);
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        main.date h2 {
            margin-bottom: 18px;
            color: #024b79;
            font-size: 26px;
        }
        #edit-event-form label {
            font-weight: 600;
            color: #333;
            font-size: 15px;
            margin-top: 10px;
        }
        #edit-event-form input[type="text"],
        #edit-event-form input[type="date"],
        #edit-event-form textarea {
            width: 100%;
            padding: 12px 14px;
            border-radius: 8px;
            border: 1px solid #bbb;
            font-size: 16px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        #edit-event-form textarea {
            min-height: 140px;
            resize: vertical;
        }
        #edit-event-form input:focus,
        #edit-event-form textarea:focus {
            border-color: #2a6fb0;
            box-shadow: 0 0 4px rgba(42, 111, 176, 0.4);
            outline: none;
        }
        #edit-event-form input[type="submit"] {
            margin-top: 12px;
            padding: 14px;
            background: #2a6fb0;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s, transform 0.15s;
        }
        #edit-event-form input[type="submit"]:hover {
            background: #1e5d94;
        }
        #edit-event-form input[type="submit"]:active {
            transform: scale(0.97);
        }
        .button.cancel {
            margin-top: 25px;
            font-size: 16px;
        }
    </style>
</head>

<body>
<?php require_once('header.php'); ?>

<h1>Edit Event</h1>

<main class="date">

    <p style="font-size:14px; color:red; margin-top:-8px; margin-bottom:18px; padding-left:5rem;">
        <em>* indicates required fields</em>
    </p>

    <form id="edit-event-form" method="POST">

        <label>* Fundraiser Name</label>
        <input type="text" name="name" required value="<?php echo htmlspecialchars($name); ?>">

        <label>* Fundraiser Goal</label>
        <input type="text" name="goalAmount" required value="<?php echo htmlspecialchars($goalAmount); ?>">

        <label>* Start Date</label>
        <input type="date" name="startDate" required value="<?php echo $startDate; ?>">

        <label>* End Date</label>
        <input type="date" name="endDate" required value="<?php echo $endDate; ?>">

        <label>* Start Time</label>
        <input type="text" name="startTime" required 
               pattern="([1-9]|10|11|12):[0-5][0-9] ?([aApP][mM])"
               value="<?php echo $startTime; ?>">

        <label>* End Time</label>
        <input type="text" name="endTime" required 
               pattern="([1-9]|10|11|12):[0-5][0-9] ?([aApP][mM])"
               value="<?php echo $endTime; ?>">

        <label>* Description</label>
        <textarea name="description" required><?php echo htmlspecialchars($description); ?></textarea>

        <label>Location</label>
        <input type="text" name="location" value="<?php echo htmlspecialchars($location); ?>">

        <input type="submit" value="Save Changes">
    </form>

    <a class="button cancel" href="viewEvent.php?id=<?php echo $eventID; ?>">Return to Event</a>

</main>
</body>
</html>
