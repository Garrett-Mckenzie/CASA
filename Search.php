<?php
require_once '/Applications/XAMPP/xamppfiles/htdocs/CASA/database/dbinfo.php';
session_start();
$con = connect();

if ($con) {
    $sql = "SELECT DISTINCT `location` FROM `dbevents` WHERE `location` IS NOT NULL AND `location` != '' ORDER BY `location` ASC";
    $result = mysqli_query($con, $sql);
    if ($result) {
        $locations = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $locations[] = $row['location'];
        }
        mysqli_free_result($result);
    }
    mysqli_close($con);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>

    <link rel="stylesheet" href="css/base.css">

</head>
<body>

    <?php require_once('header.php') ?>

    <h1 class="page-title">Event Location Search</h1>

    <main class="search-form">
        <p style="text-align: center;">Select a location to filter events from the database.</p>

        <form action="#" method="get">

            <label for="event-location">Select a Location</label>
            <select id="event-location" name="location" required>
                <option value="">-- Select a Location --</option>
                <?php foreach ($locations as $location):?>
                    <option value="<?php echo htmlspecialchars($location); ?>"> <?php echo htmlspecialchars($location); ?> </option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Search Events</button>

        </form>
    </main>

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