<?php
    session_cache_expire(30);
    session_start();
    $errorCode = $_GET['e'] ?? 0;
    $edit = $_GET['edit'] ?? 0;
    $id = $_GET['id'] ?? 0;

    if ($errorCode == 1) {
        $msg = "Missing Required Form Data";
    } elseif ($errorCode == 2) {
        $msg = "Bad Time Range: End is before Start";
    } elseif ($errorCode == 3) {
        $msg = "Missing Date or Time Form Data";
    } else {
        $msg = "Unexpected Error Raised — Contact UMW Software Engineering";
    }

    //where to redirect
    if ($edit == 1) {
        if ($id ==0){
            header("refresh:3;url=viewAllEvents.php");
        }
        else{
            header("refresh:3;url=editEvent.php?id=".$id); #these need to be above any html that echos to screen
        }
    } 
    else {
        header("refresh:3;url=addEvent.php");
    }
?>
    <!DOCTYPE html>
    <html>
        <head>
            <?php require_once('universal.inc') ?>
            <title>Rappahannock CASA | Fundraisers</title>
            <style>
            .error-banner {
                width: 100%;
                background: #c62828;               /* strong red */
                color: white;
                padding: 18px 22px;
                font-size: 20px;
                font-weight: bold;
                text-align: center;
                border-bottom: 4px solid #8e0000;  /* deeper red for effect */
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
