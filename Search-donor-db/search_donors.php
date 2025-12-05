<?php
// search_multi_criteria.php

// 1. Include the database connection
require_once '../database/dbinfo.php'; 
header('Content-Type: application/json');

$con = connect();
$results = [];
$search_query_parts = [];
$where_clauses = [];

// Base SQL query selects donor details, plus a dummy donation_count for the frontend rendering logic
$sql = "SELECT d.id, d.first, d.last, d.email, d.city, d.state, 0 AS donation_count FROM donors d";

// Get selected query types and the associated values from the test.php frontend
$query_types = isset($_GET['query_types']) ? (array)$_GET['query_types'] : [];

// If no specific criteria are selected, the frontend defaults to 'all_donors'
if (empty($query_types)) {
    // If only 'all_donors' is selected, simply return all donors
    // The initial SQL is already set up for this, but we'll still add ORDER BY
    $sql .= " ORDER BY d.last, d.first";
} else {
    // --- Multi-Criteria Logic (Simplified for Name Search) ---
    
    // Example: Handle 'donor_name' criteria
    if (in_array('donor_name', $query_types) && isset($_GET['donor_name_query'])) {
        $name_query = trim($_GET['donor_name_query']);
        
        if (strlen($name_query) > 0) {
            $search_query_parts[] = "Donor Name: " . $name_query;

            // Split the query into individual words/names
            $search_terms = preg_split('/\s+/', $name_query, -1, PREG_SPLIT_NO_EMPTY);
            
            // Build the WHERE clause for name matching
            $name_sub_clauses = [];
            foreach ($search_terms as $term) {
                $safe_term = mysqli_real_escape_string($con, $term);
                // Broad search: first OR last name contains the term
                $name_sub_clauses[] = "(d.first LIKE '%$safe_term%' OR d.last LIKE '%$safe_term%')";
            }
            
            // IMPORTANT: Since the frontend states "Donors must match ALL selected criteria," 
            // we combine the terms for one criterion (Name) with OR logic, and separate
            // different criteria (Name, Zip, Date) with AND logic.
            if (!empty($name_sub_clauses)) {
                // All name parts are ORed together to match a full name
                $where_clauses[] = "(" . implode(" OR ", $name_sub_clauses) . ")";
            }
        }

    } 
    
    if (in_array('donor_events', $query_types) && isset($_GET['donor_name_events'])) {
        $event_query = trim($_GET['donor_name_events']);
        
        if (strlen($event_query) > 0) {
            $search_query_parts[] = "Event Query: " . $event_query;
            
            // Split the query into individual words/names
            $search_terms = preg_split('/\s+/', $event_query, -1, PREG_SPLIT_NO_EMPTY);
            
            // Build the WHERE clause for name matching
            $name_sub_clauses = [];
            foreach ($search_terms as $term) {
                $safe_term = mysqli_real_escape_string($con, $term);
                // Broad search: first OR last name contains the term
                $name_sub_clauses[] = "(d.name LIKE '%$safe_term%' OR d.id LIKE '%$safe_term%')";
            }
            
            // IMPORTANT: Since the frontend states "Donors must match ALL selected criteria," 
            // we combine the terms for one criterion (Name) with OR logic, and separate
            // different criteria (Name, Zip, Date) with AND logic.
            if (!empty($name_sub_clauses)) {
                // All name parts are ORed together to match a full name
                // take results and get Donor IDs from Donor table
                $where_clauses[] = "(" . implode(" OR ", $name_sub_clauses) . ")";
                $sql = "SELECT DISTINCT d.id FROM dbevents d JOIN associations don ON d.id = don.eventID JOIN donors e ON don.donorID = e.id";
            }
            

            
        } 
    } 
    
    if (in_array('event_donors', $query_types) && isset($_GET['donor_name_events'])) {
        $event_query = trim($_GET['donor_name_events']);
        
        if (strlen($event_query) > 0) {
            $search_query_parts[] = "Event Query: " . $event_query;
            
            // Split the query into individual words/names
            $search_terms = preg_split('/\s+/', $event_query, -1, PREG_SPLIT_NO_EMPTY);
            
            // Build the WHERE clause for name matching
            $name_sub_clauses = [];
            foreach ($search_terms as $term) {
                $safe_term = mysqli_real_escape_string($con, $term);
                // Broad search: first OR last name contains the term
                $name_sub_clauses[] = "(d.first LIKE '%$safe_term%' OR d.last LIKE '%$safe_term%')";
            }

            
            // IMPORTANT: Since the frontend states "Donors must match ALL selected criteria," 
            // we combine the terms for one criterion (Name) with OR logic, and separate
            // different criteria (Name, Zip, Date) with AND logic.
            if (!empty($name_sub_clauses)) {
                // All name parts are ORed together to match a full name
                // take results and get Donor IDs from Donor table
                $where_clauses[] = "(" . implode(" OR ", $name_sub_clauses) . ")";
                $sql = "SELECT DISTINCT d.id FROM donors d JOIN associations don ON d.id = don.donorID JOIN events e ON don.eventID = e.id";
            }
            

            
        }
    } 

    /*if (in_array('Thanked_donors', $query_types)) {
        $search_query_parts[] = "Thanked Donors"
        $subquery = "SELECT DISTINCT d.id FROM donors d JOIN associations don ON d.id = don.donorID JOIN events e ON don.eventID = e.id WHERE e.thanked = 1";
        $where_clauses[] = "d.id IN ({$subquery})";
    } */
    
    if (in_array('donor_zip', $query_types) && isset($_GET['zip_query'])) {
        $zip_query = trim($_GET['zip_query']);
        
        if (strlen($zip_query) > 0) {
            $search_query_parts[] = "Zip Code: " . $zip_query;
            $safe_zip = mysqli_real_escape_string($con, $zip_query);
            // Search for exact match of the zip code
            $where_clauses[] = "d.zip = '$safe_zip'";
        }
    }

    // New: Handle 'donors_by_donation_range_in' criteria 
    
    if (in_array('donors_by_donation_range_in', $query_types) 
        && isset($_GET['start_date_in'], $_GET['end_date_in'])) {
        
        $start_date = trim($_GET['start_date_in']);
        $end_date = trim($_GET['end_date_in']);

        if (strlen($start_date) > 0 && strlen($end_date) > 0) {
            $search_query_parts[] = "Donors with Donations Between {$start_date} and {$end_date}";
            
            $safe_start_date = mysqli_real_escape_string($con, $start_date);
            $safe_end_date = mysqli_real_escape_string($con, $end_date);

            // Subquery to find DISTINCT donor IDs that have a donation in the range
            $subquery = "SELECT DISTINCT donorID FROM donations 
                         WHERE STR_TO_DATE(date, '%m/%d/%Y') BETWEEN STR_TO_DATE('$safe_start_date', '%Y-%m-%d') AND STR_TO_DATE('$safe_end_date', '%Y-%m-%d')";

            // Add the condition to the WHERE clauses of the main query
            // This allows it to combine with other criteria like 'donor_name'
            $where_clauses[] = "d.id IN ({$subquery})";

            // Note: The main SQL will remain the base SELECT and the donation_count 
            // will default to 0, but this is acceptable for a donor list search.
        } 
    // New: Handle 'donors_by_donation_range_not_in' criteria
    } 
    
    if (in_array('donors_by_donation_range_not_in', $query_types) 
        && isset($_GET['start_date_not_in'], $_GET['end_date_not_in'])) {
        
        $start_date = trim($_GET['start_date_not_in']);
        $end_date = trim($_GET['end_date_not_in']);
        
        if (strlen($start_date) > 0 && strlen($end_date) > 0) {
            $search_query_parts[] = "Donors with NO Donations Between {$start_date} and {$end_date}";
            
            $safe_start_date = mysqli_real_escape_string($con, $start_date);
            $safe_end_date = mysqli_real_escape_string($con, $end_date);

            // Find all donor IDs that have a donation in the range
            $subquery = "SELECT DISTINCT donorID FROM donations 
                         WHERE STR_TO_DATE(date, '%m/%d/%Y') BETWEEN STR_TO_DATE('$safe_start_date', '%Y-%m-%d') AND STR_TO_DATE('$safe_end_date', '%Y-%m-%d')";

            // Select donors whose ID is NOT in the subquery result
            $where_clauses[] = "d.id NOT IN ({$subquery})";
        }
    
    }
    
    // 2. Combine all separate criteria (e.g., Name AND Zip AND Date) with 'AND'
    if (!empty($where_clauses)) {
        $sql .= " WHERE " . implode(" AND ", $where_clauses);
    }
    
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