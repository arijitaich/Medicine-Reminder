<?php
header('Content-Type: application/json');

function getTimeSlot($time) {
    $time = strtotime($time); // Convert time to a timestamp
    $timeSlots = [
        "Before Breakfast" => ["06:00 AM", "08:00 AM"],
        "After Breakfast" => ["08:01 AM", "11:00 AM"],
        "After Lunch" => ["12:00 PM", "03:00 PM"],
        "Evening" => ["04:00 PM", "06:00 PM"],
        "After Dinner" => ["07:00 PM", "09:00 PM"],
        "Bedtime" => ["09:01 PM", "11:59 PM"]
    ];

    foreach ($timeSlots as $slot => $range) {
        $start = strtotime($range[0]);
        $end = strtotime($range[1]);
        if ($time >= $start && $time <= $end) {
            return $slot;
        }
    }
    return null; // Return null if no matching slot is found
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['day']) && isset($_GET['time'])) {
    $day = ucfirst(strtolower($_GET['day'])); // Capitalize the first letter of the day
    $time = $_GET['time']; // Time in format like "08:00 AM"
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

    $timeSlot = getTimeSlot($time); // Map the time to a time slot
    if ($timeSlot && isset($schedule[$day][$timeSlot])) {
        $medicines = implode(", ", $schedule[$day][$timeSlot]); // Convert the medicines array to a string
        $message = "Your medicine for $day $timeSlot is $medicines";
        echo json_encode(["message" => $message]);
    } else {
        echo json_encode(["message" => "No medicines scheduled for $day at $time."]);
    }
} else {
    echo json_encode(["error" => "Invalid request. Please provide both 'day' and 'time' parameters."]);
    http_response_code(400);
}
?>
