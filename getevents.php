<?php

    session_start();
    include("database.php");

    // check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit();
    }

    $user_id = $_SESSION['user_id'];

    $addEvent = $mysqli->prepare("SELECT * FROM events WHERE user_id = ?");
    $addEvent->bind_param("i", $user_id);
    $addEvent->execute();
    $result = $addEvent->get_result();

    // get events
    $events = [];
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }

    $addEvent->close();
    
    echo json_encode($events);
?>
