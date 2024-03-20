<?php
    ini_set("session.cookie_httponly", 1);
    session_start();
    include("database.php");

    // check if the user is logged in
    if (!isset($_SESSION["username"])) {
        echo json_encode(array("success" => false, "message" => "Please log in first."));
        exit();
    }

    if (!isset($_GET["event_id"])) {
        echo "Invalid request.";
        exit();
    }

    $user_id = htmlentities($_SESSION["user_id"]);
    $event_id = htmlentities($_GET["event_id"]); 

    // check if the current user is the one who created the event
    $stmt = $mysqli->prepare("SELECT user_id FROM events WHERE event_id = ?");
    $stmt->bind_param("i", $event_id); 
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $event_owner_id = $row["user_id"];

        if ($user_id != $event_owner_id) {
            echo "You do not have permission to delete this event.";
            exit();
        } 
        else {
            $stmt = $mysqli->prepare("DELETE FROM events WHERE event_id = ?");
            $stmt->bind_param("i", $event_id);
            $stmt->execute();
            $stmt->close();

            header("Location: eventsList.php");
            exit();
        }
    } 
    else {
        echo "Event not found.";
        exit();
    }
?>
