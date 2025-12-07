<?php
require_once 'database/dbinfo.php'; // Include database info (likely for connection check, though not used here)

session_start(); // Start PHP session (not used in the search logic)
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
    <title>Rappahannock CASA | Donor Search</title>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/yourkit.js" crossorigin="anonymous"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Get all query type checkboxes
            const queryTypeCheckboxes = document.querySelectorAll('input[name="query_type"]');
            // Get all input containers, grouped by data-query-type
            const formContainers = document.querySelectorAll('.query-input-container');
            const searchForm = document.getElementById('search-form');
            const resultsContent = document.getElementById('results-content');

            // Function to dynamically show/hide input fields based on checkbox selection
            function updateFormVisibility() {
                // Get a list of the values of all currently checked checkboxes
                const selectedQueryTypes = Array.from(queryTypeCheckboxes)
                    .filter(checkbox => checkbox.checked)
                    .map(checkbox => checkbox.value);

                // If no criteria are selected, default to 'all_donors' for visibility logic
                if (selectedQueryTypes.length === 0) {
                    selectedQueryTypes.push('all_donors');
                }

                // Iterate through all input containers
                formContainers.forEach(container => {
                    const containerType = container.getAttribute('data-query-type');

                    // Check if the container's type is in the list of selected criteria
                    if (selectedQueryTypes.includes(containerType)) {
                        // Show the container and apply styling
                        container.classList.remove('hidden');
                        container.classList.add('space-y-4');
                        // Set 'required' attribute for inputs inside the active container
                        container.querySelectorAll('[required-if-active]').forEach(input => {
                            input.setAttribute('required', 'required');
                        });
                    } else {
                        // Hide the container and remove styling
                        container.classList.add('hidden');
                        container.classList.remove('space-y-4');
                        // Remove 'required' attribute and clear input values for inactive criteria
                        container.querySelectorAll('[required-if-active]').forEach(input => {
                            input.removeAttribute('required');
                            if (input.type === 'text' || input.type === 'date') {
                                input.value = '';
                            }
                        });
                    }
                });
            }

            // Attach the visibility update function to all checkbox change events
            queryTypeCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateFormVisibility);
            });

            // Run the visibility update once on page load to set initial state
            updateFormVisibility();


            // Function to handle the actual data fetching from the PHP backend
            async function fetchResults(formData) {
                // Get all selected query types again
                const selectedQueries = formData.getAll('query_type');

                // If nothing selected, ensure 'all_donors' is processed
                if (selectedQueries.length === 0) {
                    selectedQueries.push('all_donors');
                }

                let endpoint = 'search_multi_criteria.php';
                let params = new URLSearchParams(); // Object to build the query string (e.g., `?query_types[]=donor_name&donor_name_query=...`)
                let displayQuery = ''; // String to summarize the active query for display

                // This variable is not used in the current form structure, as each input is distinct
                const sharedDonorNameValue = formData.get('shared_donor_name_query');

                // Iterate over all selected query types to build the URL parameters
                selectedQueries.forEach(queryType => {
                    params.append('query_types[]', queryType); // Append the criterion name

                    switch (queryType) {
                        case 'donor_name':
                            // Append input value for donor name search
                            params.append('donor_name_query', formData.get('donor_name_query_name'));
                            displayQuery += `Name: ${formData.get('donor_name_query_name')} | `;
                            break;
                        case 'donor_zip':
                            // Append input value for zip search
                            params.append('zip_query', formData.get('zip_query'));
                            displayQuery += `Zip: ${formData.get('zip_query')} | `;
                            break;
                        case 'event_donors':
                            // Append input value for event name search
                            params.append('event_query', formData.get('event_query_name'));
                            displayQuery += `Event: ${formData.get('event_query_name')} | `;
                            break;
                        case 'donors_by_donation_range_in':
                            // Append start and end dates for 'IN' range search
                            params.append('start_date_in', formData.get('start_date_in'));
                            params.append('end_date_in', formData.get('end_date_in'));
                            displayQuery += `Donations IN Range | `;
                            break;
                        case 'donors_by_donation_range_not_in':
                            // Append start and end dates for 'NOT IN' range search
                            params.append('start_date_not_in', formData.get('start_date_not_in'));
                            params.append('end_date_not_in', formData.get('end_date_not_in'));
                            displayQuery += `Donations NOT IN Range | `;
                            break;
                        case 'Thanked_donors':
                            // Only display "Thanked Donors"
                            displayQuery += `Thanked Donors | `;
                            break;
                        case 'all_donors':
                            // Only display "All Donors" if it's the *only* criterion
                            if (selectedQueries.length === 1) displayQuery = 'All Donors';
                            break;
                    }
                });

                // Clean up the trailing ' | ' from the display string
                displayQuery = displayQuery.replace(/ \| $/, '');

                // Construct the final URL with parameters
                const url = `${endpoint}?${params.toString()}`;
                console.log("Fetching URL:", url);

                try {
                    // Make the AJAX request
                    const response = await fetch(url);

                    // Check if the server returned JSON data
                    const contentType = response.headers.get("content-type");
                    if (contentType && contentType.indexOf("application/json") === -1) {
                         throw new Error(`Server returned non-JSON data. Check your PHP script for errors.`);
                    }

                    // Check for HTTP error status (4xx or 5xx)
                    if (!response.ok) {
                        const errorData = await response.json();
                        throw new Error(`HTTP error! Status: ${response.status}. Details: ${errorData.details || errorData.error}`);
                    }

                    // Parse the JSON response
                    const results = await response.json();

                    return { results, displayQuery };

                } catch (error) {
                    // Handle fetch or JSON parsing errors
                    console.error("Fetch error:", error);
                    // Display a user-friendly error message
                    resultsContent.innerHTML = `<p class="error-msg">
                        An error occurred while fetching data for **${displayQuery}**. Check the console for details. Error: ${error.message}
                        <br>
                        ***NOTE: This might be because the required PHP backend file (${endpoint}) does not exist or doesn't handle multiple criteria.***
                    </p>`;
                    return { results: [], displayQuery };
                }
            }

            // Attach the submit handler to the form
            searchForm.addEventListener('submit', async (e) => {
                e.preventDefault(); // Prevent default form submission
                const formData = new FormData(searchForm); // Get all form data

                // Fetch and get the results
                const { results, displayQuery } = await fetchResults(formData);
                // Render the results to the display area
                renderResults(displayQuery, results);
            });

            // Function to format and display the returned data
            function renderResults(displayQuery, data) {
                resultsContent.innerHTML = '';

                if (data.length === 0) {
                    // Display message if no results found
                    resultsContent.innerHTML = `
                        <p class="no-results">
                            No data found for: **${displayQuery}**.
                        </p>
                    `;
                } else {
                    // Display result count and start building HTML list
                    let html = `<p class="result-count">${data.length} result(s) found for **"${displayQuery}"**:</p>`;

                    // Iterate over each result item
                    data.forEach(item => {
                        const name = item.first && item.last ? `${item.first} ${item.last}` : item.name || 'Donor/Event Name';
                        const detail = item.email || item.name || `${item.city || 'N/A'}, ${item.state || 'N/A'}` || 'Details N/A';
                        const count = item.donation_count ? ` | Donations: ${item.donation_count}` : '';
                        // Append a formatted card for each donor
                        html += `
                            <div class="result-item">
                                <p class="result-title">${name}${count}</p>
                                <p class="result-detail">${detail}</p>
                            </div>
                        `;
                    });
                    resultsContent.innerHTML = html;
                }
            }
        });
    </script>
		<style>
    /* --- GLOBAL RESET & FONTS --- */
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
    }
    * { box-sizing: border-box; }

    body {
        font-family: 'Quicksand', sans-serif;
        background-color: #f9f9f9;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    /* --- LAYOUT --- */
    .content-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.15);
        width: 100%;
        max-width: 800px;
        margin: 30px auto;
        text-align: left;
    }

    h1 {
        text-align: center;
        color: #333;
        font-weight: 700;
        margin-top: 0;
        margin-bottom: 5px;
    }

    p.subtitle {
        text-align: center;
        color: #666;
        font-style: italic;
        margin-bottom: 25px;
        font-size: 14px;
    }

    h3 {
        color: #00447b;
        border-bottom: 2px solid #eee;
        padding-bottom: 10px;
        margin-bottom: 15px;
        font-weight: bold;
        font-size: 18px;
    }

    /* --- FORM ELEMENTS --- */
    .checkbox-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 15px;
        background: #f0f7ff;
        padding: 20px;
        border-radius: 8px;
        border: 1px solid #d0e3ff;
        margin-bottom: 20px;
    }

    .checkbox-item {
        display: flex;
        align-items: center;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        color: #333;
    }

    .checkbox-item input {
        transform: scale(1.2);
        margin-right: 10px;
    }

    label.field-label {
        display: block;
        font-weight: bold;
        color: #444;
        margin-bottom: 5px;
        font-size: 14px;
        margin-top: 15px;
    }

    input[type="text"], input[type="date"] {
        width: 100%;
        padding: 12px;
        font-size: 16px;
        border-radius: 8px;
        border: 1px solid #aaa;
        font-family: 'Quicksand', sans-serif;
        background-color: #fff;
    }

    input:focus {
        outline: 2px solid #00447b;
        border-color: #00447b;
    }

    /* --- UTILITY CLASSES FOR JS --- */
    .hidden { display: none !important; }
    .space-y-4 > * { margin-bottom: 15px; }

    /* --- BUTTONS --- */
    .btn-primary {
        background-color: #00447b;
        color: white;
        padding: 14px 24px;
        font-size: 16px;
        font-weight: bold;
        border-radius: 8px;
        border: none;
        width: 100%;
        cursor: pointer;
        transition: all 0.2s;
        margin-top: 20px;
    }

    .btn-primary:hover {
        background-color: #003366;
        transform: translateY(-2px);
    }

    /* --- RESULTS STYLING --- */
    .result-item {
        background: white;
        padding: 15px;
        margin-bottom: 10px;
        border-radius: 8px;
        border: 1px solid #eee;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    .result-title { font-weight: bold; color: #333; margin: 0; }
    .result-detail { color: #666; font-size: 13px; margin: 5px 0 0 0; }
    .result-count { margin-bottom: 15px; font-weight: bold; color: #00447b; }
    .no-results { color: #856404; background-color: #fff3cd; padding: 10px; border-radius: 5px; }
    .error-msg { color: #721c24; background-color: #f8d7da; padding: 10px; border-radius: 5px; }
    </style>
</head>

<body>
    <?php require_once('header.php') ?>

    <div class="content-card">
        <header>
            <h1>Donor Search</h1>
            <p class="subtitle">
                Select one or more criteria below. Donors must match **ALL** selected criteria.
            </p>
        </header>

        <form id="search-form">

            <div class="checkbox-grid">
                <label class="checkbox-item">
                    <input type="checkbox" name="query_type" value="donor_name">
                    Donor by Name
                </label>
                <label class="checkbox-item">
                    <input type="checkbox" name="query_type" value="event_donors">
                    Event by Name
                </label>
                <label class="checkbox-item">
                    <input type="checkbox" name="query_type" value="donor_zip">
                    Donor zip
                </label>
                <label class="checkbox-item">
                    <input type="checkbox" name="query_type" value="donors_by_donation_range_in">
                    Donors IN Date Range
                </label>
                <label class="checkbox-item">
                    <input type="checkbox" name="query_type" value="donors_by_donation_range_not_in">
                    Donors NOT IN Date Range
                </label>
                <label class="checkbox-item">
                    <input type="checkbox" name="query_type" value="Thanked_donors">
                    Thanked donors
                </label>
            </div>

            <div id="dynamic-inputs">
                    <div class="query-input-container hidden" data-query-type="donor_name">
                    <label for="donor-name-input-name" class="field-label">Donor Name (First or First & Last)</label>
                    <input type="text" id="donor-name-input-name" name="donor_name_query_name" placeholder="e.g., Jane Doe" required-if-active>
                </div>
                    <div class="query-input-container hidden" data-query-type="event_donors">
                    <label for="donor-event-input-events" class="field-label">Event Name (shows donors who donated at event) </label>
                    <input type="text" id="donor-event-input-events" name="event_query_name" placeholder="e.g., test" required-if-active>
                </div>
                    <div class="query-input-container hidden" data-query-type="donor_zip">
                    <label for="zip-input" class="field-label">Donor Zip Code</label>
                    <input type="text" id="zip-input" name="zip_query" placeholder="e.g., 22401" required-if-active>
                </div>
                    <div class="query-input-container hidden" data-query-type="donors_by_donation_range_in">
                    <label for="start-date-in" class="field-label">Start Date (YYYY-MM-DD)</label>
                    <input type="date" id="start-date-in" name="start_date_in" required-if-active>
                    <label for="end-date-in" class="field-label">End Date (YYYY-MM-DD)</label>
                    <input type="date" id="end-date-in" name="end_date_in" required-if-active>
                </div>
                    <div class="query-input-container hidden" data-query-type="donors_by_donation_range_not_in">
                    <label for="start-date-not-in" class="field-label">Start Date (YYYY-MM-DD)</label>
                    <input type="date" id="start-date-not-in" name="start_date_not_in" required-if-active>
                    <label for="end-date-not-in" class="field-label">End Date (YYYY-MM-DD)</label>
                    <input type="date" id="end-date-not-in" name="end_date_not_in" required-if-active>
                </div>
                    <div class="query-input-container hidden" data-query-type="Thanked_donors">
                    <p style="color:#666; font-style:italic; margin-top:15px;">Returning only donors who have been thanked.</p>
                </div>
                    <div class="query-input-container hidden" data-query-type="all_donors">
                    <p style="color:#666; font-style:italic; margin-top:15px;">This returns a list of **ALL** donors. Click "Execute Query".</p>
                </div>

            </div>
            <button type="submit" class="btn-primary">
                <i class="fas fa-search"></i> Execute Query
            </button>

        </form>
    </div>

    <div class="content-card" id="results-display" style="margin-top:0;">
        <h3>Query Results</h3>
        <div id="results-content">
            <p>Select one or more criteria above and click 'Execute Query'.</p>
        </div>
    </div>

    <?php require_once('footer.php'); ?>
</body>
</html>