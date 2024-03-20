<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Events</title>
</head>
<body>
    <?php
        ini_set("session.cookie_httponly", 1);
        session_start();
        include("database.php");

        // check if the user is logged in
        if (!isset($_SESSION["username"])) {
            echo json_encode(array("success" => false, "message" => "Please log in first."));
            exit();
        }

        $user_id = htmlentities($_SESSION['user_id']);
        $username = htmlentities($_SESSION['username']);

        echo "<h2>Events for $username:</h2>";

        // get events
        $query = $mysqli->prepare("SELECT event_id, title, date, start_time, end_time, tag FROM events WHERE user_id = ?");
        $query->bind_param("i", $user_id);
        $query->execute();
        $result = $query->get_result();

        // display the events with options to edit, delete, and share each event
        if ($result->num_rows > 0) {
            echo "<ul>";
            while ($row = $result->fetch_assoc()) {
                // get event info
                $event_id = $row['event_id'];
                $title = $row['title'];
                $date = $row['date'];
                $start_time = $row['start_time'];
                $end_time = $row['end_time'];
                $tag = $row['tag'];

                echo "<li><a href='#' class='event-link' event-info='Event: $title\nDate: $date\nStart Time: $start_time\nEnd Time: $end_time\nTag: $tag'>$title</a>";
                echo "      [<a href='edit-event.php?event_id=$event_id'>Edit</a>]     [<a href='#' class='share-link' event-id='$event_id'>Share</a>]     [<a href='delete-event.php?event_id=$event_id'>Delete</a>]</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No events found.</p>";
        }

        $query->close();
        $mysqli->close();
    ?>

    <a href="calendar.php" id="returnHome">Return to Calendar</a>

    <script>
        document.querySelectorAll('.event-link').forEach(link => {
            link.addEventListener('click', function(event) {
                event.preventDefault();
                let event_info = this.getAttribute('event-info');
                alert(event_info);
            });
        });

        document.querySelectorAll('.share-link').forEach(link => {
            link.addEventListener('click', function(event){
                event.preventDefault();

                let event_id = this.getAttribute('event-id');
                let share_to_username = prompt("Enter the username you want to share this event with:");

                let data = {
                    'event_id': event_id,
                    'share_to_username': share_to_username
                }

                fetch('share-event.php',{
                    method: "POST",
                    body: JSON.stringify(data),
                    headers: {
                        "Content-Type": "application/json"
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Event shared successfully"); 
                    } else {
                        alert(data.message); 
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("An error occurred during sharing.");
                });
            })
        })
    </script>
</body>
</html>
