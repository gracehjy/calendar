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

    // get user's own events
    $ownEvents = $mysqli->prepare("SELECT * FROM events WHERE user_id = ?");
    $ownEvents->bind_param("i", $user_id);
    $ownEvents->execute();
    $result1 = $ownEvents->get_result();


    // all events for user
    $events = [];
    while ($row = $result1->fetch_assoc()) {
        $events[] = $row;
    }

    $ownEvents->close();

    echo json_encode($events);
?>