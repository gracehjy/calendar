<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Edit Event</title>
</head>
<body>
    <?php
        ini_set("session.cookie_httponly", 1);
        session_start();
        include("database.php");

        // get current event details
        $event_id = htmlentities($_GET["event_id"]);
        $query = $mysqli->prepare("SELECT title, date, start_time, end_time, tag FROM events WHERE event_id = ?");
        $query->bind_param("i", $event_id);
        $query->execute();
        $result = $query->get_result();
        $event = $result->fetch_assoc();
    ?>

    <h2>Edit event</h2>
    <form id="editForm">
        <label for="title">Title:</label>
        <input type="text" name="title" id="title" value="<?php echo $event['title']; ?>" required>
        <br><br>
        <label for="date">Date:</label>
        <input type="date" name="date" id="date" value="<?php echo $event['date']; ?>" required>
        <br><br>
        <label for="starttime">Start Time:</label>
        <input type="time" name="starttime" id="starttime" value="<?php echo $event['start_time']; ?>" required>
        <br><br>
        <label for="endtime">End Time:</label>
        <input type="time" name="endtime" id="endtime" value="<?php echo $event['end_time']; ?>" required>
        <br><br>
        <label for="tag">Tags:</label>
        <select name="tag" id="tag">
            <option value="">Select From Existing Tags</option>
        </select>
        <br><br>
        <label for="newtag">Create New Tag:</label>
        <input type="text" name="newtag" id="newtag" value="<?php echo $event['tag']; ?>">
        <br><br>
        <input type="submit" value="Edit Event">
        <br>
    </form>

    <a href="calendar.php" id="returnHome">Return to Calendar</a>
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
        // function to get existing tags
        function getTags() {
            fetch("gettags.php")
            .then(response => response.json())
            .then(data => {
                let selectTagDropdown = document.getElementById("tag");
                selectTagDropdown.innerHTML = '<option value="">Select tag or create new</option>';
                data.forEach(tag => {
                    let option = document.createElement("option");
                    option.value = tag;
                    option.textContent = tag;
                    selectTagDropdown.appendChild(option);
                });
            })
            .catch(error => console.error(error));
        }

        // Call getTags after DOM content is loaded
        getTags();
    });

        document.getElementById("editForm").addEventListener("submit", function(event) {
            event.preventDefault();

            let eventData = {
                'title': document.getElementById("title").value,
                'date': document.getElementById("date").value,
                'tag': document.getElementById("tag").value,
                'newtag': document.getElementById("newtag").value,
                'starttime': document.getElementById("starttime").value,
                'endtime': document.getElementById("endtime").value
            };

            fetch("update-event.php?event_id="+<?php echo $event_id; ?>, {
                method: "POST",
                body: JSON.stringify(eventData),
                headers: {
                    "Content-Type": "application/json"
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Event updated successfully");
                    window.location.href = "calendar.php"; 
                } else {
                    alert("Error: " + data.message); 
                }
            })
            .catch(error => console.error(error));
        });
    </script>
</body>
</html>
