<?php
    ini_set("session.cookie_httponly", 1);
    session_start();
    include("database.php");
    header("Content-Type: application/json");

    // check if the user is logged in
    if (!isset($_SESSION["username"])) {
        echo json_encode(array("success" => false, "message" => "Please log in first."));
        exit();
    }

    // session's user id
    $owner_user_id = htmlentities($_SESSION['user_id']);

    $json_str = file_get_contents('php://input');
    $json_obj = json_decode($json_str, true);

    // change event_id to be an integer since we passed it in as a string
    $event_id = isset($json_obj["event_id"]) ? (int)$json_obj["event_id"] : null;

    // trim username typed in
    $share_to_username = isset($json_obj["share_to_username"]) ? trim($json_obj["share_to_username"]) : '';

    // get the user_id of share_to_username
    $query = $mysqli->prepare("SELECT user_id FROM user_information WHERE username = ?");
    $query->bind_param("s", $share_to_username);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows === 0) {
        $response = array("success" => false, "message" => "Username does not exist");
        echo json_encode($response);
        exit();
    }

    $row = $result->fetch_assoc();
    $share_user_id = $row["user_id"];

    // check if the event is already shared with the user
    $check_shared = $mysqli->prepare("SELECT * FROM events WHERE event_id = ? AND user_id = ?");
    $check_shared->bind_param("ii", $event_id, $share_user_id);
    $check_shared->execute();
    $check_shared_result = $check_shared->get_result();

    if ($check_shared_result->num_rows > 0) {
        $response = array("success" => false, "message" => "Event is already shared with this user");
        echo json_encode($response);
        exit();
    }

    // get event details
    $get_event_details = $mysqli->prepare("SELECT title, date, start_time, end_time, tag FROM events WHERE event_id = ?");
    $get_event_details->bind_param("i", $event_id);
    $get_event_details->execute();
    $event_details_result = $get_event_details->get_result();

    if ($event_details_result->num_rows === 0) {
        $response = array("success" => false, "message" => "Event not found");
        echo json_encode($response);
        exit();
    }

    $event_details = $event_details_result->fetch_assoc();
    $title = $event_details["title"];
    $date = $event_details["date"];
    $start_time = $event_details["start_time"];
    $end_time = $event_details["end_time"];
    $tag = $event_details["tag"];

    // put event details into events table for the shared user
    $insert_shared_event = $mysqli->prepare("INSERT INTO events (user_id, title, date, start_time, end_time, tag) VALUES (?, ?, ?, ?, ?, ?)");
    $insert_shared_event->bind_param("isssss", $share_user_id, $title, $date, $start_time, $end_time, $tag);

    if ($insert_shared_event->execute()) {
        $response = array("success" => true, "message" => "Event shared successfully");
    } else {
        $response = array("success" => false, "message" => "Error: share failed");
    }

    $insert_shared_event->close();
    $mysqli->close();

    echo json_encode($response);
?>
