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

    <script src="https://cdn.tailwindcss.com"></script>
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
                    resultsContent.innerHTML = `<p class="text-sm text-red-600 bg-red-50 p-3 rounded-lg border border-red-200">
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
                        <p class="text-sm text-yellow-600 bg-yellow-50 p-3 rounded-lg border border-yellow-200">
                            No data found for: **${displayQuery}**.
                        </p>
                    `;
                } else {
                    // Display result count and start building HTML list
                    let html = `<p class="text-sm font-medium text-gray-700 mb-3">${data.length} result(s) found for **"${displayQuery}"**:</p>`;
                    
                    // Iterate over each result item
                    data.forEach(item => {
                        const name = item.first && item.last ? `${item.first} ${item.last}` : item.name || 'Donor/Event Name';
                        const detail = item.email || item.name || `${item.city || 'N/A'}, ${item.state || 'N/A'}` || 'Details N/A';
                        const count = item.donation_count ? ` | Donations: ${item.donation_count}` : ''; 
                        // Append a formatted card for each donor
                        html += `
                            <div class="bg-white p-4 mb-2 rounded-lg shadow-sm border border-gray-100 transition duration-150 hover:shadow-md">
                                <p class="text-base font-semibold text-gray-900">${name}${count}</p>
                                <p class="text-xs text-indigo-600 font-mono mt-1">${detail}</p>
                            </div>
                        `;
                    });
                    resultsContent.innerHTML = html;
                }
            }
        });
    </script>
		<style>
    body{
            font-family: 'Quicksand', sans-serif;
            background-color: #f9f9f9;
            margin: 0 auto;
            padding: 0;
						justify-content: center;
						text-align: center;
						align-self: center;
						vertical-align: middle;
        }
				.footer {
						background: #00447b;
						display: flex;
						justify-content: space-between;
						align-items: flex-start;
						padding: 30px 50px;
						flex-wrap: wrap;
						margin-top: 50px;
				}
				.footer-left { display: flex; flex-direction: column; align-items: center; }
				.footer-logo { width: 150px; margin-bottom: 15px; }
				.social-icons { display: flex; gap: 15px; }
				.social-icons a { color: white; font-size: 20px; }
				.footer-right { display: flex; gap: 50px; flex-wrap: wrap; }
				.footer-section { display: flex; flex-direction: column; gap: 10px; color: white; }
				.footer-topic { font-size: 18px; font-weight: bold; }
				.footer a { color: white; text-decoration: none; padding: 5px 10px; border-radius: 5px; }
				.footer a:hover { background: rgba(255, 255, 255, 0.1); }	
				.box {
								height: 100%;
								width: 70%;
								max-width: 700px;        
								background: #ffffff;     
								padding: 2rem;      
								border-radius: 20px;     
								box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1); 
								margin: 2rem auto;      
								font-family: Inter, sans-serif;
				}
    </style>
</head>

<body>
    <?php require_once('header.php') ?>

    <div class = "box">
        <header class="text-center">
            <h1 class="text-3xl font-extrabold text-gray-900">
                Donor Search
            </h1>
            <p class="mt-2 text-sm text-gray-500">
                Select one or more criteria below. Donors must match **ALL** selected criteria.
            </p>
        </header>

        <form id="search-form" class="space-y-6">
            
            <div class="border p-4 rounded-lg bg-gray-50 border-gray-200">
                <p class="block text-sm font-semibold text-gray-700 mb-3">Select Query Mode(s):</p>
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-3"> 
                    <!-- makes Donor Name checkbox -->
                    <label class="flex items-center p-3 rounded-lg border cursor-pointer hover:bg-white transition bg-white/50">
                        <input type="checkbox" name="query_type" value="donor_name" class="h-4 w-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                        <span class="ml-2 text-sm font-medium text-gray-700">Donor by Name</span>
                    </label>
                    <!-- makes event Donors checkbox -->
                    <label class="flex items-center p-3 rounded-lg border cursor-pointer hover:bg-white transition bg-white/50">
                        <input type="checkbox" name="query_type" value="event_donors" class="h-4 w-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                        <span class="ml-2 text-sm font-medium text-gray-700">Event by Name</span>
                    </label>
                    <!-- makes Donors zip checkbox -->
                    <label class="flex items-center p-3 rounded-lg border cursor-pointer hover:bg-white transition bg-white/50">
                        <input type="checkbox" name="query_type" value="donor_zip" class="h-4 w-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                        <span class="ml-2 text-sm font-medium text-gray-700">Donor zip</span>
                    </label>
                    <!-- makes Donor donations date range checkbox -->
                    <label class="flex items-center p-3 rounded-lg border cursor-pointer hover:bg-white transition bg-white/50">
                        <input type="checkbox" name="query_type" value="donors_by_donation_range_in" class="h-4 w-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                        <span class="ml-2 text-sm font-medium text-gray-700">Donors *IN* Date Range</span>
                    </label>
                    <!-- makes Donors by donations date range not in checkbox -->
                    <label class="flex items-center p-3 rounded-lg border cursor-pointer hover:bg-white transition bg-white/50">
                        <input type="checkbox" name="query_type" value="donors_by_donation_range_not_in" class="h-4 w-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                        <span class="ml-2 text-sm font-medium text-gray-700">Donors *NOT IN* Date Range</span>
                    </label>
                    <!-- makes Thankd Donors checkbox -->
                    <label class="flex items-center p-3 rounded-lg border cursor-pointer hover:bg-white transition bg-white/50">
                        <input type="checkbox" name="query_type" value="Thanked_donors" class="h-4 w-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                        <span class="ml-2 text-sm font-medium text-gray-700">Thanked donors</span>
                    </label>

                    </div>
            </div>

            <div class="space-y-4" id="dynamic-inputs">
                    <!-- inputs needed for 'donor_name' -->
                <div class="query-input-container hidden" data-query-type="donor_name">
                    <label for="donor-name-input-name" class="block text-sm font-semibold text-gray-700 mb-2">Donor Name (First or First & Last)</label>
                    <input type="text" id="donor-name-input-name" name="donor_name_query_name" placeholder="e.g., Jane Doe" class="w-full appearance-none bg-white p-3 border border-gray-300 rounded-lg shadow-inner text-gray-900 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150" required-if-active>
                </div>
                    <!-- inputs needed for 'event_donors' -->
                <div class="query-input-container hidden" data-query-type="event_donors">
                    <label for="donor-event-input-events" class="block text-sm font-semibold text-gray-700 mb-2">Event Name (shows donors who donated at event) </label>
                    <input type="text" id="donor-event-input-events" name="event_query_name" placeholder="e.g., test" class="w-full appearance-none bg-white p-3 border border-gray-300 rounded-lg shadow-inner text-gray-900 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150" required-if-active>
                </div>
                    <!-- inputs needed for 'zip' -->
                <div class="query-input-container hidden" data-query-type="donor_zip">
                    <label for="zip-input" class="block text-sm font-semibold text-gray-700 mb-2">Donor Zip Code</label>
                    <input type="text" id="zip-input" name="zip_query" placeholder="e.g., 22401" class="w-full appearance-none bg-white p-3 border border-gray-300 rounded-lg shadow-inner text-gray-900 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150" required-if-active>
                </div>
                    <!-- inputs needed for 'donor by donation in range' -->
                <div class="query-input-container hidden" data-query-type="donors_by_donation_range_in">
                    <label for="start-date-in" class="block text-sm font-semibold text-gray-700 mb-2">Start Date (YYYY-MM-DD)</label>
                    <input type="date" id="start-date-in" name="start_date_in" class="w-full appearance-none bg-white p-3 border border-gray-300 rounded-lg shadow-inner text-gray-900 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150" required-if-active>
                    <label for="end-date-in" class="block text-sm font-semibold text-gray-700 mb-2 mt-4">End Date (YYYY-MM-DD)</label>
                    <input type="date" id="end-date-in" name="end_date_in" class="w-full appearance-none bg-white p-3 border border-gray-300 rounded-lg shadow-inner text-gray-900 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150" required-if-active>
                </div>
                    <!-- inputs needed for 'donor by donation not in range' -->
                <div class="query-input-container hidden" data-query-type="donors_by_donation_range_not_in">
                    <label for="start-date-not-in" class="block text-sm font-semibold text-gray-700 mb-2">Start Date (YYYY-MM-DD)</label>
                    <input type="date" id="start-date-not-in" name="start_date_not_in" class="w-full appearance-none bg-white p-3 border border-gray-300 rounded-lg shadow-inner text-gray-900 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150" required-if-active>
                    <label for="end-date-not-in" class="block text-sm font-semibold text-gray-700 mb-2 mt-4">End Date (YYYY-MM-DD)</label>
                    <input type="date" id="end-date-not-in" name="end_date_not_in" class="w-full appearance-none bg-white p-3 border border-gray-300 rounded-lg shadow-inner text-gray-900 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150" required-if-active>
                </div>
                    <!-- inputs needed for 'thanked_donors' -->
                <div class="query-input-container hidden" data-query-type="Thanked_donors">
                    <p class="text-sm text-gray-500 p-2 border border-gray-200 rounded-lg bg-gray-50">returning only donors who have been thanked.</p>
                </div>
                    <!-- inputs needed for 'all_donors' -->
                <div class="query-input-container hidden" data-query-type="all_donors">
                    <p class="text-sm text-gray-500 p-2 border border-gray-200 rounded-lg bg-gray-50">This returns a list of **ALL** donors. Click "Execute Query".</p>
                </div>
                
            </div>
            <!-- submit button -->
            <div class="pt-2">
                <button type="submit"
                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-md text-base font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200 ease-in-out transform hover:scale-[1.01] active:scale-[0.99]">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    Execute Query
                </button>
            </div>
            
        </form>

        <div id="results-display" class="mt-6 p-4 border border-gray-200 rounded-lg bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">
                <span class="text-indigo-600">Query</span> Results
            </h3>
            <div id="results-content" class="text-sm text-gray-600 space-y-2">
                <p>Select one or more criteria above and click 'Execute Query'.</p>
            </div>
        </div>
    </div>  
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
    <script src="https://kit.fontawesome.com/yourkit.js" crossorigin="anonymous"></script>





</body>
</html>
