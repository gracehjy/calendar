<?php
    session_start();
    // check if the user is logged in
    if (isset($_SESSION["username"])) {  
        echo '<div class="navbar">';
        // If logged in, display "Profile" and "Add Event" and "Sign Out" links
        echo '<a href="profile.php">Profile</a>';
        echo '<a href="addevent.html">Add Event</a>';
        echo '<a href="eventsList.php">Edit Event</a>';
        echo '<a href="eventsList.php">Delete Event</a>';
        echo '<a href="#" id="logout">Sign Out</a>';
        echo '</div>';
    } else {
        echo '<div class="navbar">';
        // If not logged in, display "Log In" and "Sign Up" links
        echo '<a href="login.html">Log In</a>';
        echo '<a href="signup.html">Sign Up</a>';
        echo '</div>';
    }
?>

