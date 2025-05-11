<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['day']) && isset($_GET['time'])) {
    $day = ucfirst(strtolower($_GET['day'])); // Capitalize the first letter of the day
    $time = ucwords(strtolower($_GET['time'])); // Capitalize the first letter of each word in time
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

    if (isset($schedule[$day][$time])) {
        $medicines = implode(", ", $schedule[$day][$time]); // Convert the medicines array to a string
        $message = "Your medicine for $day $time is $medicines";
        echo json_encode(["message" => $message]);
    } else {
        echo json_encode(["message" => "No medicines scheduled for $day at $time."]);
    }
} else {
    echo json_encode(["error" => "Invalid request. Please provide both 'day' and 'time' parameters."]);
    http_response_code(400);
}
?>
