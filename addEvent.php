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
            header('Location: eventFailure.php?e=1');
            exit();
        } else {

            $startTime = to24h($args['startTime']);
            $endTime = to24h($args['endTime']);
            $startDate = validateDate($args["startDate"]);
            $endDate = validateDate($args["endDate"]);

            //check if end is later than start
            $start = $startDate . ' ' . $startTime;
            $end = $endDate . ' ' . $endTime;
            $valid = $start < $end;
            if (!$valid) {
                header('Location: eventFailure.php?e=2');
                exit();
            }

            $args['startTime'] = $startTime;
            $args['endTime'] = $endTime;
            $args['startDate'] = $startDate;
            $args['endDate'] = $endDate;
            
    
            if (!$startTime || !$endTime || !$endDate || !$startDate){
                header('Location: eventFailure.php?e=3');
                exit();
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
        <title>Rappahannock CASA | Create Fundraiser</title>
        <style>
        /* ---- Page Layout ---- */
        main.date {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 30px 20px;
        }

        /* ---- Form Card ---- */
        #new-event-form {
            width: 100%;
            max-width: 920px; /* WIDER FORM */
            background: #ffffff;
            padding: 35px 32px;
            border-radius: 14px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.12);
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        /* ---- Headings ---- */
        main.date h2 {
            margin-bottom: 18px;
            color: #024b79;
            font-size: 26px;
        }

        /* ---- Labels ---- */
        #new-event-form label {
            font-weight: 600;
            color: #333;
            margin-top: 10px;
            font-size: 15px;
        }

        /* ---- Inputs ---- */
        #new-event-form input[type="text"],
        #new-event-form input[type="date"],
        #new-event-form textarea {
            width: 100%;
            padding: 12px 14px;
            border-radius: 8px;
            border: 1px solid #bbb;
            font-size: 16px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        /* ---- Large description box ---- */
        #new-event-form textarea {
            min-height: 140px;       /* MUCH larger */
            resize: vertical;        /* allow user to stretch if needed */
        }

        /* ---- Focus states ---- */
        #new-event-form input:focus,
        #new-event-form textarea:focus {
            border-color: #2a6fb0;
            box-shadow: 0 0 4px rgba(42, 111, 176, 0.4);
            outline: none;
        }

        /* ---- Submit Button ---- */
        #new-event-form input[type="submit"] {
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

        #new-event-form input[type="submit"]:hover {
            background: #1e5d94;
        }

        #new-event-form input[type="submit"]:active {
            transform: scale(0.97);
        }

        /* ---- Return Link ---- */
        .button.cancel {
            margin-top: 25px;
            font-size: 16px;
        }
        </style>


    </head>
    <body>
        <?php require_once('header.php') ?>
        <h1>Create Fundraiser</h1>
        <main class="date">
            <p style="font-size:14px; color:red; margin-top:-8px; margin-bottom:18px; padding-left:5rem;">
                <em>* indicates required fields</em>
            </p>

            <form id="new-event-form" method="POST">
                <label for="name">* Fundraiser Name </label>
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

                <label for="description">* Description </label>
                <textarea id="description" name="description" required placeholder="Enter description"></textarea>
                
                <label for="name">Location </label>
                <input type="text" id="location" name="location" placeholder="Enter location">

                
                <input type="submit" value="Create Fundraiser">
                
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