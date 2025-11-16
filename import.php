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
        <link rel="stylesheet" href="css/base.css">
        <link rel="stylesheet" href="css/messages.css"></link>
        <script src="js/messages.js"></script>
        <title>Rappahannock CASA | Import</title>
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
                </style>
        <script type="text/javascript">

            var files;
            function displayImport(event){
                files = event.target.files;
                const fileList = event.target.files;
                const fileDisplay = document.getElementById('fileDisplay');
                fileDisplay.innerHTML = '';

                for(let i = 0; i < fileList.length; i++){
                    const listItem = document.createElement('li');
                    listItem.textContent = fileList[i].name;
                    fileDisplay.appendChild(listItem);
                }
            }
        </script>
    </head>
    <body>
        <?php require_once('header.php') ?>
        <h1 class="page-title">Import</h1>
            <?php
		        $status = -1;
		        if (array_key_exists("success" , $_GET)){
						$status =$_GET["success"];	
		        }

		        $message = "";
		        if ($status == 0){
						$message = "There was a problem importing the file no information has been put into the storage system.";
		        }
		        else if ($status == 1){
						$message = "Import attempt has been made! The only information/files that have not been imported are mentioned below.";
		        }
		
		        if ($status == 1){
		        	echo "<div><b>".$message."</b></div>";
		        	$message = "";
                    if (isset($_SESSION["importStatus"])){
                                foreach ($_SESSION["importStatus"] as $st){
											$message = $message.$st."<br/>";
							}
							echo "<div>".$message."</div>";
			    }
			    else{
				    echo "<div>Could not retrieve import report</div>";
			    }
		        }
                 ?>
                <main>
                    <div class="full-width-bar-sub">
                        <form
                            class="content-box-test"
                            action="python/ImportHandler.php"
                            method="post"
                            enctype="multipart/form-data"
                            onsubmit="return validateFiles();"
                        >
                            <div class="icon-overlay">
                                <img style="border-radius: 5px;" src="images/file-regular.svg" alt="Document Icon">
                            </div>
                            <img class="background-image" src="images/blank-white-background.jpg" />

                            <div class="large-text-sub">Import Files</div>

                            <input
                                type="file"
                                name="files[]"
                                multiple
                                onchange="displayImport(event)"
                                class="file-input"
                                style="margin-top: 10px;"
                            >
                            <ul id="fileDisplay" class="display"></ul>

                            <button type="submit" class="arrow-button">→</button>
                        </form>
                    </div>
                </main>
        <footer class="footer" style="footer">
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
    </body>
</html>
