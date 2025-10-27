<?php session_cache_expire(30);
    session_start();
    // Make session information accessible, allowing us to associate
    // data with the logged-in user.

    ini_set("display_errors",1);
    error_reporting(E_ALL);

    $loggedIn = false;
    $accessLevel = 0;
    $userID = null;
    if (isset($_SESSION['_id'])) {
        $loggedIn = true;
        // 0 = not logged in, 1 = standard user, 2 = manager (Admin), 3 super admin (TBI)
        $accessLevel = $_SESSION['access_level'];
        $userID = $_SESSION['_id'];
    } 
    // Require admin privileges
    if ($accessLevel < 2) {
        header('Location: login.php');
        echo 'bad access level';
        die();
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        require_once('include/input-validation.php');
        require_once('database/dbEvents.php');

        $args = sanitize($_POST, null);

        $required = array("name","goalAmount", "startDate","endDate", "startTime", "endTime", "description");

        if (!wereRequiredFieldsSubmitted($args, $required)) {
            echo 'bad form data';
            die();
        } else {
            $validated = validate12hTimeRangeAndConvertTo24h($args["startTime"], $args["endTime"]);
            if (!$validated) {
                echo 'bad time range';
                die();
            }

            $startTime = $args['startTime'] = $validated[0];
            $endTime = $args['endTime'] = $validated[1];
            $startDate = $args['startDate'] = validateDate($args["startDate"]);
            $endDate = $args['endDate'] = validateDate($args["endDate"]);
            
    
            if (!$startTime || !$endTime || !$endDate || !$startDate){
                echo 'bad args';
                die();
            }

            $id = create_event($args);
            if(!$id){
                die();
            } else {
                header('Location: eventSuccess.php');
                exit();
            }
            
        }
    }
    $date = null;
    if (isset($_GET['date'])) {
        $date = $_GET['date'];
        $datePattern = '/[0-9]{4}-[0-9]{2}-[0-9]{2}/';
        $timeStamp = strtotime($date);
        if (!preg_match($datePattern, $date) || !$timeStamp) {
            header('Location: calendar.php');
            die();
        }
    }

    include_once('database/dbinfo.php'); 
    $con=connect();  

?><!DOCTYPE html>
<html>
    <head>
        <?php require_once('universal.inc') ?>
        <title>Rappahannock CASA | Create Event</title>
    </head>
    <body>
        <?php require_once('header.php') ?>
        <h1>Create Event</h1>
        <main class="date">
            <h2>New Event Form</h2>
            <form id="new-event-form" method="POST">
                <label for="name">* Event Name </label>
                <input type="text" id="name" name="name" required placeholder="Enter name"> 

                <label for="name">* Fundraiser Goal </label>
                <input type="text" id="goalAmount" name="goalAmount" required placeholder=" $$$ ">

                <label for="name">* Start Date </label>
                <input type="date" id="startDate" name="startDate" <?php if ($date) echo 'value="' . $date . '"'; ?> min="<?php echo date('Y-m-d'); ?>" required>

                <label for="name">* End Date </label>
                <input type="date" id="endDate" name="endDate" <?php if ($date) echo 'value="' . $date . '"'; ?> min="<?php echo date('Y-m-d'); ?>" required>

                <label for="name">* Start Time </label>
                <input type="text" id="startTime" name="startTime" pattern="([1-9]|10|11|12):[0-5][0-9] ?([aApP][mM])" required placeholder="Enter start time. Ex. 12:00 PM">

                <label for="name">* End Time </label>
                <input type="text" id="endTime" name="endTime" pattern="([1-9]|10|11|12):[0-5][0-9] ?([aApP][mM])" required placeholder="Enter end time. Ex. 1:00 PM">

                <label for="name">* Description </label>
                <input type="text" id="description" name="description" required placeholder="Enter description">
  
                <label for="name">Location </label>
                <input type="text" id="location" name="location" placeholder="Enter location">

                
                <input type="submit" value="Create Event">
                
            </form>
                <?php if ($date): ?>
                    <a class="button cancel" href="calendar.php?month=<?php echo substr($date, 0, 7) ?>" style="margin-top: -.5rem">Return to Calendar</a>
                <?php else: ?>
                    <a class="button cancel" href="index.php" style="margin-top: -.5rem">Return to Dashboard</a>
                <?php endif ?>

                <!-- Require at least one checkbox be checked -->
                <script type="text/javascript">
                    $(document).ready(function(){
                        var checkboxes = $('.checkboxes');
                        checkboxes.change(function(){
                            if($('.checkboxes:checked').length>0) {
                                checkboxes.removeAttr('required');
                            } else {
                                checkboxes.attr('required', 'required');
                            }
                        });
                    });
                </script>
        </main>
    </body>
</html>