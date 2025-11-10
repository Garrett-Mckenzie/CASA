<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Search Application</title>
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
            const searchInput = document.getElementById('donor-search-input');
            const searchForm = document.getElementById('search-form');
            const resultsContent = document.getElementById('results-content');
            
            // --- ASYNCHRONOUS SEARCH FUNCTION ---
            async function fetchDonors(query) {
                // IMPORTANT: Ensure this path is correct relative to where test.php is run.
                // Assuming search_donors.php is in the same directory.
                const url = `search_donors.php?query=${encodeURIComponent(query)}`;
                
                try {
                    const response = await fetch(url);
                    
                    // Check if response is JSON (important for debugging)
                    const contentType = response.headers.get("content-type");
                    if (contentType && contentType.indexOf("application/json") === -1) {
                         throw new Error(`Server returned non-JSON data. Check your PHP script for errors.`);
                    }

                    if (!response.ok) {
                        const errorData = await response.json();
                        throw new Error(`HTTP error! Status: ${response.status}. Details: ${errorData.details || errorData.error}`);
                    }
                    
                    const donors = await response.json();
                    return donors;
                } catch (error) {
                    console.error("Fetch error:", error);
                    // Display an error message to the user
                    resultsContent.innerHTML = `<p class="text-sm text-red-600 bg-red-50 p-3 rounded-lg border border-red-200">
                        🚨 An error occurred while fetching data. Check the console for details. Error: ${error.message}
                    </p>`;
                    return [];
                }
            }

            // 1. Handle form submission (Search button click)
            searchForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const searchQuery = searchInput.value;

                // 2. Fetch the actual data from the server
                const results = await fetchDonors(searchQuery);
                renderResults(searchQuery, results);
            });
            
            // 3. Render the search results
            function renderResults(query, donors) {
                resultsContent.innerHTML = '';
                
                if (donors.length === 0 && query.trim() !== "") {
                    resultsContent.innerHTML = `
                        <p class="text-sm text-yellow-600 bg-yellow-50 p-3 rounded-lg border border-yellow-200">
                            No donors found matching ${query} **${donors.length}**. Please try a different name.
                        </p>
                    `;
                } else if (donors.length === 0 && query.trim() === "") {
                    resultsContent.innerHTML = `<p class="text-sm text-gray-500 p-3">Enter a name to begin searching.</p>`;
                } 
                else {
                    let html = `<p class="text-sm font-medium text-gray-700 mb-3">${donors.length} donor(s) found matching **"${query}"**:</p>`;
                    
                    donors.forEach(donor => {
                        html += `
                            <div class="bg-white p-4 mb-2 rounded-lg shadow-sm border border-gray-100 transition duration-150 hover:shadow-md">
                                <p class="text-base font-semibold text-gray-900">${donor.first} ${donor.last}</p>
                                <p class="text-xs text-indigo-600 font-mono mt-1">${donor.email || 'No email'} | ${donor.city || 'N/A'}, ${donor.state || 'N/A'}</p>
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
                <span class="text-primary">Donor</span> Search
            </h1>
            <p class="mt-2 text-sm text-gray-500">
                Enter a donor's name (first or last) to search the database.
            </p>
        </header>

        <form id="search-form" class="space-y-6">
            
            <div>
                <label for="donor-search-input" class="block text-sm font-semibold text-gray-700 mb-2">
                    Donor Name
                </label>
                <div class="relative">
                    <input 
                        type="text" 
                        id="donor-search-input" 
                        name="query"
                        placeholder="e.g., Jane Doe"
                        class="w-full appearance-none bg-white p-3 border border-gray-300 rounded-lg shadow-inner text-gray-900 focus:ring-primary focus:border-primary transition duration-150"
                        required>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit"
                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-lg text-base font-bold text-white bg-primary hover:bg-primary-hover focus:outline-none focus:ring-4 focus:ring-primary/50 transition duration-200 ease-in-out transform hover:scale-[1.01] active:scale-[0.99]">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    Search Donors
                </button>
            </div>
            
        </form>

        <div id="results-display" class="mt-8 p-4 border-t-4 border-primary/20 rounded-lg bg-gray-50 shadow-inner">
            <h3 class="text-xl font-extrabold text-gray-900 mb-3">
                <span class="text-primary">Donor</span> Matches
            </h3>
            <div id="results-content" class="text-sm text-gray-600 space-y-2">
                <p>Enter a name above and click 'Search Donors'.</p>
            </div>
        </div>

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
            <a href="all.php"
                class="w-full flex justify-center py-3 px-4 border border-gray-300 rounded-lg shadow-md text-base font-bold text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition duration-200 ease-in-out transform hover:scale-[1.01] active:scale-[0.99]">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                Go to testdb Page
            </a>
        </div>

    </div>

    

</body>
</html>