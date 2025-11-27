<?php
// Template for new VMS pages. Base your new page on this one

// Make session information accessible, allowing us to associate
// data with the logged-in user.
session_cache_expire(30);
session_start();

$loggedIn = false;
$accessLevel = 0;
$userID = null;
if (isset($_SESSION['_id'])) {
    $loggedIn = true;
    // 0 = not logged in, 1 = standard user, 2 = manager (Admin), 3 super admin (TBI)
    $accessLevel = $_SESSION['access_level'];
    $userID = $_SESSION['_id'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html">
    <title>Rappahannock CASA | Export</title>
    <link rel="stylesheet" href="css/base.css">

    <link rel="stylesheet" href="css/messages.css"></link>
    <script src="js/messages.js"></script>

    <style>
          html, body {
              height: 100%;
              margin: 0;
              padding: 0;
          }

          body {
              display: flex;
              flex-direction: column;
              min-height: 100vh;
          }

          main {
              flex: 1; /* pushes the footer down */
          }

          .footer {
              background-color: #333;
              color: #fff;
              text-align: center;
              padding: 20px 0;
          }

          /* This container holds the export boxes side-by-side */
          .export-container {
              display: flex;
              justify-content: center;
              align-items: flex-start;
              flex-wrap: wrap;
              gap: 30px;
              padding: 20px;
          }

          /* You will also need to copy the styles for
             .content-box-test, .icon-overlay, etc.
             from your import page's CSS file */

    </style>
</head>

<body>
    <?php require_once('header.php') ?>
    <h1 class="page-title">Export</h1>

    <main>
        <?php
        $status = -1;
        if (array_key_exists("success" , $_GET)){
            $status =$_GET["success"];
        }

        $message = "";
        if ($status == 0){
            $message = "There was a problem exporting the file";
        }
        else if ($status == 1){
            // This link logic assumes your ExportHandler/dbManager are working correctly
            $message = "The file was exported! It can be downloaded <a href = './python/exports/Export.xlsx' download='Export.xlsx'>here</a>!";
        }

        if ($status != -1){
								echo "<div style='text-align:center'>";
								echo $message;
								echo " </div>";
        }
        ?>

        <div class="export-container">
					<div style="display:flex;">
            <div class="full-width-bar-sub">
                <form
                    class="content-box-test"
                    action="python/ExportHandler.php"
                    method="post"
                >
                    <div class="icon-overlay">
                        <img style="border-radius: 5px;" src="images/file-regular.svg" alt="Export Donors Icon">
                    </div>
                    <img class="background-image" src="images/blank-white-background.jpg" />

                    <div class="large-text-sub">Export Donors</div>

                    <input type="hidden" name="export" value="donor">

                    <button type="submit" class="arrow-button">→</button>
                </form>
            </div>

            <div class="full-width-bar-sub">
                <form
                    class="content-box-test"
                    action="python/ExportHandler.php"
                    method="post"
                >
                    <div class="icon-overlay">
                         <img style="border-radius: 5px;" src="images/file-regular.svg" alt="Export Donations Icon">
                    </div>
                    <img class="background-image" src="images/blank-white-background.jpg" />

                    <div class="large-text-sub">Export Donations</div>

                    <input type="hidden" name="export" value="donation">

                    <button type="submit" class="arrow-button">→</button>
                </form>
            </div>
					</div>
        </div> </main>
<footer class="footer" style="margin-top: 100px;">
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
