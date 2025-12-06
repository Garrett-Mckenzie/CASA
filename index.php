<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    session_cache_expire(30);
    session_start();

    date_default_timezone_set("America/New_York");
    
    // Security Redirect
    if (!isset($_SESSION['access_level']) || $_SESSION['access_level'] < 1) {
        if (isset($_SESSION['change-password'])) {
            header('Location: changePassword.php');
        } else {
            header('Location: login.php');
        }
        die();
    }

    require 'database/dbPersons.php';
    require 'domain/Person.php';

    if (isset($_SESSION['_id'])) {
        $person = retrieve_person($_SESSION['_id']);
    }

    // Logic Fix: Define who sees the volunteer view
    $isVolunteer = ($_SESSION['access_level'] < 2);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;700&display=swap" rel="stylesheet">
    <title>CASA Donation Management Web Application</title>
    <link rel="icon" type="image/png" href="images/RAPPAHANNOCK_v_RedBlue2.png">

    <script src="https://kit.fontawesome.com/yourkit.js" crossorigin="anonymous"></script>

    <style>
        /* --- GLOBAL RESETS --- */
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Quicksand', sans-serif;
            background-color: #f9f9f9; /* Subtle background color */
        }

        h2 {
            font-weight: normal;
            font-size: 30px;
            color: #333;
        }

        /* --- DASHBOARD LAYOUT --- */
        .full-width-bar {
            width: 100%;
            background: #00447b;
            padding: 30px 5%;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 25px;
        }

        .full-width-bar-sub {
            width: 100%;
            background: white;
            padding: 30px 5%;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 25px;
        }

        /* --- TOP ROW CARDS (Blue Section) --- */
        .content-box {
            flex: 1 1 280px;
            max-width: 375px;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            position: relative;
            padding-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2); /* Added shadow for depth */
        }

        .content-box img {
            width: 100%;
            height: auto;
            border-bottom: 1px solid #ddd;
        }

        .small-text {
            margin-top: 15px;
            font-size: 14px;
            font-weight: 700;
            color: #00447b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .large-text {
            margin-top: 5px;
            font-size: 22px;
            font-weight: 700;
            color: #333;
            margin-bottom: 50px; /* Space for the button */
        }

        /* --- BOTTOM ROW CARDS (White Section) --- */
        .content-box-test {
            flex: 1 1 250px;
            max-width: 350px;
            padding: 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            position: relative;
            cursor: pointer;
            border: 1px solid #e0e0e0;
            transition: all 0.3s ease;
            border-radius: 12px;
            background: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .content-box-test:hover {
            border-color: #007BFF;
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,123,255,0.15);
        }

        .content-box-test img.background-image {
            width: 100%;
            border-radius: 8px;
            opacity: 0.1; /* Faded background effect */
        }

        .large-text-sub {
            font-size: 20px;
            font-weight: 700;
            color: #333;
            margin-top: 15px;
            z-index: 2;
        }

        .graph-text {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
            z-index: 2;
        }

        /* --- ICONS & BUTTONS --- */
        .icon-overlay {
            position: absolute;
            top: 20%;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            z-index: 2;
        }

        .icon-overlay img {
            width: 30px;
            height: 30px;
        }

        /* Circle Go Button */
        .circle-arrow-button {
            position: absolute;
            bottom: 20px;
            right: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            background: transparent;
            border: none;
            font-family: 'Quicksand', sans-serif;
            font-weight: bold;
            color: #00447b;
            cursor: pointer;
            font-size: 16px;
        }

        .circle {
            width: 35px;
            height: 35px;
            background-color: #00447b;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            transition: transform 0.3s ease, background 0.3s;
        }

        .circle-arrow-button:hover .circle {
            transform: translateX(5px);
            background-color: #0066cc;
        }

        /* Standard Arrow Button */
        .arrow-button {
            margin-top: 15px;
            background: none;
            border: none;
            font-size: 24px;
            color: #00447b;
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        .content-box-test:hover .arrow-button {
            transform: translateX(5px);
        }

        /* --- FOOTER --- */
        .footer {
            background: #00447b;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 40px 5%;
            color: white;
            flex-wrap: wrap;
            gap: 30px;
        }

        .footer-left {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .footer-logo { width: 120px; margin-bottom: 15px; }

        .social-icons { display: flex; gap: 15px; }
        .social-icons a { color: white; font-size: 20px; transition: 0.3s; }
        .social-icons a:hover { color: #dcdcdc; }

        .footer-right {
            display: flex;
            gap: 40px;
        }

        .footer-section { display: flex; flex-direction: column; gap: 8px; }
        .footer-topic { font-weight: bold; font-size: 18px; margin-bottom: 5px; }
        .footer-section a { color: #e0e0e0; text-decoration: none; font-size: 15px; }
        .footer-section a:hover { text-decoration: underline; color: white; }

        /* Responsive Fixes */
        @media (max-width: 768px) {
            .footer { flex-direction: column; text-align: center; }
            .footer-right { flex-direction: column; gap: 20px; }
        }
   </style>
</head>

<body>

    <?php require 'header.php';?>

    <?php if ($_SESSION['access_level'] >= 2): ?>

       <div style="margin-top: 20px; padding: 30px 5%;">
             <h2><b>Welcome <?php echo htmlspecialchars($person->get_name()); ?>!</b> Let's get started.</h2>
       </div>

       <div class="full-width-bar">
            <div class="content-box">
                <img src="images/blank-white-background.jpg"/>
                <div class="small-text">Analyze the data!</div>
                <div class="large-text">Generate Report</div>
                <button class="circle-arrow-button" onclick="window.location.href='ReportGeneration.php'">
                    <span class="button-text">Go</span>
                    <div class="circle">&gt;</div>
                </button>
            </div>

            <div class="content-box">
                <img src="images/blank-white-background.jpg"/>
                <div class="small-text">Let’s raise some money!</div>
                <div class="large-text">Fundraiser Management</div>
                <button class="circle-arrow-button" onclick="window.location.href='viewAllEvents.php'">
                    <span class="button-text">Go</span>
                    <div class="circle">&gt;</div>
                </button>
            </div>
       </div>

        <div style="margin-top: 50px; padding: 0 5%;">
            <h2><b>Admin Dashboard</b></h2>
        </div>

        <div class="full-width-bar-sub">
            <div class="content-box-test" onclick="window.location.href='import.php'">
                <div class="icon-overlay">
                    <img src="images/file-regular.svg" alt="Icon">
                </div>
                <img class="background-image" src="images/blank-white-background.jpg" />
                <div class="large-text-sub">Import Files</div>
                <button class="arrow-button">→</button>
            </div>

            <div class="content-box-test" onclick="window.location.href='export.php'">
                <div class="icon-overlay">
                    <img src="images/file-regular.svg" alt="Icon">
                </div>
                <img class="background-image" src="images/blank-white-background.jpg" />
                <div class="large-text-sub">Export Files</div>
                <button class="arrow-button">→</button>
            </div>

            <div class="content-box-test" onclick="window.location.href='emailgen.php'">
                <div class="icon-overlay">
                    <img src="images/clipboard-regular.svg" alt="Icon">
                </div>
                <img class="background-image" src="images/blank-white-background.jpg" />
                <div class="large-text-sub">Generate Emails</div>
                <button class="arrow-button">→</button>
            </div>

            <div class="content-box-test" onclick="window.location.href='donationAddEdit.php'">
                <div class="icon-overlay">
                    <img src="images/clipboard-regular.svg" alt="Icon">
                </div>
                <img class="background-image" src="images/blank-white-background.jpg" />
                <div class="large-text-sub">Add/Edit Donations</div>
                <button class="arrow-button">→</button>
            </div>

            <div class="content-box-test" onclick="window.location.href='searchindex.php'">
                <div class="icon-overlay">
                    <img src="images/magnifying-glass.svg" alt="Icon">
                </div>
                <img class="background-image" src="images/blank-white-background.jpg" />
                <div class="large-text-sub">Search</div>
                <button class="arrow-button">→</button>
            </div>
        </div>
    <?php endif; ?>


    <?php if ($isVolunteer) : ?>

        <div style="position: absolute; top: 110px; right: 30px; z-index: 99; display: flex; flex-direction: row; gap: 30px; align-items: center; text-align: center;">
            <a href="selectVOTM.php" style="text-decoration: none;">
                <div style="font-size: 12px; font-weight: bold; color: #00447b; margin-bottom: 5px;">
                    🎖 Volunteer of the Month
                </div>
                <img src="images/star-icon.svg" alt="VOTM" style="width: 55px; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
            </a>

            <a href="leaderboard.php" style="text-decoration: none;">
                <div style="font-size: 12px; font-weight: bold; color: #00447b; margin-bottom: 5px;">
                    👑 Leaderboard
                </div>
                <img src="images/crown.png" alt="Leaderboard" style="width: 55px; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
            </a>
        </div>

        <div style="margin-top: 20px; padding: 30px 5%;">
            <h2><b>Welcome <?php echo htmlspecialchars($person->get_name()); ?>!</b> Let's get started.</h2>
        </div>

        <div class="full-width-bar">
            <div class="content-box">
                <img src="images/VolM.png" style="filter: contrast(150%);"/>
                <div class="small-text">Make a difference.</div>
                <div class="large-text">My Profile</div>
                <button class="circle-arrow-button" onclick="window.location.href='viewProfile.php'">
                    <span class="button-text">View</span>
                    <div class="circle">&gt;</div>
                </button>
            </div>

            <div class="content-box">
                <img src="images/EvM.png" />
                <div class="small-text">Let’s have some fun!</div>
                <div class="large-text">My Events</div>
                <button class="circle-arrow-button" onclick="window.location.href='viewAllEvents.php'">
                    <span class="button-text">Sign Up</span>
                    <div class="circle">&gt;</div>
                </button>
            </div>

            <div class="content-box">
                <img src="images/GrM.png" />
                <div class="small-text">Our team makes this possible.</div>
                <div class="large-text">My Group</div>
                <button class="circle-arrow-button" onclick="window.location.href='volunteerViewGroup.php'">
                    <span class="button-text">View</span>
                    <div class="circle">&gt;</div>
                </button>
            </div>
        </div>

        <div style="margin-top: 50px; padding: 0 5%;">
            <h2><b>Your Dashboard</b></h2>
        </div>

        <div class="full-width-bar-sub">
            <div class="content-box-test" onclick="window.location.href='viewResources.php'">
                <div class="icon-overlay"><img src="images/file-regular.svg"></div>
                <img class="background-image" src="images/blank-white-background.jpg" />
                <div class="large-text-sub">Documents</div>
                <div class="graph-text">View documents & handbook.</div>
                <button class="arrow-button">→</button>
            </div>

            <div class="content-box-test" onclick="window.location.href='viewDiscussions.php'">
                <div class="icon-overlay"><img src="images/clipboard-regular.svg"></div>
                <img class="background-image" src="images/blank-white-background.jpg" />
                <div class="large-text-sub">Discussions</div>
                <div class="graph-text">See the latest.</div>
                <button class="arrow-button">→</button>
            </div>

            <div class="content-box-test" onclick="window.location.href='inbox.php'">
                <div class="icon-overlay"><img src="images/<?php echo $inboxIcon ?? 'default-icon.png'; ?>"></div>
                <img class="background-image" src="images/blank-white-background.jpg" />
                <div class="large-text-sub">Notifications</div>
                <div class="graph-text">Stay up to date.</div>
                <button class="arrow-button">→</button>
            </div>
        </div>

    <?php endif; ?>

    <div style="width: 90%; height: 1px; background: #ddd; margin: 70px auto;"></div>

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

</body>
</html>