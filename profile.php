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
        include ("database.php");

        // check if the user is logged in, otherwise send them to login.php
        if (!isset($_SESSION["username"])) {
            header("Location: login.php");
            exit(); 
        }

        echo '<div class="navbar">';
        // If logged in, display "Profile" and "Add Event" and "Sign Out" links
        echo '<a href="profile.php">Profile</a>';
        echo '<a href="addevent.html">Add Event</a>';
        echo '<a href="#" id="logout">Sign Out</a>';
        echo '</div>';

        $username = $_SESSION["username"];
        echo "<h2> Hello, ".$username."!</h2>";
        ?>
        <p><a href="eventsList.php">View Upcoming Events</a></p>
        <p><a href="changeuser.html">Change Username</a></p>
        <p><a href='deleteaccount.php' onclick='return confirm("Are you sure you want to delete this account?")'>Delete Account</a></p>

        <p><a href="calendar.php" id="returnHome">Return to Calendar</a></p>
        <script>
        // if sign out is clicked, go to logout.php and update the loggedIn data
        document.getElementById('logout').addEventListener('click', function(event) {
            event.preventDefault();

            fetch('logout.php')
                .then(response => response.json())
                .then(data => {
                    sessionStorage.setItem('loggedIn', data.loggedIn);
                    window.location.href = "login.html";
                })
                .catch(error => console.error('Error logging out:', error));
        });
     </script>
    </body>
</html>