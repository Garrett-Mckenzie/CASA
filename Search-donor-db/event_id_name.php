<?php
// Set the content type header to application/json for API response
header('Content-Type: application/json');

// Include the database connection file
require_once 'dbinfo.php';

// Check if the 'event' parameter is provided in the GET request
if (!isset($_GET['event']) || empty($_GET['event'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: Missing "event" parameter. Please provide an event ID or name.'
    ]);
    exit();
}

// Get the event identifier from the request
$event_identifier = $_GET['event'];

// Establish a database connection
$con = connect();

// Check if connection was successful (connect() returns the connection object or an error string)
if (!is_object($con)) {
    // If connection failed, return a JSON error response
    echo json_encode([
        'success' => false,
        'message' => 'Database connection error: ' . $con
    ]);
    exit();
}

// --- Determine search mode (ID or Name) and build the query ---
$is_numeric = is_numeric($event_identifier);

// SQL to join donors, donations, and events, and retrieve donor details and donation info.
// We use DISTINCT because a single donor might have multiple donations to the same event, 
// but we want their name and details once per donor for the event list.
$sql = "
SELECT 
    d.first,
    d.last,
    d.email,
    d.phone,
    d.city,
    d.state,
    o.amount,
    o.date,
    o.reason
FROM donors d
INNER JOIN donations o ON d.id = o.donorID
INNER JOIN dbevents e ON o.eventID = e.id
WHERE ";

if ($is_numeric) {
    // Search by Event ID
    $sql .= "e.id = ?";
    $param_type = "i"; // integer
} else {
    // Search by Event Name (using LIKE for partial matching)
    $sql .= "e.name LIKE ?";
    $param_type = "s"; // string
    // Prepare the identifier for a fuzzy search on the event name
    $event_identifier = "%" . $event_identifier . "%"; 
}

// --- Execute the prepared statement ---
$stmt = mysqli_prepare($con, $sql);

if ($stmt === false) {
    echo json_encode([
        'success' => false,
        'message' => 'Error preparing statement: ' . mysqli_error($con)
    ]);
    mysqli_close($con);
    exit();
}

// Bind the parameter
mysqli_stmt_bind_param($stmt, $param_type, $event_identifier);

// Execute the statement
mysqli_stmt_execute($stmt);

// Get the result set
$result = mysqli_stmt_get_result($stmt);

// Initialize the final results array for donors
$donors = [];
$total_donations = 0.0;

// Fetch results and build the array. 
// Note: This collects every donation. If we strictly wanted unique *donors*, we would need
// a different structure or group by donor ID in the SQL and use aggregate functions (SUM).
// Given the original structure, we compile a list of donation records associated with donors.
while ($row = mysqli_fetch_assoc($result)) {
    $amount = (float)($row['amount'] ?? 0.00);

    // Format the donor/donation entry
    $donor_entry = [
        'name' => trim($row['first'] . ' ' . $row['last']),
        'email' => $row['email'] ?? 'N/A',
        'phone' => $row['phone'] ?? 'N/A',
        'location' => trim(($row['city'] ?? '') . (($row['city'] && $row['state']) ? ', ' : '') . ($row['state'] ?? '')),
        'donation_amount' => $amount,
        'donation_date' => $row['date'] ?? 'N/A',
        'donation_reason' => $row['reason'] ?? 'General'
    ];
    $donors[] = $donor_entry;
    $total_donations += $amount;
}

// Close resources
mysqli_stmt_close($stmt);
mysqli_close($con);

// Prepare the final output array structure
$results = [
    'success' => true,
    'event_query' => $_GET['event'],
    'donor_records_count' => count($donors), // Renamed for clarity since this counts records, not unique donors
    'total_amount_donated' => number_format($total_donations, 2, '.', ''), // formatted as string for consistency
    'donors_and_donations' => $donors // Renamed the key to reflect that it contains donation records
];

// --- Output the final JSON response ---
if (count($donors) == 0) {
    // Override message for clarity if no records found
    $results['message'] = 'No donation records found for the provided event ID or name.';
    $results['donors_and_donations'] = [];
}

echo json_encode($results);

?>