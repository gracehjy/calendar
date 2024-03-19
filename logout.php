<?php
session_start();

// destroy sessions
if (isset($_SESSION["username"])) {
    session_destroy();
    echo json_encode(array("loggedIn" => false));
    exit();
} 
?>
