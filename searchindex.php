<?php
require_once 'database/dbinfo.php';

session_start();
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
    <style>

        body {
            font-family: 'Quicksand', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            margin-top: 20px;
            color: #333;
        }


        .content-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.15);
            max-width: 800px;
            margin: 20px auto;
        }

        h3 {
            margin-top: 0;
            color: #00447b;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }


        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            color: #444;
            margin-bottom: 8px;
            font-size: 14px;
        }

        input[type="text"],
        input[type="date"] {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border-radius: 8px;
            border: 1px solid #aaa;
            box-sizing: border-box;
            font-family: 'Quicksand', sans-serif;
        }

        input:focus {
            outline: 2px solid #00447b;
            border-color: #00447b;
        }


        .checkbox-container {
            background: #f8f9fa;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .checkbox-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 10px;
        }

        .checkbox-item {
            display: flex;
            align-items: center;
            background: white;
            padding: 10px;
            border: 1px solid #eee;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .checkbox-item:hover {
            background: #eef4fb;
            border-color: #bbcce6;
        }

        .checkbox-item input {
            transform: scale(1.3);
            margin-right: 10px;
        }


        .btn-primary {
            background: #00447b; /* CASA Blue */
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.25s ease, transform 0.2s ease;
            width: 100%;
            display: block;
            text-align: center;
        }

        .btn-primary:hover {
            background: #003366;
            transform: translateY(-2px);
        }


        .hidden {
            display: none;
        }


        .results-box {
            margin-top: 30px;
            border-top: 2px solid #eee;
            padding-top: 20px;
        }

        .result-card {
            background: white;
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            transition: transform 0.2s;
        }

        .result-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .result-title {
            font-weight: bold;
            color: #333;
            margin: 0 0 5px 0;
            font-size: 16px;
        }

        .result-detail {
            color: #00447b;
            font-size: 13px;
            font-family: monospace;
            margin: 0;
        }

        .alert-box {
            padding: 15px;
            border-radius: 8px;
            font-size: 14px;
        }
        .alert-info { background: #e3f2fd; color: #0d47a1; border: 1px solid #bbdefb; }
        .alert-warning { background: #fff3e0; color: #e65100; border: 1px solid #ffe0b2; }
        .alert-error { background: #ffebee; color: #b71c1c; border: 1px solid #ffcdd2; }


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
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const queryTypeCheckboxes = document.querySelectorAll('input[name="query_type"]');
            const formContainers = document.querySelectorAll('.query-input-container');
            const searchForm = document.getElementById('search-form');
            const resultsContent = document.getElementById('results-content');

            function updateFormVisibility() {
                const selectedQueryTypes = Array.from(queryTypeCheckboxes)
                    .filter(checkbox => checkbox.checked)
                    .map(checkbox => checkbox.value);

                if (selectedQueryTypes.length === 0) {
                    selectedQueryTypes.push('all_donors');
                }

                formContainers.forEach(container => {
                    const containerType = container.getAttribute('data-query-type');
                    if (selectedQueryTypes.includes(containerType)) {
                        container.classList.remove('hidden');
                        container.querySelectorAll('[required-if-active]').forEach(input => {
                            input.setAttribute('required', 'required');
                        });
                    } else {
                        container.classList.add('hidden');
                        container.querySelectorAll('[required-if-active]').forEach(input => {
                            input.removeAttribute('required');
                            if (input.type === 'text' || input.type === 'date') {
                                input.value = '';
                            }
                        });
                    }
                });
            }

            queryTypeCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateFormVisibility);
            });
            updateFormVisibility();

            async function fetchResults(formData) {
                const selectedQueries = formData.getAll('query_type');
                if (selectedQueries.length === 0) selectedQueries.push('all_donors');

                let endpoint = 'search_multi_criteria.php';
                let params = new URLSearchParams();
                let displayQuery = '';

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
                            if (selectedQueries.length === 1) displayQuery = 'All Donors';
                            break;
                    }
                });

                displayQuery = displayQuery.replace(/ \| $/, '');
                const url = `${endpoint}?${params.toString()}`;

                try {
                    const response = await fetch(url);
                    const contentType = response.headers.get("content-type");
                    if (contentType && contentType.indexOf("application/json") === -1) {
                         throw new Error(`Server returned non-JSON data.`);
                    }
                    if (!response.ok) {
                        const errorData = await response.json();
                        throw new Error(errorData.details || errorData.error || "HTTP Error");
                    }
                    const results = await response.json();
                    return { results, displayQuery };
                } catch (error) {
                    console.error("Fetch error:", error);
                    resultsContent.innerHTML = `<div class="alert-box alert-error">
                        <strong>Error:</strong> An error occurred while fetching data for <em>${displayQuery}</em>.<br>
                        ${error.message}
                    </div>`;
                    return { results: [], displayQuery };
                }
            }

            searchForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const button = searchForm.querySelector('button[type="submit"]');
                const originalText = button.innerText;

                button.disabled = true;
                button.innerText = "Searching...";

                const formData = new FormData(searchForm);
                const { results, displayQuery } = await fetchResults(formData);
                renderResults(displayQuery, results);

                button.disabled = false;
                button.innerText = originalText;
            });

            function renderResults(displayQuery, data) {
                resultsContent.innerHTML = '';

                if (!data || data.length === 0) {
                    resultsContent.innerHTML = `
                        <div class="alert-box alert-warning">
                            No data found for: <strong>${displayQuery}</strong>.
                        </div>
                    `;
                } else {
                    let html = `<p class="alert-box alert-info">Found <strong>${data.length}</strong> result(s) for <strong>${displayQuery}</strong>:</p>`;

                    data.forEach(item => {
                        const name = item.first && item.last ? `${item.first} ${item.last}` : item.name || 'Donor/Event Name';
                        const detail = item.email || item.name || `${item.city || 'N/A'}, ${item.state || 'N/A'}` || 'Details N/A';
                        const count = item.donation_count ? ` | Donations: ${item.donation_count}` : '';


                        html += `
                            <div class="result-card">
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
</head>

<body>
    <?php require_once('header.php') ?>

    <h1>Donor Search</h1>

    <div class="content-card">
        <h3>Advanced Search Criteria</h3>
        <p style="margin-bottom: 20px; color: #666; font-size: 14px;">
            Select one or more criteria below. Donors must match <strong>ALL</strong> selected criteria.
        </p>

        <form id="search-form">

            <div class="checkbox-container">
                <label style="margin-bottom: 10px;">Select Query Mode(s):</label>
                <div class="checkbox-grid">
                    <label class="checkbox-item">
                        <input type="checkbox" name="query_type" value="donor_name">
                        <span>Donor by Name</span>
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="query_type" value="donor_events">
                        <span>Donor by Events</span>
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="query_type" value="event_donors">
                        <span>Event Return Donors</span>
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="query_type" value="donor_zip">
                        <span>Donor Zip</span>
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="query_type" value="donors_by_donation_range_in">
                        <span>Donors IN Date Range</span>
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="query_type" value="donors_by_donation_range_not_in">
                        <span>Donors NOT IN Date Range</span>
                    </label>
                </div>
            </div>

            <div id="dynamic-inputs">

                <div class="query-input-container hidden form-group" data-query-type="donor_name">
                    <label for="donor-name-input-name">Donor Name (First or First & Last)</label>
                    <input type="text" id="donor-name-input-name" name="donor_name_query_name" placeholder="e.g., Jane Doe" required-if-active>
                </div>

                <div class="query-input-container hidden form-group" data-query-type="donor_events">
                    <label for="donor-name-input-events">Donor Name for Events</label>
                    <input type="text" id="donor-name-input-events" name="donor_name_query_events" placeholder="e.g., Jane Doe" required-if-active>
                </div>

                <div class="query-input-container hidden form-group" data-query-type="event_donors">
                    <label for="donor-name-input-event-search">Event Name</label>
                    <input type="text" id="donor-name-input-event-search" name="event_query" placeholder="e.g., Gala 2024" required-if-active>
                </div>

                <div class="query-input-container hidden form-group" data-query-type="donor_zip">
                    <label for="zip-input">Donor Zip Code</label>
                    <input type="text" id="zip-input" name="zip_query" placeholder="e.g., 22401" required-if-active>
                </div>

                <div class="query-input-container hidden form-group" data-query-type="donors_by_donation_range_in">
                    <label for="start-date-in">Start Date (YYYY-MM-DD)</label>
                    <input type="date" id="start-date-in" name="start_date_in" required-if-active>
                    <label for="end-date-in" style="margin-top: 10px;">End Date (YYYY-MM-DD)</label>
                    <input type="date" id="end-date-in" name="end_date_in" required-if-active>
                </div>

                <div class="query-input-container hidden form-group" data-query-type="donors_by_donation_range_not_in">
                    <label for="start-date-not-in">Start Date (YYYY-MM-DD)</label>
                    <input type="date" id="start-date-not-in" name="start_date_not_in" required-if-active>
                    <label for="end-date-not-in" style="margin-top: 10px;">End Date (YYYY-MM-DD)</label>
                    <input type="date" id="end-date-not-in" name="end_date_not_in" required-if-active>
                </div>

                <div class="query-input-container hidden form-group" data-query-type="all_donors">
                    <p class="alert-box alert-info">This returns a list of <strong>ALL</strong> donors because no specific criteria were selected. Click "Execute Query".</p>
                </div>

            </div>

            <button type="submit" class="btn-primary">Execute Query</button>

        </form>

        <div id="results-display" class="results-box">
            <h3>Results</h3>
            <div id="results-content">
                <p style="color: #777; font-style: italic;">Results will appear here...</p>
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