<?php
header('Content-Type: application/json');

// Set the timezone to Asia/Kolkata
date_default_timezone_set('Asia/Kolkata');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $day = ucfirst(strtolower(date('l'))); // Get the current day based on the server's timezone
    $filePath = __DIR__ . '/app/med_schedule.json';

    if (!file_exists($filePath)) {
        echo json_encode(["error" => "Medicine schedule file not found."]);
        http_response_code(404);
        exit;
    }

    $jsonData = file_get_contents($filePath);
    $schedule = json_decode($jsonData, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(["error" => "Invalid JSON format in medicine schedule file."]);
        http_response_code(500);
        exit;
    }

    if (isset($schedule[$day])) {
        $allMedicines = [];
        foreach ($schedule[$day] as $time => $medicines) {
            $medicinesList = implode(", ", $medicines); // Convert the medicines array to a string
            $allMedicines[] = "- $time is $medicinesList.";
        }
        $message = "Your medicine for $day:\n" . implode("\n", $allMedicines);
        echo json_encode(["message" => $message]);
    } else {
        echo json_encode(["message" => "No medicines scheduled for $day."]);
    }
} else {
    echo json_encode(["error" => "Invalid request."]);
    http_response_code(400);
}
?>
