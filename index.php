<?php
// Set the timezone to Asia/Kolkata
date_default_timezone_set('Asia/Kolkata');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $day = ucfirst(strtolower(date('l'))); // Get the current day based on the server's timezone
    $filePath = __DIR__ . '/app/med_schedule.json';

    if (!file_exists($filePath)) {
        echo "<h1>Error: Medicine schedule file not found.</h1>";
        http_response_code(404);
        exit;
    }

    $jsonData = file_get_contents($filePath);
    $schedule = json_decode($jsonData, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "<h1>Error: Invalid JSON format in medicine schedule file.</h1>";
        http_response_code(500);
        exit;
    }

    if (isset($schedule[$day])) {
        $allMedicines = [];
        foreach ($schedule[$day] as $time => $medicines) {
            $medicinesList = implode(", ", $medicines); // Convert the medicines array to a string
            $allMedicines[] = "<li><strong>$time:</strong> $medicinesList</li>";
        }
        $message = "<h1>Your medicine schedule for $day:</h1><ul>" . implode("", $allMedicines) . "</ul>";
    } else {
        $message = "<h1>No medicines scheduled for $day.</h1>";
    }

    // Output the HTML
    echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicine Schedule</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
            background-color: #f9f9f9;
            color: #333;
        }
        h1 {
            color: #2c3e50;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            margin: 10px 0;
            padding: 10px;
            background: #ecf0f1;
            border-radius: 5px;
        }
        strong {
            color: #2980b9;
        }
    </style>
</head>
<body>
    $message
</body>
</html>
HTML;
} else {
    echo "<h1>Error: Invalid request.</h1>";
    http_response_code(400);
}
