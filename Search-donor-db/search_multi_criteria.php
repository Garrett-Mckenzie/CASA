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

    } elseif (in_array('donor_events', $query_types) && isset($_GET['donor_name_events'])) {
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

            foreach ($name_sub_clauses as $term){
                

                
            }
            
            // IMPORTANT: Since the frontend states "Donors must match ALL selected criteria," 
            // we combine the terms for one criterion (Name) with OR logic, and separate
            // different criteria (Name, Zip, Date) with AND logic.
            if (!empty($name_sub_clauses)) {
                // All name parts are ORed together to match a full name
                $where_clauses[] = "(" . implode(" OR ", $name_sub_clauses) . ")";
            }
            // take results and get Donor IDs from Donor table

        }
    }
    
    // --- Add more 'if' blocks here for other criteria (e.g., 'donor_zip', 'event_donors') ---
    
    // 2. Combine all separate criteria (e.g., Name AND Zip AND Date) with 'AND'
    if (!empty($where_clauses) && !empty($query_types)) {
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