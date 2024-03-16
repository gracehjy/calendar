<?php
session_start();
include("database.php");

// check if user logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = $_POST['etitle'];
    $date = $_POST['edate'];
    $tag = $_POST['etag'];
    $newTag = $_POST['newtag'];

    if (!empty($newTag)) {
        $tag = $newTag;

        $event = $mysqli->prepare("INSERT INTO tags (user_id, tag_name) VALUES (?, ?)");
        $event->bind_param("is", $user_id, $tag);
        $event->execute();
        $event->close();
    }

    // Prepare and execute SQL statement to insert event
    $event = $mysqli->prepare("INSERT INTO events (user_id, title, date, tag) VALUES (?, ?, ?, ?)");
    $event->bind_param("isss", $user_id, $title, $date, $tag);
    
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
    header('Location: calendar.php');
    exit();
}
?>
