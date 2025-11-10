<?php
// Include the database connection file
require_once 'dbinfo.php';

// Connect to the database
$con = connect();

// Set up HTML structure
echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Donors List</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f7f6; color: #333; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); }
        h1 { color: #007bff; text-align: center; margin-bottom: 25px; }
        ul { list-style: none; padding: 0; }
        li { padding: 10px 0; border-bottom: 1px dashed #e0e0e0; font-size: 1.1em; }
        li:last-child { border-bottom: none; }
        .error { color: #dc3545; font-weight: bold; text-align: center; }
    </style>
</head>
<body>
<div class="container">

        <!-- Link Button to test.php -->
        <div class="mt-4">
            <a href="index.php"
                class="w-full flex justify-center py-3 px-4 border border-gray-300 rounded-lg shadow-md text-base font-bold text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition duration-200 ease-in-out transform hover:scale-[1.01] active:scale-[0.99]">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                Go to main Page
            </a>
        </div>

        <!-- Link Button to test.php -->
        <div class="mt-4">
            <a href="test.php"
                class="w-full flex justify-center py-3 px-4 border border-gray-300 rounded-lg shadow-md text-base font-bold text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition duration-200 ease-in-out transform hover:scale-[1.01] active:scale-[0.99]">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                Go to test Page
            </a>
        </div>
    <h1>Donor Directory</h1>';
    

if (is_string($con) && $con != '') {
    // If connect() returns a string, it's an error message.
    echo '<p class="error">Database Connection Error: ' . htmlspecialchars($con) . '</p>';
} else if ($con === false) {
     // Generic connection failure catch
    echo '<p class="error">Failed to connect to the database. Check credentials in dbinfo.php.</p>';
} else {
    // Connection successful, proceed to query
    
    // SQL query to select the first and last name of all donors
    // The query is ordered by last name for better readability
    $sql = "SELECT first, last FROM donors ORDER BY last, first";

    $result = mysqli_query($con, $sql);

    if ($result) {
        // Check if any rows were returned
        if (mysqli_num_rows($result) > 0) {
            echo '<ul>';
            // Output data of each row
            while($row = mysqli_fetch_assoc($result)) {
                // Display the donor\'s first name followed by their last name
                echo '<li>' . htmlspecialchars($row["first"]) . ' ' . htmlspecialchars($row["last"]) . '</li>';
            }
            echo '</ul>';
        } else {
            echo '<p>No donors found in the database.</p>';
        }
        
        // Free result set
        mysqli_free_result($result);

    } else {
        // Query failed
        echo '<p class="error">Error executing query: ' . mysqli_error($con) . '</p>';
    }

    // Close the database connection
    mysqli_close($con);
}

echo '</div>
</body>
</html>';


?>