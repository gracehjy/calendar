<?php
    ini_set("session.cookie_httponly", 1);
    session_start();
    include("database.php");

    // check if the user is logged in
    if (!isset($_SESSION["username"])) {
        echo json_encode(array("success" => false, "message" => "Please log in first."));
        exit();
    }

    $user_id = htmlentities($_SESSION['user_id']); // get the user_id

    $mysqli->begin_transaction();

    // delete user tags
    $stmt = $mysqli->prepare("DELETE FROM tags WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    // delete user events
    $stmt = $mysqli->prepare("DELETE FROM events WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    // delete the user from the user_information table
    $stmt = $mysqli->prepare("DELETE FROM user_information WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    $mysqli->commit();

    // destroy session and update loggedIn status
    session_unset();
    session_destroy();
    echo json_encode(array("loggedIn" => false));
    exit();
?>