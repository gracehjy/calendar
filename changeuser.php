<?php
session_start();
header("Content-Type: application/json");

// include database connection script
include("database.php");

// check if the user is logged in
if (!isset($_SESSION["username"])) {
    echo json_encode(array("success" => false, "message" => "Please log in first."));
    exit();
}

// get current username
$currentUsername = $_SESSION['username'];

// get new username and password from the form

$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str, true);

$newUsername = $json_obj["new_username"];
$password = $json_obj["password"];

// check if password is correct using prepared queries
$stmt = $mysqli->prepare("SELECT hashed_password FROM user_information WHERE username = ?");
$stmt->bind_param("s", $currentUsername);
$stmt->execute();
$result = $stmt->get_result();
$hashedPassword = $result->fetch_assoc()['hashed_password'];
$stmt->close();

// verify password
if (password_verify($password, $hashedPassword)) {
    // check if the new username already exists
    $check = $mysqli->prepare("SELECT username FROM user_information WHERE username = ?");
    $check->bind_param("s", $newUsername);
    $check->execute();
    $checkResult = $check->get_result();
    $check->close();

    if ($checkResult->num_rows > 0) {
        echo json_encode(array("success" => false, "message" => "Sorry, that username already exists. Try again!"));
    } else {
        // update the username in the database
        $stmt = $mysqli->prepare("UPDATE user_information SET username = ? WHERE username = ?");
        $stmt->bind_param("ss", $newUsername, $currentUsername);
        $stmt->execute();
        $_SESSION["username"] = $newUsername;
        echo json_encode(array("success" => true, "message" => "Username changed successfully."));
    }
} else {
    echo json_encode(array("success" => false, "message" => "The inputted password is incorrect. Try again!"));
}
?>