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
        echo '<a href="#" id="profileLink">Profile</a>';
        echo '<a href="#" id="addeventLink">Add Event</a>';
        echo '<a href="#" id="logout">Sign Out</a>';
        echo '</div>';

        $username = $_SESSION["username"];
        echo "<h2> Hello, ".$username."!</h2>";
        ?>

        <!-- profile page links -->
        <p><a href="#" id="changeUserLink">Change Username</a></p>
        <p><a href='deleteaccount.php' onclick='return confirm("Are you sure you want to delete this account?")'>Delete Account</a></p>

        <script src="navbar.js"></script>
        <script>
         // add event listener for changeUserLink
         document.getElementById("changeUserLink").addEventListener("click", function(event) {
            event.preventDefault();

            fetch("changeuser.html")
                .then(response => response.text())
                .then(html => {
                    document.body.innerHTML = html;
                })
                .catch(error => console.error("Error fetching changeuser.html:", error));
        })
     </script>
    </body>
</html>