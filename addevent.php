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

$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str, true);

$title = $json_obj["etitle"];
$date = $json_obj["edate"];
$tag = $json_obj['etag'];
//isset($json_obj["etag"]) ? $json_obj["etag"] : null;
$newTag = isset($json_obj["newtag"]) ? $json_obj["newtag"] : null;
$starttime = $json_obj['starttime'];
$endtime = $json_obj['endtime'];

// check if there's a new tag
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
$insertEvent = $mysqli->prepare("INSERT INTO events (user_id, title, date, tag, start_time, end_time) VALUES (?, ?, ?, ?, ?, ?);");
if(!$insertEvent) {
    $response = array("success" => false, "message" => "Event can not be added");
    echo json_encode($response);
}
$insertEvent->bind_param("isssss", $user_id, $title, $date, $tag, $starttime, $endtime);
$insertEvent->execute();
$insertEvent->close();
$response = array("success" => true, "message" => "Event added successfully");
echo json_encode($response);
exit();
?>
