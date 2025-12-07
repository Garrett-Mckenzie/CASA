<?php
// Template for new VMS pages. Base your new page on this one

// Make session information accessible
session_cache_expire(30);
session_start();

$loggedIn = false;
$accessLevel = 0;
$userID = null;

if (isset($_SESSION['_id'])) {
    $loggedIn = true;
    // 0 = not logged in, 1 = standard user, 2 = manager (Admin), 3 = super admin (TBI)
    $accessLevel = $_SESSION['access_level'];
    $userID = $_SESSION['_id'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/yourkit.js" crossorigin="anonymous"></script> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <title>Import Donations</title>
    <link rel="icon" type="image/png" href="images/RAPPAHANNOCK_v_RedBlue2.png">

    <style>
        /* --- GLOBAL RESETS (Matched to Dashboard) --- */
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Quicksand', sans-serif;
            background-color: #f9f9f9;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        h2 {
            font-weight: normal;
            font-size: 30px;
            color: #333;
        }

        h3 {
            font-weight: 700;
            color: #00447b;
            margin-bottom: 15px;
        }

        /* --- LAYOUT CONTAINERS --- */
        .page-header {
            margin-top: 20px;
            padding: 30px 5%;
        }

        .full-width-bar {
            width: 100%;
            background: #00447b;
            padding: 40px 5%;
            display: flex;
            flex-direction: column;
            align-items: center;
            color: white;
            text-align: center;
        }

        .full-width-bar-sub {
            width: 100%;
            background: white;
            padding: 30px 5%;
            display: flex;
            justify-content: center;
            flex: 1; /* Pushes footer down */
        }

        /* --- CUSTOM FORM CARD --- */
        /* Based on .content-box-test but wider for forms */
        .form-card {
            background: white;
            width: 100%;
            max-width: 800px;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            position: relative;
            border: 1px solid #e0e0e0;
        }

        .file-input-wrapper {
            margin: 20px 0;
            padding: 20px;
            border: 2px dashed #00447b;
            border-radius: 8px;
            text-align: center;
            background-color: #f0f7ff;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .file-input-wrapper:hover {
            background-color: #e0efff;
        }

        input[type="file"] {
            font-family: 'Quicksand', sans-serif;
            width: 100%;
            padding: 10px;
        }

        /* --- BUTTONS --- */
        .btn-submit {
            background-color: #00447b;
            color: white;
            font-family: 'Quicksand', sans-serif;
            font-weight: 700;
            padding: 12px 30px;
            border: none;
            border-radius: 50px;
            font-size: 18px;
            cursor: pointer;
            transition: transform 0.2s, background 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-submit:hover {
            background-color: #0066cc;
            transform: translateY(-2px);
        }

        /* --- MESSAGES --- */
        .message-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 5px solid #ccc;
        }
        .message-error { border-left-color: #dc3545; color: #dc3545; }
        .message-success { border-left-color: #28a745; color: #155724; }

        .file-list {
            list-style: none;
            text-align: left;
            margin-top: 15px;
        }
        .file-list li {
            padding: 8px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
            color: #555;
        }


    </style>

    <script type="text/javascript">
        function displayImport(event){
            const fileList = event.target.files;
            const fileDisplay = document.getElementById('fileDisplay');
            fileDisplay.innerHTML = '';

            for(let i = 0; i < fileList.length; i++){
                const listItem = document.createElement('li');
                // Adding an icon for visual consistency
                listItem.innerHTML = '<i class="fas fa-file-csv" style="margin-right:10px; color:#00447b;"></i> ' + fileList[i].name;
                fileDisplay.appendChild(listItem);
            }
        }

        function validateFiles() {
            const input = document.querySelector('input[type="file"]');
            if (input.files.length === 0) {
                alert("Please select at least one file to import.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>

    <?php require_once('header.php') ?>

    <div class="page-header">
        <h2><b>Data Management</b></h2>
    </div>

    <div class="full-width-bar">
        <div style="max-width: 800px;">
            <i class="fas fa-cloud-upload-alt" style="font-size: 50px; margin-bottom: 20px; opacity: 0.8;"></i>
            <h1 style="font-weight: 700;">Import Files</h1>
            <p style="font-size: 18px; opacity: 0.9;">Upload CSV or Excel files to update the database.</p>
        </div>
    </div>

    <div class="full-width-bar-sub">
        <div class="form-card">

            <?php
            // Logic for status messages
            $status = -1;
            if (array_key_exists("success" , $_GET)){
                $status = $_GET["success"];
            }

            if ($status != -1) {
                $msgClass = ($status == 1) ? 'message-success' : 'message-error';
                $icon = ($status == 1) ? '<i class="fas fa-check-circle"></i>' : '<i class="fas fa-exclamation-triangle"></i>';

                echo '<div class="message-box ' . $msgClass . '">';
                echo '<h3>' . $icon . ' Import Report</h3>';

                if ($status == 0){
                    echo "There was a problem importing the file. No information has been saved.";
                } else if ($status == 1){
                    echo "<b>Import attempt complete!</b> Details below:<br/>";
                    if (isset($_SESSION["importStatus"])){
                        echo '<div style="margin-top:10px; font-size: 14px; color: #333;">';
                        foreach ($_SESSION["importStatus"] as $st){
                            echo $st . "<br/>";
                        }
                        echo '</div>';
                    } else {
                        echo 'Could not retrieve detailed report.';
                    }
                }
                echo '</div>';
            }
            ?>

            <h3 class="mb-3">Select Files</h3>
            <form action="python/ImportHandler.php" method="post" enctype="multipart/form-data" onsubmit="return validateFiles();">

                <div class="file-input-wrapper">
                    <input type="file" name="files[]" multiple onchange="displayImport(event)">
                    <p style="margin-top: 10px; color: #666; font-size: 14px;">(Supported: .csv, .xlsx)</p>
                </div>

                <ul id="fileDisplay" class="file-list"></ul>

                <div style="text-align: right; margin-top: 30px;">
                    <button type="submit" class="btn-submit">
                        Upload Data <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php require_once('footer.php'); ?>


</body>
</html>