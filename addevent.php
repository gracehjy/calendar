<?php
session_start();
include("database.php");

// check if user logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Read JSON data from the request body
$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str, true);

// Check if the JSON data was successfully decoded
if ($json_obj !== null) {
    // Extract data from the JSON object
    $title = $json_obj["etitle"];
    $date = $json_obj["edate"];
    $tag = $json_obj["etag"];
    $newTag = $json_obj["newtag"];
    $starttime = $json_obj['starttime'];
    $endtime = $json_obj['endtime'];

    if (!empty($newTag)) {
        $tag = $newTag;

        $event = $mysqli->prepare("INSERT INTO tags (user_id, tag_name) VALUES (?, ?)");
        $event->bind_param("is", $user_id, $tag);
        $event->execute();
        $event->close();
    }

    // Prepare and execute SQL statement to insert event
    $event = $mysqli->prepare("INSERT INTO events (user_id, title, date, tag, starttime, endtime) VALUES (?, ?, ?, ?, ?, ?)");
    $event->bind_param("isssss", $user_id, $title, $date, $tag, $starttime, $endtime);

    if ($event->execute()) {
        $event_id = $event->insert_id;

        $event->close();

        // Send the event ID back as part of the response
        $response = array("success" => true, "message" => "Event added successfully", "event_id" => $event_id);
        echo json_encode($response);
    } else {
        $response = array("success" => false, "message" => "Error adding event");
        echo json_encode($response);
    }
} else {
    // Handle case where JSON data is invalid or not provided
    $response = array("success" => false, "message" => "Invalid or missing JSON data");
    echo json_encode($response);
}
?>
