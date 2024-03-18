<?php
session_start();
include("database.php");
header("Content-Type: application/json");

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Read JSON data from the request body
$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str, true);

// Check if required fields are present in the JSON object
if (!isset($json_obj["etitle"], $json_obj["edate"], $json_obj["starttime"], $json_obj["endtime"])) {
    $response = array("success" => false, "message" => "Missing required fields");
    echo json_encode($response);
    exit();
}

// Extract data from the JSON object
$title = $json_obj["etitle"];
$date = $json_obj["edate"];
$tag = isset($json_obj["etag"]) ? $json_obj["etag"] : null;
$newTag = isset($json_obj["newtag"]) ? $json_obj["newtag"] : null;
$starttime = $json_obj['starttime'];
$endtime = $json_obj['endtime'];

// Insert new tag if provided
if (!empty($newTag)) {
    $tag = $newTag;
    $insertTag = $mysqli->prepare("INSERT INTO tags (user_id, tag_name) VALUES (?, ?)");
    $insertTag->bind_param("is", $user_id, $tag);
    if (!$insertTag->execute()) {
        $response = array("success" => false, "message" => "Error adding new tag: " . $insertTag->error);
        echo json_encode($response);
        exit();
    }
    $insertTag->close();
}

// Prepare and execute SQL statement to insert event
$insertEvent = $mysqli->prepare("INSERT INTO events (user_id, title, date, tag, starttime, endtime) VALUES (?, ?, ?, ?, ?, ?)");
$insertEvent->bind_param("isssss", $user_id, $title, $date, $tag, $starttime, $endtime);
if ($insertEvent->execute()) {
    $event_id = $insertEvent->insert_id;
    $insertEvent->close();
    // Send the event ID back as part of the response
    $response = array("success" => true, "message" => "Event added successfully", "event_id" => $event_id);
    echo json_encode($response);
} else {
    $response = array("success" => false, "message" => "Error adding event: " . $insertEvent->error);
    echo json_encode($response);
}
?>
