<?php
    ini_set("session.cookie_httponly", 1);
    session_start();
    include("database.php");

    // check if the user is logged in
    if (!isset($_SESSION["username"])) {
        echo json_encode(array("success" => false, "message" => "Please log in first."));
        exit();
    }

    $user_id = htmlentities($_SESSION['user_id']);

    // get tags for user
    $stmt = $mysqli->prepare("SELECT tag_name FROM tags WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    $result = $stmt->get_result();

    $tags = array();
    while ($row = $result->fetch_assoc()) {
        $tags[] = $row['tag_name'];
    }

    $stmt->close();

    header('Content-Type: application/json');
    echo json_encode($tags);
?>
