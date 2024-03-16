<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" type="text/css" href="calendar.css">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Profile Page</title>
    </head>
    <body>
        <?php 
        session_start();
        include("navbar.php");
        include ("database.php");

        // check if the user is logged in, otherwise send them to login.php
        if (!isset($_SESSION["username"])) {
            header("Location: login.php");
            exit(); 
        }

        $username = $_SESSION["username"];
        echo "<h2> Hello, ".$username."!</h2>";
        ?>
        <p><a href="eventsList.php">View Upcoming Events</a></p>
        <p><a href="changeuser.html">Change Username</a></p>
        <p><a href='deleteaccount.php' onclick='return confirm("Are you sure you want to delete this account?")'>Delete Account</a></p>
    </body>
</html>