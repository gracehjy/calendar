<?php
session_start();
include("database.php");

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = $_POST['etitle'];
    $date = $_POST['edate'];
    $tag = $_POST['etag'];

    $stmt = $mysqli->prepare("INSERT INTO events (user_id, title, date, tag)");
    $stmt->bind_param("isss", $user_id, $title, $date, $tag);
    
    if ($stmt->execute()) {
        $response = array("success" => true, "message" => "Event added successfully");
        echo json_encode($response);
    } else {
        $response = array("success" => false, "message" => "Error adding event");
        echo json_encode($response);
    }

    $stmt->close();
} else {
    header('Location: calendar.php');
    exit();
}
?>
