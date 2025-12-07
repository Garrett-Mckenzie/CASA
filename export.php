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
    // 0 = not logged in, 1 = standard user, 2 = manager (Admin), 3 super admin (TBI)
    $accessLevel = $_SESSION['access_level'];
    $userID = $_SESSION['_id'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rappahannock CASA | Export</title>

    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/yourkit.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" href="images/RAPPAHANNOCK_v_RedBlue2.png">

    <style>
        /* --- GLOBAL RESETS --- */
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Quicksand', sans-serif;
            background-color: #f9f9f9;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        h2 { font-weight: normal; font-size: 30px; color: #333; }
        h3 { font-weight: 700; color: #00447b; margin-bottom: 15px; }

        /* --- LAYOUT --- */
        .page-header { margin-top: 20px; padding: 30px 5%; }

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
            padding: 50px 5%;
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1; /* Pushes footer down */
        }

        /* --- EXPORT CARDS (Matching Dashboard) --- */
        .card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 40px;
            width: 100%;
            max-width: 900px;
        }

        /* Styling the form as a card */
        .export-card {
            flex: 1 1 300px; /* Responsive width */
            max-width: 350px;
            padding: 20px;
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
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        .export-card:hover {
            border-color: #00447b;
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 68, 123, 0.15);
        }

        /* Background image styling inside card */
        .export-card img.background-image {
            width: 100%;
            border-radius: 8px;
            opacity: 0.1; /* Faded effect */
            height: 150px;
            object-fit: cover;
        }

        /* Circular Icon Overlay */
        .icon-overlay {
            position: absolute;
            top: 25%; /* Adjusted position */
            left: 50%;
            transform: translateX(-50%);
            width: 70px;
            height: 70px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            z-index: 2;
            color: #00447b;
            font-size: 30px;
        }

        .large-text-sub {
            font-size: 22px;
            font-weight: 700;
            color: #333;
            margin-top: 20px;
            z-index: 2;
        }

        /* Arrow Button */
        .arrow-button {
            margin-top: 15px;
            background: none;
            border: none;
            font-size: 28px;
            color: #00447b;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .export-card:hover .arrow-button {
            transform: translateX(10px);
            color: #0066cc;
        }

        /* --- MESSAGES --- */
        .message-box {
            width: 100%;
            max-width: 700px;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 40px;
            text-align: center;
            font-size: 18px;
        }
        .message-error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .message-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .message-success a { font-weight: bold; color: #155724; text-decoration: underline; }
    </style>
</head>

<body>
    <?php require_once('header.php') ?>

    <div class="page-header">
        <h2><b>Data Management</b></h2>
    </div>

    <div class="full-width-bar">
        <div style="max-width: 800px;">
            <i class="fas fa-file-export" style="font-size: 50px; margin-bottom: 20px; opacity: 0.8;"></i>
            <h1 style="font-weight: 700;">Export Data</h1>
            <p style="font-size: 18px; opacity: 0.9;">Download database records to Excel.</p>
        </div>
    </div>

    <main class="full-width-bar-sub">

        <?php
        $status = -1;
        if (array_key_exists("success" , $_GET)){
            $status = $_GET["success"];
        }

        if ($status != -1){
            if ($status == 0){
                echo '<div class="message-box message-error">';
                echo '<i class="fas fa-exclamation-triangle"></i> There was a problem exporting the file.';
                echo '</div>';
            }
            else if ($status == 1){
                echo '<div class="message-box message-success">';
                echo '<i class="fas fa-check-circle"></i> The file was exported successfully! <br>';
                echo 'Your download should begin shortly, or <a href="./python/exports/Export.xlsx" download="Export.xlsx">Click Here</a>.';
                echo '</div>';
            }
        }
        ?>

        <div class="card-container">

            <form class="export-card" action="python/ExportHandler.php" method="post" onclick="this.submit();">
                <div class="icon-overlay">
                    <i class="fas fa-users"></i>
                </div>
                <img class="background-image" src="images/blank-white-background.jpg" alt="Background" />

                <div class="large-text-sub">Export Donors</div>
                <div style="color:#666; font-size: 14px; margin-top:5px;">Full list of registered donors</div>

                <input type="hidden" name="export" value="donor">
                <button type="submit" class="arrow-button"><i class="fas fa-arrow-right"></i></button>
            </form>

            <form class="export-card" action="python/ExportHandler.php" method="post" onclick="this.submit();">
                <div class="icon-overlay">
                    <i class="fas fa-hand-holding-dollar"></i>
                </div>
                <img class="background-image" src="images/blank-white-background.jpg" alt="Background" />

                <div class="large-text-sub">Export Donations</div>
                <div style="color:#666; font-size: 14px; margin-top:5px;">Detailed transaction history</div>

                <input type="hidden" name="export" value="donation">
                <button type="submit" class="arrow-button"><i class="fas fa-arrow-right"></i></button>
            </form>

        </div>
    </main>
    <?php require_once('footer.php'); ?>

</body>
</html>