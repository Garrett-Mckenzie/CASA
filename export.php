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
    <link rel="stylesheet" href="css/messages.css"></link>
    <script src="js/messages.js"></script>
    <title>Rappahannock CASA | Export</title>
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
                    </style>
</head>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html">
        <!-- Popup for download link -->
        <script>
            async function handleExport(event){
                const sass = await import("https://jspm.dev/sass");
                event.preventDefault();

                const form = event.target;
                const formData = new FormData(form);

                const response = await fetch(form.action, {
                    method: form.method,
                    body: formData,
                });

                const result = await resposne.text();

                const popup = document.createElement('div');
                popup.style.position = 'fixed';
                popup.innerHTML = `
                    <p>${result}</p>
                    <button onclick="this.parentElement.remove()">Close</button> `;
                document.body.appendChild(popup);
            }

            /*
            document.addEventListener("DOMContentLoaded", () => {
                const params = new URLSearchParams(window.location.search);
                const success = params.get("success");
                const file = params.get("file");

                if(success === "1" && file){
                    alert(`Export complete. Download here: ${file}`);
                }
                else{
                    alert(`Failed to export.`);
                }
            });
            */
        </script>
    </head>
    <body>
        <?php require_once('header.php') ?>
        <h1>Export</h1>

        <div class="full-width-bar-sub">
                <div class="content-box-test" onclick="window.location.href=''">
                    <div class="icon-overlay">
                        <img style="border-radius: 5px;" src="images/file-regular.svg" alt="Document Icon">
                    </div>
                    <img class="background-image" src="images/blank-white-background.jpg" />
                    <div class="large-text-sub">Export Donor Information</div>
                    <button class="arrow-button">→</button>
                </div>


                <div class="content-box-test" onclick="window.location.href=''">
                    <div class="icon-overlay">
                        <img style="border-radius: 5px;" src="images/file-regular.svg" alt="Document Icon">
                    </div>
                    <img class="background-image" src="images/blank-white-background.jpg" />
                    <div class="large-text-sub">Export Donation Information</div>
                    <button class="arrow-button">→</button>
                </div>
        </div>

        <header>
            <nav>
                <table></table>
            </nav>
        </header>
        <main>
            <form action="ExportHandler.php" method="post" enctype="multipart/form-data">
                <table>
                    <tr>
                        <td class="info">Export Donor Information</td>
                        <td><button name="export" id="donor" type="submit">Download</button></td>
                    </tr>
                    <tr>
                        <td class="info">Export Donation Information</td>
                        <td><button name="export" id="donation" type="submit">Download</button></td>
                    </tr>
                </table>
            </form>
        </main>
    </body>
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