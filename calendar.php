<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="calendar.css">
    <title>Calendar</title>
</head>

<body>
    <?php
        session_start();
        // include database connection script
        include("database.php");

        // check if the user is logged in
        if (isset($_SESSION["username"])) {  
            echo '<div class="navbar">';
                    // If logged in, display "Profile" and "Add Event" and "Sign Out" links
            echo '<a href="profile.php">Profile</a>';
            echo '<a href="addevent.html">Add Event</a>';
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
    
    <div class="calendar">
        <div class="calendar-main">
            <button type="button" id="previous">
                <<<
            </button>
            <div class="month-year" id="month-yearnum"></div>
            <button type="button" id="future">
                >>>
            </button>
        </div>

        <div class="calendar-weeks">
            <div>Sunday</div>
            <div>Monday</div>
            <div>Tuesday</div>
            <div>Wednesday</div>
            <div>Thursday</div>
            <div>Friday</div>
            <div>Saturday</div>
        </div>
        <div class="calendar-days"></div>
    </div>

    <script src="calendar.js"></script>
    <script>
        // Add event listener for the logout link
        document.getElementById('logout').addEventListener('click', function(event) {
            event.preventDefault();

            // Send a request to logout.php to log the user out
            fetch('logout.php')
                .then(response => response.json())
                .then(data => {
                    // Update the loggedIn status in sessionStorage
                    sessionStorage.setItem('loggedIn', data.loggedIn);
                    // Redirect the user to the login page
                    window.location.href = "login.html";
                })
                .catch(error => console.error('Error logging out:', error));
        });
        </script>
</body>