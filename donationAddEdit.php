<?php
// --- PHP LOGIC ---
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

session_cache_expire(30);
session_start();

$loggedIn = false;
$accessLevel = 0;
$userID = null;
if (isset($_SESSION['_id'])) {
    $loggedIn = true;
    $accessLevel = $_SESSION['access_level'];
    $userID = $_SESSION['_id'];
}

try {
    include_once("database/dbinfo.php");
    $con = connect();
    $query = "SELECT name FROM dbevents";
    $eventNames = mysqli_query($con, $query);
} catch(Exception $e) {
    echo "Message: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rappahannock CASA | Donation Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;700&display=swap" rel="stylesheet">

    <?php require_once('universal.inc') ?>

    <style>
        /* --- GLOBAL RESET & FONTS --- */
        * {
            box-sizing: border-box; /* Ensures padding doesn't expand width */
        }

        body {
            font-family: 'Quicksand', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        h1.page-title {
            text-align: center;
            margin-top: 30px;
            color: #333;
            font-weight: 700;
        }

        /* --- CARD CONTAINER --- */
        .content-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.15);
            /* SIZING ENFORCEMENT */
            width: 100%;
            max-width: 900px;
            margin: 20px auto;
        }

        /* --- PAGE BREAK UTILITY --- */
        /* Adds visual space on screen, forces new page on print */
        .section-break {
            margin-top: 50px;
        }

        @media print {
            .section-break {
                page-break-before: always;
                margin-top: 0;
            }
            .content-card {
                box-shadow: none;
                border: 1px solid #ddd;
                page-break-inside: avoid;
            }
            body {
                background-color: white;
            }
            .footer, .page-title, button {
                display: none;
            }
        }

        h3 {
            margin-top: 0;
            color: #00447b; /* CASA Blue */
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 15px;
            font-weight: bold;
        }

        p.note {
            font-size: 14px;
            color: #666;
            font-style: italic;
            margin-bottom: 15px;
        }

        /* --- FORM ELEMENTS --- */
        .mb-3 {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            color: #444;
            margin-bottom: 8px;
            font-size: 14px;
        }

        input[type="text"],
        input[type="number"],
        input[type="datetime-local"],
        input[type="email"],
        select,
        textarea {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border-radius: 8px;
            border: 1px solid #aaa;
            font-family: 'Quicksand', sans-serif;
            background-color: #fff;
        }

        textarea {
            resize: vertical;
            height: 100px;
        }

        input:focus, select:focus, textarea:focus {
            outline: 2px solid #00447b;
            border-color: #00447b;
        }

        input[type="checkbox"] {
            transform: scale(1.3);
            margin-left: 5px;
        }

        /* --- BUTTONS --- */
        .btn {
            display: inline-block;
            font-weight: bold;
            text-align: center;
            border: 1px solid transparent;
            padding: 12px 24px;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            transition: all 0.2s ease-in-out;
        }

        .btn-primary {
            background-color: #00447b;
            color: white;
        }

        .btn-primary:hover {
            background-color: #003366;
            transform: translateY(-2px);
        }

        /* --- ALERTS --- */
        .alert-box {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid transparent;
        }
        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }
        .alert-error {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }

        /* --- TABLE STYLING --- */
        .table-responsive {
            overflow-x: auto;
        }

        .casa-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        .casa-table th {
            background-color: #00447b;
            color: white;
            padding: 12px;
            text-align: left;
            white-space: nowrap;
        }

        .casa-table td {
            padding: 8px;
            border-bottom: 1px solid #eee;
        }

        .casa-table input {
            width: 100%;
            border: 1px solid transparent;
            background: transparent;
            padding: 5px;
            font-size: 13px;
        }

        .casa-table input:focus {
            background: white;
            border: 1px solid #00447b;
        }

        .edit-icon-btn {
            background: none;
            border: none;
            cursor: pointer;
            transition: transform 0.2s;
            padding: 5px;
        }
        .edit-icon-btn:hover {
            transform: scale(1.2);
        }

        /* --- FOOTER --- */
        .footer {
            background: #00447b;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 30px 50px;
            flex-wrap: wrap;
            margin-top: 50px;
        }
        .footer-left { display: flex; flex-direction: column; align-items: center; }
        .footer-logo { width: 150px; margin-bottom: 15px; }
        .social-icons { display: flex; gap: 15px; }
        .social-icons a { color: white; font-size: 20px; }
        .footer-right { display: flex; gap: 50px; flex-wrap: wrap; }
        .footer-section { display: flex; flex-direction: column; gap: 10px; color: white; }
        .footer-topic { font-size: 18px; font-weight: bold; }
        .footer a { color: white; text-decoration: none; padding: 5px 10px; border-radius: 5px; }
        .footer a:hover { background: rgba(255, 255, 255, 0.1); }

    </style>
</head>

<body>
<?php require_once('header.php') ?>

<h1>Donation Management</h1>

<?php
if (isset($_GET["addAttempt"]) and isset($_SESSION["addComplete"]) and isset($_SESSION["reason"])){
    $status = $_SESSION["addComplete"];
    unset($_SESSION["addComplete"]);
    $reason = $_SESSION["reason"];
    unset($_SESSION["reason"]);

    echo '<div class="content-card ' . ($status == "t" ? 'alert-success' : 'alert-error') . '">';
    echo '<h3 style="background:none; border:none; padding:0; margin-bottom:10px; color:inherit;">' . ($status == "t" ? "New Donation Added!" : "Issue Adding Donation") . '</h3>';
    echo '<p style="margin:0;">'.$reason.'</p>';
    echo '</div>';
}

if (isset($_GET["editAttempt"]) and isset($_SESSION["editComplete"])){
    $status = $_SESSION["editComplete"];
    unset($_SESSION["editComplete"]);
    $reason = $_SESSION["reason"];
    unset($_SESSION["reason"]);

    echo '<div class="content-card ' . ($status == "t" ? 'alert-success' : 'alert-error') . '">';
    echo '<h3 style="background:none; border:none; padding:0; margin-bottom:10px; color:inherit;">' . ($status == "t" ? "Changes Saved!" : "Issue Editing Donation") . '</h3>';
    echo '<p style="margin:0;">'.$reason.'</p>';
    echo '</div>';
}
?>

<div class="content-card">
    <h3>Add a New Donation</h3>
    <p class="note">Note: If first name, last name, and email fields are left unfilled, the donation is filed to the 'anonymous' donor.</p>

    <form id="addDonation" action="addDonationHandler.php" method="post">

        <div class="mb-3">
            <label>First Name of Donor</label>
            <input type="text" id="firstName" name="firstName">
        </div>

        <div class="mb-3">
            <label>Last Name of Donor</label>
            <input type="text" id="lastName" name="lastName">
        </div>

        <div class="mb-3">
            <label>Donor Email</label>
            <input type="email" id="donorEmail" name="donorEmail">
        </div>

        <div class="mb-3">
            <label>Date of Donation</label>
            <input type="date" id="date" name="date">
        </div>

        <div class="mb-3">
            <label>Amount Donated*</label>
            <input type="number" id="amount" name="amount" step="0.01" min="0" placeholder="Enter amount in $USD" required>
        </div>

        <div class="mb-3">
            <label>Associated Fees</label>
            <input type="number" id="fee" name="fee" value="0" step="0.01" min="0" placeholder="Enter amount in $USD">
        </div>

        <div class="mb-3">
            <label>Associated Fundraising Event</label>
            <select name="eventName" id="eventName">
                <option value="none">None</option>
                <?php
                if(isset($eventNames)) {
                    foreach($eventNames as $row) {
                        echo "<option value='".$row["name"]."'>".$row["name"]."</option>";
                    }
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Reason For Donation</label>
            <textarea name="reason"></textarea>
        </div>

        <div class="mb-3" style="display:flex; align-items:center;">
            <label style="margin-bottom:0; margin-right:10px;">Donation Already Thanked?</label>
            <input type="checkbox" id="thanked" name="thanked" value="y">
        </div>

        <button type="submit" class="btn btn-primary">Add Donation</button>
    </form>
</div>

<div class="section-break"></div>

<div class="content-card">
    <h3>Find and Edit Donations</h3>

    <form id="editDonation" action="editDonationHandler.php" method="post">

        <div class="mb-3">
            <label>First Name of Donor</label>
            <input type="text" id="firstName" name="firstName">
        </div>

        <div class="mb-3">
            <label>Last Name of Donor</label>
            <input type="text" id="lastName" name="lastName">
        </div>

        <div class="mb-3">
            <label>Email of Donor</label>
            <input type="email" id="email" name="email">
        </div>

        <div class="mb-3">
            <label>Date of Donation</label>
            <input type="datetime-local" id="date" name="date">
        </div>

        <div class="mb-3">
            <label>Max Donation Amount</label>
            <input type="number" id="maxAmount" name="maxAmount" min="0" max="999999999" placeholder="Enter amount in $USD" value="0" step="0.1">
        </div>

        <div class="mb-3">
            <label>Min Donation Amount</label>
            <input type="number" id="minAmount" name="minAmount" min="0" max="999999999" placeholder="Enter amount in $USD" value="0" step="0.1">
        </div>

        <input type="hidden" id="goal" name="goal" value="search">
        <button type="submit" class="btn btn-primary">Search</button>
    </form>
</div>

<?php
if (isset($_GET["searchAttempt"]) and isset($_SESSION["searchComplete"]) and isset($_SESSION["reason"])){

    $status = $_SESSION["searchComplete"];
    unset($_SESSION["searchComplete"]);
    $reason = $_SESSION["reason"];
    unset($_SESSION["reason"]);

    echo '<div class="section-break"></div>'; // Break before results too
    echo '<div class="content-card">';
    if ($status == "t"){
       echo '<h3>Search Results</h3>';
       echo '<p class="note">Donation info can be directly edited from this table by clicking the edit icon (right) after making changes.</p>';

       echo '<div class="table-responsive">';
       echo '<table class="casa-table">';
       echo '<thead>';
       echo '<tr>';
       echo '<th>Amount</th>';
       echo '<th>Reason</th>';
       echo '<th>Date (mm-dd-yyyy)</th>';
       echo '<th>Fees</th>';
       echo '<th>Thanked</th>';
       echo '<th>First Name</th>';
       echo '<th>Last Name</th>';
       echo '<th>Email</th>';
       echo '<th>Zip</th>';
       echo '<th>City</th>';
       echo '<th>Save</th>';
       echo '</tr>';
       echo '</thead><tbody>';

       foreach($reason as $row){
          echo '<tr>';
          echo '<form action="./editDonationHandler.php" method="post">';
          echo '<input type="hidden" name="goal" value="edit">';

          echo '<td><input type="number" name="amount" min="0" max="999999" step="0.1" value="'.$row[0].'" placeholder="'.$row[0].'"></td>';
          echo '<td><input type="text" name="reason" value="'.$row[1].'"></td>';
          echo '<td><input type="text" name="date" value="'.$row[2].'"></td>';
          echo '<td><input type="number" name="fee" min="0.00" max="999999" step="0.1" value="'.$row[3].'" placeholder="'.$row[3].'"></td>';
          echo '<td><input type="number" name="thanked" min="0" max="1" step="1" value="'.$row[4].'" placeholder="'.$row[4].'"></td>';
          echo '<td><input type="text" name="first" value="'.$row[5].'"></td>';
          echo '<td><input type="text" name="last" value="'.$row[6].'"></td>';
          echo '<td><input type="text" name="email" value="'.$row[7].'"></td>';
          echo '<td>'.$row[8].'</td>';
          echo '<td>'.$row[9].'</td>';

          echo '<td style="text-align:center;">';
          echo '<input type="hidden" name="donorID" value="'.$row[11].'">';
          echo '<input type="hidden" name="donationID" value="'.$row[10].'">';
          echo '<button type="submit" class="edit-icon-btn" title="Save Changes">';
          // SVG Icon
          echo '<svg width="24px" height="24px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
             <path d="M20,16v4a2,2,0,0,1-2,2H4a2,2,0,0,1-2-2V6A2,2,0,0,1,4,4H8" fill="none" stroke="#00447b" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
             <polygon fill="none" points="12.5 15.8 22 6.2 17.8 2 8.3 11.5 8 16 12.5 15.8" stroke="#00447b" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
             </svg>';
          echo '</button>';
          echo '</td>';
          echo '</form>';
          echo '</tr>';
       }
       echo "</tbody></table>";
       echo "</div>"; // End responsive wrapper
    }
    else {
       echo '<h3 style="color:#d9534f">Issue Searching Donations</h3>';
       echo '<p>'.$reason.'</p>';
    }
    echo '</div>';
}
?>

<br/>
<br/>

<footer class="footer">
    <div class="footer-left">
        <img src="images/RAPPAHANNOCK_v_White-300x300.png" alt="Logo" class="footer-logo">
        <div class="social-icons">
            <a href="#"><i class="fab fa-facebook"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-linkedin"></i></a>
        </div>
    </div>

    <div class="footer-right">
        <div class="footer-section">
            <div class="footer-topic">Connect</div>
            <a href="https://www.facebook.com/RappCASA/" target="_blank">Facebook</a>
            <a href="https://www.instagram.com/rappahannock_casa/" target="_blank">Instagram</a>
            <a href="https://rappahannockcasa.com/" target="_blank">Main Website</a>
        </div>
        <div class="footer-section">
            <div class="footer-topic">Contact Us</div>
            <a href="mailto:rappcasa@gmail.com">rappcasa@gmail.com</a>
            <a href="tel:5407106199">540-710-6199</a>
        </div>
    </div>
</footer>

<script src="https://kit.fontawesome.com/yourkit.js" crossorigin="anonymous"></script>

</body>
</html>
