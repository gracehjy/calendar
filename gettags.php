<?php
    session_start();
    include("database.php");

    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit();
    }

    $user_id = $_SESSION['user_id'];

    $stmt = $mysqli->prepare("SELECT DISTINCT tag FROM tags WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $tags = array();
    while ($row = $result->fetch_assoc()) {
        $tags[] = $row['tag'];
    }

    $stmt->close();

    header('Content-Type: application/json');
    echo json_encode($tags);
?>
