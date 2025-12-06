<?php
    // Template for new VMS pages. Base your new page on this one

    // Make session information accessible, allowing us to associate
    // data with the logged-in user.
    session_cache_expire(30);
    session_start();
    date_default_timezone_set("America/New_York");

    $loggedIn = false;
    $accessLevel = 0;
    $userID = null;
    if (isset($_SESSION['_id'])) {
        $loggedIn = true;
        // 0 = not logged in, 1 = standard user, 2 = manager (Admin), 3 super admin (TBI)
        $accessLevel = $_SESSION['access_level'];
        $userID = $_SESSION['_id'];
    }
    if ($accessLevel < 1) {
        header('Location: login.php');
        die();
    }

?>


<!DOCTYPE html>
<html>
    <head>
        <?php require_once('universal.inc');?>
        <?php require_once('database/dbEvents.php');?>
        <?php require_once('database/dbDonations.php');?>
        <?php require_once('database/dbPersons.php');
            require_once('include/input-validation.php');  
            $get = sanitize($_GET);
            $event = retrieve_event2($get["id"])
        ?>
        
        
        <title>Rappahannock CASA | <?php echo $event["name"];?></title>
    </head>
    <body>
        <?php require_once('header.php') ?>

        <h1> <?php echo $event["name"];?></h1>
        <main class="general">
            <?php
                //event attributes
                $goal = $event["goalAmount"];
                $desc = $event["description"];

                $donations = fetch_donations_for_event($get["id"]);
                $totalRaised = 0;
                if ($donations){
                    foreach ($donations as $donation){
                        $totalRaised += $donation["amount"];
                    }
                    $safeGoal = ($goal == 0) ? 1: $goal;
                    $completion = round($totalRaised/$safeGoal,2)*100;
                }
                else{
                    $completion = 0;
                }

                $completed = ($completion>=100)? 1:0;
            ?>
            <div style="
                display: flex; 
                justify-content: center;
                align-items: center;
                gap: 40px;
                margin: 20px auto;
                width: 100%;
                max-width: 900px;
            ">
                <p><?php echo $desc; ?></p>
            </div>
            <div style="
                display: flex; 
                justify-content: center;
                align-items: center;
                gap: 40px;
                margin: 20px auto;
                width: 100%;
                max-width: 900px;
            ">
                <!-- LEFT: EVENT DETAILS TABLE -->
                <table style="border-collapse: collapse; min-width: 350px;">
                    <tr>
                        <th style="text-align: left; padding: 10px; border: 1px solid #ddd;">Status</th>
                        <td style="padding: 10px; border: 1px solid #ddd;">
                            <?php echo ($completed ? "Completed" : "Incomplete"); ?>
                        </td>
                    </tr>
                    <tr style="background-color: #f2f2f2;">
                        <th style="text-align: left; padding: 10px; border: 1px solid #ddd;">Goal Amount</th>
                        <td style="padding: 10px; border: 1px solid #ddd;">$<?php echo $goal; ?></td>
                    </tr>
                    <tr>
                        <th style="text-align: left; padding: 10px; border: 1px solid #ddd;">Total Donations</th>
                        <td style="padding: 10px; border: 1px solid #ddd;">$<?php echo $totalRaised; ?></td>
                    </tr>
                    <tr style="background-color: #f2f2f2;">
                        <th style="text-align: left; padding: 10px; border: 1px solid #ddd;">Completion</th>
                        <td style="padding: 10px; border: 1px solid #ddd;"><?php echo $completion; ?>%</td>
                    </tr>
                </table>

                <!-- RIGHT: PIE CHART -->
                <div style="
                    display: flex; 
                    justify-content: center; 
                    align-items: center;
                    width: 300px; 
                    height: 300px;
                ">
                    <canvas id="progressChart" width="300" height="300"></canvas>
                </div>

            </div>

            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

            <script>
                // ---- PHP VALUES (Use 'let' as values may be modified for calculation) ----
                let raised = <?php echo $totalRaised; ?>;
                let goal = <?php echo $goal; ?>;

                // ---- CHART DATA LOGIC ----
                let data = [];
                let labels = [];
                let colors = [];
                let percent = 0; // Initialize percent

                // --- Goal Safety Check & Percentage Calculation ---
                if (goal <= 0) {
                    if (raised > 0) {
                        percent = Math.round(raised); // If raised is 100, percent is 100. If raised is 150, percent is 150.
                        let denominator = 1; // A safe, non-zero denominator
                        percent = Math.round(100 + raised); // This ensures 100% + $1 per raised dollar. (This is highly dependent on how you want the scale to look)
                        let safeGoal = 1; 
                        
                        // If raised > 0, we calculate how many times the raised amount is over the minimum $1 goal
                        percent = Math.round((raised / safeGoal) * 100); 
                        if (raised > 0) {
                            percent = 100;
                        } else {
                            percent = 100; // Nothing raised, so 0% progress shown.
                        }
                    }
                } else {
                    percent = Math.round((raised / goal) * 100);
                }

            // Determine overflow categories
            if (raised <= goal) {
                // No overflow
                data = [raised, goal - raised];
                labels = ['Raised', 'Remaining'];
                colors = ['#4CAF50', '#e0e0e0']; // green + gray
            } else {
                const overflow = raised - goal;

                data = [goal, overflow];
                labels = ['Goal Reached', 'Overflow'];

                // Overflow color changes with severity
                let overflowColor = "#FF9800";      // mild overflow = orange
                if (percent >= 150) overflowColor = "#ff5722";   // medium overflow = deeper orange/red
                if (percent >= 200) overflowColor = "#d32f2f";   // extreme overflow = red

                colors = ['#4CAF50', overflowColor];
            }

            // ---- CHART.JS PLUGIN: CENTER TEXT ----
            const centerText = {
                id: 'centerText',
                afterDraw(chart) {
                    const { ctx, chartArea: { width, height } } = chart;
                    ctx.save();
                    ctx.font = 'bold 28px Arial';
                    ctx.fillStyle = '#333';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.fillText(percent + '%', width / 2, height / 2);
                    ctx.restore();
                }
            };

            // ---- CHART CREATION ----
            const ctx = document.getElementById('progressChart');

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: colors,
                        borderWidth: 1,
                        hoverOffset: 15   // gives animation on hover (pop out)
                    }]
                },
                options: {
                    cutout: '65%',  // doughnut hole size
                    animation: {
                        animateRotate: true,
                        animateScale: true,
                        duration: 1200
                    },
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.label + ": " + context.raw;
                                }
                            }
                        }
                    }
                },
                plugins: [centerText]
            });
            </script>



            <form action="deleteEvent.php" method="GET">
                <input type="hidden" name="id" value=<?php echo $event['id'];?>>
                <button 
                    type="submit" 
                    onclick="return confirm('Are you sure you want to delete this event? This action cannot be undone.');"
                    onmouseover="this.style.backgroundColor='#a50f1e';"
                    onmouseout="this.style.backgroundColor='#d11a2a';"
                    style="
                        background-color:#d11a2a;
                        color:white;
                        border:none;
                        padding:10px 18px;
                        border-radius:6px;
                        cursor:pointer;
                        font-weight:bold;
                    "
                >
                    Delete
                </button>
            </form>

        </main>
    

    </body>
</html>