<?php
    session_start();
    include("database.php");
    header("Content-Type: application/json");

    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        $response = array("success" => false, "message" => "User not logged in");
        echo json_encode($response);
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $event_id = $_GET['event_id'];

    $json_str = file_get_contents('php://input');
    $json_obj = json_decode($json_str, true);

    $title = $json_obj['title']; 
    $date = $json_obj['date']; 
    $tag = $json_obj['tag']; 
    $newTag = isset($json_obj['newtag']) ? $json_obj['newtag'] : null;
    $starttime = $json_obj['starttime']; 
    $endtime = $json_obj['endtime']; 


    // check if there's a new tag
    if (!empty($newTag)) {
        $tag = $newTag;
        $insertTag = $mysqli->prepare("INSERT INTO tags (user_id, tag_name) VALUES (?, ?)");
        $insertTag->bind_param("is", $user_id, $tag);
        if (!$insertTag->execute()) {
            $response = array("success" => false, "message" => "Error adding new tag: " . $insertTag->error);
            echo json_encode($response);
            exit();
        }
        $insertTag->close();
    }

    // update event in the SQL table
    $updateEvent = $mysqli->prepare("UPDATE events SET title = ?, date = ?, tag = ?, start_time = ?, end_time = ? WHERE event_id = ? AND user_id = ?");
    if (!$updateEvent) {
        $response = array("success" => false, "message" => "Error preparing update statement: " . $mysqli->error);
        echo json_encode($response);
        exit();
    }

    $updateEvent->bind_param("sssssii", $title, $date, $tag, $starttime, $endtime, $event_id, $user_id);
    if ($updateEvent->execute()) {
        $response = array("success" => true, "message" => "Event updated successfully");
    } else {
        $response = array("success" => false, "message" => "Error updating event: " . $updateEvent->error);
    }
    $updateEvent->close();

    echo json_encode($response);
    exit();
?>
