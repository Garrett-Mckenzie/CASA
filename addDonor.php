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
        require_once('database/dbDonors.php');

        $args = sanitize($_POST, null);

        $required = array("first","last");

        if (!wereRequiredFieldsSubmitted($args, $required)) {
            echo 'bad form data';
            die();
        } else {
            $validStates = ['AL', 'AK', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'FL', 'GA', 'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME', 'MD', 'MA', 'MI', 'MN', 'MS', 'MO', 'MT', 'NE', 'NV', 'NH', 'NJ', 'NM', 'NY', 'NC', 'ND', 'OH', 'OK', 'OR', 'PA', 'RI', 'SC', 'SD', 'TN', 'TX', 'UT', 'VT', 'VA', 'WA', 'WV', 'WI', 'WY'];

            $email = $args['email'] = validateEmail($args['email']);
            $zip = $args['zip'] = validateZipcode($args['zip']);
            $phone = $args['phone'] = validateAndFilterPhoneNumber($args["phone"]);

            $state = valueConstrainedTo(strtoupper($args['state']),$validStates);
            if ($state == 1){
                $args['state'] = strtoupper($args['state']);
            }
            else{
                $args['state'] = null;
            }


            $id = create_donor($args);
            if(!$id){
                die();
            } else {
                header('Location: donorSuccess.php');
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
        <title>Rappahannock CASA | Add Donor</title>
    </head>
    <body>
        <?php require_once('header.php') ?>
        <h1>Add Donor</h1>
        <main class="date">
            <h2>New Donor Form</h2>
            <form id="new-event-form" method="POST">
                <label for="name">* first name</label>
                <input type="text" id="first" name="first" required placeholder="Enter name"> 

                <label for="name">* last name</label>
                <input type="text" id="last" name="last" required placeholder="Enter name">

                <label for="name"> email</label>
                <input type="text" id="email" name="email" placeholder="somebody@gmail.com">

                <label for="name"> zip code</label>
                <input type="text" id="zip" name="zip" placeholder="11234">

                <label for="name"> city</label>
                <input type="text" id="city" name="city" placeholder="">

                <label for="name"> state</label>
                <input type="text" id="state" name="state" placeholder="VA, WA, OR, etc">

                <label for="name"> street</label>
                <input type="text" id="street" name="street" placeholder="">

                <label for="name"> phone #</label>
                <input type="text" id="phone" name="phone" placeholder="">

                <label for="name"> notes</label>
                <input type="text" id="notes" name="notes" placeholder="">
                
                <input type="submit" value="add donor">
                
            </form>
                    
                <?php
                    echo "<a class='button cancel' href='index.php' style='margin-top: -.5rem'>Return to Dashboard</a>";
                ?>

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