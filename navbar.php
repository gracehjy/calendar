<?php
    session_start();
    // check if the user is logged in
    if (isset($_SESSION["username"])) {  
        echo '<div class="navbar">';
        echo '<a href="profile.php">Profile</a>';
        echo '<a href="add_event.php" id="add-event">Add Event</a>';
        // If logged in, display "Profile" and "Sign Out" links
        echo '<a href="login.php?logout=true">Sign Out</a>';
        echo '</div>';
    } else {
        echo '<div class="navbar">';
        // If not logged in, display "Log In" and "Sign Up" links
        echo '<a href="login.html">Log In</a>';
        echo '<a href="signup.html">Sign Up</a>';
        echo '</div>';
    }
?>