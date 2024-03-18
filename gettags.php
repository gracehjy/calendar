<?php
session_start();
include("database.php");

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Prepare and execute SQL statement to fetch distinct tags for the user
$stmt = $mysqli->prepare("SELECT DISTINCT tag FROM tags WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();

// Check for errors in query execution
if (!$stmt) {
    $error_message = $mysqli->error;
    $response = array("success" => false, "message" => "Database error: " . $error_message);
    echo json_encode($response);
    exit();
}

// Get the result
$result = $stmt->get_result();

// Fetch tags into an array
$tags = array();
while ($row = $result->fetch_assoc()) {
    $tags[] = $row['tag'];
}

// Close the statement
$stmt->close();

// Return tags as JSON response
header('Content-Type: application/json');
echo json_encode($tags);
?>
