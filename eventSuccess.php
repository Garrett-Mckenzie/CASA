<?php
    session_cache_expire(30);
    session_start();

    $code = $_GET['edit'] ?? 0;

    if ($code == 1) {
        $msg = "Successfully Edited Fundraiser Event";
        header("refresh:2;url=viewAllEvents.php"); #these need to be above any html that echos to screen
    } 
    else {
        $msg = "Successfully Created Fundraiser Event";
        header("refresh:2;url=addEvent.php");
    }
?>

    <!DOCTYPE html>
    <html>
        <head>
            <?php require_once('universal.inc') ?>
            <title>Rappahannock CASA | Create Event</title>
            <style>
            .error-banner {
                width: 100%;
                background: #3ac628ff;               
                color: white;
                padding: 18px 22px;
                font-size: 20px;
                font-weight: bold;
                text-align: center;
                border-bottom: 4px solid #1c8e00ff;  
                box-shadow: 0 3px 8px rgba(0,0,0,0.25);
                margin-bottom: 20px;
            }
            </style>
        </head>
        <body>
            <?php 
                require_once('header.php');
            ?>
            <h1>Fundraisers</h1>
            <div class="error-banner">
                <?= htmlspecialchars($msg) ?>
            </div>
        </body>
    <footer class="footer">
            <!-- Left Side: Logo & Socials -->
            <div class="footer-left">
                <img src="images/RAPPAHANNOCK_v_White-300x300.png" alt="Logo" class="footer-logo">
                <div class="social-icons">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>

            <!-- Right Side: Page Links -->
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
    </html>