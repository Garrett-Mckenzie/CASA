<?php
date_default_timezone_set('America/New_York');
/*
 * Copyright 2013 by Allen Tucker. */

// check if we are in locked mode
?>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="icon" type="image/png" href="images/RAPPAHANNOCK_v_RedBlue2.png">

  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;700&display=swap" rel="stylesheet">

  <style>
    <?php if (empty($tailwind_mode)): ?>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    <?php endif; ?>
    body {
      font-family: 'Quicksand', sans-serif;
      padding-top: 80px; /* Space for fixed header */
    }

    /* nav */
    .navbar {
      width: 100%;
      height: 80px;
      position: fixed;
      top: 0;
      left: 0;
      background: white;
      box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.15);
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0 20px;
      z-index: 1000;
      box-sizing: border-box;
    }

    /* Logo Container - FIXED STABILITY */
    .logo-container {
      background: #00447b;
      padding: 5px 15px;
      border-radius: 50px;
      display: flex;
      align-items: center;
      justify-content: center;

      /* New Stability Rules to prevent "smashing" */
      box-sizing: border-box !important;
      height: 60px;
      width: auto;
      flex-shrink: 0;
    }

    .logo-container a {
        display: flex;
        align-items: center;
        height: 100%;
    }

    .logo-container img {
      height: 50px !important; /* Force exact height */
      width: auto !important;  /* Force aspect ratio */
      max-width: none !important;
      display: block;
      filter: brightness(1000%); /* Keeps your white logo effect */
    }

    /* Hamburger Icon */
    .hamburger-icon {
      cursor: pointer;
      padding: 10px;
      display: flex;
      flex-direction: column;
      gap: 6px;
      z-index: 1002;
    }
    .bar {
      width: 30px;
      height: 3px;
      background-color: #00447b;
      border-radius: 5px;
      transition: 0.3s;
    }

    /* Animation for X transformation */
    .hamburger-icon.open .bar:nth-child(1) { transform: rotate(-45deg) translate(-7px, 6px); }
    .hamburger-icon.open .bar:nth-child(2) { opacity: 0; }
    .hamburger-icon.open .bar:nth-child(3) { transform: rotate(45deg) translate(-7px, -6px); }

    /* side menu */
    .side-menu {
      height: 100%;
      width: 0;
      position: fixed;
      z-index: 1001;
      top: 0;
      right: 0;
      background-color: #00447b;
      overflow-x: hidden;
      transition: 0.4s;
      padding-top: 80px;
      box-shadow: -4px 0 10px rgba(0,0,0,0.2);
    }

    .side-menu.open {
      width: 300px;
    }

    /* Menu Links */
    .side-menu a {
      padding: 15px 30px;
      text-decoration: none;
      font-size: 20px;
      color: #f1f1f1;
      display: block;
      transition: 0.2s;
      border-bottom: 1px solid rgba(255,255,255,0.1);
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .side-menu a:hover {
      background-color: #005a9e;
      color: #fff;
      padding-left: 40px;
    }

    .side-menu .menu-section-title {
        color: #7aacf5;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 20px 30px 5px 30px;
        margin-top: 10px;
        font-weight: bold;
    }

    .side-menu img {
        width: 24px;
        height: 24px;
        filter: brightness(0) invert(1);
    }

    /* Overlay Background */
    .menu-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.5);
      z-index: 1000;
      opacity: 0;
      visibility: hidden;
      transition: 0.3s;
    }
    .menu-overlay.open {
      opacity: 1;
      visibility: visible;
    }

    /* --- DATE DISPLAY --- */
    .header-date {
        font-weight: bold;
        color: #00447b;
        margin-right: 20px;
        display: none;
        white-space: nowrap;
    }
    @media (min-width: 768px) {
        .header-date { display: block; }
    }

  </style>

  <script>
    function toggleMenu() {
        const menu = document.getElementById("mySideMenu");
        const icon = document.getElementById("hamburgerIcon");
        const overlay = document.getElementById("menuOverlay");

        menu.classList.toggle("open");
        icon.classList.toggle("open");
        overlay.classList.toggle("open");
    }
  </script>
</head>


<header>

<?php
// Permission Logic
$permission_array = [];
$permission_array['index.php'] = 0;
$permission_array['about.php'] = 0;
$permission_array['apply.php'] = 0;
$permission_array['logout.php'] = 0;
$permission_array['volunteerregister.php'] = 3;
$permission_array['leaderboard.php'] = 0;
// Volunteers
$permission_array['help.php'] = 1;
$permission_array['dashboard.php'] = 1;
$permission_array['calendar.php'] = 1;
$permission_array['eventsearch.php'] = 1;
$permission_array['changepassword.php'] = 1;
$permission_array['editprofile.php'] = 1;
$permission_array['inbox.php'] = 1;
$permission_array['date.php'] = 1;
$permission_array['event.php'] = 1;
$permission_array['viewprofile.php'] = 1;
$permission_array['viewnotification.php'] = 1;
$permission_array['volunteerreport.php'] = 1;
$permission_array['viewcheckinout.php'] = 1;
$permission_array['viewresources.php'] = 1;
$permission_array['milestonepoints.php'] = 1;
$permission_array['selectvotm.php'] = 1;
$permission_array['eventlist.php'] = 1;
$permission_array['eventsignup.php'] = 1;
$permission_array['eventfailure.php'] = 1;
$permission_array['signupsuccess.php'] = 1;
$permission_array['edittimes.php'] = 1;
$permission_array['signuppending.php'] = 1;
$permission_array['requestfailed.php'] = 1;
$permission_array['settimes.php'] = 1;
$permission_array['eventfailurebaddeparturetime.php'] = 1;
$permission_array['specificevent.php'] = 1;
// Managers
$permission_array['viewallevents.php'] = 0;
$permission_array['personedit.php'] = 0;
$permission_array['viewschedule.php'] = 2;
$permission_array['addweek.php'] = 2;
$permission_array['log.php'] = 2;
$permission_array['reports.php'] = 2;
$permission_array['eventedit.php'] = 2;
$permission_array['addevent.php'] = 2;
$permission_array['editevent.php'] = 2;
$permission_array['report.php'] = 2;
$permission_array['reportspage.php'] = 2;
$permission_array['resetpassword.php'] = 2;
$permission_array['eventsuccess.php'] = 2;
$permission_array['viewsignuplist.php'] = 2;
$permission_array['vieweventsignups.php'] = 2;
$permission_array['viewalleventsignups.php'] = 2;
$permission_array['resources.php'] = 2;
$permission_array['uploadresources.php'] = 2;
$permission_array['deleteresources.php'] = 2;
$permission_array['managemembers.php'] = 2;
$permission_array['eventmanagement.php'] = 2;
$permission_array['deletediscussion.php'] = 2;
$permission_array['clockoutbulk.php'] = 2;
$permission_array['clockOut.php'] = 2;
$permission_array['edithours.php'] = 2;
$permission_array['adminviewingevents.php'] = 2;
// Super Admin
$permission_array['import.php'] = 3;
$permission_array['export.php'] = 3;
$permission_array['emailgen.php'] = 3;
$permission_array['Search.php'] = 3;
$permission_array['adddonor.php'] = 3;
$permission_array['donationaddedit.php'] = 3;
$permission_array["editdonationhandler.php"] = 3;
$permission_array["ReportGeneration.php"] = 3;
$permission_array["VolunteerRegister.php"] = 3;

// Security Redirect
if (isset($_SESSION['access_level'])) {
    $current_page = strtolower(substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], '/') + 1));
    $current_page = substr($current_page, strpos($current_page, "/"));

    if (isset($permission_array[$current_page]) && $permission_array[$current_page] > $_SESSION['access_level']) {
        echo "<script type=\"text/javascript\">window.location = \"index.php\";</script>";
        die();
    }
}
?>

<div class="navbar">
    <div class="logo-container">
        <a href="index.php">
            <img src="images/RAPPAHANNOCK_v_RedBlue2.png" alt="Logo">
        </a>
    </div>

    <div style="display: flex; align-items: center; gap: 20px;">
        <div class="header-date"><?php echo date('l, F j, Y'); ?></div>

        <div class="hamburger-icon" id="hamburgerIcon" onclick="toggleMenu()">
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
        </div>
    </div>
</div>

<div class="menu-overlay" id="menuOverlay" onclick="toggleMenu()"></div>

<div id="mySideMenu" class="side-menu">

    <?php if (!isset($_SESSION['logged_in'])): ?>


    <?php elseif ($_SESSION['logged_in']): ?>

        <a href="index.php">Home / Dashboard</a>
        <a href="calendar.php">Calendar</a>
        <a href="viewAllEvents.php">Event Management</a>

        <?php if ($_SESSION['access_level'] <= 1): ?>
            <div class="menu-section-title">Volunteering</div>
            <a href="viewAllEvents.php">Sign-Up for Events</a>
            <a href="volunteerReport.php">My Hours</a>
            <a href="editHours.php">Edit Hours</a>

            <div class="menu-section-title">My Account</div>
            <a href="viewProfile.php">View Profile</a>
            <a href="editProfile.php">Edit Profile</a>
            <a href="inbox.php">Notifications</a>
        <?php endif; ?>

        <?php if ($_SESSION['access_level'] >= 2): ?>
            <div class="menu-section-title">Tools</div>
	    <a href="/VolunteerRegister.php">
                <img src="images/add-person.svg"> Add Users
            </a>

	    <a href="/addDonor.php">
                <img src="images/plus-solid.svg"> Add Donor
            </a>


            <a href="/searchindex.php">
                <img src="images/search.svg"> Seairch Database
            </a>
            <a href="emailgen.php">
                <img src="images/toppng.com-email-icon-vector-circle-980x980.png"> Email Gen
            </a>
            <a href="ReportGeneration.php">
                <img src="images/clipboard-regular.svg"> Report Gen
            </a>

            <div class="menu-section-title">Data Management</div>
            <a href="import.php">
                 <img src="images/toppng.com-file-save-icon-vector-980x980.png"> Import Data
            </a>
            <a href="export.php">
                 <img src="images/toppng.com-ms-excel-icon-microsoft-powerpoint-icon-1557x1588.png"> Export Data
            </a>

        <?php endif; ?>

        <div style="margin-top: 30px; border-top: 1px solid rgba(255,255,255,0.2);">
            <a href="logout.php" style="color: #ffcccc;">Log Out</a>
        </div>

    <?php endif; ?>
</div>

</header>
