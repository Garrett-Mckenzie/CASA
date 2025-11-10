<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multi-Option Donor Search Application</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                },
                colors: {
                    'primary': '#4f46e5', // Indigo-600
                    'primary-hover': '#4338ca', // Indigo-700
                }
            }
        }

        // --- Core Application Logic ---
        document.addEventListener('DOMContentLoaded', () => {
            const queryTypeCheckboxes = document.querySelectorAll('input[name="query_type"]');
            const formContainers = document.querySelectorAll('.query-input-container');
            const searchForm = document.getElementById('search-form');
            const resultsContent = document.getElementById('results-content');
            
            // --- UI LOGIC: Show/Hide Inputs Based on Checkbox Selection ---
            function updateFormVisibility() {
                const selectedQueryTypes = Array.from(queryTypeCheckboxes)
                    .filter(checkbox => checkbox.checked)
                    .map(checkbox => checkbox.value);

                // Default check: If nothing is selected, treat it as 'all_donors'
                if (selectedQueryTypes.length === 0) {
                    selectedQueryTypes.push('all_donors');
                }

                formContainers.forEach(container => {
                    const containerType = container.getAttribute('data-query-type');
                    
                    if (selectedQueryTypes.includes(containerType)) {
                        container.classList.remove('hidden');
                        container.classList.add('space-y-4'); // Re-add spacing
                        // Ensure required fields are set/unset when shown/hidden
                        container.querySelectorAll('[required-if-active]').forEach(input => {
                            input.setAttribute('required', 'required');
                        });
                    } else {
                        container.classList.add('hidden');
                        container.classList.remove('space-y-4');
                        // Remove 'required' attribute when hidden
                        container.querySelectorAll('[required-if-active]').forEach(input => {
                            input.removeAttribute('required');
                            // Clear values when hiding to ensure they aren't included in the FormData
                            if (input.type === 'text' || input.type === 'date') {
                                input.value = '';
                            }
                        });
                    }
                });
            }

            // Attach event listener to all checkboxes
            queryTypeCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateFormVisibility);
            });
            
            // Initial render based on the default checked checkbox (if any)
            updateFormVisibility();


            // --- ASYNCHRONOUS SEARCH FUNCTION ---
            async function fetchResults(formData) {
                const selectedQueries = formData.getAll('query_type');
                
                // If no query types selected, default to 'all_donors' for the backend
                if (selectedQueries.length === 0) {
                    selectedQueries.push('all_donors');
                }

                let endpoint = 'search_multi_criteria.php'; // *NEW BACKEND REQUIRED for multi-select*
                let params = new URLSearchParams();
                let displayQuery = '';

                // Build parameters from all selected types
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
                        // NOTE: For 'events', 'donations', and 'date ranges', the backend needs to handle combining these criteria.
                        // For simplicity, we'll only pass the inputs for the selected options.
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
                        case 'all_donors':
                            // No extra parameters needed, handled by query_types[]
                            if (selectedQueries.length === 1) displayQuery = 'All Donors';
                            break;
                    }
                });
                
                // Trim trailing separator for clean display
                displayQuery = displayQuery.replace(/ \| $/, '');
                
                const url = `${endpoint}?${params.toString()}`;
                console.log("Fetching URL:", url);

                try {
                    // *** You MUST implement search_multi_criteria.php to handle the combination of these criteria ***
                    const response = await fetch(url); 
                    
                    const contentType = response.headers.get("content-type");
                    if (contentType && contentType.indexOf("application/json") === -1) {
                         throw new Error(`Server returned non-JSON data. Check your PHP script for errors.`);
                    }

                    if (!response.ok) {
                        const errorData = await response.json();
                        throw new Error(`HTTP error! Status: ${response.status}. Details: ${errorData.details || errorData.error}`);
                    }
                    
                    const results = await response.json();
                    
                    return { results, displayQuery };

                } catch (error) {
                    console.error("Fetch error:", error);
                    resultsContent.innerHTML = `<p class="text-sm text-red-600 bg-red-50 p-3 rounded-lg border border-red-200">
                        🚨 An error occurred while fetching data for **${displayQuery}**. Check the console for details. Error: ${error.message}
                        <br>
                        ***NOTE: This might be because the required PHP backend file (${endpoint}) does not exist or doesn't handle multiple criteria.***
                    </p>`;
                    return { results: [], displayQuery };
                }
            }

            // 1. Handle form submission (Search button click)
            searchForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const formData = new FormData(searchForm); 
                
                // 2. Fetch the actual data from the server
                const { results, displayQuery } = await fetchResults(formData);
                renderResults(displayQuery, results);
            });
            
            // 3. Render the search results
            function renderResults(displayQuery, data) {
                resultsContent.innerHTML = '';
                
                if (data.length === 0) {
                    resultsContent.innerHTML = `
                        <p class="text-sm text-yellow-600 bg-yellow-50 p-3 rounded-lg border border-yellow-200">
                            No data found for: **${displayQuery}**.
                        </p>
                    `;
                } else {
                    let html = `<p class="text-sm font-medium text-gray-700 mb-3">${data.length} result(s) found for **"${displayQuery}"**:</p>`;
                    
                    data.forEach(item => {
                        const name = item.first && item.last ? `${item.first} ${item.last}` : item.name || 'Donor/Event Name';
                        const detail = item.email || item.event_name || `${item.city || 'N/A'}, ${item.state || 'N/A'}` || 'Details N/A';
                        const count = item.donation_count ? ` | Donations: ${item.donation_count}` : '';

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
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4 sm:p-8">

    <div class="w-full max-w-lg bg-white shadow-2xl rounded-xl p-6 sm:p-8 space-y-6">
        <header class="text-center">
            <h1 class="text-3xl font-extrabold text-gray-900">
                <span class="text-primary">Multi-Criteria</span> Donor Search
            </h1>
            <p class="mt-2 text-sm text-gray-500">
                Select one or more criteria below. Donors must match **ALL** selected criteria.
            </p>
        </header>

        <form id="search-form" class="space-y-6">
            
            <div class="border p-4 rounded-lg bg-gray-50 border-gray-200">
                <p class="block text-sm font-semibold text-gray-700 mb-3">Select Query Mode(s):</p>
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                    
                    <label class="flex items-center p-3 rounded-lg border cursor-pointer hover:bg-white transition bg-white/50">
                        <input type="checkbox" name="query_type" value="donor_name" class="h-4 w-4 text-primary rounded border-gray-300 focus:ring-primary">
                        <span class="ml-2 text-sm font-medium text-gray-700">Donor by Name</span>
                    </label>

                    <label class="flex items-center p-3 rounded-lg border cursor-pointer hover:bg-white transition bg-white/50">
                        <input type="checkbox" name="query_type" value="donor_events" class="h-4 w-4 text-primary rounded border-gray-300 focus:ring-primary">
                        <span class="ml-2 text-sm font-medium text-gray-700">Donor's Events</span>
                    </label>

                    <label class="flex items-center p-3 rounded-lg border cursor-pointer hover:bg-white transition bg-white/50">
                        <input type="checkbox" name="query_type" value="donor_donations_date_range" class="h-4 w-4 text-primary rounded border-gray-300 focus:ring-primary">
                        <span class="ml-2 text-sm font-medium text-gray-700">Donor's Donations (Date)</span>
                    </label>

                    <label class="flex items-center p-3 rounded-lg border cursor-pointer hover:bg-white transition bg-white/50">
                        <input type="checkbox" name="query_type" value="event_donors" class="h-4 w-4 text-primary rounded border-gray-300 focus:ring-primary">
                        <span class="ml-2 text-sm font-medium text-gray-700">Donors by Event</span>
                    </label>
                    
                    <label class="flex items-center p-3 rounded-lg border cursor-pointer hover:bg-white transition bg-white/50">
                        <input type="checkbox" name="query_type" value="donors_by_donation_range_in" class="h-4 w-4 text-primary rounded border-gray-300 focus:ring-primary">
                        <span class="ml-2 text-sm font-medium text-gray-700">Donors IN Date Range</span>
                    </label>
                    
                    <label class="flex items-center p-3 rounded-lg border cursor-pointer hover:bg-white transition bg-white/50">
                        <input type="checkbox" name="query_type" value="donors_by_donation_range_not_in" class="h-4 w-4 text-primary rounded border-gray-300 focus:ring-primary">
                        <span class="ml-2 text-sm font-medium text-gray-700">Donors NOT IN Date Range</span>
                    </label>
                    
                    <label class="flex items-center p-3 rounded-lg border cursor-pointer hover:bg-white transition bg-white/50">
                        <input type="checkbox" name="query_type" value="donor_zip" class="h-4 w-4 text-primary rounded border-gray-300 focus:ring-primary">
                        <span class="ml-2 text-sm font-medium text-gray-700">Donors by Zip Code</span>
                    </label>
                    
                </div>
            </div>

            <div class="space-y-4" id="dynamic-inputs">

                <div class="query-input-container hidden" data-query-type="donor_name">
                    <label for="donor-name-input-name" class="block text-sm font-semibold text-gray-700 mb-2">Donor Name (First, Last, or Both)</label>
                    <input type="text" id="donor-name-input-name" name="donor_name_query_name" placeholder="e.g., Jane Doe" class="w-full appearance-none bg-white p-3 border border-gray-300 rounded-lg shadow-inner text-gray-900 focus:ring-primary focus:border-primary transition duration-150" required-if-active>
                </div>
                
                <div class="query-input-container hidden" data-query-type="all_donors">
                    <p class="text-sm text-gray-500 p-2 border border-gray-200 rounded-lg bg-gray-50">This returns a list of **ALL** donors because no specific criteria were selected. Click "Execute Query".</p>
                </div>

                <div class="query-input-container hidden" data-query-type="donor_events">
                    <label for="donor-name-input-events" class="block text-sm font-semibold text-gray-700 mb-2">Donor Name (For Events)</label>
                    <input type="text" id="donor-name-input-events" name="donor_name_query_events" placeholder="e.g., John Smith" class="w-full appearance-none bg-white p-3 border border-gray-300 rounded-lg shadow-inner text-gray-900 focus:ring-primary focus:border-primary transition duration-150" required-if-active>
                </div>

                <div class="query-input-container hidden" data-query-type="donor_donations_date_range">
                    <label for="donor-name-input-donations" class="block text-sm font-semibold text-gray-700 mb-2">Donor Name (For Donations Count)</label>
                    <input type="text" id="donor-name-input-donations" name="donor_name_query_donations" placeholder="e.g., Alex Johnson" class="w-full appearance-none bg-white p-3 border border-gray-300 rounded-lg shadow-inner text-gray-900 focus:ring-primary focus:border-primary transition duration-150 mb-4" required-if-active>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Donation Date Range</label>
                    <div class="flex space-x-2">
                        <input type="date" id="start-date-donations" name="start_date_donations" class="w-1/2 appearance-none bg-white p-3 border border-gray-300 rounded-lg shadow-inner text-gray-900 focus:ring-primary focus:border-primary transition duration-150" required-if-active>
                        <input type="date" id="end-date-donations" name="end_date_donations" class="w-1/2 appearance-none bg-white p-3 border border-gray-300 rounded-lg shadow-inner text-gray-900 focus:ring-primary focus:border-primary transition duration-150" required-if-active>
                    </div>
                </div>

                <div class="query-input-container hidden" data-query-type="event_donors">
                    <label for="event-input" class="block text-sm font-semibold text-gray-700 mb-2">Event Name or ID</label>
                    <input type="text" id="event-input" name="event_query" placeholder="e.g., Annual Gala" class="w-full appearance-none bg-white p-3 border border-gray-300 rounded-lg shadow-inner text-gray-900 focus:ring-primary focus:border-primary transition duration-150" required-if-active>
                </div>

                <div class="query-input-container hidden" data-query-type="donors_by_donation_range_in">
                    <p class="text-sm font-semibold text-gray-700 mb-2">Donation Date Range (Donors with donations **IN** this range)</p>
                    <div class="flex space-x-2">
                        <input type="date" id="start-date-in" name="start_date_in" class="w-1/2 appearance-none bg-white p-3 border border-gray-300 rounded-lg shadow-inner text-gray-900 focus:ring-primary focus:border-primary transition duration-150" required-if-active>
                        <input type="date" id="end-date-in" name="end_date_in" class="w-1/2 appearance-none bg-white p-3 border border-gray-300 rounded-lg shadow-inner text-gray-900 focus:ring-primary focus:border-primary transition duration-150" required-if-active>
                    </div>
                </div>

                <div class="query-input-container hidden" data-query-type="donors_by_donation_range_not_in">
                    <p class="text-sm font-semibold text-gray-700 mb-2">Donation Date Range (Donors with donations **NOT IN** this range)</p>
                    <div class="flex space-x-2">
                        <input type="date" id="start-date-not-in" name="start_date_not_in" class="w-1/2 appearance-none bg-white p-3 border border-gray-300 rounded-lg shadow-inner text-gray-900 focus:ring-primary focus:border-primary transition duration-150" required-if-active>
                        <input type="date" id="end-date-not-in" name="end_date_not-in" class="w-1/2 appearance-none bg-white p-3 border border-gray-300 rounded-lg shadow-inner text-gray-900 focus:ring-primary focus:border-primary transition duration-150" required-if-active>
                    </div>
                </div>

                <div class="query-input-container hidden" data-query-type="donor_zip">
                    <label for="zip-input" class="block text-sm font-semibold text-gray-700 mb-2">Zip Code</label>
                    <input type="text" id="zip-input" name="zip_query" placeholder="e.g., 20001" class="w-full appearance-none bg-white p-3 border border-gray-300 rounded-lg shadow-inner text-gray-900 focus:ring-primary focus:border-primary transition duration-150" required-if-active>
                </div>

            </div>
            <div class="pt-2">
                <button type="submit"
                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-lg text-base font-bold text-white bg-primary hover:bg-primary-hover focus:outline-none focus:ring-4 focus:ring-primary/50 transition duration-200 ease-in-out transform hover:scale-[1.01] active:scale-[0.99]">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    Execute Query
                </button>
            </div>
            
        </form>

        <div id="results-display" class="mt-8 p-4 border-t-4 border-primary/20 rounded-lg bg-gray-50 shadow-inner">
            <h3 class="text-xl font-extrabold text-gray-900 mb-3">
                <span class="text-primary">Query</span> Results
            </h3>
            <div id="results-content" class="text-sm text-gray-600 space-y-2">
                <p>Select one or more criteria above and click 'Execute Query'.</p>
            </div>
        </div>

        <div class="mt-4">
            <a href="index.php"
                class="w-full flex justify-center py-3 px-4 border border-gray-300 rounded-lg shadow-md text-base font-bold text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition duration-200 ease-in-out transform hover:scale-[1.01] active:scale-[0.99]">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                Go to main Page
            </a>
        </div>

        <div class="mt-4">
            <a href="all.php"
                class="w-full flex justify-center py-3 px-4 border border-gray-300 rounded-lg shadow-md text-base font-bold text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition duration-200 ease-in-out transform hover:scale-[1.01] active:scale-[0.99]">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                Go to testdb Page
            </a>
        </div>

    </div>

</body>
</html>