<?php
require_once '../database/dbinfo.php';

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
    <title>Search Forum</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-4">

    <!-- Main Container -->
    <div class="w-full max-w-md bg-white shadow-xl rounded-xl p-8 space-y-6">
        <header class="text-center">
            <h1 class="text-3xl font-extrabold text-gray-900">Event Location Search</h1>
            <p class="mt-2 text-sm text-gray-500">Select a location to filter events from the database.</p>
        </header>

        <!-- Search Form -->
        <form action="#" method="get" class="space-y-4">
            
            <!-- Dropdown Section -->
            <div>
                <label for="event-location" class="block text-sm font-medium text-gray-700 mb-1">
                    Select a Location
                </label>
                <div class="relative">
                    <select id="event-location" name="location" required
                        class="mt-1 block w-full py-3 pl-3 pr-10 border border-gray-300 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-base cursor-pointer transition duration-150 ease-in-out appearance-none">
                        
                        <option value="">-- Select a Location --</option>
            
                    <?php foreach ($locations as $location):?>
                        <option value="<?php echo htmlspecialchars($location); ?>"> <?php echo htmlspecialchars($location); ?> </option>
                    <?php endforeach; ?>

                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>            
        </form>

        <!-- Search Button -->
         
        <div class="pt-2">
            <button type="submit"
                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out transform hover:scale-[1.01]">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                Search Events
            </button>
        </div>
    </div>

</body>
</html>