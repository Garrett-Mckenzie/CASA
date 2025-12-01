<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Search</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        // Ensure the script runs only after the entire document is loaded
        document.addEventListener('DOMContentLoaded', () => {
            // Get references to key DOM elements
            const queryTypeCheckboxes = document.querySelectorAll('input[name="query_type"]');
            const formContainers = document.querySelectorAll('.query-input-container');
            const searchForm = document.getElementById('search-form');
            const resultsContent = document.getElementById('results-content');
            
            /**
             * Updates the visibility and required status of the input fields
             * based on which query type checkboxes are selected.
             */
            function updateFormVisibility() {
                // Determine which query types are selected
                const selectedQueryTypes = Array.from(queryTypeCheckboxes)
                    .filter(checkbox => checkbox.checked)
                    .map(checkbox => checkbox.value);

                // Default to 'all_donors' if no criteria are selected
                if (selectedQueryTypes.length === 0) {
                    selectedQueryTypes.push('all_donors');
                }

                // Iterate over all input containers
                formContainers.forEach(container => {
                    const containerType = container.getAttribute('data-query-type');
                    
                    // Show the container if its type is selected
                    if (selectedQueryTypes.includes(containerType)) {
                        container.classList.remove('hidden');
                        container.classList.add('space-y-4');
                        // Make inputs required if they have the 'required-if-active' attribute
                        container.querySelectorAll('[required-if-active]').forEach(input => {
                            input.setAttribute('required', 'required');
                        });
                    } else {
                        // Hide the container if its type is not selected
                        container.classList.add('hidden');
                        container.classList.remove('space-y-4');
                        // Remove 'required' attribute and clear values for hidden inputs
                        container.querySelectorAll('[required-if-active]').forEach(input => {
                            input.removeAttribute('required');
                            if (input.type === 'text' || input.type === 'date') {
                                input.value = ''; // Clear value to prevent submitting stale data
                            }
                        });
                    }
                });
            }

            // Attach the visibility update function to all query type checkboxes
            queryTypeCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateFormVisibility);
            });
            
            // Run on initial load to set up the default state (which is 'all_donors')
            updateFormVisibility();


            /**
             * Asynchronously fetches search results from the backend PHP script.
             * @param {FormData} formData - The data collected from the search form.
             */
            async function fetchResults(formData) {
                // Re-read selected queries to ensure 'all_donors' is included if none are selected
                const selectedQueries = formData.getAll('query_type');
                
                if (selectedQueries.length === 0) {
                    selectedQueries.push('all_donors');
                }

                let endpoint = 'search_multi_criteria.php'; // The backend script to call
                let params = new URLSearchParams(); // Used to build the query string
                let displayQuery = ''; // Human-readable summary of the query

                // Iterate through selected queries to build the URL parameters and display string
                selectedQueries.forEach(queryType => {
                    params.append('query_types[]', queryType);
                    
                    switch (queryType) {
                        case 'donor_name':
                            params.append('donor_name_query', formData.get('donor_name_query_name'));
                            displayQuery += `Name: ${formData.get('donor_name_query_name')} | `;
                            break;
                        case 'donor_zip':
                            params.append('zip_query', formData.get('zip_query'));
                            displayQuery += `Zip: ${formData.get('zip_query')} | `;
                            break;
                        case 'donor_events':
                            params.append('donor_name_events', formData.get('donor_name_query_events'));
                            displayQuery += `Events for: ${formData.get('donor_name_query_events')} | `;
                            break;
                        case 'event_donors':
                            params.append('event_query', formData.get('event_query'));
                            displayQuery += `Event: ${formData.get('event_query')} | `;
                            break;
                        case 'donor_donations_date_range':
                            params.append('donor_name_donations', formData.get('donor_name_query_donations'));
                            params.append('start_date_donations', formData.get('start_date_donations'));
                            params.append('end_date_donations', formData.get('end_date_donations'));
                            displayQuery += `Donations Date Range | `;
                            break;
                        case 'donors_by_donation_range_in':
                            params.append('start_date_in', formData.get('start_date_in'));
                            params.append('end_date_in', formData.get('end_date_in'));
                            displayQuery += `Donations IN Range | `;
                            break;
                        case 'donors_by_donation_range_not_in':
                            params.append('start_date_not_in', formData.get('start_date_not_in'));
                            params.append('end_date_not_in', formData.get('end_date_not_in'));
                            displayQuery += `Donations NOT IN Range | `;
                            break;
                        case 'Thanked_donors':
                            // Only set 'All Donors' if this is the ONLY selected query
                            displayQuery += 'Thanked Donors';
                            break;
                        case 'all_donors':
                            // Only set 'All Donors' if this is the ONLY selected query
                            if (selectedQueries.length === 1) displayQuery = 'All Donors';
                            break;
                        
                    }
                });
                
                // Clean up trailing separator
                displayQuery = displayQuery.replace(/ \| $/, '');
                
                // Construct the final URL
                const url = `${endpoint}?${params.toString()}`;
                console.log("Fetching URL:", url);

                try {
                    const response = await fetch(url); 
                    
                    // Check if the response content type is JSON
                    const contentType = response.headers.get("content-type");
                    if (contentType && contentType.indexOf("application/json") === -1) {
                         throw new Error(`Server returned non-JSON data. Check your PHP script for errors.`);
                    }

                    // Check for HTTP error status codes (e.g., 404, 500)
                    if (!response.ok) {
                        const errorData = await response.json();
                        throw new Error(`HTTP error! Status: ${response.status}. Details: ${errorData.details || errorData.error}`);
                    }
                    
                    // Parse the JSON response
                    const results = await response.json();
                    
                    return { results, displayQuery };

                } catch (error) {
                    // Handle any network or parsing errors
                    console.error("Fetch error:", error);
                    // Display a user-friendly error message
                    resultsContent.innerHTML = `<p class="text-sm text-red-600 bg-red-50 p-3 rounded-lg border border-red-200">
                        🚨 An error occurred while fetching data for **${displayQuery}**. Check the console for details. Error: ${error.message}
                        <br>
                        ***NOTE: This might be because the required PHP backend file (${endpoint}) does not exist or doesn't handle multiple criteria.***
                    </p>`;
                    return { results: [], displayQuery };
                }
            }

            // Event listener for form submission
            searchForm.addEventListener('submit', async (e) => {
                e.preventDefault(); // Prevent default form submission and page reload
                const formData = new FormData(searchForm); // Get all form data
                
                // Fetch the results and display them
                const { results, displayQuery } = await fetchResults(formData);
                renderResults(displayQuery, results);
            });
            
            /**
             * Renders the fetched data into the results content div.
             * @param {string} displayQuery - Human-readable query string.
             * @param {Array<Object>} data - The array of result objects.
             */
            function renderResults(displayQuery, data) {
                resultsContent.innerHTML = ''; // Clear previous results
                
                if (data.length === 0) {
                    // Display message if no results are found
                    resultsContent.innerHTML = `
                        <p class="text-sm text-yellow-600 bg-yellow-50 p-3 rounded-lg border border-yellow-200">
                            No data found for: **${displayQuery}**.
                        </p>
                    `;
                } else {
                    // Display count and start building the results HTML
                    let html = `<p class="text-sm font-medium text-gray-700 mb-3">${data.length} result(s) found for **"${displayQuery}"**:</p>`;
                    
                    data.forEach(item => {
                        // Extract and format donor/event name
                        const name = item.first && item.last ? `${item.first} ${item.last}` : item.name || 'Donor/Event Name';
                        // Determine the secondary detail to display
                        const detail = item.email || item.name || `${item.city || 'N/A'}, ${item.state || 'N/A'}` || 'Details N/A';
                        // Add donation count if available
                        //$sql = "SELECT DISTINCT d.id FROM donors d JOIN donations don ON d.id = don.donorID";

                        const count = item.donation_count ? ` | Donations: ${item.donation_count}` : '';

                        // Append a result card to the HTML
                        html += `
                            <div class="bg-white p-4 mb-2 rounded-lg shadow-sm border border-gray-100 transition duration-150 hover:shadow-md">
                                <p class="text-base font-semibold text-gray-900">${name}${count}</p>
                                <p class="text-xs text-indigo-600 font-mono mt-1">${detail}</p>
                            </div>
                        `;
                    });
                    resultsContent.innerHTML = html; // Inject the results HTML
                }
            }
        });
    </script>

    <style>
        /* Simple style to set a modern font */
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-4">

    <div class="w-full max-w-md bg-white shadow-xl rounded-xl p-8 space-y-6">
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
                    <!-- makes Donor events checkbox -->
                    <label class="flex items-center p-3 rounded-lg border cursor-pointer hover:bg-white transition bg-white/50">
                        <input type="checkbox" name="query_type" value="donor_events" class="h-4 w-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                        <span class="ml-2 text-sm font-medium text-gray-700">Donor by events</span>
                    </label>
                    <!-- makes event Donors checkbox -->
                    <label class="flex items-center p-3 rounded-lg border cursor-pointer hover:bg-white transition bg-white/50">
                        <input type="checkbox" name="query_type" value="event_donors" class="h-4 w-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                        <span class="ml-2 text-sm font-medium text-gray-700">Event return Donors</span>
                    </label>
                    <!-- makes Donors zip checkbox -->
                    <label class="flex items-center p-3 rounded-lg border cursor-pointer hover:bg-white transition bg-white/50">
                        <input type="checkbox" name="query_type" value="donor_zip" class="h-4 w-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                        <span class="ml-2 text-sm font-medium text-gray-700">Donor zip</span>
                    </label>
                    
                    <!-- makes Donors donations date range checkbox -->
                    <!--
                    <label class="flex items-center p-3 rounded-lg border cursor-pointer hover:bg-white transition bg-white/50">
                        <input type="checkbox" name="query_type" value="donor_donations_date_range" class="h-4 w-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                        <span class="ml-2 text-sm font-medium text-gray-700">Donor Donations (Date Range)</span>
                    </label>
                    -->
                    <!-- makes Donors by donations date range in checkbox -->
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
                    <!-- inputs needed for 'donor_events' -->
                <div class="query-input-container hidden" data-query-type="donor_events">
                    <label for="donor-name-input-events" class="block text-sm font-semibold text-gray-700 mb-2">Donor Name for Events (First or First & Last)</label>
                    <input type="text" id="donor-name-input-events" name="donor_name_query_events" placeholder="e.g., Jane Doe" class="w-full appearance-none bg-white p-3 border border-gray-300 rounded-lg shadow-inner text-gray-900 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150" required-if-active>
                </div>
                    <!-- inputs needed for 'event_donors' -->
                <div class="query-input-container hidden" data-query-type="event_donors">
                    <label for="donor-name-input-events" class="block text-sm font-semibold text-gray-700 mb-2">Event Name for Donors </label>
                    <input type="text" id="donor-name-input-events" name="donor_name_query_events" placeholder="e.g., Jane Doe" class="w-full appearance-none bg-white p-3 border border-gray-300 rounded-lg shadow-inner text-gray-900 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150" required-if-active>
                </div>
                    <!-- inputs needed for 'zip' -->
                <div class="query-input-container hidden" data-query-type="donor_zip">
                    <label for="zip-input" class="block text-sm font-semibold text-gray-700 mb-2">Donor Zip Code</label>
                    <input type="text" id="zip-input" name="zip_query" placeholder="e.g., 22401" class="w-full appearance-none bg-white p-3 border border-gray-300 rounded-lg shadow-inner text-gray-900 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150" required-if-active>
                </div>
                
                    <!-- inputs needed for 'donor donations date range' -->
    <!--
                <div class="query-input-container hidden" data-query-type="donor_donations_date_range">
                    <label for="donor-name-input-donations" class="block text-sm font-semibold text-gray-700 mb-2">Donor Name for Donation Count</label>
                    <input type="text" id="donor-name-input-donations" name="donor_name_query_donations" placeholder="e.g., John Smith" class="w-full appearance-none bg-white p-3 border border-gray-300 rounded-lg shadow-inner text-gray-900 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 mb-4" required-if-active>
                    <label for="start-date-donations" class="block text-sm font-semibold text-gray-700 mb-2">Start Date (YYYY-MM-DD)</label>
                    <input type="date" id="start-date-donations" name="start_date_donations" class="w-full appearance-none bg-white p-3 border border-gray-300 rounded-lg shadow-inner text-gray-900 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150" required-if-active>
                    <label for="end-date-donations" class="block text-sm font-semibold text-gray-700 mb-2 mt-4">End Date (YYYY-MM-DD)</label>
                    <input type="date" id="end-date-donations" name="end_date_donations" class="w-full appearance-none bg-white p-3 border border-gray-300 rounded-lg shadow-inner text-gray-900 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150" required-if-active>
                </div>
    -->
                    <!-- inputs needed for 'donor by donation range in' -->
                <div class="query-input-container hidden" data-query-type="donors_by_donation_range_in">
                    <label for="start-date-in" class="block text-sm font-semibold text-gray-700 mb-2">Start Date (YYYY-MM-DD)</label>
                    <input type="date" id="start-date-in" name="start_date_in" class="w-full appearance-none bg-white p-3 border border-gray-300 rounded-lg shadow-inner text-gray-900 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150" required-if-active>
                    <label for="end-date-in" class="block text-sm font-semibold text-gray-700 mb-2 mt-4">End Date (YYYY-MM-DD)</label>
                    <input type="date" id="end-date-in" name="end_date_in" class="w-full appearance-none bg-white p-3 border border-gray-300 rounded-lg shadow-inner text-gray-900 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150" required-if-active>
                </div>
                    <!-- inputs needed for 'donor by donation range not in' -->
                <div class="query-input-container hidden" data-query-type="donors_by_donation_range_not_in">
                    <label for="start-date-not-in" class="block text-sm font-semibold text-gray-700 mb-2">Start Date (YYYY-MM-DD)</label>
                    <input type="date" id="start-date-not-in" name="start_date_not_in" class="w-full appearance-none bg-white p-3 border border-gray-300 rounded-lg shadow-inner text-gray-900 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150" required-if-active>
                    <label for="end-date-not-in" class="block text-sm font-semibold text-gray-700 mb-2 mt-4">End Date (YYYY-MM-DD)</label>
                    <input type="date" id="end-date-not-in" name="end_date_not_in" class="w-full appearance-none bg-white p-3 border border-gray-300 rounded-lg shadow-inner text-gray-900 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150" required-if-active>
                </div>
                <div class="query-input-container hidden" data-query-type="thanked_donors">
                    <p class="text-sm text-gray-500 p-2 border border-gray-200 rounded-lg bg-gray-50">All thanked donors</p>
                </div>
                    <!-- No inputs needed for 'all_donors' -->
                <div class="query-input-container hidden" data-query-type="all_donors">
                    <p class="text-sm text-gray-500 p-2 border border-gray-200 rounded-lg bg-gray-50">This returns a list of **ALL** donors because no specific criteria were selected. Click "Execute Query".</p>
                </div>
                

                </div>
            
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

        <!--
        <div class="mt-4">
            <a href="test.php"
                class="w-full flex justify-center py-3 px-4 border border-gray-300 rounded-lg shadow-md text-base font-bold text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200 ease-in-out transform hover:scale-[1.01] active:scale-[0.99]">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                Go to test.php Page
            </a>
        </div>
        <div class="mt-4">
            <a href="all.php"
                class="w-full flex justify-center py-3 px-4 border border-gray-300 rounded-lg shadow-md text-base font-bold text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200 ease-in-out transform hover:scale-[1.01] active:scale-[0.99]">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                Go to test.db Page
            </a>
        </div>
        -->

    </div>

</body>
</html>