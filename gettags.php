<?php
session_start();
include("database.php");

// check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// get tags for user
$stmt = $mysqli->prepare("SELECT tag_name FROM tags WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();

// // Check for errors in query execution
// if ($stmt->error) {
//     $response = array("success" => false, "message" => "Database error: " . $stmt->error);
//     echo json_encode($response);
//     exit();
// }

$result = $stmt->get_result();

$tags = array();
while ($row = $result->fetch_assoc()) {
    $tags[] = $row['tag_name'];
}

$stmt->close();

header('Content-Type: application/json');
echo json_encode($tags);
?>
