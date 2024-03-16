<?php
    session_start();
    include("database.php");

    // check if the user is logged in, otherwise send them to login.php
    if (!isset($_SESSION["user_id"])) {
        header("Location: login.php");
        exit(); 
    }

    $user_id = $_SESSION['user_id']; // get the user_id

    $mysqli->begin_transaction();

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

    // destroy session
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit();
?>