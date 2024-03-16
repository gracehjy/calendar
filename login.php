<?php
session_start();
include("database.php");
header("Content-Type: application/json");

if (isset($_GET["logout"])) {
    // Redirect to login page
    header("Location: login.html");
    session_destroy();
    exit(); 
}

$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str, true);

$username = $json_obj["username"];
$password = $json_obj["password"];

$stmt = $mysqli->prepare("SELECT user_id, hashed_password FROM user_information WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 1) {
    $stmt->bind_result($user_id, $hashed_password);
    $stmt->fetch();

    // verify password and user
    if (password_verify($password, $hashed_password)) {
        $_SESSION["user_id"] = $user_id;
        $_SESSION["username"] = $username;
        $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
        $stmt->close();
        echo json_encode(array("success" => true));
        exit;
    } else {
        $stmt->close();
        echo json_encode(array("success" => false, "message" => "Incorrect Username or Password"));
        exit;
    }
} else {
    $stmt->close();
    echo json_encode(array("success" => false, "message" => "Incorrect Username or Password"));
    exit;
}
?>
