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
        session_start();
        include("navbar.php");
        include("database.php");

        // check if the user is logged in
        if (!isset($_SESSION["username"])) {
            header("Location: login.php");
            exit(); 
        }

        // check if the story_id is provided in the URL
        if (!isset($_GET["story_id"])) {
            echo "Invalid request.";
            exit();
        }

        $story_id = $_GET["story_id"];
        $user_id = $_SESSION["user_id"];

        // get existing story details
        $stmt = $mysqli->prepare("SELECT * FROM stories WHERE story_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $story_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows > 0) {
            $story = $result->fetch_assoc();
        } else {
            echo "You cannot edit this.";
            exit();
        }

        // update the story
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if(!hash_equals($_SESSION['token'], $_POST['token'])){
                die("Request forgery detected");
            }

            $newTitle = $_POST["title"];
            $newBody = $_POST["body"];
            $newLink = $_POST["link"];

            $stmt = $mysqli->prepare("UPDATE stories SET title = ?, body = ?, link = ? WHERE story_id = ?");
            $stmt->bind_param("sssi", $newTitle, $newBody, $newLink, $story_id);
            
            if ($stmt->execute()) {
                $stmt->close();
                echo "<p>Story updated successfully!</p>";

                // check if the link changed
                if ($newLink != $story['link']) {
                    $stmt = $mysqli->prepare("SELECT * FROM links WHERE story_id = ?");
                    $stmt->bind_param("i", $story_id);
                    $stmt->execute();
                    $linkResult = $stmt->get_result();
                    $stmt->close();

                    if ($linkResult->num_rows > 0) {
                        // update the current link
                        $stmt = $mysqli->prepare("UPDATE links SET link = ? WHERE story_id = ?");
                        $stmt->bind_param("si", $newLink, $story_id);
                        $stmt->execute();
                        $stmt->close();
                    } 
                    else {
                        // add the new link into the table
                        $stmt = $mysqli->prepare("INSERT INTO links (story_id, link) VALUES (?, ?)");
                        $stmt->bind_param("is", $story_id, $newLink);
                        $stmt->execute();
                        $stmt->close();
                    }
                }
        
                header("Location: user_stories.php");
                exit();
            }
            else {
                $stmt->close();
                echo  $mysqli->error;
            }
        }
    ?>

    <h2>Edit Story</h2>
    <form action="edit_story.php?story_id=<?php echo $story['story_id']; ?>" method="post">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" value="<?php echo $story['title']; ?>" required>

        <label for="body">Body:</label>
        <textarea id="body" name="body" required><?php echo $story['body']; ?></textarea>

        <label for="link">Link:</label>
        <input type="text" id="link" name="link" value="<?php echo $story['link']; ?>">
        <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>">

        <input type="submit" value="Update Story">
    </form>
</body>
</html>