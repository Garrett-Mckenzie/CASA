<?php
// search_multi_criteria.php - Corrected Implementation

// 1. Include the database connection
require_once '../database/dbinfo.php'; 
header('Content-Type: application/json');

$con = connect();
$results = [];
$where_clauses = [];
$join_clauses = [];

// Get selected query types and the associated values from the test.php frontend
$query_types = isset($_GET['query_types']) ? (array)$_GET['query_types'] : [];

// Check if only 'all_donors' or no query types were selected (default to showing all donors)
$is_all_donors_query = empty(array_filter($query_types)) || (count($query_types) === 1 && in_array('all_donors', $query_types));

// Check for the specific event lookup query requested by the user
$is_donor_events_lookup = in_array('donor_events', $query_types) && isset($_GET['donor_name_query_events']) && strlen(trim($_GET['donor_name_query_events'])) > 0;

// --- STEP 1: Determine the base SELECT statement ---
if ($is_donor_events_lookup && !$is_all_donors_query) {
    // If querying by Donor Events, select DISTINCT event details (Aliasing columns for frontend compatibility)
    $sql = "SELECT DISTINCT e.id, e.name AS first, e.name AS last, e.date AS email, e.goalAmount AS city, e.date AS state, e.name AS event_name FROM dbevents e";
} else {
    // Default: Select Donor details
    $sql = "SELECT d.id, d.first, d.last, d.email, d.city, d.state, 0 AS donation_count FROM donors d";
}


// --- STEP 2: Build JOIN and WHERE clauses for selected criteria ---

if ($is_all_donors_query) {
    // If it's just an 'all donors' request, bypass complex filtering
    // The base SQL for donors is already set. We just need to order it.
} else {
    // --- A. Handle Donor Name filter for an Event Lookup (primary request) ---
    if ($is_donor_events_lookup) {
        $donor_name_query = trim($_GET['donor_name_query_events']);
        
        // Add the necessary JOINs to link events (e) to the donors table (d) via donations (dn)
        $join_clauses[] = "INNER JOIN donations dn ON e.id = dn.eventID";
        $join_clauses[] = "INNER JOIN donors d ON dn.donorID = d.id";
        
        // Build the WHERE clause for matching the donor name
        if (strlen($donor_name_query) > 0) {
            $search_terms = preg_split('/\s+/', $donor_name_query, -1, PREG_SPLIT_NO_EMPTY);
            $name_sub_clauses = [];
            foreach ($search_terms as $term) {
                $safe_term = mysqli_real_escape_string($con, $term);
                // Search donor name in the joined 'd' table
                $name_sub_clauses[] = "(d.first LIKE '%$safe_term%' OR d.last LIKE '%$safe_term%')";
            }
            if (!empty($name_sub_clauses)) {
                $where_clauses[] = "(" . implode(" OR ", $name_sub_clauses) . ")";
            }
        }
    }
    
    // --- B. Handle simple Donor Name filter (if only this was selected) ---
    elseif (in_array('donor_name', $query_types) && isset($_GET['donor_name_query'])) {
        $name_query = trim($_GET['donor_name_query']);
        
        if (strlen($name_query) > 0) {
            $search_terms = preg_split('/\s+/', $name_query, -1, PREG_SPLIT_NO_EMPTY);
            $name_sub_clauses = [];
            foreach ($search_terms as $term) {
                $safe_term = mysqli_real_escape_string($con, $term);
                // Search donor name in the base 'd' table
                $name_sub_clauses[] = "(d.first LIKE '%$safe_term%' OR d.last LIKE '%$safe_term%')";
            }
            if (!empty($name_sub_clauses)) {
                $where_clauses[] = "(" . implode(" OR ", $name_sub_clauses) . ")";
            }
        }
    }
    
    // --- C. Handle Event Donors filter (Find Donors by Event Name/ID) ---
    elseif (in_array('event_donors', $query_types) && isset($_GET['event_query'])) {
        $event_query = trim($_GET['event_query']);
        
        if (strlen($event_query) > 0) {
            // Add the necessary JOINs to link donors (d) to events (e)
            $join_clauses[] = "INNER JOIN donations dn ON d.id = dn.donorID";
            $join_clauses[] = "INNER JOIN dbevents e ON dn.eventID = e.id";
            
            $safe_event_query = mysqli_real_escape_string($con, $event_query);
            
            if (is_numeric($event_query)) {
                $where_clauses[] = "(e.id = '$safe_event_query')";
            } else {
                $where_clauses[] = "(e.name LIKE '%$safe_event_query%')";
            }
        }
    }
    // Note: Other criteria (zip, date ranges) would be implemented here with AND logic
}

// --- STEP 3: Combine SQL parts and Execute ---

// Apply joins right after the FROM clause
if (!empty($join_clauses)) {
    $sql .= " " . implode(" ", $join_clauses);
}

// Apply WHERE clause
if (!empty($where_clauses)) {
    // Combine all different criteria (A, B, C, etc.) with 'AND' logic
    $sql .= " WHERE " . implode(" AND ", $where_clauses);
}

// Add ORDER BY
if ($is_donor_events_lookup && !$is_all_donors_query) {
     $sql .= " ORDER BY e.name";
} else {
    $sql .= " ORDER BY d.last, d.first";
}

// Execute the final query
$result = mysqli_query($con, $sql);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $results[] = $row;
    }
    mysqli_free_result($result);
} else {
    // Handle SQL error
    http_response_code(500);
    die(json_encode(["error" => "SQL query failed.", "details" => mysqli_error($con), "query" => $sql]));
}

mysqli_close($con);

// Return results as JSON
echo json_encode($results);
?>