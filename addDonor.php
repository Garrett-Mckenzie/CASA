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
        <title>Rappahannock CASA | Add Donor</title>
        <link rel="icon" type="image/png" href="images/RAPPAHANNOCK_v_RedBlue2.png">
        <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;700&display=swap" rel="stylesheet">
        <script src="https://kit.fontawesome.com/yourkit.js" crossorigin="anonymous"></script>

        <style>
        /* --- GLOBAL RESET & FONTS --- */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Quicksand', sans-serif;
            background-color: #f9f9f9;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        h1 {
            text-align: center;
            color: #333;
            font-weight: 700;
            margin-top: 30px;
        }

        /* --- CARD CONTAINER --- */
        .content-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.15);
            width: 100%;
            max-width: 900px;
            margin: 30px auto;
        }

        /* ---- Headings ---- */
        h3 {
            margin-top: 0;
            color: #00447b;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 15px;
            font-weight: bold;
        }

        /* ---- Required indicator note ---- */
        .required-note {
            font-size: 14px;
            color: #d9534f;
            margin: -8px 0 14px 0;
            font-style: italic;
        }

        /* ---- Labels ---- */
        label {
            display: block;
            font-weight: bold;
            color: #444;
            margin-bottom: 8px;
            font-size: 14px;
            margin-top: 15px;
        }

        /* ---- Inputs + textarea ---- */
        input[type="text"], textarea {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border-radius: 8px;
            border: 1px solid #aaa;
            font-family: 'Quicksand', sans-serif;
            box-sizing: border-box;
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        /* ---- Focus states ---- */
        input:focus, textarea:focus {
            border-color: #00447b;
            outline: 2px solid #00447b;
        }

        /* ---- Submit Button ---- */
        input[type="submit"] {
            margin-top: 25px;
            padding: 12px 24px;
            background: #00447b;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
            transition: background 0.2s, transform 0.15s;
        }

        input[type="submit"]:hover {
            background: #003366;
            transform: translateY(-2px);
        }
        </style>

    </head>
    <body>
        <?php require_once('header.php') ?>

        <div class="content-card">
            <h3>Add Donor</h3>
            <p class="required-note">
                <em>* indicates required fields</em>
            </p>
            <form id="new-event-form" method="POST">
                <label for="name">* First name</label>
                <input type="text" id="first" name="first" required placeholder="Enter name">

                <label for="name">* Last name</label>
                <input type="text" id="last" name="last" required placeholder="Enter name">

                <label for="name"> Email</label>
                <input type="text" id="email" name="email" placeholder="somebody@gmail.com">

                <label for="name"> Zip code</label>
                <input type="text" id="zip" name="zip" placeholder="11234">

                <label for="name"> City</label>
                <input type="text" id="city" name="city" placeholder="">

                <label for="name"> State</label>
                <input type="text" id="state" name="state" placeholder="VA, WA, OR, etc">

                <label for="name"> Street</label>
                <input type="text" id="street" name="street" placeholder="">

                <label for="name"> Phone #</label>
                <input type="text" id="phone" name="phone" placeholder="">

                <label for="notes">Notes</label>
                <textarea id="notes" name="notes" placeholder=""></textarea>

                <input type="submit" value="Add Donor">

            </form>

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
        </div>

        <?php require_once('footer.php'); ?>
    </body>
</html>