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
            max-width: 920px; /* WIDE FORM */
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

        /* ---- Required indicator note ---- */
        .required-note {
            font-size: 14px;
            color: #666;
            margin: -8px 0 14px 0;
            font-style: italic;
        }

        /* ---- Labels ---- */
        #new-event-form label {
            font-weight: 600;
            color: #333;
            margin-top: 10px;
            font-size: 15px;
        }

        /* ---- Inputs + textarea ---- */
        #new-event-form input[type="text"],
        #new-event-form textarea {
            width: 100%;
            padding: 12px 14px;
            border-radius: 8px;
            border: 1px solid #bbb;
            font-size: 16px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        /* Large notes box */
        #new-event-form textarea {
            min-height: 120px;
            resize: vertical;
        }

        /* ---- Focus states ---- */
        #new-event-form input:focus,
        #new-event-form textarea:focus {
            border-color: #2a6fb0;
            box-shadow: 0 0 4px rgba(42,111,176,0.4);
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
        <h1>Add Donor</h1>
        <main class="date">
            <p style="font-size:14px; color:red; margin-top:-8px; margin-bottom:18px; padding-left:5rem;">
                <em>* indicates required fields</em>
            </p>
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

                <label for="notes">notes</label>
                <textarea id="notes" name="notes" placeholder=""></textarea>
                
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